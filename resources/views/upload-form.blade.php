<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>File Upload to S3</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Upload File to S3</h2>

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

        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Choose File</label>
                <input type="file" name="file" id="file" class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                    file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                @error('file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Upload File
            </button>
        </form>
    </div>
</body>
</html>