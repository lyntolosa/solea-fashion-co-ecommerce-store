<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit {{ $product['name'] }} | SOLÉA FASHION CO. Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-brand.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>tailwind.config={darkMode:'class',theme:{extend:{colors:{primary:'#D9FF00',background:'#0B0B0B',surface:'#131313'},fontFamily:{headline:['Plus Jakarta Sans','sans-serif'],body:['Inter','sans-serif']}}}};</script>
</head>
<body class="admin-brand min-h-screen bg-black text-white">`r
@include('partials.demo-notice')
<aside class="fixed left-0 top-0 hidden h-full w-64 border-r border-white/10 bg-[#0a0a0a] px-6 py-8 lg:block">
    <a href="{{ route('admin.products') }}" class="mb-10 block font-headline text-xl font-extrabold uppercase">SOLÉA FASHION CO. <span class="text-xs font-normal text-muted">| Admin</span></a>
    <nav class="space-y-1">
        @foreach([['Dashboard','dashboard',route('admin.dashboard')],['Orders','shopping_bag',route('admin.orders')],['Products','inventory_2',route('admin.products')],['Inventory','warehouse',route('admin.inventory')],['Customers','group',route('admin.customers')],['Analytics','analytics',route('admin.analytics')],['Settings','settings',route('admin.settings')]] as $item)
            <a href="{{ $item[2] }}" class="flex items-center gap-3 border-l-4 px-3 py-3 text-xs font-bold uppercase tracking-widest {{ $item[0]==='Products'?'border-primary bg-white/10 text-primary':'border-transparent text-muted hover:bg-white/10 hover:text-white' }}"><span class="material-symbols-outlined text-lg">{{ $item[1] }}</span>{{ $item[0] }}</a>
        @endforeach
    </nav>
    <div class="mt-8 border-t border-white/10 pt-5">
        <a href="{{ route('admin.logout') }}" class="flex items-center gap-3 py-3 text-xs font-bold uppercase tracking-widest text-muted hover:text-red-400"><span class="material-symbols-outlined">logout</span>Exit Admin</a>
    </div>
</aside>

<main class="min-h-screen px-6 py-8 lg:ml-64 lg:px-12">
    <header class="mb-10 flex flex-col justify-between gap-5 border-b border-white/10 pb-8 md:flex-row md:items-end">
        <div>
            <a href="{{ route('admin.products') }}" class="text-xs font-bold uppercase tracking-[.25em] text-primary">Soléa / Products</a>
            <h1 class="mt-4 flex items-center gap-3 font-headline text-4xl font-extrabold uppercase"><span class="material-symbols-outlined text-primary">edit</span>Edit product</h1>
            <p class="mt-3 text-sm text-muted">Update the live product information shown on its storefront page.</p>
        </div>
        <a href="{{ route('admin.products') }}" class="border border-white/20 px-5 py-3 text-xs font-bold uppercase tracking-widest hover:border-primary hover:text-primary">Back to Products</a>
    </header>

    @if ($errors->any())
        <div class="mb-6 border border-red-400/40 bg-red-400/10 px-5 py-4 text-sm text-red-200"><ul class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('admin.inventory.products.update', $product['slug']) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        <section class="border border-white/10 bg-surface p-6 md:p-8">
            <div class="mb-6"><h2 class="font-headline text-2xl font-bold uppercase">Product information</h2><p class="mt-2 text-sm text-muted">These fields are synchronized with the live catalog.</p></div>
            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2"><label for="name" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Product title</label><input id="name" name="name" value="{{ old('name', $product['name']) }}" required class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none"></div>
                <div><label for="detail_name" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Single-page title</label><input id="detail_name" name="detail_name" value="{{ old('detail_name', $product['detail_name'] ?? $product['name']) }}" class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none"></div>
                <div><label for="sku" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">SKU</label><input id="sku" name="sku" value="{{ old('sku', $product['sku']) }}" required class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none"></div>
                <div><label for="category" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Category</label><select id="category" name="category" required class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none">@foreach($categories as $category)<option value="{{ $category }}" @selected(old('category', $product['category']) === $category)>{{ $category }}</option>@endforeach</select></div>
                <div><label for="price" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Price</label><input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product['price']) }}" required class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none"></div>
                <div><label for="status" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Status</label><select id="status" name="status" required class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none">@foreach(['Active','Draft','Scheduled','Archived'] as $status)<option value="{{ $status }}" @selected(old('status', $product['status']) === $status)>{{ $status }}</option>@endforeach</select></div>
                <div class="md:col-span-2"><label for="description" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Short description</label><textarea id="description" name="description" rows="4" class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none">{{ old('description', $product['description'] ?? '') }}</textarea></div>
                <div class="md:col-span-2"><label for="details" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Product details</label><textarea id="details" name="details" rows="5" class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none">{{ old('details', $product['details'] ?? '') }}</textarea></div>
                <div><label for="materials" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Materials</label><textarea id="materials" name="materials" rows="5" class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none">{{ old('materials', $product['materials'] ?? '') }}</textarea></div>
                <div><label for="sizing_guide" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Sizing guide</label><textarea id="sizing_guide" name="sizing_guide" rows="5" class="w-full border border-white/15 bg-black px-4 py-3 text-white focus:border-primary focus:outline-none">{{ old('sizing_guide', $product['sizing_guide'] ?? '') }}</textarea></div>
            </div>
        </section>

        <section class="border border-white/10 bg-surface p-6 md:p-8">
            <div class="mb-6"><h2 class="font-headline text-2xl font-bold uppercase">Product media</h2><p class="mt-2 text-sm text-muted">Upload image files directly. These replace the current thumbnail or gallery set when selected.</p></div>
            <div class="grid gap-6 md:grid-cols-2">
                <div><label for="image" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Thumbnail</label><input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full border border-white/15 bg-black px-4 py-3 text-sm text-muted file:mr-4 file:border-0 file:bg-primary file:px-3 file:py-2 file:font-bold file:text-black"><img src="{{ $product['image'] }}" alt="Current thumbnail" class="mt-4 h-32 w-32 object-cover"></div>
                <div><label for="gallery" class="mb-2 block text-xs font-bold uppercase tracking-widest text-muted">Gallery photos</label><input id="gallery" name="gallery[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="block w-full border border-white/15 bg-black px-4 py-3 text-sm text-muted file:mr-4 file:border-0 file:bg-primary file:px-3 file:py-2 file:font-bold file:text-black"><div class="mt-4 flex flex-wrap gap-3">@foreach($product['gallery'] ?? [] as $image)<img src="{{ $image }}" alt="Current gallery photo" class="h-24 w-24 object-cover">@endforeach</div></div>
            </div>
        </section>

        <div class="flex flex-wrap items-center gap-4"><button type="submit" class="flex items-center gap-2 bg-primary px-6 py-3 text-xs font-bold uppercase tracking-widest text-black hover:bg-lime-300"><span class="material-symbols-outlined text-sm">save</span>Save product changes</button><a href="{{ route('admin.products') }}" class="border border-white/20 px-6 py-3 text-xs font-bold uppercase tracking-widest text-muted hover:border-white hover:text-white">Cancel</a></div>
    </form>
</main>
</body>
</html>
