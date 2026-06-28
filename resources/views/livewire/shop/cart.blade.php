<main class="min-h-screen bg-[#fbf9f5] pb-4 pt-[80px]">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        <x-checkout-progress step="1" />

        {{-- ELEGANT EDITORIAL HEADER --}}
        <div class="mb-4 text-left">
            <h1 class="mb-2 text-3xl font-bold leading-none tracking-tight text-[#1b1c1a] md:text-4xl"
                style="font-family: 'Noto Serif', serif;">
                Your Collection
            </h1>
            <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-500"
                style="font-family: 'Manrope', sans-serif;">
                Review your selected collection
            </p>
        </div>

        @if (count($this->cartData['items']) > 0)
            <div class="grid grid-cols-1 items-start gap-12 lg:grid-cols-12 lg:gap-16">

                {{-- Left Column: Cart Items --}}
                <div class="border-t border-[#E5E0DA] lg:col-span-8">
                    @foreach ($this->cartData['items'] as $id => $item)
                        @php
                            $product = $item['product'];

                            $img =
                                is_array($product->images) && count($product->images) > 0
                                    ? asset('storage/' . $product->images[0])
                                    : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                        @endphp

                        <div class="group relative flex flex-col items-stretch gap-6 border-b border-[#E5E0DA] py-6 sm:flex-row"
                            wire:key="item-{{ $id }}">

                            {{-- Product Image --}}
                            <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" wire:navigate
                                class="relative mt-1 block w-24 flex-shrink-0 overflow-hidden bg-[#F4F0EB] shadow-sm sm:w-28">
                                <img src="{{ $img }}" alt="{{ $product->name }}"
                                    class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                            </a>

                            {{-- Product Details --}}
                            <div class="flex flex-1 flex-col justify-between pr-8">
                                <div>
                                    <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" wire:navigate>
                                        <h3 class="mb-1.5 text-lg font-bold leading-snug text-[#800020] transition-colors hover:text-[#570013] sm:text-xl"
                                            style="font-family: 'Noto Serif', serif;">
                                            {{ $product->name }}
                                        </h3>
                                    </a>

                                    <p class="mb-3 text-[13px] font-medium text-[#706663]"
                                        style="font-family: 'Manrope', sans-serif;">
                                        Size: Free Size
                                    </p>

                                    {{-- Price --}}
                                    <div class="mb-4 flex items-center gap-2.5 font-bold"
                                        style="font-family: 'Manrope', sans-serif;">
                                        @if ($product->original_price && $product->original_price > $product->current_price)
                                            <span class="flex items-center text-[13px] text-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="mr-0.5">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg>
                                                {{ round((($product->original_price - $product->current_price) / $product->original_price) * 100) }}%
                                            </span>
                                            <span class="text-sm text-gray-400 line-through">
                                                ₹{{ number_format($product->original_price * $item['qty']) }}
                                            </span>
                                        @endif
                                        <span class="text-base text-[#1b1c1a] sm:text-lg">
                                            ₹{{ number_format($product->current_price * $item['qty']) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    {{-- Quantity Selector --}}
                                    <div
                                        class="inline-flex h-9 items-center rounded-sm border border-[#E5E0DA] bg-white shadow-sm">
                                        <button wire:click="decrementQty({{ $id }})"
                                            class="flex h-full w-9 items-center justify-center text-gray-500 transition hover:bg-[#F4F0EB]">-</button>
                                        <span class="w-10 border-x border-[#E5E0DA] text-center text-[13px] font-bold"
                                            style="font-family: 'Manrope', sans-serif;">{{ $item['qty'] }}</span>
                                        <button wire:click="incrementQty({{ $id }})"
                                            class="flex h-full w-9 items-center justify-center text-gray-500 transition hover:bg-[#F4F0EB]">+</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Remove Button --}}
                            <button
                                class="absolute right-0 top-6 p-1 text-gray-400 transition-colors hover:text-red-700"
                                wire:click="removeItem({{ $id }})" title="Remove item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Right Column: Order Summary --}}
                <aside class="h-fit rounded-sm border border-[#E5E0DA] bg-white p-8 shadow-sm lg:col-span-4">
                    <h2 class="mb-6 border-b border-[#E5E0DA] pb-4 text-2xl font-bold text-[#1b1c1a]"
                        style="font-family: 'Noto Serif', serif;">
                        Order Summary
                    </h2>

                    <div class="mb-3 flex justify-between text-sm text-[#706663]"
                        style="font-family: 'Manrope', sans-serif;">
                        <span>Price ({{ $this->cartData['totalItems'] }}
                            item{{ $this->cartData['totalItems'] > 1 ? 's' : '' }})</span>
                        <span>₹{{ number_format($this->cartData['totalOriginalPrice']) }}</span>
                    </div>

                    @if ($this->cartData['totalDiscount'] > 0)
                        <div class="mb-3 flex justify-between text-sm" style="font-family: 'Manrope', sans-serif;">
                            <span class="text-[#706663]">Discount</span>
                            <span class="font-medium text-green-600">-
                                ₹{{ number_format($this->cartData['totalDiscount']) }}</span>
                        </div>
                    @endif

                    <div class="relative mb-4 flex justify-between text-sm text-[#706663]"
                        style="font-family: 'Manrope', sans-serif;" x-data="{ tooltipOpen: false }">
                        <span class="group flex cursor-pointer items-center gap-1" @mouseenter="tooltipOpen = true"
                            @mouseleave="tooltipOpen = false" @click="tooltipOpen = !tooltipOpen">
                            Shipping
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-gray-400 transition-colors hover:text-[#800020]">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>

                            <!-- Tooltip -->
                            <div x-show="tooltipOpen" x-cloak x-transition.opacity.duration.200ms
                                class="absolute bottom-full left-0 z-50 mb-2 w-64 rounded-sm border border-[#E5E0DA] bg-white p-3 text-xs text-[#706663] shadow-lg">
                                <p class="mb-1 font-bold text-[#1b1c1a]">Shipping Policy</p>
                                <p>We offer complimentary shipping on all orders above Rs. 10,000. For orders below this
                                    amount, a standard shipping fee of Rs. 150 applies.</p>
                                <!-- Arrow -->
                                <div
                                    class="absolute left-16 top-full -mt-[1px] h-3 w-3 rotate-45 transform border-b border-r border-[#E5E0DA] bg-white">
                                </div>
                            </div>
                        </span>
                        <span>{{ $this->cartData['shipping'] == 0 ? 'Complimentary' : '₹' . number_format($this->cartData['shipping']) }}</span>
                    </div>

                    <div class="my-4 border-t border-dashed border-[#E5E0DA]"></div>

                    <div class="mb-4 flex justify-between text-base font-bold text-[#1b1c1a]"
                        style="font-family: 'Noto Serif', serif;">
                        <span>Total Amount</span>
                        <span>₹{{ number_format($this->cartData['total']) }}</span>
                    </div>

                    @if ($this->cartData['totalDiscount'] > 0)
                        <div class="mb-6 flex items-center justify-center gap-2 rounded bg-green-50 px-4 py-3 text-sm font-medium text-green-700"
                            style="font-family: 'Manrope', sans-serif;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.41l9 9c.36.36.86.58 1.41.58s1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41s-.23-1.06-.59-1.41zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7zM11 13.5l-2-2 1.41-1.41L11 10.67l3.09-3.09L15.5 9l-4.5 4.5z" />
                            </svg>
                            You'll save ₹{{ number_format($this->cartData['totalDiscount']) }} on this order!
                        </div>
                    @else
                        <div class="mb-4"></div>
                    @endif

                    <div
                        class="mb-4 flex items-start gap-2 rounded border border-yellow-200 bg-yellow-50 p-3 text-xs text-yellow-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="m-0 font-medium leading-snug" style="font-family: 'Manrope', sans-serif;">
                            <strong>Disclaimer:</strong> Payment feature is currently in test mode. No real transactions
                            will be made.
                        </p>
                    </div>

                    <button wire:click="checkout" wire:loading.attr="disabled"
                        class="mb-4 flex w-full items-center justify-center gap-2 rounded-sm bg-[#800020] py-4 text-xs font-bold uppercase tracking-[0.15em] text-white shadow-md transition-colors hover:bg-[#570013] disabled:cursor-wait disabled:opacity-75">
                        <span wire:loading.remove wire:target="checkout">Proceed to Checkout</span>
                        <span wire:loading wire:target="checkout">
                            <svg class="-ml-1 mr-2 h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Connecting securely...
                        </span>
                    </button>



                    <div class="border-t border-dashed border-[#E5E0DA] pt-6 text-center">
                        <p class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-[#A68A64]"
                            style="font-family: 'Manrope', sans-serif;">
                            Heritage Craft Secure Global Payment
                        </p>
                    </div>
                </aside>

            </div>
        @else
            {{-- Empty State (Now safely outside the grid, perfectly centered) --}}
            <div class="flex w-full flex-col items-center justify-center border-t border-[#E5E0DA] py-24 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mb-4 h-16 w-16 text-gray-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="mb-8 text-lg text-gray-500" style="font-family: 'Manrope', sans-serif;">Your collection is
                    currently empty.</p>
                <a href="{{ route('shop.index') }}" wire:navigate
                    class="inline-block rounded-sm bg-[#800020] px-8 py-3.5 text-xs font-bold uppercase tracking-[0.15em] text-white shadow-md transition-colors hover:bg-[#570013]">
                    Discover Sarees
                </a>
            </div>
        @endif

    </div>
</main>
