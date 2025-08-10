<nav class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold">Blue Star Memory</a>
        <div class="space-x-4">
            <a href="{{ route('home') }}" class="hover:text-gray-300">Home</a>
            <a  class="hover:text-gray-300">About</a>
            <a class="hover:text-gray-300">Pricing</a>
            <a class="hover:text-gray-300">Contact</a>
            @auth
                <a  class="hover:text-gray-300">Dashboard</a>
                <form  method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gray-300">Logout</button>
                </form>
            @else
                <a class="hover:text-gray-300">Login</a>
                <a   class="hover:text-gray-300">Register</a>
            @endauth
        </div>
    </div>
</nav>