<div class="bg-transparent font-sans">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="m-0 font-serif text-2xl font-bold text-secondary">Addresses</h1>
        @if (!$showForm)
            <button wire:click="toggleForm" class="btn-primary rounded-sm border-none px-6 py-2.5 text-xs">
                Add a new address
            </button>
        @endif
    </div>

    <x-toast-notification />

    @if ($showForm)
        <div class="mb-8 rounded-sm border border-outline_variant/50 bg-surface_lowest p-8 shadow-sm">
            <h2 class="mb-6 font-serif text-lg font-bold text-secondary">
                {{ $editingId ? 'Edit Address' : 'Add a new address' }}</h2>

            <form wire:submit.prevent="saveAddress" class="space-y-5">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <input type="text" wire:model="first_name" placeholder="First name*"
                            class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                        @error('first_name')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="last_name" placeholder="Last name*"
                            class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                        @error('last_name')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div>
                    <input type="text" wire:model="address_1" placeholder="Address 1*"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                    @error('address_1')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <input type="text" wire:model="address_2" placeholder="Address 2"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                </div>

                <div>
                    <input type="text" wire:model="city" placeholder="City*"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                    @error('city')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <input type="text" disabled value="India"
                            class="h-[48px] w-full cursor-not-allowed rounded-sm border border-outline_variant/70 bg-surface_low px-4 text-sm text-tertiary">
                    </div>
                    <div>
                        <input type="text" wire:model="province" placeholder="Province / State"
                            class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <input type="text" wire:model="postal_code" placeholder="Postal/ZIP code*"
                            class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                        @error('postal_code')
                            <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="phone" placeholder="Phone"
                            class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm placeholder-tertiary/70 outline-none transition-colors focus:border-primary">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input type="checkbox" wire:model="is_default"
                            class="h-4 w-4 rounded-sm border-outline_variant/70 text-primary focus:ring-primary">
                        <span class="text-sm text-on_surface/80">Set as default address</span>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="btn-primary rounded-sm border-none px-8 py-3 text-sm">
                        {{ $editingId ? 'Update Address' : 'Add Address' }}
                    </button>
                    <button type="button" wire:click="toggleForm"
                        class="cursor-pointer border-none bg-transparent px-6 py-3 text-sm font-bold uppercase tracking-widest text-tertiary transition-colors hover:text-primary">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if (!$showForm)
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @forelse($addresses as $address)
                <div
                    class="{{ $address->is_default ? 'border-primary' : 'border-outline_variant/50' }} group relative rounded-sm border bg-surface_lowest p-6 transition-shadow hover:shadow-md">
                    @if ($address->is_default)
                        <span
                            class="absolute right-4 top-4 rounded-sm bg-primary px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-white">Default</span>
                    @endif

                    <h3 class="mb-1 font-serif text-base font-bold text-secondary">{{ $address->first_name }}
                        {{ $address->last_name }}</h3>
                    <div class="mt-3 text-sm leading-relaxed text-tertiary">
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 flex-shrink-0 text-secondary"
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
                            <div class="mt-3 flex items-center gap-2 font-medium text-on_surface">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-secondary"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <p>{{ $address->phone }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <button wire:click="editAddress({{ $address->id }})"
                            class="btn-primary rounded-sm border-none px-6 py-2 text-xs">
                            Edit
                        </button>
                        <button type="button" wire:click="confirmDelete({{ $address->id }})"
                            class="btn-heritage cursor-pointer rounded-sm border-none px-6 py-2 text-xs">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full rounded-sm border border-outline_variant/50 bg-surface_lowest p-12 text-center">
                    <h3 class="mb-2 font-serif text-lg font-bold text-secondary">No addresses saved</h3>
                    <p class="m-0 text-tertiary">You currently don't have any saved delivery addresses.</p>
                </div>
            @endforelse
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div
            class="fixed inset-0 z-[1001] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
            <div
                class="mx-4 w-full max-w-sm rounded-sm border border-outline_variant/50 bg-surface_lowest p-8 shadow-xl">
                <h3 class="mb-4 text-center font-serif text-xl font-bold text-secondary">Delete Address</h3>
                <p class="mb-8 text-center font-sans text-sm text-tertiary">Are you sure you want to delete this
                    address? This action cannot be undone.</p>
                <div class="flex items-center justify-center gap-4">
                    <button type="button" wire:click="cancelDelete"
                        class="cursor-pointer border-none bg-transparent px-6 py-2.5 text-sm font-bold uppercase tracking-widest text-tertiary transition-colors hover:text-primary">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteAddress"
                        class="btn-heritage cursor-pointer rounded-sm border-none px-6 py-2.5 text-xs">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
