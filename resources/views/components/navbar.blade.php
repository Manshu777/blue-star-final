<!-- resources/views/components/header.blade.php -->
<nav x-data="{ menuOpen: false }" class="bg-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-4 lg:px-8">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.16 6.674a1 1 0 
                        00.95.69h7.013c.969 0 1.371 1.24.588 
                        1.81l-5.677 4.13a1 1 0 00-.364 
                        1.118l2.16 6.674c.3.921-.755 
                        1.688-1.538 
                        1.118l-5.677-4.13a1 1 0 
                        00-1.176 0l-5.677 
                        4.13c-.783.57-1.838-.197-1.538-1.118l2.16-6.674a1 
                        1 0 00-.364-1.118L2.338 
                        11.1c-.783-.57-.38-1.81.588-1.81h7.013a1 
                        1 0 00.95-.69l2.16-6.674z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Blue Star Memory
                </h1>
                <p class="text-sm text-gray-500 font-medium">AI Photo Organization</p>
            </div>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden lg:flex items-center space-x-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">Home</a>
            <a href="/shop" class="text-gray-600 hover:text-blue-600">Shop</a>
            <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-600">About</a>
            <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-blue-600">Pricing</a>
            <a href="{{ route('contact') }}" class="text-gray-600 hover:text-blue-600">Contact</a>

            @auth
                <a href="{{ route('upload') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-blue-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Login</a>
                <a href="{{ route('signup') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow hover:opacity-90">Sign Up</a>
            @endauth
        </div>

        <!-- Mobile Toggle -->
        <button @click="menuOpen = !menuOpen" class="lg:hidden focus:outline-none">
            <svg x-show="!menuOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="menuOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-700" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="menuOpen" x-transition class="lg:hidden bg-white shadow-md px-4 pb-4 space-y-3">
        <a href="{{ route('home') }}" class="block text-gray-600 hover:text-blue-600">Home</a>
        <a href="/shop" class="block text-gray-600 hover:text-blue-600">Shop</a>
        <a href="{{ route('about') }}" class="block text-gray-600 hover:text-blue-600">About</a>
        <a href="{{ route('pricing') }}" class="block text-gray-600 hover:text-blue-600">Pricing</a>
        <a href="{{ route('contact') }}" class="block text-gray-600 hover:text-blue-600">Contact</a>

        @auth
            <a href="{{ route('upload') }}" class="block text-gray-600 hover:text-blue-600">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left text-gray-600 hover:text-blue-600">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block text-gray-600 hover:text-blue-600">Login</a>
            <a href="{{ route('signup') }}" class="block px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow hover:opacity-90">Sign Up</a>
        @endauth
    </div>
</nav>
