<!-- resources/views/footer.blade.php -->

<footer class="bg-gray-900 text-white">
    <!-- Main Footer Content -->
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Company Info -->
            <div class="lg:col-span-2">
                <a href="/" class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
               <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" class="text-white" viewBox="0 0 128 128">
                <path d="M53.621 65.1a5.451 5.451 0 1 0-7.709-7.708l-.006.006a5.446 5.446 0 0 0 0 7.708 5.438 5.438 0 0 0 7.7.006zm-2.348-2.36-.006.006a2.134 2.134 0 0 1-3 0 2.114 2.114 0 1 1 3 0zM78.243 55.789a5.439 5.439 0 0 0-3.857 1.6 5.438 5.438 0 0 0-.009 7.7l.006.006a5.438 5.438 0 0 0 7.7.006l.006-.006a5.452 5.452 0 0 0-3.858-9.306zm1.5 6.953-.006.006a2.134 2.134 0 0 1-2.995 0l-.006-.006a2.134 2.134 0 0 1 0-2.995 2.12 2.12 0 0 1 3 2.995zM74.813 73.226H53.2a1.67 1.67 0 0 0-1.669 1.674 12.476 12.476 0 0 0 24.951 0 1.67 1.67 0 0 0-1.669-1.674zm-4.345 8.131a9.139 9.139 0 0 1-15.446-4.793h17.97a9.113 9.113 0 0 1-2.524 4.793z" />
                <path d="M109.327 46.817a11.434 11.434 0 0 0-4.277-2.384c-3.088-.94-6.79-1.684-10.32-2.393a238.86 238.86 0 0 1-4.606-.956l-5.8-1.287-1.237-2.1-8.977-15.23a11.464 11.464 0 0 0-4.189-4.267 11.763 11.763 0 0 0-5.821-1.6 11.643 11.643 0 0 0-5.857 1.5 11.51 11.51 0 0 0-4.263 4.233L43.688 39.8l-19.573 4.34a11.463 11.463 0 0 0-5.4 2.667 11.763 11.763 0 0 0-3.328 5.014 11.64 11.64 0 0 0-.412 6.013 11.506 11.506 0 0 0 2.707 5.405l13.443 15.2-1.9 19.827a11.428 11.428 0 0 0 .849 6.091 11.83 11.83 0 0 0 9.2 6.951 11.462 11.462 0 0 0 6.134-.883l18.608-8.085 18.6 8.08a11.451 11.451 0 0 0 5.687.92 11.791 11.791 0 0 0 9.633-6.952 11.493 11.493 0 0 0 .9-5.717l-1.95-20.233 13.443-15.2a11.5 11.5 0 0 0 2.412-4.292 11.728 11.728 0 0 0-3.415-12.126zm.233 11.161a8.188 8.188 0 0 1-1.721 3.053L93.916 76.776l-.479.542.068.716 2.012 20.948a8.2 8.2 0 0 1-.635 4.074 8.327 8.327 0 0 1-2.777 3.415 8.433 8.433 0 0 1-4.117 1.556 8.148 8.148 0 0 1-4.045-.659h-.006L64.665 99 64 98.711l-.659.289-19.27 8.373a8.176 8.176 0 0 1-4.374.635 8.341 8.341 0 0 1-3.933-1.634 8.461 8.461 0 0 1-2.642-3.348 8.135 8.135 0 0 1-.6-4.354l.006-.058 1.976-20.579.069-.716-.479-.542-13.92-15.745a8.175 8.175 0 0 1-1.925-3.84 8.33 8.33 0 0 1 .3-4.306 8.424 8.424 0 0 1 2.376-3.593 8.134 8.134 0 0 1 3.832-1.884l.039-.013 20.319-4.51.707-.157.369-.625 10.665-18.1a8.2 8.2 0 0 1 3.038-3.021 8.325 8.325 0 0 1 4.187-1.066 8.416 8.416 0 0 1 4.161 1.145 8.158 8.158 0 0 1 2.979 3.043l.013.019 8.988 15.25 1.603 2.726.369.625.707.157 6.506 1.444c1.448.321 3.042.642 4.671.969 3.449.693 7.065 1.42 10.007 2.315a8.126 8.126 0 0 1 3.038 1.692 8.44 8.44 0 0 1 2.437 8.671z" /></svg>
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
            </div>

            <!-- Footer Links -->
            @php
                $footerSections = [
                    [
                        'title' => 'Navigation',
                        'links' => [
                            ['name' => 'Home', 'href' => "{{ route('home') }}"],
                            ['name' => 'Shop', 'href' => '/shop'],
                            ['name' => 'About', 'href' => "{{ route('about') }}"],
                            ['name' => 'Pricing', 'href' => "{{ route('pricing') }}"],
                            ['name' => 'Contact', 'href' => "{{ route('contact') }}"]
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