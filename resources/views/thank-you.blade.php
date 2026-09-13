<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | ORDER SUCCESS</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                        "error-container": "#b92902",
                        "surface-container-lowest": "#000000",
                        "secondary-dim": "#316bf3",
                        "on-secondary": "#001b55",
                        "surface-container-low": "#131313",
                        "outline-variant": "#484847",
                        "surface-variant": "#262626",
                        "on-surface": "#ffffff",
                        "primary-container": "#d5fb00",
                        primary: "#f5ffc4",
                        "tertiary-container": "#fedb42",
                        "surface-container-high": "#201f1f",
                        outline: "#777575",
                        "tertiary-dim": "#efcd34",
                        background: "#0e0e0e",
                        "surface-container": "#1a1919",
                        surface: "#0e0e0e",
                        secondary: "#7799ff",
                        "surface-container-highest": "#262626",
                        "on-primary-container": "#4e5d00",
                        "on-surface-variant": "#adaaaa"
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

        .kinetic-grid {
            background-image: linear-gradient(to right, #484847 1px, transparent 1px), linear-gradient(to bottom, #484847 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.05;
        }

        .blur-glow {
            filter: blur(80px);
            opacity: 0.15;
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface selection:bg-primary-container selection:text-on-primary-container">`r
@include('partials.demo-notice')
    <main class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-6">
        <div class="kinetic-grid pointer-events-none absolute inset-0"></div>
        <div class="blur-glow pointer-events-none absolute -left-20 -top-20 h-96 w-96 rounded-full bg-primary-container"></div>
        <div class="blur-glow pointer-events-none absolute -bottom-20 -right-20 h-96 w-96 rounded-full bg-secondary"></div>

        <div class="relative z-10 w-full max-w-2xl">
            <div class="mb-12 flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-primary-container opacity-40 blur-2xl"></div>
                    <div class="relative flex h-24 w-24 rotate-12 items-center justify-center bg-primary-container">
                        <span class="material-symbols-outlined !text-5xl -rotate-12 text-on-primary-container" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                </div>
            </div>

            <div class="mb-16 space-y-4 text-center">
                <h1 class="font-headline text-5xl font-extrabold italic uppercase leading-none tracking-tighter md:text-7xl">
                    Your order has been placed <span class="text-primary-container">successfully</span>
                </h1>
                <p class="font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Confirmation Stage Complete</p>
            </div>

            <div class="mb-16 grid grid-cols-1 gap-1 md:grid-cols-3">
                <div class="flex flex-col justify-between border-l-4 border-primary-container bg-surface-container-low p-8">
                    <span class="font-label mb-4 text-xs uppercase tracking-widest text-on-surface-variant">Reference</span>
                    <span class="font-headline text-xl font-bold tracking-tight text-white">{{ $order['reference'] }}</span>
                </div>
                <div class="flex flex-col justify-between bg-surface-container p-8">
                    <span class="font-label mb-4 text-xs uppercase tracking-widest text-on-surface-variant">Total Amount</span>
                    <span class="font-headline text-xl font-bold tracking-tight text-primary-container">&#8369;{{ number_format((float) $order['total'], 2) }}</span>
                </div>
                <div class="flex flex-col justify-between border-r-4 border-secondary bg-surface-container-high p-8">
                    <span class="font-label mb-4 text-xs uppercase tracking-widest text-on-surface-variant">Method</span>
                    <span class="font-headline text-xl font-bold tracking-tight text-white">{{ $order['payment'] }}</span>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center gap-6 md:flex-row">
                <a href="{{ route('dashboard') }}" class="group relative w-full overflow-hidden bg-gradient-to-r from-primary to-primary-container px-12 py-5 text-center font-headline font-extrabold italic uppercase tracking-tighter text-on-primary-container transition-all hover:scale-105 active:scale-95 md:w-auto">
                    <span class="relative z-10">Dashboard</span>
                    <span class="absolute inset-0 translate-x-full bg-white/20 transition-transform duration-300 group-hover:translate-x-0"></span>
                </a>
                <a href="{{ route('cart') }}" class="w-full border-2 border-outline-variant bg-transparent px-12 py-5 text-center font-headline font-bold italic uppercase tracking-tighter text-white transition-all hover:border-secondary hover:text-secondary active:scale-95 md:w-auto">
                    Back to Cart
                </a>
            </div>

            <div class="mt-24 flex items-center justify-center gap-4 opacity-30">
                <div class="h-px w-12 bg-outline-variant"></div>
                <span class="font-label text-[10px] uppercase tracking-[0.5em] text-on-surface-variant">SOLÉA FASHION CO. SYST v2.0</span>
                <div class="h-px w-12 bg-outline-variant"></div>
            </div>
        </div>

        <div class="pointer-events-none absolute -right-20 top-1/2 hidden -translate-y-1/2 opacity-20 lg:block">
            <p class="select-none font-headline text-[20rem] font-black italic leading-none tracking-tighter text-outline-variant">THREAD</p>
        </div>
        <div class="pointer-events-none absolute -left-20 top-1/2 hidden -translate-y-1/2 opacity-20 lg:block">
            <p class="rotate-180 select-none font-headline text-[20rem] font-black italic leading-none tracking-tighter text-outline-variant">LAB</p>
        </div>
    </main>

    <footer class="mt-auto flex w-full flex-col items-center justify-between gap-8 border-t border-[#484847]/20 bg-[#131313] px-6 py-12 md:flex-row">
        <div class="text-lg font-bold text-white">Soléa Fashion Co.</div>
        <div class="flex flex-wrap justify-center gap-6">
            <a class="font-label text-xs uppercase tracking-widest text-white/40 transition-colors hover:text-secondary" href="#">TERMS</a>
            <a class="font-label text-xs uppercase tracking-widest text-white/40 transition-colors hover:text-secondary" href="#">PRIVACY</a>
            <a class="font-label text-xs uppercase tracking-widest text-white/40 transition-colors hover:text-secondary" href="#">SHIPPING</a>
            <a class="font-label text-xs uppercase tracking-widest text-white/40 transition-colors hover:text-secondary" href="#">RETURNS</a>
            <a class="font-label text-xs uppercase tracking-widest text-white/40 transition-colors hover:text-secondary" href="{{ route('home') }}#contact">CONTACT</a>
        </div>
        <div class="font-label text-[10px] uppercase tracking-wider text-white/40">
            &copy;2024 SOLÉA FASHION CO. SOLÉA FASHION CO.
        </div>
    </footer>
    <script>
        localStorage.removeItem('threadlab_cart');
        window.setTimeout(() => {
            window.location.href = @json(route('dashboard'));
        }, 3000);
    </script>
</body>
</html>
