<header x-data="{ mobileMenuOpen: false }"
    class="fixed left-0 top-0 z-[1000] flex h-[76px] w-full items-center justify-between border-b border-[#E5E0DA] bg-[#F4F0EB]/90 px-[4%] shadow-sm backdrop-blur-xl transition-all duration-300 md:px-[6%]">

    <!-- Left Section: Hamburger & Logo -->
    <div class="flex items-center gap-4">
        <!-- Mobile Hamburger Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle Navigation Menu"
            class="flex items-center text-[#2A211F] transition-colors hover:text-[#800020] focus:outline-none lg:hidden">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="mobileMenuOpen" x-cloak style="display: none;" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <a href="{{ route('home') }}" wire:navigate
            class="logo flex shrink-0 items-center gap-2 text-xl font-bold tracking-widest text-[#800020] transition-transform duration-300 hover:scale-105 sm:text-2xl">
            @if ($settings && $settings->logo_type === 'image' && $settings->logo_image)
                <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Alpha Digital Logo"
                    class="h-10 w-auto object-contain drop-shadow-sm sm:h-14">
            @else
                {{ $settings->logo_text ?? 'ALPHA DIGITAL' }}
            @endif
        </a>
    </div>

    <!-- Center Section: Navigation Links -->
    <nav class="hidden flex-1 items-center justify-center gap-6 lg:flex lg:gap-10">
        <a href="{{ route('home') }}" wire:navigate
            class="{{ request()->routeIs('home') ? 'text-[#800020]' : '' }} group relative pb-1 font-sans text-[13px] font-medium tracking-[1.5px] text-[#2A211F] transition-all duration-300 hover:text-[#800020]">
            HOME
            <span
                class="{{ request()->routeIs('home') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }} absolute -bottom-1 left-0 h-[2px] w-full origin-center bg-[#800020] transition-all duration-300"></span>
        </a>
        <a href="{{ route('shop.index') }}" wire:navigate
            class="{{ request()->routeIs('shop.index') ? 'text-[#800020]' : '' }} group relative pb-1 font-sans text-[13px] font-medium tracking-[1.5px] text-[#2A211F] transition-all duration-300 hover:text-[#800020]">
            ALL SAREES
            <span
                class="{{ request()->routeIs('shop.index') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }} absolute -bottom-1 left-0 h-[2px] w-full origin-center bg-[#800020] transition-all duration-300"></span>
        </a>
        <a href="{{ route('shop.new-arrival') }}" wire:navigate
            class="{{ request()->routeIs('shop.new-arrival') ? 'text-[#800020]' : '' }} group relative pb-1 font-sans text-[13px] font-medium tracking-[1.5px] text-[#2A211F] transition-all duration-300 hover:text-[#800020]">
            NEW ARRIVAL
            <span
                class="{{ request()->routeIs('shop.new-arrival') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }} absolute -bottom-1 left-0 h-[2px] w-full origin-center bg-[#800020] transition-all duration-300"></span>
        </a>
        <a href="{{ route('shop.occasion') }}" wire:navigate
            class="{{ request()->routeIs('shop.occasion') ? 'text-[#800020]' : '' }} group relative pb-1 font-sans text-[13px] font-medium tracking-[1.5px] text-[#2A211F] transition-all duration-300 hover:text-[#800020]">
            OCCASION
            <span
                class="{{ request()->routeIs('shop.occasion') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }} absolute -bottom-1 left-0 h-[2px] w-full origin-center bg-[#800020] transition-all duration-300"></span>
        </a>
        <a href="{{ route('shop.about') }}" wire:navigate
            class="{{ request()->routeIs('shop.about') ? 'text-[#800020]' : '' }} group relative pb-1 font-sans text-[13px] font-medium tracking-[1.5px] text-[#2A211F] transition-all duration-300 hover:text-[#800020]">
            OUR STORY
            <span
                class="{{ request()->routeIs('shop.about') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }} absolute -bottom-1 left-0 h-[2px] w-full origin-center bg-[#800020] transition-all duration-300"></span>
        </a>
    </nav>

    <div class="flex shrink-0 items-center gap-4 sm:gap-6">
        <div class="relative z-[1001] hidden lg:block" x-data="searchComponent('{{ request('search') }}')">
            <form action="{{ route('shop.index') }}" method="GET" @submit="if(!query.trim()) $event.preventDefault()"
                class="m-0 flex h-[48px] items-center rounded-full border border-[#E5E0DA] bg-white/50 px-2 transition-colors focus-within:border-[#800020] focus-within:bg-white focus-within:shadow-sm hover:bg-white">
                <button type="submit" aria-label="Submit Search"
                    class="flex h-[44px] w-[44px] shrink-0 cursor-pointer items-center justify-center border-none bg-transparent text-[#706663] outline-none transition-colors hover:text-[#800020]">
                    <i data-lucide="search" aria-hidden="true" class="h-5 w-5"></i>
                </button>
                <input type="text" name="search" autocomplete="off" placeholder="Search Alpha Digital"
                    x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="open = true"
                    @click.outside="open = false" @keydown.escape.window="open = false"
                    class="m-0 h-full w-[180px] border-none bg-transparent px-2 font-sans text-[13px] text-[#2A211F] placeholder-[#706663] outline-none transition-all focus:w-[220px]">
            </form>
            <div x-show="open && suggestions.length > 0" x-transition x-cloak
                class="absolute left-0 top-[calc(100%+8px)] w-full min-w-[280px] overflow-hidden rounded-md border border-[#E5E0DA] bg-white py-2 text-left shadow-[0_10px_30px_rgba(0,0,0,0.1)]">
                <ul class="m-0 list-none p-0">
                    <template x-for="suggestion in suggestions" :key="suggestion">
                        <li>
                            <a :href="'{{ route('shop.index') }}?search=' + encodeURIComponent(suggestion)"
                                class="flex items-center px-4 py-2 text-[14px] text-[#2A211F] no-underline transition-colors hover:bg-[#F4F0EB]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-4 w-4 shrink-0 text-[#706663]"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span x-html="highlight(suggestion)" class="font-sans text-[#2A211F]"></span>
                            </a>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <div class="nav-icons flex items-center gap-4 sm:gap-5">
            <livewire:nav-icons />
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <template x-teleport="body">
        <div x-show="mobileMenuOpen" x-cloak style="display: none;" class="fixed inset-0 z-[2000] flex lg:hidden">

            <!-- Overlay -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/50">
            </div>

            <!-- Drawer Panel -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="relative flex h-full w-full max-w-xs flex-col border-r border-[#E5E0DA] bg-[#F4F0EB] pb-4 pt-5 shadow-2xl">

                <div class="mb-6 flex items-center justify-between px-4">
                    <a href="{{ route('home') }}"
                        class="font-serif text-xl font-bold tracking-widest text-[#800020]">ALPHA DIGITAL</a>
                    <button @click="mobileMenuOpen = false" aria-label="Close Navigation Menu"
                        class="text-[#2A211F] hover:text-[#800020] focus:outline-none">
                        <svg aria-hidden="true" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="relative mb-6 px-4" x-data="searchComponent('{{ request('search') }}')">
                    <form action="{{ route('shop.index') }}" method="GET"
                        @submit="if(!query.trim()) $event.preventDefault()"
                        class="m-0 flex h-[48px] items-center rounded-md border border-[#E5E0DA] bg-white px-2">
                        <button type="submit" aria-label="Submit Mobile Search"
                            class="flex h-[44px] w-[44px] shrink-0 items-center justify-center text-[#706663]">
                            <i data-lucide="search" aria-hidden="true" class="h-5 w-5"></i>
                        </button>
                        <input type="text" name="search" autocomplete="off" placeholder="Search"
                            x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="open = true"
                            @click.outside="open = false" @keydown.escape.window="open = false"
                            class="h-full w-full border-none bg-transparent px-2 font-sans text-[14px] outline-none">
                    </form>
                    <div x-show="open && suggestions.length > 0" x-transition x-cloak
                        class="absolute left-4 right-4 top-[calc(100%+8px)] z-50 overflow-hidden rounded-md border border-[#E5E0DA] bg-white py-2 text-left shadow-[0_10px_30px_rgba(0,0,0,0.1)]">
                        <ul class="m-0 list-none p-0">
                            <template x-for="suggestion in suggestions" :key="suggestion">
                                <li>
                                    <a :href="'{{ route('shop.index') }}?search=' + encodeURIComponent(suggestion)"
                                        class="flex items-center px-4 py-3 text-[14px] text-[#2A211F] no-underline transition-colors hover:bg-[#F4F0EB]">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="mr-3 h-4 w-4 shrink-0 text-[#706663]" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <span x-html="highlight(suggestion)" class="font-sans text-[#2A211F]"></span>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <div class="flex-1 space-y-1 overflow-y-auto px-2">
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'bg-white text-[#800020]' : '' }} block rounded-md px-4 py-3 text-base font-bold uppercase tracking-widest text-[#2A211F] transition-colors hover:bg-white hover:text-[#800020]">Home</a>
                    <a href="{{ route('shop.index') }}"
                        class="{{ request()->routeIs('shop.index') ? 'bg-white text-[#800020]' : '' }} block rounded-md px-4 py-3 text-base font-bold uppercase tracking-widest text-[#2A211F] transition-colors hover:bg-white hover:text-[#800020]">All
                        Sarees</a>
                    <a href="{{ route('shop.new-arrival') }}"
                        class="{{ request()->routeIs('shop.new-arrival') ? 'bg-white text-[#800020]' : '' }} block rounded-md px-4 py-3 text-base font-bold uppercase tracking-widest text-[#2A211F] transition-colors hover:bg-white hover:text-[#800020]">New
                        Arrival</a>
                    <a href="{{ route('shop.occasion') }}"
                        class="{{ request()->routeIs('shop.occasion') ? 'bg-white text-[#800020]' : '' }} block rounded-md px-4 py-3 text-base font-bold uppercase tracking-widest text-[#2A211F] transition-colors hover:bg-white hover:text-[#800020]">Occasion</a>
                    <a href="{{ route('shop.about') }}"
                        class="{{ request()->routeIs('shop.about') ? 'bg-white text-[#800020]' : '' }} block rounded-md px-4 py-3 text-base font-bold uppercase tracking-widest text-[#2A211F] transition-colors hover:bg-white hover:text-[#800020]">Our
                        Story</a>

                    <div class="mt-4 border-t border-[#E5E0DA] pt-4">
                        @auth('customer')
                            <a href="{{ route('profile.account') }}"
                                class="block rounded-md px-4 py-3 text-base font-bold uppercase tracking-widest text-[#2A211F] transition-colors hover:bg-white hover:text-[#800020]">My
                                Account</a>
                            <a href="{{ route('profile.orders') }}"
                                class="block rounded-md px-4 py-3 text-base font-bold uppercase tracking-widest text-[#2A211F] transition-colors hover:bg-white hover:text-[#800020]">Order
                                History</a>
                            <form method="POST" action="{{ route('customer.logout') }}" class="m-0 p-0">
                                @csrf
                                <button type="submit"
                                    class="block w-full rounded-md px-4 py-3 text-left text-base font-bold uppercase tracking-widest text-[#800020] transition-colors hover:bg-white">Sign
                                    Out</button>
                            </form>
                        @else
                            <button @click="$dispatch('open-login-modal'); mobileMenuOpen = false"
                                class="block w-full rounded-md px-4 py-3 text-left text-base font-bold uppercase tracking-widest text-[#800020] transition-colors hover:bg-white">Login
                                / Sign Up</button>
                        @endauth
                    </div>
                </div>
            </div>
    </template>

    <script>
        if (typeof window.searchComponent === 'undefined') {
            window.searchComponent = function(initialQuery = '') {
                return {
                    query: initialQuery,
                    suggestions: [],
                    open: false,
                    async fetchSuggestions() {
                        if (this.query.trim().length < 2) {
                            this.suggestions = [];
                            this.open = false;
                            return;
                        }
                        try {
                            const response = await fetch(
                                `/api/search-suggestions?q=${encodeURIComponent(this.query)}`);
                            this.suggestions = await response.json();
                            this.open = true;
                        } catch (error) {
                            console.error('Error fetching suggestions:', error);
                        }
                    },
                    highlight(text) {
                        if (!this.query) return text;
                        const escapedQuery = this.query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                        const regex = new RegExp(`(${escapedQuery})`, 'i');
                        const parts = text.split(regex);
                        return parts.map(part => {
                            if (part.toLowerCase() === this.query.toLowerCase()) {
                                return `<span class="font-normal">${part}</span>`;
                            }
                            return part ? `<span class="font-bold">${part}</span>` : '';
                        }).join('');
                    }
                };
            }
        }
    </script>
</header>
