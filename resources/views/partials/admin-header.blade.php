<header class="fixed top-0 z-50 flex h-16 w-full items-center justify-between border-b border-outline-variant/20 bg-black/90 px-6 backdrop-blur-xl lg:pl-72">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-headline text-xl font-bold uppercase tracking-tight">
        <span>SOLÉA FASHION CO.</span><span class="text-sm font-normal text-on-surface-variant">| Store Administrator</span>
    </a>
    <div class="flex items-center gap-5">
        <a href="{{ route('home') }}" class="hidden items-center gap-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant transition hover:text-primary-container md:flex">
            <span class="material-symbols-outlined text-lg">storefront</span> Preview
        </a>
        <button type="button" class="relative text-on-surface-variant transition hover:text-primary-container" aria-label="Notifications">
            <span class="material-symbols-outlined">notifications</span><span class="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full border border-black bg-error"></span>
        </button>
        <button type="button" class="text-on-surface-variant transition hover:text-primary-container" aria-label="Help"><span class="material-symbols-outlined">help</span></button>
        <div class="group relative">
            <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full border border-outline-variant bg-surface-container-high text-primary-container transition hover:border-primary-container focus:border-primary-container focus:outline-none focus:ring-2 focus:ring-primary-container/30" aria-label="Admin profile" aria-haspopup="true">
                <span class="material-symbols-outlined text-lg">person</span>
            </button>
            <div class="invisible absolute right-0 top-full z-20 mt-3 w-40 translate-y-1 border border-outline-variant/20 bg-surface p-2 opacity-0 shadow-lg transition duration-150 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100">
                <a href="{{ route('admin.logout') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-bold uppercase tracking-wider text-on-surface-variant transition hover:bg-surface-container-high hover:text-primary-container">
                    <span class="material-symbols-outlined text-base">logout</span>Exit Admin
                </a>
            </div>
        </div>
    </div>
</header>
