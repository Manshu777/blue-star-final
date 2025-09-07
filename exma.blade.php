<div x-data="{ 
                        tab: 'album', 
                        selectedPhoto: null, 
                        searchTags: '', 
                        filteredPhotos: @json($photos), 
                        showPreview: false,
                        previewPhoto: null 
                    }"  x-show="tab === 'album'" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800 dark:text-white">📸 Photo Gallery</h2>
                    <!-- Recent Uploads -->
                      <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">🔍 Search by Tags</h3>
                                <input 
                                    type="text" 
                                    x-model="searchTags" 
                                    @input.debounce.500ms="filterPhotos()" 
                                    placeholder="Enter tags (e.g., Nature, Beach)" 
                                    class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white"
                                >
                            </div>
                    <div class="mb-10">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">🆕 Recent Uploads</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                            @foreach($recentUploads as $photo)
                                <div class="relative group">
                                    <img src="{{ $photo['url'] }}" class="w-full h-28 object-cover rounded-lg shadow">
                                    <div class="absolute top-1 right-1 flex space-x-1 opacity-0 gallery-button">
                                        <form action="{{ route('photos.destroy', $photo['id']) }}" method="POST"
                                              @submit.prevent="deletePhoto($event, {{ $photo['id'] }})">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center hover:bg-red-600 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                        <button @click="selectedPhoto = '{{ $photo['url'] }}'; tab = 'edit'; if(isMobile) sidebarOpen = false"
                                                class="bg-blue-500 text-white rounded-full h-6 w-6 flex items-center justify-center hover:bg-blue-600 transition">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Albums -->
                    @if(!empty($photos))
                        <div class="space-y-8">
                            @foreach($photos as $event => $eventPhotos)
                                <div x-data="{ renameOpen: false, newName: '{{ $event }}', inviteOpen: false, inviteEmail: '' }">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">📂 {{ $event ?? 'Unknown Event' }}</h3>
                                        <div class="flex space-x-2">
                                            <button @click="renameOpen = true"
                                                    class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 py-1 px-3 rounded hover:bg-gray-300 dark:hover:bg-gray-500">
                                                Rename
                                            </button>
                                            <button @click="deleteAlbum('{{ $event }}')"
                                                    class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">
                                                Delete
                                            </button>
                                            <button @click="inviteOpen = true"
                                                    class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">
                                                Invite
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Rename Form -->
                                    <div x-show="renameOpen" class="mb-4">
                                        <form @submit.prevent="renameAlbum('{{ $event }}', newName)">
                                            <input type="text" x-model="newName" class="form-input p-2 mr-2 border-gray-300 dark:border-gray-600 rounded-md"
                                                   placeholder="New event name">
                                            <button type="submit" class="bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700">Save</button>
                                            <button type="button" @click="renameOpen = false" class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 py-1 px-3 rounded hover:bg-gray-300 dark:hover:bg-gray-500">Cancel</button>
                                        </form>
                                    </div>
                                    <!-- Invite Form -->
                                    <div x-show="inviteOpen" class="mb-4">
                                        <form @submit.prevent="inviteCollaborator('{{ $event }}', inviteEmail)">
                                            <input type="email" x-model="inviteEmail" class="form-input p-2 mr-2 border-gray-300 dark:border-gray-600 rounded-md"
                                                   placeholder="Collaborator email">
                                            <button type="submit" class="bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700">Send Invite</button>
                                            <button type="button" @click="inviteOpen = false" class="bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 py-1 px-3 rounded hover:bg-gray-300 dark:hover:bg-gray-500">Cancel</button>
                                        </form>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                        @foreach($eventPhotos as $photo)
                                            <div class="relative bg-gray-50 dark:bg-gray-700 rounded-lg shadow overflow-hidden group">
                                                <img src="{{ $photo['url'] }}" class="w-full h-40 object-cover">
                                                <div class="p-2">
                                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ $photo['title'] }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $photo['location'] }}</p>
                                                    <p class="text-xs text-gray-400">{{ $photo['date'] }}</p>
                                                </div>
                                                <div class="absolute top-1 right-1 flex space-x-1 opacity-0 gallery-button">
                                                    <form action="{{ route('photos.destroy', $photo['id']) }}" method="POST"
                                                          @submit.prevent="deletePhoto($event, {{ $photo['id'] }})">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center hover:bg-red-600 transition">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <button @click="selectedPhoto = '{{ $photo['url'] }}'; tab = 'edit'; if(isMobile) sidebarOpen = false"
                                                            class="bg-blue-500 text-white rounded-full h-6 w-6 flex items-center justify-center hover:bg-blue-600 transition">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center">No photos found. Upload some 📤</p>
                    @endif
                </div>



<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('gallery', () => ({
            filterPhotos() {
                const tags = this.searchTags.toLowerCase().split(',').map(tag => tag.trim()).filter(tag => tag);
                if (!tags.length) {
                    this.filteredPhotos = @json($photos);
                    return;
                }
                const filtered = {};
                Object.entries(@json($photos)).forEach(([event, photos]) => {
                    const matchingPhotos = photos.filter(photo => 
                        tags.every(searchTag => 
                            photo.tags.some(tag => tag.toLowerCase().includes(searchTag))
                        )
                    );
                    if (matchingPhotos.length) {
                        filtered[event] = matchingPhotos;
                    }
                });
                this.filteredPhotos = filtered;
            },
            openPreview(url, photo) {
                this.previewPhoto = photo;
                this.showPreview = true;
            },
            deletePhoto(event, id) {
                if (confirm('Are you sure you want to delete this photo?')) {
                    event.target.closest('form').submit();
                }
            },
            renameAlbum(event, newName) {
                // Implement rename logic (e.g., via fetch/AJAX to backend)
                console.log(`Renaming album ${event} to ${newName}`);
            },
            deleteAlbum(event) {
                if (confirm(`Are you sure you want to delete the album ${event}?`)) {
                    console.log(`Deleting album ${event}`);
                    // Implement delete logic
                }
            },
            inviteCollaborator(event, email) {
                console.log(`Inviting ${email} to album ${event}`);
                // Implement invite logic
            }
        }));
    });
</script>


whats wrong on this code 