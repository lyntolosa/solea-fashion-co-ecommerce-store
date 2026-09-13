<details class="account-menu group relative">
    <summary class="flex cursor-pointer list-none items-center text-white transition-colors hover:text-[#d5fb00] [&::-webkit-details-marker]:hidden" aria-label="Account menu">
        <span class="material-symbols-outlined">person</span>
    </summary>
    <div class="account-dropdown absolute right-0 top-full z-50 mt-3 w-44 border border-white/10 bg-[#171717] p-2 shadow-xl">
        @if (session()->has('customer'))
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-xs font-bold uppercase tracking-widest text-white transition-colors hover:bg-[#d5fb00] hover:text-black">Dashboard</a>
            <a href="{{ route('logout') }}" class="mt-1 block px-3 py-2 text-xs font-bold uppercase tracking-widest text-white transition-colors hover:bg-[#d5fb00] hover:text-black">Log Out</a>
        @else
            <a href="{{ route('login') }}" class="block px-3 py-2 text-xs font-bold uppercase tracking-widest text-white transition-colors hover:bg-[#d5fb00] hover:text-black">Log In</a>
        @endif
    </div>
</details>
<style>
    .account-dropdown { display: none; }
    .account-menu:hover > .account-dropdown, .account-menu[open] > .account-dropdown { display: block; }
</style>
