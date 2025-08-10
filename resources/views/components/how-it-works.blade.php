<section id="how-it-works" class="py-20 mt-5  bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center lg:text-left mb-16">
            How It Works
        </h2>
        
        <div class="flex flex-col lg:flex-row items-center justify-between space-y-12 lg:space-y-0 lg:space-x-8">
            @php
                $steps = [
                    [
                        'number' => 1,
                        'title' => 'Upload',
                        'subtitle' => 'a Selfie',
                        'description' => 'Simply upload a selfie to get started with our advanced face recognition technology.',
                    ],
                    [
                        'number' => 2,
                        'title' => 'View',
                        'subtitle' => 'Your Photos',
                        'description' => 'Browse through all the photos where you appear, organized and grouped automatically.',
                    ],
                    [
                        'number' => 3,
                        'title' => 'Download or',
                        'subtitle' => 'Order Merchandise',
                        'description' => 'Download your photos or order custom merchandise with your favorite memories.',
                    ],
                ];
            @endphp

            @foreach ($steps as $index => $step)
                <div class="flex-1 text-center group">
                    <div class="relative mb-6">
                        <div class="bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold mx-auto mb-4 group-hover:bg-blue-700 transition-colors duration-300">
                            {{ $step['number'] }}
                        </div>
                        <div class="bg-blue-50 rounded-2xl p-6 group-hover:bg-blue-100 transition-colors duration-300">
                            @if ($step['number'] == 1)
                                <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            @elseif ($step['number'] == 2)
                                <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z M9 10h.01M15 10h.01" />
                                </svg>
                            @elseif ($step['number'] == 3)
                                <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $step['title'] }}</h3>
                    <h4 class="text-lg text-blue-600 font-semibold mb-3">{{ $step['subtitle'] }}</h4>
                    <p class="text-gray-600 leading-relaxed">{{ $step['description'] }}</p>
                </div>

                @if ($index < count($steps) - 1)
                    <div class="hidden lg:block">
                        <svg class="h-8 w-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>