<div x-data="{
    show: false,
    _scrollY: 0,
    _wheelHandler: null,
    _touchHandler: null,
    _keyHandler: null,

    _blockScroll(e) {
        e.preventDefault();
        e.stopPropagation();
    },

    _blockKeys(e) {
        const scrollKeys = [32, 33, 34, 35, 36, 37, 38, 39, 40];
        if (scrollKeys.includes(e.keyCode)) {
            /* only block if the focus is NOT inside an input/textarea/select */
            const tag = document.activeElement && document.activeElement.tagName;
            if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) {
                e.preventDefault();
            }
        }
    },

    _openModal() {
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');
        document.documentElement.classList.add('modal-open');

        /* Block wheel / touch / keyboard scroll on the backdrop element */
        const el = this.$el;
        this._wheelHandler = (e) => {
            if (!el.contains(e.target)) return;
            /* Allow scrolling inside the inner modal panel */
            const inner = el.querySelector('.modal-inner-scroll');
            if (inner && inner.contains(e.target)) return;
            e.preventDefault();
            e.stopPropagation();
        };
        this._touchHandler = (e) => {
            if (!el.contains(e.target)) return;
            const inner = el.querySelector('.modal-inner-scroll');
            if (inner && inner.contains(e.target)) return;
            e.preventDefault();
            e.stopPropagation();
        };
        this._keyHandler = this._blockKeys.bind(this);

        el.addEventListener('wheel', this._wheelHandler, { passive: false, capture: true });
        el.addEventListener('touchmove', this._touchHandler, { passive: false, capture: true });
        window.addEventListener('keydown', this._keyHandler, { capture: true });

        this.show = true;
    },

    _closeModal() {
        if (typeof $wire !== 'undefined' && $wire.step == 4) {
            window.location.href = $wire.redirectUrl || '/';
            return;
        }

        this.show = false;

        /* Remove event listeners */
        const el = this.$el;
        if (this._wheelHandler) el.removeEventListener('wheel', this._wheelHandler, { capture: true });
        if (this._touchHandler) el.removeEventListener('touchmove', this._touchHandler, { capture: true });
        if (this._keyHandler) window.removeEventListener('keydown', this._keyHandler, { capture: true });
        this._wheelHandler = this._touchHandler = this._keyHandler = null;

        /* Restore body */
        document.body.classList.remove('modal-open');
        document.documentElement.classList.remove('modal-open');
        document.body.style.overflow = '';
    }
}" x-init="window.addEventListener('open-login-modal', () => _openModal());
window.addEventListener('close-login-modal', () => _closeModal());" @close-login-modal.window="_closeModal()"
    @open-login-modal.window="_openModal()" x-show="show" style="display: none;" {{-- z-[9999] so it completely covers the z-1000 navbar --}}
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <!-- Background overlay isolated to prevent backdrop-filter rendering glitches on focus -->
    <div x-show="show" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="_closeModal()"></div>

    {{-- Made the height responsive (max-h-[95vh]) so it doesn't break on small laptops --}}
    <div x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative z-10 flex max-h-[95vh] min-h-[500px] w-full max-w-[850px] overflow-hidden rounded-2xl bg-white shadow-2xl md:h-[550px]">
        <div class="relative hidden bg-[#F4F0EB] md:block md:w-[45%]">
            <img src="{{ asset('images/LoginPopup.webp') }}" class="h-full w-full object-cover" alt="Alpha Digital">
        </div>

        <div class="modal-inner-scroll relative flex w-full flex-col overflow-y-auto overscroll-contain bg-white px-8 pb-6 pt-8 md:w-[55%] md:px-12 md:pt-10"
            style="-ms-overflow-style: none; scrollbar-width: none;">

            <button @click="_closeModal()"
                class="absolute right-6 top-6 z-20 cursor-pointer border-none bg-transparent text-gray-500 outline-none transition-colors hover:text-black"
                aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <div class="mb-6 mt-6 flex justify-center text-center md:mt-2">
                <h1 class="m-0 text-xl font-bold uppercase tracking-[0.2em] text-[#800020]"
                    style="font-family: 'Noto Serif', serif;">
                    ALPHA DIGITAL
                </h1>
            </div>

            @if ($step == 1)
                <h2 class="mb-1 mt-0 text-center text-lg font-bold text-black">Login / Sign Up</h2>
                <p class="mb-8 mt-0 text-center text-[13px] text-gray-500">Enter your log in details</p>

                <form wire:submit.prevent="checkEmail" class="m-0 space-y-6">
                    <div>
                        <label class="mb-2 block text-[13px] font-bold text-black">Email Address</label>
                        <input type="email" wire:model="email" placeholder="Enter your email"
                            class="h-[46px] w-full rounded-md border border-gray-300 px-3 text-[14px] outline-none transition-colors focus:border-black">
                        @error('email')
                            <span class="mt-1 block text-[11px] font-bold text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="mt-2 h-[46px] w-full cursor-pointer rounded-md border-none bg-black text-[15px] font-medium text-white transition-colors hover:bg-gray-800"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="checkEmail">Continue</span>
                        <span wire:loading wire:target="checkEmail">Processing...</span>
                    </button>

                    <div class="relative mb-6 mt-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-white px-2 text-[12px] text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <a href="{{ route('google.login') }}"
                        class="flex h-[46px] w-full cursor-pointer items-center justify-center rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 no-underline shadow-sm transition-colors hover:bg-gray-50">
                        <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4" />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853" />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                fill="#FBBC05" />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335" />
                            <path d="M1 1h22v22H1z" fill="none" />
                        </svg>
                        Sign in with Google
                    </a>
                    <div class="mt-6 text-center">
                        <p class="m-0 text-[11px] leading-relaxed text-gray-400">
                            By continuing, you agree to Alpha Digital's<br>
                            <a href="#" class="text-gray-500 hover:text-black">Conditions of Use</a> and <a
                                href="#" class="text-gray-500 hover:text-black">Privacy Notice</a>.
                        </p>
                    </div>
                </form>
            @elseif($step == 2)
                <button wire:click="$set('step', 1)"
                    class="absolute left-6 top-6 flex cursor-pointer items-center border-none bg-transparent p-0 text-xs font-bold text-gray-400 outline-none hover:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg> Back
                </button>

                <h2 class="mb-1 mt-0 text-center text-lg font-bold text-black">Sign-In</h2>
                <p class="mb-6 mt-0 flex items-center justify-center gap-2 text-center text-[13px] text-gray-500">
                    {{ $email }}
                    <a href="#" wire:click.prevent="$set('step', 1)"
                        class="text-[11px] font-bold text-[#800020] hover:underline">Change</a>
                </p>

                <form wire:submit.prevent="authenticate" class="m-0 space-y-6">
                    @error('email')
                        <div
                            class="rounded-md border border-red-100 bg-red-50 p-3 text-center text-[13px] font-medium text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-[13px] font-bold text-black">Password</label>
                            <a href="#" wire:click.prevent="$set('step', 5)"
                                class="text-[11px] font-medium text-[#800020] hover:underline">Forgot password?</a>
                        </div>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password"
                                class="h-[46px] w-full rounded-md border border-gray-300 pl-3 pr-10 outline-none transition-colors focus:border-black">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-[#800020] focus:outline-none"
                                aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg"
                                    width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="mt-2 block text-[11px] font-bold text-red-500">{{ $message }}</span>
                        @enderror

                        <div class="mt-4 flex items-center">
                            <input type="checkbox" wire:model="remember" id="remember_me"
                                class="h-4 w-4 rounded border-gray-300 text-[#800020] focus:ring-[#800020]">
                            <label for="remember_me" class="m-0 ml-2 block text-[13px] text-gray-700">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="h-[46px] w-full cursor-pointer rounded-md border-none bg-black text-[15px] font-medium text-white transition-colors hover:bg-gray-800"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="authenticate">Sign In</span>
                        <span wire:loading wire:target="authenticate">Signing In...</span>
                    </button>
                </form>
            @elseif($step == 3)
                <button wire:click="$set('step', 1)"
                    class="absolute left-6 top-6 z-10 flex cursor-pointer items-center border-none bg-transparent p-0 text-xs font-bold text-gray-400 outline-none hover:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg> Back
                </button>
                <h2 class="mb-1 mt-0 text-center text-lg font-bold text-black">Enter Account Details</h2>
                <p class="mb-6 mt-0 text-center text-[13px] text-gray-500">Enter below details and update your account
                </p>

                <form wire:submit.prevent="saveDetails" class="m-0 space-y-4" x-data="{
                    show: false,
                    showConfirm: false,
                    val: '',
                    reqs: { length: false, upper: false, lower: false, number: false, special: false, noSpace: true },
                    validate(v) {
                        this.val = v || '';
                        this.reqs.length = this.val.length >= 8;
                        this.reqs.upper = /[A-Z]/.test(this.val);
                        this.reqs.lower = /[a-z]/.test(this.val);
                        this.reqs.number = /[0-9]/.test(this.val);
                        this.reqs.special = /[!@#$%^&*()_+\-=\[\]{}|;:'\',.<>\/?]/.test(this.val);
                        this.reqs.noSpace = this.val.length > 0 && !/\s/.test(this.val);
                    },
                    get score() {
                        if (!this.val) return 0;
                        let s = 0;
                        if (this.reqs.length) s++;
                        if (this.reqs.upper) s++;
                        if (this.reqs.lower) s++;
                        if (this.reqs.number) s++;
                        if (this.reqs.special) s++;
                        if (this.reqs.noSpace) s++;
                        return s;
                    },
                    get strengthPercent() { return (this.score / 6) * 100; },
                    get strengthText() {
                        if (!this.val) return '';
                        if (this.score <= 2) return 'Weak';
                        if (this.score <= 4) return 'Medium';
                        if (this.score === 6) return 'Strong';
                        return 'Good';
                    },
                    get strengthClass() {
                        if (this.score <= 2) return 'bg-red-500';
                        if (this.score <= 4) return 'bg-yellow-500';
                        return 'bg-green-500';
                    },
                    get strengthTextClass() {
                        if (this.score <= 2) return 'text-red-500';
                        if (this.score <= 4) return 'text-yellow-600';
                        return 'text-green-600';
                    },
                    get isValid() {
                        return this.score === 6;
                    }
                }">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-[12px] font-bold text-black">First Name*</label>
                            <input type="text" wire:model="first_name" placeholder="Enter your first name"
                                class="h-[40px] w-full rounded-md border border-gray-300 px-3 text-[13px] outline-none transition-colors focus:border-black">
                            @error('first_name')
                                <span class="text-[10px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-[12px] font-bold text-black">Last Name*</label>
                            <input type="text" wire:model="last_name" placeholder="Enter your last name"
                                class="h-[40px] w-full rounded-md border border-gray-300 px-3 text-[13px] outline-none transition-colors focus:border-black">
                            @error('last_name')
                                <span class="text-[10px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-[12px] font-bold text-black">Email*</label>
                        <input type="email" readonly tabindex="-1" autocomplete="username"
                            value="{{ $email }}"
                            class="h-[40px] w-full cursor-not-allowed rounded-md border border-gray-200 bg-gray-50 px-3 text-[13px] text-gray-500 outline-none">
                    </div>

                    <div>
                        <label class="mb-1 block text-[12px] font-bold text-black">Mobile number*</label>
                        <input type="tel" autocomplete="tel" wire:model="phone" placeholder="Mobile number"
                            class="h-[40px] w-full rounded-md border border-gray-300 px-3 text-[13px] outline-none transition-colors focus:border-black">
                        @error('phone')
                            <span class="text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-[12px] font-bold text-black">Password*</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="password"
                                @input="validate($event.target.value)" autocomplete="new-password"
                                placeholder="At least 8 characters"
                                class="h-[40px] w-full rounded-md border border-gray-300 pl-3 pr-10 text-[13px] outline-none transition-colors focus:border-black">
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-[#800020] focus:outline-none"
                                aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="mt-1 block text-[10px] text-red-500">{{ $message }}</span>
                        @enderror

                        <!-- Strength indicator -->
                        <div class="mt-2 flex h-1.5 overflow-hidden rounded-full bg-gray-200" x-show="val.length > 0"
                            x-cloak style="display: none;">
                            <div class="h-full transition-all duration-500 ease-out" :class="strengthClass"
                                :style="`width: ${strengthPercent}%`"></div>
                        </div>
                        <div class="mt-1 text-right text-[10px] font-bold uppercase tracking-wide"
                            :class="strengthTextClass" x-text="strengthText" x-show="val.length > 0" x-cloak
                            style="display: none;"></div>

                        <!-- Checklist -->
                        <div class="mt-2 grid grid-cols-2 gap-x-2 gap-y-1.5 text-[10.5px]">
                            <template
                                x-for="(req, i) in [
                                { label: 'Min 8 characters', met: reqs.length },
                                { label: 'Uppercase letter', met: reqs.upper },
                                { label: 'Lowercase letter', met: reqs.lower },
                                { label: 'Numeric digit', met: reqs.number },
                                { label: 'Special character', met: reqs.special },
                                { label: 'No spaces', met: reqs.noSpace }
                            ]"
                                :key="i">
                                <div class="flex items-center gap-1.5 transition-colors duration-200"
                                    :class="req.met ? 'text-green-600 font-medium' : 'text-gray-400'">
                                    <svg x-show="req.met" class="h-3 w-3 text-green-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg x-show="!req.met" class="h-3 w-3 text-gray-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <circle cx="12" cy="12" r="9" />
                                    </svg>
                                    <span x-text="req.label"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-[12px] font-bold text-black">Re-enter Password*</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" wire:model="password_confirmation"
                                autocomplete="new-password"
                                class="h-[40px] w-full rounded-md border border-gray-300 pl-3 pr-10 text-[13px] outline-none transition-colors focus:border-black">
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 flex -translate-y-1/2 cursor-pointer items-center border-none bg-transparent p-0 text-gray-400 transition-colors hover:text-[#800020] focus:outline-none"
                                aria-label="Toggle password visibility">
                                <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg x-show="showConfirm" x-cloak style="display: none;"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                    <line x1="2" x2="22" y1="2" y2="22" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-[12px] font-bold text-black">Date of Birth*</label>
                        <input type="date" wire:model="dob"
                            class="h-[40px] w-full rounded-md border border-gray-300 px-3 text-[13px] text-gray-600 outline-none transition-colors focus:border-black">
                        @error('dob')
                            <span class="text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-[12px] font-bold text-black">Gender*</label>
                        <select wire:model="gender"
                            class="h-[40px] w-full rounded-md border border-gray-300 bg-white px-3 text-[13px] text-gray-600 outline-none transition-colors focus:border-black">
                            <option value="">Gender</option>
                            <option value="female">Female</option>
                            <option value="male">Male</option>
                            <option value="other">Other</option>
                        </select>
                        @error('gender')
                            <span class="text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-2 pt-2">
                        <label class="m-0 flex cursor-pointer items-center gap-2">
                            <input type="checkbox" wire:model="subscribe"
                                class="h-3.5 w-3.5 rounded border-gray-300 text-black focus:ring-black">
                            <span class="text-[11px] text-gray-600">Subscribe to our newsletter for exclusive content
                                and news.</span>
                        </label>

                        <label class="m-0 flex cursor-pointer items-center gap-2">
                            <input type="checkbox" wire:model="agree_tos"
                                class="h-3.5 w-3.5 rounded border-gray-300 text-black focus:ring-black">
                            <span class="text-[11px] text-gray-600">I agree to the Terms of Service.*</span>
                        </label>
                        @error('agree_tos')
                            <span class="mt-0 block text-[10px] text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" :disabled="!isValid"
                        class="mt-4 h-[46px] w-full cursor-pointer rounded-md border-none bg-black text-[15px] font-medium text-white transition-colors hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveDetails">Register</span>
                        <span wire:loading wire:target="saveDetails">Registering...</span>
                    </button>
                </form>
            @elseif($step == 4)
                <div class="py-8 text-center">
                    <div
                        class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#F4F0EB] text-[#800020]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h2 class="mb-2 text-2xl font-bold text-black" style="font-family: 'Noto Serif', serif;">Welcome
                        to ALPHA DIGITAL</h2>
                    <p class="mb-8 px-4 text-[13px] leading-relaxed text-gray-500">Discover timeless sarees,
                        handcrafted elegance, and curated collections inspired by Indian heritage.</p>
                    <a href="{{ $redirectUrl }}"
                        class="flex h-[46px] cursor-pointer items-center justify-center rounded-md border-none bg-[#800020] px-8 text-[13px] font-medium uppercase tracking-[0.15em] text-white no-underline transition-all duration-300 hover:bg-[#5c0017]">
                        START SHOPPING
                    </a>
                </div>
            @elseif($step == 5)
                <button wire:click="$set('step', 1)"
                    class="absolute left-6 top-6 flex cursor-pointer items-center border-none bg-transparent p-0 text-xs font-bold text-gray-400 outline-none hover:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg> Back
                </button>

                <h2 class="mb-1 mt-0 text-center text-lg font-bold text-black">Reset Password</h2>
                <p class="mb-8 mt-0 text-center text-[13px] text-gray-500">Enter your email to receive a password reset
                    link.</p>

                <form wire:submit.prevent="sendResetLink" class="m-0 space-y-6">
                    <div>
                        <label class="mb-2 block text-[13px] font-bold text-black">Email Address</label>
                        <input type="email" wire:model="email" placeholder="Enter your email"
                            class="h-[46px] w-full rounded-md border border-gray-300 px-3 text-[14px] outline-none transition-colors focus:border-black">
                        @error('email')
                            <span class="mt-1 block text-[11px] font-bold text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="mt-2 h-[46px] w-full cursor-pointer rounded-md border-none bg-black text-[15px] font-medium text-white transition-colors hover:bg-gray-800"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="sendResetLink">Send Reset Link</span>
                        <span wire:loading wire:target="sendResetLink">Sending...</span>
                    </button>
                </form>
            @elseif($step == 6)
                <div class="py-8 text-center">
                    <div
                        class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="mb-3 text-xl font-bold text-black">Link Sent!</h2>
                    <p class="mx-auto mb-8 max-w-[280px] text-[13px] leading-relaxed text-gray-500">
                        We've sent a password reset link to <span
                            class="font-bold text-black">{{ $email }}</span>. Please check your inbox.
                    </p>
                    <button @click="_closeModal()"
                        class="h-[46px] w-full cursor-pointer rounded-md border-none bg-black text-[15px] font-medium text-white transition-colors hover:bg-gray-800">
                        Close
                    </button>
                </div>
            @elseif($step == 7)
                <button wire:click="$set('step', 1)"
                    class="absolute left-6 top-6 flex cursor-pointer items-center border-none bg-transparent p-0 text-xs font-bold text-gray-400 outline-none hover:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg> Back
                </button>

                <h2 class="mb-1 mt-0 text-center text-lg font-bold text-black">Verify Your Email</h2>
                <p class="mb-2 mt-0 text-center text-[13px] text-gray-500">
                    We've sent a 6-digit code to <span class="font-bold text-black">{{ $email }}</span>
                </p>

                @if (session()->has('otp_message'))
                    <div class="mb-4 rounded bg-green-50 p-2 text-center text-[12px] font-medium text-green-600">
                        {{ session('otp_message') }}
                    </div>
                @endif

                <form wire:submit.prevent="verifyOtp" class="m-0 mt-6 space-y-6">
                    <div>
                        <label class="mb-2 block text-[13px] font-bold text-black">6-Digit Code</label>
                        <input type="text" wire:model="otp_code" maxlength="6" placeholder="Enter code"
                            class="h-[46px] w-full rounded-md border border-gray-300 px-3 text-center font-mono text-[14px] font-bold tracking-[0.5em] outline-none transition-colors focus:border-black">
                        @error('otp_code')
                            <span class="mt-1 block text-[11px] font-bold text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="mt-2 h-[46px] w-full cursor-pointer rounded-md border-none bg-black text-[15px] font-medium text-white transition-colors hover:bg-gray-800"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="verifyOtp">Verify</span>
                        <span wire:loading wire:target="verifyOtp">Verifying...</span>
                    </button>

                    <div class="mt-6 text-center" x-data="{
                        timer: 30,
                        interval: null,
                        startTimer() {
                            this.timer = 30;
                            clearInterval(this.interval);
                            this.interval = setInterval(() => {
                                if (this.timer > 0) {
                                    this.timer--;
                                } else {
                                    clearInterval(this.interval);
                                }
                            }, 1000);
                        }
                    }" x-init="startTimer();
                    $wire.on('otp-resent', () => startTimer());">
                        <p class="m-0 text-[12px] text-gray-500">
                            Didn't receive the code?
                            <button type="button" x-show="timer === 0" wire:click="resendOtp"
                                class="ml-1 cursor-pointer border-none bg-transparent p-0 font-bold text-black underline hover:text-[#800020]">
                                Resend
                            </button>
                            <span x-show="timer > 0" x-cloak class="ml-1 font-bold text-gray-400">
                                Resend in <span x-text="timer"></span>s
                            </span>
                        </p>
                    </div>
                </form>
            @endif

        </div>
    </div>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</div>
