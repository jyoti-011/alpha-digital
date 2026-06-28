<main class="arrival-container" style="padding-top: 1.5rem;">

    <div class="arrival-header" style="margin-bottom: 2rem;">
        <p class="subtitle">DISCOVER THE LATEST ARRIVALS</p>
        <h1 style="margin-top: 0.5rem;">Just Introduced</h1>
        <p class="description">Explore the newest additions to the Alpha Digital collection. Our latest sarees bring
            together timeless elegance, exceptional quality, and the perfect drape for every occasion.</p>
    </div>

    <x-toast-notification />



    <div
        style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 1px solid #eaeaea; padding-bottom: 1rem; gap: 1rem;">

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">

            <div x-data="{ open: false, selected: @entangle('selectedFabric').live }" class="relative w-40 font-sans sm:w-48">
                <button @click="open = !open" @click.away="open = false" type="button"
                    class="flex w-full items-center justify-between rounded-md border border-[#e5e7eb] bg-white py-[0.6rem] pl-4 pr-10 text-left text-[0.85rem] text-[#555] shadow-sm transition-all duration-300 hover:border-[#d1d5db] focus:border-[#800020] focus:outline-none focus:ring-1 focus:ring-[#800020]">
                    <span class="truncate">
                        <template x-if="selected == '' || selected == null"><span>All Fabrics</span></template>
                        @foreach ($fabrics as $fabric)
                            <template
                                x-if="selected == '{{ $fabric->id }}'"><span>{{ $fabric->name }}</span></template>
                        @endforeach
                    </span>
                    <svg class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="scrollbar-hide absolute z-[100] mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-100 bg-white py-1 shadow-lg">
                    <div @click="selected = ''; open = false"
                        class="cursor-pointer select-none px-4 py-2 text-[0.85rem] text-gray-700 transition-colors hover:bg-[#fff0f2] hover:text-[#800020]"
                        :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '' || selected == null }">
                        All Fabrics
                    </div>
                    @foreach ($fabrics as $fabric)
                        <div @click="selected = '{{ $fabric->id }}'; open = false"
                            class="cursor-pointer select-none px-4 py-2 text-[0.85rem] text-gray-700 transition-colors hover:bg-[#fff0f2] hover:text-[#800020]"
                            :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '{{ $fabric->id }}' }">
                            {{ $fabric->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false, selected: @entangle('selectedColor').live }" class="relative w-40 font-sans sm:w-48">
                <button @click="open = !open" @click.away="open = false" type="button"
                    class="flex w-full items-center justify-between rounded-md border border-[#e5e7eb] bg-white py-[0.6rem] pl-4 pr-10 text-left text-[0.85rem] text-[#555] shadow-sm transition-all duration-300 hover:border-[#d1d5db] focus:border-[#800020] focus:outline-none focus:ring-1 focus:ring-[#800020]">
                    <span class="truncate">
                        <template x-if="selected == '' || selected == null"><span>All Colors</span></template>
                        @foreach ($colors as $color)
                            <template
                                x-if="selected == '{{ $color->id }}'"><span>{{ $color->name }}</span></template>
                        @endforeach
                    </span>
                    <svg class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="scrollbar-hide absolute z-[100] mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-100 bg-white py-1 shadow-lg">
                    <div @click="selected = ''; open = false"
                        class="cursor-pointer select-none px-4 py-2 text-[0.85rem] text-gray-700 transition-colors hover:bg-[#fff0f2] hover:text-[#800020]"
                        :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '' || selected == null }">
                        All Colors
                    </div>
                    @foreach ($colors as $color)
                        <div @click="selected = '{{ $color->id }}'; open = false"
                            class="cursor-pointer select-none px-4 py-2 text-[0.85rem] text-gray-700 transition-colors hover:bg-[#fff0f2] hover:text-[#800020]"
                            :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '{{ $color->id }}' }">
                            {{ $color->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false, selected: @entangle('selectedPattern').live }" class="relative w-40 font-sans sm:w-48">
                <button @click="open = !open" @click.away="open = false" type="button"
                    class="flex w-full items-center justify-between rounded-md border border-[#e5e7eb] bg-white py-[0.6rem] pl-4 pr-10 text-left text-[0.85rem] text-[#555] shadow-sm transition-all duration-300 hover:border-[#d1d5db] focus:border-[#800020] focus:outline-none focus:ring-1 focus:ring-[#800020]">
                    <span class="truncate">
                        <template x-if="selected == '' || selected == null"><span>All Patterns</span></template>
                        @foreach ($patterns as $pattern)
                            <template
                                x-if="selected == '{{ $pattern->id }}'"><span>{{ $pattern->name }}</span></template>
                        @endforeach
                    </span>
                    <svg class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="scrollbar-hide absolute z-[100] mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-100 bg-white py-1 shadow-lg">
                    <div @click="selected = ''; open = false"
                        class="cursor-pointer select-none px-4 py-2 text-[0.85rem] text-gray-700 transition-colors hover:bg-[#fff0f2] hover:text-[#800020]"
                        :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '' || selected == null }">
                        All Patterns
                    </div>
                    @foreach ($patterns as $pattern)
                        <div @click="selected = '{{ $pattern->id }}'; open = false"
                            class="cursor-pointer select-none px-4 py-2 text-[0.85rem] text-gray-700 transition-colors hover:bg-[#fff0f2] hover:text-[#800020]"
                            :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '{{ $pattern->id }}' }">
                            {{ $pattern->name }}
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div>
            <select wire:model.live="sort" class="premium-select min-w-[150px]">
                <option value="latest">Sort by: Latest</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
            </select>
        </div>
    </div>

    <div class="arrival-grid grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-6 lg:grid-cols-4">
        @forelse($products as $product)
            <x-product-card :product="$product" :showWishlist="true" :isNewArrival="true" />
        @empty
            <p style="grid-column: 1 / -1; text-align: center; padding: 4rem 0; color: #666; font-style: italic;">
                No new arrivals match these filters.
            </p>
        @endforelse
    </div>

    <div class="load-more mb-20 mt-16 flex justify-center">
        @if ($products->hasMorePages())
            <button wire:click="loadMore" wire:loading.attr="disabled"
                class="min-w-[250px] border border-[#800020] bg-white px-10 py-4 text-[0.8rem] font-bold uppercase tracking-[2px] text-[#800020] transition-colors duration-300 hover:bg-[#800020] hover:text-white">
                <span wire:loading.remove wire:target="loadMore">Discover More</span>
                <span wire:loading wire:target="loadMore">Loading...</span>
            </button>
        @else
            <button disabled
                class="min-w-[250px] cursor-not-allowed border border-[#e5e5e5] bg-[#f9f9f9] px-10 py-4 text-[0.8rem] font-bold uppercase tracking-[2px] text-[#999]">
                You've Viewed All
            </button>
        @endif
    </div>
</main>
