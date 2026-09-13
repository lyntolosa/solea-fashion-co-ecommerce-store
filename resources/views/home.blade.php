<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | SOLÉA FASHION CO.</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary-container": "#d5fb00",
                        "on-primary-container": "#4e5d00",
                        "surface-container-low": "#131313",
                        "surface-container-highest": "#262626",
                        "surface-container-lowest": "#000000",
                        "on-surface-variant": "#adaaaa",
                        "background": "#0e0e0e",
                        "surface": "#0e0e0e",
                        "secondary": "#7799ff",
                        "outline-variant": "#484847"
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
        .home-nav-link { transition: color 180ms ease; }
        .home-nav-link:hover,
        .home-nav-link:focus-visible { color: #A6533E !important; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            background-color: #0e0e0e;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
        }

        .kinetic-gradient {
            background: linear-gradient(to right, #f5ffc4, #d5fb00);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background text-white selection:bg-primary-container selection:text-on-primary-container">`r
@include('partials.demo-notice')
    <nav class="fixed top-0 z-50 w-full border-b border-[#484847]/20 bg-[#0e0e0e]/80 px-8 py-6 backdrop-blur-xl">
        <div class="mx-auto flex w-full max-w-[1200px] items-center justify-between">
            <a href="{{ route('home') }}" class="font-headline text-2xl font-black italic tracking-tighter text-[#d5fb00]">Soléa Fashion Co.</a>

            <div class="hidden items-center gap-8 md:flex">
                <a class="home-nav-link font-headline text-sm font-black uppercase tracking-tighter text-[#A6533E]" href="{{ route('home') }}">HOME</a>
                <a class="home-nav-link font-headline text-sm font-black uppercase tracking-tighter text-white" href="{{ route('shop') }}">SHOP</a>
                <a class="home-nav-link font-headline text-sm font-black uppercase tracking-tighter text-white" href="{{ route('shop') }}">COLLECTIONS</a>
                <a class="home-nav-link font-headline text-sm font-black uppercase tracking-tighter text-white" href="{{ route('contact') }}">CONTACT</a>
            </div>

            <div class="flex items-center gap-6">
                <button id="home-search-toggle" class="scale-95 text-white transition-transform hover:text-[#A6533E] active:scale-90" type="button" aria-label="Search">
                    <span class="material-symbols-outlined">search</span>
                </button>
                <a href="{{ route('cart') }}" class="scale-95 text-white transition-transform hover:text-[#d5fb00] active:scale-90" aria-label="Shopping bag">
                    <span class="material-symbols-outlined">shopping_bag</span>
                </a>
                @include('partials.account-menu')
            </div>
        </div>
        <form id="home-search-form" action="{{ route('shop') }}" method="GET" class="absolute right-24 top-full hidden w-72 border border-[#A6533E]/30 bg-white p-3 shadow-lg">
            <label class="sr-only" for="home-search-input">Search products</label>
            <div class="flex items-center gap-2">
                <input id="home-search-input" name="search" type="search" placeholder="Search products..." class="w-full border border-[#CBBBAE] bg-[#F4EEE7] px-3 py-2 text-sm text-[#201A17] focus:border-[#A6533E] focus:ring-1 focus:ring-[#A6533E]" autocomplete="off">
                <button type="submit" class="bg-[#A6533E] px-3 py-2 text-xs font-bold uppercase text-white hover:bg-[#8E4433]">Go</button>
            </div>
        </form>
    </nav>

    <main class="pt-20">
        <section class="relative flex min-h-[calc(100svh-80px)] items-center overflow-hidden px-8 py-12 md:min-h-[calc(100vh-88px)]">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: linear-gradient(90deg, rgba(36, 28, 24, 0.82) 0%, rgba(36, 28, 24, 0.42) 44%, rgba(36, 28, 24, 0.08) 100%), url('{{ asset('images/solea-hero-studio.png') }}'); background-size: cover; background-position: center center;"></div>
            </div>

            <div class="relative z-10 mx-auto w-full max-w-[1200px]">
                <div class="mb-6 inline-block bg-primary-container px-3 py-1 font-headline text-xs font-black tracking-widest text-on-primary-container">
                    SOLÉA SERIES 01
                </div>
                <h1 class="home-hero-title font-headline mb-8 text-6xl font-black italic uppercase leading-[0.85] tracking-tighter text-white md:text-8xl">
                    Minimal.<br>Bold.<br><span class="text-primary-container">Timeless.</span>
                </h1>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('shop') }}" class="kinetic-gradient flex scale-95 items-center gap-2 px-10 py-5 font-headline text-sm font-black uppercase tracking-widest text-on-primary-container transition-transform active:scale-90">
                        Shop Collection
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                    <a href="{{ route('shop') }}" class="scale-95 bg-surface-container-highest px-10 py-5 font-headline text-sm font-black uppercase tracking-widest text-secondary transition-transform active:scale-90">
                        Buy Now
                    </a>
                </div>
            </div>
        </section>

        <section id="shop" class="bg-surface px-8 py-20 md:py-24">
            <div class="mx-auto w-full max-w-[1200px]">
                <div class="mb-16 flex flex-col items-start justify-between gap-6 md:flex-row md:items-end">
                    <h2 class="font-headline text-5xl font-black italic uppercase leading-none tracking-tighter md:text-7xl">SHOP<br>COLLECTION.</h2>
                    <p class="max-w-sm font-body text-on-surface-variant md:text-right">Selected high-performance prototypes and new laboratory editions. Engineered for the high-velocity urban mobility environment.</p>
                </div>

                <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                @php
                    $products = [
                        ['slug' => 'essential-black-tee', 'series' => 'CORE SERIES', 'name' => 'ESSENTIAL BLACK TEE', 'price' => 'P799.00', 'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'studio-white-tee', 'series' => 'MINIMALIST', 'name' => 'STUDIO WHITE TEE', 'price' => 'P799.00', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'graphic-logo-tee', 'series' => 'SOLÉA FASHION CO.', 'name' => 'GRAPHIC LOGO TEE', 'price' => 'P950.00', 'image' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'oversized-charcoal-tee', 'series' => 'RELAXED FIT', 'name' => 'OVERSIZED CHARCOAL TEE', 'price' => 'P1,100.00', 'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'tech-wear-tactical-tee', 'series' => 'PROTOTYPE', 'name' => 'TECH-WEAR TACTICAL TEE', 'price' => 'P1,200.00', 'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85'],
                        ['slug' => 'vintage-wash-renegade-tee', 'series' => 'ARCHIVE', 'name' => 'VINTAGE WASH RENEGADE TEE', 'price' => 'P1,050.00', 'image' => 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85'],
                    ];
                @endphp
                @php $products = $liveProducts; @endphp

                @foreach ($products as $product)
                    <a href="{{ route('products.show', ['slug' => $product['slug']]) }}" class="product-card group relative block aspect-[4/5] cursor-pointer overflow-hidden bg-surface-container-low" data-product-url="{{ route('products.show', ['slug' => $product['slug']]) }}" aria-label="View {{ $product['name'] }} details">
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
            </div>
        </section>

        <section id="collections" class="relative overflow-hidden bg-surface-container-low py-24 md:py-28">
            <div class="absolute -left-20 -top-20 select-none font-headline text-[20rem] font-black italic leading-none tracking-tighter text-white/[0.02]">SOLÉA FASHION CO.</div>
            <div class="relative z-10 mx-auto max-w-6xl px-8">
                <div class="grid grid-cols-1 items-center gap-20 md:grid-cols-2">
                    <div>
                        <h2 class="font-headline mb-10 text-6xl font-black italic uppercase leading-[0.9] tracking-tighter md:text-8xl">
                            The New<br><span class="text-primary-container">Uniform.</span>
                        </h2>
                        <div class="flex flex-col gap-4">
                            <p class="font-body text-xl leading-relaxed text-white/80">
                                Soléa Fashion Co. exists at the intersection of technical performance and high-fashion editorial. We don't follow seasons; we follow the pulse.
                            </p>
                            <p class="font-body text-on-surface-variant">
                                Every piece is validated through our global registry for authenticity and material excellence. Designed in the laboratory, proven on the street.
                            </p>
                            <a href="{{ route('shop') }}" class="mt-8 w-fit bg-[#d5fb00] px-10 py-5 font-headline text-sm font-black uppercase tracking-widest text-black transition-transform hover:scale-105 active:scale-95">
                                View Lookbook
                            </a>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="aspect-[4/5] overflow-hidden bg-surface-container-highest">
                            <img alt="High-end fashion photography" class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=85';">
                        </div>
                        <div class="absolute -bottom-10 -right-10 flex h-48 w-48 rotate-12 items-center justify-center bg-primary-container p-8 text-center font-headline text-xl font-black italic uppercase leading-tight text-on-primary-container">
                            Validated Archive 2024
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden bg-surface py-20 md:py-24">
            <div class="mx-auto max-w-6xl px-8">
                <h2 class="font-headline mb-16 text-4xl font-black italic uppercase tracking-tighter text-[#d5fb00] md:text-6xl">COMMUNITY<br>VOICE.</h2>
                <div class="flex snap-x snap-mandatory gap-8 overflow-x-auto pb-8 hide-scrollbar">
                    @foreach ([
                        ['quote' => "This is the most comfortable shirt I've ever owned.", 'name' => 'Verified Lab Participant'],
                        ['quote' => 'Simple but premium - exactly what I was looking for.', 'name' => 'Archival Member'],
                        ['quote' => 'The quality of the technical fabric is unmatched. Truly the new standard.', 'name' => 'Tech Enthusiast'],
                        ['quote' => 'Vibrant energy met with sophisticated tailoring. Soléa Fashion Co. is in a league of its own.', 'name' => 'Editorial Director'],
                    ] as $testimonial)
                        <article class="group relative w-full flex-shrink-0 snap-center rounded-xl border border-white/10 bg-white/5 p-10 backdrop-blur-md transition-all duration-500 hover:border-[#d5fb00]/30 md:w-[calc(50%-1rem)]">
                            <div class="absolute right-0 top-0 p-4 text-[#d5fb00]/20 transition-colors group-hover:text-[#d5fb00]/40">
                                <span class="material-symbols-outlined text-4xl">format_quote</span>
                            </div>
                            <p class="mb-8 font-body text-xl italic leading-relaxed text-white md:text-2xl">"{{ $testimonial['quote'] }}"</p>
                            <div class="flex items-center gap-4">
                                <div class="h-0.5 w-12 bg-[#d5fb00]"></div>
                                <span class="font-headline text-xs font-black uppercase tracking-widest text-on-surface-variant">{{ $testimonial['name'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="contact" class="relative flex flex-col items-center justify-center overflow-hidden border-t border-[#484847]/20 bg-surface-container-highest px-8 py-20 text-center md:py-24">
            <h2 class="font-headline mb-6 text-5xl font-black italic uppercase tracking-tighter text-white md:text-7xl">
                READY TO JOIN<br>THE <span class="text-[#d5fb00]">CIRCUIT?</span>
            </h2>
            <p class="mb-12 max-w-2xl font-body text-xl text-on-surface-variant">
                Experience the fusion of high-fashion and technical performance.
            </p>
            <a href="{{ route('shop') }}" class="kinetic-gradient flex items-center gap-2 px-12 py-6 font-headline text-base font-black uppercase tracking-widest text-black transition-transform hover:scale-105 active:scale-95">
                Shop Now
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </section>
    </main>

    <footer class="w-full border-t border-[#484847]/20 bg-[#0e0e0e] px-8 py-20">
        <div class="flex flex-col items-start justify-between gap-12 md:flex-row md:items-end">
            <div class="flex w-full flex-col gap-8 md:w-auto">
                <div class="font-headline text-3xl font-black italic tracking-tighter text-white">Soléa Fashion Co.</div>
                <div class="flex flex-wrap gap-8">
                    <a class="font-['Inter'] text-[10px] font-medium uppercase tracking-widest text-[#484847] opacity-80 transition-colors hover:text-white hover:opacity-100" href="#">PRIVACY</a>
                    <a class="font-['Inter'] text-[10px] font-medium uppercase tracking-widest text-[#484847] opacity-80 transition-colors hover:text-white hover:opacity-100" href="#">TERMS</a>
                    <a class="font-['Inter'] text-[10px] font-medium uppercase tracking-widest text-[#484847] opacity-80 transition-colors hover:text-white hover:opacity-100" href="#">SHIPPING</a>
                    <a class="font-['Inter'] text-[10px] font-medium uppercase tracking-widest text-[#484847] opacity-80 transition-colors hover:text-white hover:opacity-100" href="#contact">CONTACT</a>
                </div>
            </div>
            <div class="flex flex-col items-start gap-4 md:items-end">
                <div class="flex gap-4">
                    <span class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-outline-variant transition-colors hover:bg-primary-container hover:text-black">
                        <span class="material-symbols-outlined text-sm">language</span>
                    </span>
                    <span class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-outline-variant transition-colors hover:bg-primary-container hover:text-black">
                        <span class="material-symbols-outlined text-sm">share</span>
                    </span>
                </div>
                <p class="font-['Inter'] text-[10px] font-medium uppercase tracking-widest text-[#484847]">&copy;2024 SOLÉA FASHION CO. GLOBAL REGISTRY. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
<script>
    const searchToggle = document.getElementById('home-search-toggle');
    const searchForm = document.getElementById('home-search-form');
    const searchInput = document.getElementById('home-search-input');
    searchToggle?.addEventListener('click', () => {
        searchForm?.classList.toggle('hidden');
        if (!searchForm?.classList.contains('hidden')) searchInput?.focus();
    });
    document.addEventListener('click', (event) => {
        if (searchForm && searchToggle && !searchForm.contains(event.target) && !searchToggle.contains(event.target)) {
            searchForm.classList.add('hidden');
        }
    });
</script>
</body>
</html>
