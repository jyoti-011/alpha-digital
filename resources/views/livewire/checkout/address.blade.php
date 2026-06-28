<div class="mx-auto min-h-screen max-w-4xl px-5 pb-12 pt-[80px] font-sans">

    <x-checkout-progress step="2" />

    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="text-left">
            <h1 class="mb-2 text-3xl font-bold leading-none tracking-tight text-[#2A211F]"
                style="font-family: 'Noto Serif', serif;">Delivery Details</h1>
            <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-500"
                style="font-family: 'Manrope', sans-serif;">Where should we send your collection?</p>
        </div>

        @if (!$showForm)
            <button wire:click="toggleForm"
                class="cursor-pointer border-none bg-transparent text-left text-sm font-bold tracking-wider text-[#800020] underline transition hover:text-[#5D4037] sm:mb-1 sm:text-right">Add
                a new address</button>
        @endif
    </div>

    <div class="rounded border border-gray-200 bg-white p-6 shadow-sm">
        @if ($showForm)
            <h2 class="mb-6 text-lg font-bold">Add a new address</h2>
            <form wire:submit.prevent="saveAddress" class="space-y-5">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <input type="text" wire:model="first_name" placeholder="First name*"
                            class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                        @error('first_name')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="last_name" placeholder="Last name*"
                            class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                        @error('last_name')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div>
                    <input type="text" wire:model="address_1" placeholder="Address 1*"
                        class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                    @error('address_1')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <input type="text" wire:model="address_2" placeholder="Address 2"
                        class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                </div>

                <div>
                    <input type="text" wire:model="city" placeholder="City*"
                        class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                    @error('city')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <input type="text" disabled value="India"
                            class="h-[48px] w-full cursor-not-allowed rounded border border-gray-300 bg-gray-50 px-4 text-sm text-gray-500">
                    </div>
                    <div>
                        <input type="text" wire:model="province" placeholder="Province / State"
                            class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <input type="text" wire:model="postal_code" placeholder="Postal/ZIP code*"
                            class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                        @error('postal_code')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="phone" placeholder="Phone"
                            class="h-[48px] w-full rounded border border-gray-300 px-4 text-sm outline-none transition-colors focus:border-[#800020]">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input type="checkbox" wire:model="is_default"
                            class="h-4 w-4 text-[#800020] focus:ring-[#800020]">
                        <span class="text-sm text-gray-700">Set as default address</span>
                    </label>
                </div>

                <div class="mt-6 flex items-center gap-4 border-t border-gray-200 pt-4">
                    <button type="submit"
                        class="cursor-pointer rounded border-none bg-[#800020] px-8 py-3 text-sm font-bold tracking-widest text-white transition-colors hover:bg-[#5D4037]">
                        ADD ADDRESS
                    </button>
                    <button type="button" wire:click="toggleForm"
                        class="cursor-pointer border-none bg-transparent px-6 py-3 text-sm font-bold uppercase tracking-widest text-gray-500 transition-colors hover:text-[#800020]">
                        CANCEL
                    </button>
                </div>
            </form>
        @else
            <h2 class="mb-4 text-lg font-bold">Delivery to {{ auth('customer')->user()->name }}</h2>

            @if (count($addresses) > 0)
                <div x-data="{ selectedId: @entangle('selectedAddressId') }" class="space-y-4">
                    @foreach ($addresses as $address)
                        <label
                            :class="selectedId == {{ $address->id }} ? 'border-[#800020] bg-[#F4F0EB]' : 'border-gray-200'"
                            class="flex cursor-pointer items-start rounded border p-4 transition-colors">
                            <input type="radio" x-model="selectedId" value="{{ $address->id }}"
                                class="mr-3 mt-1 accent-[#800020]">
                            <div>
                                <p class="font-bold text-[#2A211F]">{{ $address->first_name }} {{ $address->last_name }}
                                </p>
                                <div class="mt-1 text-sm leading-relaxed text-gray-600">

                                    <p>{{ $address->address_1 }}</p>
                                    @if ($address->address_2)
                                        <p>{{ $address->address_2 }}</p>
                                    @endif
                                    <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                    <p>{{ $address->country }}</p>
                                    @if ($address->phone)
                                        <p class="mt-1 font-medium">Phone: {{ $address->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('selectedAddressId')
                    <span class="mt-2 block text-xs font-bold text-red-500">{{ $message }}</span>
                @enderror
            @else
                <p class="mb-4 text-gray-500">You don't have any addresses saved yet.</p>
                <button wire:click="toggleForm"
                    class="cursor-pointer border-none bg-transparent text-sm font-bold text-[#800020] underline">Add a
                    new address</button>
            @endif

            <div class="mt-8 flex justify-end border-t border-gray-200 pt-6">
                <button wire:click="continueToSummary"
                    class="cursor-pointer rounded border-none bg-[#800020] px-8 py-3 text-sm font-bold tracking-widest text-white transition-colors hover:bg-[#5D4037]">
                    CONTINUE
                </button>
            </div>
        @endif
    </div>
</div>
