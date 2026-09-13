<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | CHECKOUT</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&display=swap" rel="stylesheet">
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
                        primary: "#A6533E",
                        "on-primary": "#FFFFFF",
                        background: "#F4EEE7",
                        surface: "#FFFDFC",
                        "surface-variant": "#EFE5DB",
                        "on-surface": "#201A17",
                        "on-surface-variant": "#735F56",
                        outline: "#CBBBAE",
                        error: "#B42318"
                    },
                    borderRadius: {
                        DEFAULT: "0px",
                        lg: "2px",
                        xl: "4px",
                        full: "9999px"
                    },
                    fontFamily: {
                        headline: ["Plus Jakarta Sans"],
                        body: ["Plus Jakarta Sans"],
                        label: ["Plus Jakarta Sans"]
                    }
                }
            }
        };
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .neon-border { box-shadow: none; }

        .neon-glow { text-shadow: none; }

        .italic-heading {
            font-style: italic;
            font-weight: 800;
        }

        .checkout-address-select {
            color-scheme: light;
            appearance: none;
            background-color: #fffdfc;
        }

        .checkout-address-select option {
            background-color: #fffdfc;
            color: #201a17;
        }

        .checkout-address-select option:checked,
        .checkout-address-select option:hover {
            background-color: #a6533e;
            color: #ffffff;
        }

        .checkout-choice:has(input:checked) {
            border-color: #a6533e;
            background: #f1e2d8;
        }

        .checkout-choice input[type="radio"] {
            appearance: auto !important;
            accent-color: #a6533e;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface selection:bg-primary selection:text-white">`r
@include('partials.demo-notice')
    <header class="fixed top-0 z-50 w-full border-b border-[#CBBBAE]/60 bg-[#FFFDFC]/95 backdrop-blur-xl">
        <nav class="mx-auto flex w-full max-w-[1200px] items-center justify-between px-8 py-6">
            <a href="{{ route('home') }}" class="italic-heading tracking-tighter text-[#A6533E] text-2xl">
                Soléa Fashion Co.
            </a>
            <div class="hidden items-center gap-8 md:flex">
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-[#201A17] transition-colors duration-300 hover:text-[#A6533E]" href="{{ route('home') }}">HOME</a>
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-[#201A17] transition-colors duration-300 hover:text-[#A6533E]" href="{{ route('shop') }}">SHOP</a>
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-[#201A17] transition-colors duration-300 hover:text-[#A6533E]" href="{{ route('shop') }}">COLLECTIONS</a>
                <a class="font-headline text-sm font-black uppercase tracking-tighter text-[#201A17] transition-colors duration-300 hover:text-[#A6533E]" href="{{ route('contact') }}">CONTACT</a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="{{ route('cart') }}" class="scale-95 text-[#201A17] transition-colors hover:text-[#A6533E]" aria-label="Shopping bag"><span class="material-symbols-outlined">shopping_bag</span></a>
                @include('partials.account-menu')
            </div>
        </nav>
    </header>

    <main class="mx-auto min-h-screen max-w-[1200px] px-8 pb-24 pt-32">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-12 lg:gap-24">
            <form id="checkout-form" action="{{ route('checkout.complete') }}" method="POST" class="space-y-20 lg:col-span-7 lg:pl-8">
                @csrf
                <input type="hidden" name="cart_json" id="cart_json" value="[]">
                @if ($errors->any())
                    <div class="border border-error/50 bg-error/10 p-5 text-sm text-red-200" role="alert">
                        <p class="font-bold">Please complete the highlighted fields before continuing.</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="relative">
                    <h1 class="italic-heading mb-2 text-6xl uppercase tracking-tighter md:text-8xl">Checkout</h1>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary">Secure your selection from the Digital Atelier.</p>
                    <div class="neon-border absolute -left-8 top-0 h-full w-1 bg-primary"></div>
                </div>

                <section class="space-y-10">
                    <div class="flex items-baseline justify-between border-b border-outline pb-4">
                        <h2 class="italic-heading text-2xl uppercase tracking-tight">01 / Shipping Address</h2>
                    </div>
                    @php
                        $checkoutFields = [
                            ['name' => 'first_name', 'label' => 'First Name', 'placeholder' => 'Alexander', 'type' => 'text', 'autocomplete' => 'given-name'],
                            ['name' => 'last_name', 'label' => 'Last Name', 'placeholder' => 'McQueen', 'type' => 'text', 'autocomplete' => 'family-name'],
                            ['name' => 'street_address', 'label' => 'Street Address', 'placeholder' => '128 Studio Alley, Suite 4', 'type' => 'text', 'autocomplete' => 'street-address', 'wide' => true],
                            ['name' => 'city', 'label' => 'City', 'placeholder' => 'Metro Manila', 'type' => 'text', 'autocomplete' => 'address-level2'],
                            ['name' => 'zip_code', 'label' => 'Zip Code', 'placeholder' => '1200', 'type' => 'text', 'autocomplete' => 'postal-code'],
                            ['name' => 'phone', 'label' => 'Phone Number', 'placeholder' => '+63 000 000 0000', 'type' => 'tel', 'autocomplete' => 'tel'],
                            ['name' => 'email', 'label' => 'Email Address', 'placeholder' => 'you@example.com', 'type' => 'email', 'autocomplete' => 'email', 'hint' => 'For your customer account after purchase.'],
                        ];
                        if (! $isReturningCustomer) {
                            $checkoutFields[] = ['name' => 'username', 'label' => 'Username', 'placeholder' => 'user_01', 'type' => 'text', 'autocomplete' => 'username'];
                            $checkoutFields[] = ['name' => 'password', 'label' => 'Password', 'placeholder' => 'Enter a password', 'type' => 'password', 'autocomplete' => 'new-password', 'hint' => 'Use this to log in after purchase.'];
                            $checkoutFields[] = ['name' => 'password_confirmation', 'label' => 'Confirm Password', 'placeholder' => 'Re-enter your password', 'type' => 'password', 'autocomplete' => 'new-password'];
                        }
                    @endphp
                    @if ($savedAddresses)
                        <div class="mb-8 space-y-3">
                            <label for="saved-address" class="text-[10px] font-bold uppercase tracking-[0.3em] text-on-surface-variant">Shipping Address</label>
                            <div class="relative">
                            <select id="saved-address" class="checkout-address-select w-full border-0 border-b-2 border-outline px-0 py-4 pr-10 text-lg font-bold text-on-surface focus:border-primary focus:ring-0">
                                @foreach ($savedAddresses as $address)
                                    <option value="{{ $address['id'] }}" data-address="{{ json_encode($address) }}">{{ $address['label'] }} - {{ $address['street_address'] }}, {{ $address['city'] }}</option>
                                @endforeach
                                <option value="new">Use a different address</option>
                            </select>
                            <span class="material-symbols-outlined pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 text-primary">expand_more</span>
                            </div>
                            <p class="text-[10px] text-on-surface-variant">Choose a saved address or select “Use a different address” to enter another one.</p>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 gap-x-12 gap-y-8 md:grid-cols-2">
                        @foreach ($checkoutFields as $field)
                            <div class="{{ ! empty($field['wide']) ? 'md:col-span-2 ' : '' }}space-y-3">
                                <label for="{{ $field['name'] }}" class="text-[10px] font-bold uppercase tracking-[0.3em] text-on-surface-variant">{{ $field['label'] }}</label>
                                 <input id="{{ $field['name'] }}" name="{{ $field['name'] }}" value="{{ old($field['name'], $customer[$field['name']] ?? '') }}" class="w-full border-0 border-b-2 border-outline bg-transparent px-0 py-4 text-lg font-bold normal-case placeholder:text-on-surface-variant/60 transition-all focus:border-primary focus:ring-0" placeholder="{{ $field['placeholder'] }}" type="{{ $field['type'] }}" autocomplete="{{ $field['autocomplete'] }}" required>
                                @if ($field['name'] === 'password_confirmation')
                                    <p id="password-match-error" class="hidden text-[10px] font-bold text-error" role="alert">Passwords do not match.</p>
                                @endif
                                @if (! empty($field['hint']))
                                    <p class="text-[10px] leading-relaxed text-on-surface-variant">{{ $field['hint'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="space-y-10">
                    <h2 class="italic-heading border-b border-outline pb-4 text-2xl uppercase tracking-tight">02 / Shipping Method</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @foreach ([
                            ['name' => 'Standard', 'days' => '3-5 BUSINESS DAYS', 'price' => '&#8369;100', 'checked' => true],
                            ['name' => 'Express', 'days' => 'OVERNIGHT DELIVERY', 'price' => '&#8369;300', 'checked' => false],
                        ] as $method)
                            <label class="checkout-choice group relative flex cursor-pointer items-center justify-between border border-outline bg-surface p-8 transition-all duration-300 hover:border-primary">
                                <input class="peer hidden" name="shipping" type="radio" value="{{ strtolower($method['name']) }}" @checked($method['checked'])>
                                <div class="space-y-1">
                                    <span class="block text-lg font-bold italic uppercase">{{ $method['name'] }}</span>
                                    <span class="text-[10px] uppercase tracking-widest text-on-surface-variant">{{ $method['days'] }}</span>
                                </div>
                                <span class="text-xl font-bold italic tracking-tighter">{!! $method['price'] !!}</span>
                                <div class="neon-border pointer-events-none absolute inset-0 border-2 border-primary opacity-0 transition-opacity peer-checked:opacity-100"></div>
                            </label>
                        @endforeach
                    </div>
                </section>

                <section class="space-y-10">
                    <h2 class="italic-heading border-b border-outline pb-4 text-2xl uppercase tracking-tight">03 / Payment Method</h2>
                    <div class="space-y-8">
                        <div class="checkout-choice border border-outline bg-surface">
                            <label class="group flex cursor-pointer items-center gap-6 p-8">
                                <input checked class="h-6 w-6 border-2 border-primary bg-transparent text-primary focus:ring-0 focus:ring-offset-0" name="payment" type="radio" value="card">
                                <span class="text-xl font-bold italic uppercase">Credit / Debit Card</span>
                            </label>
                            <div class="space-y-8 px-8 pb-10">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-bold uppercase tracking-[0.3em] text-on-surface-variant">Card Number</label>
                                    <input id="card_number" name="card_number" class="w-full border-0 border-b-2 border-outline bg-transparent px-0 py-4 text-lg font-bold normal-case placeholder:text-on-surface-variant/60 transition-all focus:border-primary focus:ring-0" placeholder="0000 0000 0000 0000" type="text" inputmode="numeric" autocomplete="cc-number">
                                </div>
                                <div class="grid grid-cols-2 gap-12">
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-bold uppercase tracking-[0.3em] text-on-surface-variant">Expiry Date</label>
                                        <input id="expiry_date" name="expiry_date" class="w-full border-0 border-b-2 border-outline bg-transparent px-0 py-4 text-lg font-bold normal-case placeholder:text-on-surface-variant/60 transition-all focus:border-primary focus:ring-0" placeholder="MM/YY" type="text" inputmode="numeric" autocomplete="cc-exp">
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-bold uppercase tracking-[0.3em] text-on-surface-variant">CVV</label>
                                        <input id="cvv" name="cvv" class="w-full border-0 border-b-2 border-outline bg-transparent px-0 py-4 text-lg font-bold normal-case placeholder:text-on-surface-variant/60 transition-all focus:border-primary focus:ring-0" placeholder="123" type="text" inputmode="numeric" autocomplete="cc-csc">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-choice group relative border border-outline bg-surface transition-all duration-300 hover:border-primary">
                            <div class="relative">
                                <label class="flex cursor-pointer items-center gap-6 p-8">
                                    <input class="h-6 w-6 border-2 border-primary bg-transparent text-primary focus:ring-0 focus:ring-offset-0" name="payment" type="radio" value="cod">
                                    <div class="flex flex-col">
                                        <span class="text-xl font-bold italic uppercase text-primary">Cash on Delivery</span>
                                        <span class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant">Pay upon receiving your pieces - Premium Service</span>
                                    </div>
                                    <span class="material-symbols-outlined ml-auto text-primary">workspace_premium</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>
            </form>

            <aside class="lg:col-span-5">
                <div class="neon-border sticky top-32 space-y-10 border border-outline bg-surface p-10">
                    <h2 class="italic-heading mb-10 border-b border-primary/20 pb-6 text-4xl uppercase tracking-tighter">Summary</h2>

                    <div id="checkout-items" class="space-y-8 border-b border-outline pb-10"></div>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold uppercase tracking-[0.3em] text-on-surface-variant">Subtotal</span>
                            <span id="checkout-subtotal" class="font-bold italic tracking-tighter">&#8369;0</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold uppercase tracking-[0.3em] text-on-surface-variant">Shipping</span>
                            <span id="checkout-shipping" class="font-bold italic tracking-tighter">&#8369;0</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-primary/20 pt-8">
                            <span class="italic-heading text-2xl uppercase tracking-tighter">Total</span>
                            <span id="checkout-total" class="neon-glow text-4xl font-black italic tracking-tighter text-primary">&#8369;0</span>
                        </div>
                    </div>

                    <button form="checkout-form" type="submit" class="group flex w-full scale-100 items-center justify-center gap-4 bg-primary px-12 py-8 text-sm italic-heading uppercase tracking-[0.3em] text-white transition-transform duration-300 hover:scale-[1.02] hover:bg-[#8E4433] focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background active:scale-95">
                        Complete Purchase
                        <span class="material-symbols-outlined font-bold transition-transform group-hover:translate-x-2">arrow_forward</span>
                    </button>

                    <div class="flex items-center justify-center gap-4 pt-6 opacity-30">
                        <span class="material-symbols-outlined text-sm">lock</span>
                        <span class="text-[9px] font-bold uppercase tracking-[0.4em]">SSL SECURED ENCRYPTION</span>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <div id="purchase-confirmation" class="fixed inset-0 z-[60] hidden items-center justify-center bg-[#201A17]/60 px-6 py-8 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="confirmation-title">
        <div class="w-full max-w-xl border border-[#CBBBAE] bg-[#FFFDFC] p-8 text-[#201A17] shadow-xl md:p-10">
            <div class="flex items-start justify-between gap-6 border-b border-[#CBBBAE] pb-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#A6533E]">Final Review</p>
                    <h2 id="confirmation-title" class="mt-2 text-3xl font-extrabold uppercase italic">Check your details</h2>
                </div>
                <button id="close-confirmation" type="button" class="text-2xl leading-none text-[#735F56] transition-colors hover:text-[#A6533E]" aria-label="Close confirmation">&times;</button>
            </div>
            <p class="mt-6 text-sm text-[#735F56]">Please make sure everything is correct before completing your purchase.</p>
            <dl class="mt-6 space-y-4 border-y border-[#CBBBAE] py-6 text-sm">
                <div class="flex justify-between gap-6"><dt class="font-bold uppercase tracking-widest text-[#735F56]">Ship to</dt><dd id="confirm-name" class="text-right font-bold"></dd></div>
                <div class="flex justify-between gap-6"><dt class="font-bold uppercase tracking-widest text-[#735F56]">Address</dt><dd id="confirm-address" class="max-w-[65%] text-right"></dd></div>
                <div class="flex justify-between gap-6"><dt class="font-bold uppercase tracking-widest text-[#735F56]">Shipping</dt><dd id="confirm-shipping" class="text-right font-bold"></dd></div>
                <div class="flex justify-between gap-6"><dt class="font-bold uppercase tracking-widest text-[#735F56]">Payment</dt><dd id="confirm-payment" class="text-right font-bold"></dd></div>
                <div class="flex justify-between gap-6 border-t border-[#CBBBAE] pt-4"><dt class="font-bold uppercase tracking-widest text-[#735F56]">Total</dt><dd id="confirm-total" class="text-right text-xl font-extrabold text-[#A6533E]"></dd></div>
            </dl>
            <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <button id="edit-confirmation" type="button" class="border border-[#A6533E] px-6 py-4 text-xs font-bold uppercase tracking-widest text-[#A6533E] transition-colors hover:bg-[#F1E2D8]">Edit Info</button>
                <button id="confirm-purchase" type="button" class="bg-[#A6533E] px-6 py-4 text-xs font-bold uppercase tracking-widest text-white transition-colors hover:bg-[#8E4433]">Confirm Purchase</button>
            </div>
        </div>
    </div>

    <footer class="mt-24 w-full border-t border-outline bg-surface px-8 py-16">
        <div class="mx-auto flex w-full max-w-[1200px] flex-col items-center justify-between gap-12 md:flex-row">
            <div class="italic-heading text-xl uppercase tracking-tighter text-primary">
                SOLÉA FASHION CO. ATELIER
            </div>
            <div class="flex flex-wrap justify-center gap-12">
                <a class="text-[10px] uppercase tracking-[0.4em] text-on-surface-variant transition-colors hover:text-primary" href="#">PRIVACY</a>
                <a class="text-[10px] uppercase tracking-[0.4em] text-on-surface-variant transition-colors hover:text-primary" href="#">TERMS</a>
                <a class="text-[10px] uppercase tracking-[0.4em] text-on-surface-variant transition-colors hover:text-primary" href="#">SHIPPING</a>
                <a class="text-[10px] uppercase tracking-[0.4em] text-on-surface-variant transition-colors hover:text-primary" href="#">RETURNS</a>
            </div>
            <div class="text-[9px] font-bold tracking-[0.3em] text-on-surface-variant/70">
                &copy; 2024 SOLÉA FASHION CO. DIGITAL ATELIER
            </div>
        </div>
    </footer>
    <script>
        (() => {
            const checkoutForm = document.getElementById('checkout-form');
            const cart = JSON.parse(localStorage.getItem('threadlab_cart') || '[]');
            const inventory = @json($cartInventory ?? []);
            const cartInput = document.getElementById('cart_json');
            const checkoutItems = document.getElementById('checkout-items');
            const subtotalElement = document.getElementById('checkout-subtotal');
            const shippingElement = document.getElementById('checkout-shipping');
            const totalElement = document.getElementById('checkout-total');
            const completeButton = checkoutForm.closest('main').querySelector('button[form="checkout-form"]');
            const confirmation = document.getElementById('purchase-confirmation');
            const closeConfirmation = document.getElementById('close-confirmation');
            const editConfirmation = document.getElementById('edit-confirmation');
            const confirmPurchase = document.getElementById('confirm-purchase');
            let confirmed = false;
            const money = amount => `₱${Number(amount).toLocaleString('en-US')}`;
            const subtotal = cart.reduce((sum, item) => sum + (Number(item.price || 0) * Number(item.quantity || 1)), 0);
            const shipping = subtotal ? 100 : 0;
            cartInput.value = JSON.stringify(cart);
            checkoutItems.innerHTML = cart.length ? cart.map(item => `<div class="flex gap-6"><div class="relative h-36 w-28 flex-shrink-0 overflow-hidden border border-outline bg-surface-variant"><img class="h-full w-full object-cover" alt="${item.name}" src="${inventory[item.slug]?.thumbnail || item.image}"></div><div class="flex flex-grow flex-col justify-between py-2"><div><h3 class="text-lg font-bold italic uppercase tracking-tight">${item.name}</h3><p class="mt-2 text-[10px] uppercase tracking-widest text-on-surface-variant">Size: <span class="font-bold text-on-surface">${item.size}</span></p><p class="text-[10px] uppercase tracking-widest text-on-surface-variant">Qty: <span class="font-bold text-on-surface">${item.quantity}</span></p></div><span class="text-xl font-bold italic tracking-tighter text-primary">${money(item.price * item.quantity)}</span></div></div>`).join('') : '<p class="text-sm text-on-surface-variant">Your cart is empty. Return to the shop to choose a product.</p>';
            subtotalElement.textContent = money(subtotal);
            shippingElement.textContent = money(shipping);
            totalElement.textContent = money(subtotal + shipping);
            if (!cart.length) {
                completeButton.disabled = true;
                completeButton.classList.add('cursor-not-allowed', 'opacity-50');
            }
            const savedAddressSelect = document.getElementById('saved-address');
            const addressFields = ['street_address', 'city', 'zip_code', 'phone'].map(name => document.getElementById(name));
            const applySavedAddress = () => {
                const option = savedAddressSelect?.selectedOptions[0];
                if (!option || option.value === 'new') {
                    addressFields.forEach(field => { if (field) field.value = ''; });
                    return;
                }
                const address = JSON.parse(option.dataset.address || '{}');
                addressFields.forEach(field => { if (field) field.value = address[field.name] || ''; });
            };
            savedAddressSelect?.addEventListener('change', applySavedAddress);
            const paymentOptions = checkoutForm.querySelectorAll('input[name="payment"]');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const passwordMatchError = document.getElementById('password-match-error');
            const cardFields = [
                document.getElementById('card_number'),
                document.getElementById('expiry_date'),
                document.getElementById('cvv'),
            ];

            const syncPaymentValidation = () => {
                const cardSelected = checkoutForm.querySelector('input[name="payment"]:checked')?.value === 'card';
                cardFields.forEach(field => {
                    field.required = cardSelected;
                    field.disabled = !cardSelected;
                });
            };

            paymentOptions.forEach(option => option.addEventListener('change', syncPaymentValidation));
            checkoutForm.querySelectorAll('.checkout-choice').forEach(choice => {
                choice.addEventListener('click', event => {
                    if (event.target.matches('input, button, a')) return;
                    const option = choice.querySelector('input[name="payment"]');
                    if (!option) return;
                    option.checked = true;
                    option.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });
            syncPaymentValidation();

            const validatePasswordMatch = () => {
                const mismatch = passwordConfirmation.value.length > 0 && password.value !== passwordConfirmation.value;
                passwordConfirmation.setCustomValidity(mismatch ? 'Passwords do not match.' : '');
                passwordMatchError.classList.toggle('hidden', !mismatch);
                passwordConfirmation.classList.toggle('border-error', mismatch);
                return !mismatch;
            };

            password?.addEventListener('input', validatePasswordMatch);
            passwordConfirmation?.addEventListener('input', validatePasswordMatch);
            const closeReview = () => {
                confirmation.classList.add('hidden');
                confirmation.classList.remove('flex');
            };
            const openReview = () => {
                const value = name => document.getElementById(name)?.value?.trim() || 'Not provided';
                const selectedShipping = checkoutForm.querySelector('input[name="shipping"]:checked')?.value || 'Not selected';
                const selectedPayment = checkoutForm.querySelector('input[name="payment"]:checked')?.value === 'cod' ? 'Cash on Delivery' : 'Credit / Debit Card';
                document.getElementById('confirm-name').textContent = `${value('first_name')} ${value('last_name')}`;
                document.getElementById('confirm-address').textContent = `${value('street_address')}, ${value('city')} ${value('zip_code')}`;
                document.getElementById('confirm-shipping').textContent = `${selectedShipping} - ${selectedShipping === 'express' ? '₱300' : '₱100'}`;
                document.getElementById('confirm-payment').textContent = selectedPayment;
                document.getElementById('confirm-total').textContent = totalElement.textContent;
                confirmation.classList.remove('hidden');
                confirmation.classList.add('flex');
                confirmPurchase.focus();
            };
            closeConfirmation?.addEventListener('click', closeReview);
            editConfirmation?.addEventListener('click', () => { closeReview(); document.getElementById('first_name')?.focus(); });
            confirmation?.addEventListener('click', event => { if (event.target === confirmation) closeReview(); });
            confirmPurchase?.addEventListener('click', () => { confirmed = true; checkoutForm.requestSubmit(); });
            checkoutForm.addEventListener('submit', event => {
                if (!confirmed) {
                    event.preventDefault();
                    openReview();
                    return;
                }
                confirmed = false;
                if (!validatePasswordMatch()) {
                    event.preventDefault();
                    passwordConfirmation.focus();
                }
                if (!cart.length) event.preventDefault();
            });
        })();
    </script>
</body>
</html>
