<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $definition['title'] }} | Soléa Fashion Co.</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="{{ asset('css/admin-brand.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: {
            colors: { primary: '#A6533E', 'primary-container': '#A6533E', 'on-primary-container': '#FFFFFF', background: '#201A17', surface: '#FFFDFC', 'surface-container-highest': '#EFE5DB', 'on-surface-variant': '#735F56', secondary: '#7799FF' },
            fontFamily: { headline: ['Plus Jakarta Sans', 'sans-serif'], body: ['Inter', 'sans-serif'] }
        } } };
    </script>
</head>
<body class="admin-brand min-h-screen bg-background text-white">`r
@include('partials.demo-notice')
@include('partials.admin-header')
<aside class="fixed left-0 top-0 hidden h-full w-64 border-r border-outline-variant/20 bg-surface px-6 pb-8 pt-24 lg:block">
    <a href="{{ route('admin.dashboard') }}" class="mb-8 block font-headline text-xl font-extrabold uppercase">Soléa Fashion Co. <span class="text-xs font-normal text-muted">| Admin</span></a>
    <nav class="space-y-1">
        @foreach ([['Dashboard','dashboard',route('admin.dashboard')],['Orders','shopping_bag',route('admin.orders')],['Products','inventory_2',route('admin.products')],['Inventory','warehouse',route('admin.inventory')],['Customers','group',route('admin.customers')],['Discounts','local_offer',route('admin.discounts')],['Reviews','reviews',route('admin.reviews')],['Analytics','analytics',route('admin.analytics')],['Settings','settings',route('admin.settings')]] as $item)
            <a href="{{ $item[2] }}" class="flex items-center gap-3 border-l-4 px-3 py-3 text-xs font-bold uppercase tracking-widest {{ $section === strtolower($item[0]) ? 'border-primary bg-primary/10 text-primary' : 'border-transparent text-muted hover:bg-surface-container-high hover:text-primary' }}"><span class="material-symbols-outlined text-lg">{{ $item[1] }}</span>{{ $item[0] }}</a>
        @endforeach
    </nav>
    <div class="mt-8 border-t border-outline-variant/20 pt-5">
        <a href="{{ route('admin.logout') }}" class="flex items-center gap-3 py-3 text-xs font-bold uppercase tracking-widest text-muted hover:text-primary"><span class="material-symbols-outlined">logout</span>Exit Admin</a>
    </div>
</aside>
<main class="min-h-screen px-6 pb-16 pt-24 lg:ml-64 lg:px-12">
    <div class="mx-auto max-w-[1400px]">
        <header class="mb-8 flex flex-col justify-between gap-5 border-b border-outline-variant/20 pb-8 md:flex-row md:items-end">
            <div><p class="mb-3 text-xs font-bold uppercase tracking-[.25em] text-primary">Soléa / Admin Manager</p><h1 class="flex items-center gap-3 font-headline text-4xl font-extrabold uppercase"><span class="material-symbols-outlined text-primary">{{ $definition['icon'] }}</span>{{ $definition['title'] }}</h1><p class="mt-3 text-sm text-muted">{{ $definition['description'] }}</p></div>
            <a href="{{ route('admin.dashboard') }}" class="border border-outline-variant/30 px-5 py-3 text-xs font-bold uppercase tracking-widest text-on-surface hover:border-primary hover:text-primary">Dashboard Overview</a>
        </header>
        <section class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ([['Orders',$storeCounts['orders'],'shopping_bag'],['Customers',$storeCounts['customers'],'group'],['Products',$storeCounts['products'],'inventory_2']] as $count)
                <div class="border border-outline-variant/20 bg-surface p-5"><span class="material-symbols-outlined text-primary">{{ $count[2] }}</span><p class="mt-4 font-headline text-3xl font-bold">{{ $count[1] }}</p><p class="mt-1 text-xs uppercase tracking-widest text-muted">Recorded {{ $count[0] }}</p></div>
            @endforeach
        </section>
        @if($section === 'orders')
            <form method="GET" class="mb-6 flex flex-wrap items-center gap-2 border border-outline-variant/20 bg-surface p-4"><input name="search" value="{{ $orderFilters['search'] }}" placeholder="Search order number, customer, or email" class="min-w-[260px] flex-1 border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface"><select name="status" onchange="this.form.submit()" class="border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface"><option value="">All statuses</option>@foreach($orderFilters['statuses'] as $status)<option value="{{ $status }}" @selected($orderFilters['status'] === $status)>{{ $status }}</option>@endforeach</select><button class="bg-primary px-4 py-2 text-sm font-bold text-white">Apply</button></form>
        @elseif($section === 'customers')
            <form method="GET" class="mb-6 flex flex-wrap items-center gap-2 border border-outline-variant/20 bg-surface p-4"><input name="search" value="{{ $customerFilters['search'] }}" placeholder="Search name, email, phone, or address" class="min-w-[260px] flex-1 border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface"><select name="status" class="border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface"><option value="">All statuses</option><option value="Active" @selected($customerFilters['status'] === 'Active')>Active</option></select><button class="bg-primary px-4 py-2 text-sm font-bold text-white">Search Customers</button></form>
        @elseif($section === 'products')
            <form method="GET" class="mb-6 flex flex-wrap items-center gap-2 border border-outline-variant/20 bg-surface p-4"><input name="search" value="{{ $productFilters['search'] }}" placeholder="Search products or SKUs" class="min-w-[260px] flex-1 border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface"><select name="category" class="border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface"><option value="">All categories</option>@foreach($productFilters['categories'] as $category)<option value="{{ $category }}" @selected($productFilters['category'] === $category)>{{ $category }}</option>@endforeach</select><select name="status" class="border-outline-variant/30 bg-surface-container-high px-3 py-2 text-sm text-on-surface"><option value="">All statuses</option>@foreach(['Active','Draft','Scheduled','Archived'] as $status)<option value="{{ $status }}" @selected($productFilters['status'] === $status)>{{ $status }}</option>@endforeach</select><button class="bg-primary px-4 py-2 text-sm font-bold text-white">Apply filters</button></form>
        @endif
        @if($section === 'discounts')
            <section class="mb-8 border border-outline-variant/20 bg-surface p-6"><h2 class="mb-5 font-headline text-2xl font-bold">Create voucher</h2><form action="{{ route('admin.discounts.store') }}" method="POST" class="grid gap-4 md:grid-cols-4">@csrf<label class="text-xs font-bold uppercase tracking-wider text-muted">Code<input name="code" required class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-on-surface" placeholder="SOLEA10"></label><label class="text-xs font-bold uppercase tracking-wider text-muted">Type<select name="type" class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-on-surface"><option value="percent">Percentage</option><option value="fixed">Fixed amount</option></select></label><label class="text-xs font-bold uppercase tracking-wider text-muted">Value<input name="value" type="number" min="0.01" step="0.01" required class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-on-surface"></label><label class="text-xs font-bold uppercase tracking-wider text-muted">Minimum spend<input name="minimum_spend" type="number" min="0" step="0.01" value="0" class="mt-2 w-full border-outline-variant/30 bg-surface-container-high px-3 py-3 text-on-surface"></label><button class="w-fit bg-primary px-5 py-3 font-bold text-white hover:bg-[#8E4433]">Save voucher</button></form></section>
        @endif
        @if($section === 'products' && $rows)
            <section class="mb-6 border border-outline-variant/20 bg-surface p-5">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div><h2 class="font-headline text-lg font-bold uppercase">Quick edit</h2><p class="mt-1 text-xs text-muted">Open a product editor directly from the catalog.</p></div>
                    <span class="material-symbols-outlined text-primary">edit</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($rows as $row)
                        <a href="{{ route('admin.products.edit', $row['slug']) }}" class="inline-flex items-center gap-2 border border-outline-variant/30 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary hover:border-primary hover:bg-primary hover:text-white"><span class="material-symbols-outlined text-sm">edit</span>{{ $row['primary'] }}</a>
                    @endforeach
                </div>
            </section>
        @endif
        @if($rows)
            <section class="overflow-x-auto border border-outline-variant/20 bg-surface"><table class="w-full min-w-[900px] text-left"><thead class="border-b border-outline-variant/20 text-xs font-bold uppercase tracking-widest text-muted"><tr><th class="px-6 py-4">{{ $section === 'products' ? 'Product' : ($section === 'customers' ? 'Customer' : ($section === 'discounts' ? 'Voucher' : 'Order')) }}</th><th class="px-6 py-4">Details</th><th class="px-6 py-4">Recorded</th><th class="px-6 py-4">Value</th><th class="px-6 py-4">Status</th></tr></thead><tbody class="divide-y divide-outline-variant/20">@foreach($rows as $row)<tr class="align-top hover:bg-surface-container-high"><td class="px-6 py-5"><p class="font-bold">{{ $row['primary'] }}</p><p class="mt-1 text-xs text-muted">{{ $row['secondary'] }}</p>@if($section === 'products')<a href="{{ route('admin.products.edit', $row['slug']) }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold uppercase tracking-widest text-primary"><span class="material-symbols-outlined text-sm">edit</span>Edit product</a>@endif</td><td class="max-w-md px-6 py-5 text-sm text-muted">{{ $row['detail'] }}</td><td class="px-6 py-5 text-sm text-muted">{{ $row['recorded'] ?? 'Recorded' }}</td><td class="px-6 py-5 font-bold text-primary">{!! $row['value'] !!}</td><td class="px-6 py-5">@if($section === 'orders')<form action="{{ route('admin.orders.status', $row['reference']) }}" method="POST">@csrf<select name="status" onchange="this.form.submit()" class="border border-outline-variant/30 bg-surface-container-high px-3 py-2 text-xs font-bold uppercase text-primary">@foreach($orderFilters['statuses'] as $status)<option @selected($row['status'] === $status)>{{ $status }}</option>@endforeach</select></form>@else<span class="text-xs font-bold uppercase tracking-widest text-primary">{{ $row['status'] }}</span>@endif</td></tr>@endforeach</tbody></table></section>
        @else
            <section class="border border-dashed border-outline-variant/30 bg-surface p-16 text-center"><span class="material-symbols-outlined mb-4 text-5xl text-primary">{{ $definition['icon'] }}</span><h2 class="font-headline text-2xl font-bold">No {{ strtolower($definition['title']) }} records yet</h2><p class="mx-auto mt-3 max-w-lg text-sm text-muted">New records will appear here as customers and administrators use the application.</p></section>
        @endif
    </div>
</main>
</body>
</html>
