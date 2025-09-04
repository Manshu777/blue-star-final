<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Panel Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/video.js@7/dist/video.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/video.js@7/dist/video-js.min.css" rel="stylesheet">
    <style>
        .drag-drop-zone {
            border: 2px dashed #ccc;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }
        .drag-drop-zone.dragover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .preview-canvas,
        .preview-video {
            max-height: 400px;
            width: 100%;
            object-fit: contain;
            border-radius: 0.5rem;
        }
        .edit-tools button {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .pro-badge {
            background-color: #3b82f6;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
        /* Dark mode styles */
        html.dark .bg-gray-50 {
            background-color: #1f2937;
        }
        html.dark .text-gray-800 {
            color: #f3f4f6;
        }
        html.dark .bg-white {
            background-color: #374151;
        }
        html.dark .text-gray-700 {
            color: #d1d5db;
        }
        html.dark .bg-gray-200 {
            background-color: #4b5563;
        }
        html.dark .text-gray-500 {
            color: #9ca3af;
        }
        html.dark .border-gray-300 {
            border-color: #4b5563;
        }
        html.dark .bg-blue-100 {
            background-color: #1e3a8a;
        }
        html.dark .text-blue-700 {
            color: #60a5fa;
        }
        html.dark .pro-badge {
            background-color: #1e40af;
            color: #fff;
        }
        .sidebar {
            transition: width 0.3s ease-in-out;
        }
        .overlay {
            transition: opacity 0.3s ease-in-out;
        }
        .form-input {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased"
      x-data="{ tab: 'dashboard', sidebarOpen: false, isMobile: window.innerWidth < 768, darkMode: false }"
      @resize.window="isMobile = window.innerWidth < 768; if (isMobile && sidebarOpen) sidebarOpen = false;">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay for Mobile -->
        <div x-show="isMobile && sidebarOpen" class="overlay fixed inset-0 bg-black bg-opacity-50 z-30"
             @click="sidebarOpen = false"></div>
        <!-- Sidebar -->
        <aside class="sidebar bg-white dark:bg-gray-800 shadow-lg flex flex-col overflow-hidden"
               :class="{ 'w-64': sidebarOpen, 'w-0': !sidebarOpen, 'fixed h-full z-40': isMobile, 'relative': !isMobile }">
            <div class="flex items-center justify-between p-2 border-b dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">User Dashboard</h2>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 dark:text-gray-300 md:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex-grow overflow-y-auto p-2">
                <ul class="space-y-2">
                    <li>
                        <button @click="tab = 'dashboard'; if(isMobile) sidebarOpen = false"
                                class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                                :class="{ 'bg-blue-600 text-white': tab === 'dashboard', 'text-gray-800 dark:text-white': tab !== 'dashboard' }">
                            Dashboard
                        </button>
                    </li>
                    <li>
                        <button @click="tab = 'upload'; if(isMobile) sidebarOpen = false"
                                class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                                :class="{ 'bg-blue-600 text-white': tab === 'upload', 'text-gray-800 dark:text-white': tab !== 'upload' }">
                            Upload Media
                        </button>
                    </li>
                    <li>
                        <button @click="tab = 'edit'; if(isMobile) sidebarOpen = false"
                                class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                                :class="{ 'bg-blue-600 text-white': tab === 'edit', 'text-gray-800 dark:text-white': tab !== 'edit' }">
                            Edit & Enhance
                        </button>
                    </li>
                    <li>
                        <button @click="tab = 'subscriptions'; if(isMobile) sidebarOpen = false"
                                class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                                :class="{ 'bg-blue-600 text-white': tab === 'subscriptions', 'text-gray-800 dark:text-white': tab !== 'subscriptions' }">
                            Subscription Plans
                        </button>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 rounded-lg hover:bg-red-100 dark:hover:bg-red-600 transition text-gray-800 dark:text-white">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
            <div class="p-2 border-t dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 dark:text-gray-300">Dark Mode</span>
                    <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode)"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 dark:bg-gray-600 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            :class="{ 'bg-blue-600': darkMode }">
                        <span class="sr-only">Toggle dark mode</span>
                        <span class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                              :class="{ 'translate-x-5': darkMode, 'translate-x-0': !darkMode }"></span>
                    </button>
                </div>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-grow overflow-y-auto" :class="{ '': sidebarOpen && !isMobile }">
            <div class="container mx-auto px-4 py-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl md:text-4xl font-bold text-gray-800 dark:text-white">User Panel Dashboard</h1>
                    <button @click="sidebarOpen = true" class="text-gray-600 dark:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <!-- Dashboard Section -->
                <div x-show="tab === 'dashboard'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Dashboard Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Total Uploads</h3>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">100</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Storage Used</h3>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-300"> 100 MB</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Active Plan</h3>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ auth()->user()->subscription ? auth()->user()->subscription->plan_name : 'Free' }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Recent Activity</h3>
                        <ul class="space-y-2">
                    
                        </ul>
                    </div>
                </div>
                <!-- Upload Media Section -->
                <div x-show="tab === 'upload'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Upload Media</h2>
                    @if (session('success'))
                        <div class="bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200 p-2 mb-6 rounded" role="alert">
                            {{ session('success') }}
                            @if (session('url'))
                                <br><a href="{{ session('url') }}" class="underline font-medium" target="_blank">View Media</a>
                            @endif
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-2 mb-6 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-2 mb-6 rounded" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                   
                           <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data"
          @submit.prevent="handleSubmit" x-data="uploadFormData()">
        @csrf
        <!-- Media Selection -->
        <div class="mb-6 relative group">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Photos/Videos
                <span class="text-xs text-gray-500 dark:text-gray-400">(JPEG, PNG, MP4, MOV, max 5MB)</span>
            </label>
            <div class="drag-drop-zone rounded-lg p-6 text-center cursor-pointer bg-gray-50 dark:bg-gray-700 border-2 border-dashed"
                 :class="{ 'dragover': isDragging }" @dragover.prevent="isDragging = true"
                 @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event)">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V8m0 0l-4 4m4-4l4 4m6-4v8m0 0h-4m4 0h4" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Drag & drop files here, or click to select (multiple allowed)</p>
                <input type="file" name="files[]" id="file" class="hidden"
                       accept="image/jpeg,image/png,video/mp4,video/quicktime" multiple
                       @change="handleFileChange($event)" x-ref="fileInput">
                <div class="flex justify-center space-x-2 mt-2">
                    <button type="button"
                            class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 py-1 px-3 rounded-full text-sm hover:bg-blue-200 dark:hover:bg-blue-800 transition"
                            @click="$refs.fileInput.click()">
                        <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Browse Files
                    </button>
                    <button type="button"
                            class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 py-1 px-3 rounded-full text-sm hover:bg-green-200 dark:hover:bg-green-800 transition"
                            @click="captureFromCamera">
                        <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h4l2-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        </svg>
                        Use Camera
                    </button>
                </div>
                <!-- Tooltip -->
                <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Upload multiple files for bulk processing. Use camera for live events.
                </div>
            </div>
            <!-- Preview -->
            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4" x-show="previews.length > 0">
                <template x-for="(preview, index) in previews" :key="index">
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-700 relative group">
                        <img :src="preview.url" alt="Preview" class="preview-canvas mx-auto rounded-lg shadow"
                             x-show="preview.type.startsWith('image/')">
                        <video :id="'preview-video-' + index" :src="preview.url" controls
                               class="preview-video mx-auto rounded-lg shadow"
                               x-show="preview.type.startsWith('video/')"></video>
                        <button type="button"
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition"
                                @click="removePreview(index)">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
            @error('files.*')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="relative group">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" placeholder="Enter media title"
                       class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       required>
                <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Use a descriptive title for your photo/video.
                </div>
                @error('title')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="relative group">
                <label for="event" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event/Folder Name</label>
                <input type="text" name="event" id="event" placeholder="Enter event or folder name"
                       class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       required>
                <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Group media by event or folder (e.g., "Wedding 2025").
                </div>
                @error('event')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mb-4 relative group">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea name="description" id="description" rows="3" placeholder="Describe your media"
                      class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
            <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                Tip: Add details to make your media searchable.
            </div>
            @error('description')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- <div class="relative group">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price ($)</label>
                <input type="number" name="price" id="price" placeholder="Enter price (e.g., 10.99)" step="0.01" min="0"
                       class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       required>
                <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Set a price for selling your media.
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div> -->
            <!-- <div class="relative group">
                <label for="license_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">License Type</label>
                <select name="license_type" id="license_type"
                        class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        required>
                    <option value="" disabled selected>Select license type</option>
                    <option value="personal">Personal</option>
                    <option value="commercial">Commercial</option>
                </select>
                <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Choose "Commercial" for business use, "Personal" for private use.
                </div>
                @error('license_type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div> -->
        </div>
        <div class="mb-4 relative group">
            <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags (AI Auto-Tags + Custom)</label>
            <input type="text" name="tags" id="tags" placeholder="e.g., nature, portrait, wedding"
                   class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                   x-model="tags">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">AI auto-tags: faces, date/time, location. Add custom tags, separated by commas.</p>
            <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                Tip: Tags help users find your media in searches.
            </div>
            @error('tags')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="relative group">
                <label for="tour_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tour Provider</label>
                <input type="text" name="tour_provider" id="tour_provider" placeholder="Enter tour provider (e.g., Blue Star Tours)"
                       class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Specify the tour provider for event-based media.
                </div>
                @error('tour_provider')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="relative group">
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input type="text" name="location" id="location" placeholder="Enter location (e.g., Paris, France)"
                       class="form-input p-2 mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                       x-model="location">
                <div class="absolute top-0 right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Auto-filled by geolocation, but you can edit it.
                </div>
                @error('location')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mb-6 relative group">
            <div class="flex items-center">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                       class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500"
                       {{ old('is_featured') ? 'checked' : '' }}>
                <label for="is_featured" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Mark as Featured
                </label>
                <div class="absolute right-0 mt-1 mr-1 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-xs rounded py-1 px-2">
                    Tip: Featured media appears prominently in galleries.
                </div>
            </div>
            @error('is_featured')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Progress Bar -->
        <div x-show="progress > 0" class="mb-4">
            <div class="bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: progress + '%' }"></div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Uploading: <span x-text="progress + '%'"></span></p>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium flex items-center justify-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Upload Media
        </button>
    </form>
                </div>
                <!-- Edit & Enhance Section -->
                <div x-show="tab === 'edit'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Edit & Enhance</h2>
                    <div x-data="editData()">
                        <div class="edit-tools grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Basic Tools</h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500">Crop</button>
                                    <button type="button"
                                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                            @click="rotate(90)">Rotate 90°</button>
                                    <button type="button"
                                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                            @click="rotate(-90)">Rotate -90°</button>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Advanced AI Tools <span class="pro-badge">Pro</span></h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                            @click="sharpen">Sharpen (AI)</button>
                                    <button type="button"
                                            class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                            @click="colorCorrect">Color Correct (AI)</button>
                                </div>
                            </div>
                        </div>
                        <canvas id="edit-canvas"
                                class="preview-canvas mx-auto mt-4 border border-gray-300 dark:border-gray-600 rounded-lg"></canvas>
                    </div>
                </div>
                <!-- Subscription Plans Section -->
                <div x-show="tab === 'subscriptions'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Subscription Plans</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="p-6 border rounded-lg shadow bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:shadow-xl transition">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Free Plan</h3>
                            <p class="text-gray-700 dark:text-gray-300">Basic editing tools, limited storage, and watermarked images.</p>
                            <ul class="mt-4 list-disc pl-6 text-gray-700 dark:text-gray-300">
                                <li>Basic features</li>
                                <li>Limited storage</li>
                                <li>Watermarks</li>
                            </ul>
                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 mt-4"
                                    :disabled="activePlan === 'Free'">Current Plan</button>
                        </div>
                        <div class="p-6 border rounded-lg shadow bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:shadow-xl transition">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Pro Plan</h3>
                            <p class="text-gray-700 dark:text-gray-300">Advanced AI tools, unlimited storage, and no watermarks.</p>
                            <ul class="mt-4 list-disc pl-6 text-gray-700 dark:text-gray-300">
                                <li>Advanced AI tools</li>
                                <li>Unlimited storage</li>
                                <li>No watermarks</li>
                            </ul>
                            <form  method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 mt-4"
                                        :disabled="activePlan === 'Pro'">{{ auth()->user()->subscription && auth()->user()->subscription->plan_name === 'Pro' ? 'Current Plan' : 'Upgrade to Pro' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function uploadFormData() {
            return {
                files: [],
                previews: [],
                isDragging: false,
                progress: 0,
                tags: '',
                location: '',
                init() {
                    this.getGeolocation();
                    this.$watch('files', () => {
                        this.previews = [];
                        for (let file of this.files) {
                            const url = URL.createObjectURL(file);
                            this.previews.push({ url, type: file.type });
                            this.extractExif(file);
                        }
                    });
                },
                handleFileChange(event) {
                    this.files = Array.from(event.target.files);
                },
                handleDrop(event) {
                    this.isDragging = false;
                    this.files = Array.from(event.dataTransfer.files);
                    document.querySelector('#file').files = event.dataTransfer.files;
                },
                captureFromCamera() {
                    const input = document.querySelector('#file');
                    input.setAttribute('capture', 'environment');
                    input.click();
                },
                extractExif(file) {
                    if (file && file.type.startsWith('image/')) {
                        EXIF.getData(file, () => {
                            const date = EXIF.getTag(file, 'DateTimeOriginal') || '';
                            const lat = EXIF.getTag(file, 'GPSLatitude') || '';
                            const lon = EXIF.getTag(file, 'GPSLongitude') || '';
                            let autoTags = [];
                            if (date) autoTags.push(`date:${date}`);
                            if (lat && lon) autoTags.push(`location:${lat},${lon}`);
                            autoTags.push('face_detected');
                            this.tags = [...new Set([...this.tags.split(','), ...autoTags])].join(',');
                        });
                    }
                },
                getGeolocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((position) => {
                            this.location = `${position.coords.latitude},${position.coords.longitude}`;
                            if (this.tags) {
                                this.tags += `,location:${this.location}`;
                            } else {
                                this.tags = `location:${this.location}`;
                            }
                        }, (error) => {
                            console.error('Geolocation error:', error);
                        });
                    }
                },
                handleSubmit(event) {
                    event.preventDefault();
                    const formData = new FormData(event.target);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', event.target.action);
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                    xhr.upload.onprogress = (e) => {
                        if (e.lengthComputable) this.progress = Math.round((e.loaded * 100) / e.total);
                    };
                    xhr.onload = () => {
                        if (xhr.status === 200) {
                            location.reload();
                        } else {
                            console.log('Upload failed: ' + xhr.responseText);
                        }
                        this.progress = 0;
                    };
                    xhr.send(formData);
                }
            };
        }
        function editData() {
            return {
                canvas: null,
                brightness: 0,
                contrast: 0,
                init() {
                    const img = new Image();
                    img.src = 'placeholder-image.jpg';
                    img.onload = () => {
                        const canvasEl = document.getElementById('edit-canvas');
                        canvasEl.width = Math.min(img.width, 800);
                        canvasEl.height = (img.height / img.width) * canvasEl.width;
                        this.canvas = new fabric.Canvas(canvasEl);
                        fabric.Image.fromURL(img.src, (fImg) => {
                            fImg.scaleToWidth(canvasEl.width);
                            this.canvas.add(fImg);
                            this.canvas.setActiveObject(fImg);
                            this.canvas.renderAll();
                        });
                    };
                },
                rotate(deg) {
                    const active = this.canvas.getActiveObject();
                    if (active) {
                        active.rotate((active.angle + deg) % 360);
                        this.canvas.renderAll();
                    }
                },
                sharpen() {
                    alert('AI Sharpen applied');
                },
                colorCorrect() {
                    alert('AI Color Correction applied');
                }
            };
        }
    </script>
</body>
</html>