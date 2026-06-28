@push('scripts')
    <x-seo.schema type="product" :data="$product" />
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "{{ route('home') }}"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Shop",
    "item": "{{ route('shop.index') }}"
  },{
    "@type": "ListItem",
    "position": 3,
    "name": "{{ $product->name }}"
  }]
}
</script>
@endpush

<main class="product-main">

    {{-- Removed upper inline notification, using toast instead --}}

    <div class="product-container">

        <div class="product-gallery" x-data="{
            lightboxOpen: false,
            currentIndex: 0,
            isZoomed: false,
            zoomOriginX: '50%',
            zoomOriginY: '50%',
            images: {{ json_encode(is_array($product->images)? array_values(array_map(function ($img) {return asset('storage/' . $img);}, $product->images)): []) }},
            openLightbox(imgUrl) {
                if (this.images.length === 0) return;
                let idx = this.images.indexOf(imgUrl);
                this.currentIndex = idx !== -1 ? idx : 0;
                this.isZoomed = false;
                this.lightboxOpen = true;
            },
            toggleLightboxZoom(e) {
                if (!this.isZoomed) {
                    const rect = e.target.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    this.zoomOriginX = (x / rect.width * 100) + '%';
                    this.zoomOriginY = (y / rect.height * 100) + '%';
                    this.isZoomed = true;
                } else {
                    this.isZoomed = false;
                }
            },
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
                this.isZoomed = false;
            },
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                this.isZoomed = false;
            }
        }" @keydown.escape.window="lightboxOpen = false"
            @keydown.arrow-right.window="if(lightboxOpen) next()" @keydown.arrow-left.window="if(lightboxOpen) prev()">

            <!-- Lightbox Modal -->
            <template x-teleport="body">
                <div x-show="lightboxOpen" style="display: none;"
                    class="fixed inset-0 z-[2000] flex items-center justify-center overflow-hidden bg-black bg-opacity-95"
                    x-transition.opacity.duration.300ms>

                    <!-- Prominent Close Button -->
                    <button @click="lightboxOpen = false"
                        class="absolute right-4 top-4 z-50 rounded-full border border-gray-600 bg-black/60 p-2.5 text-white shadow-lg transition-colors hover:bg-black/80 hover:text-red-400 md:right-8 md:top-8 md:p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Previous Button -->
                    <button @click.stop="prev()"
                        class="absolute left-2 z-40 rounded-full bg-black/50 p-3 text-white transition-transform hover:scale-110 hover:bg-black/80 hover:text-gray-300 md:left-6"
                        x-show="images.length > 1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-10 md:w-10" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- The Image container ensures perfect center with equal top/bottom padding -->
                    <div class="flex h-full w-full items-center justify-center p-8 md:p-16"
                        @click.self="lightboxOpen = false">
                        <img :src="images[currentIndex]"
                            class="max-h-full max-w-full select-none object-contain shadow-2xl transition-transform duration-300"
                            :class="isZoomed ? 'cursor-zoom-out scale-[2.5]' : 'cursor-zoom-in scale-100'"
                            :style="isZoomed ? `transform-origin: ${zoomOriginX} ${zoomOriginY};` :
                                'transform-origin: center center;'"
                            @click.stop="toggleLightboxZoom($event)">
                    </div>

                    <!-- Next Button -->
                    <button @click.stop="next()"
                        class="absolute right-2 z-40 rounded-full bg-black/50 p-3 text-white transition-transform hover:scale-110 hover:bg-black/80 hover:text-gray-300 md:right-6"
                        x-show="images.length > 1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-10 md:w-10" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Progress Dots -->
                    <div class="absolute bottom-6 left-1/2 z-40 flex -translate-x-1/2 gap-3 rounded-full bg-black/60 px-5 py-3 backdrop-blur-sm"
                        x-show="images.length > 1">
                        <template x-for="(img, index) in images" :key="index">
                            <div @click.stop="currentIndex = index"
                                class="h-2.5 w-2.5 cursor-pointer rounded-full transition-all"
                                :class="currentIndex === index ? 'bg-white scale-125 shadow-[0_0_8px_rgba(255,255,255,0.8)]' :
                                    'bg-gray-500 hover:bg-gray-300'">
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <div class="thumbnails">
                @if (is_array($product->images))
                    @foreach ($product->images as $img)
                        <img src="{{ asset('storage/' . $img) }}"
                            class="thumb {{ $activeImage === $img ? 'active' : '' }}"
                            wire:click="changeImage('{{ $img }}')" style="cursor: pointer;">
                    @endforeach
                @endif
            </div>

            <div class="main-display group relative cursor-crosshair" x-data="{
                showZoom: false,
                bgPosX: '0%',
                bgPosY: '0%',
                updateZoom(e) {
                    // Only enable hover zoom on desktop
                    if (window.innerWidth < 1024) return;
            
                    const rect = this.$refs.mainImage.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
            
                    // Calculate percentage
                    const xPercent = Math.max(0, Math.min(100, (x / rect.width) * 100));
                    const yPercent = Math.max(0, Math.min(100, (y / rect.height) * 100));
            
                    this.bgPosX = xPercent + '%';
                    this.bgPosY = yPercent + '%';
                }
            }"
                @mouseenter="if(window.innerWidth >= 1024) showZoom = true" @mouseleave="showZoom = false"
                @mousemove="updateZoom($event)" @click="openLightbox('{{ asset('storage/' . $activeImage) }}')">

                <!-- Zoom Hint Overlay (Mobile) -->
                <div
                    class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center bg-black/10 opacity-0 transition-opacity group-hover:opacity-100 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white drop-shadow-md" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </div>

                @if ($activeImage)
                    <img x-ref="mainImage" src="{{ asset('storage/' . $activeImage) }}" id="expandedImg"
                        wire:target="setActiveImage" wire:loading.class="opacity-50"
                        class="main-image-styled transition-opacity duration-200">

                    <!-- Amazon-style Zoom Pane (Hidden on mobile) -->
                    <div x-show="showZoom" x-cloak x-transition.opacity.duration.200ms
                        class="pointer-events-none absolute left-full top-0 z-[150] ml-4 hidden h-[550px] w-[450px] border border-[#E5E0DA] bg-white shadow-2xl lg:block xl:h-[650px] xl:w-[550px]"
                        style="background-repeat: no-repeat; background-size: 250%;"
                        :style="`background-image: url('{{ asset('storage/' . $activeImage) }}'); background-position: ${bgPosX} ${bgPosY};`">
                    </div>
                @else
                    <img x-ref="mainImage"
                        src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80"
                        id="expandedImg" class="h-full w-full object-cover">
                @endif
            </div>
        </div>

        <div class="product-info mt-0 flex flex-1 flex-col pt-0">

            {{-- Title & Wishlist Toggle Container --}}
            <div class="flex items-start justify-between gap-4">
                <h1 class="m-0 p-0 text-3xl font-bold leading-none text-[#1b1c1a]"
                    style="font-family: 'Noto Serif', serif; line-height: 1.1;">
                    {{ $product->name }}
                </h1>

                <button wire:click="toggleWishlist({{ $product->id }})"
                    class="m-0 flex-shrink-0 rounded-full p-2 transition-colors hover:bg-[#F4F0EB]"
                    title="Add to Wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="{{ in_array($product->id, \App\Services\WishlistService::getWishlistProductIds()) ? 'fill-[#800020] text-[#800020]' : 'fill-none text-gray-400 hover:text-[#800020]' }} h-7 w-7 transition-colors duration-300"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            </div>

            {{-- UPDATED PRICE SECTION --}}
            <div class="mb-4 mt-4 flex flex-col items-start gap-1 md:flex-row md:items-baseline md:gap-3">
                <p class="price m-0 p-0 text-3xl font-bold leading-none text-[#800020] md:text-4xl">
                    Rs. {{ number_format($product->current_price, 2) }}
                </p>

                @if ($product->original_price > $product->current_price)
                    @php
                        $discount = round(
                            (($product->original_price - $product->current_price) / $product->original_price) * 100,
                        );
                    @endphp
                    <div class="flex items-center gap-2">
                        <p class="m-0 text-xl text-gray-400 line-through">
                            Rs. {{ number_format($product->original_price, 2) }}
                        </p>
                        <span class="rounded bg-green-100 px-2 py-0.5 text-sm font-bold text-green-700">
                            {{ $discount }}% OFF
                        </span>
                    </div>
                @endif
            </div>

            <p class="tax-tag mb-6 text-xs text-gray-500" style="font-family: 'Manrope', sans-serif;">
                Inclusive of all taxes.
            </p>

            <div class="purchase-controls">
                {{-- Stock Status --}}
                @if ($product->stock > 5)
                    <span class="stock-status mb-2 block text-sm font-bold italic text-green-600">
                        In Stock
                    </span>
                @elseif($product->stock > 0)
                    <span class="stock-status mb-2 block text-sm font-bold italic text-orange-500">
                        Only {{ $product->stock }} left
                    </span>
                @else
                    <span class="stock-status mb-2 block text-sm font-bold italic text-red-500">
                        (Out of stock)
                    </span>
                @endif

                <div class="quantity-box mb-3">
                    <label
                        style="font-size: 0.75rem; font-weight: 700; color: #706663; text-transform: uppercase; font-family: 'Manrope', sans-serif;">
                        Quantity
                    </label>
                    <div
                        class="qty-selector mt-2 flex h-10 w-fit items-center rounded-sm border border-[#E5E0DA] bg-white shadow-sm">
                        <button wire:click="decrementQty"
                            class="flex h-full w-10 items-center justify-center text-gray-600 transition hover:bg-[#F4F0EB]">-</button>
                        <span id="quantity" class="w-10 border-x border-[#E5E0DA] text-center text-sm font-bold"
                            style="font-family: 'Manrope', sans-serif;">
                            {{ $quantity }}
                        </span>
                        <button wire:click="incrementQty"
                            class="flex h-full w-10 items-center justify-center text-gray-600 transition hover:bg-[#F4F0EB]">+</button>
                    </div>
                </div>

                <div class="mb-10 flex flex-col gap-3">
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button wire:click="addToCart({{ $product->id }})"
                            class="flex-1 rounded-sm border-2 border-[#800020] bg-white px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#800020] shadow-sm transition-colors hover:bg-[#800020] hover:text-white disabled:opacity-50"
                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                            ADD TO CART
                        </button>
                        <button wire:click="buyNow({{ $product->id }})"
                            class="flex-1 rounded-sm bg-[#800020] px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-white shadow-md transition-colors hover:bg-[#5D4037] disabled:opacity-50"
                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                            BUY IT NOW
                        </button>
                    </div>

                    <a href="https://wa.me/{{ $settings->whatsapp_number ?? '919876543210' }}?text=Hello!%20I%20am%20interested%20in%20buying%20{{ $quantity }}x%20{{ urlencode($product->name) }}."
                        target="_blank"
                        class="flex w-full items-center justify-center gap-2 rounded-sm bg-[#25D366] px-6 py-4 text-xs font-bold uppercase tracking-widest text-white shadow-sm transition hover:bg-[#20ba5a]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
                        </svg>
                        ORDER ON WHATSAPP
                    </a>
                </div>
            </div>
        </div>

    </div>
    {{-- END OF .product-container --}}


    {{-- FULL WIDTH TABS SECTION (Moved outside the grid to stretch) --}}
    <div x-data="{ tab: 'description' }" class="mt-10 w-full border-t border-[#E5E0DA] pt-8">

        {{-- Tabs Navigation --}}
        <div class="mb-8 flex flex-wrap gap-8 border-b border-[#E5E0DA]">
            <button @click="tab = 'description'"
                :class="tab === 'description' ? 'border-[#800020] text-[#800020]' : 'border-transparent text-gray-500'"
                class="border-b-2 pb-3 text-[0.85rem] font-bold uppercase tracking-widest transition hover:text-[#800020]">
                Product Description
            </button>
            <button @click="tab = 'specs'"
                :class="tab === 'specs' ? 'border-[#800020] text-[#800020]' : 'border-transparent text-gray-500'"
                class="border-b-2 pb-3 text-[0.85rem] font-bold uppercase tracking-widest transition hover:text-[#800020]">
                Specification & Dimension
            </button>
            <button @click="tab = 'care'"
                :class="tab === 'care' ? 'border-[#800020] text-[#800020]' : 'border-transparent text-gray-500'"
                class="border-b-2 pb-3 text-[0.85rem] font-bold uppercase tracking-widest transition hover:text-[#800020]">
                Care & Maintenance
            </button>
        </div>

        {{-- Tabs Content --}}
        <div class="w-full">
            <div x-show="tab === 'description'" class="prose prose-sm max-w-none text-gray-600"
                style="font-family: 'Manrope', sans-serif;">
                {!! $product->description ?? 'No description available.' !!}
            </div>

            <div x-show="tab === 'specs'" x-cloak class="prose prose-sm max-w-none text-gray-600"
                style="font-family: 'Manrope', sans-serif; display: none;"
                :style="tab === 'specs' ? 'display: block;' : 'display: none;'">
                {!! $product->specifications ?? 'No specifications available.' !!}
            </div>

            <div x-show="tab === 'care'" x-cloak class="prose prose-sm max-w-none text-gray-600"
                style="font-family: 'Manrope', sans-serif; display: none;"
                :style="tab === 'care' ? 'display: block;' : 'display: none;'">
                {!! $product->care_instructions ?? 'Dry clean recommended.' !!}
            </div>
        </div>

    </div>

    {{-- UPDATED: BEAUTIFUL FULL WIDTH SIMILAR PRODUCTS SECTION --}}
    @if ($similarProducts && $similarProducts->count() > 0)
        <div class="mt-24 w-full border-t border-[#E5E0DA] pt-16">
            <div class="mb-12 text-center">
                <h2 class="font-serif text-3xl text-[#2A211F] md:text-4xl">You May Also Like</h2>
            </div>

            {{-- Slider Container with Alpine.js --}}
            <div class="group relative mx-auto max-w-7xl px-12 sm:px-16 lg:px-24" x-data="{
                scrollLeft() { $refs.slider.scrollBy({ left: -$refs.slider.clientWidth, behavior: 'smooth' }); },
                    scrollRight() { $refs.slider.scrollBy({ left: $refs.slider.clientWidth, behavior: 'smooth' }); }
            }">

                {{-- Prev Button --}}
                <button @click="scrollLeft()"
                    class="absolute left-0 top-[35%] z-10 hidden -translate-y-1/2 cursor-pointer items-center justify-center rounded-full border border-[#E5E0DA] bg-white/90 p-3 text-gray-500 shadow-md transition hover:bg-white hover:text-[#800020] md:flex lg:left-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Scrollable Area --}}
                <div x-ref="slider" class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-8 md:gap-6 lg:gap-8"
                    style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        [x-ref="slider"]::-webkit-scrollbar {
                            display: none;
                        }
                    </style>

                    {{-- Loop ALL similar products --}}
                    @foreach ($similarProducts as $simProduct)
                        @php
                            $mainImg =
                                is_array($simProduct->images) && count($simProduct->images) > 0
                                    ? asset('storage/' . $simProduct->images[0])
                                    : 'https://images.unsplash.com/photo-1610030469668-93510ec67d9e?auto=format&fit=crop&w=500';
                            $hoverImg =
                                is_array($simProduct->images) && count($simProduct->images) > 1
                                    ? asset('storage/' . $simProduct->images[1])
                                    : $mainImg;
                        @endphp

                        {{-- Sizing: 2 per row on mobile, 3 on tablet, 4 on large screens --}}
                        <div
                            class="product-card group relative w-[calc(50%-8px)] flex-none snap-start md:w-[calc(33.333%-16px)] lg:w-[calc(25%-24px)]">
                            <a href="{{ route('shop.product', $simProduct->slug ?? $simProduct->id) }}" wire:navigate
                                class="block h-full w-full no-underline">

                                {{-- Image Wrapper with Hover Effects --}}
                                <div
                                    class="img-wrapper relative mb-4 aspect-[3/4] overflow-hidden rounded-sm bg-[#F4F0EB]">
                                    {{-- Heart Icon (Appears on hover) --}}
                                    <div
                                        class="absolute right-3 top-3 z-10 rounded-full bg-white p-2 opacity-0 shadow-sm transition-opacity duration-300 group-hover:opacity-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                                            style="transition: all 0.3s; {{ in_array($simProduct->id, \App\Services\WishlistService::getWishlistProductIds()) ? 'fill: #800020; color: #800020;' : 'fill: none; color: #706663;' }}">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>

                                    <img src="{{ $mainImg }}" alt="{{ $simProduct->name }}"
                                        class="main-img h-full w-full object-cover">
                                    <img src="{{ $hoverImg }}" alt="{{ $simProduct->name }} (Hover)"
                                        class="hover-img absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                                </div>

                                {{-- Text Info --}}
                                <div class="text-center">
                                    <h3
                                        class="mb-2 line-clamp-2 min-h-[2.5rem] font-sans text-[0.85rem] font-semibold text-[#555]">
                                        {{ $simProduct->name }}
                                    </h3>
                                    <div class="mt-1 flex flex-col items-center gap-1">
                                        <div class="flex flex-wrap items-baseline justify-center gap-x-2 gap-y-1">
                                            <p class="m-0 font-sans text-base font-bold text-[#800020]">
                                                Rs. {{ number_format($simProduct->current_price, 2) }}
                                            </p>
                                            @if ($simProduct->original_price > $simProduct->current_price)
                                                <p class="m-0 text-sm font-normal text-gray-400 line-through">
                                                    Rs. {{ number_format($simProduct->original_price, 2) }}
                                                </p>
                                            @endif
                                        </div>
                                        @if ($simProduct->original_price > $simProduct->current_price)
                                            @php
                                                $discountPercent = round(
                                                    (($simProduct->original_price - $simProduct->current_price) /
                                                        $simProduct->original_price) *
                                                        100,
                                                );
                                            @endphp
                                            <span
                                                class="rounded bg-green-50 px-2 py-0.5 text-xs font-bold text-green-600">({{ $discountPercent }}%
                                                OFF)</span>
                                        @endif
                                    </div>
                                </div>

                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Next Button --}}
                <button @click="scrollRight()"
                    class="absolute right-0 top-[35%] z-10 hidden -translate-y-1/2 cursor-pointer items-center justify-center rounded-full border border-[#E5E0DA] bg-white/90 p-3 text-gray-500 shadow-md transition hover:bg-white hover:text-[#800020] md:flex lg:right-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- REVIEWS SECTION (NEW) --}}
    <div id="reviews" class="mt-24 w-full border-t border-[#E5E0DA] pt-16">
        <div class="mb-8 text-center">
            <h2 class="font-serif text-3xl text-[#2A211F] md:text-4xl">Customer Reviews</h2>
        </div>

        {{-- Reviews Filter UI --}}
        @if ($product->reviews && $product->reviews->count() > 0)
            <div class="mb-10 flex flex-wrap justify-center gap-3">
                <button wire:click="$set('ratingFilter', null)"
                    class="{{ is_null($ratingFilter) ? 'bg-[#800020] text-white border-[#800020]' : 'bg-white text-gray-600 border-[#E5E0DA] hover:bg-gray-50' }} rounded-full border px-5 py-2 text-sm font-bold transition-colors">
                    All
                </button>
                @for ($i = 5; $i >= 1; $i--)
                    <button wire:click="$set('ratingFilter', {{ $i }})"
                        class="{{ $ratingFilter === $i ? 'bg-[#800020] text-white border-[#800020]' : 'bg-white text-gray-600 border-[#E5E0DA] hover:bg-gray-50' }} flex items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-bold transition-colors">
                        {{ $i }} <svg xmlns="http://www.w3.org/2000/svg"
                            class="{{ $ratingFilter === $i ? 'text-yellow-400' : 'text-yellow-500' }} h-3.5 w-3.5 fill-current"
                            viewBox="0 0 24 24" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </button>
                @endfor
            </div>
        @endif

        <div class="mx-auto max-w-4xl" x-data="{
            isOpen: false,
            activeImage: '',
            review: null,
            init() {
                this.$watch('isOpen', value => {
                    if (value) {
                        this._scrollY = window.pageYOffset;
                        document.body.style.position = 'fixed';
                        document.body.style.top = '-' + this._scrollY + 'px';
                        document.body.style.width = '100%';
                        document.body.style.overflowY = 'scroll';
                    } else {
                        // Temporarily disable smooth scrolling to prevent visible jump
                        document.documentElement.style.setProperty('scroll-behavior', 'auto', 'important');
        
                        document.body.style.position = '';
                        document.body.style.top = '';
                        document.body.style.width = '';
                        document.body.style.overflowY = '';
        
                        window.scrollTo(0, this._scrollY);
        
                        // Restore original scroll behavior after layout paint
                        setTimeout(() => {
                            document.documentElement.style.removeProperty('scroll-behavior');
                        }, 10);
                    }
                });
            },
            openModal(reviewData, startImage) {
                this.review = reviewData;
                this.activeImage = startImage;
                this.isOpen = true;
            },
            prevImage() {
                let currentIndex = this.review.photos.indexOf(this.activeImage);
                if (currentIndex > 0) {
                    this.activeImage = this.review.photos[currentIndex - 1];
                } else {
                    this.activeImage = this.review.photos[this.review.photos.length - 1];
                }
            },
            nextImage() {
                let currentIndex = this.review.photos.indexOf(this.activeImage);
                if (currentIndex < this.review.photos.length - 1) {
                    this.activeImage = this.review.photos[currentIndex + 1];
                } else {
                    this.activeImage = this.review.photos[0];
                }
            }
        }">


            {{-- Existing Reviews --}}
            @if ($product->reviews && $product->reviews->count() > 0)
                @if ($reviews->count() > 0)
                    <div class="space-y-8">
                        @foreach ($reviews as $review)
                            <div class="border-b border-[#E5E0DA] pb-6">
                                <div class="mb-2 flex items-center justify-between">
                                    <span
                                        class="font-bold text-[#1b1c1a]">{{ $review->customer->name ?? 'Guest User' }}</span>
                                    <div class="flex gap-0.5 text-sm text-yellow-500">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="{{ $i <= $review->rating ? 'text-yellow-500 fill-current' : 'text-gray-300 fill-current' }} h-4 w-4"
                                                viewBox="0 0 24 24" stroke="none">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                                </polygon>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span
                                    class="mb-3 block text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                                @if ($review->comment)
                                    <p class="break-words text-sm leading-relaxed text-gray-600"
                                        style="font-family: 'Manrope', sans-serif;">
                                        {{ $review->comment }}
                                    </p>
                                @endif
                                @if (is_array($review->photos) && count($review->photos) > 0)
                                    @php
                                        $reviewData = [
                                            'name' => $review->customer->name ?? 'Guest User',
                                            'rating' => $review->rating,
                                            'comment' => $review->comment,
                                            'photos' => array_map(function ($p) {
                                                return asset('storage/' . $p);
                                            }, $review->photos),
                                        ];
                                    @endphp
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach ($review->photos as $photo)
                                            <a href="#" data-review="{{ json_encode($reviewData) }}"
                                                data-photo="{{ asset('storage/' . $photo) }}"
                                                @click.prevent="openModal(JSON.parse($el.dataset.review), $el.dataset.photo)"
                                                class="relative block h-16 w-16 flex-none cursor-pointer overflow-hidden rounded border border-[#E5E0DA] transition hover:opacity-80 md:h-20 md:w-20">
                                                <img src="{{ asset('storage/' . $photo) }}"
                                                    class="absolute inset-0 h-full w-full object-cover object-center">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                @if ($review->admin_reply)
                                    <div class="mt-4 rounded-sm border-l-4 border-[#800020] bg-[#F5F0EB] p-4">
                                        <span class="mb-1 block text-sm font-bold text-[#800020]">Response from Alpha
                                            Digital</span>
                                        <p class="text-sm leading-relaxed text-gray-700"
                                            style="font-family: 'Manrope', sans-serif;">
                                            {{ $review->admin_reply }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center">
                        <p class="mb-4 italic text-gray-500">No {{ $ratingFilter }}-star reviews found for this
                            product.</p>
                        <button wire:click="$set('ratingFilter', null)"
                            class="text-sm font-bold text-[#800020] hover:underline">View all reviews</button>
                    </div>
                @endif
            @else
                <p class="text-center italic text-gray-500">No reviews yet. Be the first to review this product!</p>
            @endif

            {{-- Review Image Lightbox Modal --}}
            <template x-teleport="body">
                <div x-show="isOpen"
                    class="fixed inset-0 z-[9999] flex items-center justify-center p-2 sm:p-4 md:p-10"
                    style="display: none;">
                    <div class="absolute inset-0 bg-black bg-opacity-80" @click="isOpen = false"></div>

                    <div
                        class="animate-fade-in-up relative z-10 flex h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-lg bg-white shadow-2xl md:h-[85vh] md:flex-row">
                        <button @click="isOpen = false"
                            class="absolute right-2 top-2 z-20 cursor-pointer rounded-full border border-gray-200 bg-white p-1.5 text-gray-400 shadow-md transition hover:bg-gray-100 hover:text-black md:right-4 md:top-4 md:p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-5 md:w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Left: Large Image Area -->
                        <div
                            class="group relative flex h-[45%] w-full shrink-0 items-center justify-center bg-[#F5F0EB] p-2 md:h-full md:w-[60%] md:p-4">
                            <!-- Prev Arrow -->
                            <button x-show="review && review.photos && review.photos.length > 1" @click="prevImage()"
                                class="absolute left-2 z-20 cursor-pointer rounded-full border border-[#E5E0DA] bg-white p-2 text-[#800020] opacity-100 shadow-md transition hover:bg-[#800020] hover:text-white focus:opacity-100 md:left-4 md:p-3 md:opacity-0 md:group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-6 md:w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <img :src="activeImage"
                                class="max-h-full max-w-full object-contain mix-blend-multiply drop-shadow-lg">

                            <!-- Next Arrow -->
                            <button x-show="review && review.photos && review.photos.length > 1" @click="nextImage()"
                                class="absolute right-2 z-20 cursor-pointer rounded-full border border-[#E5E0DA] bg-white p-2 text-[#800020] opacity-100 shadow-md transition hover:bg-[#800020] hover:text-white focus:opacity-100 md:right-4 md:p-3 md:opacity-0 md:group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-6 md:w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Right: Review Details -->
                        <div
                            class="flex h-[55%] w-full grow flex-col overflow-y-auto bg-white p-4 sm:p-6 md:h-full md:w-[40%] md:p-8">
                            <h3
                                class="mb-4 border-b border-gray-100 pb-2 pr-8 text-base font-bold text-gray-900 md:mb-6 md:pb-3 md:pr-0 md:text-lg">
                                Customer photos and review</h3>

                            <div class="mb-3 flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-sm font-bold text-gray-600 md:h-10 md:w-10 md:text-base">
                                    <span x-text="review.name ? review.name.charAt(0).toUpperCase() : 'G'"></span>
                                </div>
                                <span class="text-sm font-bold text-[#1b1c1a] md:text-base"
                                    x-text="review.name || 'Guest User'"></span>
                            </div>

                            <div class="mb-4 flex items-center gap-2">
                                <div class="flex gap-0.5">
                                    <template x-for="i in 5">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-3 w-3 fill-current md:h-4 md:w-4"
                                            :class="i <= review.rating ? 'text-[#FF9900]' : 'text-gray-300'"
                                            viewBox="0 0 24 24" stroke="none">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-[12px] font-bold text-[#C45500] md:text-[13px]">Verified
                                    Purchase</span>
                            </div>

                            <p class="mb-6 whitespace-pre-wrap break-words text-[13px] leading-relaxed text-gray-700 md:text-[14px]"
                                x-text="review.comment" style="font-family: 'Manrope', sans-serif;"></p>

                            <!-- Thumbnails Gallery -->
                            <div class="mt-auto pt-4 md:pt-6">
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="photo in review.photos">
                                        <button @click="activeImage = photo"
                                            class="relative h-12 w-12 flex-none cursor-pointer overflow-hidden rounded border-2 transition focus:outline-none md:h-16 md:w-16"
                                            :class="activeImage === photo ? 'border-[#e77600] shadow-sm' :
                                                'border-transparent opacity-70 hover:opacity-100 hover:border-gray-300'">
                                            <img :src="photo"
                                                class="absolute inset-0 h-full w-full object-cover object-center">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</main>
