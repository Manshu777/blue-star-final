<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <span class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 text-indigo-800 text-sm font-medium mb-6 shadow-sm">
                ⭐ Powerful Features
            </span>
            <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-6">
                Revolutionary Photo Management
            </h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Discover cutting-edge features designed to transform how you organize, 
                enhance, and share your precious memories.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center max-w-7xl mx-auto">
            <!-- Left: Features List -->
            <div class="space-y-8">
                @php
                    $features = [
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h2l.4 2M7 7h10l1.4 7H7.4L6 7H3z"/></svg>',
                            'title' => 'AI Facial Recognition',
                            'description' => 'Advanced AI technology that instantly identifies and tags people in photos with 99.9% accuracy.',
                            'gradient' => 'from-blue-500 to-cyan-500'
                        ],
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l-4-4m0 0l4-4m-4 4h18"/></svg>',
                            'title' => 'Smart Search Engine',
                            'description' => 'Find any photo in seconds using natural language, facial recognition, or metadata filters.',
                            'gradient' => 'from-purple-500 to-pink-500'
                        ],
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h6M9 4a4 4 0 100 8 4 4 0 000-8z"/></svg>',
                            'title' => 'Intelligent Grouping',
                            'description' => 'Automatically organize photos by events, locations, people, and time with AI-powered categorization.',
                            'gradient' => 'from-green-500 to-teal-500'
                        ],
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"/></svg>',
                            'title' => 'AI Enhancement Suite',
                            'description' => 'Professional-grade editing tools with AI-powered enhancements, filters, and background removal.',
                            'gradient' => 'from-orange-500 to-red-500'
                        ]
                    ];
                @endphp

                @foreach ($features as $index => $feature)
                    <div
                        class="p-8 rounded-3xl border-2 transition-all duration-500 cursor-pointer 
                            border-gray-100 bg-white hover:border-indigo-200 hover:shadow-xl hover:scale-[1.02]">
                        <div class="flex items-start space-x-6">
                            <div class="w-16 h-16 bg-gradient-to-r {{ $feature['gradient'] }} rounded-2xl flex items-center justify-center text-white shadow-lg">
                                {!! $feature['icon'] !!}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                                <p class="text-gray-600 text-lg leading-relaxed">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right: Highlight Card -->
            <div class="relative">
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-8 shadow-2xl">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl p-8 text-white">
                        <h3 class="text-3xl font-bold mb-6">Experience the Magic</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-lg">Instant photo recognition</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-lg">Smart auto-organization</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-lg">Professional editing tools</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-lg">Secure cloud storage</span>
                            </div>
                        </div>
                        <a href="{{ url('/features') }}"
                            class="inline-flex items-center mt-8 bg-white text-blue-600 hover:bg-gray-100 font-semibold px-8 py-3 rounded-xl shadow-md transition">
                            Explore All Features
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
