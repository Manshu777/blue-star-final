<section class="py-24 bg-gradient-to-br from-indigo-50 via-white to-purple-50 relative">
    <!-- Section Heading -->
    <div class="text-center mb-16">
        <span
            class="inline-flex items-center px-5 py-2 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-5 shadow-sm">
            ❤️ Customer Stories
        </span>
        <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4">
            Loved by Thousands
        </h2>
        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">
            Real stories from real customers sharing their
            <span class="font-semibold text-indigo-600">Blue Star Memory</span> experience.
        </p>
    </div>

    <!-- Slider Wrapper -->
    <div class="relative max-w-7xl mx-auto px-8 flex items-center">
        <!-- Left Button -->
        <button id="prevBtn"
            class="absolute -left-4 md:-left-10 z-10 p-3 md:p-4 rounded-full bg-white shadow-lg hover:bg-indigo-100 hover:scale-110 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Scrollable Slider -->
        <style>
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }
        </style>
        <div id="testimonial-slider"
            class="flex gap-8 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-6 no-scrollbar">
            @foreach ($testimonials as $testimonial)
                <div
                    class="min-w-[320px] max-w-sm flex-shrink-0 bg-white rounded-2xl shadow-md hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 snap-start border border-gray-100">
                    <div class="p-8">
                        <!-- Avatar + Name -->
                        <div class="flex items-center space-x-4 mb-6">
                            @php
                                $media = $testimonial->image ?? null;
                                $isVideo = $media && preg_match('/\.(mp4|mov|avi|webm)$/i', $media);
                                $mediaPath = $media
                                    ? (Str::startsWith($media, ['http://', 'https://'])
                                        ? $media
                                        : asset('storage/' . $media))
                                    : 'https://via.placeholder.com/100x100.png?text=User';
                            @endphp

                            @if ($isVideo)
                                <video class="w-16 h-16 rounded-full object-cover border-4 border-indigo-100" autoplay muted
                                    loop>
                                    <source src="{{ $mediaPath }}" type="video/mp4">
                                </video>
                            @else
                                <img src="{{ $mediaPath }}" alt="{{ $testimonial->name }}"
                                    class="w-16 h-16 rounded-full object-cover border-4 border-indigo-100" />
                            @endif

                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">{{ $testimonial->name }}</h4>
                                <p class="text-gray-500 text-sm">{{ $testimonial->location ?? 'Verified Customer' }}</p>
                            </div>
                        </div>

                        <!-- Review -->
                        <p class="text-gray-700 text-base leading-relaxed italic mb-4">
                            "{{ \Illuminate\Support\Str::limit(strip_tags($testimonial->comment), 120) }}"
                        </p>

                        <!-- Stars -->
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'fill-current' : 'text-gray-300' }}"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Right Button -->
        <button id="nextBtn"
            class="absolute -right-4 md:-right-10 z-10 p-3 md:p-4 rounded-full bg-white shadow-lg hover:bg-indigo-100 hover:scale-110 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const slider = document.getElementById('testimonial-slider');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        function scrollByAmount(amount) {
            slider.scrollBy({ left: amount, behavior: 'smooth' });
        }

        prevBtn.addEventListener('click', () => scrollByAmount(-350));
        nextBtn.addEventListener('click', () => scrollByAmount(350));

        // Auto scroll every 5s
        setInterval(() => {
            if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth) {
                slider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                scrollByAmount(350);
            }
        }, 5000);
    });
</script>