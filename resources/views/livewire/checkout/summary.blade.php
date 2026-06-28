<div class="mx-auto min-h-screen max-w-5xl px-5 pb-12 pt-[80px] font-sans">

    <x-checkout-progress step="3" />

    <div class="mb-4 text-left">
        <h1 class="mb-2 text-3xl font-bold leading-none tracking-tight text-[#2A211F]"
            style="font-family: 'Noto Serif', serif;">Final Review</h1>
        <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-500"
            style="font-family: 'Manrope', sans-serif;">Secure your collection</p>
    </div>

    <div class="grid grid-cols-1 gap-12 md:grid-cols-12">

        {{-- Left side details --}}
        <div class="space-y-8 md:col-span-7">
            <div class="rounded border border-[#E5E0DA] bg-white p-8 shadow-sm">
                <div class="mb-6 flex items-center justify-between border-b border-[#E5E0DA] pb-4">
                    <h3 class="text-xl font-bold text-[#2A211F]" style="font-family: 'Noto Serif', serif;">Shipping To
                    </h3>
                    <a href="{{ route('checkout.address') }}"
                        class="text-xs font-bold uppercase tracking-widest text-[#800020] transition-colors hover:text-[#5D4037]"
                        style="text-decoration: none;">Edit</a>
                </div>
                <p class="mb-2 text-lg font-bold text-[#1b1c1a]">{{ $address->first_name }} {{ $address->last_name }}
                </p>
                <div class="text-sm leading-relaxed text-gray-600">
                    <div class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 flex-shrink-0 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>

                            <p>{{ $address->address_1 }}</p>
                            @if ($address->address_2)
                                <p>{{ $address->address_2 }}</p>
                            @endif
                            <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                            <p>{{ $address->country }}</p>
                        </div>
                    </div>
                    @if ($address->phone)
                        <div class="mt-3 flex items-center gap-2 font-medium text-[#1b1c1a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <p>{{ $address->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded border border-[#E5E0DA] bg-white p-8 shadow-sm">
                <h3 class="mb-6 border-b border-[#E5E0DA] pb-4 text-xl font-bold text-[#2A211F]"
                    style="font-family: 'Noto Serif', serif;">Your Selection</h3>
                <div class="space-y-6">
                    @foreach ($cartDetails['items'] as $item)
                        <div class="flex items-start gap-6 border-b border-[#E5E0DA] pb-6 last:border-0 last:pb-0">
                            <div class="h-28 w-20 flex-shrink-0 bg-[#F4F0EB]">
                                <img src="{{ asset('storage/' . ($item['product']->images[0] ?? '')) }}"
                                    class="h-full w-full object-cover object-top">
                            </div>
                            <div class="flex-1">
                                <p class="mb-1 font-bold text-[#1b1c1a]" style="font-family: 'Noto Serif', serif;">
                                    {{ $item['product']->name }}</p>
                                <p class="mb-3 text-xs uppercase tracking-widest text-gray-500">Qty:
                                    {{ $item['qty'] }}</p>
                                <p class="font-bold text-[#800020]">Rs.
                                    {{ number_format($item['product']->current_price * $item['qty']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Special Instructions Input --}}
            <div class="rounded border border-[#E5E0DA] bg-white p-8 shadow-sm">
                <h3 class="mb-6 border-b border-[#E5E0DA] pb-4 text-xl font-bold text-[#2A211F]"
                    style="font-family: 'Noto Serif', serif;">Special Instructions <span
                        class="font-sans text-sm font-normal text-gray-400">(Optional)</span></h3>
                <textarea wire:model="customer_note" rows="3"
                    placeholder="Examples:&#10;• Please gift wrap this saree.&#10;• Do not include invoice in the parcel.&#10;• Call before delivery."
                    class="w-full rounded border border-gray-300 px-4 py-3 text-sm transition-colors focus:border-[#800020] focus:outline-none focus:ring-[#800020]"
                    style="font-family: 'Manrope', sans-serif; resize: vertical;"></textarea>
            </div>
        </div>

        {{-- Right side payment --}}
        <div class="md:col-span-5">
            <div class="rounded border border-[#E5E0DA] bg-white p-8 shadow-sm">
                <h3 class="mb-6 border-b border-[#E5E0DA] pb-4 text-2xl font-bold text-[#001f3f]"
                    style="font-family: 'Noto Serif', serif;">Order Summary</h3>

                <div class="mb-6 space-y-4">
                    <div class="flex justify-between text-[15px] text-gray-500">
                        <span>Price ({{ $cartDetails['total_items'] }}
                            {{ Str::plural('item', $cartDetails['total_items']) }})</span>
                        <span>₹{{ number_format($cartDetails['original_price_total']) }}</span>
                    </div>
                    <div class="flex justify-between text-[15px] text-gray-500">
                        <span>Discount</span>
                        <span class="text-[#008f5d]">- ₹{{ number_format($cartDetails['discount']) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[15px] text-gray-500">
                        <span class="flex items-center gap-1">
                            Shipping
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-gray-400">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </span>
                        <span>{{ $cartDetails['shipping'] == 0 ? 'Free' : '₹' . number_format($cartDetails['shipping']) }}</span>
                    </div>
                </div>

                <div class="my-4 border-t border-dashed border-gray-300"></div>

                <div class="flex justify-between py-2 text-lg font-bold text-[#1b1c1a]"
                    style="font-family: 'Noto Serif', serif;">
                    <span>Total Amount</span>
                    <span>₹{{ number_format($cartDetails['total']) }}</span>
                </div>

                <div
                    class="mb-6 mt-4 flex items-start gap-2 rounded border border-yellow-200 bg-yellow-50 p-3 text-xs text-yellow-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="m-0 font-medium leading-snug" style="font-family: 'Manrope', sans-serif;">
                        <strong>Disclaimer:</strong> Payment feature is currently in test mode. No real transactions
                        will occur.
                    </p>
                </div>

                <div class="mt-2">
                    <button wire:click="payWithRazorpay" wire:loading.attr="disabled"
                        class="flex w-full items-center justify-center gap-3 bg-black py-4 text-xs font-bold uppercase tracking-[0.2em] text-white transition-colors hover:bg-gray-800 disabled:cursor-wait disabled:opacity-75">
                        <span wire:loading.remove wire:target="payWithRazorpay" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                </rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Pay Securely
                        </span>
                        <span wire:loading wire:target="payWithRazorpay" class="flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Connecting to Razorpay...
                        </span>
                    </button>

                    <div class="mt-6 flex flex-col items-center justify-center gap-2 text-gray-400">
                        <div class="flex gap-2">
                            {{-- Dummy payment icons --}}
                            <svg viewBox="0 0 38 24" class="h-auto w-8" xmlns="http://www.w3.org/2000/svg"
                                role="img" aria-labelledby="pi-visa">
                                <path opacity=".07"
                                    d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                </path>
                                <path fill="#fff"
                                    d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                </path>
                                <path
                                    d="M28.3 10.1l-1.4 8.7h-2.7l1.4-8.7h2.7zM24.7 10.1l-2.6 5.8-2-5.8h-2.8l3.4 8.7h2.8l4-8.7h-2.8zM11.6 10.1L9 16.5l-.3-1.4c-.6-2.1-1.9-3.7-4-4.5l2.6 8.2h3L14.7 10.1h-3.1zM18.8 15.6c-.3 2.1-3 2.2-3.1.8-.1-1.6 2.7-1.9 2.7-3-.1-.1-1.1-.1-1.6.4l-.5-1.5c.8-.5 2.1-.8 3.3-.8 2.2 0 3 1.3 3 2.8 0 3-3.6 3.2-3.6 4.3 0 .7.8.8 1.5.5l.5 1.5c-1 0-2.4.3-2.2-5zM11.5 10.1L9.6 11c-.5-.7-1.2-1-2.4-1-1.9 0-3.3 1-3.3 2.5 0 1.3 1.2 2 2.7 2 .8 0 1.4-.2 1.8-.4l-.2-1h-1.6V12h3.5v6.8H11.5v-8.7z"
                                    fill="#1434CB"></path>
                            </svg>
                            <svg viewBox="0 0 38 24" class="h-auto w-8" xmlns="http://www.w3.org/2000/svg"
                                role="img" aria-labelledby="pi-master">
                                <path opacity=".07"
                                    d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z">
                                </path>
                                <path fill="#fff"
                                    d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32">
                                </path>
                                <circle fill="#EB001B" cx="15" cy="12" r="7"></circle>
                                <circle fill="#F79E1B" cx="23" cy="12" r="7"></circle>
                                <path fill="#FF5F00"
                                    d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold uppercase tracking-widest">Encrypted 256-bit Secure Checkout
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('razorpay-checkout', (event) => {
                let data = event[0];
                var options = {
                    "key": data.key,
                    "amount": data.amount,
                    "currency": "INR",
                    "name": data.name,
                    "order_id": data.order_id,
                    "handler": function(response) {
                        @this.call('verifyPayment', response.razorpay_payment_id, response
                            .razorpay_order_id, response.razorpay_signature);
                    },
                    "prefill": {
                        "name": data.prefill.name,
                        "email": data.prefill.email,
                        "contact": data.prefill.contact
                    },
                    "theme": {
                        "color": "#800020"
                    },
                    "modal": {
                        "ondismiss": function() {
                            // Triggers failure logic instead of emptying cart
                            @this.call('paymentFailed');
                        }
                    }
                };
                var rzp = new Razorpay(options);
                rzp.on('payment.failed', function(response) {
                    @this.call('paymentFailed'); // Triggers failure logic if payment fails
                });
                rzp.open();
            });
        });
    </script>

    {{-- Payment Failure Modal --}}
    @if ($showFailureModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 transition-opacity">
            <div class="relative w-full max-w-md overflow-hidden rounded-lg bg-white shadow-xl">

                {{-- Close Button --}}
                <button wire:click="closeFailureModal"
                    class="absolute right-4 top-4 text-gray-400 transition-colors hover:text-gray-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="p-8 pb-6">
                    {{-- Error Icon --}}
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                        <span class="font-serif text-2xl font-bold leading-none text-red-500">!</span>
                    </div>

                    {{-- Title --}}
                    <h2 class="mb-3 font-sans text-xl font-bold text-[#2A211F]">
                        Transaction of ₹{{ number_format($cartDetails['total']) }} Failed
                    </h2>

                    {{-- Payment Mode --}}
                    <p class="mb-4 font-sans text-sm text-gray-600">
                        Payment Mode: Online (Razorpay)
                    </p>

                    {{-- Info Text --}}
                    <p class="mb-2 font-sans text-sm leading-relaxed text-gray-600">
                        Payment Failed - In case of any amount deduction, the refund will be initiated within 48 hours.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4 border-t border-gray-200 p-6">
                    <a href="{{ route('shop.index') }}" wire:navigate
                        class="flex flex-1 items-center justify-center rounded border border-gray-300 bg-white py-3 text-center text-sm font-semibold text-[#2A211F] transition-colors hover:bg-gray-50"
                        style="text-decoration: none;">
                        Continue Shopping
                    </a>
                    <button wire:click="payWithRazorpay"
                        class="flex-1 rounded border border-[#800020] bg-[#800020] py-3 text-sm font-semibold text-white transition-colors hover:border-[#5D4037] hover:bg-[#5D4037]">
                        Retry
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
