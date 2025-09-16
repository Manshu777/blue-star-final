<!-- resources/views/footer.blade.php -->

@extends('layouts.app') <!-- Assuming a base layout, adjust as needed -->

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">

        <!-- <section class="py-20 lg:py-32">
                <div class="container mx-auto px-4">
                    <div class="text-center max-w-4xl mx-auto">
                        <span
                            class="mb-6 inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white border-0 px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-heart mr-2"></i> 
                            Get in Touch
                        </span>
                        <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 tracking-tight">
                            We're Here to
                            <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Help</span>
                        </h1>
                        <p class="text-xl text-gray-600 mb-12 leading-relaxed max-w-2xl mx-auto">
                            Have questions about Blue Star Memory? Need technical support? Want to explore enterprise solutions?
                            Our team is ready to assist you.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                class="text-center p-6 bg-white/80 backdrop-blur-md rounded-2xl border border-white/30 shadow-md hover:shadow-lg transition-shadow duration-300">
                                <i class="fas fa-clock w-8 h-8 text-blue-600 mx-auto mb-3 block"></i>
                                <div class="font-semibold text-gray-900">Response Time</div>
                                <div class="text-sm text-gray-600">Within 24 hours</div>
                            </div>
                            <div
                                class="text-center p-6 bg-white/80 backdrop-blur-md rounded-2xl border border-white/30 shadow-md hover:shadow-lg transition-shadow duration-300">
                                <i class="fas fa-users w-8 h-8 text-purple-600 mx-auto mb-3 block"></i>
                                <div class="font-semibold text-gray-900">Expert Team</div>
                                <div class="text-sm text-gray-600">AI & Tech Specialists</div>
                            </div>
                            <div
                                class="text-center p-6 bg-white/80 backdrop-blur-md rounded-2xl border border-white/30 shadow-md hover:shadow-lg transition-shadow duration-300">
                                <i class="fas fa-globe w-8 h-8 text-green-600 mx-auto mb-3 block"></i>
                                <div class="font-semibold text-gray-900">Global Support</div>
                                <div class="text-sm text-gray-600">Multiple time zones</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->

        <!-- Contact Methods - Improved with subtle animations and cleaner cards -->
        <section class="py-20 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <span class="mb-6 inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-comment-dots mr-2"></i>
                        Contact Methods
                    </span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4 tracking-tight">
                        Choose Your Preferred Way to Connect
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Multiple ways to reach us, ensuring you get the help you need when you need it
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto">
                    @php
                        $contactMethods = [
                            [
                                'icon' => 'fas fa-envelope',
                                'title' => 'Email Support',
                                'description' => 'Get help via email within 24 hours',
                                'contact' => 'support@bluestarmemory.com',
                                'availability' => '24/7',
                                'gradient' => 'from-blue-500 to-cyan-500'
                            ],
                            [
                                'icon' => 'fas fa-phone',
                                'title' => 'Phone Support',
                                'description' => 'Speak directly with our support team',
                                'contact' => '+1 (555) 123-4567',
                                'availability' => 'Mon-Fri, 9AM-6PM PST',
                                'gradient' => 'from-green-500 to-teal-500'
                            ],
                            [
                                'icon' => 'fas fa-comment-dots',
                                'title' => 'Live Chat',
                                'description' => 'Instant help through our chat system',
                                'contact' => 'Available on website',
                                'availability' => 'Mon-Fri, 9AM-6PM PST',
                                'gradient' => 'from-purple-500 to-pink-500'
                            ],
                            [
                                'icon' => 'fas fa-map-pin',
                                'title' => 'Office Location',
                                'description' => 'Visit us at our headquarters',
                                'contact' => '123 Innovation Drive, San Francisco, CA 94105',
                                'availability' => 'By appointment only',
                                'gradient' => 'from-orange-500 to-red-500'
                            ]
                        ];
                    @endphp
                    @foreach($contactMethods as $method)
                        <div
                            class="text-center bg-white border-0 shadow-lg hover:shadow-xl transition-all duration-300 rounded-2xl overflow-hidden">
                            <div class="p-8">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r {{ $method['gradient'] }} rounded-2xl flex items-center justify-center mx-auto mb-6 text-white transform hover:scale-110 transition-transform duration-300">
                                    <i class="{{ $method['icon'] }} text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $method['title'] }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ $method['description'] }}</p>
                                <div class="text-blue-600 font-medium text-sm mb-2">{{ $method['contact'] }}</div>
                                <div class="text-gray-500 text-xs">{{ $method['availability'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Contact Form & Info - Enhanced form styling with better spacing and subtle borders -->
        <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 max-w-7xl mx-auto">
                    <!-- Contact Form -->
                    <div>
                        <span
                            class="mb-6 inline-block bg-purple-100 text-purple-800 px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </span>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6 tracking-tight">
                            Get in Touch Today
                        </h2>
                        <p class="text-lg text-gray-600 mb-8">
                            Fill out the form below and we'll get back to you as soon as possible.
                        </p>

                        <div class="bg-white border-0 shadow-xl rounded-2xl overflow-hidden">
                            <div class="p-8">
                                <form action="#" method="POST" class="space-y-6"> <!-- Adjust action as needed -->
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Full Name *
                                            </label>
                                            <input type="text" name="name" placeholder="Your full name" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Email Address *
                                            </label>
                                            <input type="email" name="email" placeholder="your@email.com" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Company (Optional)
                                        </label>
                                        <input type="text" name="company" placeholder="Your company name"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Inquiry Type
                                        </label>
                                        <select name="inquiryType"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            @php
                                                $inquiryTypes = [
                                                    ['value' => 'general', 'label' => 'General Inquiry'],
                                                    ['value' => 'sales', 'label' => 'Sales & Pricing'],
                                                    ['value' => 'support', 'label' => 'Technical Support'],
                                                    ['value' => 'partnership', 'label' => 'Partnership'],
                                                    ['value' => 'enterprise', 'label' => 'Enterprise Solutions'],
                                                    ['value' => 'media', 'label' => 'Media & Press']
                                                ];
                                            @endphp
                                            @foreach($inquiryTypes as $type)
                                                <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Subject *
                                        </label>
                                        <input type="text" name="subject" placeholder="Brief subject of your inquiry"
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Message *
                                        </label>
                                        <textarea name="message" placeholder="Please provide details about your inquiry..."
                                            required rows="6"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 rounded-md transition-colors duration-300">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Send Message
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Office Locations - Refined with softer gradients and hover effects -->
                    <div>
                        <span
                            class="mb-6 inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-building mr-2"></i>
                            Our Offices
                        </span>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6 tracking-tight">
                            Visit Us Worldwide
                        </h2>
                        <p class="text-lg text-gray-600 mb-8">
                            We have offices around the globe to better serve our international customers.
                        </p>

                        <div class="space-y-6">
                            @php
                                $officeLocations = [
                                    [
                                        'city' => 'San Francisco',
                                        'address' => '123 Innovation Drive, San Francisco, CA 94105',
                                        'phone' => '+1 (555) 123-4567',
                                        'email' => 'sf@bluestarmemory.com',
                                        'type' => 'Headquarters'
                                    ],
                                    [
                                        'city' => 'New York',
                                        'address' => '456 Tech Avenue, New York, NY 10001',
                                        'phone' => '+1 (555) 234-5678',
                                        'email' => 'ny@bluestarmemory.com',
                                        'type' => 'Sales Office'
                                    ],
                                    [
                                        'city' => 'London',
                                        'address' => '789 Digital Street, London, UK EC1A 1BB',
                                        'phone' => '+44 20 1234 5678',
                                        'email' => 'london@bluestarmemory.com',
                                        'type' => 'European Office'
                                    ]
                                ];
                            @endphp
                            @foreach($officeLocations as $office)
                                <div
                                    class="bg-white border-0 shadow-lg hover:shadow-xl transition-all duration-300 rounded-2xl overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                                                <i class="fas fa-map-pin text-xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h3 class="text-xl font-bold text-gray-900">{{ $office['city'] }}</h3>
                                                    <span
                                                        class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
                                                        {{ $office['type'] }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-600 mb-2">{{ $office['address'] }}</p>
                                                <div class="space-y-1">
                                                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                        <i class="fas fa-phone w-4 h-4"></i>
                                                        <span>{{ $office['phone'] }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                                                        <i class="fas fa-envelope w-4 h-4"></i>
                                                        <span>{{ $office['email'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- <section class="py-20 bg-white">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-16">
                        <span
                            class="mb-6 inline-block bg-orange-100 text-orange-800 px-4 py-2 rounded-full text-sm font-medium">
                            <i class="fas fa-star mr-2"></i>
                            Investment Information
                        </span>
                        <h2 class="text-4xl font-bold text-gray-900 mb-4 tracking-tight">
                            Development Investment Details
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Transparent pricing for our comprehensive development and deployment services
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Development Costs</h3>
                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-mobile-alt w-6 h-6 text-blue-600"></i>
                                        <span class="font-medium text-gray-900">Mobile App Development</span>
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">₹75,000</span>
                                </div>
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-globe w-6 h-6 text-purple-600"></i>
                                        <span class="font-medium text-gray-900">Website Development</span>
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">₹45,000</span>
                                </div>
                                <div
                                    class="flex justify-between items-center p-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg text-white">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-star w-6 h-6"></i>
                                        <span class="font-medium">Total Investment</span>
                                    </div>
                                    <span class="text-2xl font-bold">₹1,20,000</span>
                                </div>
                            </div>

                            <h4 class="text-lg font-bold text-gray-900 mb-4">Operational Costs</h4>
                            <div class="p-4 bg-blue-50 rounded-lg mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-900">Monthly Server & Hosting</span>
                                    <span class="font-bold text-blue-600">₹2,500 - ₹3,000</span>
                                </div>
                            </div>

                            <button
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 rounded-md transition-colors duration-300">
                                <i class="fas fa-arrow-right mr-2"></i>
                                Start Your Project
                            </button>
                        </div>

                        <div class="bg-gradient-to-br from-gray-50 to-blue-50 p-8 rounded-2xl shadow-md">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">What's Included</h3>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle w-6 h-6 text-green-500 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Complete AI Platform</h4>
                                        <p class="text-gray-600">Full facial recognition and photo organization system</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle w-6 h-6 text-green-500 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Mobile Applications</h4>
                                        <p class="text-gray-600">Native iOS and Android apps with full functionality</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle w-6 h-6 text-green-500 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Admin Dashboard</h4>
                                        <p class="text-gray-600">Comprehensive management and analytics tools</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle w-6 h-6 text-green-500 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Cloud Infrastructure</h4>
                                        <p class="text-gray-600">Scalable cloud setup with security measures</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle w-6 h-6 text-green-500 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Training & Support</h4>
                                        <p class="text-gray-600">Complete training and 6 months of support</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->

        <section class="py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600">
            <div class="container mx-auto px-4 text-center">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight">
                        Ready to Start Your Project?
                    </h2>
                    <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                        Contact us today to discuss your requirements and get a detailed project proposal.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <button
                            class="bg-white text-blue-600 hover:bg-gray-100 px-10 py-4 text-lg font-semibold rounded-md transition-colors duration-300">
                            <i class="fas fa-phone mr-3"></i>
                            Schedule Call
                        </button>
                        <button
                            class="border-2 border-white text-white hover:bg-white/10 px-10 py-4 text-lg font-semibold rounded-md transition-colors duration-300">
                            <i class="fas fa-envelope mr-3"></i>
                            Send Email
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection