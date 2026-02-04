<div class="min-h-screen bg-gray-950 text-white pb-24">
    @livewire('search')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Posts</h1>
        </div>

        <!-- Create Post Form (Inline) -->
        <div 
            x-data="{ show: @entangle('showCreateForm') }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4"
            class="mb-2 sticky top-4 z-40"
            id="create-post-form"
        >
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg">
                <form wire:submit.prevent="create">
                    <div class="mb-4">
                        <textarea 
                            wire:model="content"
                            id="content"
                            rows="4"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="What's on your mind?"></textarea>
                        @error('content') 
                            <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Specialty Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Add Specialty & Sub-Specialty *</label>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <input 
                                    type="text"
                                    wire:model="specialtyName"
                                    placeholder="Enter Specialty (e.g., Web Development)"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <input 
                                    type="text"
                                    wire:model="subSpecialtyName"
                                    placeholder="Enter Sub-Specialty (e.g., Frontend Developer)"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        <button 
                            type="button"
                            wire:click="addSpecialty"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors"
                            @if(!trim($specialtyName) || !trim($subSpecialtyName)) disabled @endif>
                            Add Specialty
                        </button>
                        @error('specialties') 
                            <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        
                        <!-- Selected Specialties -->
                        @if(count($specialties) > 0)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($specialties as $index => $spec)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600/20 border border-blue-600/50 rounded-lg text-blue-300 text-sm">
                                        <span>{{ $spec['specialty_name'] }} - {{ $spec['sub_specialty_name'] }}</span>
                                        <button 
                                            type="button"
                                            wire:click="removeSpecialty({{ $index }})"
                                            class="hover:text-red-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Tags Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Add Tags (Optional)</label>
                        <div class="flex gap-3 mb-3">
                            <input 
                                type="text"
                                wire:model="tagName"
                                wire:keydown.enter.prevent="addTag"
                                placeholder="Enter tag (e.g., #laravel, #php, #webdev)"
                                class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button 
                                type="button"
                                wire:click="addTag"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors"
                                @if(!trim($tagName)) disabled @endif>
                                Add Tag
                            </button>
                        </div>
                        @error('tags') 
                            <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        
                        <!-- Selected Tags -->
                        @if(count($tags) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $index => $tag)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600/20 border border-purple-600/50 rounded-lg text-purple-300 text-sm">
                                        <span>#{{ $tag['name'] }}</span>
                                        <button 
                                            type="button"
                                            wire:click="removeTag({{ $index }})"
                                            class="hover:text-red-400 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <label for="media" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg text-gray-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm">Upload Media</span>
                                <input 
                                    type="file"
                                    wire:model="media"
                                    id="media"
                                    accept="image/*,video/*"
                                    class="hidden">
                            </label>
                            @error('media') 
                                <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                            @if ($media)
                                <p class="mt-2 text-sm text-gray-400">Selected: {{ $media->getClientOriginalName() }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <button 
                                type="button"
                                wire:click="closeCreateForm"
                                class="px-4 py-2 text-gray-300 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-lg text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Posts List -->
        <div class="space-y-6">
            @forelse ($posts as $post)
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover:border-gray-700 transition-colors">
                    <!-- Post Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-300 font-semibold">
                                    {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                <p class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        @if ($post->user_id === auth()->id())
                            <div class="flex items-center gap-2">
                                <button 
                                    wire:click="openEditModal({{ $post->id }})"
                                    class="p-2 text-gray-400 hover:text-blue-400 hover:bg-gray-800 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button 
                                    wire:click="openDeleteModal({{ $post->id }})"
                                    class="p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Post Content -->
                    <div class="mb-4">
                        <p class="text-gray-200 leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
                    </div>

                    <!-- Post Media -->
                    @if ($post->media)
                        <div class="mb-4 rounded-lg overflow-hidden">
                            @php
                                $mediaUrl = $this->getMediaUrl($post);
                                $isImage = in_array(strtolower(pathinfo($post->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            
                            @if ($isImage)
                                <img src="{{ $mediaUrl }}" alt="Post media" class="w-full h-auto rounded-lg">
                            @else
                                <div class="bg-gray-800 p-4 rounded-lg">
                                    <a href="{{ $mediaUrl }}" target="_blank" class="flex items-center gap-2 text-blue-400 hover:text-blue-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>View Video</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Post Specialties -->
                    @if($post->specialties && $post->specialties->count() > 0)
                        <div class="mb-4 pt-4 border-t border-gray-800">
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->specialties as $specialty)
                                    @php
                                        $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null;
                                        $subSpecialty = $subSpecialtyId ? \App\Models\SubSpecialty::find($subSpecialtyId) : null;
                                    @endphp
                                    @if($subSpecialty)
                                        <span class="px-3 py-1 bg-blue-600/20 border border-blue-600/50 rounded-lg text-blue-300 text-xs">
                                            {{ $specialty->name }} - {{ $subSpecialty->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Post Tags -->
                    @if($post->tags && $post->tags->count() > 0)
                        <div class="mb-4 pt-4 border-t border-gray-800">
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <span class="px-3 py-1 bg-purple-600/20 border border-purple-600/50 rounded-lg text-purple-300 text-xs">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Post Stats -->
                    <div class="flex items-center gap-6 pt-4 border-t border-gray-800">
                        <button class="flex items-center gap-2 text-gray-400 hover:text-red-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>{{ $post->likes->count() }}</span>
                        </button>
                        <button class="flex items-center gap-2 text-gray-400 hover:text-blue-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>{{ $post->comments->count() }}</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-400">No posts yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new post!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>


    <!-- Edit Post Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" wire:click="closeEditModal"></div>

                <div class="inline-block align-bottom bg-gray-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800" wire:click.stop>
                    <form wire:submit.prevent="update">
                        <div class="bg-gray-900 px-6 py-4 border-b border-gray-800">
                            <h3 class="text-lg font-semibold text-white">Edit Post</h3>
                        </div>
                        
                        <div class="bg-gray-900 px-6 py-4">
                            <div class="mb-4">
                                <label for="editContent" class="block text-sm font-medium text-gray-300 mb-2">Content</label>
                                <textarea 
                                    wire:model="editContent"
                                    id="editContent"
                                    rows="6"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="What's on your mind?"></textarea>
                                @error('editContent') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editMedia" class="block text-sm font-medium text-gray-300 mb-2">Media (Optional)</label>
                                <input 
                                    type="file"
                                    wire:model="editMedia"
                                    id="editMedia"
                                    accept="image/*,video/*"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                @error('editMedia') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                @if ($editMedia)
                                    <p class="mt-2 text-sm text-gray-400">New file: {{ $editMedia->getClientOriginalName() }}</p>
                                @else
                                    <p class="mt-2 text-sm text-gray-400">Leave empty to keep current media</p>
                                @endif
                            </div>

                            <!-- Edit Specialty Selection -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Add Specialty & Sub-Specialty *</label>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <input 
                                            type="text"
                                            wire:model="editSpecialtyName"
                                            placeholder="Enter Specialty"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <input 
                                            type="text"
                                            wire:model="editSubSpecialtyName"
                                            placeholder="Enter Sub-Specialty"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    wire:click="addEditSpecialty"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors"
                                    @if(!trim($editSpecialtyName) || !trim($editSubSpecialtyName)) disabled @endif>
                                    Add Specialty
                                </button>
                                @error('editSpecialties') 
                                    <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                                
                                <!-- Selected Edit Specialties -->
                                @if(count($editSpecialties) > 0)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($editSpecialties as $index => $spec)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600/20 border border-blue-600/50 rounded-lg text-blue-300 text-sm">
                                                <span>{{ $spec['specialty_name'] }} - {{ $spec['sub_specialty_name'] }}</span>
                                                <button 
                                                    type="button"
                                                    wire:click="removeEditSpecialty({{ $index }})"
                                                    class="hover:text-red-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Edit Tags Selection -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Add Tags (Optional)</label>
                                <div class="flex gap-3 mb-3">
                                    <input 
                                        type="text"
                                        wire:model="editTagName"
                                        wire:keydown.enter.prevent="addEditTag"
                                        placeholder="Enter tag"
                                        class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button 
                                        type="button"
                                        wire:click="addEditTag"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors"
                                        @if(!trim($editTagName)) disabled @endif>
                                        Add Tag
                                    </button>
                                </div>
                                @error('editTags') 
                                    <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                                
                                <!-- Selected Edit Tags -->
                                @if(count($editTags) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($editTags as $index => $tag)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600/20 border border-purple-600/50 rounded-lg text-purple-300 text-sm">
                                                <span>#{{ $tag['name'] }}</span>
                                                <button 
                                                    type="button"
                                                    wire:click="removeEditTag({{ $index }})"
                                                    class="hover:text-red-400 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-900 px-6 py-4 border-t border-gray-800 flex justify-end gap-3">
                            <button 
                                type="button"
                                wire:click="closeEditModal"
                                class="px-4 py-2 text-gray-300 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" wire:click="closeDeleteModal"></div>

                <div class="inline-block align-bottom bg-gray-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800" wire:click.stop>
                    <div class="bg-gray-900 px-6 py-4 border-b border-gray-800">
                        <h3 class="text-lg font-semibold text-white">Delete Post</h3>
                    </div>
                    
                    <div class="bg-gray-900 px-6 py-4">
                        <p class="text-gray-300">Are you sure you want to delete this post? This action cannot be undone.</p>
                    </div>

                    <div class="bg-gray-900 px-6 py-4 border-t border-gray-800 flex justify-end gap-3">
                        <button 
                            type="button"
                            wire:click="closeDeleteModal"
                            class="px-4 py-2 text-gray-300 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button 
                            wire:click="delete"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bottom Navigation -->
    <div 
        x-data="{ 
            isVisible: true,
            lastScroll: 0,
            init() {
                this.lastScroll = window.pageYOffset || window.scrollY;
                window.addEventListener('scroll', () => {
                    const currentScroll = window.pageYOffset || window.scrollY;
                    // Hide when scrolling down, show when scrolling up or at bottom
                    if (currentScroll > this.lastScroll && currentScroll > 100) {
                        this.isVisible = false;
                    } else if (currentScroll < this.lastScroll || currentScroll <= 100) {
                        this.isVisible = true;
                    }
                    this.lastScroll = currentScroll;
                });
            }
        }"
        x-show="isVisible"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-full"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-full"
        class="fixed bottom-0 z-50 max-w-md w-full -translate-x-1/2 bg-gray-600/60 backdrop-blur-sm rounded-2xl left-1/2 shadow-lg mb-2 mx-auto px-4 py-2"
    >
        <div class="w-full">
            <div class="grid max-w-xs grid-cols-3 gap-1 p-1 mx-auto my-1 bg-gray-700/80 rounded-lg" role="group">
                <button type="button"
                    class="px-5 py-1.5 text-xs font-medium text-gray-200 hover:bg-gray-800 hover:text-white rounded">
                    New
                </button>
                <button type="button" class="px-5 py-1.5 text-xs font-medium text-white bg-gray-800 rounded">
                    Popular
                </button>
                <button type="button"
                    class="px-5 py-1.5 text-xs font-medium text-gray-200 hover:bg-gray-800 hover:text-white rounded">
                    Following
                </button>
            </div>
        </div>
        <div class="grid h-full max-w-md grid-cols-5 mx-auto">
            <button data-tooltip-target="tooltip-home" type="button"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                </svg>
                <span class="sr-only">Home</span>
            </button>
            <div id="tooltip-home" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Home
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-bookmark" type="button"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m17 21-5-4-5 4V3.889a.92.92 0 0 1 .244-.629.808.808 0 0 1 .59-.26h8.333a.81.81 0 0 1 .589.26.92.92 0 0 1 .244.63V21Z" />
                </svg>
                <span class="sr-only">Bookmark</span>
            </button>
            <div id="tooltip-bookmark" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Bookmark
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button 
                wire:click="toggleCreateForm"
                data-tooltip-target="tooltip-post" 
                type="button"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h14m-7 7V5" />
                </svg>
                <span class="sr-only">New post</span>
            </button>
            <div id="tooltip-post" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                New post
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button 
                wire:click="$dispatch('openSearch')"
                data-tooltip-target="tooltip-search" 
                type="button"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <span class="sr-only">Search</span>
            </button>
            <div id="tooltip-search" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Search
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-settings" type="button"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2" />
                </svg>
                <span class="sr-only">Settings</span>
            </button>
            <div id="tooltip-settings" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Settings
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>
</div>
