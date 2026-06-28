<div class="mt-20 flex min-h-screen items-center justify-center bg-[#fbf9f5] px-4 py-10 font-sans">
    <div class="w-full max-w-2xl overflow-hidden rounded border border-[#E5E0DA] bg-white shadow-sm">

        {{-- Top Header --}}
        <div class="border-b border-[#570013] bg-[#800020] py-6 text-center">
            <h1 class="m-0 font-serif text-2xl font-bold uppercase tracking-[0.2em] text-white">Order Placed</h1>
        </div>

        {{-- Success Message & ID --}}
        <div class="flex items-center gap-4 border-b border-[#E5E0DA] bg-[#FAFAFA] p-6">
            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-600 shadow-sm">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h2 class="m-0 text-lg font-bold text-[#1b1c1a]">Thank you for shopping with us!</h2>
                <p class="m-0 mt-0.5 text-sm font-medium text-gray-500">ID: {{ $order->order_number }}</p>
            </div>
        </div>

        {{-- Delivery Estimate & Track --}}
        <div class="flex items-center justify-between border-b border-[#E5E0DA] bg-white p-4 px-6">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-[#6366f1]">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
                <p class="m-0 text-sm font-bold text-[#1b1c1a]">Estimated Delivery by
                    {{ now()->addDays(7)->format('l, jS M') }}</p>
            </div>
            <a href="{{ route('profile.orders.details', $order->id) }}"
                class="flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-[#800020] transition hover:text-[#5D4037]"
                style="text-decoration: none;">
                Track Order <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>

        {{-- Ordered Items --}}
        <div class="bg-white">
            @php
                $subtotal = 0;
            @endphp
            @if ($order->items && $order->items->count() > 0)
                @foreach ($order->items as $item)
                    @php
                        $subtotal += $item->price * $item->quantity;
                        $product = $item->product;
                        $img =
                            $product && is_array($product->images) && count($product->images) > 0
                                ? asset('storage/' . $product->images[0])
                                : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                    @endphp
                    <div class="flex gap-4 border-b border-[#E5E0DA] p-6">
                        <div
                            class="h-24 w-20 flex-shrink-0 overflow-hidden rounded border border-gray-100 bg-[#F4F0EB]">
                            <img src="{{ $img }}" alt="{{ $product ? $product->name : 'Product' }}"
                                class="h-full w-full object-cover object-top">
                        </div>
                        <div class="flex-1">
                            <h3 class="mb-1 line-clamp-2 text-sm font-bold leading-snug text-[#1b1c1a]">
                                {{ $product ? $product->name : 'Unknown Product' }}</h3>
                            <p class="mb-2 text-sm font-bold text-[#1b1c1a]">Rs. {{ number_format($item->price) }}</p>
                            <div class="flex items-center gap-4 text-xs font-medium text-gray-500">
                                <span>Size: Free Size</span>
                                <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                                <span>Qty: {{ $item->quantity }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @php
                $shipping = $subtotal > 10000 || $subtotal == 0 ? 0 : 150;
            @endphp
            <div
                class="flex items-center justify-between border-b border-[#E5E0DA] bg-[#FAFAFA] p-4 px-6 text-xs font-medium text-gray-600">
                <span>Sold by: ALPHA DIGITAL</span>
                @if ($shipping == 0)
                    <span class="font-bold uppercase tracking-wide text-green-600">Free Delivery</span>
                @else
                    <span class="font-bold">Delivery Charge: Rs. {{ number_format($shipping) }}</span>
                @endif
            </div>
        </div>

        {{-- Download Invoice --}}
        <div class="flex justify-end border-b border-[#E5E0DA] bg-white p-4 px-6">
            <a href="{{ route('profile.orders.invoice', $order->id) }}"
                class="flex cursor-pointer items-center gap-2 border-none bg-transparent text-xs font-bold uppercase tracking-wider text-[#800020] transition hover:text-[#5D4037]"
                style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download Invoice
            </a>
        </div>

        {{-- Delivery Address Details --}}
        <div class="border-b border-[#E5E0DA] bg-white p-6">
            <div class="mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-[#800020]">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <h3 class="m-0 text-sm font-bold uppercase tracking-wide text-gray-800">Delivery Address</h3>
            </div>

            @if ($address)
                <p class="mb-2 text-sm font-bold text-[#1b1c1a]">{{ $address->first_name }} {{ $address->last_name }}
                    <span class="ml-2 font-normal text-gray-500">{{ $address->phone }}</span></p>
                <div class="m-0 text-sm leading-relaxed text-gray-600">

                    <p class="m-0">{{ $address->address_1 }}</p>
                    @if ($address->address_2)
                        <p class="m-0">{{ $address->address_2 }}</p>
                    @endif
                    <p class="m-0">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    <p class="m-0">{{ $address->country }}</p>
                </div>
            @endif
        </div>

        {{-- Action Button --}}
        <a href="{{ route('shop.index') }}" wire:navigate
            class="block w-full cursor-pointer bg-[#800020] py-4 text-center text-sm font-bold uppercase tracking-[0.15em] text-white outline-none transition-colors hover:bg-[#5D4037]">
            Continue Shopping
        </a>
    </div>
</div>
