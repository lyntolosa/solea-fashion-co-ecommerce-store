<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Developer Mode | Soléa Fashion Co.</title>
    <link rel="stylesheet" href="{{ asset('css/admin-brand.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body class="admin-brand min-h-screen bg-background text-white">`r
@include('partials.demo-notice')
@include('partials.admin-header')
<aside class="fixed left-0 top-0 hidden h-full w-64 border-r border-outline-variant/20 bg-surface px-6 pb-8 pt-24 lg:block">
    <nav class="space-y-1">
        @foreach ([['Dashboard','dashboard',route('admin.dashboard')],['Orders','shopping_bag',route('admin.orders')],['Products','inventory_2',route('admin.products')],['Inventory','warehouse',route('admin.inventory')],['Customers','group',route('admin.customers')],['Discounts','local_offer',route('admin.discounts')],['Reviews','reviews',route('admin.reviews')],['Analytics','analytics',route('admin.analytics')],['Settings','settings',route('admin.settings')]] as $item)
            <a href="{{ $item[2] }}" class="flex items-center gap-3 px-3 py-3 text-xs font-bold uppercase tracking-widest text-muted hover:bg-surface-container-high hover:text-primary"><span class="material-symbols-outlined text-lg">{{ $item[1] }}</span>{{ $item[0] }}</a>
        @endforeach
    </nav>
    <div class="mt-8 border-t border-outline-variant/20 pt-5">
        <a href="{{ route('admin.logout') }}" class="mt-2 flex items-center gap-3 px-3 py-3 text-xs font-bold uppercase tracking-widest text-muted hover:text-primary"><span class="material-symbols-outlined">logout</span>Exit Admin</a>
    </div>
</aside>
<main class="min-h-screen px-6 pb-16 pt-24 lg:ml-64 lg:px-12">
    <div class="mx-auto max-w-[1400px]">
        <header class="mb-8 border-b border-outline-variant/20 pb-8">
            <p class="mb-3 text-xs font-bold uppercase tracking-[.25em] text-primary">Soléa / Developer Workspace</p>
            <h1 class="flex items-center gap-3 font-headline text-4xl font-extrabold uppercase"><span class="material-symbols-outlined text-primary">code</span>Developer Mode</h1>
            <p class="mt-3 text-sm text-muted">Configure connections and operational services for the store.</p>
        </header>
        @if(session('status'))<div class="mb-6 border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="mb-6 border border-red-300/40 bg-red-100/40 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>@endif
        <form action="{{ route('admin.developer.save') }}" method="POST" class="space-y-8">
            @csrf
            <section class="border border-outline-variant/20 bg-surface p-6">
                <div class="mb-5"><h2 class="font-headline text-2xl font-bold">Environment &amp; API connections</h2><p class="mt-1 text-sm text-muted">Control the runtime environment and external API endpoints.</p></div>
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-muted">Environment<select name="environment" class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-sm text-on-surface"><option value="local" @selected(($settings['environment'] ?? 'local') === 'local')>Local development</option><option value="staging" @selected(($settings['environment'] ?? '') === 'staging')>Staging</option><option value="production" @selected(($settings['environment'] ?? '') === 'production')>Production</option></select></label>
                    <label class="text-xs font-bold uppercase tracking-wider text-muted">Store API base URL<input type="url" name="api_base_url" value="{{ old('api_base_url', $settings['api_base_url'] ?? '') }}" placeholder="https://api.example.com" class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-sm text-on-surface"></label>
                    <label class="text-xs font-bold uppercase tracking-wider text-muted">Webhook endpoint<input type="url" name="webhook_url" value="{{ old('webhook_url', $settings['webhook_url'] ?? '') }}" placeholder="https://example.com/webhooks/orders" class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-sm text-on-surface"></label>
                    <label class="text-xs font-bold uppercase tracking-wider text-muted">Shipping provider<input name="shipping_provider" value="{{ old('shipping_provider', $settings['shipping_provider'] ?? 'Manual fulfillment') }}" class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-sm text-on-surface"></label>
                </div>
            </section>
            <section class="border border-outline-variant/20 bg-surface p-6">
                <div class="mb-5"><h2 class="font-headline text-2xl font-bold">Payment gateways</h2><p class="mt-1 text-sm text-muted">Choose the payment service used by checkout.</p></div>
                <div class="grid gap-4 md:grid-cols-2">
                    <label class="flex cursor-pointer items-center justify-between border border-outline-variant/30 bg-surface-container-high px-4 py-4"><span><strong class="block text-sm">Primary payment gateway</strong><small class="text-muted">Used for online card payments</small></span><select name="payment_gateway" class="border-outline-variant/30 bg-surface px-3 py-2 text-sm text-on-surface"><option value="Cash on Delivery" @selected(($settings['payment_gateway'] ?? 'Cash on Delivery') === 'Cash on Delivery')>Cash on Delivery</option><option value="Stripe" @selected(($settings['payment_gateway'] ?? '') === 'Stripe')>Stripe</option><option value="PayMongo" @selected(($settings['payment_gateway'] ?? '') === 'PayMongo')>PayMongo</option></select></label>
                    <label class="flex items-center gap-3 border border-outline-variant/30 bg-surface-container-high px-4 py-4"><input type="checkbox" name="cod_enabled" value="1" @checked($settings['cod_enabled'] ?? true)><span><strong class="block text-sm">Cash on Delivery</strong><small class="text-muted">Allow customers to pay upon delivery</small></span></label>
                </div>
            </section>
            <section class="border border-outline-variant/20 bg-surface p-6">
                <div class="mb-5"><h2 class="font-headline text-2xl font-bold">Notifications &amp; monitoring</h2><p class="mt-1 text-sm text-muted">Keep store operators informed about important events.</p></div>
                <label class="flex items-center gap-3 border border-outline-variant/30 bg-surface-container-high px-4 py-4"><input type="checkbox" name="email_notifications" value="1" @checked($settings['email_notifications'] ?? true)><span><strong class="block text-sm">Order email notifications</strong><small class="text-muted">Send alerts when a new order is placed or its status changes</small></span></label>
            </section>
            <div class="flex items-center gap-4"><button type="submit" class="bg-primary px-6 py-3 text-sm font-bold uppercase tracking-wider text-white hover:bg-[#8E4433]">Save developer settings</button><span class="text-xs text-muted">Last saved: {{ !empty($settings['updated_at']) ? now()->parse($settings['updated_at'])->format('M d, Y H:i') : 'Not saved yet' }}</span></div>
        </form>
    </div>
</main>
</body>
</html>
