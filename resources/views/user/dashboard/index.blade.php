{{-- resources/views/user/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Page Heading --}}
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                Welcome back, {{ Auth::user()->name ?? 'User' }} 👋
            </h1>
            <a href="{{ route('photos.search') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Photos
            </a>
        </div>

        {{-- Stats Section --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Photos</h2>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">1,248</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Albums</h2>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">32</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Orders</h2>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">58</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</h2>
                <p class="mt-2 text-3xl font-bold text-green-500">₹45,300</p>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Recent Activity</h2>
                <a href="{{ route('photos.search') }}" class="text-blue-600 hover:underline text-sm">View All</a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                <li class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="/assets/sample-photo.jpg" class="w-12 h-12 rounded object-cover mr-4" alt="Photo">
                        <div>
                            <p class="text-gray-800 dark:text-gray-100">Sunset over the hills</p>
                            <p class="text-sm text-gray-500">Uploaded 2 hours ago</p>
                        </div>
                    </div>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Published</span>
                </li>
                <li class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="/assets/sample-photo2.jpg" class="w-12 h-12 rounded object-cover mr-4" alt="Photo">
                        <div>
                            <p class="text-gray-800 dark:text-gray-100">Beach Day</p>
                            <p class="text-sm text-gray-500">Edited yesterday</p>
                        </div>
                    </div>
                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Pending</span>
                </li>
            </ul>
        </div>
    </div>
@endsection