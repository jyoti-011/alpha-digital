<x-layouts.app>
    @php
        $siteSettings = \App\Models\Setting::getSiteSettings();
        $cSettings = $siteSettings->carousel_settings ?? [];
        $autoplayDelay = $cSettings['autoplay_speed'] ?? 6500;
        $transitionDuration = $cSettings['transition_speed'] ?? 700;
        $loop = ($cSettings['infinite_loop'] ?? true) ? 'true' : 'false';
        $pauseOnHover = ($cSettings['pause_on_hover'] ?? true) ? 'true' : 'false';
        $showPagination = $cSettings['show_pagination'] ?? true;
        $showNavigation = $cSettings['show_navigation'] ?? true;
    @endphp
    <section class="hero w-full relative overflow-hidden mt-[76px] h-[500px] md:h-[650px] lg:h-[700px]" 
             x-data="{
                 init() {
                     if(window.Swiper) {
                         this.initSwiper();
                     } else {
                         setTimeout(() => this.initSwiper(), 500); // Wait for vite to load
                     }
                 },
                 initSwiper() {
                     new window.Swiper('.swiper', {
                         effect: 'fade',
                         fadeEffect: { crossFade: true },
                         speed: {{ $transitionDuration }},
                         loop: {{ $loop }},
                         autoplay: {
                             delay: {{ $autoplayDelay }},
                             disableOnInteraction: false,
                             pauseOnMouseEnter: {{ $pauseOnHover }},
                         },
                         keyboard: { enabled: true },
                         @if($showNavigation)
                         navigation: {
                             nextEl: '.swiper-button-next',
                             prevEl: '.swiper-button-prev',
                         },
                         @endif
                         @if($showPagination)
                         pagination: {
                             el: '.swiper-pagination',
                             clickable: true,
                         },
                         @endif
                         on: {
                             slideChangeTransitionStart: function () {
                                 let slides = this.slides;
                                 for(let i=0; i<slides.length; i++) {
                                     let content = slides[i].querySelector('.hero-content');
                                     let img = slides[i].querySelector('img');
                                     if(content) content.classList.remove('animate-fade-up', 'animate-fade-left', 'animate-fade-right');
                                     if(img) img.classList.remove('animate-ken-burns');
                                 }
                             },
                             slideChangeTransitionEnd: function () {
                                 let activeSlide = this.slides[this.activeIndex];
                                 let content = activeSlide.querySelector('.hero-content');
                                 let img = activeSlide.querySelector('img');
                                 if(content) {
                                     let anim = content.dataset.animation || 'fade-up';
                                     if(anim !== 'none') content.classList.add('animate-' + anim);
                                 }
                                 if(img && img.dataset.kenburns === '1') {
                                     img.classList.add('animate-ken-burns');
                                 }
                             }
                         }
                     });
                 }
             }">
        
        <div class="swiper w-full h-full">
            <div class="swiper-wrapper">
                @foreach($carousels as $index => $carousel)
                    @php
                        $layout = $carousel->layout_settings ?? [];
                        $design = $carousel->design_settings ?? [];
                        $anim = $carousel->animation_settings ?? [];
                        
                        $desktopPos = $layout['desktop_position'] ?? 'left';
                        $mobilePos = $layout['mobile_position'] ?? 'center';
                        $textAlign = $layout['text_alignment'] ?? 'left';
                        $imgFocus = $layout['image_focus_position'] ?? 'center';
                        
                        // Justification classes
                        $justifyDesktop = $desktopPos === 'left' ? 'md:justify-start' : ($desktopPos === 'right' ? 'md:justify-end' : 'md:justify-center');
                        $justifyMobile = $mobilePos === 'left' ? 'justify-start' : ($mobilePos === 'right' ? 'justify-end' : 'justify-center');
                        
                        // Text alignment classes
                        $alignClass = $textAlign === 'left' ? 'text-left' : ($textAlign === 'right' ? 'text-right' : 'text-center');
                    @endphp
                    <div class="swiper-slide w-full h-full relative flex items-center px-6 md:px-[10%] {{ $justifyMobile }} {{ $justifyDesktop }}">
                        
                        <picture>
                            @if($carousel->image_mobile)
                            <source media="(max-width: 768px)" srcset="{{ asset('storage/' . $carousel->image_mobile) }}">
                            @endif
                            <img src="{{ asset('storage/' . $carousel->image) }}"
                                 alt="{{ $carousel->seo_alt_text ?? $carousel->heading ?? 'Alpha Digital Saree Collection' }}"
                                 @if($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                                 data-kenburns="{{ ($anim['ken_burns'] ?? true) ? '1' : '0' }}"
                                 class="absolute inset-0 w-full h-full object-cover z-0 {{ $index === 0 && ($anim['ken_burns'] ?? true) ? 'animate-ken-burns' : '' }}"
                                 style="object-position: {{ $imgFocus }};">
                        </picture>
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 z-[1]" 
                             style="background-color: {{ $design['overlay']['color'] ?? '#000000' }}; opacity: {{ $design['overlay']['opacity'] ?? 0.22 }}"></div>
                        
                        <!-- Content -->
                        <div class="hero-content relative z-[2] w-full transition-all flex flex-col gap-3 {{ $alignClass }} {{ $index === 0 ? 'animate-'.($anim['type'] ?? 'fade-up') : 'opacity-0' }} max-w-[600px]"
                             data-animation="{{ $anim['type'] ?? 'fade-up' }}"
                             style="animation-delay: {{ $anim['delay'] ?? 200 }}ms; animation-duration: {{ $anim['duration'] ?? 700 }}ms;">
                            
                            @if($carousel->collection_tag)
                                <p class="uppercase font-bold tracking-widest m-0" 
                                   style="color: {{ $design['text']['body_color'] ?? '#F5F5F5' }}; font-size: {{ $layout['tag_size'] ?? 16 }}px;">
                                    {{ $carousel->collection_tag }}
                                </p>
                            @endif

                            @if($carousel->heading)
                                <h1 class="font-serif leading-tight drop-shadow-md m-0"
                                    style="color: {{ $design['text']['heading_color'] ?? '#FFFFFF' }}; font-size: clamp(32px, 5vw, {{ $layout['heading_size'] ?? 60 }}px);">
                                    {{ $carousel->heading }}
                                </h1>
                            @endif

                            @if($carousel->sub_heading)
                                <p class="font-sans leading-snug tracking-wide m-0"
                                   style="color: {{ $design['text']['body_color'] ?? '#F5F5F5' }}; font-size: clamp(16px, 3vw, {{ $layout['subtitle_size'] ?? 22 }}px);">
                                    {{ $carousel->sub_heading }}
                                </p>
                            @endif

                            @if($carousel->button_text && $carousel->button_link)
                                @php
                                    $btnStyle = $design['button']['style'] ?? 'filled';
                                    $btnBg = $btnStyle === 'filled' ? ($design['button']['bg'] ?? '#FFFFFF') : 'transparent';
                                    $btnText = $design['button']['text'] ?? '#000000';
                                    $btnBorder = $btnStyle === 'outline' ? ($design['button']['border'] ?? '#FFFFFF') : 'transparent';
                                    $btnRadius = $design['button']['radius'] ?? 0;
                                    $btnSize = $design['button']['size'] ?? 'md';
                                    $btnPadding = $btnSize === 'sm' ? 'py-2 px-6' : ($btnSize === 'lg' ? 'py-4 px-12 text-lg' : 'py-3 px-8');
                                    $btnWidth = $design['button']['width'] ?? 'auto';
                                    
                                    $wrapperWidthClass = $btnWidth === 'full' ? 'w-full md:w-auto' : 'w-auto';
                                    $btnWidthClass = $btnWidth === 'full' ? 'w-full block text-center md:inline-block md:w-auto' : 'inline-block w-auto text-center';
                                @endphp
                                <div class="mt-4 flex {{ $textAlign === 'center' ? 'justify-center' : ($textAlign === 'right' ? 'justify-end' : 'justify-start') }} {{ $wrapperWidthClass }}">
                                    <a href="{{ $carousel->button_link }}" 
                                       class="no-underline font-sans font-bold uppercase tracking-[2px] transition-all duration-300 {{ $btnPadding }} {{ $btnWidthClass }}"
                                       style="background-color: {{ $btnBg }}; color: {{ $btnText }}; border: 1.5px solid {{ $btnBorder }}; border-radius: {{ $btnRadius }}px;"
                                       onmouseover="this.style.backgroundColor='{{ $design['button']['hover_color'] ?? '#EEEEEE' }}'; this.style.color='{{ $btnText }}';"
                                       onmouseout="this.style.backgroundColor='{{ $btnBg }}'; this.style.color='{{ $btnText }}';">
                                        {{ $carousel->button_text }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                @if($carousels->count() === 0)
                    <div class="swiper-slide w-full h-full flex items-center justify-center bg-black">
                        <img src="{{ asset('images/carousel1.webp') }}"
                             alt="Welcome to Our Store" 
                             class="absolute inset-0 w-full h-full object-cover z-0 opacity-80">
                        <div class="relative z-[2] text-white text-center">
                            <h1 class="text-4xl md:text-5xl font-serif">Alpha Digital</h1>
                        </div>
                    </div>
                @endif
            </div>

            @if($carousels->count() > 1)
                @if($showNavigation)
                <!-- Navigation -->
                <div class="swiper-button-next hidden sm:flex"></div>
                <div class="swiper-button-prev hidden sm:flex"></div>
                @endif
                
                @if($showPagination)
                <!-- Pagination -->
                <div class="swiper-pagination mb-2"></div>
                @endif
            @endif
        </div>
    </section>

    <section class="content-section bg-neutral">
        <div class="section-header">
            <div>
                <p class="subtitle">TIMELESS FAVORITES</p>
                <h2>Best Sellers</h2>
            </div>
            <a href="{{ route('shop.index', ['filter' => 'best_seller']) }}" class="view-all">EXPLORE ALL</a>
        </div>
        <div class="product-grid">
            @forelse($bestSellers as $product)
                <x-product-card :product="$product" :inlinePricing="true" />
            @empty
                <p class="text-gray-500 italic col-span-full">Add products to your admin panel and mark them as "Best Seller" to see them here.</p>
            @endforelse
        </div>
    </section>

    <section class="content-section bg-white">
        <div class="section-header">
            <div>
                <p class="subtitle">THE NEW ETHEREAL</p>
                <h2>Latest Collection</h2>
            </div>
            <a href="{{ route('shop.new-arrival') }}" class="view-all">VIEW COLLECTION</a>
        </div>
        <div class="product-grid">
            @forelse($latestCollection as $product)
                <x-product-card :product="$product" :inlinePricing="true" />
            @empty
                <p class="text-gray-500 italic col-span-full">Add products to your admin panel and mark them as "New Arrival" to see them here.</p>
            @endforelse
        </div>
    </section>

    <section id="fabrics" class="content-section bg-neutral">
        <div class="section-header">
            <div>
                <p class="subtitle">OUR TEXTURED HERITAGE</p>
                <h2>Fabrics</h2>
            </div>
        </div>

        @php
            // Fetch exactly up to 4 fabrics that are marked as featured AND have an image uploaded
            $featuredFabrics = \App\Models\Fabric::where('is_featured', true)
                                ->whereNotNull('image')
                                ->take(4)
                                ->get();
        @endphp

        @if($featuredFabrics->count() > 0)
            <div class="fabrics-grid">
                
                @if(isset($featuredFabrics[0]))
                    <div class="fab-large">
                        <div class="fab-img" style="background-image: url('{{ asset('storage/' . $featuredFabrics[0]->image) }}');">
                            <a href="{{ route('shop.index', ['selectedFabrics' => [$featuredFabrics[0]->id]]) }}" class="label" style="text-decoration: none; display: inline-block;">{{ strtoupper($featuredFabrics[0]->name) }}</a>
                        </div>
                    </div>
                @endif

                <div class="fab-sidebar">
                    
                    @if(isset($featuredFabrics[1]))
                        @php $fab1Url = asset('storage/' . $featuredFabrics[1]->image); @endphp
                        <div class="fab-img" style="background-image: url('{{ $fab1Url }}');">
                            <a href="{{ route('shop.index', ['selectedFabrics' => [$featuredFabrics[1]->id]]) }}" class="label" style="text-decoration: none; display: inline-block;">{{ strtoupper($featuredFabrics[1]->name) }}</a>
                        </div>
                    @endif

                    <div class="fab-bottom-row">
                        @if(isset($featuredFabrics[2]))
                            <div class="fab-img" style="background-image: url('{{ asset('storage/' . $featuredFabrics[2]->image) }}');">
                                <a href="{{ route('shop.index', ['selectedFabrics' => [$featuredFabrics[2]->id]]) }}" class="label" style="text-decoration: none; display: inline-block;">{{ strtoupper($featuredFabrics[2]->name) }}</a>
                            </div>
                        @endif
                        
                        @if(isset($featuredFabrics[3]))
                            <div class="fab-img" style="background-image: url('{{ asset('storage/' . $featuredFabrics[3]->image) }}');">
                                <a href="{{ route('shop.index', ['selectedFabrics' => [$featuredFabrics[3]->id]]) }}" class="label" style="text-decoration: none; display: inline-block;">{{ strtoupper($featuredFabrics[3]->name) }}</a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @else
            <p class="text-gray-500 italic text-center py-10">Add fabric images in the admin panel and mark them as "Feature on Homepage" to see them here.</p>
        @endif
    </section>

    <section class="heritage-crafted">
        <div class="heritage-container">
            <div class="heritage-text">
                <p class="subtitle">Established With 20 Years Of Experience</p>
                <h2>Crafting designs that bring brands to life.</h2>
                <p class="heritage-description">
                    Built on two decades of expertise, Alpha Digital combines creativity, advanced digital printing, and skilled craftsmanship to deliver exceptional results. Led by experienced partners and powered by in-house production, we ensure every project reflects quality, precision, and innovation from concept to completion.
                </p>
                <div class="heritage-cta">
                    <a href="{{ route('shop.about') }}" wire:navigate class="btn-heritage inline-block text-center" style="line-height: inherit; text-decoration: none;">OUR JOURNEY</a>
                    <div class="craft-mark">
                        <span class="gold-text">Made for Elegance</span>
                    </div>
                </div>
            </div>

            <div class="heritage-visual">
                <div class="main-image-frame">
                    <img src="{{ asset('images/heritage-main-opt.webp') }}" class="heritage-main-img" alt="Butterfly Saree">
                </div>
                <div class="overlap-image-frame">
                    <img src="{{ asset('images/heritage-sub.webp') }}" class="heritage-sub-img" alt="Butterfly Fabric Detail">
                </div>
                <div class="heritage-accent-box"></div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <div class="contact-container">
            <div class="contact-grid">
                
                @php
                    $settings = \App\Models\Setting::getSiteSettings();
                @endphp
                
                <div class="contact-info">
                    <div class="contact-underlap">CONTACT</div>
                    
                    <p class="subtitle">GET IN TOUCH</p>
                    <h2>We'd love to hear from you.</h2>
                    
                    <div class="contact-details">
                        @if($settings && $settings->contact_address)
                            <div class="detail-item">
                                <h4>Our Address</h4>
                                <p>{!! nl2br(e($settings->contact_address)) !!}</p>
                            </div>
                        @endif
                        
                        @if($settings && ($settings->contact_email || $settings->contact_phone))
                            <div class="detail-item">
                                <h4>Contact Us</h4>
                                <p>
                                    @if($settings->contact_email)
                                        {{ $settings->contact_email }}
                                    @endif
                                    
                                    @if($settings->contact_email && $settings->contact_phone)
                                        <br>
                                    @endif
                                    
                                    @if($settings->contact_phone)
                                        {{ $settings->contact_phone }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    
                </div>

<!-- Contact Form Livewire Component -->
                <livewire:contact-form />

            </div>
        </div>
    </section>

    {{-- Carousel script removed as it is now handled by Alpine.js --}}
</x-layouts.app>