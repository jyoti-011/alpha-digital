<profile-layout>
    <x-layouts.app>
        <div class="mx-auto max-w-7xl px-4 py-10 pt-[140px] sm:px-6 md:pt-[160px] lg:px-8">
            <div class="flex flex-col gap-8 md:flex-row">

                {{-- Sidebar --}}
                <div class="w-full shrink-0 md:w-1/4">
                    <div class="rounded-sm border border-outline_variant/50 bg-surface_lowest">
                        {{-- User Info Header --}}
                        <div class="border-b border-outline_variant/50 p-6">
                            <h2 class="m-0 font-serif text-lg font-bold uppercase tracking-wide text-secondary">
                                {{ auth('customer')->user()->name ?? 'User Name' }}
                            </h2>
                            <p class="m-0 mt-1 font-sans text-sm text-tertiary">
                                {{ auth('customer')->user()->email ?? '' }}
                            </p>
                        </div>

                        {{-- Navigation Links --}}
                        <nav class="flex flex-col py-2 font-sans">
                            <a href="{{ route('profile.account') }}"
                                class="{{ request()->routeIs('profile.account') ? 'border-primary text-primary bg-surface' : 'border-transparent text-tertiary hover:bg-surface_low hover:text-primary' }} flex items-center justify-between border-l-2 px-6 py-4 text-sm font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="user" class="h-4 w-4"></i>
                                    Account Details
                                </div>
                                <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i>
                            </a>

                            <a href="{{ route('profile.orders') }}"
                                class="{{ request()->routeIs('profile.orders*') ? 'border-primary text-primary bg-surface' : 'border-transparent text-tertiary hover:bg-surface_low hover:text-primary' }} flex items-center justify-between border-l-2 px-6 py-4 text-sm font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="package" class="h-4 w-4"></i>
                                    Orders
                                </div>
                                <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i>
                            </a>

                            <a href="{{ route('profile.addresses') }}"
                                class="{{ request()->routeIs('profile.addresses') ? 'border-primary text-primary bg-surface' : 'border-transparent text-tertiary hover:bg-surface_low hover:text-primary' }} flex items-center justify-between border-l-2 px-6 py-4 text-sm font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="map-pin" class="h-4 w-4"></i>
                                    Addresses
                                </div>
                                <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i>
                            </a>

                            <a href="{{ route('wishlist') }}"
                                class="flex items-center justify-between border-l-2 border-transparent px-6 py-4 text-sm font-medium text-tertiary transition-colors hover:bg-surface_low hover:text-primary">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="heart" class="h-4 w-4"></i>
                                    Wishlist
                                </div>
                                <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i>
                            </a>

                            <form method="POST" action="{{ route('customer.logout') }}"
                                class="m-0 mt-2 border-t border-outline_variant/50 p-0">
                                @csrf
                                <button type="submit"
                                    class="flex w-full cursor-pointer items-center justify-between border-l-2 border-transparent bg-transparent px-6 py-4 text-left text-sm font-medium text-tertiary outline-none transition-colors hover:bg-surface_low hover:text-primary">
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                {{-- Main Content Slot --}}
                <div class="w-full md:w-3/4">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </x-layouts.app>
