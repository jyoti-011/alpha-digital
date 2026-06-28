<div class="nav-icons flex items-center gap-4 sm:gap-5">
    <a href="{{ route('wishlist') }}" aria-label="Wishlist" wire:navigate
        class="group relative text-[#2A211F] transition-colors hover:text-[#800020]" title="Wishlist">
        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            class="{{ request()->routeIs('wishlist') ? 'fill-[#800020] text-[#800020]' : 'fill-none' }} h-5 w-5 transition-transform group-hover:scale-110">
            <path
                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
        </svg>
        @if ($wishlistCount > 0)
            <span
                class="absolute -right-2 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-[#800020] text-[10px] font-bold text-white shadow-sm">
                {{ $wishlistCount }}
            </span>
        @endif
    </a>

    <a href="{{ route('cart') }}" aria-label="Shopping Cart" wire:navigate
        class="group relative text-[#2A211F] transition-colors hover:text-[#800020]" title="Cart">
        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            class="{{ request()->routeIs('cart') ? 'text-[#800020]' : '' }} h-5 w-5 transition-transform group-hover:scale-110">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
            <path d="M3 6h18" />
            <path d="M16 10a4 4 0 0 1-8 0" />
        </svg>
        @if ($cartCount > 0)
            <span
                class="absolute -right-2 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-[#800020] text-[10px] font-bold text-white shadow-sm">
                {{ $cartCount }}
            </span>
        @endif
    </a>

    @auth('customer')
        <div class="relative hidden sm:block" x-data="{ open: false }">
            <button @click="open = !open" aria-label="My Account" @click.outside="open = false" title="My Account"
                class="group m-0 cursor-pointer border-none bg-transparent p-0 text-[#800020] outline-none transition-colors">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="h-5 w-5 transition-transform group-hover:scale-110">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <polyline points="16 11 18 13 22 9" />
                </svg>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2" x-cloak style="display: none;"
                class="absolute right-0 z-50 mt-6 w-64 overflow-hidden rounded-2xl border border-[#E5E0DA] bg-[#F4F0EB]/95 py-2 text-left shadow-2xl backdrop-blur-xl">

                <div class="mb-2 border-b border-[#E5E0DA]/50 bg-white/50 px-6 py-4">
                    <p class="font-sans text-[10px] font-bold uppercase tracking-widest text-[#706663]">Welcome,</p>
                    <p class="mt-1 truncate text-lg font-medium text-[#1b1c1a]" style="font-family: 'Noto Serif', serif;">
                        {{ auth('customer')->user()->name ?? 'User' }}
                    </p>
                </div>
                <a href="{{ route('profile.account') }}"
                    class="flex items-center gap-3 px-6 py-3 font-sans text-sm font-medium text-[#2A211F] transition-colors hover:bg-white/80 hover:text-[#800020]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    My Profile
                </a>
                <a href="{{ route('profile.orders') }}"
                    class="flex items-center gap-3 px-6 py-3 font-sans text-sm font-medium text-[#2A211F] transition-colors hover:bg-white/80 hover:text-[#800020]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <path d="m7.5 4.27 9 5.15" />
                        <path
                            d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                        <path d="m3.3 7 8.7 5 8.7-5" />
                        <path d="M12 22V12" />
                    </svg>
                    Order History
                </a>
                <div class="mt-2 border-t border-[#E5E0DA] pt-2">
                    <form method="POST" action="{{ route('customer.logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit"
                            class="flex w-full cursor-pointer items-center gap-3 border-none bg-transparent px-6 py-3 text-left font-sans text-sm font-medium text-[#800020] outline-none transition-colors hover:bg-white/80">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <button x-data @click="$dispatch('open-login-modal')" aria-label="Login or Signup" title="Login / Signup"
            class="group m-0 hidden cursor-pointer border-none bg-transparent p-0 text-[#2A211F] outline-none transition-colors hover:text-[#800020] sm:block">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="h-5 w-5 transition-transform group-hover:scale-110">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </button>
    @endauth

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('wishlist-updated', () => {
                sessionStorage.setItem('wishlist_changed', '1');
            });
        });

        document.addEventListener('livewire:navigated', () => {
            if (sessionStorage.getItem('wishlist_changed') === '1') {
                sessionStorage.removeItem('wishlist_changed');
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('refresh-wishlist');
                }
            }
        });
    </script>
</div>
