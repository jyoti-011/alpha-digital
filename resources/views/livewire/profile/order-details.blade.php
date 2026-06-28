<div class="bg-transparent pb-16 font-sans text-[#1b1c1a]" x-data="{
    reviewModalOpen: @entangle('reviewModalOpen').live,
    viewReviewModalOpen: @entangle('viewReviewModalOpen').live,
    cancelModalOpen: @entangle('cancelModalOpen').live,
    refundModalOpen: @entangle('refundModalOpen').live
}"
    x-effect="
         if (reviewModalOpen || viewReviewModalOpen || cancelModalOpen || refundModalOpen) {
             document.body.classList.add('modal-open');
             document.documentElement.classList.add('modal-open');
             document.body.style.overflow = 'hidden';
         } else {
             document.body.classList.remove('modal-open');
             document.documentElement.classList.remove('modal-open');
             document.body.style.overflow = '';
         }
     ">
    {{-- Header with Back Navigation --}}
    <h2
        class="m-0 mb-6 flex items-center gap-3 border-b border-outline_variant/50 pb-4 font-serif text-lg font-bold uppercase tracking-wide text-secondary">
        <a href="{{ route('profile.orders') }}" wire:navigate
            class="mt-0.5 flex items-center text-tertiary transition hover:text-primary" title="Back to Orders">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        Order Details
    </h2>

    @php
        $status = strtolower($order->status);
        $isTerminal = in_array($status, [
            'canceled',
            'cancelled',
            'failed',
            'refund_requested',
            'refund_approved',
            'refund_rejected',
            'refunded',
            'returned',
        ]);
        $isDelivered = $status === 'delivered';

        $terminalMessage = '';
        if (in_array($status, ['canceled', 'cancelled'])) {
            $terminalMessage = 'Your order has been successfully cancelled.';
        } elseif ($status === 'failed') {
            $terminalMessage = 'Payment failed or order could not be processed.';
        } elseif ($status === 'refund_requested') {
            $terminalMessage = 'Your refund request is currently under review.';
        } elseif ($status === 'refund_approved') {
            $terminalMessage = 'Refund approved. The amount will be credited soon.';
        } elseif ($status === 'refund_rejected') {
            $terminalMessage = 'Your refund request could not be approved.';
        } elseif ($status === 'refunded') {
            $terminalMessage = 'Your refund has been successfully processed.';
        } elseif ($status === 'returned') {
            $terminalMessage = 'Item has been returned successfully.';
        }

        $primaryItem = $order->items->first();
        $otherItems = $order->items->slice(1);
    @endphp

    <div class="space-y-6">

        {{-- ITEM DETAILS BOX --}}
        <div class="overflow-hidden rounded-lg border border-[#E5E0DA] bg-white shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            <div class="border-b border-[#E5E0DA] px-6 py-4">
                <span class="text-xs tracking-wide text-gray-500">Order ID:</span>
                <span class="ml-1 text-sm font-bold text-[#1b1c1a]">{{ $order->order_number }}</span>
            </div>

            <div class="p-6">
                @php
                    $expected = $order->expected_delivery_date
                        ? \Carbon\Carbon::parse($order->expected_delivery_date)->startOfDay()
                        : $order->created_at->addDays(7)->startOfDay();
                    $delivered = $order->delivered_at
                        ? \Carbon\Carbon::parse($order->delivered_at)->startOfDay()
                        : $order->updated_at->startOfDay();
                    $deliveryStatus = 'On Time';
                    $deliveryColor = 'text-[#2E7D32] bg-[#E8F5E9]';
                    if ($isDelivered) {
                        if ($delivered->lt($expected)) {
                            $deliveryStatus = 'Early Delivery';
                        } elseif ($delivered->gt($expected)) {
                            $deliveryStatus = 'Late Delivery';
                            $deliveryColor = 'text-[#C62828] bg-[#FFEBEE]';
                        }
                    }

                    $pillColor = 'bg-[#E8F5E9] text-[#2E7D32]'; // Default Green
                    $pillIcon =
                        '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>';

                    if (in_array($status, ['processing', 'shipped', 'refund_requested'])) {
                        $pillColor = 'bg-[#FFF3E0] text-[#E65100]'; // Orange
                        if ($status === 'shipped') {
                            $pillIcon =
                                '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-2.1a1 1 0 00-.29-.71l-3-3A1 1 0 0016 7z" /></svg>';
                        } else {
                            $pillIcon =
                                '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>';
                        }
                    } elseif (in_array($status, ['canceled', 'cancelled', 'failed', 'refund_rejected'])) {
                        $pillColor = 'bg-[#FFEBEE] text-[#C62828]'; // Red
                        $pillIcon =
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>';
                    }

                    $statusText =
                        strtolower($order->status) === 'new'
                            ? 'Confirmed'
                            : ucfirst(str_replace('_', ' ', $order->status));
                @endphp
                {{-- Status Header & Timeline --}}
                @if (in_array($status, ['failed', 'refund_requested', 'refund_approved', 'refund_rejected', 'refunded', 'returned']))
                    <div class="mb-6">
                        <div class="mb-4">
                            <span
                                class="{{ $pillColor }} inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold uppercase tracking-wider">
                                {!! $pillIcon !!}
                                {{ $statusText }}
                            </span>
                        </div>
                        <p class="m-0 text-sm font-medium text-gray-600">{{ $terminalMessage }}</p>
                    </div>
                @else
                    {{-- Arriving By / Delivered Header --}}
                    @if (!in_array($status, ['canceled', 'cancelled']))
                        <div class="mb-6 flex items-center justify-between">
                            @if ($isDelivered)
                                <div>
                                    <p class="m-0 text-lg font-bold text-[#2E7D32]">Delivered on
                                        {{ $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('D, d M') : $order->updated_at->format('D, d M') }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-xs text-gray-500">Return window closed on
                                            {{ \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at)->addDays(7)->format('d M') }}</span>
                                    </div>
                                </div>
                                <span
                                    class="{{ $deliveryColor }} rounded px-2 py-1 text-[10px] font-bold uppercase tracking-wider">{{ $deliveryStatus }}</span>
                            @else
                                <p class="m-0 text-lg font-bold text-[#2E7D32]">Arriving By
                                    {{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('D, d M') : $order->created_at->addDays(7)->format('D, d M') }}
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- Horizontal Tracking Timeline --}}
                    <div class="relative mb-8 mt-8 px-2">
                        @php
                            $isCancelled = in_array($status, ['canceled', 'cancelled']);
                            $prevStatus = 'new';
                            if ($isCancelled) {
                                $history = \App\Models\OrderStatusHistory::where('order_id', $order->id)
                                    ->whereIn('new_status', ['canceled', 'cancelled'])
                                    ->latest()
                                    ->first();
                                if ($history) {
                                    $prevStatus = strtolower($history->previous_status);
                                }
                            }
                        @endphp

                        @if ($isCancelled)
                            @php
                                $histories = \App\Models\OrderStatusHistory::where('order_id', $order->id)
                                    ->get()
                                    ->keyBy('new_status');
                                $processingDate = $histories->has('processing')
                                    ? $histories['processing']->created_at
                                    : null;
                                $shippedDate = $order->shipping_date
                                    ? \Carbon\Carbon::parse($order->shipping_date)
                                    : ($histories->has('shipped')
                                        ? $histories['shipped']->created_at
                                        : null);

                                $totalSteps = 2; // Confirmed + Cancelled
                                if ($prevStatus === 'processing') {
                                    $totalSteps = 3;
                                }
                                if ($prevStatus === 'shipped') {
                                    $totalSteps = 4;
                                }
                                $lineInset = 100 / ($totalSteps * 2) . '%';
                            @endphp
                            {{-- Background Line --}}
                            <div class="absolute top-4 z-0 h-[2px] bg-[#E5E0DA]"
                                style="left: {{ $lineInset }}; right: {{ $lineInset }};"></div>

                            {{-- Active Line --}}
                            <div class="absolute top-4 z-0 h-[2px] bg-[#C62828] transition-all duration-500"
                                style="left: {{ $lineInset }}; width: {{ 100 - 100 / $totalSteps }}%;"></div>

                            <div class="relative z-10 flex justify-between">
                                {{-- Step 1: Confirmed --}}
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <p class="mb-0 mt-2 text-[10px] font-bold text-[#1b1c1a] sm:text-xs">Confirmed</p>
                                    <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                        {{ $order->created_at->format('d M, h:i A') }}</p>
                                </div>

                                {{-- Step 2: Processing (if applicable) --}}
                                @if (in_array($prevStatus, ['processing', 'shipped', 'delivered']))
                                    <div class="flex flex-1 flex-col items-center">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <p class="mb-0 mt-2 text-[10px] font-bold text-[#1b1c1a] sm:text-xs">Processing
                                        </p>
                                        @if ($processingDate)
                                            <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                                {{ $processingDate->format('d M, h:i A') }}</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Step 3: Shipped (if applicable) --}}
                                @if (in_array($prevStatus, ['shipped', 'delivered']))
                                    <div class="flex flex-1 flex-col items-center">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path
                                                    d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                <path
                                                    d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-2.1a1 1 0 00-.29-.71l-3-3A1 1 0 0016 7z" />
                                            </svg>
                                        </div>
                                        <p class="mb-0 mt-2 text-[10px] font-bold text-[#1b1c1a] sm:text-xs">Shipped</p>
                                        @if ($shippedDate)
                                            <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                                {{ $shippedDate->format('d M, h:i A') }}</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Final Step: Cancelled --}}
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#C62828] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <p class="mb-0 mt-2 text-[10px] font-bold text-[#C62828] sm:text-xs">Cancelled</p>
                                    @if ($order->cancelled_at)
                                        <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                            {{ \Carbon\Carbon::parse($order->cancelled_at)->format('d M, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @elseif(in_array($status, ['refund_requested', 'refund_approved', 'refund_rejected', 'refunded']))
                            {{-- REFUND TIMELINE --}}
                            @php
                                $progressWidth = '0%';
                                if (in_array($status, ['refund_approved', 'refund_rejected'])) {
                                    $progressWidth = '66.66%';
                                }
                                if ($status === 'refunded') {
                                    $progressWidth = '100%';
                                }
                                if ($status === 'refund_requested') {
                                    $progressWidth = '33.33%';
                                }
                            @endphp
                            <div class="absolute left-[12.5%] right-[12.5%] top-4 z-0 h-[2px] bg-[#E5E0DA]">
                                <div class="absolute left-0 top-0 h-full bg-[#2E7D32] transition-all duration-500"
                                    style="width: {{ $progressWidth }}; {{ $status === 'refund_rejected' ? 'background-color: #C62828;' : '' }}">
                                </div>
                            </div>

                            <div class="relative z-10 flex justify-between">
                                {{-- Step 1: Delivered --}}
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <p class="mb-0 mt-2 text-[10px] font-bold text-[#1b1c1a] sm:text-xs">Delivered</p>
                                    @if ($order->delivered_at)
                                        <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                            {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 2: Refund Requested --}}
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#E65100] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="mb-0 mt-2 text-[10px] font-bold text-[#1b1c1a] sm:text-xs">Requested</p>
                                    @if ($order->refund_requested_at)
                                        <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                            {{ \Carbon\Carbon::parse($order->refund_requested_at)->format('d M, h:i A') }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Step 3: Approved/Rejected --}}
                                @php
                                    $isDecisionMade = in_array($status, [
                                        'refund_approved',
                                        'refund_rejected',
                                        'refunded',
                                    ]);
                                    $decisionColor = 'bg-[#F5F5F5] text-gray-300';
                                    $decisionTextColor = 'text-gray-400';
                                    $decisionText = 'Reviewed';
                                    $decisionIcon =
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                    if ($isDecisionMade) {
                                        if ($status === 'refund_rejected') {
                                            $decisionColor = 'bg-[#C62828] text-white';
                                            $decisionTextColor = 'text-[#C62828]';
                                            $decisionText = 'Rejected';
                                            $decisionIcon =
                                                '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
                                        } else {
                                            $decisionColor = 'bg-[#2E7D32] text-white';
                                            $decisionTextColor = 'text-[#1b1c1a]';
                                            $decisionText = 'Approved';
                                        }
                                    }
                                @endphp
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="{{ $decisionColor }} flex h-8 w-8 items-center justify-center rounded-full shadow-[0_0_0_4px_white]">
                                        {!! $decisionIcon !!}
                                    </div>
                                    <p class="{{ $decisionTextColor }} mb-0 mt-2 text-[10px] font-bold sm:text-xs">
                                        {{ $decisionText }}</p>
                                    @if ($isDecisionMade && ($order->refund_approved_at || $order->refund_rejected_at))
                                        <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                            {{ \Carbon\Carbon::parse($order->refund_approved_at ?? $order->refund_rejected_at)->format('d M, h:i A') }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Step 4: Refunded --}}
                                @if ($status !== 'refund_rejected')
                                    @php
                                        $isRefunded = $status === 'refunded';
                                        $refundedColor = $isRefunded
                                            ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]'
                                            : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                        $refundedTextColor = $isRefunded ? 'text-[#1b1c1a]' : 'text-gray-400';
                                    @endphp
                                    <div class="flex flex-1 flex-col items-center">
                                        <div
                                            class="{{ $refundedColor }} flex h-8 w-8 items-center justify-center rounded-full shadow-[0_0_0_4px_white]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <p
                                            class="{{ $refundedTextColor }} mb-0 mt-2 text-[10px] font-bold sm:text-xs">
                                            Refunded</p>
                                        @if ($isRefunded && $order->refund_processed_at)
                                            <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                                {{ \Carbon\Carbon::parse($order->refund_processed_at)->format('d M, h:i A') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Standard Timeline --}}
                            @php
                                $histories = \App\Models\OrderStatusHistory::where('order_id', $order->id)
                                    ->get()
                                    ->keyBy('new_status');
                                $processingDate = $histories->has('processing')
                                    ? $histories['processing']->created_at
                                    : null;
                                $shippedDate = $order->shipping_date
                                    ? \Carbon\Carbon::parse($order->shipping_date)
                                    : ($histories->has('shipped')
                                        ? $histories['shipped']->created_at
                                        : null);
                                $deliveredDate = $order->delivered_at
                                    ? \Carbon\Carbon::parse($order->delivered_at)
                                    : ($histories->has('delivered')
                                        ? $histories['delivered']->created_at
                                        : null);

                                $progressWidth = '0%';
                                if (in_array($status, ['processing'])) {
                                    $progressWidth = '33.33%';
                                }
                                if (in_array($status, ['shipped'])) {
                                    $progressWidth = '66.66%';
                                }
                                if ($isDelivered) {
                                    $progressWidth = '100%';
                                }
                            @endphp
                            <div class="absolute left-[12.5%] right-[12.5%] top-4 z-0 h-[2px] bg-[#E5E0DA]">
                                <div class="absolute left-0 top-0 h-full bg-[#2E7D32] transition-all duration-500"
                                    style="width: {{ $progressWidth }}"></div>
                            </div>

                            <div class="relative z-10 flex justify-between">
                                {{-- Step 1: Confirmed --}}
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <p class="mb-0 mt-2 text-[10px] font-bold text-[#1b1c1a] sm:text-xs">Confirmed</p>
                                    <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                        {{ $order->created_at->format('d M, h:i A') }}</p>
                                </div>

                                {{-- Step 2: Processing --}}
                                @php
                                    $isProcessingOrMore = in_array($status, ['processing', 'shipped', 'delivered']);
                                    $procColor = $isProcessingOrMore
                                        ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]'
                                        : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                    $procTextColor = $isProcessingOrMore ? 'text-[#1b1c1a]' : 'text-gray-400';
                                @endphp
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="{{ $procColor }} flex h-8 w-8 items-center justify-center rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <p class="{{ $procTextColor }} mb-0 mt-2 text-[10px] font-bold sm:text-xs">
                                        Processing</p>
                                    @if ($isProcessingOrMore && $processingDate)
                                        <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                            {{ $processingDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 3: Shipped --}}
                                @php
                                    $isShippedOrMore = in_array($status, ['shipped', 'delivered']);
                                    $shippedColor = $isShippedOrMore
                                        ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]'
                                        : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                    $shippedTextColor = $isShippedOrMore ? 'text-[#1b1c1a]' : 'text-gray-400';
                                @endphp
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="{{ $shippedColor }} flex h-8 w-8 items-center justify-center rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                            <path
                                                d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-2.1a1 1 0 00-.29-.71l-3-3A1 1 0 0016 7z" />
                                        </svg>
                                    </div>
                                    <p class="{{ $shippedTextColor }} mb-0 mt-2 text-[10px] font-bold sm:text-xs">
                                        Shipped</p>
                                    @if ($isShippedOrMore && $shippedDate)
                                        <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                            {{ $shippedDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 4: Delivered --}}
                                @php
                                    $deliveredColor = $isDelivered
                                        ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]'
                                        : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                    $deliveredTextColor = $isDelivered ? 'text-[#1b1c1a]' : 'text-gray-400';
                                @endphp
                                <div class="flex flex-1 flex-col items-center">
                                    <div
                                        class="{{ $deliveredColor }} flex h-8 w-8 items-center justify-center rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <p class="{{ $deliveredTextColor }} mb-0 mt-2 text-[10px] font-bold sm:text-xs">
                                        Delivered</p>
                                    @if ($isDelivered && $deliveredDate)
                                        <p class="mt-0.5 text-[9px] text-gray-500 sm:text-[10px]">
                                            {{ $deliveredDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Product List Grid --}}
                <div class="space-y-0 rounded-lg border border-[#E5E0DA]">
                    @foreach ($order->items as $item)
                        @php
                            $product = $item->product;
                            $img =
                                $product && is_array($product->images) && count($product->images) > 0
                                    ? asset('storage/' . $product->images[0])
                                    : 'https://via.placeholder.com/100x140';
                            $productUrl = $product ? route('shop.product', $product->slug ?? $product->id) : '#';
                        @endphp
                        <div
                            class="{{ !$loop->last ? 'border-b border-[#E5E0DA]' : '' }} group block px-4 py-4 text-inherit transition">
                            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                <a href="{{ $productUrl }}" wire:navigate
                                    class="flex items-center gap-4 no-underline hover:opacity-80">
                                    <div
                                        class="h-16 w-12 flex-shrink-0 overflow-hidden rounded border border-[#E5E0DA] bg-[#F4F0EB]">
                                        <img src="{{ $img }}" class="h-full w-full object-cover object-top">
                                    </div>
                                    <div>
                                        <h4
                                            class="m-0 line-clamp-1 text-sm text-[#1b1c1a] transition-colors duration-200 group-hover:text-[#800020]">
                                            {{ $product ? $product->name : 'Premium Heirloom Saree' }}</h4>
                                        <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                            <span>{{ $product && $product->color ? ucfirst($product->color->name) : 'Standard' }}</span>
                                            <span>&bull;</span>
                                            <span>Qty: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="flex items-center justify-end">
                                    @if ($isDelivered && $product)
                                        @php
                                            $hasReviewed = \App\Models\Review::where(
                                                'customer_id',
                                                auth('customer')->id(),
                                            )
                                                ->where('product_id', $product->id)
                                                ->exists();
                                        @endphp
                                        @if (!$hasReviewed)
                                            <button wire:click.stop="openReviewModal({{ $product->id }})"
                                                class="m-0 flex cursor-pointer items-center gap-1 border-none bg-transparent p-0 text-xs font-bold uppercase tracking-widest text-[#800020] transition hover:text-[#5D4037]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                                Rate Product
                                            </button>
                                        @else
                                            <button wire:click.stop="openViewReviewModal({{ $product->id }})"
                                                class="m-0 flex cursor-pointer items-center gap-1 border-none bg-transparent p-0 text-xs font-bold uppercase tracking-widest text-green-600 transition hover:text-green-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Review
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ $productUrl }}" wire:navigate
                                            class="text-gray-400 hover:text-[#800020]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if (!$isTerminal)
                <div class="flex items-center justify-between border-t border-[#E5E0DA] bg-[#FAFAFA] px-6 py-4">
                    <div>
                        @if ($isDelivered)
                            @php
                                $deliveredAt = $order->delivered_at
                                    ? \Carbon\Carbon::parse($order->delivered_at)
                                    : $order->updated_at;
                                $canRefund = now()->diffInDays($deliveredAt) <= 7;
                            @endphp
                            <div class="flex flex-col items-start gap-1">
                                @if ($canRefund)
                                    <button wire:click="openRefundModal"
                                        class="flex cursor-pointer items-center gap-1 border-none bg-transparent text-xs font-bold uppercase tracking-widest text-[#800020] transition hover:text-[#570013]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                        </svg>
                                        Refund / Return
                                    </button>
                                    <span class="text-[10px] font-medium text-gray-500">Note: Refunds must be requested
                                        within 7 days of delivery.</span>
                                @else
                                    <button disabled
                                        class="flex cursor-not-allowed items-center gap-1 border-none bg-transparent text-xs font-bold uppercase tracking-widest text-gray-400"
                                        title="Refund period has expired">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                        </svg>
                                        Refund / Return
                                    </button>
                                    <span class="text-[10px] font-medium text-[#C62828]">The 7-day refund window has
                                        expired.</span>
                                @endif
                            </div>
                        @elseif(in_array($status, ['new', 'processing']))
                            <div class="flex flex-col items-start gap-1">
                                <button wire:click="openCancelModal"
                                    class="flex cursor-pointer items-center gap-1 border-none bg-transparent text-xs font-bold uppercase tracking-widest text-red-600 transition hover:text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg>
                                    Cancel Order
                                </button>
                                <span class="text-[10px] font-medium text-gray-500">Note: You can cancel your order
                                    anytime before it is shipped.</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        {{-- Kept empty to balance flexbox if needed, or you can remove entirely if you align left --}}
                    </div>
                </div>
            @endif
        </div>

        @if (in_array($status, ['canceled', 'cancelled']))
            {{-- CANCELLATION DETAILS BOX --}}
            <div class="rounded-lg border border-[#E5E0DA] bg-white p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                <h3 class="m-0 mb-4 text-base font-bold text-[#C62828]">Cancellation Details</h3>
                <div class="rounded border border-[#ffcdd2] bg-[#FFEBEE] p-4 text-sm leading-relaxed text-[#C62828]">
                    <p class="mb-1 font-bold">Reason for Cancellation:</p>
                    <p class="m-0 mb-3">{{ $order->cancellation_reason ?? 'No reason provided.' }}</p>



                    <div class="flex items-center justify-between border-t border-[#ef9a9a] pt-2 text-xs opacity-80">
                        <span>Cancelled By:
                            <strong>{{ strtolower($order->cancelled_by_role) === 'admin' ? 'Alpha Digital Support' : 'You (Customer)' }}</strong></span>
                        <span>On
                            {{ $order->cancelled_at ? \Carbon\Carbon::parse($order->cancelled_at)->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                </div>

                @if ($order->refund_required)
                    <div class="mt-4 rounded border border-[#E5E0DA] bg-[#FAFAFA] p-4 text-sm text-[#5D4037]">
                        <p class="m-0 mb-1 font-bold text-[#1b1c1a]">Refund Status</p>
                        <p class="m-0 text-gray-600">Your refund is currently being processed. It typically takes 5-7
                            business days to reflect in your original payment method.</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- REFUND DETAILS BOX --}}
        @if (in_array($status, ['refund_requested', 'refund_approved', 'refund_rejected', 'refunded']))
            <div class="rounded-lg border border-[#E5E0DA] bg-white p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                <h3 class="m-0 mb-4 flex items-center gap-2 text-base font-bold text-[#d97706]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                    </svg>
                    Refund Details
                </h3>
                <div class="rounded border border-[#ffecb3] bg-[#FFF8E1] p-4 text-sm leading-relaxed text-[#F57F17]">
                    <p class="mb-1 font-bold">Reason for Refund Request:</p>
                    <p class="m-0 mb-3 text-[#5D4037]">{{ $order->refund_reason ?? 'No reason provided.' }}</p>

                    @if (!empty($order->refund_evidence) && is_array($order->refund_evidence))
                        <p class="mb-1 font-bold">Supporting Evidence:</p>
                        <div class="mb-3 flex flex-wrap gap-2">
                            @foreach ($order->refund_evidence as $evidence)
                                <a href="{{ Storage::url($evidence) }}" target="_blank"
                                    class="inline-flex items-center gap-1 rounded border border-[#ffecb3] bg-white px-3 py-1.5 text-xs font-bold text-[#F57F17] no-underline transition hover:bg-[#ffecb3]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    View Attachment {{ $loop->iteration }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div
                        class="flex items-center justify-between border-t border-[#ffe082] pt-2 text-xs text-[#F57F17] opacity-80">
                        <span>Requested By: <strong>You (Customer)</strong></span>
                        <span>On
                            {{ $order->refund_requested_at ? \Carbon\Carbon::parse($order->refund_requested_at)->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                </div>

                @if ($status === 'refund_rejected')
                    <div class="mt-4 rounded border border-[#ffcdd2] bg-[#FFEBEE] p-4 text-sm text-[#C62828]">
                        <p class="m-0 mb-1 flex items-center gap-1 font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Refund Rejected
                        </p>
                        <p class="m-0 mb-2">Your refund request was reviewed but unfortunately rejected.</p>
                        <p class="m-0 text-xs font-bold uppercase tracking-wider">Reason provided by Admin:</p>
                        <p class="m-0 italic">{{ $order->refund_rejection_reason ?? 'No specific reason provided.' }}
                        </p>
                    </div>
                @elseif($status === 'refund_approved' || $status === 'refunded')
                    <div class="mt-4 rounded border border-[#c8e6c9] bg-[#E8F5E9] p-4 text-sm text-[#2E7D32]">
                        <p class="m-0 mb-1 flex items-center gap-1 font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Refund Approved
                        </p>
                        @if ($status === 'refunded')
                            <p class="m-0">Your refund has been successfully processed on
                                {{ $order->refund_processed_at ? \Carbon\Carbon::parse($order->refund_processed_at)->format('d M Y, h:i A') : 'N/A' }}.
                                It should reflect in your original payment method shortly.</p>
                        @else
                            <p class="m-0">Your refund request has been approved and is currently being processed.
                                It typically takes 5-7 business days to reflect in your original payment method.</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- DELIVERY DETAILS --}}
        <div class="rounded-lg border border-[#E5E0DA] bg-white p-6 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            <h3 class="m-0 mb-4 text-base font-bold text-[#1b1c1a]">Delivery details</h3>
            @php $address = $order->customer?->addresses()->first(); @endphp
            <div class="rounded border border-[#E5E0DA] bg-[#FAFAFA] p-4 text-sm leading-relaxed text-[#5D4037]">
                <p class="mb-1 font-bold text-[#1b1c1a]">{{ auth('customer')->user()->name ?? 'Customer' }}</p>
                @if ($address)
                    <p class="m-0">{{ $address->address_1 }}@if ($address->address_2)
                            , {{ $address->address_2 }}
                        @endif
                    </p>
                    <p class="m-0">{{ $address->city }}, {{ $address->province }} - {{ $address->postal_code }}
                    </p>
                    <p class="mt-3 flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg> (+91) {{ $address->phone }}</p>
                @else
                    <p class="italic text-gray-400">Address details unavailable.</p>
                @endif
            </div>
        </div>

        {{-- ORDER PRICE SUMMARY --}}
        <div class="overflow-hidden rounded-lg border border-[#E5E0DA] bg-white shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
            <div class="flex cursor-pointer items-center justify-between border-b border-[#E5E0DA] px-6 py-4">
                <h3 class="m-0 text-base font-bold text-[#1b1c1a]">
                    Order Price
                </h3>
            </div>

            @php
                $totalItems = $order->items->sum('quantity');
                $originalPriceTotal = 0;
                $subtotalCalc = 0;

                foreach ($order->items as $item) {
                    $subtotalCalc += $item->price * $item->quantity;
                    $prodOrig = $item->product ? $item->product->original_price : 0;
                    $origPrice = $prodOrig > 0 ? $prodOrig : $item->price;
                    $originalPriceTotal += $origPrice * $item->quantity;
                }

                $shippingCalc = $order->total_amount - $subtotalCalc;
                $shippingCalc = $shippingCalc > 0 ? $shippingCalc : 0;
                $discount = $originalPriceTotal - $subtotalCalc;
            @endphp

            <div class="p-6">
                <div class="space-y-4 border-b border-dashed border-[#E5E0DA] pb-5 text-[15px] text-gray-600">
                    <div class="flex justify-between">
                        <span>Price ({{ $totalItems }} item{{ $totalItems > 1 ? 's' : '' }})</span>
                        <span>₹{{ number_format($originalPriceTotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Discount</span>
                        <span class="text-[#2E7D32]">- ₹{{ number_format($discount, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="flex items-center gap-1" x-data="{ showTooltip: false }">
                            Shipping
                            <div class="relative flex items-center justify-center">
                                <svg @mouseenter="showTooltip = true" @mouseleave="showTooltip = false"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 cursor-pointer text-gray-400 transition-colors hover:text-[#800020]"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                {{-- Tooltip --}}
                                <div x-show="showTooltip" x-transition.opacity.duration.200ms
                                    class="absolute bottom-full left-[-12px] z-50 mb-2 w-64 rounded border border-[#E5E0DA] bg-white p-4 text-left shadow-lg"
                                    style="display: none; cursor: default;">
                                    <h4 class="m-0 mb-2 text-sm font-bold text-[#1b1c1a]">Shipping Policy</h4>
                                    <p class="m-0 text-xs leading-relaxed text-gray-500">
                                        We offer complimentary shipping on all orders above Rs. 10,000. For orders below
                                        this amount, a standard shipping fee of Rs. 150 applies.
                                    </p>
                                    {{-- Tooltip Arrow --}}
                                    <div
                                        class="absolute left-[12px] top-full -mt-[1px] border-8 border-transparent border-t-white">
                                    </div>
                                    <div
                                        class="absolute left-[12px] top-full -z-10 mt-[1px] border-8 border-transparent border-t-[#E5E0DA]">
                                    </div>
                                </div>
                            </div>
                        </span>
                        <span>{{ $shippingCalc == 0 ? 'Free' : '₹' . number_format($shippingCalc, 0) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between py-5">
                    <span class="text-[18px] font-bold text-[#1b1c1a]">Total Amount</span>
                    <span
                        class="text-[18px] font-bold text-[#1b1c1a]">₹{{ number_format($order->total_amount, 0) }}</span>
                </div>

                @if ($discount > 0)
                    <div
                        class="mb-4 flex items-center gap-2 rounded-md bg-[#F0FDF4] p-3 text-[15px] font-medium text-[#16A34A]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" />
                        </svg>
                        You'll save ₹{{ number_format($discount, 0) }} on this order!
                    </div>
                @endif

                <div class="flex justify-between border-t border-[#E5E0DA] pt-4 text-sm">
                    <span class="text-gray-500">Payment mode</span>
                    <span
                        class="font-bold uppercase text-[#1b1c1a]">{{ $order->payment_status === 'paid' ? 'Online (Razorpay)' : 'COD' }}</span>
                </div>
            </div>

            <div
                class="flex items-center justify-between rounded-b-lg border-t border-[#E5E0DA] bg-[#FAFAFA] px-6 py-4 text-sm">
                <span class="text-gray-600">Get invoice for this shipment</span>
                <a href="{{ route('profile.orders.invoice', $order->id) }}"
                    class="font-bold text-[#800020] no-underline hover:underline">Download invoice</a>
            </div>
        </div>
    </div>

    {{-- CANCEL ORDER MODAL --}}
    @if ($cancelModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="mx-4 w-full max-w-md overflow-hidden rounded-lg border border-[#E5E0DA] bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-[#E5E0DA] bg-[#FAFAFA] px-6 py-4">
                    <h3 class="m-0 flex items-center gap-2 text-lg font-bold text-[#1b1c1a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Cancel Order
                    </h3>
                    <button wire:click="$set('cancelModalOpen', false)"
                        class="cursor-pointer border-none bg-transparent text-gray-400 transition hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="cancelOrder">
                    <div class="p-6">
                        <p class="m-0 mb-4 text-sm leading-relaxed text-[#5D4037]">
                            Are you sure you want to cancel this order? This action cannot be undone.
                            @if (strtolower($order->payment_status) === 'paid')
                                Since you have already paid, a refund will be initiated automatically to your original
                                payment method.
                            @endif
                        </p>

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-bold text-[#1b1c1a]">Reason for Cancellation <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="cancellation_reason"
                                class="w-full rounded-md border border-[#E5E0DA] bg-white px-3 py-2 text-sm transition focus:border-[#800020] focus:outline-none">
                                <option value="">Select a reason...</option>
                                <option value="Found a better price elsewhere">Found a better price elsewhere</option>
                                <option value="Changed my mind">Changed my mind</option>
                                <option value="Order placed by mistake">Order placed by mistake</option>
                                <option value="Expected delivery date is too late">Expected delivery date is too late
                                </option>
                                <option value="Other">Other (Please specify)</option>
                            </select>
                            @error('cancellation_reason')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($cancellation_reason === 'Other')
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-bold text-[#1b1c1a]">Specific Reason <span
                                        class="text-red-500">*</span></label>
                                <textarea wire:model.defer="custom_cancellation_reason" rows="2"
                                    class="w-full resize-none rounded-md border border-[#E5E0DA] bg-white px-3 py-2 text-sm transition focus:border-[#800020] focus:outline-none"
                                    placeholder="Briefly describe your reason..."></textarea>
                                @error('custom_cancellation_reason')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" wire:click="$set('cancelModalOpen', false)"
                                class="cursor-pointer rounded-md border border-[#E5E0DA] bg-white px-4 py-2 text-sm font-bold text-[#1b1c1a] transition hover:bg-gray-50">Keep
                                Order</button>
                            <button type="submit"
                                class="cursor-pointer rounded-md border border-red-600 bg-red-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-red-700">Confirm
                                Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- REFUND REQUEST MODAL --}}
    @if ($refundModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="mx-4 w-full max-w-md overflow-hidden rounded-lg border border-[#E5E0DA] bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-[#E5E0DA] bg-[#FAFAFA] px-6 py-4">
                    <h3 class="m-0 flex items-center gap-2 text-lg font-bold text-[#1b1c1a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#d97706]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                        </svg>
                        Request Refund / Return
                    </h3>
                    <button wire:click="$set('refundModalOpen', false)"
                        class="cursor-pointer border-none bg-transparent text-gray-400 transition hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitRefundRequest">
                    <div class="max-h-[70vh] overflow-y-auto p-6">
                        <div class="mb-4 flex items-start gap-2 rounded-md border border-[#ffecb3] bg-[#FFF8E1] p-3">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="mt-0.5 h-4 w-4 flex-shrink-0 text-[#F57F17]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="m-0 text-xs font-medium leading-relaxed text-[#F57F17]">
                                <strong>Policy:</strong> Refunds must be requested within 7 days of delivery. Original
                                payment methods will be credited.
                                <a href="{{ route('policy.shipping') }}" target="_blank"
                                    class="ml-1 text-[#d97706] underline transition hover:text-[#b45309]">Read our
                                    refund/return policy here</a>
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-bold text-[#1b1c1a]">Reason for Refund <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="refund_reason"
                                class="w-full rounded-md border border-[#E5E0DA] bg-white px-3 py-2 text-sm transition focus:border-[#d97706] focus:outline-none">
                                <option value="">Select a reason...</option>
                                <option value="Damaged Product">Damaged Product</option>
                                <option value="Wrong Product Received">Wrong Product Received</option>
                                <option value="Product Defect">Product Defect</option>
                                <option value="Quality Issue">Quality Issue</option>
                                <option value="Missing Item">Missing Item</option>
                                <option value="Delivery Delay">Delivery Delay</option>
                                <option value="Incorrect Product Description">Incorrect Product Description</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('refund_reason')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($refund_reason === 'Other')
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-bold text-[#1b1c1a]">Specific Reason <span
                                        class="text-red-500">*</span></label>
                                <textarea wire:model.defer="refund_custom_reason" rows="3"
                                    class="w-full resize-none rounded-md border border-[#E5E0DA] bg-white px-3 py-2 text-sm transition focus:border-[#d97706] focus:outline-none"
                                    placeholder="Please provide specific details about your refund request..."></textarea>
                                @error('refund_custom_reason')
                                    <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-bold text-[#1b1c1a]">Supporting Evidence
                                (Optional)</label>
                            <p class="mb-2 text-xs text-gray-500">Upload photos of the product, damage, or packaging to
                                help us process your request faster.</p>
                            <div
                                class="rounded-md border border-dashed border-[#E5E0DA] bg-[#FAFAFA] px-3 py-3 text-center">
                                <input type="file" wire:model="refund_evidence" multiple accept="image/*"
                                    class="w-full cursor-pointer text-sm transition file:mr-4 file:rounded-full file:border-0 file:bg-[#fef3c7] file:px-4 file:py-2 file:text-xs file:font-semibold file:text-[#d97706] hover:file:bg-[#fde68a]">
                                <div wire:loading wire:target="refund_evidence"
                                    class="mt-2 animate-pulse text-xs font-bold text-[#d97706]">Uploading files...
                                </div>
                            </div>
                            @error('refund_evidence.*')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror

                            @if (count($refund_evidence) > 0)
                                <div class="mt-2 text-xs font-medium text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 inline h-3 w-3"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ count($refund_evidence) }} file(s) attached
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[#E5E0DA] bg-[#FAFAFA] p-6">
                        <button type="button" wire:click="$set('refundModalOpen', false)"
                            class="cursor-pointer rounded-md border border-[#E5E0DA] bg-white px-4 py-2 text-sm font-bold text-[#1b1c1a] transition hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                            class="cursor-pointer rounded-md border border-[#d97706] bg-[#d97706] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#b45309]">Submit
                            Request</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- REVIEW MODAL --}}
    @if ($reviewModalOpen)
        <div x-data="{
            _wheelHandler: null,
            _touchHandler: null,
            _keyHandler: null,
            _blockKeys(e) {
                const scrollKeys = [32, 33, 34, 35, 36, 37, 38, 39, 40];
                if (scrollKeys.includes(e.keyCode)) {
                    const tag = document.activeElement && document.activeElement.tagName;
                    if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) {
                        e.preventDefault();
                    }
                }
            }
        }" x-init="const el = $el;
        this._wheelHandler = (e) => {
            if (!el.contains(e.target)) return;
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
        
        return () => {
            el.removeEventListener('wheel', this._wheelHandler, { capture: true });
            el.removeEventListener('touchmove', this._touchHandler, { capture: true });
            window.removeEventListener('keydown', this._keyHandler, { capture: true });
        }"
            class="fixed inset-0 z-[9999] flex w-screen items-center justify-center p-4 md:p-8">
            <div class="fixed inset-0 bg-[#2A211F] opacity-50" wire:click="$set('reviewModalOpen', false)"></div>
            <div
                class="animate-fade-in-up relative z-10 m-auto flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg bg-white shadow-xl">

                {{-- HEADER (Fixed) --}}
                <div class="relative flex-shrink-0 border-b border-[#E5E0DA] p-6 pb-4 md:p-8 md:pb-4">
                    @if ($isEditingReview)
                        <button wire:click="goBackToViewReview"
                            class="absolute left-6 top-6 z-20 cursor-pointer border-none bg-transparent text-gray-400 transition hover:text-[#800020]"
                            title="Back to View Review">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </button>
                    @endif
                    <button wire:click="$set('reviewModalOpen', false)"
                        class="absolute right-6 top-6 z-20 cursor-pointer border-none bg-transparent text-gray-400 transition hover:text-[#800020]"
                        title="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="text-center">
                        <h2 class="m-0 mb-2 font-serif text-2xl text-[#1b1c1a]">
                            {{ $isEditingReview ? 'Edit Your Review' : 'Rate Product' }}</h2>
                        <p class="m-0 text-sm text-gray-500">Tell us what you think!</p>
                    </div>
                </div>

                <form wire:submit.prevent="submitReview" class="flex flex-grow flex-col overflow-hidden">

                    {{-- BODY (Scrollable) --}}
                    <div class="hide-modal-scroll modal-inner-scroll flex-grow space-y-6 overflow-y-auto p-6 py-6 md:p-8"
                        style="scrollbar-width: none; -ms-overflow-style: none;">
                        <style>
                            .hide-modal-scroll::-webkit-scrollbar {
                                display: none;
                            }
                        </style>

                        <div>
                            <label
                                class="mb-2 block text-center text-[13px] font-bold uppercase tracking-wider text-gray-700">Rating</label>
                            <div class="flex justify-center gap-1" x-data="{ rating: @entangle('reviewRating').live, hoverRating: 0 }">
                                <template x-for="i in 5" :key="i">
                                    <button type="button" @click="rating = i" @mouseenter="hoverRating = i"
                                        @mouseleave="hoverRating = 0"
                                        class="cursor-pointer border-none bg-transparent transition-colors duration-150 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10"
                                            :class="(hoverRating >= i || (!hoverRating && rating >= i)) ?
                                            'text-yellow-500 fill-current' : 'text-gray-300 fill-current'"
                                            viewBox="0 0 24 24" stroke="none">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                            </polygon>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                            @error('reviewRating')
                                <span class="mt-1 block text-center text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="reviewComment" class="mb-2 block text-sm font-bold text-[#1b1c1a]">Your Review
                                (Optional)</label>
                            <textarea wire:model="reviewComment" id="reviewComment" rows="4"
                                class="w-full resize-none rounded-sm border border-[#E5E0DA] bg-[#FAFAFA] px-4 py-3 text-[14px] transition-colors focus:border-[#800020] focus:outline-none focus:ring-1 focus:ring-[#800020]"
                                placeholder="Write your experience..."></textarea>
                            @error('reviewComment')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[#1b1c1a]">Upload Photos (Optional)</label>
                            <div
                                class="rounded-md border border-dashed border-[#E5E0DA] bg-[#FAFAFA] px-3 py-3 text-center">
                                <input type="file" wire:key="review-photos-{{ $uploadIteration }}"
                                    wire:model="newPhotos" multiple accept="image/*"
                                    class="w-full cursor-pointer text-sm transition file:mr-4 file:rounded-full file:border-0 file:bg-[#F5F0EB] file:px-4 file:py-2 file:text-xs file:font-semibold file:text-[#800020] hover:file:bg-[#E5E0DA]">
                                <div wire:loading wire:target="newPhotos"
                                    class="mt-2 animate-pulse text-xs font-bold text-[#800020]">Uploading files...
                                </div>
                            </div>
                            @error('newPhotos.*')
                                <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>
                            @enderror

                            @if (count($existingPhotos) > 0 || count($reviewPhotos) > 0)
                                <div class="mt-3 flex flex-wrap gap-2">
                                    {{-- Existing Photos --}}
                                    @foreach ($existingPhotos as $index => $photo)
                                        <div
                                            class="group relative h-16 w-16 overflow-hidden rounded border border-[#E5E0DA]">
                                            <img src="{{ asset('storage/' . $photo) }}"
                                                class="h-full w-full object-cover">
                                            <button type="button"
                                                wire:click="removeExistingPhoto({{ $index }})"
                                                class="absolute right-0 top-0 rounded-bl bg-red-500 p-0.5 text-white opacity-0 transition group-hover:opacity-100"
                                                title="Remove image">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach

                                    {{-- New Uploads --}}
                                    @foreach ($reviewPhotos as $index => $photo)
                                        <div
                                            class="group relative h-16 w-16 overflow-hidden rounded border border-[#E5E0DA]">
                                            <img src="{{ $photo->temporaryUrl() }}"
                                                class="h-full w-full object-cover">
                                            <button type="button" wire:click="removeNewPhoto({{ $index }})"
                                                class="absolute right-0 top-0 rounded-bl bg-red-500 p-0.5 text-white opacity-0 transition group-hover:opacity-100"
                                                title="Remove image">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- FOOTER (Fixed) --}}
                    <div class="flex-shrink-0 border-t border-[#E5E0DA] p-6 pt-4 md:p-8 md:pt-4">
                        <div class="flex gap-4">
                            <button type="button" wire:click="$set('reviewModalOpen', false)"
                                class="flex-1 cursor-pointer rounded border border-[#E5E0DA] bg-white px-4 py-3 text-sm font-bold uppercase tracking-wider text-gray-600 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 cursor-pointer rounded border border-[#800020] bg-[#800020] px-4 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-md transition hover:bg-[#5D4037]">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- VIEW REVIEW MODAL --}}
    @if ($viewReviewModalOpen && $viewingReview)
        <div x-data="{
            _wheelHandler: null,
            _touchHandler: null,
            _keyHandler: null,
            _blockKeys(e) {
                const scrollKeys = [32, 33, 34, 35, 36, 37, 38, 39, 40];
                if (scrollKeys.includes(e.keyCode)) {
                    const tag = document.activeElement && document.activeElement.tagName;
                    if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) {
                        e.preventDefault();
                    }
                }
            }
        }" x-init="const el = $el;
        this._wheelHandler = (e) => {
            if (!el.contains(e.target)) return;
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
        
        return () => {
            el.removeEventListener('wheel', this._wheelHandler, { capture: true });
            el.removeEventListener('touchmove', this._touchHandler, { capture: true });
            window.removeEventListener('keydown', this._keyHandler, { capture: true });
        }"
            class="fixed inset-0 z-[9999] flex w-screen items-center justify-center p-4 md:p-8">
            <div class="fixed inset-0 bg-[#2A211F] opacity-50" wire:click="$set('viewReviewModalOpen', false)"></div>
            <div
                class="animate-fade-in-up relative z-10 m-auto flex max-h-[90vh] w-full max-w-2xl flex-col rounded-lg bg-white shadow-xl">

                {{-- HEADER (Fixed) --}}
                <div class="relative flex-shrink-0 border-b border-[#E5E0DA] p-6 pb-4 md:p-8 md:pb-4">
                    <button wire:click="$set('viewReviewModalOpen', false)"
                        class="absolute right-6 top-6 z-20 cursor-pointer border-none bg-transparent text-gray-400 transition hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h2 class="m-0 mb-2 font-serif text-xl text-[#1b1c1a]">Your Review</h2>
                    <div class="flex gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="{{ $i <= $viewingReview->rating ? 'text-yellow-500 fill-current' : 'text-gray-300 fill-current' }} h-6 w-6"
                                viewBox="0 0 24 24" stroke="none">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                </polygon>
                            </svg>
                        @endfor
                    </div>
                </div>

                {{-- BODY (Scrollable) --}}
                <div class="hide-modal-scroll modal-inner-scroll flex-grow overflow-y-auto p-6 py-6 md:p-8"
                    style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        .hide-modal-scroll::-webkit-scrollbar {
                            display: none;
                        }
                    </style>

                    @if ($viewingReview->comment)
                        <div class="mb-4">
                            <p class="m-0 break-words text-sm leading-relaxed text-gray-600"
                                style="font-family: 'Manrope', sans-serif;">
                                "{{ $viewingReview->comment }}"
                            </p>
                        </div>
                    @endif

                    @if (is_array($viewingReview->photos) && count($viewingReview->photos) > 0)
                        <div class="mb-4" x-data="{ localImageModalOpen: false, localModalImageSrc: '' }">
                            <span
                                class="mb-2 block text-[13px] font-bold uppercase tracking-wider text-gray-700">Attached
                                Photos</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($viewingReview->photos as $photo)
                                    <a href="#"
                                        @click.prevent="localModalImageSrc = '{{ asset('storage/' . $photo) }}'; localImageModalOpen = true"
                                        class="block h-20 w-20 overflow-hidden rounded border border-[#E5E0DA] transition hover:opacity-80">
                                        <img src="{{ asset('storage/' . $photo) }}"
                                            class="h-full w-full object-cover">
                                    </a>
                                @endforeach
                            </div>

                            {{-- Review Image Lightbox Modal --}}
                            <template x-teleport="body">
                                <div x-show="localImageModalOpen"
                                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                                    style="display: none;">
                                    <div class="absolute inset-0 bg-black opacity-80"
                                        @click="localImageModalOpen = false"></div>
                                    <div
                                        class="relative z-10 flex max-h-[90vh] w-full max-w-4xl flex-col items-center justify-center">
                                        <button @click="localImageModalOpen = false"
                                            class="absolute -top-10 right-0 cursor-pointer border-none bg-transparent text-white transition hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <img :src="localModalImageSrc"
                                            class="max-h-[85vh] max-w-full rounded object-contain shadow-2xl">
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endif

                    @if ($viewingReview->admin_reply)
                        <div class="mt-4 rounded-sm border-l-4 border-[#800020] bg-[#F5F0EB] p-4">
                            <span class="mb-1 block text-sm font-bold text-[#800020]">Response from Alpha
                                Digital</span>
                            <p class="m-0 break-words text-sm leading-relaxed text-gray-700"
                                style="font-family: 'Manrope', sans-serif;">
                                {{ $viewingReview->admin_reply }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- FOOTER (Fixed) --}}
                <div class="flex-shrink-0 border-t border-[#E5E0DA] p-6 pt-4 md:p-8 md:pt-4">
                    <div class="flex gap-4">
                        <button type="button" wire:click="editReview({{ $viewingReview->product_id }})"
                            class="flex-1 cursor-pointer rounded border border-[#E5E0DA] bg-white px-4 py-3 text-sm font-bold uppercase tracking-wider text-gray-600 transition hover:bg-gray-50">
                            Edit Review
                        </button>
                        <button type="button" wire:click="$set('viewReviewModalOpen', false)"
                            class="flex-1 cursor-pointer rounded border border-[#800020] bg-[#800020] px-4 py-3 text-sm font-bold uppercase tracking-wider text-white shadow-md transition hover:bg-[#5D4037]">
                            Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
