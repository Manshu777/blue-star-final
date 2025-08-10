<section class="py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-12 lg:mb-16">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-3 sm:mb-4 tracking-tight">
                Our Customers Love Us
            </h2>
            <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-3xl mx-auto">
                Join thousands of satisfied customers who trust us for their printing needs
            </p>
        </div>

        <div class="relative">
            <div class="swiper testimonials-swiper">
                <div class="swiper-wrapper">
                    @php
                        $testimonials = [
                            [
                                'name' => 'Henry D.',
                                'image' => 'https://images.pexels.com/photos/1043473/pexels-photo-1043473.jpeg?auto=compress&cs=tinysrgb&w=150',
                                'text' => 'Great way to relive amazing moments. The photo quality was fantastic and ordering a custom mug was a breeze.',
                                'rating' => 5,
                                'role' => 'Photographer',
                            ],
                            [
                                'name' => 'Katherine S.',
                                'image' => 'https://images.pexels.com/photos/1065084/pexels-photo-1065084.jpeg?auto=compress&cs=tinysrgb&w=150',
                                'text' => 'Absolutely love the experience! Found all my pictures easily and the t-shirt I ordered turned out perfectly.',
                                'rating' => 5,
                                'role' => 'Graphic Designer',
                            ],
                            [
                                'name' => 'Michael R.',
                                'image' => 'https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&w=150',
                                'text' => 'The customization options are incredible. My canvas print looks amazing on my wall!',
                                'rating' => 4,
                                'role' => 'Art Director',
                            ],
                            [
                                'name' => 'Sarah L.',
                                'image' => 'https://images.pexels.com/photos/415071/pexels-photo-415071.jpeg?auto=compress&cs=tinysrgb&w=150',
                                'text' => 'Fast delivery and excellent customer service. My photo book is a cherished keepsake.',
                                'rating' => 5,
                                'role' => 'Teacher',
                            ],
                            [
                                'name' => 'James T.',
                                'image' => 'https://images.pexels.com/photos/91227/pexels-photo-91227.jpeg?auto=compress&cs=tinysrgb&w=150',
                                'text' => 'The quality of the prints exceeded my expectations. Will definitely order again!',
                                'rating' => 4,
                                'role' => 'Marketing Manager',
                            ],
                        ];
                    @endphp

                    @foreach ($testimonials as $index => $testimonial)
                        <div class="swiper-slide px-2 sm:px-3 lg:px-4">
                            <div class="bg-white rounded-2xl p-5 sm:p-6 lg:p-6 shadow-lg border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 h-full">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <img
                                        src="{{ $testimonial['image'] }}"
                                        alt="{{ $testimonial['name'] }}"
                                        class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full object-cover flex-shrink-0 border-2 border-indigo-200 shadow-sm"
                                    />
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2 sm:mb-3">
                                            @for ($i = 0; $i < $testimonial['rating']; $i++)
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-700 leading-relaxed mb-3 sm:mb-4 text-sm sm:text-base lg:text-base italic">
                                            "{{ $testimonial['text'] }}"
                                        </p>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-base sm:text-lg">{{ $testimonial['name'] }}</p>
                                            <p class="text-xs sm:text-sm text-gray-500">{{ $testimonial['role'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-6 sm:mt-8"></div>
                <div class="swiper-button-prev hidden sm:block"></div>
                <div class="swiper-button-next hidden sm:block"></div>
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
                new Swiper('.testimonials-swiper', {
                    slidesPerView: 3,
                    spaceBetween: 16,
                    loop: true,
                    autoplay: {
                        delay: 5000,
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
                        1024: { slidesPerView: 3 },
                    },
                });
            } else {
                console.error('Swiper is not defined. Ensure the Swiper library is loaded.');
            }
        });
    </script>
@endpush