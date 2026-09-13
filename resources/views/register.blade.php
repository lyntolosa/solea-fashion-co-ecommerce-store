<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} | JOIN THE REGISTRY</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .editorial-text-shadow { text-shadow: 0 0 40px rgba(213,251,0,.4); }
    </style>
</head>
<body class="overflow-x-hidden bg-background font-body text-white">`r
@include('partials.demo-notice')
    <nav class="fixed top-0 z-50 flex w-full items-center justify-between border-b border-white/5 bg-neutral-950/80 px-6 py-4 backdrop-blur-xl">
        <a href="{{ route('home') }}" class="font-headline text-2xl font-black italic tracking-tighter text-primary-container">SOLÉA FASHION CO.</a>
        <div class="hidden items-center space-x-12 md:flex"><a class="text-sm tracking-widest text-neutral-400 transition hover:text-white" href="{{ route('shop') }}">SHOP</a><a class="text-sm tracking-widest text-neutral-400 transition hover:text-white" href="{{ route('shop') }}">COLLECTIONS</a><a class="text-sm tracking-widest text-neutral-400 transition hover:text-white" href="{{ route('contact') }}">CONTACT</a></div>
        <div class="flex items-center space-x-6"><a href="{{ route('cart') }}" class="text-neutral-400 transition hover:text-primary-container" aria-label="Shopping bag"><span class="material-symbols-outlined">shopping_bag</span></a>@include('partials.account-menu')</div>
    </nav>

    <main class="grid min-h-screen grid-cols-1 pt-16 md:grid-cols-2 md:pt-0">
        <section class="relative flex h-[409px] w-full items-center justify-center overflow-hidden bg-surface md:h-screen"><div class="absolute inset-0"><img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAfW8aakj1ITa020I4Wo6OfjLAD1M08JZOtbMNmSJJjZ9js9pS39TeE7wQhipMstE4WjpSTbb4UCJSEKM70bbdagvCwU_cNg-D0gtEgqJtGOVkeWwpfaruGMcKXZJcIYLR-6-5H-2FztLNIDtuo1attZ7u7PYLb9A4BRRW671tZh_XuFjE1a5OIe-R52kBsr0caRPsqKeFaM-VfLG6FKb0yKUXx2JpMR1c-jn2YxZamY1omIDCLKMV2oSrHdxMpHHVIuffWqi5EEtU" alt="Editorial streetwear" class="h-full w-full object-cover grayscale brightness-50"><div class="absolute inset-0 bg-gradient-to-tr from-background via-transparent to-transparent opacity-80"></div></div><div class="relative z-10 w-full max-w-2xl p-12 text-center md:p-16 md:text-left"><p class="mb-2 font-headline text-xl font-black italic tracking-tighter text-primary-container md:text-2xl">V-01 ACCESS GRANTED</p><h1 class="editorial-text-shadow font-headline text-5xl font-extrabold leading-none tracking-tighter md:text-8xl">JOIN THE<br>REGISTRY</h1><div class="mt-8 flex items-center justify-center space-x-4 md:justify-start"><div class="h-px w-12 bg-primary-container"></div><span class="text-xs uppercase tracking-[.3em] text-neutral-400">EST. 2024 / CORE UNIT</span></div></div></section>

        <section class="relative flex flex-col items-center justify-center bg-background p-8 md:p-16 lg:p-20"><div class="w-full max-w-md space-y-10"><header class="space-y-4"><h2 class="font-headline text-4xl font-extrabold tracking-tighter md:text-5xl">Create Account</h2><p class="text-lg text-on-surface-variant">Start your journey with Soléa Fashion Co.</p></header>
            @if ($errors->any())<div class="border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm text-red-200"><p class="font-bold">Please check your registration details.</p><ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('register.create') }}" method="POST" class="space-y-8"><div class="space-y-6"><div class="group"><label for="name" class="mb-2 block text-xs uppercase tracking-widest text-neutral-500">Full Name</label><input id="name" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-0 bg-surface-container-highest p-4 text-white placeholder:text-neutral-700 focus:ring-2 focus:ring-secondary" placeholder="ALEXANDER VOGUE" type="text" autocomplete="name" required></div><div class="group"><label for="email" class="mb-2 block text-xs uppercase tracking-widest text-neutral-500">Email Address</label><input id="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-0 bg-surface-container-highest p-4 text-white placeholder:text-neutral-700 focus:ring-2 focus:ring-secondary" placeholder="IDENTITY@THREADLAB.COM" type="email" autocomplete="email" required></div><div class="grid grid-cols-1 gap-4 md:grid-cols-2"><div class="group"><label for="password" class="mb-2 block text-xs uppercase tracking-widest text-neutral-500">Password</label><input id="password" name="password" class="w-full rounded-lg border-0 bg-surface-container-highest p-4 text-white placeholder:text-neutral-700 focus:ring-2 focus:ring-secondary" placeholder="Enter password" type="password" autocomplete="new-password" required></div><div class="group"><label for="password_confirmation" class="mb-2 block text-xs uppercase tracking-widest text-neutral-500">Confirm</label><input id="password_confirmation" name="password_confirmation" class="w-full rounded-lg border-0 bg-surface-container-highest p-4 text-white placeholder:text-neutral-700 focus:ring-2 focus:ring-secondary" placeholder="Repeat password" type="password" autocomplete="new-password" required></div></div></div><label for="terms" class="flex items-start gap-3 text-sm leading-tight text-neutral-400"><input id="terms" name="terms" value="1" type="checkbox" class="mt-0.5 h-5 w-5 rounded-sm border-outline-variant bg-surface-container-highest text-primary-container focus:ring-0" required><span>I agree to the <a href="#" class="text-secondary underline-offset-4 hover:underline">Terms &amp; Privacy Policy</a></span></label><button class="w-full bg-gradient-to-r from-primary-container to-primary-container py-5 font-headline text-sm font-extrabold uppercase tracking-widest text-black transition hover:opacity-90 active:scale-[.98]" type="submit">Register</button>@csrf</form>
            <footer class="flex flex-col items-center justify-between space-y-4 border-t border-outline-variant/20 pt-8 md:flex-row md:space-y-0"><p class="text-sm text-neutral-500">Already a member? <a href="{{ route('login') }}" class="font-bold text-white transition hover:text-primary-container">Login</a></p><div class="flex space-x-6 text-xs tracking-tighter text-neutral-600"><span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">verified_user</span>ENCRYPTED</span><span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">public</span>GLOBAL ACCESS</span></div></footer>
        </div></section>
    </main>
    <footer class="flex flex-col items-center justify-between gap-4 border-t border-neutral-800/20 bg-neutral-950 px-10 py-8 md:flex-row"><div class="text-xs font-bold uppercase tracking-widest text-neutral-500">&copy; 2024 SOLÉA FASHION CO.. ALL RIGHTS RESERVED.</div><div class="flex space-x-8"><a href="#" class="text-[10px] text-neutral-600 transition hover:text-primary-container">PRIVACY</a><a href="#" class="text-[10px] text-neutral-600 transition hover:text-primary-container">TERMS</a><a href="#" class="text-[10px] text-neutral-600 transition hover:text-primary-container">ACCESSIBILITY</a></div></footer>
</body>
</html>
