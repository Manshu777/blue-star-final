<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
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