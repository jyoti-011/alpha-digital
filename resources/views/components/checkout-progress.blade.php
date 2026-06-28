@props(['step' => 1])

<div class="mb-4 flex items-center justify-center gap-4 border-b border-[#E5E0DA] pb-2 pt-4 font-sans sm:gap-8">
    {{-- Step 1: Shopping Bag --}}
    <div class="{{ $step >= 1 ? 'opacity-100' : 'opacity-40' }} flex flex-col items-center gap-2">
        <div
            class="{{ $step >= 1 ? 'border-[#800020] text-[#800020] font-bold' : 'border-gray-300 text-gray-400' }} flex h-8 w-8 items-center justify-center rounded-full border-2 text-sm transition-colors">
            @if ($step > 1)
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            @else
                1
            @endif
        </div>
        <span
            class="{{ $step >= 1 ? 'text-[#1b1c1a]' : 'text-gray-400' }} text-[10px] font-bold uppercase tracking-[0.2em] sm:text-xs">Shopping
            Bag</span>
    </div>

    {{-- Separator --}}
    <div class="{{ $step >= 2 ? 'bg-[#800020]' : 'bg-gray-200' }} h-[2px] w-8 transition-colors sm:w-16"></div>

    {{-- Step 2: Delivery --}}
    <div class="{{ $step >= 2 ? 'opacity-100' : 'opacity-40' }} flex flex-col items-center gap-2">
        <div
            class="{{ $step >= 2 ? 'border-[#800020] text-[#800020] font-bold' : 'border-gray-300 text-gray-400' }} flex h-8 w-8 items-center justify-center rounded-full border-2 text-sm transition-colors">
            @if ($step > 2)
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            @else
                2
            @endif
        </div>
        <span
            class="{{ $step >= 2 ? 'text-[#1b1c1a]' : 'text-gray-400' }} text-[10px] font-bold uppercase tracking-[0.2em] sm:text-xs">Delivery</span>
    </div>

    {{-- Separator --}}
    <div class="{{ $step >= 3 ? 'bg-[#800020]' : 'bg-gray-200' }} h-[2px] w-8 transition-colors sm:w-16"></div>

    {{-- Step 3: Payment --}}
    <div class="{{ $step >= 3 ? 'opacity-100' : 'opacity-40' }} flex flex-col items-center gap-2">
        <div
            class="{{ $step >= 3 ? 'border-[#800020] text-[#800020] font-bold' : 'border-gray-300 text-gray-400' }} flex h-8 w-8 items-center justify-center rounded-full border-2 text-sm transition-colors">
            3
        </div>
        <span
            class="{{ $step >= 3 ? 'text-[#1b1c1a]' : 'text-gray-400' }} text-[10px] font-bold uppercase tracking-[0.2em] sm:text-xs">Payment</span>
    </div>
</div>
