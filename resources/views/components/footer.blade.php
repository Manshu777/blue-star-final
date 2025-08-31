<!-- resources/views/footer.blade.php -->

<footer class="bg-gray-900 text-white">
    <!-- Newsletter Section - Enhanced with elegant gradient and refined input styling -->
    <div class="border-b border-gray-800">
        <div class="container mx-auto px-4 py-16">
            <div class="max-w-4xl mx-auto text-center">
                <h3 class="text-3xl font-bold mb-4 tracking-tight">Stay Updated with Blue Star Memory</h3>
                <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                    Get the latest updates on new features, tips, and exclusive offers delivered to your inbox.
                </p>
                <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input
                        type="email"
                        placeholder="Enter your email"
                        class="flex-1 px-4 py-3 rounded-full bg-gray-800 border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 transition-all duration-300"
                    />
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 px-6 py-3 font-medium rounded-full text-white transition-all duration-300">
                        Subscribe
                        <i class="fas fa-arrow-right w-4 h-4 ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-6 gap-12">
            <!-- Company Info -->
            <div class="lg:col-span-2">
                <a href="/" class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Blue Star Memory</h2>
                        <p class="text-gray-400 text-sm">AI Photo Organization</p>
                    </div>
                </a>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    Revolutionizing photo management with AI-powered facial recognition,
                    smart organization, and personalized merchandise creation.
                </p>

                @php
                    $features = [
                        ['icon' => 'fas fa-camera', 'text' => 'AI-Powered Recognition'],
                        ['icon' => 'fas fa-shield-alt', 'text' => 'Enterprise Security'],
                        ['icon' => 'fas fa-credit-card', 'text' => 'Flexible Pricing'],
                        ['icon' => 'fas fa-users', 'text' => '24/7 Support']
                    ];
                @endphp
                <div class="grid grid-cols-2 gap-3 mb-6">
                    @foreach($features as $feature)
                        <div class="flex items-center space-x-2 text-sm text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="{{ $feature['icon'] }} text-blue-400 w-5 h-5"></i>
                            <span>{{ $feature['text'] }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Social Links -->
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors duration-300 ease-in-out">
                        <i class="fab fa-facebook-f text-white text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors duration-300 ease-in-out">
                        <i class="fab fa-twitter text-white text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors duration-300 ease-in-out">
                        <i class="fab fa-instagram text-white text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors duration-300 ease-in-out">
                        <i class="fab fa-linkedin-in text-white text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Footer Links -->
            @php
                $footerSections = [
                    [
                        'title' => 'Product',
                        'links' => [
                            ['name' => 'Features', 'href' => '/features'],
                            ['name' => 'Mobile App', 'href' => '/mobile-app'],
                            ['name' => 'Pricing', 'href' => '/pricing'],
                            ['name' => 'API Documentation', 'href' => '/api'],
                            ['name' => 'Integrations', 'href' => '/integrations']
                        ]
                    ],
                    [
                        'title' => 'Company',
                        'links' => [
                            ['name' => 'About Us', 'href' => '/about'],
                            ['name' => 'Careers', 'href' => '/careers'],
                            ['name' => 'Blog', 'href' => '/blog'],
                            ['name' => 'Press Kit', 'href' => '/press'],
                            ['name' => 'Partners', 'href' => '/partners']
                        ]
                    ],
                    [
                        'title' => 'Support',
                        'links' => [
                            ['name' => 'Help Center', 'href' => '/help'],
                            ['name' => 'Contact Support', 'href' => '/support'],
                            ['name' => 'System Status', 'href' => '/status'],
                            ['name' => 'Security', 'href' => '/security'],
                            ['name' => 'Privacy Policy', 'href' => '/privacy']
                        ]
                    ],
                    [
                        'title' => 'Resources',
                        'links' => [
                            ['name' => 'Developer Docs', 'href' => '/docs'],
                            ['name' => 'Community', 'href' => '/community'],
                            ['name' => 'Tutorials', 'href' => '/tutorials'],
                            ['name' => 'Webinars', 'href' => '/webinars'],
                            ['name' => 'Case Studies', 'href' => '/case-studies']
                        ]
                    ]
                ];
            @endphp
            @foreach($footerSections as $section)
                <div>
                    <h4 class="font-bold text-lg mb-6 tracking-wide">{{ $section['title'] }}</h4>
                    <ul class="space-y-3">
                        @foreach($section['links'] as $link)
                            <li>
                                <a href="{{ $link['href'] }}" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                                    {{ $link['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Contact Info -->
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start space-x-3">
                    <i class="fas fa-envelope w-5 h-5 text-blue-400"></i>
                    <span class="text-gray-400">contact@bluestarmemory.com</span>
                </div>
                <div class="flex items-center justify-center md:justify-start space-x-3">
                    <i class="fas fa-phone w-5 h-5 text-blue-400"></i>
                    <span class="text-gray-400">+1 (555) 123-4567</span>
                </div>
                <div class="flex items-center justify-center md:justify-start space-x-3">
                    <i class="fas fa-map-pin w-5 h-5 text-blue-400"></i>
                    <span class="text-gray-400">San Francisco, CA</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm">
                    © 2025 Blue Star Memory. All rights reserved.
                </div>
                <div class="flex space-x-6 text-sm">
                    <a href="/terms" class="text-gray-400 hover:text-white transition-colors duration-200">
                        Terms of Service
                    </a>
                    <a href="/privacy" class="text-gray-400 hover:text-white transition-colors duration-200">
                        Privacy Policy
                    </a>
                    <a href="/cookies" class="text-gray-400 hover:text-white transition-colors duration-200">
                        Cookie Policy
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
