<div class="mt-24 flex min-h-[70vh] items-center justify-center bg-surface_lowest px-4 py-12 font-sans sm:px-6 lg:px-8">
    <div class="w-full max-w-md rounded-sm border border-outline_variant/50 bg-white p-8 shadow-sm">
        <div class="mb-8 text-center">
            <h1 class="m-0 font-serif text-2xl font-bold uppercase tracking-[0.2em] text-primary">
                ALPHA DIGITAL
            </h1>
            <h2 class="mt-6 text-xl font-bold text-secondary">Reset Password</h2>
        </div>

        <form wire:submit.prevent="resetPassword" class="space-y-6">
            <div>
                <label class="mb-2 block text-sm font-bold text-on_surface/80">Email Address</label>
                <input type="email" wire:model="email"
                    class="h-[48px] w-full rounded-sm border border-outline_variant/70 px-4 text-sm outline-none transition-colors focus:border-primary"
                    required autofocus>
                @error('email')
                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-on_surface/80">New Password</label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="password"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 pl-4 pr-12 text-sm outline-none transition-colors focus:border-primary"
                        required>
                    <button type="button" @click="show = !show"
                        class="absolute right-4 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-primary focus:outline-none"
                        aria-label="Toggle password visibility">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg"
                            width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                            <line x1="2" x2="22" y1="2" y2="22" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-on_surface/80">Confirm New Password</label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" wire:model="password_confirmation"
                        class="h-[48px] w-full rounded-sm border border-outline_variant/70 pl-4 pr-12 text-sm outline-none transition-colors focus:border-primary"
                        required>
                    <button type="button" @click="show = !show"
                        class="absolute right-4 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-primary focus:outline-none"
                        aria-label="Toggle password visibility">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg"
                            width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                            <line x1="2" x2="22" y1="2" y2="22" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="btn-primary w-full cursor-pointer rounded-sm border-none py-3 text-sm font-bold">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
