<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Soléa Fashion Co.') }} Admin | LOGIN</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: {
            colors: { primary: '#A6533E', 'primary-container': '#A6533E', 'on-primary-container': '#FFFFFF', background: '#201A17', surface: '#FFFDFC', 'surface-container-highest': '#EFE5DB', 'on-surface-variant': '#735F56', secondary: '#7799FF' },
            fontFamily: { headline: ['Plus Jakarta Sans', 'sans-serif'], body: ['Inter', 'sans-serif'] }
        } } };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        body { font-family: 'Inter', sans-serif; background: #0B0B0B; }
        h1, h2, h3 { font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-glow:hover { box-shadow: 0 8px 20px rgba(166,83,62,.28); }
        .admin-login-panel { color: #201A17; }
        .admin-login-panel h2 { color: #201A17; }
        .admin-login-panel label,
        .admin-login-panel p { color: #735F56 !important; }
        .admin-login-panel input[type="email"],
        .admin-login-panel input[type="password"] {
            border: 1px solid #CBBBAE !important;
            background: #EFE5DB !important;
            color: #201A17 !important;
            box-shadow: none !important;
        }
        .admin-login-panel input[type="email"]::placeholder,
        .admin-login-panel input[type="password"]::placeholder { color: #735F56 !important; opacity: .8; }
        .admin-login-panel input[type="email"]:focus,
        .admin-login-panel input[type="password"]:focus {
            border-color: #A6533E !important;
            box-shadow: 0 0 0 2px rgba(166,83,62,.18) !important;
            outline: none !important;
        }
        .admin-login-panel input[type="checkbox"] { accent-color: #A6533E; }
    </style>
</head>
<body class="overflow-hidden bg-background text-white antialiased selection:bg-primary selection:text-black">`r
@include('partials.demo-notice')
    <main class="flex min-h-screen flex-col md:flex-row">
        <section class="relative flex h-[420px] w-full items-end overflow-hidden bg-black p-8 md:h-screen md:w-1/2 md:p-16 lg:w-3/5 lg:p-24">
            <div class="absolute inset-0"><img src="{{ asset('images/solea-login-editorial.png') }}" alt="Soléa editorial fashion atelier" class="h-full w-full object-cover opacity-90"><div class="absolute inset-0 bg-gradient-to-t from-background via-background/15 to-transparent opacity-75"></div><div class="absolute inset-0 bg-gradient-to-r from-background/70 via-background/15 to-transparent opacity-55"></div></div>
            <div class="relative z-10 max-w-2xl"><div class="mb-12"><a href="{{ route('home') }}" class="font-headline text-2xl font-black italic tracking-tighter text-primary">Soléa Fashion Co.</a><span class="mt-2 block text-xs uppercase tracking-[.3em] text-white/40">SOLÉA FASHION CO. Systems</span></div><h1 class="mb-8 font-headline text-5xl font-extrabold leading-[.9] tracking-tighter md:text-7xl lg:text-8xl">Manage Your <span class="italic text-primary">Store</span> with <br>Confidence</h1><p class="max-w-md border-l-2 border-primary pl-6 text-lg leading-relaxed text-white/60 md:text-xl">Track orders, manage products, and monitor performance in one place. The digital flagship for your Soléa brand evolution.</p><div class="mt-16 flex items-center gap-12 text-white/20"><div><span class="block font-headline text-3xl font-bold text-white">99.9%</span><span class="text-[10px] uppercase tracking-widest">Uptime Performance</span></div><div><span class="block font-headline text-3xl font-bold text-white">0.02s</span><span class="text-[10px] uppercase tracking-widest">Global Latency</span></div></div></div>
        </section>

        <section class="admin-login-panel relative flex w-full flex-col items-center justify-center bg-surface p-6 md:w-1/2 md:p-12 lg:w-2/5 lg:p-20"><div class="w-full max-w-md"><div class="mb-10"><h2 class="mb-2 font-headline text-3xl font-bold tracking-tight md:text-4xl">Admin Login</h2><p class="text-sm text-white/50 md:text-base">Access the Soléa Fashion Co. dashboard</p></div>
                @if ($errors->any())<div class="mb-6 border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm text-red-200"><p class="font-bold">Please check your admin credentials.</p><ul class="mt-1 list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('admin.login.authenticate') }}" method="POST" class="space-y-6"><div class="space-y-1.5"><label for="email" class="ml-1 text-[10px] font-semibold uppercase tracking-widest text-white/40">Email Address</label><input id="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border-0 bg-surface-container-highest px-4 py-4 text-white placeholder:text-white/20 focus:ring-2 focus:ring-primary" placeholder="admin@threadlab.studio" required type="email" autocomplete="email"></div><div class="space-y-1.5"><label for="password" class="ml-1 text-[10px] font-semibold uppercase tracking-widest text-white/40">Password</label><input id="password" name="password" class="w-full rounded-xl border-0 bg-surface-container-highest px-4 py-4 text-white placeholder:text-white/20 focus:ring-2 focus:ring-primary" placeholder="Enter your password" required type="password" autocomplete="current-password"></div><div class="flex items-center justify-between py-2"><label for="remember" class="flex cursor-pointer items-center gap-3 text-xs text-white/40"><input id="remember" name="remember" type="checkbox" class="h-5 w-5 rounded border-0 bg-surface-container-highest text-primary focus:ring-primary">Remember Me</label><a href="#" class="text-xs font-bold italic text-primary transition hover:text-white">Forgot Password?</a></div><button class="btn-glow mt-4 w-full rounded-xl bg-primary py-5 font-headline text-sm font-black uppercase tracking-widest text-black transition-all active:scale-[.98]" type="submit">Login</button>@csrf</form>
            <div class="mt-12 border-t border-white/10 pt-8 text-center"><p class="text-sm text-white/40">Authorized personnel only. Customer registration is unavailable from this portal.</p></div>
        </div><footer class="absolute bottom-8 flex w-full items-center justify-between px-8 text-white/40"><span class="text-[9px] uppercase tracking-[.3em]">&copy; 2024 SOLÉA FASHION CO. SOLÉA FASHION CO.</span><div class="flex gap-6"><a href="#" class="text-[9px] uppercase tracking-widest transition hover:text-primary">Support</a><a href="#" class="text-[9px] uppercase tracking-widest transition hover:text-primary">System Status</a></div></footer></section>
    </main>
</body>
</html>
