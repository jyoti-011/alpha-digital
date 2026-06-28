<x-filament-panels::page>

    @livewire(\App\Filament\Widgets\TopStatsWidget::class)

    <div class="mb-4 mt-8">
        @livewire(\App\Filament\Widgets\BookingsChartWidget::class)
    </div>

    <div class="mb-2 mt-8 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-[16px] font-bold uppercase tracking-wide text-gray-600">
            <x-heroicon-o-presentation-chart-line class="h-5 w-5 text-gray-400" />
            Booking Analytics
        </h2>
        <select
            class="focus:ring-primary-500 focus:border-primary-500 rounded-md border-gray-200 py-1.5 pl-3 pr-8 text-sm text-gray-600 shadow-sm">
            <option>All Time</option>
        </select>
    </div>
    @livewire(\App\Filament\Widgets\BookingAnalyticsWidget::class)

    <div class="mb-2 mt-8 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-[16px] font-bold uppercase tracking-wide text-gray-600">
            <x-heroicon-o-clock class="h-5 w-5 text-gray-400" />
            User, Queries, Reviews Analytics
        </h2>
        <select
            class="focus:ring-primary-500 focus:border-primary-500 rounded-md border-gray-200 py-1.5 pl-3 pr-8 text-sm text-gray-600 shadow-sm">
            <option>All Time</option>
        </select>
    </div>
    @livewire(\App\Filament\Widgets\UserAnalyticsWidget::class)

    <div class="mb-2 mt-8 flex items-center justify-start">
        <h2 class="flex items-center gap-2 text-[16px] font-bold uppercase tracking-wide text-gray-600">
            <x-heroicon-o-users class="h-5 w-5 text-gray-400" />
            Users Overview
        </h2>
    </div>
    @livewire(\App\Filament\Widgets\UsersOverviewWidget::class)

</x-filament-panels::page>
