<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | ADMIN DASHBOARD</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-brand.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary-container': '#D9FF00',
                        'on-primary-container': '#000000',
                        secondary: '#316bf3',
                        background: '#000000',
                        surface: '#131313',
                        'surface-container-high': '#201f1f',
                        'surface-container-highest': '#262626',
                        'on-surface-variant': '#adaaaa',
                        'outline-variant': '#484847',
                        error: '#ff7351'
                    },
                    fontFamily: {
                        headline: ['Plus Jakarta Sans', 'sans-serif'],
                        body: ['Inter', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        body { font-family: 'Inter', sans-serif; }
        .bento-card { background: #131313; transition: background .3s ease; }
        .bento-card:hover { background: #201f1f; }
    </style>
</head>
<body class="admin-brand bg-background text-white antialiased selection:bg-primary-container selection:text-black">`r
@include('partials.demo-notice')
    <header class="fixed top-0 z-50 flex h-16 w-full items-center justify-between border-b border-outline-variant/20 bg-black/90 px-6 backdrop-blur-xl lg:pl-72">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-headline text-xl font-bold uppercase tracking-tight">
            <span>SOLÉA FASHION CO.</span><span class="text-sm font-normal text-on-surface-variant">| Store Administrator</span>
        </a>
        <div class="flex items-center gap-5">
            <a href="{{ route('home') }}" class="hidden items-center gap-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant transition hover:text-primary-container md:flex">
                <span class="material-symbols-outlined text-lg">storefront</span> Preview
            </a>
            <button type="button" class="relative text-on-surface-variant transition hover:text-primary-container" aria-label="Notifications">
                <span class="material-symbols-outlined">notifications</span><span class="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full border border-black bg-error"></span>
            </button>
            <button type="button" class="text-on-surface-variant transition hover:text-white" aria-label="Help"><span class="material-symbols-outlined">help</span></button>
            <div class="group relative">
                <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full border border-outline-variant bg-surface-container-high text-primary-container transition hover:border-primary-container focus:border-primary-container focus:outline-none focus:ring-2 focus:ring-primary-container/30" aria-label="Admin profile" aria-haspopup="true">
                    <span class="material-symbols-outlined text-lg">person</span>
                </button>
                <div class="invisible absolute right-0 top-full z-20 mt-3 w-40 translate-y-1 border border-outline-variant/20 bg-surface p-2 opacity-0 shadow-lg transition duration-150 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100">
                    <a href="{{ route('admin.logout') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-bold uppercase tracking-wider text-on-surface-variant transition hover:bg-surface-container-high hover:text-primary-container">
                        <span class="material-symbols-outlined text-base">logout</span>Exit Admin
                    </a>
                </div>
            </div>
        </div>
    </header>

    <aside class="fixed left-0 top-0 z-[60] hidden h-full w-64 flex-col overflow-y-auto border-r border-outline-variant/10 bg-[#0a0a0a] pb-8 pt-20 lg:flex">
        <nav class="flex-1 space-y-1">
            @foreach ([
                ['icon' => 'dashboard', 'label' => 'Dashboard', 'active' => true, 'href' => route('admin.dashboard')],
                ['icon' => 'shopping_bag', 'label' => 'Orders', 'href' => route('admin.orders')],
                ['icon' => 'inventory_2', 'label' => 'Products', 'href' => route('admin.products')],
                ['icon' => 'warehouse', 'label' => 'Inventory', 'href' => route('admin.inventory')],
                ['icon' => 'group', 'label' => 'Customers', 'href' => route('admin.customers')],
                ['icon' => 'local_offer', 'label' => 'Discounts', 'href' => route('admin.discounts')],
                ['icon' => 'reviews', 'label' => 'Reviews', 'href' => route('admin.reviews')],
                ['icon' => 'analytics', 'label' => 'Analytics', 'href' => route('admin.analytics')],
                ['icon' => 'settings', 'label' => 'Settings', 'href' => route('admin.settings')],
            ] as $item)
                <a href="{{ $item['href'] ?? (!empty($item['active']) ? route('admin.dashboard') : '#') }}" class="flex items-center gap-4 border-l-4 py-3 px-6 text-[11px] font-bold uppercase tracking-widest transition {{ !empty($item['active']) ? 'border-primary-container bg-surface-container-high text-primary-container' : 'border-transparent text-on-surface-variant hover:bg-surface-container-high hover:text-white' }}">
                    <span class="material-symbols-outlined text-lg">{{ $item['icon'] }}</span>{{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="mt-6 space-y-2 border-t border-outline-variant/10 px-6 pt-4">
            <a href="{{ route('admin.logout') }}" class="flex items-center gap-4 py-2 text-[11px] font-bold uppercase tracking-widest text-on-surface-variant transition hover:text-error"><span class="material-symbols-outlined text-lg">logout</span> Exit Admin</a>
        </div>
    </aside>

    <main class="min-h-screen bg-background px-6 pb-24 pt-24 lg:ml-64">
        <div class="mx-auto max-w-[1400px]">
            <section class="mb-10 flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <p class="mb-3 text-xs font-bold uppercase tracking-[.2em] text-primary-container">Soléa / Command Center</p>
                    <h1 class="font-headline text-4xl font-bold uppercase leading-none tracking-tight md:text-5xl">Dashboard Overview</h1>
                    <p class="mt-3 text-sm tracking-wide text-on-surface-variant">Store performance metrics for the current period.</p>
                </div>
                <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row md:items-center">
                    <label class="relative block w-full md:w-80">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-sm text-on-surface-variant">search</span>
                        <input class="w-full rounded border border-outline-variant/20 bg-surface-container-high py-3 pl-10 pr-4 text-sm text-on-surface placeholder:text-on-surface-variant focus:border-primary-container focus:ring-1 focus:ring-primary-container" placeholder="Search orders, products, or customers" type="search">
                    </label>
                    <button type="button" class="flex items-center justify-center gap-2 self-start rounded border border-outline-variant/20 bg-surface px-4 py-3 text-sm text-on-surface-variant transition hover:bg-surface-container-high md:self-auto">
                        <span class="material-symbols-outlined text-[18px]">calendar_today</span><span class="font-bold">Last 30 Days</span><span class="material-symbols-outlined text-[18px]">expand_more</span>
                    </button>
                </div>
            </section>

            <section class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                @foreach ($stats as $stat)
                    <div class="bento-card rounded-lg border border-outline-variant/10 p-6">
                        <div class="mb-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant">{{ $stat['label'] }}</div>
                        <div class="mb-2 font-headline text-3xl font-bold">{!! $stat['value'] !!}</div>
                        <div class="flex items-center gap-1 text-xs font-bold {{ $stat['icon'] === 'horizontal_rule' ? 'text-on-surface-variant/50' : 'text-primary-container' }}"><span class="material-symbols-outlined text-[14px]">{{ $stat['icon'] }}</span>{{ $stat['change'] }}</div>
                    </div>
                @endforeach
                    <div class="relative overflow-hidden rounded-lg border border-error/30 bg-error/5 p-6">
                    <div class="relative z-10"><div class="mb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-error"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-error"></span>Urgent Fulfillment</div><div class="mb-2 font-headline text-3xl font-bold">{{ $urgentCount }}</div><div class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Orders Pending</div></div>
                </div>
            </section>

            <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <section class="rounded-lg border border-outline-variant/10 bg-surface p-6 lg:col-span-2">
                    <div class="mb-6 flex items-center justify-between"><h2 class="font-headline text-lg font-bold uppercase tracking-wide">Sales Performance</h2><div class="flex rounded bg-surface-container-highest p-1"><button type="button" class="rounded bg-surface px-3 py-1 text-xs font-bold uppercase tracking-wider text-white">Revenue</button><button type="button" class="px-3 py-1 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Orders</button></div></div>
                    <div class="flex h-[300px] items-end justify-between gap-2 px-2">
                        @forelse ($salesHeights as $height)
                            <div class="h-full w-full rounded-t bg-surface-container-high" style="display:flex;align-items:flex-end"><div class="w-full rounded-t {{ $loop->odd ? 'bg-primary-container' : 'bg-surface-container-highest' }}" style="height: {{ $height }}%"></div></div>
                        @empty
                            <p class="w-full self-center text-center text-sm text-on-surface-variant">No sales recorded in the last 30 days.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 flex justify-between border-t border-outline-variant/20 pt-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">@foreach ($salesLabels as $label)<span>{{ $label }}</span>@endforeach</div>
                </section>
                <section class="rounded-lg border border-outline-variant/10 bg-surface p-6"><h2 class="mb-6 font-headline text-sm font-bold uppercase tracking-wide">Order Pipeline</h2>
                    @foreach ($pipeline as $stage)
                        <div class="mb-5"><div class="mb-1 flex justify-between text-xs font-bold text-on-surface-variant"><span>{{ $stage['label'] }}</span><span>{{ $stage['count'] }}</span></div><div class="h-1.5 overflow-hidden rounded-full bg-surface-container-highest"><div class="h-full {{ $stage['color'] }}" style="width: {{ $stage['width'] }}%"></div></div></div>
                    @endforeach
                </section>
            </div>

            <section class="mb-8 overflow-hidden rounded-lg border border-outline-variant/10 bg-surface p-6">
                <div class="mb-6 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center"><h2 class="font-headline text-xl font-bold uppercase tracking-wide">Recent Orders</h2><div class="flex gap-2"><button type="button" class="rounded border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-[10px] font-bold uppercase tracking-widest transition hover:bg-surface-container-high"><span class="material-symbols-outlined mr-1 align-[-3px] text-[14px]">download</span>Export</button><button type="button" class="rounded border border-outline-variant/20 bg-surface-container-highest px-4 py-2 text-[10px] font-bold uppercase tracking-widest transition hover:bg-surface-container-high"><span class="material-symbols-outlined mr-1 align-[-3px] text-[14px]">filter_list</span>Filter</button></div></div>
                <div class="overflow-x-auto"><table class="w-full min-w-[760px] text-left"><thead class="border-b border-outline-variant/20"><tr class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant"><th class="px-2 pb-3">Order</th><th class="px-2 pb-3">Date</th><th class="px-2 pb-3">Customer</th><th class="px-2 pb-3">Total</th><th class="px-2 pb-3">Payment</th><th class="px-2 pb-3">Fulfillment</th><th class="px-2 pb-3 text-right">Action</th></tr></thead><tbody class="divide-y divide-outline-variant/10">
                    @forelse ($recentOrders as $order)
                        <tr class="transition hover:bg-surface-container-high/30"><td class="px-2 py-4 text-sm font-bold">{{ $order['id'] }}</td><td class="px-2 py-4 text-xs text-on-surface-variant">{{ $order['date'] }}</td><td class="px-2 py-4 text-sm font-bold">{{ $order['customer'] }}</td><td class="px-2 py-4 text-sm font-bold">{!! $order['total'] !!}</td><td class="px-2 py-4"><span class="inline-flex items-center gap-1 rounded border px-2 py-1 text-[10px] font-bold uppercase tracking-widest {{ $order['tone'] === 'primary' ? 'border-primary-container/20 bg-primary-container/10 text-primary-container' : 'border-outline-variant/20 bg-surface-container-highest text-on-surface-variant' }}"><span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $order['payment'] }}</span></td><td class="px-2 py-4"><span class="inline-flex items-center gap-1 rounded border border-outline-variant/20 bg-surface-container-highest px-2 py-1 text-[10px] font-bold uppercase tracking-widest {{ $order['fulfillment'] === 'Processing' ? 'text-secondary' : 'text-on-surface-variant' }}"><span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $order['fulfillment'] }}</span></td><td class="px-2 py-4 text-right"><button type="button" class="text-xs font-bold uppercase tracking-widest text-on-surface-variant transition hover:text-primary-container">View</button></td></tr>
                    @empty
                        <tr><td colspan="7" class="px-2 py-12 text-center text-sm text-on-surface-variant">No orders have been recorded yet.</td></tr>
                    @endforelse
                </tbody></table></div>
            </section>
        </div>
    </main>

    <nav class="fixed bottom-0 z-50 flex h-16 w-full items-center justify-around border-t border-outline-variant/20 bg-black/95 backdrop-blur-xl lg:hidden">
        @foreach ([['icon' => 'dashboard', 'label' => 'Dash', 'href' => route('admin.dashboard')], ['icon' => 'shopping_bag', 'label' => 'Orders', 'href' => route('admin.orders')], ['icon' => 'inventory_2', 'label' => 'Products', 'href' => route('admin.products')], ['icon' => 'settings', 'label' => 'Settings', 'href' => route('admin.settings')]] as $item)
            <a href="{{ $item['href'] }}" class="flex flex-col items-center text-{{ $item['label'] === 'Dash' ? 'primary-container' : 'on-surface-variant' }}"><span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span><span class="mt-1 text-[9px] font-bold uppercase tracking-widest">{{ $item['label'] }}</span></a>
        @endforeach
    </nav>
</body>
</html>
