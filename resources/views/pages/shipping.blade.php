<x-layouts.app title="Shipping Policy">
    <div
        class="mx-auto min-h-[60vh] max-w-4xl px-4 py-16 pt-[140px] font-sans text-on_surface sm:px-6 md:pt-[160px] lg:px-8">
        <h1 class="mb-12 text-center font-serif text-4xl font-bold uppercase tracking-wider text-secondary">Shipping
            Policy</h1>

        <div class="policy-content">
            {!! $policy->shipping_policy ?? '<p>Shipping policy will appear here.</p>' !!}
        </div>
    </div>
</x-layouts.app>
