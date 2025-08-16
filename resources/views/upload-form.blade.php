<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Photo Upload, Edit & Manage</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/caman@4.1.2/dist/caman.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ar.js@2.2.2/aframe/build/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ar.js@2.2.2/aframe/aframe-ar.min.js"></script>
    <style>
        #editModal,
        #loginModal,
        #shareModal {
            display: none;
        }

        #editModal.show,
        #loginModal.show,
        #shareModal:not(.hidden) {
            display: block;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .image-card {
            border: 1px solid #ddd;
            padding: 0.5rem;
            border-radius: 0.5rem;
            position: relative;
        }

        .image-card.active {
            border: 2px solid #10b981;
        }

        .image-card img {
            width: 100%;
            height: auto;
            border-radius: 0.25rem;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-start pt-10">
    @auth
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
            <h2 class="text-3xl font-bold mb-8 text-center">Upload, Edit & Manage Photos</h2>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('success') }}
                    @if (session('url'))
                        <br><a href="{{ session('url') }}" class="underline" target="_blank">View File</a>
                    @endif
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Upload Form -->
            <form id="uploadForm" action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="mb-8">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">Choose Photo</label>
                    <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                            file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" accept=".jpg,.jpeg,.png">
                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-200">
                    Upload Photo
                </button>
            </form>

            <!-- Manage Uploaded Images -->
            <div class="mb-8">
                <h3 class="text-2xl font-semibold mb-4">Your Uploaded Photos</h3>
                <div class="image-grid">
                    @foreach ($photos as $photo)
                        <div class="image-card {{ $photo->is_active_for_merch ? 'active' : '' }}" id="photo-{{ $photo->id }}">
                            <img src="{{ $photo->url }}" alt="Uploaded Photo" class="mb-2">
                            <div class="text-sm mb-2">
                                <strong>Tags:</strong>
                                <ul class="list-disc pl-4">
                                    @foreach ($photo->tags ?? [] as $key => $value)
                                        <li>{{ ucfirst($key) }}: {{ $value }}</li>
                                    @endforeach
                                </ul>
                                <!-- Tag Edit Form -->
                                <form action="{{ route('update.tags', $photo->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="tags" placeholder="Edit tags (JSON)"
                                        class="w-full p-1 border rounded mb-1">
                                    <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">Update
                                        Tags</button>
                                </form>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="loadImageForEdit('{{ $photo->url }}', {{ $photo->id }})"
                                    class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 text-sm">Edit</button>
                                <form action="{{ route('delete', $photo->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                                <button onclick="shareImage({{ $photo->id }})"
                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">Share</button>
                            </div>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" {{ $photo->is_active_for_merch ? 'checked' : '' }}
                                        onchange="toggleMerchStatus({{ $photo->id }})"
                                        class="form-checkbox h-5 w-5 text-green-600" aria-label="Mark photo for merchandise">
                                    <span class="ml-2 text-sm">Use for Merch</span>
                                </label>
                            </div>
                            <div id="collaborators-{{ $photo->id }}" class="text-sm mt-2">
                                <strong>Collaborators:</strong>
                                @foreach ($photo->collaborators as $collab)
                                    <p>{{ $collab->email }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($photos->isEmpty())
                    <p class="text-center text-gray-500">No photos uploaded yet.</p>
                @endif
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-4xl overflow-y-auto max-h-[90vh]">
                <h3 class="text-xl font-bold mb-4">Edit Photo</h3>
                <div class="flex mb-4 border-b">
                    <button class="px-4 py-2 tab-button" data-tab="basic">Basic Tools</button>
                    <button class="px-4 py-2 tab-button" data-tab="ai">AI Tools</button>
                    <button class="px-4 py-2 tab-button" data-tab="ar">AR Stickers</button>
                </div>
                <div id="basic" class="tab-content">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <button onclick="applyCrop()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Crop</button>
                        <button onclick="applyRotate()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Rotate 90°</button>
                    </div>
                    <div class="mb-4">
                        <label for="brightness">Brightness:</label>
                        <input type="range" id="brightness" min="-100" max="100" value="0" class="w-full">
                    </div>
                    <div class="mb-4">
                        <label for="contrast">Contrast:</label>
                        <input type="range" id="contrast" min="-100" max="100" value="0" class="w-full">
                    </div>
                    <div class="mb-4">
                        <label for="filter">Filter:</label>
                        <select id="filter" class="w-full p-2 border rounded">
                            <option value="">None</option>
                            <option value="vintage">Vintage</option>
                            <option value="lomo">Lomo</option>
                            <option value="clarity">Clarity</option>
                        </select>
                    </div>
                </div>
                <div id="ai" class="tab-content">
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <button onclick="applySharpen()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Sharpen</button>
                        <button onclick="applyColorCorrect()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Color Correct</button>
                        <button onclick="removeBackground()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Remove Background</button>
                    </div>
                </div>
                <div id="ar" class="tab-content">
                    <div class="mb-4">
                        <label for="sticker">Sticker:</label>
                        <select id="sticker" class="w-full p-2 border rounded mb-2">
                            <option value="star.png">Star</option>
                            <option value="heart.png">Heart</option>
                            <option value="emoji.png">Emoji</option>
                        </select>
                        <button onclick="applySticker()"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Sticker</button>
                    </div>
                    <div id="arPreview" class="h-48 bg-gray-200 mb-4"></div>
                </div>
                <canvas id="editCanvas" class="mb-4 border border-gray-300"></canvas>
                <div class="flex justify-end space-x-2">
                    <button onclick="previewImage()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Preview</button>
                    <button onclick="saveImage()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save
                        Changes</button>
                    <button onclick="closeEditModal()"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Close</button>
                </div>
            </div>
        </div>

        <!-- Share Modal -->
        <div id="shareModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h3 class="text-xl font-bold mb-4">Share Photo</h3>
                <input type="email" id="collaboratorEmail" placeholder="Enter collaborator's email"
                    class="w-full p-2 border rounded mb-4">
                <div class="flex justify-end space-x-2">
                    <button onclick="submitShare()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Share</button>
                    <button onclick="closeShareModal()"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div id="toast" class="toast hidden bg-green-600 text-white p-4 rounded shadow-lg">
            <span id="toastMessage"></span>
        </div>
    @else
        <!-- Login Modal -->
        <div id="loginModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center show">
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h3 class="text-xl font-bold mb-4">Login Required</h3>
                <p class="mb-4">Please log in to upload, edit, and manage photos.</p>
                <a href="{{ route('login') }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Go to
                    Login</a>
            </div>
        </div>
    @endauth

    <script>
        let currentImageUrl = '';
        let currentPhotoId = null;
        let canvas = document.getElementById('editCanvas');
        let ctx = canvas.getContext('2d');
        let originalImage = new Image();

        // Initialize CSRF for AJAX
        function setupAjax() {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            return { 'X-CSRF-TOKEN': token };
        }

        // Show Toast Notification
        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        // Toggle Merch Status
        async function toggleMerchStatus(photoId) {
            try {
                const response = await fetch('{{ route('toggle.merch') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', ...setupAjax() },
                    body: JSON.stringify({ photo_id: photoId })
                });
                const data = await response.json();
                if (data.success) {
                    const card = document.getElementById(`photo-${photoId}`);
                    if (data.is_active) {
                        card.classList.add('active');
                        showToast('Photo marked for merchandise');
                    } else {
                        card.classList.remove('active');
                        showToast('Photo unmarked from merchandise');
                    }
                } else {
                    alert('Failed to toggle merch status');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }


        document.getElementById('uploadForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch('{{ route('upload') }}', {
                    method: 'POST',
                    body: formData,
                    headers: setupAjax()
                });
                const data = await response.json();
                if (data.success) {
                    currentImageUrl = data.url;
                    location.reload();
                } else {
                    alert(data.error || 'Upload failed');
                }
            } catch (error) {
                alert('Upload failed: ' + error.message);
            }
        });

        // Load Image for Edit
        function loadImageForEdit(url, id) {
            currentImageUrl = url;
            currentPhotoId = id;
            loadImageToCanvas(url);
            openEditModal();
        }

        // Load Image to Canvas
        function loadImageToCanvas(url) {
            originalImage.src = url;
            originalImage.onload = () => {
                canvas.width = originalImage.width / 2;
                canvas.height = originalImage.height / 2;
                ctx.drawImage(originalImage, 0, 0, canvas.width, canvas.height);
            };
        }

        // Tab Switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('bg-blue-500', 'text-white'));
                document.getElementById(button.dataset.tab).classList.add('active');
                button.classList.add('bg-blue-500', 'text-white');
            });
        });
        document.querySelector('.tab-button[data-tab="basic"]').click();

        // Basic Editing (CamanJS)
        function applyCrop() {
            Caman('#editCanvas', function () {
                this.crop(canvas.width * 0.8, canvas.height * 0.8, canvas.width * 0.1, canvas.height * 0.1);
                this.render();
            });
        }

        function applyRotate() {
            Caman('#editCanvas', function () {
                this.rotate(90);
                this.render();
            });
        }

        document.getElementById('brightness').addEventListener('input', (e) => {
            Caman('#editCanvas', function () {
                this.brightness(parseInt(e.target.value));
                this.render();
            });
        });

        document.getElementById('contrast').addEventListener('input', (e) => {
            Caman('#editCanvas', function () {
                this.contrast(parseInt(e.target.value));
                this.render();
            });
        });

        document.getElementById('filter').addEventListener('change', (e) => {
            if (e.target.value) {
                Caman('#editCanvas', function () {
                    this.revert(false);
                    this[e.target.value]();
                    this.render();
                });
            }
        });

        // AI Tools (Backend Calls)
        async function applySharpen() {
            try {
                const response = await fetch('{{ route('edit.sharpen') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', ...setupAjax() },
                    body: JSON.stringify({ image: currentImageUrl })
                });
                const data = await response.json();
                if (data.url) {
                    currentImageUrl = data.url;
                    loadImageToCanvas(data.url);
                } else {
                    alert('Sharpen failed');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function applyColorCorrect() {
            try {
                const response = await fetch('{{ route('edit.colorCorrect') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', ...setupAjax() },
                    body: JSON.stringify({ image: currentImageUrl })
                });
                const data = await response.json();
                if (data.url) {
                    currentImageUrl = data.url;
                    loadImageToCanvas(data.url);
                } else {
                    alert('Color correction failed');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function removeBackground() {
            try {
                const response = await fetch('{{ route('edit.removeBackground') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', ...setupAjax() },
                    body: JSON.stringify({ image: currentImageUrl })
                });
                const data = await response.json();
                if (data.url) {
                    currentImageUrl = data.url;
                    loadImageToCanvas(data.url);
                } else {
                    alert('Background removal failed');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // AR Stickers
        function applySticker() {
            const sticker = document.getElementById('sticker').value;
            const stickerImg = new Image();
            stickerImg.src = `/stickers/${sticker}`;
            stickerImg.onload = () => {
                ctx.drawImage(stickerImg, canvas.width / 4, canvas.height / 4, canvas.width / 4, canvas.height / 4);
            };
        }

        // Preview with Watermark
        function previewImage() {
            const previewCanvas = document.createElement('canvas');
            previewCanvas.width = canvas.width;
            previewCanvas.height = canvas.height;
            const pCtx = previewCanvas.getContext('2d');
            pCtx.drawImage(canvas, 0, 0);
            pCtx.font = 'bold 40px Arial';
            pCtx.fillStyle = 'rgba(255, 255, 255, 0.6)';
            pCtx.fillText('Watermark - Purchase to Remove', 20, canvas.height - 20);
            const previewWindow = window.open('');
            previewWindow.document.write('<img src="' + previewCanvas.toDataURL() + '"/>');
        }

        // Save Changes
        async function saveImage() {
            try {
                const imageData = canvas.toDataURL('image/png');
                const response = await fetch('{{ route('save') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', ...setupAjax() },
                    body: JSON.stringify({ image: imageData, photo_id: currentPhotoId })
                });
                const data = await response.json();
                if (data.success) {
                    alert('Changes saved!');
                    closeEditModal();
                    location.reload();
                } else {
                    alert('Save failed');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // Share Functions
        let currentSharePhotoId = null;
        function shareImage(photoId) {
            currentSharePhotoId = photoId;
            document.getElementById('shareModal').classList.remove('hidden');
        }

        async function submitShare() {
            const email = document.getElementById('collaboratorEmail').value;
            if (!email) return alert('Enter an email');
            try {
                const response = await fetch('{{ route('share') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', ...setupAjax() },
                    body: JSON.stringify({ photo_id: currentSharePhotoId, email })
                });
                const data = await response.json();
                if (data.success) {
                    closeShareModal();
                    location.reload();
                } else {
                    alert('Share failed: ' + data.error);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function closeShareModal() {
            document.getElementById('shareModal').classList.add('hidden');
            document.getElementById('collaboratorEmail').value = '';
        }

        // Modal Controls
        function openEditModal() { document.getElementById('editModal').classList.add('show'); }
        function closeEditModal() { document.getElementById('editModal').classList.remove('show'); }
    </script>
</body>

</html>