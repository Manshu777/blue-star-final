<nav class="bg-gradient-to-r from-blue-600 to-blue-500 text-white py-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">Blue Star Memory</a>
        <div class="space-x-4">
            <a href="{{ route('home') }}" class="hover:text-gray-300">Home</a>
            <a href="{{ route('about') }}" class="hover:text-gray-300">About</a>
            <a href="{{ route('pricing') }}"  class="hover:text-gray-300">Pricing</a>
            <a href="{{ route('contact') }}"  class="hover:text-gray-300">Contact</a>
            @auth
                <a href="{{ route('upload') }}" class="hover:text-gray-300">Dashboard</a>
                <form  method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gray-300">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-gray-300">Login</a>
                <a href="{{ route('signup') }}"  class="hover:text-gray-300">Sign In</a>
            @endauth
        </div>
    </div>
</nav>