<x-filament-panels::page>
    <div class="fixed inset-0 z-0 bg-[#fcfcfc]"></div>

    <div class="relative z-10 -mt-10">
        @if ($record)
            {{-- Top Header Section --}}
            <div class="mb-12 flex flex-col justify-between md:flex-row md:items-center">
                <div>
                    <h1 class="text-[42px] font-bold leading-none tracking-tight text-[#1a1a1a]">Settings Management</h1>
                    <p class="mt-3 text-lg font-medium text-gray-400">Manage your store resources and data seamlessly.
                    </p>
                </div>
                <div class="mt-6 md:mt-0">
                    <button wire:click="save"
                        class="flex items-center gap-3 rounded-xl bg-[#7c061a] px-10 py-4 font-bold text-white shadow-xl transition-all hover:bg-[#5a0413] active:scale-95">
                        <x-filament::icon icon="heroicon-m-document-check" class="h-6 w-6" />
                        <span class="text-lg">Save Settings</span>
                    </button>
                </div>
            </div>

            {{-- Main Form Content --}}
            <div class="grid grid-cols-1 gap-x-24 gap-y-16 md:grid-cols-2">

                {{-- Column 1: Store Identity --}}
                <div class="space-y-12">
                    <h2
                        class="border-b-2 border-gray-100 pb-4 text-2xl font-bold uppercase tracking-[0.25em] text-[#7c061a]">
                        Store Identity</h2>

                    <div class="space-y-10">
                        <div class="group">
                            <label
                                class="mb-4 block text-[13px] font-black uppercase tracking-[0.15em] text-gray-500">Site
                                Name</label>
                            <input type="text" wire:model="data.site_title" placeholder="ALMAARI"
                                class="w-full rounded-2xl border-none bg-[#f4f4f4] p-6 text-xl font-extrabold text-[#7c061a] ring-0 transition-all focus:ring-2 focus:ring-[#7c061a]/20">
                        </div>

                        <div class="group">
                            <label
                                class="mb-4 block text-[13px] font-black uppercase tracking-[0.15em] text-gray-500">Contact
                                Email</label>
                            <input type="email" wire:model="data.email"
                                class="w-full rounded-2xl border-none bg-[#f4f4f4] p-6 text-lg font-medium text-gray-800 ring-0 transition-all focus:ring-2 focus:ring-[#7c061a]/20">
                        </div>

                        <div class="group">
                            <label
                                class="mb-4 block text-[13px] font-black uppercase tracking-[0.15em] text-gray-500">Contact
                                Phone</label>
                            <input type="text" wire:model="data.phone_1"
                                class="w-full rounded-2xl border-none bg-[#f4f4f4] p-6 text-lg font-medium text-gray-800 ring-0 transition-all focus:ring-2 focus:ring-[#7c061a]/20">
                        </div>
                    </div>
                </div>

                {{-- Column 2: Social Links --}}
                <div class="space-y-12">
                    <h2
                        class="border-b-2 border-gray-100 pb-4 text-2xl font-bold uppercase tracking-[0.25em] text-[#7c061a]">
                        Social Links</h2>

                    <div class="space-y-10">
                        <div class="group">
                            <label
                                class="mb-4 block text-[13px] font-black uppercase tracking-[0.15em] text-gray-500">Instagram
                                URL</label>
                            <input type="text" wire:model="data.instagram"
                                class="w-full rounded-2xl border-none bg-[#f4f4f4] p-6 text-lg font-medium text-gray-800 ring-0 transition-all focus:ring-2 focus:ring-[#7c061a]/20">
                        </div>

                        <div class="group">
                            <label
                                class="mb-4 block text-[13px] font-black uppercase tracking-[0.15em] text-gray-500">Facebook
                                URL</label>
                            <input type="text" wire:model="data.facebook"
                                class="w-full rounded-2xl border-none bg-[#f4f4f4] p-6 text-lg font-medium text-gray-800 ring-0 transition-all focus:ring-2 focus:ring-[#7c061a]/20">
                        </div>

                        <div class="group">
                            <label
                                class="mb-4 block text-[13px] font-black uppercase tracking-[0.15em] text-gray-500">Whatsapp
                                Number</label>
                            <input type="text" wire:model="data.whatsapp"
                                class="w-full rounded-2xl border-none bg-[#f4f4f4] p-6 text-lg font-medium text-gray-800 ring-0 transition-all focus:ring-2 focus:ring-[#7c061a]/20">
                        </div>
                    </div>
                </div>

                {{-- Full Width Bottom: Store Address --}}
                <div class="mt-8 md:col-span-2">
                    <label class="mb-4 block text-[13px] font-black uppercase tracking-[0.15em] text-gray-500">Store
                        Address</label>
                    <textarea wire:model="data.address" rows="5"
                        class="w-full resize-none rounded-[32px] border-none bg-[#f4f4f4] p-10 text-lg font-medium text-gray-800 ring-0 transition-all focus:ring-2 focus:ring-[#7c061a]/20"></textarea>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
