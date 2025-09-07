@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-violet-50 antialiased">
    <!-- Hero Section -->
    <section class="py-24 lg:py-12">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center max-w-5xl mx-auto">
                <span
                    class="inline-flex items-center mb-8 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-medium px-6 py-3 rounded-full shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    Flexible Pricing
                </span>
                <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 mb-8 tracking-tight">
                    Choose Your Perfect
                    <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                        Plan</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-16 leading-relaxed max-w-3xl mx-auto">
                    Start with our free plan and upgrade as you grow. All plans include our core AI features
                    and secure cloud storage.
                </p>
            </div>
        </div>
    </section>

    <!-- Pricing Plans -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-10 max-w-7xl mx-auto">
                @foreach($plans as $plan)
                <div
                    class="relative overflow-hidden border-2 transition-all duration-300 hover:shadow-2xl rounded-3xl bg-white
                        @if($plan->recommended) border-indigo-500 scale-105 shadow-xl @else border-gray-200 hover:border-gray-300 shadow-lg @endif">
                    @if($plan->recommended)
                    <div
                        class="absolute top-0 left-0 right-0 bg-gradient-to-r from-indigo-500 to-violet-500 text-white text-center py-3 text-base font-medium">
                        {{ $plan->badge }}
                    </div>
                    @endif

                    <div class="text-center @if($plan->recommended) pt-16 @else pt-12 @endif pb-8">
                        <div
                            class="w-16 h-16 bg-gradient-to-r {{ $plan->gradient ?? 'from-gray-500 to-gray-700' }} rounded-2xl flex items-center justify-center mx-auto mb-6 text-white shadow-lg">
                            <!-- Use SVG or FontAwesome icon based on your plan -->
                            <span class="text-2xl font-bold">💎</span>
                        </div>

                        <h3 class="text-3xl font-bold text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-gray-600 mt-2 text-lg">{{ $plan->description[0] ?? '' }}</p>

                        <div class="mt-8">
                            <div class="flex items-baseline justify-center">
                                <span class="text-6xl font-extrabold text-gray-900">${{ $plan->monthly_price }}</span>
                                <span class="text-gray-600 ml-2 text-xl">/month</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-10 pb-10">
                        <div class="space-y-4 mb-10 mt-8">
                            @foreach($plan->description as $feature)
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-emerald-500 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-700 text-base">{{ $feature }}</span>
                            </div>
                            @endforeach
                        </div>

                        <button class="w-full py-4 text-lg font-semibold rounded-full transition-all duration-300 shadow-lg
                            @if($plan->recommended) bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white 
                            @else bg-gray-900 hover:bg-gray-800 text-white shadow-md @endif">
                            {{ $plan->cta ?? 'Subscribe' }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Add-ons -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-indigo-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-16">
                <span
                    class="inline-flex items-center mb-6 bg-violet-100 text-violet-800 font-medium px-4 py-2 rounded-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Add-ons & Extras
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">
                    Enhance Your Experience
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto">
                    Customize your plan with additional features and services
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-10 max-w-6xl mx-auto">
                @foreach([
                ['name' => 'Extra Storage', 'desc' => 'Additional cloud storage space', 'price' => '$5/month per 100GB',
                'icon' => 'cloud'],
                ['name' => 'Premium Support', 'desc' => '24/7 priority support with phone access', 'price' =>
                '$15/month', 'icon' => 'phone'],
                ['name' => 'Advanced Analytics', 'desc' => 'Detailed insights and reporting', 'price' => '$10/month',
                'icon' => 'trending-up'],
                ['name' => 'Custom Branding', 'desc' => 'White-label solution with your branding', 'price' =>
                '$25/month', 'icon' => 'award'],
                ] as $addon)
                <div
                    class="text-center bg-white border-0 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden rounded-3xl transform hover:-translate-y-2">
                    <div class="p-8 md:p-10">
                        <div
                            class="w-14 h-14 bg-gradient-to-r from-indigo-500 to-violet-500 rounded-2xl flex items-center justify-center mx-auto mb-6 text-white shadow-lg">
                            @if($addon['icon'] == 'cloud')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z">
                                </path>
                            </svg>
                            @elseif($addon['icon'] == 'phone')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            @elseif($addon['icon'] == 'trending-up')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            @elseif($addon['icon'] == 'award')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9m0-2h6a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V3a2 2 0 012-2z">
                                </path>
                            </svg>
                            @endif
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $addon['name'] }}</h3>
                        <p class="text-gray-600 text-base mb-6">{{ $addon['desc'] }}</p>
                        <div class="text-2xl font-bold text-indigo-600">{{ $addon['price'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 md:px-6">
            <div class="text-center mb-16">
                <span
                    class="inline-flex items-center mb-6 bg-emerald-100 text-emerald-800 font-medium px-4 py-2 rounded-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>
                    Frequently Asked Questions
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">
                    Got Questions? We've Got Answers
                </h2>
                <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto">
                    Everything you need to know about our pricing and plans
                </p>
            </div>

            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10">
                    @foreach([
                    ['question' => 'Can I change my plan anytime?', 'answer' => 'Yes, you can upgrade or downgrade your
                    plan at any time. Changes take effect immediately, and we\'ll prorate the billing accordingly.'],
                    ['question' => 'Is there a free trial?', 'answer' => 'Yes, we offer a 14-day free trial for all
                    plans. No credit card required to start your trial.'],
                    ['question' => 'What happens to my photos if I cancel?', 'answer' => 'You can download all your
                    photos before canceling. We provide a 30-day grace period to export your data.'],
                    ['question' => 'Do you offer refunds?', 'answer' => 'Yes, we offer a 30-day money-back guarantee for
                    all paid plans. Contact support for assistance.'],
                    ['question' => 'Is my data secure?', 'answer' => 'Absolutely. We use enterprise-grade encryption and
                    security measures to protect your photos and personal data.'],
                    ['question' => 'Can I use Blue Star Memory for commercial purposes?', 'answer' => 'Yes, our Basic
                    and Premium plans include commercial usage rights. Check our terms for specific details.'],
                    ] as $faq)
                    <div
                        class="bg-white border-0 shadow-xl hover:shadow-2xl transition-all duration-300 rounded-3xl overflow-hidden">
                        <div class="p-8 md:p-10">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $faq['question'] }}</h3>
                            <p class="text-gray-600 text-base leading-relaxed">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Enterprise CTA (Adapted for Premium) -->
    <section class="py-24 bg-gradient-to-br from-gray-50 to-indigo-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="max-w-5xl mx-auto text-center">
                <div
                    class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-12 md:p-16 text-white shadow-2xl">
                    <svg class="w-20 h-20 mx-auto mb-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6">Need a Custom Solution?</h2>
                    <p class="text-xl md:text-2xl text-indigo-100 mb-10 max-w-3xl mx-auto">
                        We offer custom premium solutions with dedicated support,
                        custom integrations, and volume discounts for large organizations.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <button
                            class="inline-flex items-center bg-white text-indigo-600 font-semibold px-10 py-4 rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Contact Sales
                        </button>
                        <button
                            class="inline-flex items-center border-2 border-white text-white font-semibold px-10 py-4 rounded-full hover:bg-white/10 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            Schedule Demo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-gradient-to-r from-indigo-600 via-violet-600 to-pink-600">
        <div class="container mx-auto px-4 md:px-6 text-center">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-4xl md:text-6xl font-bold text-white mb-8 tracking-tight">
                    Ready to Get Started?
                </h2>
                <p class="text-xl md:text-2xl text-indigo-100 mb-12 max-w-3xl mx-auto">
                    Join thousands of users who have transformed their photo management experience with Blue Star
                    Memory.
                </p>
                <a href="/"
                    class="inline-flex items-center bg-white text-indigo-600 font-semibold px-12 py-5 rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.98 2.89a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.98-2.89c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    Start Your Free Trial
                    <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection