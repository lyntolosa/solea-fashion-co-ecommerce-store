<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | CUSTOMER DASHBOARD</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"><script src="{{ asset('js/app.js') }}" defer></script>
    <script>tailwind.config={darkMode:'class',theme:{extend:{colors:{primary:'#A6533E','primary-container':'#A6533E','on-primary-container':'#FFFFFF',background:'#F4EEE7',surface:'#FFFDFC','surface-container':'#F1E8DF','surface-container-low':'#F8F2EC','surface-container-high':'#E9DDD2','surface-container-highest':'#DDCEC1','on-surface':'#201A17','on-surface-variant':'#735F56','outline-variant':'#CBBBAE',secondary:'#7799FF'},fontFamily:{headline:['Plus Jakarta Sans'],body:['Inter']}}}};</script>
    <style>
        input:focus, textarea:focus, select:focus {
            border-color: #A6533E !important;
            box-shadow: 0 0 0 1px rgba(166, 83, 62, 0.22) !important;
            outline: none !important;
        }
        input, textarea, select { color: #201A17 !important; }
        input::placeholder, textarea::placeholder { color: #735F56 !important; opacity: .7; }
        button, a { text-shadow: none; }
        #security input:focus,
        #account-details input:focus,
        #account-details textarea:focus,
        #account-details select:focus {
            border-color: #A6533E !important;
            box-shadow: 0 0 0 1px rgba(166, 83, 62, 0.22) !important;
            outline: 2px solid transparent !important;
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface">`r
@include('partials.demo-notice')
    <nav class="fixed top-0 z-50 flex h-16 w-full items-center justify-between border-b border-outline-variant/30 bg-background/95 px-6 backdrop-blur-md"><a href="{{ route('home') }}" class="font-headline text-xl font-black italic tracking-tighter text-primary-container">Soléa Fashion Co.</a><div class="flex items-center gap-4"><a href="{{ route('shop') }}" aria-label="Search products"><span class="material-symbols-outlined">search</span></a><a href="{{ route('cart') }}" aria-label="Shopping bag"><span class="material-symbols-outlined">shopping_bag</span></a>@include('partials.account-menu')</div></nav>
    <div class="flex min-h-screen pt-16">
        <aside class="fixed left-0 top-16 z-40 hidden h-[calc(100vh-64px)] w-64 border-r border-outline-variant/20 bg-surface-container-low lg:block"><nav class="flex flex-col py-6"><a class="flex items-center gap-4 border-l-4 border-primary-container bg-surface-container-high px-6 py-3 text-sm font-semibold text-primary-container" href="{{ route('dashboard') }}"><span class="material-symbols-outlined">grid_view</span>Dashboard</a><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-white" href="{{ route('dashboard') }}#orders"><span class="material-symbols-outlined">package_2</span>My Orders</a><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-white" href="{{ route('dashboard') }}#wishlist"><span class="material-symbols-outlined">favorite</span>Wishlist</a><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-white" href="{{ route('dashboard') }}#account-details"><span class="material-symbols-outlined">person</span>Account Details</a><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-white" href="{{ route('dashboard') }}#saved-addresses"><span class="material-symbols-outlined">location_on</span>Saved Addresses</a><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-white" href="{{ route('dashboard') }}#payment-methods"><span class="material-symbols-outlined">credit_card</span>Payment Methods</a><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-white" href="{{ route('dashboard') }}#security"><span class="material-symbols-outlined">security</span>Security &amp; Privacy</a><div class="mx-6 my-4 border-t border-outline-variant/20"></div><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-white" href="{{ route('contact') }}"><span class="material-symbols-outlined">help_outline</span>Help &amp; Support</a><a class="flex items-center gap-4 px-6 py-3 text-sm text-on-surface-variant hover:text-error" href="{{ route('logout') }}"><span class="material-symbols-outlined">logout</span>Log Out</a></nav></aside>
        <main class="ml-0 flex-1 p-6 md:p-12 lg:ml-64"><header class="mb-10 flex flex-col items-start justify-between gap-6 md:flex-row md:items-end"><div><h1 class="mb-2 font-headline text-3xl font-bold md:text-4xl">{{ $customer ? 'Welcome back, ' . $customer['name'] : 'Customer Dashboard' }}</h1><p class="text-sm text-on-surface-variant">{{ $customer['email'] ?? 'Complete a purchase to see your customer data here.' }}</p></div><a class="bg-primary-container px-6 py-3 text-xs font-semibold uppercase tracking-wide text-on-primary-container" href="{{ route('shop') }}">Continue Shopping</a></header>
            <div class="mb-12 grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ([['local_shipping', (string) $activeOrderCount, 'Active Orders'], ['inventory_2', (string) $deliveredOrderCount, 'Delivered Orders'], ['favorite', (string) $wishlistProducts->count(), 'Wishlist Items'], ['account_balance_wallet', '&#8369;0', 'Available Store Credit']] as $stat)<div class="flex flex-col border border-outline-variant/10 bg-surface-container p-5"><div class="mb-4 flex items-start justify-between"><span class="material-symbols-outlined text-primary-container">{{ $stat[0] }}</span><span class="font-headline text-2xl font-bold">{!! $stat[1] !!}</span></div><span class="text-xs uppercase tracking-wider text-on-surface-variant">{{ $stat[2] }}</span></div>@endforeach
            </div>
            <section id="orders" class="mb-12"><div class="mb-6 flex flex-wrap items-center gap-6 border-b border-outline-variant/20"><a href="#orders" class="border-b-2 border-primary-container pb-3 font-headline text-xl font-semibold text-primary-container">Active Order Tracking</a><a href="#completed-orders" class="pb-3 font-headline text-xl font-semibold text-on-surface-variant transition hover:text-primary-container">Completed Orders</a></div>
                @if ($order)
                    <div class="border border-outline-variant/20 bg-surface-container p-6 md:p-8"><div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-center"><div><h3 class="font-headline text-lg font-bold">Order #{{ $order['reference'] }}</h3><p class="mt-1 text-sm text-on-surface-variant">Placed {{ $order['date'] }} · {{ $order['shipping'] }} shipping</p></div><span class="border border-primary-container px-5 py-2 text-xs font-semibold uppercase tracking-wide text-primary-container">{{ $order['status'] }}</span></div><div class="mb-4 h-1 overflow-hidden rounded-full bg-surface-container-highest"><div class="h-full bg-primary-container" style="width:50%"></div></div><div class="flex justify-between text-xs text-on-surface-variant"><span class="text-primary-container">Placed</span><span class="text-primary-container">Processing</span><span>Shipped</span><span>Delivered</span></div><div class="mt-8 flex items-center gap-4 bg-surface-container-low p-4"><div class="h-16 w-16 flex-shrink-0 overflow-hidden bg-surface-container-highest"><img class="h-full w-full object-cover" src="{{ $order['image'] }}" alt="{{ $order['item'] }}"></div><div><h4 class="font-headline text-sm font-bold">{{ $order['item'] }}</h4><p class="mt-1 text-xs text-on-surface-variant">Qty: {{ $order['quantity'] }} · Size: {{ $order['size'] }}</p><p class="mt-1 text-xs text-primary-container">&#8369;{{ $order['total'] }} · {{ $order['payment'] }}</p></div></div></div>
                @else
                    <div class="border border-dashed border-outline-variant/30 bg-surface-container-low p-10 text-center"><span class="material-symbols-outlined mb-3 text-4xl text-on-surface-variant">receipt_long</span><h3 class="font-headline text-lg font-bold">No orders yet</h3><p class="mt-2 text-sm text-on-surface-variant">Your completed purchases will appear here.</p><a class="mt-6 inline-block bg-primary-container px-6 py-3 text-xs font-bold uppercase text-on-primary-container" href="{{ route('shop') }}">Shop Collection</a></div>
                @endif
            </section>
            <section id="wishlist" class="mt-10 scroll-mt-24 border-t border-outline-variant/20 pt-10"><h2 class="font-headline text-xl font-semibold">Wishlist</h2>@if($wishlistProducts->isEmpty())<div class="mt-6 border border-dashed border-outline-variant/30 bg-surface-container-low p-10 text-center"><span class="material-symbols-outlined mb-3 text-4xl text-on-surface-variant">favorite_border</span><h3 class="font-headline text-lg font-bold">No saved items yet</h3><p class="mt-2 text-sm text-on-surface-variant">Save products from their product page to see them here.</p><a class="mt-6 inline-block bg-primary-container px-6 py-3 text-xs font-bold uppercase text-on-primary-container" href="{{ route('shop') }}">Browse Products</a></div>@else<div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">@foreach($wishlistProducts as $item)<a href="{{ route('products.show',$item['slug']) }}" class="group border border-outline-variant/20 bg-surface-container p-4"><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="aspect-square w-full object-cover transition group-hover:opacity-80"><h3 class="mt-4 font-headline font-bold">{{ $item['name'] }}</h3><p class="mt-1 text-sm text-primary-container">{!! $item['price'] !!}</p></a>@endforeach</div>@endif</section>
            <section id="payment-methods" class="mt-10 scroll-mt-24 border-t border-outline-variant/20 pt-10"><h2 class="font-headline text-xl font-semibold">Payment Methods</h2><div class="mt-6 border border-dashed border-outline-variant/30 bg-surface-container-low p-10 text-center"><span class="material-symbols-outlined mb-3 text-4xl text-on-surface-variant">credit_card</span><h3 class="font-headline text-lg font-bold">No saved payment methods</h3><p class="mt-2 text-sm text-on-surface-variant">Payment details can be entered securely during checkout.</p><a class="mt-6 inline-block bg-primary-container px-6 py-3 text-xs font-bold uppercase text-on-primary-container" href="{{ route('shop') }}">Continue Shopping</a></div></section>
            <section id="security" class="mt-10 scroll-mt-24 border-t border-outline-variant/20 pt-10"><div class="mb-6"><h2 class="font-headline text-xl font-semibold">Security &amp; Privacy</h2><p class="mt-2 text-sm text-on-surface-variant">Update the email used to sign in and change your password.</p></div><form action="{{ route('dashboard.security.update') }}" method="POST" class="grid grid-cols-1 gap-6 border border-outline-variant/20 bg-surface-container p-6 md:grid-cols-2">@csrf<div><label for="security_email" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">Login Email</label><input id="security_email" name="email" type="email" value="{{ old('email', $customer['email'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div><div><label for="security_password" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">New Password</label><input id="security_password" name="password" type="password" minlength="6" autocomplete="new-password" class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container" placeholder="Leave blank to keep current password"></div><div><label for="security_password_confirmation" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">Confirm New Password</label><input id="security_password_confirmation" name="password_confirmation" type="password" minlength="6" autocomplete="new-password" class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div><div class="flex items-end md:col-span-2"><button type="submit" class="bg-primary-container px-6 py-3 text-xs font-bold uppercase tracking-widest text-on-primary-container hover:bg-primary">Save Security Settings</button></div></form></section>
            <section class="grid grid-cols-1 gap-8 border-t border-outline-variant/20 pt-10 md:grid-cols-2"><div class="border border-outline-variant/10 bg-surface-container-low p-8"><h3 class="mb-2 font-headline text-lg font-semibold">Need Help?</h3><p class="mb-6 text-sm text-on-surface-variant">Questions about your order or returns? Our support team is ready to help.</p><a class="inline-block bg-surface-container-high px-6 py-2.5 text-xs font-semibold uppercase" href="{{ route('contact') }}">Contact Support</a></div><div class="p-8"><h3 class="mb-4 font-headline text-sm font-semibold uppercase tracking-wider text-on-surface-variant">Account Details</h3><p class="text-sm">{{ $customer['email'] ?? 'No customer account is active yet.' }}</p><p class="mt-2 text-xs text-on-surface-variant">Account data is shown from the current customer session.</p></div></section>
            <section id="account-details" class="mt-10 border-t border-outline-variant/20 pt-10">
                <div class="mb-6"><h2 class="font-headline text-xl font-semibold">Account Details</h2><p class="mt-2 text-sm text-on-surface-variant">Edit your personal information, login email, and delivery address.</p></div>
                @if (session('status'))
                    <div class="mb-6 border border-primary-container/40 bg-primary-container/10 p-4 text-sm text-primary-container">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 border border-error/40 bg-error/10 p-4 text-sm text-red-200"><ul class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form action="{{ route('dashboard.profile.update') }}" method="POST" class="grid grid-cols-1 gap-6 border border-outline-variant/20 bg-surface-container p-6 md:grid-cols-2">
                    @csrf
                    <div><label for="profile_first_name" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">First Name</label><input id="profile_first_name" name="first_name" value="{{ old('first_name', $customer['first_name'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div>
                    <div><label for="profile_last_name" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">Last Name</label><input id="profile_last_name" name="last_name" value="{{ old('last_name', $customer['last_name'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div>
                    <div><label for="profile_email" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">Email Address</label><input id="profile_email" name="email" type="email" value="{{ old('email', $customer['email'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div>
                    <div><label for="profile_phone" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">Phone Number</label><input id="profile_phone" name="phone" type="tel" value="{{ old('phone', $customer['phone'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div>
                    <div class="md:col-span-2"><label for="profile_address" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">Street Address</label><input id="profile_address" name="street_address" value="{{ old('street_address', $customer['street_address'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div>
                    <div><label for="profile_city" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">City</label><input id="profile_city" name="city" value="{{ old('city', $customer['city'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div>
                    <div><label for="profile_zip" class="mb-2 block text-xs font-bold uppercase tracking-widest text-on-surface-variant">Zip Code</label><input id="profile_zip" name="zip_code" value="{{ old('zip_code', $customer['zip_code'] ?? '') }}" required class="w-full border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white focus:border-primary-container focus:ring-primary-container"></div>
                    <div class="flex flex-wrap items-center gap-4 md:col-span-2"><button type="submit" class="bg-primary-container px-6 py-3 text-xs font-bold uppercase tracking-widest text-on-primary-container hover:bg-primary">Save Changes</button></div>
                </form>
                <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <div id="saved-addresses" class="scroll-mt-24 border border-outline-variant/20 bg-surface-container p-6">
                        <h3 class="mb-5 font-headline text-lg font-semibold">Saved Shipping Addresses</h3>
                        @forelse (($customer['addresses'] ?? []) as $address)
                            <div class="border-b border-outline-variant/20 py-4 first:pt-0 last:border-b-0"><div class="flex items-start justify-between gap-4"><div><p class="font-semibold">{{ $address['label'] }}</p><p class="mt-1 text-sm text-on-surface-variant">{{ $address['street_address'] }}, {{ $address['city'] }}, {{ $address['zip_code'] }}</p>@if (! empty($address['phone']))<p class="mt-1 text-xs text-on-surface-variant">{{ $address['phone'] }}</p>@endif</div><form action="{{ route('dashboard.addresses.destroy', $address['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="shrink-0 text-xs font-bold uppercase tracking-widest text-primary-container transition-colors hover:text-primary" aria-label="Delete {{ $address['label'] }}">Delete</button></form></div></div>
                        @empty
                            <p class="text-sm text-on-surface-variant">No saved addresses yet.</p>
                        @endforelse
                    </div>
                    <form action="{{ route('dashboard.addresses.store') }}" method="POST" class="grid grid-cols-1 gap-4 border border-outline-variant/20 bg-surface-container p-6">
                        @csrf
                        <h3 class="font-headline text-lg font-semibold">Add Another Address</h3>
                        <input name="label" required placeholder="Label (e.g. Home or Office)" class="border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white">
                        <input name="street_address" required placeholder="Street address" class="border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white">
                        <div class="grid grid-cols-2 gap-4"><input name="city" required placeholder="City" class="border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white"><input name="zip_code" required placeholder="Zip code" class="border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white"></div>
                        <input name="phone" placeholder="Phone number (optional)" class="border border-outline-variant/30 bg-surface-container-high px-4 py-3 text-white">
                        <button type="submit" class="w-fit bg-primary-container px-6 py-3 text-xs font-bold uppercase tracking-widest text-on-primary-container hover:bg-primary">Save Address</button>
                    </form>
                </div>
            </section>
            <section id="completed-orders" class="mt-10 scroll-mt-24 border-t border-outline-variant/20 pt-10"><div class="mb-6 flex flex-wrap items-center gap-6 border-b border-outline-variant/20"><a href="#orders" class="pb-3 font-headline text-xl font-semibold text-on-surface-variant transition hover:text-primary-container">Active Order Tracking</a><a href="#completed-orders" class="border-b-2 border-primary-container pb-3 font-headline text-xl font-semibold text-primary-container">Completed Orders</a></div>@php($completedOrders = $orders->where('status', 'Delivered'))@if($completedOrders->isEmpty())<div class="mt-6 border border-dashed border-outline-variant/30 bg-surface-container-low p-10 text-center"><span class="material-symbols-outlined mb-3 text-4xl text-on-surface-variant">inventory_2</span><h3 class="font-headline text-lg font-bold">No completed orders yet</h3><p class="mt-2 text-sm text-on-surface-variant">Your delivered orders and purchased items will appear here.</p></div>@else<div class="mt-6 space-y-6">@foreach($completedOrders as $completedOrder)<article class="border border-outline-variant/20 bg-surface-container p-6"><div class="flex flex-col justify-between gap-4 border-b border-outline-variant/20 pb-5 md:flex-row md:items-start"><div><h3 class="font-headline text-lg font-bold">Order #{{ $completedOrder['reference'] }}</h3><p class="mt-1 text-sm text-on-surface-variant">Placed {{ $completedOrder['date'] }} · {{ $completedOrder['shipping'] }} shipping</p><p class="mt-1 text-xs uppercase tracking-wider text-primary-container">{{ $completedOrder['status'] }} · {{ $completedOrder['payment'] }}</p></div><div class="text-left md:text-right"><p class="text-xs uppercase tracking-wider text-on-surface-variant">Total</p><p class="mt-1 text-xl font-bold text-primary-container">&#8369;{{ number_format((float) $completedOrder['total'], 2) }}</p></div></div><div class="mt-5 space-y-3">@foreach(($completedOrder['items'] ?? []) as $completedItem)<div class="flex flex-col gap-4 bg-surface-container-low p-4 sm:flex-row sm:items-center"><img src="{{ $completedItem['image'] }}" alt="{{ $completedItem['name'] }}" class="h-20 w-20 object-cover"><div class="flex-1"><h4 class="font-headline font-bold">{{ $completedItem['name'] }}</h4><p class="mt-1 text-xs text-on-surface-variant">Quantity: {{ $completedItem['quantity'] }} · Size: {{ $completedItem['size'] }}</p><p class="mt-1 text-sm text-primary-container">&#8369;{{ number_format((float) $completedItem['price'] * (int) $completedItem['quantity'], 2) }}</p></div><button type="button" class="buy-again bg-primary-container px-5 py-3 text-xs font-bold uppercase tracking-widest text-on-primary-container hover:bg-primary" data-product='@json($completedItem)'>Buy Again</button></div>@endforeach</div><p class="mt-5 text-xs text-on-surface-variant">Shipping to: {{ $completedOrder['street_address'] ?? 'Address not recorded' }}, {{ $completedOrder['city'] ?? '' }} {{ $completedOrder['zip_code'] ?? '' }}</p></article>@endforeach</div>@endif</section>
        <script>
            (() => {
                const section = document.getElementById('orders');
                if (!section) return;
                const activeOrders = @json($orders->whereNotIn('status', ['Delivered', 'Cancelled'])->values());
                if (!activeOrders.length) {
                    section.innerHTML = '<div class="mb-6 flex flex-wrap items-center gap-6 border-b border-outline-variant/20"><a href="#orders" class="border-b-2 border-primary-container pb-3 font-headline text-xl font-semibold text-primary-container">Active Order Tracking</a><a href="#completed-orders" class="pb-3 font-headline text-xl font-semibold text-on-surface-variant transition hover:text-primary-container">Completed Orders</a></div><div class="border border-dashed border-outline-variant/30 bg-surface-container-low p-10 text-center"><span class="material-symbols-outlined mb-3 text-4xl text-on-surface-variant">receipt_long</span><h3 class="font-headline text-lg font-bold">No active orders</h3><p class="mt-2 text-sm text-on-surface-variant">Your completed purchases are available below.</p><a class="mt-6 inline-block bg-primary-container px-6 py-3 text-xs font-bold uppercase text-on-primary-container" href="#completed-orders">View Completed Orders</a></div>';
                    return;
                }
                const progress = { 'Pending Payment': 12, Processing: 34, 'Ready to Ship': 50, 'Shipped / Transit': 75, Shipped: 75, Delivered: 100, Cancelled: 0 };
                const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character]));
                const money = value => `&#8369;${Number(value || 0).toLocaleString('en-US')}`;
                section.innerHTML = `<div class="mb-6 flex flex-wrap items-center gap-6 border-b border-outline-variant/20"><a href="#orders" class="border-b-2 border-primary-container pb-3 font-headline text-xl font-semibold text-primary-container">Active Order Tracking</a><a href="#completed-orders" class="pb-3 font-headline text-xl font-semibold text-on-surface-variant transition hover:text-primary-container">Completed Orders</a></div>${activeOrders.map(order => {
                    const status = order.status || 'Processing';
                    const items = Array.isArray(order.items) && order.items.length ? order.items : [{ name: order.item, image: order.image, quantity: order.quantity, size: order.size, price: order.total }];
                    return `<article class="mb-6 border border-outline-variant/20 bg-surface-container p-6 md:p-8"><div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-center"><div><h3 class="font-headline text-lg font-bold">Order #${escapeHtml(order.reference)}</h3><p class="mt-1 text-sm text-on-surface-variant">Placed ${escapeHtml(order.date)} · ${escapeHtml(order.shipping)} shipping</p></div><span class="border border-primary-container px-5 py-2 text-xs font-semibold uppercase tracking-wide text-primary-container">${escapeHtml(status)}</span></div><div class="mb-4 h-1 overflow-hidden rounded-full bg-surface-container-highest"><div class="h-full bg-primary-container" style="width:${progress[status] ?? 34}%"></div></div><div class="flex justify-between text-xs text-on-surface-variant"><span class="${(progress[status] ?? 34) >= 12 ? 'text-primary-container' : ''}">Placed</span><span class="${(progress[status] ?? 34) >= 34 ? 'text-primary-container' : ''}">Processing</span><span class="${(progress[status] ?? 34) >= 75 ? 'text-primary-container' : ''}">Shipped</span><span class="${status === 'Delivered' ? 'text-primary-container' : ''}">Delivered</span></div><div class="mt-8 space-y-3">${items.map(item => `<div class="flex items-center gap-4 bg-surface-container-low p-4"><div class="h-16 w-16 flex-shrink-0 overflow-hidden bg-surface-container-highest"><img class="h-full w-full object-cover" src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}"></div><div><h4 class="font-headline text-sm font-bold">${escapeHtml(item.name)}</h4><p class="mt-1 text-xs text-on-surface-variant">Qty: ${escapeHtml(item.quantity)} · Size: ${escapeHtml(item.size)}</p><p class="mt-1 text-xs text-primary-container">${money(Number(item.price || 0) * Number(item.quantity || 1))} · ${escapeHtml(order.payment)}</p></div></div>`).join('')}</div></article>`;
                }).join('')}`;
            })();
        </script>
        <script>
            (() => {
                const activeOrders = @json($orders->whereNotIn('status', ['Delivered', 'Cancelled'])->values());
                const cancellableStatuses = ['Pending Payment', 'Processing', 'Ready to Ship'];
                document.querySelectorAll('#orders article').forEach((article, index) => {
                    const order = activeOrders[index];
                    if (!order) return;
                    const control = document.createElement('div');
                    control.className = 'mt-6 flex justify-end border-t border-outline-variant/20 pt-5';
                    if (cancellableStatuses.includes(order.status)) {
                        control.innerHTML = `<form method="POST" action="/dashboard/orders/${encodeURIComponent(order.reference)}/cancel"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" class="border border-primary-container px-5 py-2 text-xs font-bold uppercase tracking-widest text-primary-container transition-colors hover:bg-primary-container hover:text-white">Cancel Order</button></form>`;
                        control.querySelector('form').addEventListener('submit', event => {
                            if (!window.confirm('Cancel this order? The items will be returned to stock.')) event.preventDefault();
                        });
                    } else {
                        control.innerHTML = '<span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant/50">Cancellation unavailable after shipping</span>';
                    }
                    article.appendChild(control);
                });
            })();
        </script>
        </main>
    </div>
    <nav class="fixed bottom-0 z-50 flex h-16 w-full items-center justify-around border-t border-outline-variant/30 bg-background/95 px-4 lg:hidden"><a class="flex flex-col items-center gap-1 text-primary-container" href="{{ route('dashboard') }}"><span class="material-symbols-outlined">grid_view</span><span class="text-[9px] uppercase">Dashboard</span></a><a class="flex flex-col items-center gap-1 text-on-surface-variant" href="{{ route('dashboard') }}#orders"><span class="material-symbols-outlined">package_2</span><span class="text-[9px] uppercase">Orders</span></a><a class="flex flex-col items-center gap-1 text-on-surface-variant" href="{{ route('dashboard') }}#wishlist"><span class="material-symbols-outlined">favorite</span><span class="text-[9px] uppercase">Wishlist</span></a><a class="flex flex-col items-center gap-1 text-on-surface-variant" href="{{ route('dashboard') }}#account-details"><span class="material-symbols-outlined">person</span><span class="text-[9px] uppercase">Account</span></a></nav>
<script>
    (() => {
        const orderSection = document.getElementById('orders');
        if (!orderSection) return;
        const status = orderSection.querySelector('span.border-primary-container')?.textContent.trim().toLowerCase();
        const progress = { 'pending payment': 12, processing: 34, 'ready to ship': 50, 'shipped / transit': 75, shipped: 75, delivered: 100, cancelled: 0 };
        const bar = orderSection.querySelector('.h-1 .h-full');
        if (bar && status in progress) bar.style.width = `${progress[status]}%`;
        const labels = orderSection.querySelectorAll('.h-1 + .flex span');
        const stage = status === 'delivered' ? 3 : status === 'shipped' || status === 'shipped / transit' ? 2 : status === 'processing' || status === 'ready to ship' ? 1 : 0;
        labels.forEach((label, index) => label.classList.toggle('text-primary-container', index <= stage));
    })();
</script>
<script>
    (() => {
        document.querySelectorAll('.buy-again').forEach(button => button.addEventListener('click', () => {
            const product = JSON.parse(button.dataset.product || '{}');
            const cart = JSON.parse(localStorage.getItem('threadlab_cart') || '[]');
            const existing = cart.find(item => item.slug === product.slug && item.size === product.size);
            if (existing) existing.quantity = Number(existing.quantity || 1) + Number(product.quantity || 1);
            else cart.push({ slug: product.slug, name: product.name, price: Number(product.price || 0), image: product.image, size: product.size || 'M', quantity: Number(product.quantity || 1) });
            localStorage.setItem('threadlab_cart', JSON.stringify(cart));
            window.location.href = '{{ route('cart') }}';
        }));

        const sideLinks = [...document.querySelectorAll('aside nav a[href*="#"]')];
        const activeClasses = ['border-l-4', 'border-primary-container', 'bg-surface-container-high', 'text-primary-container'];
        const setActiveLink = hash => sideLinks.forEach(link => {
            const active = hash && link.getAttribute('href').endsWith(hash);
            activeClasses.forEach(className => link.classList.toggle(className, active));
            link.classList.toggle('text-on-surface-variant', !active);
        });
        setActiveLink(window.location.hash);
        sideLinks.forEach(link => link.addEventListener('click', () => setActiveLink(link.getAttribute('href').split('#')[1] ? `#${link.getAttribute('href').split('#')[1]}` : '')));
    })();
</script>
        <script>
            (() => {
                const initialOrders = @json($orders->values());
                const syncDashboard = async () => {
                    try {
                        const response = await fetch('{{ route('dashboard.data') }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            cache: 'no-store',
                        });
                        if (!response.ok) return;
                        const latest = await response.json();
                        if (JSON.stringify(latest.orders || []) !== JSON.stringify(initialOrders)) {
                            window.location.reload();
                        }
                    } catch (error) {
                        // A temporary network failure should not interrupt dashboard use.
                    }
                };
                window.setInterval(syncDashboard, 5000);
            })();
        </script>
<div id="customer-demo-password-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 px-4" role="dialog" aria-modal="true" aria-labelledby="customer-demo-password-title">
    <div class="w-full max-w-md border border-outline-variant bg-surface p-7 shadow-2xl">
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-primary-container">Portfolio Live Demo</p>
        <h2 id="customer-demo-password-title" class="font-headline text-2xl font-bold text-on-surface">Password changes are disabled</h2>
        <p class="mt-3 text-sm leading-6 text-on-surface-variant">This shop is for live demo only. Password changes are disabled for portfolio reviewers.</p>
        <button type="button" data-customer-demo-password-close class="mt-6 bg-primary px-5 py-3 text-xs font-bold uppercase tracking-widest text-on-primary transition hover:bg-primary-container">Close</button>
    </div>
</div>
<script>
    (() => {
        const form = document.querySelector('form[action*="security"]');
        const modal = document.getElementById('customer-demo-password-modal');
        if (!form || !modal) return;
        const password = form.querySelector('input[name="password"]');
        const confirmation = form.querySelector('input[name="password_confirmation"]');
        const open = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
        const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
        form.addEventListener('submit', (event) => {
            if ((password && password.value.trim()) || (confirmation && confirmation.value.trim())) {
                event.preventDefault();
                open();
            }
        });
        modal.querySelector('[data-customer-demo-password-close]').addEventListener('click', close);
        modal.addEventListener('click', (event) => { if (event.target === modal) close(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape') close(); });
        @if(session('demo_notice')) open(); @endif
    })();
</script>
</body>
</html>
