@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-violet-50 antialiased">
    <!-- Hero Section -->
    <section class="py-24 lg:py-40">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-5xl mx-auto">
                <span class="inline-flex items-center mb-8 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-medium px-6 py-3 rounded-full shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.98 2.89a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.98-2.89c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    About Blue Star Memory
                </span>
                <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 mb-8 tracking-tight">
                    Revolutionizing Photo Management
                    <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent"> with AI</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-16 leading-relaxed max-w-3xl mx-auto">
                    We're on a mission to help people rediscover and cherish their memories through 
                    cutting-edge artificial intelligence and intuitive design.
                </p>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                    @foreach([
                        ['number' => '2025', 'label' => 'Founded', 'icon' => 'rocket'],
                        ['number' => '50K+', 'label' => 'Users Worldwide', 'icon' => 'users'],
                        ['number' => '10M+', 'label' => 'Photos Processed', 'icon' => 'camera'],
                        ['number' => '99.9%', 'label' => 'Uptime', 'icon' => 'shield'],
                    ] as $stat)
                        <div class="text-center p-6 bg-white/80 backdrop-blur-md rounded-3xl border border-white/30 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="w-14 h-14 bg-gradient-to-r from-indigo-500 to-violet-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-white">
                                @if($stat['icon'] == 'rocket')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                @elseif($stat['icon'] == 'users')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                @elseif($stat['icon'] == 'camera')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                @elseif($stat['icon'] == 'shield')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                @endif
                            </div>
                            <div class="text-4xl font-bold text-gray-900 mb-2">{{ $stat['number'] }}</div>
                            <div class="text-base text-gray-600 font-medium">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center max-w-7xl mx-auto">
                <div class="order-2 lg:order-1">
                    <span class="inline-flex items-center mb-6 bg-indigo-100 text-indigo-800 font-medium px-4 py-2 rounded-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        Our Mission
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 tracking-tight">
                        Making Memories Accessible to Everyone
                    </h2>
                    <p class="text-lg md:text-xl text-gray-600 mb-10 leading-relaxed">
                        We believe that every photo tells a story, and every memory deserves to be easily found and cherished. 
                        Our mission is to harness the power of artificial intelligence to create the most intuitive and 
                        powerful photo management platform in the world.
                    </p>
                    <div class="space-y-6">
                        @foreach([
                            ['title' => 'Democratize AI Technology', 'desc' => 'Make advanced AI accessible to everyone, not just tech experts.'],
                            ['title' => 'Preserve Digital Memories', 'desc' => 'Help people organize and preserve their most precious moments.'],
                            ['title' => 'Connect Through Stories', 'desc' => 'Enable people to share and connect through their visual stories.'],
                        ] as $point)
                            <div class="flex items-start space-x-4">
                                <svg class="w-7 h-7 text-emerald-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <h4 class="font-semibold text-xl text-gray-900">{{ $point['title'] }}</h4>
                                    <p class="text-gray-600">{{ $point['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative order-1 lg:order-2">
                    <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-10 text-white shadow-2xl transform hover:scale-105 transition-transform duration-500">
                        <svg class="w-20 h-20 mb-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <h3 class="text-3xl font-bold mb-6">Our Vision</h3>
                        <p class="text-indigo-100 text-lg md:text-xl leading-relaxed mb-8">
                            To become the world's leading platform for AI-powered photo organization, 
                            where finding and sharing memories is as natural as taking the photo itself.
                        </p>
                        <div class="space-y-4">
                            @foreach(['Global reach and impact', 'Continuous innovation', 'User-first approach'] as $star)
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-lg">{{ $star }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-indigo-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center mb-6 bg-violet-100 text-violet-800 font-medium px-4 py-2 rounded-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Our Values
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">
                    What Drives Us Forward
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto">
                    Our core values guide every decision we make and every feature we build
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 max-w-6xl mx-auto">
                @foreach([
                    ['icon' => 'eye', 'title' => 'Innovation First', 'desc' => 'We push the boundaries of AI technology to create breakthrough solutions for photo management.', 'gradient' => 'from-blue-500 to-cyan-500'],
                    ['icon' => 'shield', 'title' => 'Privacy & Security', 'desc' => 'Your memories are precious. We protect them with enterprise-grade security and privacy controls.', 'gradient' => 'from-green-500 to-teal-500'],
                    ['icon' => 'heart', 'title' => 'User-Centric Design', 'desc' => 'Every feature is designed with our users in mind, ensuring intuitive and delightful experiences.', 'gradient' => 'from-purple-500 to-pink-500'],
                    ['icon' => 'globe', 'title' => 'Global Accessibility', 'desc' => 'We believe everyone should have access to powerful photo organization tools, regardless of location.', 'gradient' => 'from-orange-500 to-red-500'],
                ] as $value)
                    <div class="bg-white border-0 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden rounded-3xl">
                        <div class="p-8 md:p-10">
                            <div class="flex items-start space-x-6">
                                <div class="w-16 h-16 bg-gradient-to-r {{ $value['gradient'] }} rounded-2xl flex items-center justify-center text-white flex-shrink-0 shadow-lg">
                                    @if($value['icon'] == 'eye')
                                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    @elseif($value['icon'] == 'shield')
                                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                    @elseif($value['icon'] == 'heart')
                                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    @elseif($value['icon'] == 'globe')
                                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 002 2 2 2 0 012 2v.5a2.5 2.5 0 002.5 2.5h1.5a2 2 0 002 2 2 2 0 012 2v.945M4 20h16M4 4h16"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $value['title'] }}</h3>
                                    <p class="text-gray-600 text-lg md:text-xl leading-relaxed">{{ $value['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center mb-6 bg-emerald-100 text-emerald-800 font-medium px-4 py-2 rounded-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Our Team
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">
                    Meet the Innovators
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto">
                    Our diverse team of experts brings together decades of experience in AI, design, and technology
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-10 max-w-7xl mx-auto">
                @foreach([
                    ['name' => 'Sarah Johnson', 'role' => 'CEO & Co-Founder', 'bio' => 'Former Google AI researcher with 10+ years in computer vision and machine learning.', 'image' => 'https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=300', 'expertise' => ['AI Strategy', 'Product Vision', 'Team Leadership']],
                    ['name' => 'Michael Chen', 'role' => 'CTO & Co-Founder', 'bio' => 'Ex-Facebook engineer specializing in large-scale distributed systems and facial recognition.', 'image' => 'https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=300', 'expertise' => ['System Architecture', 'AI Engineering', 'Scalability']],
                    ['name' => 'Emily Rodriguez', 'role' => 'Head of Design', 'bio' => 'Award-winning UX designer with expertise in creating intuitive AI-powered interfaces.', 'image' => 'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=300', 'expertise' => ['UX Design', 'User Research', 'Interface Design']],
                    ['name' => 'David Kim', 'role' => 'Head of AI Research', 'bio' => 'PhD in Computer Vision from Stanford, leading our facial recognition and image processing innovations.', 'image' => 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg?auto=compress&cs=tinysrgb&w=300', 'expertise' => ['Computer Vision', 'Deep Learning', 'Research']],
                ] as $member)
                    <div class="text-center bg-white border-0 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden rounded-3xl transform hover:-translate-y-2">
                        <div class="p-8 md:p-10">
                            <img 
                                src="{{ $member['image'] }}" 
                                alt="{{ $member['name'] }}"
                                class="w-28 h-28 rounded-full object-cover mx-auto mb-6 border-4 border-indigo-100 shadow-md"
                            />
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $member['name'] }}</h3>
                            <p class="text-indigo-600 font-medium text-lg mb-6">{{ $member['role'] }}</p>
                            <p class="text-gray-600 text-base mb-8 leading-relaxed">{{ $member['bio'] }}</p>
                            <div class="flex flex-wrap justify-center gap-2">
                                @foreach($member['expertise'] as $skill)
                                    <span class="bg-gray-100 text-gray-800 text-sm font-medium px-4 py-2 rounded-full">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-indigo-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center mb-6 bg-orange-100 text-orange-800 font-medium px-4 py-2 rounded-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Our Journey
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">
                    Milestones & Achievements
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto">
                    From inception to innovation, here's how we've grown and evolved
                </p>
            </div>

            <div class="max-w-5xl mx-auto">
                <div class="relative">
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-indigo-500 to-violet-500 rounded-full opacity-80"></div>
                    <div class="space-y-16">
                        @foreach([
                            ['year' => '2025', 'title' => 'Company Founded', 'desc' => 'Blue Star Memory was founded with a vision to revolutionize photo management using AI.'],
                            ['year' => '2025 Q2', 'title' => 'Beta Launch', 'desc' => 'Launched our beta platform with core facial recognition and organization features.'],
                            ['year' => '2025 Q3', 'title' => '10K Users', 'desc' => 'Reached our first 10,000 users and processed over 1 million photos.'],
                            ['year' => '2025 Q4', 'title' => 'Mobile App', 'desc' => 'Released our mobile applications for iOS and Android platforms.'],
                        ] as $index => $milestone)
                            <div class="flex items-center {{ $index % 2 === 0 ? 'flex-row' : 'flex-row-reverse' }} group">
                                <div class="{{ $index % 2 === 0 ? 'pr-8 md:pr-12 text-right' : 'pl-8 md:pl-12 text-left' }} w-1/2">
                                    <div class="bg-white shadow-xl hover:shadow-2xl transition-all duration-300 rounded-2xl p-6 md:p-8 transform group-hover:scale-105">
                                        <div class="text-3xl font-bold text-indigo-600 mb-3">{{ $milestone['year'] }}</div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $milestone['title'] }}</h3>
                                        <p class="text-gray-600 text-lg leading-relaxed">{{ $milestone['desc'] }}</p>
                                    </div>
                                </div>
                                <div class="relative z-10">
                                    <div class="w-5 h-5 bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full border-4 border-white shadow-lg transform group-hover:scale-125 transition-transform duration-300"></div>
                                </div>
                                <div class="w-1/2"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-indigo-600 via-violet-600 to-pink-600">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-4xl md:text-6xl font-bold text-white mb-8 tracking-tight">
                    Join Our Mission
                </h2>
                <p class="text-xl md:text-2xl text-indigo-100 mb-12 max-w-3xl mx-auto">
                    Be part of the revolution in photo management. Start organizing your memories with AI today.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="/get-started" class="inline-flex items-center bg-white text-indigo-600 font-semibold px-12 py-5 rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Get Started
                    </a>
                    <a href="/careers" class="inline-flex items-center border-2 border-white text-white font-semibold px-12 py-5 rounded-full hover:bg-white/10 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Join Our Team
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection