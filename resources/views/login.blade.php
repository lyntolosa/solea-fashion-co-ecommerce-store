<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | LOGIN</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,800;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: {
            colors: { background: '#0E0E0E', surface: '#0E0E0E', 'surface-container-highest': '#262626', 'on-surface-variant': '#ADAAAA', 'primary-container': '#D5FB00', 'on-primary-container': '#000000', secondary: '#7799FF' },
            fontFamily: { headline: ['Plus Jakarta Sans', 'sans-serif'], body: ['Inter', 'sans-serif'], label: ['Inter', 'sans-serif'] }
        } } };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        body { font-family: 'Inter', sans-serif; }
        .editorial-gradient { background: linear-gradient(to bottom, rgba(36,28,24,.12), rgba(36,28,24,.72)) !important; }
        .editorial-gradient h1 { color: #fffaf5 !important; text-shadow: 0 2px 16px rgba(36,28,24,.72); }
        .editorial-gradient p { color: #f4b39e !important; text-shadow: 0 1px 8px rgba(36,28,24,.6); }
        .editorial-gradient + div.absolute.left-8.top-8 { border-color: rgba(255,250,245,.5) !important; background: rgba(36,28,24,.2); }
        .editorial-gradient + div.absolute.left-8.top-8 span { color: #fffaf5 !important; }
        .btn-volt-gradient { background: linear-gradient(90deg, #F5FFC4 0%, #D5FB00 100%); }
    </style>
</head>
<body class="bg-background font-body text-white selection:bg-primary-container selection:text-black">`r
@include('partials.demo-notice')
    <main class="flex min-h-screen flex-col md:flex-row">
        <section class="relative h-[360px] w-full overflow-hidden md:h-screen md:w-1/2 lg:w-3/5">
            <img src="{{ asset('images/solea-login-editorial.png') }}" alt="Soléa editorial fashion atelier" class="absolute inset-0 h-full w-full object-cover">
            <div class="editorial-gradient absolute inset-0 flex flex-col justify-end p-8 md:p-16 lg:p-24"><div class="space-y-2"><h1 class="font-headline text-5xl font-black italic leading-[.85] tracking-tighter md:text-7xl lg:text-8xl">SOLÉA ACCESS</h1><div class="flex items-center gap-4"><div class="h-[2px] w-12 bg-primary-container"></div><p class="text-sm font-bold tracking-[.3em] text-primary-container md:text-base">THE DIGITAL ATELIER</p></div></div></div>
            <div class="absolute left-8 top-8 border border-white/10 px-4 py-2 backdrop-blur-md"><span class="text-[10px] uppercase tracking-widest text-on-surface-variant">EST. 2024 / AUTHENTICATED</span></div>
        </section>

        <section class="flex w-full flex-col justify-center bg-surface px-8 py-16 md:w-1/2 md:px-16 lg:w-2/5 lg:px-24">
            <div class="mx-auto w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-12 inline-block font-headline text-2xl font-black italic tracking-tighter text-primary-container">SOLÉA FASHION CO.</a>
                <header class="mb-10"><h2 class="mb-3 font-headline text-4xl font-bold tracking-tight">Welcome Back</h2><p class="text-on-surface-variant">Login to access your SOLÉA FASHION CO. registry.</p></header>

                @if ($errors->any())
                    <div class="mb-6 border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm text-red-200"><p class="font-bold">Please check your details.</p><ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                @if (session('status'))
                    <div class="mb-6 border border-primary-container/40 bg-primary-container/10 px-4 py-3 text-sm text-primary-container">{{ session('status') }}</div>
                @endif

                <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2"><label for="email" class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant">Email Address</label><input id="email" name="email" value="{{ old('email', 'rctolosa.customer@soleafashion.demo') }}" class="w-full rounded-none border-0 bg-surface-container-highest px-5 py-4 text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-secondary" placeholder="name@SOLÉA FASHION CO..com" type="email" autocomplete="email" required></div>
                    <div class="space-y-2"><div class="flex items-center justify-between"><label for="password" class="text-xs font-semibold uppercase tracking-widest text-on-surface-variant">Password</label><a href="#" class="text-[10px] font-bold uppercase tracking-tighter text-secondary transition hover:text-primary-container">Forgot Password?</a></div><input id="password" name="password" value="{{ old('password', 'RCTOLOSA2026!') }}" class="w-full rounded-none border-0 bg-surface-container-highest px-5 py-4 text-white placeholder:text-neutral-600 focus:ring-2 focus:ring-secondary" placeholder="Enter your password" type="password" autocomplete="current-password" required></div>
                    <label for="remember" class="flex cursor-pointer select-none items-center gap-3 py-2 text-xs text-neutral-400"><input id="remember" name="remember" type="checkbox" class="h-5 w-5 rounded-none border-0 bg-surface-container-highest text-primary-container focus:ring-0">Remember Me</label>
                    <div class="pt-4"><button class="btn-volt-gradient w-full py-5 font-headline text-sm font-black uppercase tracking-widest text-black transition hover:brightness-110 active:scale-[.98]" type="submit">Login</button></div>
                </form>
                <footer class="mt-12 border-t border-white/5 pt-8 text-center"><p class="text-sm text-on-surface-variant">New to Soléa Fashion Co.? <a href="{{ route('register') }}" class="ml-1 font-bold text-white underline decoration-primary-container underline-offset-8 transition hover:text-primary-container">Create Account</a></p></footer>
            </div>
        </section>
    </main>
    <footer class="flex flex-col items-center justify-between gap-5 border-t border-neutral-800/20 bg-neutral-950 px-10 py-8 md:flex-row"><a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-tighter text-neutral-500">SOLÉA FASHION CO.</a><div class="flex gap-8"><a href="#" class="text-[10px] tracking-widest text-neutral-600 transition hover:text-primary-container">PRIVACY</a><a href="#" class="text-[10px] tracking-widest text-neutral-600 transition hover:text-primary-container">TERMS</a><a href="#" class="text-[10px] tracking-widest text-neutral-600 transition hover:text-primary-container">ACCESSIBILITY</a></div><p class="text-[10px] tracking-widest text-neutral-600">&copy; 2024 SOLÉA FASHION CO.. ALL RIGHTS RESERVED.</p></footer>
</body>
</html>
