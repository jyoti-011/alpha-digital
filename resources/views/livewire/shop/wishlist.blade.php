<main class="min-h-screen bg-[#fbf9f5] pb-16 pt-[100px]">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">

        {{-- Alpha Digital Header --}}
        <div class="mb-10 flex items-end justify-between border-b border-[#E5E0DA] pb-6">
            <div>
                <h1 class="mb-2 text-3xl font-bold leading-none tracking-tight text-[#1b1c1a] md:text-4xl"
                    style="font-family: 'Noto Serif', serif;">
                    Your Wishlist
                </h1>
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-500"
                    style="font-family: 'Manrope', sans-serif;">
                    Curated by you at Alpha Digital
                </p>
            </div>
            <div class="text-sm font-bold text-gray-500">
                {{ count($this->wishlistItems) }} {{ count($this->wishlistItems) === 1 ? 'Item' : 'Items' }}
            </div>
        </div>


        @if ($this->wishlistItems->count() > 0)
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($this->wishlistItems as $product)
                    @php
                        $img =
                            is_array($product->images) && count($product->images) > 0
                                ? asset('storage/' . $product->images[0])
                                : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                    @endphp

                    <div class="product-card group relative flex flex-col overflow-hidden rounded-sm border border-[#E5E0DA] bg-white shadow-sm"
                        wire:key="wishlist-{{ $product->id }}">

                        <div class="relative aspect-[4/5] w-full overflow-hidden bg-[#F4F0EB]">
                            {{-- Image Link --}}
                            <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" wire:navigate
                                class="block h-full w-full">
                                <img src="{{ $img }}" alt="{{ $product->name }}"
                                    class="h-full w-full object-cover object-top transition-transform duration-700 group-hover:scale-105">
                            </a>

                            {{-- FIXED: Prevent default click bubbling --}}
                            <button wire:click.prevent="removeItem({{ $product->id }})"
                                class="absolute right-3 top-3 z-10 rounded-full bg-white/80 p-2 text-gray-500 shadow-sm backdrop-blur-md transition-colors hover:text-red-600"
                                title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        {{-- Details --}}
                        <div class="flex flex-grow flex-col p-5">
                            <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" wire:navigate>
                                <h3 class="line-clamp-1 text-lg font-bold text-[#1b1c1a] transition-colors hover:text-[#800020]"
                                    style="font-family: 'Noto Serif', serif;">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            <p class="mb-4 mt-1 text-xs font-bold uppercase tracking-widest text-gray-500">
                                {{ $product->fabric->name ?? 'Premium Fabric' }}
                            </p>

                            <div class="mt-auto flex flex-col justify-end">
                                <div
                                    class="{{ $product->original_price > $product->current_price ? '' : 'justify-center' }} flex items-baseline gap-2">
                                    <span class="text-xl font-bold leading-none text-[#800020]">
                                        Rs. {{ number_format($product->current_price) }}
                                    </span>
                                    @if ($product->original_price > $product->current_price)
                                        <span class="text-sm font-normal text-gray-400 line-through">
                                            Rs. {{ number_format($product->original_price) }}
                                        </span>
                                        @php
                                            $discountPercent = round(
                                                (($product->original_price - $product->current_price) /
                                                    $product->original_price) *
                                                    100,
                                            );
                                        @endphp
                                        <span
                                            class="text-xs font-bold tracking-wide text-green-600">({{ $discountPercent }}%
                                            OFF)</span>
                                    @endif
                                </div>
                            </div>

                            <button wire:click="moveToCart({{ $product->id }})"
                                class="mt-5 w-full rounded-sm border-2 border-[#800020] bg-white py-3 text-xs font-bold uppercase tracking-[0.15em] text-[#800020] transition-colors hover:bg-[#800020] hover:text-white">
                                Move to Bag
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <p class="mb-8 text-lg text-gray-500" style="font-family: 'Manrope', sans-serif;">Your wishlist is
                    currently empty.</p>
                <a href="{{ route('shop.index') }}" wire:navigate
                    class="inline-block rounded-sm bg-[#800020] px-8 py-3.5 text-xs font-bold uppercase tracking-[0.15em] text-white shadow-md transition-colors hover:bg-[#570013]">
                    Explore Alpha Digital
                </a>
            </div>
        @endif
    </div>
</main>
