@extends('layouts.app')

@section('title', 'Dashboard - Blue Star Memory')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Welcome to Your Dashboard</h2>

        <!-- Search Form -->
        <form method="GET"  class="mb-6">
            <div class="flex items-center space-x-2">
                <input type="text" name="query" id="query" placeholder="Search photos..."
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       maxlength="255">
                <button type="submit"
                        class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Search
                </button>
            </div>
        </form>

        <!-- Facial Search Form -->
        <form method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div class="flex items-center space-x-2">
                <input type="file" name="image" id="image" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <button type="submit"
                        class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Facial Search
                </button>
            </div>
        </form>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Placeholder for Photos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Example placeholder for photos -->
            <div class="bg-gray-200 h-48 rounded-md flex items-center justify-center">
                <span class="text-gray-500">Photo Placeholder</span>
            </div>
            <!-- Add more photo placeholders or dynamic content here -->
        </div>

        <!-- Link to Preview (Example) -->
        <p class="mt-4 text-center text-sm text-gray-600">
            <a  class="text-indigo-600 hover:text-indigo-800">View Sample Photo</a>
        </p>
    </div>
</div>
@endsection