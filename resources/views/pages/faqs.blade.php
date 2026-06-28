<x-layouts.app title="Frequently Asked Questions">
    <div
        class="mx-auto min-h-[60vh] max-w-4xl px-4 py-16 pt-[140px] font-sans text-on_surface sm:px-6 md:pt-[160px] lg:px-8">
        <h1 class="mb-12 text-center font-serif text-4xl font-bold uppercase tracking-wider text-secondary">Frequently
            Asked Questions</h1>

        <div class="space-y-6">
            @if (is_array($policy->faqs) && count($policy->faqs) > 0)
                @foreach ($policy->faqs as $faq)
                    <div class="rounded-sm border border-outline_variant/50 bg-surface_lowest p-6">
                        <h3 class="mb-2 font-serif text-lg font-bold text-secondary">{{ $faq['question'] }}</h3>
                        <p class="text-[15px] leading-relaxed text-tertiary">{{ $faq['answer'] }}</p>
                    </div>
                @endforeach
            @else
                <p>FAQs will appear here.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
