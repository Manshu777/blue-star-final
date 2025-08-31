<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize {{ $mug->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Customize {{ $mug->name }}</h1>
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4">Mug Preview</h2>
                <img src="{{ Storage::url($mug->image_path) }}" alt="{{ $mug->name }}" class="w-full h-64 object-cover rounded">
                <p class="mt-4 text-gray-600">{{ $mug->description }}</p>
                <p class="text-lg font-bold">${{ number_format($mug->price, 2) }}</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold mb-4">Upload Your Image</h2>
                <form action="{{ route('shop.storeCustomization', $mug->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="custom_image" class="block text-sm font-medium">Custom Image (PNG/JPEG, max 2MB)</label>
                        <input type="file" name="custom_image" id="custom_image" accept="image/png,image/jpeg" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Preview:</p>
                        <div id="imagePreview" class="mt-2 w-32 h-32 border rounded flex items-center justify-center text-gray-500">
                            No image selected
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Upload Custom Image</button>
                </form>
            </div>
        </div>
        <a href="{{ route('shop.index') }}" class="mt-6 inline-block text-blue-500 hover:underline">Back to Shop</a>
    </div>
    <script>
        document.getElementById('custom_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = 'No image selected';
            }
        });
    </script>
</body>
</html>