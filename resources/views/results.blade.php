<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Match Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles for modal and enhanced responsiveness */
        .modal {
            transition: opacity 0.3s ease-in-out;
        }
        .modal img {
            max-height: 80vh;
            max-width: 90vw;
        }
        @media (max-width: 640px) {
            .grid-cols-2-mobile {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gray-100">
    <div class="container mx-auto max-w-6xl px-2 sm:px-4 py-8">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-4 sm:p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-full shadow-lg mb-4">
                    <i class="fas fa-search text-xl mr-2"></i>
                    <h1 class="text-xl sm:text-2xl font-bold">Face Match Results</h1>
                </div>
                <p class="text-gray-600 text-sm sm:text-base">Discover your perfect matches! 🎉</p>
            </div>

            @if (session('face_matches') && count(session('face_matches')) > 0)
                <div class="grid grid-cols-1 grid-cols-2-mobile sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach (session('face_matches') as $match)
                        <div class="group bg-gradient-to-br from-white to-gray-50 rounded-2xl p-4 sm:p-6 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 overflow-hidden cursor-pointer" onclick="openModal('{{ $match['url'] }}', '{{ $match['title'] }}')">
                            <!-- Image container -->
                            <div class="relative mb-4">
                                <img src="{{ $match['url'] }}" 
                                     alt="{{ $match['title'] }}" 
                                     class="w-full h-[150px] sm:h-[200px] lg:h-[250px] object-cover rounded-xl group-hover:scale-105 transition-transform duration-300">
                                <!-- Gradient overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                                <!-- Similarity badge -->
                                <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold shadow-lg">
                                    {{ number_format($match['similarity'], 0) }}% <i class="fas fa-star ml-1"></i>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 truncate">{{ $match['title'] }}</h2>
                                <div class="flex items-center justify-center mb-4">
                                    <div class="flex bg-green-100 rounded-full p-2">
                                        <i class="fas fa-check-circle text-green-600 text-base sm:text-lg mr-2"></i>
                                        <span class="text-green-700 font-semibold text-sm sm:text-base">Perfect Match!</span>
                                    </div>
                                </div>
                                <!-- Progress bar -->
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $match['similarity'] }}%"></div>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-500">Similarity: <span class="font-bold text-purple-600">{{ number_format($match['similarity'], 1) }}%</span></p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Stats section -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 text-sm sm:text-base">Found {{ count(session('face_matches')) }} amazing matches! ✨</p>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="relative">
                        <div class="w-20 sm:w-24 h-20 sm:h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <i class="fas fa-user-times text-3xl sm:text-4xl text-gray-400"></i>
                        </div>
                        <!-- Animated search icon -->
                        <div class="animate-pulse">
                            <i class="fas fa-search text-4xl sm:text-6xl text-gray-300 mb-6"></i>
                        </div>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-700 mb-4">No Matches Found</h2>
                    <p class="text-gray-600 text-sm sm:text-base mb-8">We couldn't find any similar faces this time. Try a different angle or lighting!</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ url('/') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 sm:px-8 py-3 rounded-full font-semibold hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 flex items-center text-sm sm:text-base">
                            <i class="fas fa-camera mr-2"></i>
                            Try Another Selfie
                        </a>
                        <a href="{{ route('photos.index') }}" class="border-2 border-gray-300 text-gray-700 px-6 sm:px-8 py-3 rounded-full font-semibold hover:bg-gray-50 transform hover:-translate-y-1 transition-all duration-300 flex items-center text-sm sm:text-base">
                            <i class="fas fa-images mr-2"></i>
                            View Gallery
                        </a>
                    </div>
                </div>
            @endif
            
            <!-- Back button -->
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors text-sm sm:text-base">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Modal for image preview -->
    <div id="imageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 modal">
        <div class="relative bg-white rounded-2xl p-4 sm:p-6 max-w-[90%] sm:max-w-3xl">
            <button onclick="closeModal()" class="absolute top-2 right-2 sm:top-4 sm:right-4 text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-xl sm:text-2xl"></i>
            </button>
            <h2 id="modalTitle" class="text-lg sm:text-xl font-bold text-center mb-4"></h2>
            <img id="modalImage" src="" alt="Preview" class="w-full rounded-xl">
        </div>
    </div>

    <script>
        function openModal(imageUrl, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            
            modalImage.src = imageUrl;
            modalTitle.textContent = title;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('imageModal')) {
                closeModal();
            }
        });
    </script>
</body>
</html>