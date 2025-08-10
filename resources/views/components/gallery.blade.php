<section class="py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="pb-16">
            <h2 class="w-full text-center text-gray-900 text-4xl font-bold leading-loose pb-2.5">
                Our Gallery
            </h2>
            <p class="w-full text-center text-gray-600 text-lg font-normal leading-8">
                Explore the essence of beauty in our gallery&apos;s intimate space.
            </p>
        </div>
        <div class="mx-auto w-auto relative">
            <div class="swiper gallery-top pt-6" id="gallery-swiper">
                <div class="swiper-wrapper">
                    @php
                        $mediaItems = [
                            [
                                'type' => 'image',
                                'src' => asset('gallery/img-1.jpeg'),
                                'title' => 'Image 1',
                                'description' => 'A captivating moment captured in time.',
                            ],
                            [
                                'type' => 'image',
                                'src' => asset('gallery/img-2.jpeg'),
                                'title' => 'Image 2',
                                'description' => 'A vibrant scene full of energy.',
                            ],
                            [
                                'type' => 'image',
                                'src' => asset('gallery/img-3.jpeg'),
                                'title' => 'Image 3',
                                'description' => 'A serene and elegant composition.',
                            ],
                            [
                                'type' => 'image',
                                'src' => asset('gallery/img-4.jpeg'),
                                'title' => 'Image 4',
                                'description' => 'A bold and striking visual.',
                            ],
                            [
                                'type' => 'image',
                                'src' => asset('gallery/img-5.jpeg'),
                                'title' => 'Image 5',
                                'description' => 'A timeless snapshot of beauty.',
                            ],
                            [
                                'type' => 'video',
                                'src' => asset('gallery/vid-1.mp4'),
                                'title' => 'Video 1',
                                'description' => 'A dynamic video showcasing motion.',
                            ],
                            [
                                'type' => 'video',
                                'src' => asset('gallery/vid-2.mp4'),
                                'title' => 'Video 2',
                                'description' => 'An engaging clip with vivid details.',
                            ],
                        ];
                    @endphp

                    @foreach ($mediaItems as $index => $item)
                        <div class="swiper-slide grid">
                            @if ($item['type'] === 'video')
                                <video
                                    src="{{ $item['src'] }}"
                                    autoplay
                                    loop
                                    muted
                                    playsinline
                                    class="w-full h-[400px] rounded-xl object-cover mx-auto"
                                ></video>
                            @else
                                <a >
                                    <img
                                        src="{{ $item['src'] }}"
                                        alt="{{ $item['title'] }}"
                                        class="w-full h-[400px] rounded-xl object-cover mx-auto"
                                    />
                                </a>
                            @endif
                            <div class="mt-4 text-center">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $item['title'] }}</h3>
                                <p class="text-gray-600 text-sm">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-4"></div>
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
            if (typeof Swiper !== 'undefined') {
                new Swiper('#gallery-swiper', {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-prev',
                        prevEl: '.swiper-button-next',
                    },
                    breakpoints: {
                        320: { slidesPerView: 1 },
                        1024: { slidesPerView: 2 },
                        1280: { slidesPerView: 3 },
                    },
                });
            } else {
                console.error('Swiper is not defined. Ensure the Swiper library is loaded.');
            }
        });
    </script>
@endpush