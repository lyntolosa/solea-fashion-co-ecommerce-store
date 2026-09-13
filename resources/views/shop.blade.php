<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shop Collection | {{ config('app.name', 'Soléa Fashion Co.') }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "secondary-fixed": "#c4d0ff",
                        "outline-variant": "#484847",
                        "on-secondary-container": "#f8f7ff",
                        "on-primary-container": "#4e5d00",
                        "on-primary": "#556600",
                        "secondary-fixed-dim": "#b0c2ff",
                        "surface-dim": "#0e0e0e",
                        "tertiary-container": "#fedb42",
                        "on-surface": "#ffffff",
                        "error-dim": "#d53d18",
                        "surface-container-high": "#201f1f",
                        "tertiary": "#ffeaa2",
                        "on-background": "#ffffff",
                        "primary-container": "#d5fb00",
                        "on-error-container": "#ffd2c8",
                        "surface-bright": "#2c2c2c",
                        "surface": "#0e0e0e",
                        "on-secondary-fixed-variant": "#0047bd",
                        "primary-fixed-dim": "#c8ec00",
                        "surface-container-low": "#131313",
                        "surface-container": "#1a1919",
                        "on-tertiary-fixed": "#473b00",
                        "background": "#0e0e0e",
                        "primary": "#f5ffc4",
                        "tertiary-dim": "#efcd34",
                        "inverse-on-surface": "#565554",
                        "surface-container-lowest": "#000000",
                        "primary-dim": "#cbef00",
                        "surface-variant": "#262626",
                        "on-secondary-fixed": "#002d80",
                        "surface-tint": "#f5ffc4",
                        "surface-container-highest": "#262626",
                        "tertiary-fixed": "#fedb42",
                        "on-secondary": "#001b55",
                        "inverse-surface": "#fcf8f8",
                        "error": "#ff7351",
                        "on-primary-fixed-variant": "#576800",
                        "on-primary-fixed": "#3d4a00",
                        "error-container": "#b92902",
                        "on-error": "#450900",
                        "on-tertiary-fixed-variant": "#685700",
                        "inverse-primary": "#556600",
                        "tertiary-fixed-dim": "#efcd34",
                        "primary-fixed": "#d5fb00",
                        "secondary-dim": "#316bf3",
                        "on-tertiary-container": "#5d4d00",
                        "secondary": "#7799ff",
                        "on-surface-variant": "#adaaaa",
                        "secondary-container": "#0053db",
                        "outline": "#777575",
                        "on-tertiary": "#675600"
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

        .tonal-shift-via-bg-surface-container-low {
            background-color: rgba(19, 19, 19, 0.8);
        }

        .glass-gradient {
            background: linear-gradient(135deg, #f5ffc4 0%, #d5fb00 100%);
        }

        body {
            background-color: #0e0e0e;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
        }

        .shop-filter.is-active {
            background-color: #9f4f3d !important;
            color: #fffdfa !important;
        }

        .shop-filter:not(.is-active) {
            background-color: #e4d8cd;
            color: #241c18;
        }

        .shop-filter:not(.is-active):hover {
            background-color: #74372d;
            color: #fffdfa;
        }

        .shop-sort-select {
            color-scheme: light;
            color: #241c18;
            background-color: #fffdfa;
        }

        .shop-sort-select option {
            background-color: #fffdfa;
            color: #241c18;
        }

        .shop-sort-select option:checked,
        .shop-sort-select option:hover {
            background-color: #d5fb00;
            color: #000000;
        }
    </style>
</head>
<body class="bg-background text-on-background">`r
@include('partials.demo-notice')
    <nav class="fixed top-0 z-50 w-full bg-black/80 px-6 py-4 backdrop-blur-xl">
        <div class="mx-auto flex w-full max-w-[1200px] items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="font-headline text-xl font-black italic tracking-tighter text-[#d5fb00]">Soléa Fashion Co.</a>
                <div class="hidden items-center gap-6 md:flex">
                    <a class="font-headline uppercase tracking-tighter text-white/70 transition-colors duration-300 hover:text-[#d5fb00]" href="{{ route('shop') }}">COLLECTIONS</a>
                    <a class="font-headline uppercase tracking-tighter text-white/70 transition-colors duration-300 hover:text-[#d5fb00]" href="{{ route('contact') }}">CONTACT</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('cart') }}" class="material-symbols-outlined text-white/70 transition-colors hover:text-[#d5fb00]" aria-label="Shopping bag">shopping_bag</a>
                @include('partials.account-menu')
            </div>
        </div>
    </nav>

    <main class="pb-12 pt-24">
        <header class="mx-auto max-w-[1200px] px-6 py-16 md:px-0 md:py-24">
            <div class="relative mb-4 inline-block">
                <h1 class="font-headline mb-4 text-6xl font-black italic uppercase leading-[0.9] tracking-tighter text-white md:text-8xl">Shop <span class="text-primary-container">Collection.</span></h1>
                <div class="absolute -right-8 -top-4 bg-primary-container px-3 py-1 font-headline text-[10px] font-black uppercase tracking-widest text-on-primary-container">SOLÉA FASHION CO.</div>
            </div>
            <p class="mt-6 max-w-2xl font-body text-lg text-on-surface-variant md:text-xl">
                Explore our curated line of premium t-shirts. Engineered for the high-velocity urban lifestyle.
            </p>
        </header>

        <section class="shop-controls sticky top-16 z-40 border-y border-outline-variant/10 bg-surface/90 px-6 py-6 backdrop-blur-md md:px-0">
            <div class="mx-auto flex max-w-[1200px] flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div class="flex flex-wrap gap-3">
                    <button class="shop-filter is-active px-6 py-2 font-headline text-xs font-black uppercase tracking-widest transition-transform active:scale-95" data-filter="all" type="button">All</button>
                    <button class="shop-filter rounded-full bg-surface-container-high px-6 py-2 text-xs font-bold uppercase tracking-widest text-white transition-colors hover:text-primary-container" data-filter="basic" type="button">Basic</button>
                    <button class="shop-filter rounded-full bg-surface-container-high px-6 py-2 text-xs font-bold uppercase tracking-widest text-white transition-colors hover:text-primary-container" data-filter="oversized" type="button">Oversized</button>
                    <button class="shop-filter rounded-full bg-surface-container-high px-6 py-2 text-xs font-bold uppercase tracking-widest text-white transition-colors hover:text-primary-container" data-filter="minimal" type="button">Minimal</button>
                </div>
                <div class="flex items-center gap-4 rounded-lg bg-surface-container-low px-4 py-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Sort By:</span>
                    <select id="shop-sort" class="shop-sort-select cursor-pointer rounded border border-outline-variant/20 px-3 py-2 text-xs font-bold uppercase tracking-widest focus:border-primary-container focus:ring-1 focus:ring-primary-container">
                        <option value="newest">Newest First</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="name">Name: A to Z</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-[1200px] px-6 py-16 md:px-0">
            <div id="shop-grid" class="grid grid-cols-1 gap-x-8 gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $products = [
                        ['slug' => 'essential-black-tee', 'series' => 'CORE SERIES', 'name' => 'ESSENTIAL BLACK TEE', 'price' => '&#8369;799.00', 'category' => 'basic', 'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'studio-white-tee', 'series' => 'MINIMALIST', 'name' => 'STUDIO WHITE TEE', 'price' => '&#8369;799.00', 'category' => 'minimal', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'graphic-logo-tee', 'series' => 'SOLÉA FASHION CO.', 'name' => 'GRAPHIC LOGO TEE', 'price' => '&#8369;950.00', 'category' => 'minimal', 'image' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'oversized-charcoal-tee', 'series' => 'RELAXED FIT', 'name' => 'OVERSIZED CHARCOAL TEE', 'price' => '&#8369;1,100.00', 'category' => 'oversized', 'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'tech-wear-tactical-tee', 'series' => 'PROTOTYPE', 'name' => 'TECH-WEAR TACTICAL TEE', 'price' => '&#8369;1,200.00', 'category' => 'oversized', 'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'vintage-wash-renegade-tee', 'series' => 'ARCHIVE', 'name' => 'VINTAGE WASH RENEGADE TEE', 'price' => '&#8369;1,050.00', 'category' => 'basic', 'image' => 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85'],
                    ];
                @endphp
                @php $products = $liveProducts; @endphp

                @foreach ($products as $index => $product)
                    <a href="{{ route('products.show', ['slug' => $product['slug']]) }}" class="shop-card group relative block aspect-[4/5] cursor-pointer overflow-hidden bg-surface-container-low" data-category="{{ $product['category'] }}" data-name="{{ $product['name'] }}" data-order="{{ $index }}" data-price="{{ (int) preg_replace('/[^0-9]/', '', $product['price']) }}" data-product-url="{{ route('products.show', ['slug' => $product['slug']]) }}" aria-label="View {{ $product['name'] }} details">
                        <img alt="{{ $product['name'] }}" class="h-full w-full object-cover transition-all duration-700 group-hover:scale-105" src="{{ $product['image'] }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=900&q=85';">
                        <span class="pointer-events-none absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center gap-2 bg-primary-container px-5 py-3 text-xs font-black uppercase tracking-widest text-black opacity-0 shadow-[0_0_24px_rgba(159,79,61,.35)] transition-all duration-300 group-hover:opacity-100" aria-hidden="true">
                            View Product <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </span>
                        @if (($product['stock'] ?? 0) < 1)
                            <span class="absolute left-4 top-4 bg-error px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white">Out of stock</span>
                        @else
                            <span class="shop-stock-badge absolute left-4 top-4 px-3 py-1 text-[10px] font-bold uppercase tracking-widest">{{ $product['stock'] }} in stock</span>
                        @endif
                        <div class="shop-card-overlay pointer-events-none absolute inset-x-0 bottom-0 h-40"></div>
                        <div class="shop-card-copy absolute bottom-4 left-4 right-4 z-10">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-secondary">{{ $product['series'] }}</span>
                                <h3 class="font-headline text-lg font-black italic uppercase tracking-tighter text-white">{{ $product['name'] }}</h3>
                                <div class="font-headline text-sm font-black text-[#d5fb00]">{!! $product['price'] !!}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="mt-12 border-y border-outline-variant/10 bg-surface-container-low py-12">
            <div class="mx-auto grid max-w-[1200px] grid-cols-1 gap-8 px-6 md:grid-cols-3 md:px-0">
                @foreach ([
                    ['icon' => 'local_shipping', 'title' => 'Free Shipping', 'text' => 'Nationwide Delivery'],
                    ['icon' => 'verified', 'title' => 'Premium Quality', 'text' => 'Ethically Sourced Fabric'],
                    ['icon' => 'undo', 'title' => 'Easy Returns', 'text' => '30-Day Policy'],
                ] as $feature)
                    <div class="group flex items-center gap-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-surface-container-high text-primary-container transition-colors group-hover:bg-primary-container group-hover:text-black">
                            <span class="material-symbols-outlined">{{ $feature['icon'] }}</span>
                        </div>
                        <div>
                            <h4 class="font-headline text-sm font-bold uppercase tracking-widest">{{ $feature['title'] }}</h4>
                            <p class="mt-1 text-[10px] uppercase tracking-widest text-on-surface-variant">{{ $feature['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mx-auto max-w-4xl px-6 py-24 text-center">
            <h2 class="font-headline mb-6 text-5xl font-black italic uppercase tracking-tighter text-white md:text-7xl">READY TO JOIN<br>THE <span class="text-primary-container">CIRCUIT?</span></h2>
            <p class="mb-12 font-body text-lg text-on-surface-variant">Get exclusive drops and offers straight to your inbox.</p>
            <form class="mx-auto flex max-w-xl flex-col gap-4 md:flex-row">
                <input class="flex-grow rounded-lg border-none bg-surface-container-highest px-6 py-4 text-xs font-bold uppercase tracking-widest text-white focus:ring-2 focus:ring-secondary" placeholder="ENTER YOUR EMAIL" type="email">
                <button class="flex items-center justify-center gap-2 bg-primary-container px-12 py-4 font-headline text-sm font-black uppercase tracking-widest text-black transition-transform hover:scale-105 active:scale-95" type="submit">Subscribe</button>
            </form>
        </section>
    </main>

    <script>
        (() => {
            const grid = document.getElementById('shop-grid');
            const sort = document.getElementById('shop-sort');
            const filters = document.querySelectorAll('.shop-filter');
            let activeFilter = 'all';

            const updateShop = () => {
                const cards = [...grid.querySelectorAll('.shop-card')];
                cards.sort((a, b) => {
                    if (sort.value === 'price-low') return Number(a.dataset.price) - Number(b.dataset.price);
                    if (sort.value === 'price-high') return Number(b.dataset.price) - Number(a.dataset.price);
                    if (sort.value === 'name') return a.dataset.name.localeCompare(b.dataset.name);
                    return Number(a.dataset.order) - Number(b.dataset.order);
                });
                cards.forEach(card => {
                    const shouldHide = activeFilter !== 'all' && card.dataset.category !== activeFilter;
                    card.hidden = shouldHide;
                    card.classList.toggle('hidden', shouldHide);
                    grid.appendChild(card);
                });
            };

            filters.forEach(filter => filter.addEventListener('click', () => {
                activeFilter = filter.dataset.filter;
                filters.forEach(item => item.classList.toggle('is-active', item === filter));
                updateShop();
            }));
            sort.addEventListener('change', updateShop);
            grid.addEventListener('click', event => {
                const card = event.target.closest('.shop-card');
                if (card && event.button === 0 && !event.defaultPrevented) {
                    event.preventDefault();
                    window.location.assign(card.dataset.productUrl);
                }
            });
            updateShop();
        })();
    </script>

    <footer class="w-full border-t border-[#484847]/20 bg-[#0e0e0e] px-10 py-12">
        <div class="mx-auto flex max-w-[1200px] flex-col items-center justify-between gap-8 md:flex-row">
            <div class="flex flex-col items-center gap-4 md:items-start">
                <span class="font-headline text-lg font-bold text-white">Soléa Fashion Co.</span>
                <p class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/40">&copy;2024 SOLÉA FASHION CO. SOLÉA FASHION CO.. ALL RIGHTS RESERVED.</p>
            </div>
            <div class="flex gap-8">
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/40 transition-all duration-200 hover:text-[#7799ff]" href="#">PRIVACY</a>
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/40 transition-all duration-200 hover:text-[#7799ff]" href="#">TERMS</a>
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/40 transition-all duration-200 hover:text-[#7799ff]" href="#">SHIPPING</a>
                <a class="font-['Inter'] text-[10px] uppercase tracking-widest text-white/40 transition-all duration-200 hover:text-[#7799ff]" href="{{ route('home') }}#contact">CONTACT</a>
            </div>
        </div>
    </footer>
</body>
</html>
