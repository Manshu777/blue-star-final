<nav class="bg-black text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">Blue Star Memory</a>
        <div class="space-x-4">
            <a href="{{ route('home') }}" class="hover:text-gray-300">Home</a>
            <a href="{{ route('about') }}" class="hover:text-gray-300">About</a>
            <a href="{{ route('pricing') }}" class="hover:text-gray-300">Pricing</a>
            <a href="{{ route('contact') }}" class="hover:text-gray-300">Contact</a>
            @auth
                <a href="{{ route('user.dashboard') }}" class="hover:text-gray-300">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gray-300">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-gray-300">Login</a>
                
            @endauth
        </div>
    </div>
</nav>