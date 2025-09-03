<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload & Collaborate: Seamless Photo and Video Management</title>
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
            background-color: #FFD700;
            color: #000;
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
        html.dark .bg-green-100 {
            background-color: #064e3b;
        }
        html.dark .text-green-700 {
            color: #34d399;
        }
        html.dark .border-green-500 {
            border-color: #059669;
        }
        html.dark .bg-red-100 {
            background-color: #7f1d1d;
        }
        html.dark .text-red-700 {
            color: #f87171;
        }
        html.dark .border-red-500 {
            border-color: #ef4444;
        }
        html.dark .bg-blue-100 {
            background-color: #1e3a8a;
        }
        html.dark .text-blue-700 {
            color: #60a5fa;
        }
        html.dark .bg-green-100 {
            background-color: #064e3b;
        }
        html.dark .text-green-700 {
            color: #34d399;
        }
        html.dark .bg-blue-200 {
            background-color: #1e40af;
        }
        html.dark .pro-badge {
            background-color: #a16207;
            color: #fffbeb;
        }
        /* Sidebar transitions */
        .sidebar {
            transition: width 0.3s ease-in-out;
        }
        /* Overlay for mobile sidebar */
        .overlay {
            transition: opacity 0.3s ease-in-out;
        }
        /* Improved design */
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
    x-data="{ tab: 'upload', sidebarOpen: false, isMobile: window.innerWidth < 768, darkMode: false }"
    @resize.window="isMobile = window.innerWidth < 768; if (isMobile && sidebarOpen) sidebarOpen = false;">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay for Mobile -->
        <div x-show="isMobile && sidebarOpen" class="overlay fixed inset-0 bg-black bg-opacity-50 z-30"
            @click="sidebarOpen = false"></div>
        <!-- Sidebar -->
        <aside class="sidebar bg-white dark:bg-gray-800 shadow-lg flex flex-col overflow-hidden"
            :class="{ 'w-64': sidebarOpen, 'w-0': !sidebarOpen, 'fixed h-full z-40': isMobile, 'relative': !isMobile }">
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</h2>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 dark:text-gray-300 md:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex-grow overflow-y-auto p-4">
                <ul class="space-y-2">
                    <li>
                        <button @click="tab = 'upload'; if(isMobile) sidebarOpen = false"
                            class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                            :class="{ 'bg-blue-600 text-white': tab === 'upload', 'text-gray-800 dark:text-white': tab !== 'upload' }">Upload
                            Media</button>
                    </li>
                    <li>
                        <button @click="tab = 'edit'; if(isMobile) sidebarOpen = false"
                            class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                            :class="{ 'bg-blue-600 text-white': tab === 'edit', 'text-gray-800 dark:text-white': tab !== 'edit' }">Edit
                            & Enhance</button>
                    </li>
                    <li>
                        <button @click="tab = 'collaborate'; if(isMobile) sidebarOpen = false"
                            class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                            :class="{ 'bg-blue-600 text-white': tab === 'collaborate', 'text-gray-800 dark:text-white': tab !== 'collaborate' }">Collaborate
                            & Share</button>
                    </li>
                    <li>
                        <button @click="tab = 'advanced'; if(isMobile) sidebarOpen = false"
                            class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                            :class="{ 'bg-blue-600 text-white': tab === 'advanced', 'text-gray-800 dark:text-white': tab !== 'advanced' }">Advanced
                            Features (Pro)</button>
                    </li>
                    <li>
                        <button @click="tab = 'pricing'; if(isMobile) sidebarOpen = false"
                            class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                            :class="{ 'bg-blue-600 text-white': tab === 'pricing', 'text-gray-800 dark:text-white': tab !== 'pricing' }">Pricing</button>
                    </li>
                    <li>
                        <button @click="tab = 'future'; if(isMobile) sidebarOpen = false"
                            class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition"
                            :class="{ 'bg-blue-600 text-white': tab === 'future', 'text-gray-800 dark:text-white': tab !== 'future' }">Future
                            Features</button>
                    </li>
                </ul>
            </nav>
            <div class="p-4 border-t dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 dark:text-gray-300">Dark Mode</span>
                    <button @click="darkMode = !darkMode; document.documentElement.classList.toggle('dark', darkMode)"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 dark:bg-gray-600 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        :class="{ 'bg-blue-600': darkMode }">
                        <span class="sr-only">Toggle dark mode</span>
                        <span
                            class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                            :class="{ 'translate-x-5': darkMode, 'translate-x-0': !darkMode }"></span>
                    </button>
                </div>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-grow overflow-y-auto" :class="{ '': sidebarOpen && !isMobile }">
            <div class="container mx-auto px-4 py-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl md:text-4xl font-bold text-gray-800 dark:text-white">Upload & Collaborate:
                        Seamless Photo and Video Management</h1>
                    <button @click="sidebarOpen = true" class="text-gray-600 dark:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <!-- Sections -->
                <!-- Upload Media Section -->
                <div x-show="tab === 'upload'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Upload Media</h2>
                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6 rounded"
                            role="alert">
                            {{ session('success') }}
                            @if (session('url'))
                                <br><a href="{{ session('url') }}" class="underline font-medium" target="_blank">View Media</a>
                            @endif
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded"
                            role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded"
                            role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Form -->
                    <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data"
                        @submit.prevent="handleSubmit" x-data="uploadFormData()">
                        @csrf
                        <!-- Media Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select
                                Photos/Videos (Gallery or
                                Camera - Live Events Supported)</label>
                            <div class="drag-drop-zone rounded-lg p-6 text-center cursor-pointer bg-gray-50 dark:bg-gray-700"
                                :class="{ 'dragover': isDragging }" @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event)">
                                <p class="text-gray-500 dark:text-gray-400">Drag & drop files here, or click to select (multiple allowed)</p>
                                <input type="file" name="files[]" id="file" class="hidden"
                                    accept="image/jpeg,image/png,video/mp4,video/quicktime" multiple
                                    @change="handleFileChange($event)" x-ref="fileInput">
                                <button type="button"
                                    class="mt-2 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 py-1 px-3 rounded-full text-sm"
                                    @click="$refs.fileInput.click()">
                                    Browse Files
                                </button>
                                <button type="button"
                                    class="mt-2 ml-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 py-1 px-3 rounded-full text-sm"
                                    @click="captureFromCamera">
                                    Use Camera (Live)
                                </button>
                            </div>
                            <!-- Preview -->
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4" x-show="previews.length > 0">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-700">
                                        <img :src="preview.url" alt="Preview" class="preview-canvas mx-auto rounded-lg shadow"
                                            x-show="preview.type.startsWith('image/')">
                                        <video :id="'preview-video-' + index" :src="preview.url" controls
                                            class="preview-video mx-auto rounded-lg shadow"
                                            x-show="preview.type.startsWith('video/')"></video>
                                    </div>
                                </template>
                            </div>
                            @error('files.*')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Title, Description, License, Featured, Tags -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="title"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" name="title" id="title" placeholder="Enter media title"
                                    class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    required>
                            </div>
                            <div>
                                <label for="folder_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Folder/Memory Name</label>
                                <input type="text" name="folder_name" id="folder_name" placeholder="Enter folder or memory name"
                                    class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3" placeholder="Enter description"
                                class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                        </div>
                        <!--<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">-->
                        <!--    <div>-->
                        <!--        <label for="license_type"-->
                        <!--            class="block text-sm font-medium text-gray-700 dark:text-gray-300">License-->
                        <!--            Type</label>-->
                        <!--        <select name="license_type" id="license_type"-->
                        <!--            class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"-->
                        <!--            required>-->
                        <!--            <option value="personal">Personal</option>-->
                        <!--            <option value="commercial">Commercial</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--    <div class="flex items-center mt-6 md:mt-0">-->
                        <!--        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}-->
                        <!--            class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">-->
                        <!--        <label for="is_featured" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">-->
                        <!--            Mark as Featured-->
                        <!--        </label>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!--@error('is_featured')-->
                        <!--    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>-->
                        <!--@enderror-->
                        <!--<div class="mb-6">-->
                        <!--    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tags-->
                        <!--        (AI Auto-Tags +-->
                        <!--        Custom)</label>-->
                        <!--    <input type="text" name="tags" id="tags" placeholder="e.g., nature, portrait"-->
                        <!--        class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"-->
                        <!--        x-model="tags">-->
                        <!--    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">AI auto-tags: faces, date/time,-->
                        <!--        location. Edit as needed.-->
                        <!--    </p>-->
                        <!--</div>-->
                        <input type="hidden" name="location" x-model="location">
                        <!-- Progress Bar -->
                        <div x-show="progress > 0" class="mb-4">
                            <div class="bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: progress + '%' }"></div>
                            </div>
                        </div>
                        <!-- Submit -->
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            Upload Media
                        </button>
                    </form>
                </div>
                <!-- Edit & Enhance Section -->
                <div x-show="tab === 'edit'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">AI-Powered Edit &
                        Enhance</h2>
                    <p class="text-center mb-6 text-gray-700 dark:text-gray-300">Open a photo/video to edit. (In full
                        app, load from server; here, assume
                        from upload preview)</p>
                    <div x-data="editData()">
                        <div class="edit-tools grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
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
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-700 dark:text-gray-300">Brightness</label>
                                    <input type="range" min="-100" max="100" x-model="brightness" @input="applyFilters"
                                        class="w-full">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-sm text-gray-700 dark:text-gray-300">Contrast</label>
                                    <input type="range" min="-100" max="100" x-model="contrast" @input="applyFilters"
                                        class="w-full">
                                </div>
                                <button type="button"
                                    class="w-full mt-2 bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700"
                                    @click="applyFilter">Apply Filter</button>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Advanced AI Tools
                                </h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="sharpen">Sharpen (AI)</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="colorCorrect">Color Correct (AI)</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="removeBackground">Background Removal (AI)</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="skinSmoothing">Skin Smoothing (AI)</button>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Fun Features</h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="addSticker">Add AR Sticker/Overlay</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="addText">Add Text/Caption</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="createGif">Create GIF</button>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Preview & Save
                                </h3>
                                <button type="button"
                                    class="w-full bg-blue-600 text-white py-2 px-3 rounded hover:bg-blue-700"
                                    @click="previewSave">Preview & Save (Watermark Applied)</button>
                            </div>
                        </div>
                        <!-- Canvas for Image Edit -->
                        <canvas id="edit-canvas"
                            class="preview-canvas mx-auto mt-4 border border-gray-300 dark:border-gray-600 rounded-lg"></canvas>
                        <!-- Video Player for Video Edit -->
                        <video id="edit-video"
                            class="preview-video mx-auto mt-4 video-js border border-gray-300 dark:border-gray-600 rounded-lg"
                            controls></video>
                    </div>
                </div>
                <!-- Advanced Editing Tools (Pro) -->
                <div x-show="tab === 'advanced'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Advanced Editing Tools
                        (Pro Version)</h2>
                    <div x-data="advancedEditData()">
                        <div class="edit-tools grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">AI-Powered
                                    Backgrounds <span class="pro-badge">Pro</span></h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="backgroundBlur">Background Blur (Bokeh)</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="virtualBackground">Virtual Backgrounds (AI)</button>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Advanced AI
                                    Filters <span class="pro-badge">Pro</span></h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="neuralStyle">Neural Style Transfer</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="skinToneAdjust">Skin Tone Adjustment</button>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Layer-Based
                                    Editing <span class="pro-badge">Pro</span></h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="addLayer">Add Layer</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="masking">Masking</button>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Video Editing
                                    <span class="pro-badge">Pro</span></h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="trimVideo">Trim & Cut</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="slowMotion">Slow Motion/Time-Lapse</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="videoEnhancer">AI Video Enhancer</button>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-center font-medium mb-2 text-gray-800 dark:text-white">Batch Processing
                                    <span class="pro-badge">Pro</span></h3>
                                <div class="flex flex-wrap justify-center">
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="batchEdit">Mass Editing</button>
                                    <button type="button"
                                        class="bg-gray-200 dark:bg-gray-600 py-1 px-3 rounded m-1 hover:bg-gray-300 dark:hover:bg-gray-500"
                                        @click="aiSuggestions">AI-Driven Suggestions</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Collaborate & Share Section -->
                <div x-show="tab === 'collaborate'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Collaborate & Share
                    </h2>
                    <div class="space-y-8">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Shared Albums</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Create collaborative albums and invite
                                others for real-time editing.</p>
                            <!-- Placeholder for album creation form -->
                            <input type="text" placeholder="Album Name"
                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mb-2 p-2">
                            <input type="email" placeholder="Invite Emails (comma-separated)"
                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mb-2 p-2">
                            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Create
                                Album</button>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Comment & Feedback
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Leave comments and vote on images.</p>
                            <!-- Placeholder -->
                            <textarea placeholder="Add Comment"
                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mb-2 p-2 h-24"></textarea>
                            <div class="flex space-x-2">
                                <button
                                    class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700">Post
                                    Comment</button>
                                <button
                                    class="flex-1 bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700">Vote</button>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Share Media</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Share to social media or export to cloud.
                            </p>
                            <div class="flex flex-wrap justify-center space-x-2">
                                <button class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 m-1">Share
                                    to Instagram</button>
                                <button class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 m-1">Share
                                    to Facebook</button>
                                <button class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 m-1">Export
                                    to Cloud</button>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Watermark Removal
                                (Pro) <span class="pro-badge">Pro</span></h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Remove watermark after purchase.</p>
                            <button
                                class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700">Remove
                                Watermark</button>
                        </div>
                    </div>
                </div>
                <!-- Pricing Section -->
                <div x-show="tab === 'pricing'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Pricing & Subscription
                        Options</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div
                            class="p-6 border rounded-lg shadow bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:shadow-xl transition">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Free</h3>
                            <p class="text-gray-700 dark:text-gray-300">Access to basic editing tools, limited storage,
                                and watermarked images.</p>
                            <ul class="mt-4 list-disc pl-6 text-gray-700 dark:text-gray-300">
                                <li>Basic features</li>
                                <li>Limited storage</li>
                                <li>Watermarks</li>
                            </ul>
                        </div>
                        <div
                            class="p-6 border rounded-lg shadow bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 hover:shadow-xl transition">
                            <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-white">Pro</h3>
                            <p class="text-gray-700 dark:text-gray-300">Unlock advanced features like AI-powered tools,
                                background removal, collaboration, and more.</p>
                            <ul class="mt-4 list-disc pl-6 text-gray-700 dark:text-gray-300">
                                <li>Advanced AI tools</li>
                                <li>Unlimited storage</li>
                                <li>No watermarks</li>
                                <li>Collaboration</li>
                            </ul>
                            <p class="mt-4 font-bold text-gray-800 dark:text-white">Subscription Plans:</p>
                            <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300">
                                <li>Monthly ($9.99)</li>
                                <li>Yearly ($99.99)</li>
                                <li>Lifetime ($199.99)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Future Features Section -->
                <div x-show="tab === 'future'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center mb-8 text-gray-800 dark:text-white">Future Feature
                        Requests</h2>
                    <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                        <li class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <span class="font-semibold">Augmented Reality Enhancements:</span> More interactive AR
                            filters for photos and live events.
                        </li>
                        <li class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <span class="font-semibold">AI-Powered Creative Suite:</span> Advanced tools for graphic
                            designers and photographers (like object
                            recognition, intelligent photo composites, and style guides).
                        </li>
                    </ul>
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
                            if (file.type.startsWith('video/')) {
                                // Initialize videojs if needed, but since multiple, handle dynamically
                            }
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
                videoPlayer: null,
                brightness: 0,
                contrast: 0,
                init() {
                    const img = new Image();
                    img.src = 'placeholder-image.jpg'; // Assume a placeholder or from upload
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
                    this.videoPlayer = videojs('edit-video');
                    this.videoPlayer.src('placeholder-video.mp4'); // Assume placeholder
                },
                rotate(deg) {
                    const active = this.canvas.getActiveObject();
                    if (active) {
                        active.rotate((active.angle + deg) % 360);
                        this.canvas.renderAll();
                    }
                },
                applyFilters() {
                    const active = this.canvas.getActiveObject();
                    if (active) {
                        active.filters = [
                            new fabric.Image.filters.Brightness({ brightness: this.brightness / 100 }),
                            new fabric.Image.filters.Contrast({ contrast: this.contrast / 100 }),
                        ];
                        active.applyFilters();
                        this.canvas.renderAll();
                    }
                },
                crop() {
                    alert('Crop tool activated (select area)');
                },
                applyFilter() {
                    alert('Filter applied');
                },
                sharpen() {
                    alert('AI Sharpen applied');
                },
                colorCorrect() {
                    alert('AI Color Correction applied');
                },
                removeBackground() {
                    alert('AI Background Removed');
                },
                skinSmoothing() {
                    alert('AI Skin Smoothing applied');
                },
                addSticker() {
                    fabric.Image.fromURL('https://example.com/sticker.png', (img) => {
                        img.scale(0.2);
                        this.canvas.add(img);
                        this.canvas.renderAll();
                    });
                },
                addText() {
                    const text = new fabric.Textbox('Enter Text', { left: 100, top: 100, fontSize: 20 });
                    this.canvas.add(text);
                    this.canvas.renderAll();
                },
                createGif() {
                    alert('GIF created from photo');
                },
                previewSave() {
                    alert('Preview saved with watermark');
                }
            };
        }
        function advancedEditData() {
            return {
                backgroundBlur() {
                    alert('Background Blur applied (Pro)');
                },
                virtualBackground() {
                    alert('Virtual Background applied (Pro)');
                },
                neuralStyle() {
                    alert('Neural Style Transfer applied (Pro)');
                },
                skinToneAdjust() {
                    alert('Skin Tone Adjusted (Pro)');
                },
                addLayer() {
                    alert('New Layer added (Pro)');
                },
                masking() {
                    alert('Masking tool activated (Pro)');
                },
                trimVideo() {
                    alert('Video trimmed (Pro)');
                },
                slowMotion() {
                    alert('Slow Motion applied (Pro)');
                },
                videoEnhancer() {
                    alert('AI Video Enhanced (Pro)');
                },
                batchEdit() {
                    alert('Batch Editing started (Pro)');
                },
                aiSuggestions() {
                    alert('AI Suggestions provided (Pro)');
                }
            };
        }
    </script>
</body>
</html>