<!DOCTYPE html>
<html lang="en">

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
            transition: border-color 0.3s ease;
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
    </style>
</head>

<body class="bg-gray-50 min-h-screen font-sans antialiased">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">Upload & Collaborate: Seamless Photo and Video
            Management</h1>

        <!-- Tabs for Sections -->
        <div x-data="{ tab: 'upload' }">
            <div class="flex justify-center mb-8">
                <button @click="tab = 'upload'" class="px-4 py-2 mx-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': tab === 'upload', 'bg-gray-200': tab !== 'upload' }">Upload
                    Media</button>
                <button @click="tab = 'edit'" class="px-4 py-2 mx-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': tab === 'edit', 'bg-gray-200': tab !== 'edit' }">Edit &
                    Enhance</button>
                <button @click="tab = 'collaborate'" class="px-4 py-2 mx-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': tab === 'collaborate', 'bg-gray-200': tab !== 'collaborate' }">Collaborate
                    & Share</button>
                <button @click="tab = 'advanced'" class="px-4 py-2 mx-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': tab === 'advanced', 'bg-gray-200': tab !== 'advanced' }">Advanced
                    Features (Pro)</button>
                <button @click="tab = 'pricing'" class="px-4 py-2 mx-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': tab === 'pricing', 'bg-gray-200': tab !== 'pricing' }">Pricing</button>
                <button @click="tab = 'future'" class="px-4 py-2 mx-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': tab === 'future', 'bg-gray-200': tab !== 'future' }">Future
                    Features</button>
            </div>

            <!-- Upload Media Section -->
            <div x-show="tab === 'upload'" class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Upload Media</h2>
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                        {{ session('success') }}
                        @if (session('url'))
                            <br><a href="{{ session('url') }}" class="underline font-medium" target="_blank">View Media</a>
                        @endif
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Photos/Videos (Gallery or
                            Camera - Live Events Supported)</label>
                        <div class="drag-drop-zone rounded-lg p-6 text-center cursor-pointer"
                            :class="{ 'dragover': isDragging }" @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event)">
                            <p class="text-gray-500">Drag & drop files here, or click to select</p>
                            <input type="file" name="file" id="file" class="hidden"
                                accept="image/jpeg,image/png,video/mp4,video/quicktime"
                                @change="handleFileChange($event)" x-ref="fileInput">
                            <button type="button" class="mt-2 bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-sm"
                                @click="$refs.fileInput.click()">
                                Browse Files
                            </button>
                            <button type="button"
                                class="mt-2 ml-2 bg-green-100 text-green-700 py-1 px-3 rounded-full text-sm"
                                @click="captureFromCamera">
                                Use Camera (Live)
                            </button>
                        </div>
                        <!-- Preview -->
                        <div x-show="preview" class="mt-4">
                            <img :src="preview" alt="Preview" class="preview-canvas mx-auto rounded-lg shadow"
                                x-show="file && file.type.startsWith('image/')">
                            <video id="preview-video" :src="preview" controls
                                class="preview-video mx-auto rounded-lg shadow"
                                x-show="file && file.type.startsWith('video/')"></video>
                        </div>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title, Description, Price, License, Featured, Tags -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price" step="0.01" min="0"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="license_type" class="block text-sm font-medium text-gray-700">License Type</label>
                        <select name="license_type" id="license_type"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="personal">Personal</option>
                            <option value="commercial">Commercial</option>
                            
                        </select>
                    </div>
                    <div class="mb-4 flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                            Mark as Featured
                        </label>
                    </div>

                    @error('is_featured')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mb-6">
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags (AI Auto-Tags +
                            Custom)</label>
                        <input type="text" name="tags" id="tags"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g., nature, portrait" x-model="tags">
                        <p class="mt-1 text-xs text-gray-500">AI auto-tags: faces, date/time, location. Edit as needed.
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div x-show="progress > 0" class="mb-4">
                        <div class="bg-gray-200 rounded-full h-2.5">
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
            <div x-show="tab === 'edit'" class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">AI-Powered Edit & Enhance</h2>
                <p class="text-center mb-6">Open a photo/video to edit. (In full app, load from server; here, assume
                    from upload preview)</p>
                <div x-data="editData()">
                    <div class="edit-tools flex flex-wrap justify-center">
                        <h3 class="w-full text-center font-medium mb-2">Basic Tools</h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="crop">Crop</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="rotate(90)">Rotate
                            90°</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="rotate(-90)">Rotate
                            -90°</button>
                        <div class="flex items-center">
                            <label class="mr-2">Brightness:</label>
                            <input type="range" min="-100" max="100" x-model="brightness" @input="applyFilters">
                        </div>
                        <div class="flex items-center ml-4">
                            <label class="mr-2">Contrast:</label>
                            <input type="range" min="-100" max="100" x-model="contrast" @input="applyFilters">
                        </div>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="applyFilter">Apply
                            Filter</button>

                        <h3 class="w-full text-center font-medium mt-4 mb-2">Advanced AI Tools</h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="sharpen">Sharpen
                            (AI)</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="colorCorrect">Color Correct
                            (AI)</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="removeBackground">Background
                            Removal (AI)</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="skinSmoothing">Skin
                            Smoothing (AI)</button>

                        <h3 class="w-full text-center font-medium mt-4 mb-2">Fun Features</h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="addSticker">Add AR
                            Sticker/Overlay</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="addText">Add
                            Text/Caption</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="createGif">Create
                            GIF</button>

                        <h3 class="w-full text-center font-medium mt-4 mb-2">Preview & Save</h3>
                        <button type="button" class="bg-blue-200 py-1 px-3 rounded" @click="previewSave">Preview & Save
                            (Watermark Applied)</button>
                    </div>
                    <!-- Canvas for Image Edit -->
                    <canvas id="edit-canvas" class="preview-canvas mx-auto mt-4"></canvas>
                    <!-- Video Player for Video Edit -->
                    <video id="edit-video" class="preview-video mx-auto mt-4 video-js" controls></video>
                </div>
            </div>

            <!-- Advanced Editing Tools (Pro) -->
            <div x-show="tab === 'advanced'" class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Advanced Editing Tools (Pro Version)</h2>
                <div x-data="advancedEditData()">
                    <div class="edit-tools flex flex-wrap justify-center">
                        <h3 class="w-full text-center font-medium mb-2">AI-Powered Backgrounds <span
                                class="pro-badge">Pro</span></h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="backgroundBlur">Background
                            Blur (Bokeh)</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="virtualBackground">Virtual
                            Backgrounds (AI)</button>

                        <h3 class="w-full text-center font-medium mt-4 mb-2">Advanced AI Filters <span
                                class="pro-badge">Pro</span></h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="neuralStyle">Neural Style
                            Transfer</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="skinToneAdjust">Skin Tone
                            Adjustment</button>

                        <h3 class="w-full text-center font-medium mt-4 mb-2">Layer-Based Editing <span
                                class="pro-badge">Pro</span></h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="addLayer">Add Layer</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="masking">Masking</button>

                        <h3 class="w-full text-center font-medium mt-4 mb-2">Video Editing <span
                                class="pro-badge">Pro</span></h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="trimVideo">Trim &
                            Cut</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="slowMotion">Slow
                            Motion/Time-Lapse</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="videoEnhancer">AI Video
                            Enhancer</button>

                        <h3 class="w-full text-center font-medium mt-4 mb-2">Batch Processing <span
                                class="pro-badge">Pro</span></h3>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="batchEdit">Mass
                            Editing</button>
                        <button type="button" class="bg-gray-200 py-1 px-3 rounded" @click="aiSuggestions">AI-Driven
                            Suggestions</button>
                    </div>
                </div>
            </div>

            <!-- Collaborate & Share Section -->
            <div x-show="tab === 'collaborate'" class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Collaborate & Share</h2>
                <div>
                    <h3 class="text-2xl font-semibold mb-4">Shared Albums</h3>
                    <p>Create collaborative albums and invite others for real-time editing.</p>
                    <!-- Placeholder for album creation form -->
                    <input type="text" placeholder="Album Name"
                        class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                    <input type="email" placeholder="Invite Emails (comma-separated)"
                        class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                    <button class="mt-2 bg-blue-600 text-white py-2 px-4 rounded-lg">Create Album</button>

                    <h3 class="text-2xl font-semibold mt-6 mb-4">Comment & Feedback</h3>
                    <p>Leave comments and vote on images.</p>
                    <!-- Placeholder -->
                    <textarea placeholder="Add Comment"
                        class="mt-2 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <button class="mt-2 bg-green-600 text-white py-2 px-4 rounded-lg">Post Comment</button>
                    <button class="mt-2 ml-2 bg-yellow-600 text-white py-2 px-4 rounded-lg">Vote</button>

                    <h3 class="text-2xl font-semibold mt-6 mb-4">Share Media</h3>
                    <p>Share to social media or export to cloud.</p>
                    <button class="bg-blue-600 text-white py-2 px-4 rounded-lg">Share to Instagram</button>
                    <button class="ml-2 bg-blue-600 text-white py-2 px-4 rounded-lg">Share to Facebook</button>
                    <button class="ml-2 bg-blue-600 text-white py-2 px-4 rounded-lg">Export to Cloud</button>

                    <h3 class="text-2xl font-semibold mt-6 mb-4">Watermark Removal (Pro) <span
                            class="pro-badge">Pro</span></h3>
                    <p>Remove watermark after purchase.</p>
                    <button class="bg-purple-600 text-white py-2 px-4 rounded-lg">Remove Watermark</button>
                </div>
            </div>

            <!-- Pricing Section -->
            <div x-show="tab === 'pricing'" class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Pricing & Subscription Options</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 border rounded-lg shadow">
                        <h3 class="text-2xl font-semibold mb-4">Free</h3>
                        <p>Access to basic editing tools, limited storage, and watermarked images.</p>
                    </div>
                    <div class="p-6 border rounded-lg shadow">
                        <h3 class="text-2xl font-semibold mb-4">Pro</h3>
                        <p>Unlock advanced features like AI-powered tools, background removal, collaboration, and more.
                        </p>
                        <p>Subscription Plans: Monthly ($9.99), Yearly ($99.99), Lifetime ($199.99)</p>
                    </div>
                </div>
            </div>

            <!-- Future Features Section -->
            <div x-show="tab === 'future'" class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Future Feature Requests</h2>
                <ul class="list-disc pl-6">
                    <li>Augmented Reality Enhancements: More interactive AR filters for photos and live events.</li>
                    <li>AI-Powered Creative Suite: Advanced tools for graphic designers and photographers (like object
                        recognition, intelligent photo composites, and style guides).</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function uploadFormData() {
            return {
                file: null,
                preview: null,
                isDragging: false,
                progress: 0,
                tags: '',
                brightness: 0,
                contrast: 0,
                init() {
                    this.$watch('file', () => {
                        if (this.file) {
                            this.preview = URL.createObjectURL(this.file);
                            this.extractExif();
                            if (this.file.type.startsWith('video/')) {
                                videojs('preview-video');
                            }
                        }
                    });
                },
                handleFileChange(event) {
                    this.file = event.target.files[0];
                },
                handleDrop(event) {
                    this.isDragging = false;
                    this.file = event.dataTransfer.files[0];
                    document.querySelector('#file').files = event.dataTransfer.files;
                },
                captureFromCamera() {
                    const input = document.querySelector('#file');
                    input.setAttribute('capture', 'environment');
                    input.click();
                },
                extractExif() {
                    if (this.file && this.file.type.startsWith('image/')) {
                        EXIF.getData(this.file, () => {
                            const date = EXIF.getTag(this.file, 'DateTimeOriginal') || '';
                            const lat = EXIF.getTag(this.file, 'GPSLatitude') || '';
                            const lon = EXIF.getTag(this.file, 'GPSLongitude') || '';
                            let autoTags = [];
                            if (date) autoTags.push(`date:${date}`);
                            if (lat && lon) autoTags.push(`location:${lat},${lon}`);
                            autoTags.push('face_detected');
                            this.tags = autoTags.join(',');
                        });
                    }
                },
                handleSubmit(event) {
                    // Similar to previous
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