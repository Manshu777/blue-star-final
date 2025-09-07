<section class="py-20 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="mx-auto max-w-7xl px-6">
        <!-- Section Header -->
        <div class="pb-16 text-center">
            <span class="inline-flex items-center px-5 py-2 rounded-full bg-indigo-100 text-indigo-800 text-sm font-semibold mb-4 shadow-sm">
                📸 Memories in Motion
            </span>
            <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4">
                Our Gallery
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Explore the beauty, creativity, and special moments captured in our curated collection of images & videos.
            </p>
        </div>

        <!-- Swiper Container -->
        <div class="relative">
            <div class="swiper gallery-swiper" id="gallery-swiper">
                <div class="swiper-wrapper">
                    @foreach ($mediaItems as $item)
                        <div class="swiper-slide">
                            <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 group bg-white">
                                @if ($item->type === 'video')
                                    <video
                                        src="{{ asset('storage/' . $item->file_path) }}"
                                        autoplay
                                        loop
                                        muted
                                        playsinline
                                        class="w-full h-[350px] object-cover group-hover:scale-105 transition-transform duration-500"
                                    ></video>
                                @else
                                    <img
                                        src="{{ asset('storage/' . $item->file_path) }}"
                                        alt="{{ $item->title ?? 'Gallery Item' }}"
                                        class="w-full h-[350px] object-cover group-hover:scale-105 transition-transform duration-500"
                                    />
                                @endif
                                <div class="p-5 text-center">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $item->title ?? 'Untitled' }}</h3>
                                    <p class="text-gray-500 text-sm mt-1">
                                        {{ $item->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Controls -->
                <div class="swiper-pagination mt-6"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('#gallery-swiper', {
                slidesPerView: 3,
                spaceBetween: 24,
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    320: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1280: { slidesPerView: 3 },
                },
            });
        });
    </script>
@endpush
