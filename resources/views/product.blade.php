<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product['detail_name'] }} | {{ config('app.name', 'Soléa Fashion Co.') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface": "#0e0e0e",
                        "surface-container": "#1a1919",
                        "surface-container-low": "#131313",
                        "surface-container-high": "#201f1f",
                        "surface-container-highest": "#262626",
                        "background": "#0e0e0e",
                        "primary": "#f5ffc4",
                        "primary-container": "#d5fb00",
                        "on-primary-container": "#4e5d00",
                        "on-surface": "#ffffff",
                        "on-surface-variant": "#adaaaa",
                        "outline-variant": "#484847",
                        "secondary": "#7799ff"
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem"
                    },
                    fontFamily: {
                        headline: ["Plus Jakarta Sans"],
                        body: ["Inter"],
                        label: ["Inter"]
                    }
                }
            }
        };
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .kinetic-gradient {
            background: linear-gradient(135deg, #f5ffc4 0%, #d5fb00 100%);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface selection:bg-primary-container selection:text-on-primary-container">
@include('partials.demo-notice')
    <nav class="fixed top-0 z-50 w-full bg-black/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1200px] items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="font-headline text-xl font-black italic tracking-tighter text-[#D9FF00]">Soléa Fashion Co.</a>
            <div class="hidden items-center space-x-8 font-headline tracking-tight md:flex">
                <a class="border-b-2 border-[#D9FF00] pb-1 font-bold text-[#D9FF00]" href="{{ route('shop') }}">COLLECTIONS</a>
                <a class="font-medium text-white/60 transition-colors hover:text-white" href="{{ route('contact') }}">CONTACT</a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="{{ route('cart') }}" class="text-[#D9FF00] transition-opacity hover:opacity-80" aria-label="Shopping bag">
                    <span class="material-symbols-outlined">shopping_bag</span>
                </a>
                @include('partials.account-menu')
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-[1200px] px-6 pb-20 pt-24 lg:px-0">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-20">
            <div class="space-y-6 lg:col-span-7">
                <div id="product-image-frame" class="group relative aspect-[4/5] cursor-zoom-in overflow-hidden rounded-xl bg-surface-container-low" role="button" tabindex="0" aria-label="Zoom product image">
                    <img id="product-main-image" alt="{{ $product['detail_name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" src="{{ $product['image'] }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=900&q=85';">
                    <div class="absolute left-6 top-6">
                        <span class="rounded-full bg-secondary px-4 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-surface">{{ $product['badge'] }}</span>
                    </div>
                    <button type="button" class="gallery-arrow gallery-prev absolute left-4 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/70 bg-black/45 text-white backdrop-blur-sm transition hover:bg-secondary hover:text-surface" aria-label="Previous product photo">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button type="button" class="gallery-arrow gallery-next absolute right-4 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/70 bg-black/45 text-white backdrop-blur-sm transition hover:bg-secondary hover:text-surface" aria-label="Next product photo">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>

                <div class="grid max-w-[360px] grid-cols-3 gap-3">
                    @foreach (collect([$product['image'], ...($product['gallery'] ?? [])])->filter()->take(3)->values() as $index => $image)
                        @php($angle = ['Front view', 'Alternate view', 'Hanging view'][$index] ?? 'Gallery view')
                        <button type="button" class="product-gallery-thumb aspect-square overflow-hidden rounded-lg border-2 border-transparent bg-surface-container transition-opacity hover:opacity-100" data-gallery-image="{{ $image }}" aria-label="Show {{ strtolower($angle) }}">
                            <img alt="{{ $angle }}" class="h-full w-full object-cover" src="{{ $image }}" onerror="this.onerror=null;this.src='{{ $product['image'] }}';">
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col justify-center lg:col-span-5">
                <header class="mb-8">
                    <div class="mb-4 flex items-center gap-4">
                        <span class="font-label text-[11px] font-bold uppercase tracking-[0.3em] text-primary-container">{{ $product['detail_series'] }}</span>
                        <div class="h-px flex-1 bg-outline-variant/20"></div>
                    </div>
                    <h1 class="mb-4 font-headline text-5xl font-black italic uppercase leading-none tracking-tighter lg:text-7xl">{{ $product['detail_name'] }}</h1>
                    <div class="flex items-baseline gap-4">
                        <p class="font-headline text-3xl font-bold italic text-on-surface">{!! $product['sale_price'] !!}</p>
                        @if (($product['stock'] ?? 0) < 1)
                            <p class="mt-3 inline-block bg-error px-3 py-1 text-xs font-black uppercase tracking-widest text-white">Out of stock</p>
                        @else
                            <p id="stock-availability" class="mt-3 text-xs font-bold uppercase tracking-widest text-primary-container">M: {{ $product['stock_by_size']['M'] }} available</p>
                        @endif
                        <span class="text-sm text-on-surface-variant line-through">{!! $product['compare_price'] !!}</span>
                    </div>
                </header>

                <div class="mb-10 max-w-md">
                    <p class="font-body text-lg leading-relaxed text-on-surface-variant">{{ $product['description'] }}</p>
                </div>

                <div class="mb-10">
                    <div class="mb-4 flex items-center justify-between">
                        <label class="font-label text-[10px] font-black uppercase tracking-widest text-on-surface">SELECT SIZE</label>
                        <button id="size-guide-trigger" class="text-[10px] font-bold uppercase tracking-widest text-secondary hover:underline" type="button">SIZE GUIDE</button>
                    </div>
                    <div id="size-selector" class="grid grid-cols-4 gap-3" data-selected-size="M">
                        @foreach (['S', 'M', 'L', 'XL'] as $size)
                            <button class="size-option {{ $size === 'M' ? 'border-2 border-primary-container bg-primary-container/5 text-primary-container' : 'border border-outline-variant/30 hover:border-primary-container hover:text-primary-container' }} py-4 text-sm font-bold transition-all {{ (($product['stock_by_size'][$size] ?? 0) < 1) ? 'cursor-not-allowed opacity-40' : '' }}" data-size="{{ $size }}" data-stock="{{ (int) ($product['stock_by_size'][$size] ?? 0) }}" aria-pressed="{{ $size === 'M' ? 'true' : 'false' }}" type="button" {{ (($product['stock_by_size'][$size] ?? 0) < 1) ? 'disabled' : '' }}>{{ $size }}<span class="mt-1 block text-[9px] font-normal">{{ (int) ($product['stock_by_size'][$size] ?? 0) }} left</span></button>
                        @endforeach
                    </div>
                    <p id="selected-size" class="mt-3 text-[10px] font-bold uppercase tracking-widest text-primary-container">Selected size: M</p>
                </div>

                <form action="{{ route('wishlist.toggle', $product['slug']) }}" method="POST" class="mb-4">@csrf<button type="submit" class="flex w-full items-center justify-center gap-2 border border-outline-variant/30 py-4 text-xs font-black uppercase tracking-widest text-on-surface transition-colors hover:border-primary-container hover:text-primary-container"><span class="material-symbols-outlined">favorite</span>{{ in_array($product['slug'], session('customer.wishlist', []), true) ? 'Remove from Wishlist' : 'Add to Wishlist' }}</button></form>
                <div class="mb-12 space-y-4">
                    <a id="add-to-cart" href="{{ route('cart', ['size' => 'M']) }}" data-cart-url="{{ route('cart') }}" class="kinetic-gradient block w-full py-5 text-center font-label font-black uppercase tracking-widest text-on-primary-container transition-transform active:scale-[0.98]">
                        ADD TO CART
                    </a>
                    <a id="buy-now" href="{{ route('checkout', ['size' => 'M', 'product' => $product['slug']]) }}" data-checkout-url="{{ route('checkout') }}" class="block w-full border-2 border-on-surface py-5 text-center font-label font-black uppercase tracking-widest text-on-surface transition-all hover:bg-on-surface hover:text-surface active:scale-[0.98]">
                        BUY NOW
                    </a>
                </div>

                <div class="border-t border-outline-variant/10 pt-8">
                    <div class="no-scrollbar mb-6 flex gap-8 overflow-x-auto border-b border-outline-variant/10 pb-4">
                        <button class="product-tab whitespace-nowrap text-[11px] font-bold uppercase tracking-widest text-primary-container" data-tab="description" aria-selected="true" type="button">Description</button>
                        <button class="product-tab whitespace-nowrap text-[11px] font-bold uppercase tracking-widest text-on-surface-variant transition-colors hover:text-on-surface" data-tab="materials" aria-selected="false" type="button">Materials</button>
                        <button class="product-tab whitespace-nowrap text-[11px] font-bold uppercase tracking-widest text-on-surface-variant transition-colors hover:text-on-surface" data-tab="sizing" aria-selected="false" type="button">Sizing Guide</button>
                    </div>
                    <div id="product-tab-content" class="space-y-4 font-body text-sm leading-relaxed text-on-surface-variant">
                        <p data-panel="description">{{ $product['details'] }}</p>
                        <div class="hidden space-y-4" data-panel="materials">
                            <p>{{ $product['materials'] }}</p>
                            <ul class="list-none space-y-2">
                                <li class="flex items-center gap-3"><span class="h-1 w-1 rounded-full bg-primary-container"></span><span>100% premium cotton</span></li>
                                <li class="flex items-center gap-3"><span class="h-1 w-1 rounded-full bg-primary-container"></span><span>Double-needle stitched neck and hems</span></li>
                                <li class="flex items-center gap-3"><span class="h-1 w-1 rounded-full bg-primary-container"></span><span>Pre-shrunk for consistent fit</span></li>
                            </ul>
                        </div>
                        <div class="hidden" data-panel="sizing">
                            <p>{{ $product['sizing_guide'] }}</p>
                            <div class="overflow-hidden border border-outline-variant/20">
                                <div class="grid grid-cols-4 bg-surface-container-high px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-on-surface"><span>Size</span><span>Chest</span><span>Length</span><span>Fit</span></div>
                                <div class="grid grid-cols-4 border-t border-outline-variant/10 px-3 py-2 text-xs"><span>S</span><span>36 in</span><span>27 in</span><span>Regular</span></div>
                                <div class="grid grid-cols-4 border-t border-outline-variant/10 px-3 py-2 text-xs"><span>M</span><span>40 in</span><span>28 in</span><span>Regular</span></div>
                                <div class="grid grid-cols-4 border-t border-outline-variant/10 px-3 py-2 text-xs"><span>L</span><span>44 in</span><span>29 in</span><span>Relaxed</span></div>
                                <div class="grid grid-cols-4 border-t border-outline-variant/10 px-3 py-2 text-xs"><span>XL</span><span>48 in</span><span>30 in</span><span>Relaxed</span></div>
                            </div>
                        </div>
                        <ul data-panel="description" class="list-none space-y-2">
                            <li class="flex items-center gap-3">
                                <span class="h-1 w-1 rounded-full bg-primary-container"></span>
                                <span>Double-needle stitched neck and hems</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="h-1 w-1 rounded-full bg-primary-container"></span>
                                <span>Pre-shrunk for consistent fit</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <section class="mt-32">
            <div class="mb-12 flex flex-col items-start justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <h2 class="mb-2 font-headline text-4xl font-black italic uppercase tracking-tighter">Complement the Look</h2>
                    <p class="font-body text-on-surface-variant">Curated pieces to complete your digital uniform.</p>
                </div>
                <div class="mx-12 hidden h-[2px] flex-1 bg-outline-variant/10 md:block"></div>
                <a class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-primary-container" href="{{ route('shop') }}">
                    View Full Collection
                    <span class="material-symbols-outlined text-sm transition-transform group-hover:translate-x-1">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('products.show', $related['slug']) }}" class="group block">
                        <div class="relative mb-6 aspect-[3/4] overflow-hidden rounded-lg bg-surface-container-low">
                            <img alt="{{ $related['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ $related['image'] }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=900&q=85';">
                            <div class="absolute bottom-4 right-4 translate-y-8 opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">
                                <span class="block rounded-full bg-primary-container p-3 text-on-primary-container">
                                    <span class="material-symbols-outlined">add</span>
                                </span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-headline text-lg font-bold italic uppercase tracking-tight">{{ $related['name'] }}</h3>
                            <p class="font-label text-sm uppercase tracking-widest text-on-surface-variant">{!! $related['price'] !!}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="w-full border-t border-white/5 bg-black">
        <div class="mx-auto flex max-w-[1200px] flex-col items-center justify-between gap-8 px-6 py-12 md:flex-row">
            <div class="flex flex-col items-center gap-4 md:items-start">
                <div class="font-headline text-lg font-black italic tracking-tighter text-[#D9FF00]">Soléa Fashion Co.</div>
                <p class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/30">&copy;2024 SOLÉA FASHION CO. SOLÉA FASHION CO.. ALL RIGHTS RESERVED.</p>
            </div>
            <div class="flex gap-8">
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/30 transition-colors hover:text-[#D9FF00]" href="#">PRIVACY</a>
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/30 transition-colors hover:text-[#D9FF00]" href="#">TERMS</a>
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/30 transition-colors hover:text-[#D9FF00]" href="#">SHIPPING</a>
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/30 transition-colors hover:text-[#D9FF00]" href="#">RETURNS</a>
            </div>
            <div class="flex gap-4">
                <span class="group flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-white/10 transition-colors hover:border-[#D9FF00]">
                    <span class="material-symbols-outlined text-sm text-white/30 group-hover:text-[#D9FF00]">share</span>
                </span>
                <span class="group flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-white/10 transition-colors hover:border-[#D9FF00]">
                    <span class="material-symbols-outlined text-sm text-white/30 group-hover:text-[#D9FF00]">language</span>
                </span>
            </div>
        </div>
    </footer>
    <script>
        (() => {
            const selector = document.getElementById('size-selector');
            const label = document.getElementById('selected-size');
            const addToCart = document.getElementById('add-to-cart');
            const buyNow = document.getElementById('buy-now');
            const mainImage = document.getElementById('product-main-image');
            const imageFrame = document.getElementById('product-image-frame');
            const galleryThumbs = document.querySelectorAll('.product-gallery-thumb');
            const galleryImages = [...galleryThumbs].map(thumb => thumb.dataset.galleryImage);
            const previousButton = document.querySelector('.gallery-prev');
            const nextButton = document.querySelector('.gallery-next');
            let galleryIndex = 0;
            const sizeGuideTrigger = document.getElementById('size-guide-trigger');
            const selectedClasses = ['border-2', 'border-primary-container', 'bg-primary-container/5', 'text-primary-container'];
            const defaultClasses = ['border', 'border-outline-variant/30'];
            const product = {
                slug: @json($product['slug']),
                name: @json(ucwords(strtolower($product['name']))),
                price: {{ (int) preg_replace('/[^0-9]/', '', html_entity_decode($product['sale_price'])) }},
                image: @json($product['image']),
                stock: {{ (int) ($product['stock'] ?? 0) }},
                stockBySize: @json($product['stock_by_size'] ?? []),
            };
            const readCart = () => JSON.parse(localStorage.getItem('threadlab_cart') || '[]');
            const saveCart = (items) => localStorage.setItem('threadlab_cart', JSON.stringify(items));
            const selectedStock = () => Number(product.stockBySize[selector.dataset.selectedSize || 'M'] || 0);
            const syncStock = () => {
                const size = selector.dataset.selectedSize || 'M';
                const available = selectedStock();
                const availability = document.getElementById('stock-availability');
                if (availability) availability.textContent = `${size}: ${available} available`;
                [addToCart, buyNow].forEach(button => {
                    button.classList.toggle('pointer-events-none', available < 1);
                    button.classList.toggle('opacity-40', available < 1);
                    button.textContent = available < 1 ? 'OUT OF STOCK' : button.dataset.label;
                });
            };
            addToCart.dataset.label = 'ADD TO CART';
            buyNow.dataset.label = 'BUY NOW';
            const selectGalleryImage = (thumb) => {
                if (! mainImage || ! thumb) return;
                mainImage.src = thumb.dataset.galleryImage;
                galleryIndex = [...galleryThumbs].indexOf(thumb);
                galleryThumbs.forEach(item => item.classList.toggle('border-primary-container', item === thumb));
            };
            const selectGalleryIndex = (index) => {
                if (! galleryImages.length) return;
                const nextIndex = (index + galleryImages.length) % galleryImages.length;
                selectGalleryImage(galleryThumbs[nextIndex]);
            };
            if (galleryThumbs.length) {
                selectGalleryImage(galleryThumbs[0]);
                galleryThumbs.forEach(thumb => thumb.addEventListener('click', () => selectGalleryImage(thumb)));
                [previousButton, nextButton].forEach(button => button?.addEventListener('click', event => event.stopPropagation()));
                previousButton?.addEventListener('click', () => selectGalleryIndex(galleryIndex - 1));
                nextButton?.addEventListener('click', () => selectGalleryIndex(galleryIndex + 1));
            }
            if (imageFrame && mainImage) {
                let zoomed = false;
                const toggleZoom = () => {
                    zoomed = ! zoomed;
                    imageFrame.classList.toggle('cursor-zoom-out', zoomed);
                    imageFrame.classList.toggle('cursor-zoom-in', ! zoomed);
                    mainImage.style.transform = zoomed ? 'scale(1.8)' : '';
                };
                imageFrame.addEventListener('click', toggleZoom);
                imageFrame.addEventListener('pointermove', event => {
                    if (! zoomed) return;
                    const bounds = imageFrame.getBoundingClientRect();
                    const x = ((event.clientX - bounds.left) / bounds.width) * 100;
                    const y = ((event.clientY - bounds.top) / bounds.height) * 100;
                    mainImage.style.transformOrigin = `${x}% ${y}%`;
                });
                imageFrame.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        toggleZoom();
                    }
                });
                let touchStartX = 0;
                imageFrame.addEventListener('touchstart', event => { touchStartX = event.changedTouches[0].screenX; }, { passive: true });
                imageFrame.addEventListener('touchend', event => {
                    const distance = event.changedTouches[0].screenX - touchStartX;
                    if (Math.abs(distance) < 45) return;
                    selectGalleryIndex(galleryIndex + (distance < 0 ? 1 : -1));
                }, { passive: true });
            }
            const addProduct = (replace = false) => {
                if (selectedStock() < 1) return;
                const item = { ...product, stock: selectedStock(), size: selector.dataset.selectedSize || 'M', quantity: 1 };
                let cart = replace ? [] : readCart();
                const existing = cart.find(existingItem => existingItem.slug === item.slug && existingItem.size === item.size);
                if (existing && !replace) existing.quantity = Math.min(selectedStock(), existing.quantity + 1);
                else cart.push(item);
                saveCart(cart);
            };

            selector.querySelectorAll('.size-option').forEach(option => option.addEventListener('click', () => {
                selector.querySelectorAll('.size-option').forEach(item => {
                    item.classList.remove(...selectedClasses);
                    item.classList.add(...defaultClasses);
                    item.setAttribute('aria-pressed', 'false');
                });
                option.classList.remove(...defaultClasses);
                option.classList.add(...selectedClasses);
                option.setAttribute('aria-pressed', 'true');
                selector.dataset.selectedSize = option.dataset.size;
                label.textContent = `Selected size: ${option.dataset.size}`;
                addToCart.href = `${addToCart.dataset.cartUrl}?size=${encodeURIComponent(option.dataset.size)}`;
                buyNow.href = `${buyNow.dataset.checkoutUrl}?size=${encodeURIComponent(option.dataset.size)}&product={{ $product['slug'] }}`;
                syncStock();
            }));

            syncStock();
            addToCart.addEventListener('click', () => addProduct());
            buyNow.addEventListener('click', () => addProduct(true));

            const tabs = document.querySelectorAll('.product-tab');
            const panels = document.querySelectorAll('[data-panel]');
            const selectTab = (tabName) => {
                tabs.forEach(tab => {
                    const active = tab.dataset.tab === tabName;
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                    tab.classList.toggle('text-primary-container', active);
                    tab.classList.toggle('text-on-surface-variant', !active);
                });
                panels.forEach(panel => panel.classList.toggle('hidden', panel.dataset.panel !== tabName));
            };
            tabs.forEach(tab => tab.addEventListener('click', () => selectTab(tab.dataset.tab)));
            sizeGuideTrigger.addEventListener('click', () => {
                selectTab('sizing');
                document.getElementById('product-tab-content').scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        })();
    </script>
</body>
</html>
