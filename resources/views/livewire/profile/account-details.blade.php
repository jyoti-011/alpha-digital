<div class="rounded-sm border border-outline_variant/50 bg-surface_lowest p-8 font-sans">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="m-0 font-serif text-2xl font-bold text-secondary">Account Details</h1>
        @if (!$isEditing)
            <button wire:click="toggleEdit"
                class="flex cursor-pointer items-center gap-2 rounded-sm border border-[#800020] bg-transparent px-6 py-2.5 text-xs font-bold uppercase tracking-widest text-[#800020] transition-colors hover:bg-[#800020] hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                </svg>
                Edit Information
            </button>
        @else
            <button wire:click="toggleEdit"
                class="cursor-pointer rounded-sm border border-gray-300 bg-transparent px-6 py-2.5 text-xs font-bold uppercase tracking-widest text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900">
                Cancel
            </button>
        @endif
    </div>

    <x-toast-notification />



    @if ($isEditing)
        <form wire:submit.prevent="updateProfile" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-on_surface/80">First Name</label>
                    <input type="text" wire:model="first_name"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm outline-none transition-colors focus:border-primary">
                    @error('first_name')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-on_surface/80">Last Name</label>
                    <input type="text" wire:model="last_name"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm outline-none transition-colors focus:border-primary">
                    @error('last_name')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-on_surface/80">Email</label>
                    <input type="email" wire:model="email"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm outline-none transition-colors focus:border-primary">
                    @error('email')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-on_surface/80">Phone Number</label>
                    <input type="text" wire:model="phone"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm outline-none transition-colors focus:border-primary">
                    @error('phone')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-on_surface/80">Change Password</label>
                <label class="mb-4 mt-1 flex cursor-pointer items-center gap-2">
                    <input type="checkbox" wire:model.live="change_password"
                        class="h-4 w-4 rounded-sm border-outline_variant/70 text-primary focus:ring-primary">
                    <span class="text-sm text-tertiary">Update your password</span>
                </label>

                @if ($change_password)
                    <div
                        class="mt-4 grid grid-cols-1 gap-6 rounded-sm border border-outline_variant/50 bg-surface p-4 md:grid-cols-2">
                        <div class="col-span-full">
                            <label class="mb-2 block text-sm font-bold text-on_surface/80">Current Password</label>
                            <div x-data="{ show: false }" class="relative">
                                <input :type="show ? 'text' : 'password'" wire:model="current_password"
                                    class="h-[48px] w-full rounded-sm border border-outline_variant/70 pl-4 pr-12 text-sm outline-none transition-colors focus:border-primary">
                                <button type="button" @click="show = !show"
                                    class="absolute right-4 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-primary focus:outline-none"
                                    aria-label="Toggle password visibility">
                                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg x-show="show" x-cloak style="display: none;"
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                        <path
                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                        <path
                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                        <line x1="2" x2="22" y1="2" y2="22" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-on_surface/80">New Password</label>
                            <div x-data="{ show: false }" class="relative">
                                <input :type="show ? 'text' : 'password'" wire:model="new_password"
                                    class="h-[48px] w-full rounded-sm border border-outline_variant/70 pl-4 pr-12 text-sm outline-none transition-colors focus:border-primary">
                                <button type="button" @click="show = !show"
                                    class="absolute right-4 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-primary focus:outline-none"
                                    aria-label="Toggle password visibility">
                                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg x-show="show" x-cloak style="display: none;"
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                        <path
                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                        <path
                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                        <line x1="2" x2="22" y1="2" y2="22" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-on_surface/80">Confirm New Password</label>
                            <div x-data="{ show: false }" class="relative">
                                <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation"
                                    class="h-[48px] w-full rounded-sm border border-outline_variant/70 pl-4 pr-12 text-sm outline-none transition-colors focus:border-primary">
                                <button type="button" @click="show = !show"
                                    class="absolute right-4 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-primary focus:outline-none"
                                    aria-label="Toggle password visibility">
                                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg x-show="show" x-cloak style="display: none;"
                                        xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                        <path
                                            d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                        <path
                                            d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                        <line x1="2" x2="22" y1="2" y2="22" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-6 border-t border-outline_variant/50 pt-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-on_surface/80">Birthday</label>
                    <input type="date" wire:model="dob"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm text-on_surface outline-none transition-colors focus:border-primary">
                    @error('dob')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-on_surface/80">Gender</label>
                    <select wire:model="gender"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 bg-white px-4 text-sm text-on_surface outline-none transition-colors focus:border-primary">
                        <option value="">Select Gender</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                        <option value="other">Other</option>
                    </select>
                    @error('gender')
                        <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="btn-primary rounded-sm border-none px-8 py-3 text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    @else
        <div class="space-y-6 text-on_surface">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-sm border border-outline_variant/50 bg-surface p-4">
                    <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-500">First Name</span>
                    <span class="text-[15px]">{{ $first_name ?: '-' }}</span>
                </div>
                <div class="rounded-sm border border-outline_variant/50 bg-surface p-4">
                    <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-500">Last Name</span>
                    <span class="text-[15px]">{{ $last_name ?: '-' }}</span>
                </div>
                <div class="rounded-sm border border-outline_variant/50 bg-surface p-4">
                    <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-500">Email
                        Address</span>
                    <span class="text-[15px]">{{ $email ?: '-' }}</span>
                </div>
                <div class="rounded-sm border border-outline_variant/50 bg-surface p-4">
                    <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-500">Phone
                        Number</span>
                    <span class="text-[15px]">{{ $phone ?: '-' }}</span>
                </div>
                <div class="rounded-sm border border-outline_variant/50 bg-surface p-4">
                    <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-500">Date of
                        Birth</span>
                    <span class="text-[15px]">{{ $dob ? \Carbon\Carbon::parse($dob)->format('d M, Y') : '-' }}</span>
                </div>
                <div class="rounded-sm border border-outline_variant/50 bg-surface p-4">
                    <span class="mb-1 block text-xs font-bold uppercase tracking-wider text-gray-500">Gender</span>
                    <span class="text-[15px] capitalize">{{ $gender ?: '-' }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
