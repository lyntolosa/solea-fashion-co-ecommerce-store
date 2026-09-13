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
                <button type="button" data-demo-password-trigger class="flex w-full items-center gap-2 px-3 py-2 text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant transition hover:bg-surface-container-high hover:text-primary-container">
                    <span class="material-symbols-outlined text-base">lock</span>Change Password
                </button>
                <a href="{{ route('admin.logout') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-bold uppercase tracking-wider text-on-surface-variant transition hover:bg-surface-container-high hover:text-primary-container">
                    <span class="material-symbols-outlined text-base">logout</span>Exit Admin
                </a>
            </div>
        </div>
    </div>
</header>
<div id="admin-demo-password-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 px-4" role="dialog" aria-modal="true" aria-labelledby="admin-demo-password-title">
    <div class="w-full max-w-md border border-outline-variant bg-surface p-7 shadow-2xl">
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-primary-container">Portfolio Live Demo</p>
        <h2 id="admin-demo-password-title" class="font-headline text-2xl font-bold text-on-surface">Password changes are disabled</h2>
        <p class="mt-3 text-sm leading-6 text-on-surface-variant">This shop is for live demo only. Password changes are disabled for portfolio reviewers.</p>
        <button type="button" data-demo-password-close class="mt-6 bg-primary px-5 py-3 text-xs font-bold uppercase tracking-widest text-on-primary transition hover:bg-primary-container">Close</button>
    </div>
</div>
<script>
    (() => {
        const modal = document.getElementById('admin-demo-password-modal');
        if (!modal) return;
        const open = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
        const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); };
        document.querySelectorAll('[data-demo-password-trigger]').forEach((button) => button.addEventListener('click', open));
        modal.querySelector('[data-demo-password-close]').addEventListener('click', close);
        modal.addEventListener('click', (event) => { if (event.target === modal) close(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape') close(); });
    })();
</script>
