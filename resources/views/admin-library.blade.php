<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Image Library | Soléa Fashion Co.</title>
    <link rel="stylesheet" href="{{ asset('css/admin-brand.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        body { font-family: Inter, sans-serif; }
        .material-symbols-outlined { vertical-align: middle; }
    </style>
</head>
<body class="admin-brand min-h-screen">`r
@include('partials.demo-notice')
    <main class="mx-auto max-w-6xl p-6 md:p-10">
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <a href="{{ route('admin.inventory') }}" class="text-sm font-bold text-primary hover:underline">Back to Inventory</a>
                <h1 class="mt-4 font-headline text-4xl font-black">Image Library</h1>
                <p class="mt-2 text-on-surface-variant">Upload and store product thumbnails for the inventory catalog.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="font-bold text-on-surface-variant hover:text-primary">Admin Dashboard</a>
        </div>
        @if(session('library_status'))
            <div class="mb-6 border border-primary/30 bg-primary/10 p-4 text-primary">{{ session('library_status') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 border border-red-300/40 bg-red-100/40 p-4 text-red-800">{{ $errors->first() }}</div>
        @endif
        <form action="{{ route('admin.library.upload') }}" method="POST" enctype="multipart/form-data" class="mb-10 flex flex-col gap-4 border border-outline-variant/20 bg-surface p-6 sm:flex-row sm:items-end">
            @csrf
            <label class="flex-1 text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                Product thumbnail
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required class="mt-2 block w-full rounded border border-outline-variant/30 bg-surface-container-high p-2 text-sm text-on-surface">
            </label>
            <button class="bg-primary px-5 py-3 font-bold text-white hover:bg-[#8E4433]">Upload image</button>
        </form>
        <section class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @forelse($files as $file)
                <figure class="overflow-hidden border border-outline-variant/20 bg-surface">
                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="aspect-square w-full object-cover">
                    <figcaption class="truncate p-3 text-xs text-on-surface-variant">{{ $file['name'] }}</figcaption>
                </figure>
            @empty
                <div class="col-span-full border border-dashed border-outline-variant/30 bg-surface p-12 text-center text-on-surface-variant">No uploaded thumbnails yet. Upload your first product image above.</div>
            @endforelse
        </section>
    </main>
</body>
</html>
