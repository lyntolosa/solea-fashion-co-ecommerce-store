<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', ['liveProducts' => $this->publicProducts()]);
    }

    public function shop(Request $request)
    {
        $search = strtolower(trim((string) $request->input('search', '')));
        $products = collect($this->publicProducts())
            ->when($search !== '', fn ($items) => $items->filter(fn (array $product) => str_contains(strtolower(($product['name'] ?? '') . ' ' . ($product['sku'] ?? '') . ' ' . ($product['category'] ?? '')), $search)))
            ->values()
            ->all();

        return view('shop', ['liveProducts' => $products, 'search' => $request->input('search', '')]);
    }

    public function cart()
    {
        $products = collect($this->publicProducts())->keyBy('slug');
        $cartInventory = collect($this->inventoryCatalog())->mapWithKeys(function (array $product) use ($products) {
            $liveProduct = $products->get($product['slug'], []);
            return [$product['slug'] => [
                'stock' => (int) ($product['stock'] ?? 0),
                'stock_by_size' => $product['stock_by_size'] ?? [],
                'status' => $product['status'] ?? 'Active',
                'thumbnail' => $liveProduct['gallery'][1] ?? $liveProduct['gallery'][0] ?? $liveProduct['image'] ?? '',
            ]];
        })->all();

        return view('cart', ['cartInventory' => $cartInventory]);
    }

    public function checkout()
    {
        $customer = session('customer');
        if ($customer && ! empty($customer['street_address']) && empty($customer['addresses'])) {
            $customer['addresses'] = [[
                'id' => 'default',
                'label' => 'Primary address',
                'street_address' => $customer['street_address'],
                'city' => $customer['city'] ?? '',
                'zip_code' => $customer['zip_code'] ?? '',
                'phone' => $customer['phone'] ?? '',
            ]];
            session()->put('customer', $customer);
        }

        $products = collect($this->publicProducts())->keyBy('slug');
        $cartInventory = collect($this->inventoryCatalog())->mapWithKeys(function (array $product) use ($products) {
            $liveProduct = $products->get($product['slug'], []);
            return [$product['slug'] => [
                'thumbnail' => $liveProduct['gallery'][1] ?? $liveProduct['gallery'][0] ?? $liveProduct['image'] ?? '',
            ]];
        })->all();

        return view('checkout', [
            'customer' => $customer,
            'savedAddresses' => $customer['addresses'] ?? [],
            'isReturningCustomer' => (bool) $customer,
            'cartInventory' => $cartInventory,
        ]);
    }

    public function completeCheckout(Request $request)
    {
        $isReturningCustomer = $request->session()->has('customer');
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:30'],
            'cart_json' => ['required', 'json'],
            'shipping' => ['required', 'in:standard,express'],
            'payment' => ['required', 'in:card,cod'],
            'card_number' => ['required_if:payment,card', 'nullable', 'string', 'max:25'],
            'expiry_date' => ['required_if:payment,card', 'nullable', 'string', 'max:7'],
            'cvv' => ['required_if:payment,card', 'nullable', 'digits_between:3,4'],
        ];
        $rules['username'] = $isReturningCustomer ? ['nullable', 'string', 'max:100'] : ['required', 'string', 'max:100'];
        $rules['password'] = $isReturningCustomer ? ['nullable', 'string', 'min:6', 'max:255'] : ['required', 'string', 'min:6', 'max:255', 'confirmed'];
        $request->validate($rules);

        $existingCustomer = $request->session()->get('customer', []);
        $address = [
            'street_address' => $request->input('street_address'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('zip_code'),
            'phone' => $request->input('phone'),
        ];
        $addresses = $existingCustomer['addresses'] ?? [];
        $addressExists = collect($addresses)->contains(fn (array $saved) => $saved['street_address'] === $address['street_address'] && $saved['city'] === $address['city'] && $saved['zip_code'] === $address['zip_code']);
        if (! $addressExists) {
            $addresses[] = array_merge($address, ['id' => 'address-' . Str::lower(Str::random(8)), 'label' => 'Saved address']);
        }
        $customerData = array_merge($existingCustomer, [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'name' => trim($request->input('first_name') . ' ' . $request->input('last_name')),
            'email' => $request->input('email'),
            'username' => $request->input('username') ?: ($existingCustomer['username'] ?? $request->input('email')),
            ...$address,
            'addresses' => $addresses,
        ]);
        if (! $isReturningCustomer && ! empty($request->input('password'))) {
            $customerData['password_hash'] = Hash::make($request->input('password'));
        }
        $request->session()->put('customer', $customerData);

        $cart = json_decode($request->input('cart_json'), true);
        if (! is_array($cart) || count($cart) === 0) {
            return back()->withErrors(['cart_json' => 'Your cart is empty. Please choose a product before checking out.'])->withInput();
        }

        $cart = collect($cart)->map(function (array $item) {
            return [
                'slug' => (string) ($item['slug'] ?? ''),
                'name' => (string) ($item['name'] ?? 'Product'),
                'price' => max(0, (float) ($item['price'] ?? 0)),
                'image' => (string) ($item['image'] ?? ''),
                'size' => (string) ($item['size'] ?? 'M'),
                'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
            ];
        })->values()->all();
        $subtotal = collect($cart)->sum(fn (array $item) => $item['price'] * $item['quantity']);
        $shippingCost = $request->input('shipping') === 'express' ? 300 : 100;
        $firstItem = $cart[0];

        $order = [
            'reference' => 'TL-' . Str::upper(Str::random(8)),
            'created_at' => now()->toIso8601String(),
            'date' => now()->format('M d, Y H:i'),
            'status' => 'Processing',
            'shipping' => ucfirst($request->input('shipping')),
            'payment' => $request->input('payment') === 'cod' ? 'Cash on Delivery' : 'Credit / Debit Card',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
            'items' => $cart,
            'item' => $firstItem['name'],
            'size' => $firstItem['size'],
            'quantity' => $firstItem['quantity'],
            'image' => $firstItem['image'],
            'customer_name' => trim($request->input('first_name') . ' ' . $request->input('last_name')),
            'customer_email' => $request->input('email'),
            'street_address' => $request->input('street_address'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('zip_code'),
            'phone' => $request->input('phone'),
        ];

        $request->session()->put('latest_order', $order);

        $customer = $request->session()->get('customer');
        $stockError = $this->commitOrderWithStock($order, $cart);
        if ($stockError) {
            return back()->withErrors(['cart_json' => $stockError])->withInput();
        }
        $this->rememberCustomer($customer);

        return redirect()->route('thank-you');
    }

    public function dashboard()
    {
        if (! session()->has('customer')) {
            return redirect()->route('login')->with('status', 'Please log in to access your customer dashboard.');
        }

        $customer = session('customer');
        $orders = collect($this->storeData()['orders'] ?? [])
            ->filter(fn (array $order) => strtolower((string) ($order['customer_email'] ?? '')) === strtolower((string) ($customer['email'] ?? '')))
            ->sortByDesc('created_at')
            ->values();
        $wishlistProducts = collect($this->publicProducts())
            ->whereIn('slug', $customer['wishlist'] ?? [])
            ->values();

        return view('dashboard', [
            'customer' => $customer,
            'order' => $orders->first(),
            'orders' => $orders,
            'activeOrderCount' => $orders->whereNotIn('status', ['Delivered', 'Cancelled'])->count(),
            'deliveredOrderCount' => $orders->where('status', 'Delivered')->count(),
            'wishlistProducts' => $wishlistProducts,
        ]);
    }

    public function dashboardData()
    {
        if (! session()->has('customer')) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $customer = session('customer');
        $orders = collect($this->storeData()['orders'] ?? [])
            ->filter(fn (array $order) => strtolower((string) ($order['customer_email'] ?? '')) === strtolower((string) ($customer['email'] ?? '')))
            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'orders' => $orders->all(),
            'activeOrderCount' => $orders->whereNotIn('status', ['Delivered', 'Cancelled'])->count(),
            'deliveredOrderCount' => $orders->where('status', 'Delivered')->count(),
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function cancelCustomerOrder(string $reference)
    {
        if (! session()->has('customer')) {
            return redirect()->route('login')->with('status', 'Please log in to manage your orders.');
        }

        $customer = session('customer');
        $store = $this->storeData();
        $index = collect($store['orders'] ?? [])->search(fn (array $order) => ($order['reference'] ?? '') === $reference);
        if ($index === false) abort(404);

        $order = $store['orders'][$index];
        if (strtolower((string) ($order['customer_email'] ?? '')) !== strtolower((string) ($customer['email'] ?? ''))) abort(403);
        if (! in_array($order['status'] ?? '', ['Pending Payment', 'Processing', 'Ready to Ship'], true)) {
            return back()->with('status', 'This order can no longer be cancelled because it has already shipped or completed.');
        }

        $inventory = collect($store['inventory'] ?? [])->keyBy('slug');
        $catalog = collect($this->inventoryCatalog($store))->keyBy('slug');
        foreach ($order['items'] ?? [] as $item) {
            $slug = (string) ($item['slug'] ?? '');
            $product = $catalog->get($slug);
            if (! $product) continue;
            $record = $inventory->get($slug, $product);
            $stockBySize = $this->normaliseVariationStock($record);
            $size = (string) ($item['size'] ?? 'M');
            $stockBySize[$size] = (int) ($stockBySize[$size] ?? 0) + (int) ($item['quantity'] ?? 0);
            $record['stock_by_size'] = $stockBySize;
            $record['stock'] = array_sum($stockBySize);
            $record['updated_at'] = now()->toIso8601String();
            $inventory->put($slug, $record);
        }

        $store['inventory'] = $inventory->values()->all();
        $store['orders'][$index] = array_merge($order, ['status' => 'Cancelled', 'updated_at' => now()->toIso8601String()]);
        $this->saveStoreData($store);

        return back()->with('status', 'Order #' . $reference . ' has been cancelled.');
    }

    public function toggleWishlist(Request $request, string $slug)
    {
        if (! $request->session()->has('customer')) {
            return redirect()->route('login')->with('status', 'Please log in to save products to your wishlist.');
        }

        abort_unless(collect($this->publicProducts())->contains('slug', $slug), 404);
        $customer = $request->session()->get('customer', []);
        $wishlist = collect($customer['wishlist'] ?? []);
        $saved = $wishlist->contains($slug);
        $customer['wishlist'] = $saved ? $wishlist->reject(fn (string $item) => $item === $slug)->values()->all() : $wishlist->push($slug)->unique()->values()->all();
        $request->session()->put('customer', $customer);
        $this->rememberCustomer($customer);

        return back()->with('status', $saved ? 'Product removed from your wishlist.' : 'Product added to your wishlist.');
    }

    public function logoutCustomer(Request $request)
    {
        $request->session()->forget(['customer', 'latest_order']);

        return redirect()->route('home');
    }

    public function updateCustomerProfile(Request $request)
    {
        if (! $request->session()->has('customer')) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $customer = array_merge($request->session()->get('customer', []), [
            ...$validated,
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
        ]);
        $addresses = $customer['addresses'] ?? [];
        $primaryIndex = collect($addresses)->search(fn (array $address) => ($address['id'] ?? '') === 'default');
        $primaryAddress = ['id' => 'default', 'label' => 'Primary address', 'street_address' => $validated['street_address'], 'city' => $validated['city'], 'zip_code' => $validated['zip_code'], 'phone' => $validated['phone']];
        if ($primaryIndex === false) {
            array_unshift($addresses, $primaryAddress);
        } else {
            $addresses[$primaryIndex] = array_merge($addresses[$primaryIndex], $primaryAddress);
        }
        $customer['addresses'] = $addresses;
        $request->session()->put('customer', $customer);
        $this->rememberCustomer($customer);

        return redirect()->route('dashboard', ['updated' => 'profile'])->with('status', 'Your account details have been updated.');
    }

    public function addCustomerAddress(Request $request)
    {
        if (! $request->session()->has('customer')) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
        $customer = $request->session()->get('customer', []);
        $customer['addresses'] = $customer['addresses'] ?? [];
        $customer['addresses'][] = array_merge($validated, ['id' => 'address-' . Str::lower(Str::random(8))]);
        $request->session()->put('customer', $customer);
        $this->rememberCustomer($customer);

        return redirect()->route('dashboard', ['updated' => 'address'])->with('status', 'Your new shipping address has been saved.');
    }

    public function deleteCustomerAddress(string $addressId)
    {
        if (! session()->has('customer')) {
            return redirect()->route('login');
        }

        $customer = session('customer');
        $addresses = collect($customer['addresses'] ?? []);
        if (! $addresses->contains(fn (array $address) => ($address['id'] ?? '') === $addressId)) {
            return back()->with('status', 'That saved address could not be found.');
        }

        $customer['addresses'] = $addresses
            ->reject(fn (array $address) => ($address['id'] ?? '') === $addressId)
            ->values()
            ->all();
        session()->put('customer', $customer);
        $this->rememberCustomer($customer);

        return redirect()->route('dashboard', ['updated' => 'address'])->with('status', 'The saved address has been deleted.');
    }

    public function updateCustomerSecurity(Request $request)
    {
        if (! $request->session()->has('customer')) {
            return redirect()->route('login');
        }

        if ($request->filled('password') || $request->filled('password_confirmation')) {
            return back()->with('demo_notice', 'This shop is for live demo only. Password changes are disabled for portfolio reviewers.');
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'max:255', 'confirmed'],
        ]);
        $customer = $request->session()->get('customer', []);
        $oldEmail = $customer['email'] ?? '';
        $customer['email'] = $validated['email'];
        $customer['username'] = $validated['email'];
        if (! empty($validated['password'])) {
            $customer['password_hash'] = Hash::make($validated['password']);
        }
        $request->session()->put('customer', $customer);

        $store = $this->storeData();
        $oldIndex = collect($store['customers'])->search(fn (array $item) => strtolower($item['email'] ?? '') === strtolower($oldEmail));
        $newIndex = collect($store['customers'])->search(fn (array $item) => strtolower($item['email'] ?? '') === strtolower($validated['email']));
        if ($oldIndex !== false) {
            $store['customers'][$oldIndex] = array_merge($store['customers'][$oldIndex], $customer);
            if ($oldEmail !== $validated['email'] && $newIndex !== false && $newIndex !== $oldIndex) {
                unset($store['customers'][$newIndex]);
                $store['customers'] = array_values($store['customers']);
            }
        } else {
            $store['customers'][] = array_merge($customer, ['created_at' => now()->toIso8601String()]);
        }
        $this->saveStoreData($store);

        return redirect()->route('dashboard', ['updated' => 'security'])->with('status', 'Your security settings have been updated.');
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $customer = [
            'first_name' => $request->input('name'),
            'last_name' => '',
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('email'),
            'password_hash' => Hash::make($request->input('password')),
        ];
        $request->session()->put('customer', $customer);
        $this->rememberCustomer($customer);

        return redirect()->route('dashboard')->with('status', 'Your Soléa Fashion Co. account is ready.');
    }

    public function contact()
    {
        return view('contact');
    }

    public function sendContactMessage(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'topic' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'order_number' => ['nullable', 'string', 'max:100'],
        ]);

        return redirect()->route('contact')->with('status', 'Your message has been sent. Our Customer Care team will reply within 1-2 business days.');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $customer = collect($this->storeData()['customers'])->first(fn (array $item) => strtolower($item['email'] ?? '') === strtolower($request->input('email')));
        if ($customer && ! empty($customer['password_hash']) && ! Hash::check($request->input('password'), $customer['password_hash'])) {
            return back()->withErrors(['email' => 'The email or password is incorrect.'])->withInput();
        }
        $customer ??= [
            'name' => ucfirst(strtok($request->input('email'), '@')),
            'email' => $request->input('email'),
            'username' => $request->input('email'),
        ];
        $request->session()->put('customer', $customer);
        $this->rememberCustomer($customer);

        return redirect()->route('dashboard')->with('status', 'Welcome back to Soléa Fashion Co.');
    }

    public function adminDashboard()
    {
        if (! session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $store = $this->storeData();
        $orders = collect($store['orders'])->sortByDesc('created_at')->values();
        $periodOrders = $orders->filter(fn (array $order) => empty($order['created_at']) || now()->parse($order['created_at'])->greaterThanOrEqualTo(now()->subDays(30)));
        $revenue = $periodOrders->sum(fn (array $order) => (float) ($order['total'] ?? 0));
        $orderCount = $periodOrders->count();
        $customerCount = collect($store['customers'])->filter(fn (array $customer) => empty($customer['created_at']) || now()->parse($customer['created_at'])->greaterThanOrEqualTo(now()->subDays(30)))->unique(fn (array $customer) => strtolower($customer['email'] ?? ''))->count();

        $stats = [
            ['label' => 'Total Revenue', 'value' => '&#8369;' . number_format($revenue, 2), 'change' => 'Live total', 'icon' => 'payments'],
            ['label' => 'Total Orders', 'value' => number_format($orderCount), 'change' => 'Live total', 'icon' => 'shopping_bag'],
            ['label' => 'Avg Order Value', 'value' => '&#8369;' . number_format($orderCount ? $revenue / $orderCount : 0, 2), 'change' => 'Live average', 'icon' => 'calculate'],
            ['label' => 'New Customers', 'value' => number_format($customerCount), 'change' => 'Last 30 days', 'icon' => 'group'],
        ];

        $stageNames = ['Pending Payment', 'Processing', 'Ready to Ship', 'Shipped / Transit'];
        $pipelineCounts = $periodOrders->groupBy(fn (array $order) => $order['status'] ?? 'Processing');
        $pipeline = collect($stageNames)->map(function (string $label) use ($pipelineCounts, $orderCount) {
            $key = match ($label) {
                'Pending Payment' => 'Pending Payment',
                'Ready to Ship' => 'Ready to Ship',
                'Shipped / Transit' => 'Shipped / Transit',
                default => 'Processing',
            };
            $count = $pipelineCounts->get($key, collect())->count();
            return ['label' => $label, 'count' => $count, 'width' => $orderCount ? min(100, ($count / $orderCount) * 100) : 0, 'color' => $label === 'Processing' ? 'bg-primary-container' : ($label === 'Pending Payment' ? 'bg-secondary' : 'bg-white')];
        })->all();

        $dailySales = $periodOrders->groupBy(fn (array $order) => now()->parse($order['created_at'] ?? now())->format('M d'))->map(fn ($items) => $items->sum(fn (array $order) => (float) ($order['total'] ?? 0)))->take(10);
        $maxSale = max(1, (float) $dailySales->max());
        $salesHeights = $dailySales->map(fn (float $amount) => max(4, round(($amount / $maxSale) * 100)))->values()->all();
        $salesLabels = $dailySales->keys()->values()->all();
        $urgentCount = $periodOrders->filter(fn (array $order) => in_array($order['status'] ?? 'Processing', ['Pending Payment', 'Processing'], true))->count();
        $recentOrders = $orders->take(10)->map(fn (array $order) => [
            'id' => '#' . $order['reference'],
            'date' => $order['date'] ?? now()->parse($order['created_at'] ?? now())->format('M d, Y H:i'),
            'customer' => $order['customer_name'] ?? ($order['customer_email'] ?? 'Guest'),
            'total' => '&#8369;' . number_format((float) ($order['total'] ?? 0), 2),
            'payment' => $order['payment'] ?? 'Pending',
            'fulfillment' => $order['status'] ?? 'Processing',
            'tone' => ($order['payment'] ?? '') === 'Cash on Delivery' ? 'secondary' : 'primary',
        ])->all();

        return view('admin-dashboard', compact('stats', 'pipeline', 'salesHeights', 'salesLabels', 'urgentCount', 'recentOrders'));
    }

    public function adminManager(Request $request, string $section)
    {
        $definitions = [
            'orders' => ['title' => 'Orders', 'description' => 'Review every order recorded from customer checkout.', 'icon' => 'shopping_bag'],
            'products' => ['title' => 'Products', 'description' => 'Manage the live product catalog.', 'icon' => 'inventory_2'],
            'customers' => ['title' => 'Customers', 'description' => 'View customers recorded from account activity.', 'icon' => 'group'],
            'discounts' => ['title' => 'Discounts', 'description' => 'Discount campaigns will appear here when configured.', 'icon' => 'local_offer'],
            'reviews' => ['title' => 'Reviews', 'description' => 'Customer reviews will appear here when submitted.', 'icon' => 'reviews'],
            'analytics' => ['title' => 'Analytics', 'description' => 'Live performance data from recorded orders and customers.', 'icon' => 'analytics'],
            'settings' => ['title' => 'Settings', 'description' => 'Store configuration and administrator preferences.', 'icon' => 'settings'],
            'developer' => ['title' => 'Developer Mode', 'description' => 'Inspect application status, routes, and developer tools.', 'icon' => 'code'],
        ];
        abort_unless(isset($definitions[$section]), 404);

        $store = $this->storeData();
        if ($section === 'developer') {
            return view('admin-developer', [
                'definition' => $definitions[$section],
                'settings' => $store['developer_settings'] ?? [],
            ]);
        }
        $rows = match ($section) {
            'orders' => collect($store['orders'])
                ->filter(function (array $order) use ($request) {
                    $search = strtolower(trim((string) $request->input('search', '')));
                    $status = (string) $request->input('status', '');
                    $searchable = strtolower(implode(' ', [
                        (string) ($order['reference'] ?? ''),
                        (string) ($order['customer_name'] ?? ''),
                        (string) ($order['customer_email'] ?? ''),
                    ]));

                    return ($search === '' || str_contains($searchable, $search))
                        && ($status === '' || ($order['status'] ?? 'Processing') === $status);
                })
                ->sortByDesc('created_at')->map(function (array $order) use ($store) {
                $customer = collect($store['customers'])->first(fn (array $item) => strtolower((string) ($item['email'] ?? '')) === strtolower((string) ($order['customer_email'] ?? '')));
                $address = $customer['addresses'][0] ?? $customer ?? [];
                $items = collect($order['items'] ?? [[
                    'name' => $order['item'] ?? 'Product',
                    'size' => $order['size'] ?? 'N/A',
                    'quantity' => $order['quantity'] ?? 1,
                    'price' => $order['total'] ?? 0,
                ]]);
                return [
                'reference' => $order['reference'] ?? '',
                'primary' => '#' . ($order['reference'] ?? 'Unknown'),
                'secondary' => ($order['customer_name'] ?? 'Guest') . ' · ' . ($order['customer_email'] ?? 'No email'),
                'detail' => trim(($order['street_address'] ?? $address['street_address'] ?? 'Address not recorded') . "\n" . ($order['city'] ?? $address['city'] ?? '') . ' ' . ($order['zip_code'] ?? $address['zip_code'] ?? '') . "\nPhone: " . ($order['phone'] ?? $address['phone'] ?? 'No phone')),
                'items' => $items->values()->all(),
                'recorded' => $order['date'] ?? 'Recorded order',
                'value' => '&#8369;' . number_format((float) ($order['total'] ?? 0), 2),
                'status' => $order['status'] ?? 'Processing',
                ];
            })->values()->all(),
            'customers' => collect($store['customers'])
                ->filter(function (array $customer) use ($request) {
                    $search = strtolower(trim((string) $request->input('search', '')));
                    $searchable = strtolower(implode(' ', [
                        (string) ($customer['name'] ?? ''),
                        (string) ($customer['email'] ?? ''),
                        (string) ($customer['phone'] ?? ''),
                        (string) ($customer['street_address'] ?? ''),
                        (string) ($customer['city'] ?? ''),
                    ]));

                    return ($search === '' || str_contains($searchable, $search))
                        && ((string) $request->input('status', '') === '' || (string) $request->input('status') === 'Active');
                })
                ->sortByDesc('created_at')->map(function (array $customer) use ($store) {
                $address = $customer['addresses'][0] ?? $customer;
                $customerOrders = collect($store['orders'] ?? [])->filter(fn (array $order) => strtolower((string) ($order['customer_email'] ?? '')) === strtolower((string) ($customer['email'] ?? '')));
                return [
                    'primary' => $customer['name'] ?? 'Customer',
                    'secondary' => $customer['email'] ?? 'No email recorded',
                    'detail' => trim(($address['street_address'] ?? 'Address not recorded') . ', ' . ($address['city'] ?? '') . ' · ' . ($customer['phone'] ?? $address['phone'] ?? 'No phone recorded')),
                    'recorded' => ! empty($customer['created_at']) ? now()->parse($customer['created_at'])->format('M d, Y H:i') : 'Recorded customer',
                    'value' => '&#8369;' . number_format((float) $customerOrders->sum('total'), 2) . ' spent',
                    'status' => 'Active',
                ];
            })->values()->all(),
            'discounts' => collect($store['discounts'] ?? [])
                ->sortByDesc('created_at')
                ->map(fn (array $discount) => [
                    'primary' => $discount['code'],
                    'secondary' => ($discount['type'] === 'percent' ? $discount['value'] . '% off' : '₱' . number_format((float) $discount['value'], 2) . ' off'),
                    'detail' => 'Minimum spend: ₱' . number_format((float) ($discount['minimum_spend'] ?? 0), 2) . ' · ' . (($discount['active'] ?? true) ? 'Active' : 'Inactive'),
                    'recorded' => ! empty($discount['created_at']) ? now()->parse($discount['created_at'])->format('M d, Y H:i') : 'Recorded voucher',
                    'value' => $discount['type'] === 'percent' ? $discount['value'] . '% discount' : '₱' . number_format((float) $discount['value'], 2) . ' discount',
                    'status' => ($discount['active'] ?? true) ? 'Active' : 'Inactive',
                ])->values()->all(),
            'products' => collect($this->inventoryCatalog())
                ->filter(fn (array $product) => ! $request->filled('search') || str_contains(strtolower($product['name'] . ' ' . $product['sku']), strtolower($request->input('search'))))
                ->filter(fn (array $product) => ! $request->filled('category') || $product['category'] === $request->input('category'))
                ->filter(fn (array $product) => ! $request->filled('status') || $product['status'] === $request->input('status'))
                ->sortBy(match ($request->input('sort', 'name')) {
                    'price_high' => fn (array $product) => -$product['price'],
                    'stock_low' => fn (array $product) => $product['stock'],
                    'newest' => fn (array $product) => -strtotime($product['updated_at'] ?? 'now'),
                    default => fn (array $product) => strtolower($product['name']),
                })->map(fn (array $product) => [
                'primary' => ucwords(strtolower($product['name'])),
                'slug' => $product['slug'],
                'secondary' => $product['sku'] . ' · ' . $product['category'],
                'detail' => $product['stock'] . ' in stock',
                'value' => '&#8369;' . number_format($product['price'], 2),
                'status' => $product['status'],
                'image' => str_starts_with((string) ($product['image'] ?? ''), 'inventory/') ? asset('storage/' . $product['image']) : ($product['image'] ?? ''),
            ])->values()->all(),
            default => [],
        };

        return view('admin-manager', [
            'section' => $section,
            'definition' => $definitions[$section],
            'rows' => $rows,
            'storeCounts' => ['orders' => count($store['orders']), 'customers' => count($store['customers']), 'products' => count($this->inventoryCatalog())],
            'productFilters' => [
                'search' => (string) $request->input('search', ''),
                'category' => (string) $request->input('category', ''),
                'status' => (string) $request->input('status', ''),
                'sort' => (string) $request->input('sort', 'name'),
                'categories' => collect($this->inventoryCatalog())->pluck('category')->unique()->sort()->values(),
            ],
            'orderFilters' => [
                'search' => (string) $request->input('search', ''),
                'status' => (string) $request->input('status', ''),
                'statuses' => ['Pending Payment', 'Processing', 'Ready to Ship', 'Shipped / Transit', 'Delivered', 'Cancelled'],
            ],
            'customerFilters' => [
                'search' => (string) $request->input('search', ''),
                'status' => (string) $request->input('status', ''),
            ],
        ]);
    }

    public function updateOrderStatus(Request $request, string $reference)
    {
        $data = $request->validate(['status' => ['required', 'in:Pending Payment,Processing,Ready to Ship,Shipped / Transit,Delivered,Cancelled']]);
        $store = $this->storeData();
        $index = collect($store['orders'])->search(fn (array $order) => ($order['reference'] ?? '') === $reference);
        if ($index === false) abort(404);
        $store['orders'][$index]['status'] = $data['status'];
        $store['orders'][$index]['updated_at'] = now()->toIso8601String();
        $this->saveStoreData($store);

        return back()->with('status', 'Order status updated to ' . $data['status'] . '.');
    }

    public function saveDeveloperSettings(Request $request)
    {
        $data = $request->validate([
            'environment' => ['required', 'in:local,staging,production'],
            'api_base_url' => ['nullable', 'url', 'max:255'],
            'webhook_url' => ['nullable', 'url', 'max:255'],
            'shipping_provider' => ['required', 'string', 'max:100'],
            'payment_gateway' => ['required', 'string', 'max:100'],
        ]);

        $store = $this->storeData();
        $store['developer_settings'] = array_merge($store['developer_settings'] ?? [], [
            'environment' => $data['environment'],
            'api_base_url' => $data['api_base_url'] ?? '',
            'webhook_url' => $data['webhook_url'] ?? '',
            'shipping_provider' => $data['shipping_provider'],
            'payment_gateway' => $data['payment_gateway'],
            'cod_enabled' => $request->boolean('cod_enabled'),
            'email_notifications' => $request->boolean('email_notifications'),
            'updated_at' => now()->toIso8601String(),
        ]);
        $this->saveStoreData($store);

        return back()->with('status', 'Developer settings saved successfully.');
    }

    public function createDiscount(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', 'regex:/^[A-Za-z0-9_-]+$/'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'minimum_spend' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($data['type'] === 'percent' && (float) $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage discounts cannot be greater than 100.'])->withInput();
        }

        $store = $this->storeData();
        $store['discounts'] = $store['discounts'] ?? [];
        $code = strtoupper($data['code']);
        if (collect($store['discounts'])->contains(fn (array $discount) => strtoupper((string) ($discount['code'] ?? '')) === $code)) {
            return back()->withErrors(['code' => 'That voucher code already exists.'])->withInput();
        }

        $store['discounts'][] = [
            'code' => $code,
            'type' => $data['type'],
            'value' => (float) $data['value'],
            'minimum_spend' => (float) ($data['minimum_spend'] ?? 0),
            'active' => true,
            'created_at' => now()->toIso8601String(),
        ];
        $this->saveStoreData($store);

        return back()->with('status', 'Voucher ' . $code . ' created successfully.');
    }

    public function bulkOrderAction(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:Processing,Ready to Ship,Shipped / Transit,Delivered,Cancelled,Pending Payment'],
            'references' => ['required', 'array', 'min:1'],
            'references.*' => ['required', 'string'],
            'search' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
        ]);
        $store = $this->storeData();
        $references = collect($data['references'])->unique()->values();
        $updated = 0;
        $store['orders'] = collect($store['orders'] ?? [])->map(function (array $order) use ($references, $data, &$updated) {
            if (! $references->contains((string) ($order['reference'] ?? ''))) return $order;
            $updated++;
            return array_merge($order, ['status' => $data['action'], 'updated_at' => now()->toIso8601String()]);
        })->values()->all();
        $this->saveStoreData($store);

        return redirect()->route('admin.orders', array_filter([
            'search' => $data['search'] ?? null,
            'status' => $data['status'] ?? null,
        ]))->with('status', $updated . ' order(s) updated to ' . $data['action'] . '.');
    }

    public function printWaybills(Request $request)
    {
        $store = $this->storeData();
        $search = strtolower(trim((string) $request->input('search', '')));
        $status = (string) $request->input('status', '');
        $references = collect($request->input('references', []))->map(fn ($reference) => (string) $reference)->filter()->values();

        $orders = collect($store['orders'] ?? [])
            ->filter(function (array $order) use ($search, $status, $references) {
                if ($references->isNotEmpty() && ! $references->contains((string) ($order['reference'] ?? ''))) return false;
                $searchable = strtolower(implode(' ', [
                    (string) ($order['reference'] ?? ''),
                    (string) ($order['customer_name'] ?? ''),
                    (string) ($order['customer_email'] ?? ''),
                ]));

                return ($search === '' || str_contains($searchable, $search))
                    && ($status === '' || ($order['status'] ?? 'Processing') === $status);
            })
            ->sortByDesc('created_at')
            ->map(function (array $order) use ($store) {
                $customer = collect($store['customers'] ?? [])->first(
                    fn (array $item) => strtolower((string) ($item['email'] ?? '')) === strtolower((string) ($order['customer_email'] ?? ''))
                );
                $address = $customer['addresses'][0] ?? $customer ?? [];
                $items = collect($order['items'] ?? [[
                    'name' => $order['item'] ?? 'Product',
                    'size' => $order['size'] ?? 'N/A',
                    'quantity' => $order['quantity'] ?? 1,
                    'price' => $order['total'] ?? 0,
                ]])->map(fn (array $item) => [
                    'name' => $item['name'] ?? 'Product',
                    'size' => $item['size'] ?? 'N/A',
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'price' => (float) ($item['price'] ?? 0),
                ])->values()->all();

                return [
                    'reference' => $order['reference'] ?? 'Unknown',
                    'customer_name' => $order['customer_name'] ?? 'Guest customer',
                    'customer_email' => $order['customer_email'] ?? 'No email recorded',
                    'street_address' => $order['street_address'] ?? $address['street_address'] ?? 'Address not recorded',
                    'city' => $order['city'] ?? $address['city'] ?? '',
                    'zip_code' => $order['zip_code'] ?? $address['zip_code'] ?? '',
                    'phone' => $order['phone'] ?? $address['phone'] ?? 'No phone recorded',
                    'date' => $order['date'] ?? now()->parse($order['created_at'] ?? now())->format('M d, Y H:i'),
                    'payment' => $order['payment'] ?? 'Pending',
                    'status' => $order['status'] ?? 'Processing',
                    'total' => (float) ($order['total'] ?? 0),
                    'items' => $items,
                ];
            })->values();

        return view('admin-waybills', compact('orders', 'search', 'status'));
    }

    public function adminLogin()
    {
        return view('admin-login');
    }

    public function authenticateAdmin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if (
            ! hash_equals((string) env('ADMIN_EMAIL'), strtolower($request->input('email'))) ||
            ! Hash::check($request->input('password'), (string) env('ADMIN_PASSWORD_HASH'))
        ) {
            return back()->withErrors(['email' => 'The admin email or password is incorrect.'])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);

        return redirect()->route('admin.dashboard')->with('status', 'Welcome to the Soléa Fashion Co. admin dashboard.');
    }

    public function logoutAdmin(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function adminInventory(Request $request)
    {
        if (! session('admin_authenticated')) {
            return redirect()->route('admin.login');
        }

        $allProducts = collect($this->inventoryCatalog());
        $search = trim((string) $request->input('search', ''));
        $category = (string) $request->input('category', '');
        $status = (string) $request->input('status', '');
        $sort = (string) $request->input('sort', 'name');
        $products = $allProducts
            ->filter(fn (array $product) => $search === '' || str_contains(strtolower($product['name'] . ' ' . $product['sku']), strtolower($search)))
            ->filter(fn (array $product) => $category === '' || $product['category'] === $category)
            ->filter(fn (array $product) => $status === '' || $product['status'] === $status)
            ->sortBy(match ($sort) {
                'price_high' => fn (array $product) => -$product['price'],
                'stock_low' => fn (array $product) => $product['stock'],
                'newest' => fn (array $product) => -strtotime($product['updated_at'] ?? $product['created_at'] ?? 'now'),
                default => fn (array $product) => strtolower($product['name']),
            })
            ->values();
        $products = $products->map(fn (array $product) => array_merge($product, [
            'image' => str_starts_with((string) ($product['image'] ?? ''), 'inventory/') ? asset('storage/' . $product['image']) : ($product['image'] ?? ''),
        ]));

        $stats = [
            'total' => $allProducts->count(),
            'active' => $allProducts->where('status', 'Active')->count(),
            'low_stock' => $allProducts->whereBetween('stock', [1, 19])->count(),
            'out_of_stock' => $allProducts->where('stock', 0)->count(),
        ];

        return view('admin-inventory', [
            'products' => $products,
            'stats' => $stats,
            'categories' => collect($this->storeData()['categories'] ?? [])->merge($allProducts->pluck('category'))->unique()->sort()->values(),
            'filters' => compact('search', 'category', 'status', 'sort'),
            'canUndo' => count($this->storeData()['inventory_history'] ?? []) > 0,
            'canRedo' => count($this->storeData()['inventory_redo'] ?? []) > 0,
        ]);
    }

    public function adminLibrary()
    {
        $files = collect(Storage::disk('public')->files('inventory'))->map(fn (string $path) => [
            'path' => $path,
            'url' => asset('storage/' . $path),
            'name' => basename($path),
        ])->values();

        return view('admin-library', compact('files'));
    }

    public function uploadLibraryImage(Request $request)
    {
        $request->validate(['image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]);
        $path = $request->file('image')->store('inventory', 'public');

        return back()->with('library_status', 'Thumbnail uploaded to the image library.')->with('uploaded_image', asset('storage/' . $path));
    }

    public function undoInventory()
    {
        $store = $this->storeData();
        $history = $store['inventory_history'] ?? [];
        if (! $history) return back();
        $current = ['inventory' => $store['inventory'] ?? [], 'categories' => $store['categories'] ?? [], 'removed_inventory' => $store['removed_inventory'] ?? []];
        $previous = array_pop($history);
        $store['inventory_history'] = $history;
        $store['inventory_redo'] = array_merge($store['inventory_redo'] ?? [], [$current]);
        $store = array_merge($store, $previous);
        $this->saveStoreData($store);
        return back()->with('inventory_status', 'Last inventory change undone.');
    }

    public function redoInventory()
    {
        $store = $this->storeData();
        $redo = $store['inventory_redo'] ?? [];
        if (! $redo) return back();
        $current = ['inventory' => $store['inventory'] ?? [], 'categories' => $store['categories'] ?? [], 'removed_inventory' => $store['removed_inventory'] ?? []];
        $next = array_pop($redo);
        $store['inventory_redo'] = $redo;
        $store['inventory_history'] = array_merge($store['inventory_history'] ?? [], [$current]);
        $store = array_merge($store, $next);
        $this->saveStoreData($store);
        return back()->with('inventory_status', 'Last inventory change restored.');
    }

    public function createInventoryProduct(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:80'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Active,Draft,Scheduled,Archived'],
            'scheduled_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        $store = $this->storeData();
        $this->recordInventoryChange($store);
        $data['image'] = $request->hasFile('image') ? $request->file('image')->store('inventory', 'public') : '';
        $products = $store['inventory'] ?? [];
        $slug = Str::slug($data['name']);
        if (collect($this->inventoryCatalog())->contains(fn (array $item) => strtolower($item['sku']) === strtolower($data['sku']) || $item['slug'] === $slug)) {
            return back()->withErrors(['sku' => 'The SKU or product name already exists.'])->withInput();
        }
        $data['stock_by_size'] = $this->distributeStock((int) $data['stock'], ['S', 'M', 'L', 'XL']);
        $products[] = array_merge($data, ['slug' => $slug, 'variants' => '4 Sizes', 'created_at' => now()->toIso8601String(), 'updated_at' => now()->toIso8601String()]);
        $store['inventory'] = $products;
        $this->saveStoreData($store);
        return back()->with('inventory_status', 'Product added successfully.');
    }

    public function createInventoryCategory(Request $request)
    {
        $data = $request->validate(['category' => ['required', 'string', 'max:80']]);
        $store = $this->storeData();
        $this->recordInventoryChange($store);
        $category = trim($data['category']);
        $store['categories'] = collect($store['categories'] ?? [])->push($category)->unique()->sort()->values()->all();
        $this->saveStoreData($store);

        return back()->with('inventory_status', 'Category added: ' . $category . '.');
    }

    public function updateInventoryProduct(Request $request, string $slug)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'detail_name' => ['nullable', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:80'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Active,Draft,Scheduled,Archived'],
            'scheduled_at' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:3000'],
            'details' => ['nullable', 'string', 'max:5000'],
            'materials' => ['nullable', 'string', 'max:3000'],
            'sizing_guide' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery' => ['nullable', 'array', 'max:8'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'stock_by_size' => ['nullable', 'array'],
            'stock_by_size.*' => ['nullable', 'integer', 'min:0'],
        ]);
        $store = $this->storeData();
        $this->recordInventoryChange($store);
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('inventory', 'public');
        else unset($data['image']);
        if ($request->hasFile('gallery')) $data['gallery'] = collect($request->file('gallery'))->map(fn ($file) => $file->store('inventory/gallery', 'public'))->values()->all();
        else unset($data['gallery']);
        $inventory = collect($store['inventory'] ?? [])->keyBy('slug');
        $index = $inventory->search(fn (array $item) => $item['slug'] === $slug);
        if ($index === false) {
            $inventory = collect($this->inventoryCatalog());
            $inventory = $inventory->keyBy('slug');
            $index = $inventory->search(fn (array $item) => $item['slug'] === $slug);
        }
        if ($index === false) abort(404);
        $current = $inventory->get($index);
        if (is_array($data['stock_by_size'] ?? null)) {
            $data['stock_by_size'] = $this->normaliseVariationStock(['stock_by_size' => $data['stock_by_size']]);
            $data['stock'] = array_sum($data['stock_by_size']);
        }
        $data['detail_name'] = $data['detail_name'] ?? ($current['detail_name'] ?? $data['name'] ?? $current['name'] ?? '');
        $inventory->put($index, array_merge($current, $data, ['updated_at' => now()->toIso8601String()]));
        $store['inventory'] = $inventory->values()->all();
        $this->saveStoreData($store);
        return back()->with('inventory_status', 'Product updated successfully.');
    }

    public function editAdminProduct(string $slug)
    {
        $product = collect($this->inventoryCatalog())->firstWhere('slug', $slug);
        abort_unless($product, 404);

        return view('admin-product-edit', [
            'product' => $product,
            'categories' => collect($this->inventoryCatalog())->pluck('category')->unique()->sort()->values(),
        ]);
    }

    public function deleteInventoryProduct(string $slug)
    {
        $store = $this->storeData();
        $this->recordInventoryChange($store);
        $inventory = collect($store['inventory'] ?? []);
        $catalog = collect($this->inventoryCatalog());
        $exists = $inventory->contains(fn (array $item) => $item['slug'] === $slug) || $catalog->contains(fn (array $item) => $item['slug'] === $slug);
        abort_unless($exists, 404);
        $store['inventory'] = $inventory->reject(fn (array $item) => $item['slug'] === $slug)->values()->all();
        $store['removed_inventory'] = array_values(array_unique(array_merge($store['removed_inventory'] ?? [], [$slug])));
        $this->saveStoreData($store);
        return back()->with('inventory_status', 'Product removed from inventory.');
    }

    public function updateInventoryStock(Request $request, string $slug)
    {
        $data = $request->validate(['stock' => ['nullable', 'integer', 'min:0'], 'stock_by_size' => ['nullable', 'array'], 'stock_by_size.*' => ['nullable', 'integer', 'min:0']]);
        $product = collect($this->inventoryCatalog())->firstWhere('slug', $slug);
        if (! $product) abort(404);
        $stockBySize = $this->normaliseVariationStock($product);
        if (is_array($data['stock_by_size'] ?? null)) {
            foreach (array_keys($stockBySize) as $size) $stockBySize[$size] = max(0, (int) ($data['stock_by_size'][$size] ?? 0));
        } elseif (array_key_exists('stock', $data)) {
            $stockBySize = $this->distributeStock((int) $data['stock'], array_keys($stockBySize));
        }
        return $this->updateInventoryField($slug, ['stock_by_size' => $stockBySize, 'stock' => array_sum($stockBySize)], 'Variation stock levels updated.');
    }

    public function updateInventoryStatus(Request $request, string $slug)
    {
        $request->validate(['status' => ['required', 'in:Active,Draft,Scheduled,Archived']]);
        return $this->updateInventoryField($slug, ['status' => $request->input('status')], 'Product status updated.');
    }

    public function bulkInventoryAction(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,draft,scheduled,archive,delete'],
            'slugs' => ['required', 'array', 'min:1'],
            'slugs.*' => ['required', 'string'],
        ]);
        $store = $this->storeData();
        $this->recordInventoryChange($store);
        $inventory = collect($store['inventory'] ?? [])->keyBy('slug');
        $slugs = collect($data['slugs'])->unique()->values();

        if ($data['action'] === 'delete') {
            $inventory = $inventory->reject(fn (array $item, string $slug) => $slugs->contains($slug));
            $store['removed_inventory'] = array_values(array_unique(array_merge($store['removed_inventory'] ?? [], $slugs->all())));
            $message = $slugs->count() . ' product(s) removed from inventory.';
        } else {
            $status = match ($data['action']) {
                'activate' => 'Active',
                'draft' => 'Draft',
                'scheduled' => 'Scheduled',
                'archive' => 'Archived',
            };
            $inventory = $inventory->map(function (array $item, string $slug) use ($slugs, $status) {
                return $slugs->contains($slug) ? array_merge($item, ['status' => $status, 'updated_at' => now()->toIso8601String()]) : $item;
            });
            $message = $slugs->count() . ' product(s) updated to ' . $status . '.';
        }

        $store['inventory'] = $inventory->values()->all();
        $this->saveStoreData($store);
        return back()->with('inventory_status', $message);
    }

    public function importInventory(Request $request)
    {
        $request->validate(['inventory_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048']]);
        $handle = fopen($request->file('inventory_file')->getRealPath(), 'r');
        $headers = array_map(fn ($header) => strtolower(trim((string) $header)), fgetcsv($handle) ?: []);
        $required = ['name', 'sku', 'category', 'price', 'stock', 'status'];
        if (array_diff($required, $headers)) return back()->withErrors(['inventory_file' => 'CSV headers must include: ' . implode(', ', $required)]);
        $store = $this->storeData();
        $this->recordInventoryChange($store);
        $inventory = collect($store['inventory'] ?? [])->keyBy('slug');
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) continue;
            $item = array_combine($headers, array_pad($row, count($headers), ''));
            $validated = validator($item, ['name' => 'required|string|max:120', 'sku' => 'required|string|max:50', 'category' => 'required|string|max:80', 'price' => 'required|numeric|min:0', 'stock' => 'required|integer|min:0', 'status' => 'required|in:Active,Draft,Scheduled,Archived', 'image' => 'nullable|url|max:1000'])->validate();
            $slug = Str::slug($validated['name']);
            $inventory->put($slug, array_merge($inventory->get($slug, ['slug' => $slug, 'variants' => '1 Size', 'created_at' => now()->toIso8601String()]), $validated, ['slug' => $slug, 'updated_at' => now()->toIso8601String()]));
            $count++;
        }
        fclose($handle);
        $store['inventory'] = $inventory->values()->all();
        $this->saveStoreData($store);
        return back()->with('inventory_status', $count . ' inventory product(s) imported.');
    }

    public function exportInventory()
    {
        $products = $this->inventoryCatalog();
        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['name', 'sku', 'category', 'price', 'stock', 'status', 'image']);
            foreach ($products as $product) fputcsv($handle, [$product['name'], $product['sku'], $product['category'], $product['price'], $product['stock'], $product['status'], $product['image']]);
            fclose($handle);
        }, 'threadlab-inventory-' . now()->format('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
    }

    private function updateInventoryField(string $slug, array $changes, string $message)
    {
        $store = $this->storeData();
        $this->recordInventoryChange($store);
        $inventory = collect($store['inventory'] ?? [])->keyBy('slug');
        $index = $inventory->has($slug) ? $slug : false;
        if ($index === false) {
            $base = collect($this->inventoryCatalog())->firstWhere('slug', $slug);
            if (! $base) abort(404);
            $inventory->put($slug, $base);
            $index = $slug;
        }
        $inventory->put($index, array_merge($inventory->get($index), $changes, ['updated_at' => now()->toIso8601String()]));
        $store['inventory'] = $inventory->values()->all();
        $this->saveStoreData($store);
        return back()->with('inventory_status', $message);
    }

    private function recordInventoryChange(array &$store): void
    {
        $snapshot = ['inventory' => $store['inventory'] ?? [], 'categories' => $store['categories'] ?? [], 'removed_inventory' => $store['removed_inventory'] ?? []];
        $store['inventory_history'] = array_slice(array_merge($store['inventory_history'] ?? [], [$snapshot]), -30);
        $store['inventory_redo'] = [];
    }

    private function commitOrderWithStock(array $order, array $cart): ?string
    {
        $path = Storage::disk('local')->path('store-data.json');
        $handle = fopen($path, 'c+');
        if (! $handle || ! flock($handle, LOCK_EX)) return 'The order could not be completed. Please try again.';

        try {
            rewind($handle);
            $raw = stream_get_contents($handle);
            $store = is_string($raw) && $raw !== '' ? json_decode($raw, true) : [];
            $store = is_array($store) ? array_merge(['orders' => [], 'customers' => [], 'inventory' => [], 'removed_inventory' => [], 'categories' => []], $store) : $this->storeData();
            $catalog = collect($this->inventoryCatalog($store))->keyBy('slug');
            $inventory = collect($store['inventory'] ?? [])->keyBy('slug');

            $requested = collect($cart)->groupBy(fn (array $item) => ($item['slug'] ?? '') . '|' . ($item['size'] ?? 'M'))->map(fn ($items) => $items->sum('quantity'));
            foreach ($requested as $key => $quantity) {
                [$slug, $size] = array_pad(explode('|', $key, 2), 2, 'M');
                $item = collect($cart)->first(fn (array $cartItem) => ($cartItem['slug'] ?? '') === $slug && ($cartItem['size'] ?? 'M') === $size);
                $product = $catalog->get($slug);
                if (! $product || ($product['status'] ?? 'Active') !== 'Active') return $item['name'] . ' is no longer available.';
                $stockBySize = $this->normaliseVariationStock($product);
                $available = (int) ($stockBySize[$size] ?? 0);
                if ((int) $quantity > $available) return $item['name'] . ' has only ' . $available . ' item(s) left in size ' . $size . '. Please update your cart.';
                $record = $inventory->get($slug, $product);
                $record['stock_by_size'] = $this->normaliseVariationStock($record);
                $record['stock_by_size'][$size] = $available - (int) $quantity;
                $record['stock'] = array_sum($record['stock_by_size']);
                $record['updated_at'] = now()->toIso8601String();
                $inventory->put($slug, $record);
            }

            $store['inventory'] = $inventory->values()->all();
            $store['orders'][] = $order;
            $encoded = json_encode($store, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, $encoded);
            fflush($handle);
            return null;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    private function inventoryCatalog(?array $sourceStore = null): array
    {
        $store = $sourceStore ?? $this->storeData();
        $workingImages = [
            'essential-black-tee' => '/images/solea-terracotta-wrap-dress.png',
            'studio-white-tee' => '/images/solea-ivory-linen-blouse.png',
            'graphic-logo-tee' => '/images/solea-cocoa-tailored-blazer.png',
            'oversized-charcoal-tee' => '/images/solea-dusty-rose-cardigan.png',
            'tech-wear-tactical-tee' => '/images/solea-sand-utility-overshirt.png',
            'vintage-wash-renegade-tee' => '/images/solea-plum-satin-skirt.png',
        ];
        $workingGalleries = [
            'essential-black-tee' => ['/images/solea-terracotta-wrap-dress-alt.png', '/images/solea-terracotta-wrap-dress-hanging.png'],
            'studio-white-tee' => ['/images/solea-ivory-linen-blouse-alt.png', '/images/solea-ivory-linen-blouse-hanging.png'],
            'graphic-logo-tee' => ['/images/solea-cocoa-tailored-blazer-alt.png', '/images/solea-cocoa-tailored-blazer-hanging.png'],
            'oversized-charcoal-tee' => ['/images/solea-dusty-rose-cardigan-alt.png', '/images/solea-dusty-rose-cardigan-hanging.png'],
            'tech-wear-tactical-tee' => ['/images/solea-sand-utility-overshirt-alt.png', '/images/solea-sand-utility-overshirt-hanging.png'],
            'vintage-wash-renegade-tee' => ['/images/solea-plum-satin-skirt-alt.png', '/images/solea-plum-satin-skirt-hanging.png'],
        ];
        $stock = [120, 12, 0, 88, 45, 8];
        $status = ['Active', 'Active', 'Active', 'Scheduled', 'Draft', 'Active'];
        $defaults = collect($this->products())->values()->map(function (array $product, int $index) use ($stock, $status, $workingImages) {
            return array_merge($product, ['image' => $workingImages[$product['slug']] ?? $product['image'], 'sku' => 'KIN-TEE-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT), 'category' => $index === 4 ? 'Tech-Wear' : 'Apparel', 'variants' => ($index === 2 ? 4 : ($index === 4 ? 6 : 5)) . ' Sizes', 'price' => (float) preg_replace('/[^0-9.]/', '', html_entity_decode($product['sale_price'])), 'stock' => $stock[$index], 'status' => $status[$index], 'created_at' => now()->subDays(6 - $index)->toIso8601String(), 'updated_at' => now()->subDays(6 - $index)->toIso8601String()]);
        });
        $removed = $store['removed_inventory'] ?? [];
        $catalog = $defaults->reject(fn (array $item) => in_array($item['slug'], $removed, true))->keyBy('slug')->merge(collect($store['inventory'] ?? [])->keyBy('slug'));
        return $catalog->map(function (array $item) use ($workingGalleries) {
            if (empty($item['gallery'])) $item['gallery'] = $workingGalleries[$item['slug']] ?? [];
            $item['stock_by_size'] = $this->normaliseVariationStock($item);
            $item['stock'] = array_sum($item['stock_by_size']);
            $item['variants'] = count($item['stock_by_size']) . ' Sizes';
            return $item;
        })->values()->all();
    }

    private function normaliseVariationStock(array $product): array
    {
        $sizes = ['S', 'M', 'L', 'XL'];
        $stored = $product['stock_by_size'] ?? null;
        if (! is_array($stored)) return $this->distributeStock((int) ($product['stock'] ?? 0), $sizes);

        $stock = [];
        foreach ($sizes as $size) $stock[$size] = max(0, (int) ($stored[$size] ?? 0));
        return $stock;
    }

    private function distributeStock(int $total, array $sizes): array
    {
        $sizes = array_values($sizes);
        $count = max(1, count($sizes));
        $base = intdiv(max(0, $total), $count);
        $remainder = max(0, $total) % $count;
        $stock = [];
        foreach ($sizes as $index => $size) $stock[$size] = $base + ($index < $remainder ? 1 : 0);
        return $stock;
    }

    private function publicProducts(): array
    {
        $base = collect($this->products())->keyBy('slug');
        return collect($this->inventoryCatalog())
            ->filter(function (array $product): bool {
                $status = $product['status'] ?? 'Active';

                if ($status === 'Active') return true;
                if ($status !== 'Scheduled' || empty($product['scheduled_at'])) return false;

                try {
                    return Carbon::parse($product['scheduled_at'])->isPast();
                } catch (\Throwable) {
                    return false;
                }
            })
            ->map(function (array $item) use ($base) {
                $product = $base->has($item['slug']) ? array_merge($base->get($item['slug']), $item) : $item;
                $product['category'] = match ($product['slug']) {
                    'essential-black-tee', 'vintage-wash-renegade-tee' => 'basic',
                    'oversized-charcoal-tee', 'tech-wear-tactical-tee' => 'oversized',
                    default => 'minimal',
                };
                $product['price_value'] = (float) ($item['price'] ?? 0);
                $product['price'] = '&#8369;' . number_format($product['price_value'], 2);
                $product['detail_name'] = $product['detail_name'] ?? $product['name'];
                $product['sale_price'] = $product['sale_price'] ?? $product['price'];
                $product['badge'] = $product['badge'] ?? 'NEW RELEASE';
                $product['details'] = $product['details'] ?? 'A Soléa Fashion Co. product from the current live catalog.';
                $product['image'] = str_starts_with((string) ($product['image'] ?? ''), 'inventory/') ? asset('storage/' . $product['image']) : ($product['image'] ?? '');
                $product['gallery'] = collect($product['gallery'] ?? [])->map(fn ($image) => str_starts_with((string) $image, 'inventory/') ? asset('storage/' . $image) : $image)->values()->all();
                $product['materials'] = $product['materials'] ?? 'Made from heavyweight 240GSM combed cotton with a soft, breathable finish.';
                $product['sizing_guide'] = $product['sizing_guide'] ?? 'For the intended fit, choose your usual size. Size up for a relaxed silhouette.';
                return $product;
            })->values()->all();
    }

    public function thankYou()
    {
        $order = session('latest_order');

        if (! is_array($order) || empty($order['reference'])) {
            return redirect()->route('shop')->with('status', 'Complete a purchase to view the order confirmation.');
        }

        return view('thank-you', ['order' => $order]);
    }

    public function product(string $slug)
    {
        $workingImages = [
            'essential-black-tee' => '/images/solea-terracotta-wrap-dress.png',
            'studio-white-tee' => '/images/solea-ivory-linen-blouse.png',
            'graphic-logo-tee' => '/images/solea-cocoa-tailored-blazer.png',
            'oversized-charcoal-tee' => '/images/solea-dusty-rose-cardigan.png',
            'tech-wear-tactical-tee' => '/images/solea-sand-utility-overshirt.png',
            'vintage-wash-renegade-tee' => '/images/solea-plum-satin-skirt.png',
        ];

        $products = collect($this->publicProducts());

        $product = $products->firstWhere('slug', $slug);

        abort_unless($product, 404);

        $relatedProducts = $products
            ->reject(fn (array $item) => $item['slug'] === $slug)
            ->take(3)
            ->values()
            ->all();

        return view('product', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'business_type' => ['required', 'string', 'max:255'],
        ]);

        return 'Generated successfully';
    }

    private function storeData(): array
    {
        if (! Storage::disk('local')->exists('store-data.json')) {
            return ['orders' => [], 'customers' => [], 'inventory' => [], 'removed_inventory' => [], 'categories' => [], 'discounts' => []];
        }

        $data = json_decode(Storage::disk('local')->get('store-data.json'), true);

        return is_array($data) ? array_merge(['orders' => [], 'customers' => [], 'inventory' => [], 'removed_inventory' => [], 'categories' => [], 'discounts' => []], $data) : ['orders' => [], 'customers' => [], 'inventory' => [], 'removed_inventory' => [], 'categories' => [], 'discounts' => []];
    }

    private function saveStoreData(array $data): void
    {
        Storage::disk('local')->put('store-data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function rememberCustomer(array $customer): void
    {
        if (empty($customer['email'])) {
            return;
        }

        $store = $this->storeData();
        $index = collect($store['customers'])->search(fn (array $item) => strtolower($item['email'] ?? '') === strtolower($customer['email']));
        if ($index === false) {
            $store['customers'][] = array_merge($customer, ['created_at' => now()->toIso8601String()]);
        } else {
            $store['customers'][$index] = array_merge($store['customers'][$index], $customer);
        }
        $this->saveStoreData($store);
    }

    private function products(): array
    {
        return [
            [
                'slug' => 'essential-black-tee',
                'series' => 'CORE SERIES',
                'detail_series' => 'SOLÉA BASICS',
                'name' => 'TERRACOTTA WRAP DRESS',
                'detail_name' => 'TERRACOTTA WRAP DRESS',
                'price' => '&#8369;799.00',
                'sale_price' => '&#8369;799',
                'compare_price' => '&#8369;1,200',
                'badge' => 'V-01 ACCESS',
                'description' => 'A softly gathered linen wrap dress in a warm terracotta tone, designed for an effortless, polished silhouette.',
                'details' => 'The Terracotta Wrap Dress brings an elegant wrap waist, long sleeves, and a fluid midi length together in breathable linen with a softly textured finish.',
                'image' => 'https://lh3.googleusercontent.com/aida/AP1WRLvhHGiYFg084ZoR-j4Ig8PZ8cU9N9k-jCHj4BnD06yeOgxnKNVgB98RE7JQBeKYsJziuvHJ0MPq9BrgSC2GmX2ajKZy4h4GMo0h-mGIP6evxtw69GQznyUWhcDLxk32mPdhDQeFYrIzz1HX4J98MFGSObTfMnti_QvSyAmRdCesCbQh5ShzciTX8EptqgoimSh-6uvoSrbuFfNac58jMjwSavjRp6IdN9_vtRRTzdmXOdKCDEoDF-FOoA',
            ],
            [
                'slug' => 'studio-white-tee',
                'series' => 'MINIMALIST',
                'detail_series' => 'STUDIO BASICS',
                'name' => 'IVORY LINEN BLOUSE',
                'detail_name' => 'IVORY LINEN BLOUSE',
                'price' => '&#8369;799.00',
                'sale_price' => '&#8369;799',
                'compare_price' => '&#8369;1,100',
                'badge' => 'S-02 RELEASE',
                'description' => 'A relaxed ivory linen blouse with rolled cuffs and an easy, refined drape for everyday styling.',
                'details' => 'The Ivory Linen Blouse is cut in breathable linen with a softly structured collar, button front, and rollable cuffs that move easily from workdays to weekends.',
                'image' => 'https://lh3.googleusercontent.com/aida/AP1WRLvsraWOub1QuAtIx5jHotNh7nWQB--Fk1nmO8SPZTejFicSIyIQzKO0LbeKSzGSyw12p8ooyQhtS106Tgw3eY-rKKAY6wCNITogOcjGRRfnxCNLssixWRVWdzj2xJhMg-406oIrgjjb5D7wE3AsW1X6nBar5qQ4q2PVWKIWnq94PtgAD_I2P9vpjDdqYe8D9ci4NpvIT-a4D1FPTQBfNgjUVP3kf0FJ2wH4xx6gRVglulqr_iudSny3pXo',
            ],
            [
                'slug' => 'graphic-logo-tee',
                'series' => 'SOLÉA EDITORIAL',
                'detail_series' => 'SOLÉA EDITORIAL',
                'name' => 'COCOA TAILORED BLAZER',
                'detail_name' => 'COCOA TAILORED BLAZER',
                'price' => '&#8369;950.00',
                'sale_price' => '&#8369;950',
                'compare_price' => '&#8369;1,300',
                'badge' => 'G-03 DROP',
                'description' => 'A softly tailored cocoa blazer with a clean single-breasted profile and timeless wardrobe appeal.',
                'details' => 'The Cocoa Tailored Blazer is shaped in a warm wool-blend with a polished lapel, comfortable structure, and an easy fit for layered dressing.',
                'image' => 'https://lh3.googleusercontent.com/aida/AP1WRLtyqA1UoN5dgQ_jP-l1tMFMbyJ7U3Gzyw3F01GgD4nChrwkm-djGUprcVcBytQUrnb-W0Q8jENFp75x9g9oXrHS50CLuQXXcKZGvw_L9fzr6gO3M70isSae4bvrBKPie8Mbt2EjM2aLnA7q1jw3iixpCaEoWDtatvN9Z0Yd-a379mlUWNj0bt-nNSyXhMgE1qDWZRm0TOKqcAg5LUfEGCF4qSMErPEcPTZ7zuZon34izVqfVSLLJrlfWks',
            ],
            [
                'slug' => 'oversized-charcoal-tee',
                'series' => 'RELAXED FIT',
                'detail_series' => 'RELAXED FIT',
                'name' => 'DUSTY ROSE KNIT CARDIGAN',
                'detail_name' => 'DUSTY ROSE KNIT CARDIGAN',
                'price' => '&#8369;1,100.00',
                'sale_price' => '&#8369;1,100',
                'compare_price' => '&#8369;1,500',
                'badge' => 'R-04 FIT',
                'description' => 'A soft dusty rose cardigan with an open front, practical pockets, and a relaxed layerable shape.',
                'details' => 'The Dusty Rose Knit Cardigan is made for easy layering with a tactile knit texture, gently dropped shoulders, and a comfortable length that pairs with skirts or trousers.',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAuN2NL6--ihZ9ZGxuzT0JS-t7n0ax56B5V-hDCh-tmx0QKRBmfsk7trBwqlP3v6gWMkL0dmR0Xz88IZo-uYAI2KzT3W2ldN1K0zbIQoeND4upY1CF7uYfITHzbgGHWU_iHSt1PZQnCrwa01JcBWSJziEX78wSf6q7PhfUMcn705hugz__MFl_O0AO2CoWmP5jVbeu4XQnIg6Gmcl0VZIbp_v9HplqOnOyCcdYRmyeTnko8bq0g-e6o',
            ],
            [
                'slug' => 'tech-wear-tactical-tee',
                'series' => 'PROTOTYPE',
                'detail_series' => 'PROTOTYPE LAB',
                'name' => 'SAND UTILITY OVERSHIRT',
                'detail_name' => 'SAND UTILITY OVERSHIRT',
                'price' => '&#8369;1,200.00',
                'sale_price' => '&#8369;1,200',
                'compare_price' => '&#8369;1,650',
                'badge' => 'P-05 TEST',
                'description' => 'A clean sand utility overshirt with functional pockets and a relaxed fit for warm layered looks.',
                'details' => 'The Sand Utility Overshirt combines durable cotton texture with a structured collar, buttoned pockets, and an easy silhouette that works over tees and knits.',
                'image' => 'https://lh3.googleusercontent.com/aida/AP1WRLv_D-pR1glmTtg_bOjyMHNRf_uYaCHqBMdNiZ7WN4uMAb1KIdbwbrMQunbqDD2ouT2CveXb45QRcENCHA5S_6SuxDUF11M1BnM2gGcAfDFSMkFLyFOvoezP-RJPh1AMjmPFgPT1s3TAimVI7_4LDkI4L9rsHPTtoctuwaX8IBfJDzNCVuiOZme7rfey003DashGJ8Hf7c5KS0heJoJDt6rjc6_3oolMGYTaEAZaO41OOCsHoz3HwGCKW1U',
            ],
            [
                'slug' => 'vintage-wash-renegade-tee',
                'series' => 'ARCHIVE',
                'detail_series' => 'ARCHIVE WASH',
                'name' => 'PLUM SATIN MIDI SKIRT',
                'detail_name' => 'PLUM SATIN MIDI SKIRT',
                'price' => '&#8369;1,050.00',
                'sale_price' => '&#8369;1,050',
                'compare_price' => '&#8369;1,450',
                'badge' => 'A-06 ARCHIVE',
                'description' => 'A fluid plum satin midi skirt with a softly polished finish and an easy feminine drape.',
                'details' => 'The Plum Satin Midi Skirt falls to an elegant midi length with a smooth satin hand feel and subtle movement that dresses up a simple knit or jacket.',
                'image' => 'https://lh3.googleusercontent.com/aida/AP1WRLtvdBEFMajThTL_P-d0qyG6iA2YPEMEgIZtvzSC3bVbsVpnxT-NUNIMNfSVLo-wWkxSQqsid_Ivz8BMJn08lRtn3lZDx5Yd-ekUDBD9CAazf6qqUQkZWQl0gt2pAnAz3yuw0AOzMj2ash77jt5kvYT9SC3PxAbDEt8mH7JGBKAiVYgHw28jaAaCTiuIcSZJ-kL6WPOg2JkEK08fK-NAD36cKaES0X_QxrfQPXOH-SN9NBiOGuSm3DeR2GA',
            ],
        ];
    }
}
