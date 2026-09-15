<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | YOUR CART</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#D9FF00",
                        "on-primary": "#000000",
                        background: "#000000",
                        surface: "#111111",
                        "surface-container": "#1A1A1A",
                        "surface-variant": "#222222",
                        "on-surface": "#FFFFFF",
                        "on-surface-variant": "#A1A1A1"
                    },
                    borderRadius: {
                        DEFAULT: "0rem",
                        lg: "0rem",
                        xl: "0rem",
                        full: "9999px"
                    },
                    fontFamily: {
                        headline: ["Plus Jakarta Sans", "sans-serif"],
                        body: ["Plus Jakarta Sans", "sans-serif"],
                        label: ["Plus Jakarta Sans", "sans-serif"]
                    }
                }
            }
        };
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        body {
            background-color: #000000;
            color: #FFFFFF;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
        }

        .italic-headline {
            font-style: italic;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            display: inline-block;
            animation: marquee 20s linear infinite;
        }
    </style>
</head>
<body class="bg-background text-on-surface antialiased">`r
@include('partials.demo-notice')
    <nav class="fixed top-0 z-50 w-full border-b border-[#484847]/20 bg-[#0e0e0e]/80 px-8 py-6 backdrop-blur-xl">
        <div class="mx-auto flex w-full max-w-[1200px] items-center justify-between">
            <a href="{{ route('home') }}" class="font-headline text-2xl font-black italic tracking-tighter text-[#d5fb00]">Soléa Fashion Co.</a>

            <div class="hidden items-center gap-8 md:flex">
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-white transition-colors duration-300 hover:text-[#d5fb00]" href="{{ route('home') }}">HOME</a>
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-white transition-colors duration-300 hover:text-[#d5fb00]" href="{{ route('shop') }}">SHOP</a>
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-white transition-colors duration-300 hover:text-[#d5fb00]" href="{{ route('shop') }}">COLLECTIONS</a>
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-white transition-colors duration-300 hover:text-[#d5fb00]" href="{{ route('contact') }}">CONTACT</a>
            </div>

            <div class="flex items-center gap-6">
                <button class="scale-95 text-white transition-transform hover:text-[#d5fb00] active:scale-90" type="button" aria-label="Search">
                    <span class="material-symbols-outlined">search</span>
                </button>
                <a href="{{ route('cart') }}" class="scale-95 text-white transition-transform hover:text-[#d5fb00] active:scale-90" aria-label="Shopping bag">
                    <span class="material-symbols-outlined">shopping_bag</span>
                </a>
                @include('partials.account-menu')
            </div>
        </div>
    </nav>

    <main class="mx-auto min-h-screen max-w-[1200px] px-8 pb-24 pt-32">
        <header class="mb-20">
            <h1 class="italic-headline mb-4 text-7xl font-extrabold uppercase leading-none tracking-tighter md:text-9xl">Your Cart</h1>
            <p class="font-body text-xs font-bold uppercase tracking-[0.2em] text-primary">Atelier Selection / Vol. 24</p>
        </header>

        <div class="flex flex-col gap-16 lg:flex-row">
            <div id="cart-items" class="flex-grow space-y-16"></div>

            <aside class="lg:w-96">
                <div class="sticky top-32 border border-white/10 bg-surface p-10">
                    <h2 class="italic-headline mb-10 text-3xl font-extrabold uppercase tracking-tighter">Summary</h2>
                    <div class="space-y-6 font-body text-sm uppercase tracking-[0.15em]">
                        <div class="flex justify-between">
                            <span class="font-bold text-zinc-500">Subtotal</span>
                            <span id="cart-subtotal" class="font-extrabold text-white">&#8369;0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold text-zinc-500">Shipping</span>
                            <span id="cart-shipping" class="font-extrabold text-white">&#8369;0</span>
                        </div>
                        <div class="flex justify-between border-t border-white/10 pt-6">
                            <span class="text-lg font-extrabold">Total</span>
                            <span id="cart-total" class="italic-headline text-3xl font-extrabold tracking-tighter text-primary">&#8369;0</span>
                        </div>
                    </div>
                    <div class="mt-12 space-y-4">
                        <a href="{{ route('checkout') }}" class="block w-full bg-primary py-6 text-center text-xs font-extrabold uppercase tracking-[0.2em] text-black transition-transform duration-300 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-[#A6533E] focus:ring-offset-2 focus:ring-offset-[#F4EEE7] active:scale-95">
                            Proceed to Checkout
                        </a>
                        <p class="mt-6 text-center text-[9px] font-bold uppercase tracking-[0.2em] text-zinc-500">
                            Tax included. Shipping calculated at checkout.
                        </p>
                    </div>
                    <div class="mt-10 flex items-start gap-4 border-t border-white/10 pt-10">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">verified</span>
                        <p class="text-[10px] font-bold uppercase leading-relaxed tracking-widest text-zinc-400">
                            Exclusive Atelier Guarantee. Limited production runs ensuring rarity.
                        </p>
                    </div>
                </div>
            </aside>
        </div>

        <div class="-mx-8 mt-40 -rotate-1 overflow-hidden whitespace-nowrap bg-primary py-6">
            <div class="animate-marquee inline-block">
                <span class="mx-8 text-5xl font-extrabold italic uppercase tracking-tighter text-black">New Archive Drop 2024 - Worldwide Shipping - Soléa Fashion Co. Atelier - Hand-Finished Details - SOLÉA FASHION CO. -</span>
                <span class="mx-8 text-5xl font-extrabold italic uppercase tracking-tighter text-black">New Archive Drop 2024 - Worldwide Shipping - Soléa Fashion Co. Atelier - Hand-Finished Details - SOLÉA FASHION CO. -</span>
            </div>
        </div>
    </main>

    <footer class="w-full border-t border-white/10 bg-black px-8 py-24">
        <div class="mx-auto flex max-w-[1200px] flex-col items-center justify-between gap-12 md:flex-row">
            <div class="flex flex-col items-center md:items-start">
                <span class="italic-headline mb-4 text-xl font-extrabold tracking-tighter text-white">Soléa Fashion Co.</span>
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-zinc-600">&copy; 2024 SOLÉA FASHION CO. ATELIER. PRODUCED IN LIMITED QUANTITIES.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-x-12 gap-y-6">
                <a class="text-[10px] font-bold uppercase tracking-[0.3em] text-zinc-500 transition-all duration-300 hover:text-primary" href="#">PRIVACY</a>
                <a class="text-[10px] font-bold uppercase tracking-[0.3em] text-zinc-500 transition-all duration-300 hover:text-primary" href="#">TERMS</a>
                <a class="text-[10px] font-bold uppercase tracking-[0.3em] text-zinc-500 transition-all duration-300 hover:text-primary" href="#">SHIPPING</a>
                <a class="text-[10px] font-bold uppercase tracking-[0.3em] text-zinc-500 transition-all duration-300 hover:text-primary" href="#">RETURNS</a>
                <a class="text-[10px] font-bold uppercase tracking-[0.3em] text-zinc-500 transition-all duration-300 hover:text-primary" href="{{ route('home') }}#contact">CONTACT</a>
            </div>
        </div>
    </footer>
    <script>
        (() => {
            const itemsContainer = document.getElementById('cart-items');
            const subtotal = document.getElementById('cart-subtotal');
            const shippingTotal = document.getElementById('cart-shipping');
            const total = document.getElementById('cart-total');
            const checkoutLink = document.querySelector('a[href="{{ route('checkout') }}"]');
            const shipping = 100;
            const inventory = @json($cartInventory);

            if (!itemsContainer || !subtotal || !total) return;

            const readCart = () => JSON.parse(localStorage.getItem('threadlab_cart') || '[]');
            const saveCart = (items) => localStorage.setItem('threadlab_cart', JSON.stringify(items));
            const availableStock = item => Number(inventory[item.slug]?.stock_by_size?.[item.size] ?? inventory[item.slug]?.stock ?? 0);
            const money = amount => `₱${Number(amount).toLocaleString('en-US')}`;

            const renderItems = () => {
                let items = readCart();
                const validItems = items.filter(item => inventory[item.slug] && inventory[item.slug].status === 'Active');
                items = validItems.map(item => ({ ...item, quantity: Math.min(Number(item.quantity || 1), availableStock(item)) })).filter(item => availableStock(item) > 0 && item.quantity > 0);
                if (JSON.stringify(items) !== JSON.stringify(readCart())) saveCart(items);
                itemsContainer.innerHTML = items.length ? items.map((item, index) => `
                    <article class="cart-item flex flex-col items-start gap-8 border-b border-white/10 pb-16 md:flex-row" data-index="${index}">
                        <div class="aspect-[3/4] w-full overflow-hidden bg-surface-container md:w-64"><img alt="${item.name}" class="h-full w-full object-cover" src="${inventory[item.slug]?.thumbnail || item.image}"></div>
                        <div class="flex h-64 flex-grow flex-col justify-between py-2"><div class="flex items-start justify-between gap-6"><div><h3 class="italic-headline text-3xl font-extrabold uppercase tracking-tighter">${item.name}</h3><p class="mt-2 text-sm font-bold uppercase tracking-widest text-zinc-500">Size: ${item.size}</p><p class="mt-2 text-xs font-bold uppercase tracking-widest text-primary">${availableStock(item)} in stock for size ${item.size} · max available</p></div><span class="italic-headline text-2xl font-extrabold tracking-tighter text-primary">${money(item.price)}</span></div><div class="flex w-full items-end justify-between"><div class="flex items-center border border-white/20 p-1"><button class="decrease-quantity flex h-10 w-10 items-center justify-center transition-colors hover:bg-white/10" type="button" aria-label="Decrease quantity"><span class="material-symbols-outlined text-sm">remove</span></button><span class="quantity-value px-6 text-lg font-bold">${item.quantity}</span><button class="increase-quantity flex h-10 w-10 items-center justify-center transition-colors hover:bg-white/10 ${item.quantity >= availableStock(item) ? 'pointer-events-none opacity-40' : ''}" type="button" aria-label="Increase quantity"><span class="material-symbols-outlined text-sm">add</span></button></div><button class="remove-item text-xs font-bold uppercase tracking-widest text-zinc-500 underline decoration-secondary underline-offset-8 transition-colors hover:text-secondary" type="button">Remove Item</button></div></div>
                    </article>`).join('') : '<div class="empty-cart-state border border-white/10 bg-surface p-10 text-center"><span class="material-symbols-outlined mb-4 text-4xl text-primary">shopping_bag</span><p class="font-headline text-2xl font-extrabold uppercase">Your cart is empty</p><a href="/shop" class="mt-6 inline-block bg-primary px-6 py-3 text-xs font-extrabold uppercase tracking-widest text-black">Continue Shopping</a></div>';
                updateSummary(items);
            };

            const updateSummary = (items = readCart()) => {
                const amount = items.reduce((sum, item) => sum + (Number(item.price || 0) * Number(item.quantity || 1)), 0);
                subtotal.textContent = money(amount);
                shippingTotal.textContent = money(amount ? shipping : 0);
                total.textContent = money(amount ? amount + shipping : 0);
                if (checkoutLink) checkoutLink.classList.toggle('pointer-events-none', !items.length);
            };

            itemsContainer.addEventListener('click', event => {
                const itemElement = event.target.closest('.cart-item');
                if (!itemElement) return;
                const index = Number(itemElement.dataset.index);
                const items = readCart();
                if (event.target.closest('.remove-item')) {
                    items.splice(index, 1);
                    saveCart(items);
                    renderItems();
                    return;
                }
                const quantityButton = event.target.closest('.increase-quantity, .decrease-quantity');
                if (quantityButton) {
                    const maxStock = availableStock(items[index]);
                    const nextQuantity = Number(items[index].quantity || 1) + (quantityButton.classList.contains('increase-quantity') ? 1 : -1);
                    items[index].quantity = Math.min(maxStock, Math.max(1, nextQuantity));
                    saveCart(items);
                    renderItems();
                }
            });
            renderItems();
        })();
    </script>
</body>
</html>
