<div class="min-h-screen text-white pb-24">
    <div class="w-full px-0 sm:px-2 lg:px-0 py-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Posts</h1>
        </div>

    
        @if($showCreateForm)
        <div 
            class="mb-2 sticky top-4 z-40"
            id="create-post-form"
        >
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg">
                <form wire:submit.prevent="create" wire:key="create-post-form">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                        <input
                            type="text"
                            wire:model="title"
                            wire:key="title-input"
                            id="title"
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Give your post a clear title">
                        @error('title')
                            <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <textarea 
                            wire:model="content"
                            wire:key="content-input"
                            id="content"
                            rows="4"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="What's on your mind?"></textarea>
                        @error('content') 
                            <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Job Type Selection -->
                    <div class="mb-4">
                        <label for="jobType" class="block text-sm font-medium text-gray-300 mb-2">Job Type (Optional)</label>
                        <select
                            wire:model="jobType"
                            wire:key="job-type-input"
                            id="jobType"
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select job type...</option>
                            <option value="remote">Remote</option>
                            <option value="full-time">Full-time</option>
                            <option value="part-time">Part-time</option>
                        </select>
                        @error('job_type')
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
                                    wire:key="specialty-name-input"
                                    placeholder="Enter Specialty (e.g., Web Development)"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <input 
                                    type="text"
                                    wire:model="subSpecialtyName"
                                    wire:key="sub-specialty-name-input"
                                    placeholder="Enter Sub-Specialty (e.g., Frontend Developer)"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        <button 
                            type="button"
                            wire:click="addSpecialty"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
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
                                wire:key="tag-name-input"
                                wire:keydown.enter.prevent="addTag"
                                placeholder="Enter tag (e.g., #laravel, #php, #webdev)"
                                class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button 
                                type="button"
                                wire:click="addTag"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
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
                                    wire:key="media-input"
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
                            <!-- DEBUG: Test button to verify Livewire is working -->
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="create"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="create">Post</span>
                                <span wire:loading wire:target="create">Posting...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

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
        <div class="space-y-5">
            @forelse ($posts as $post)
                <!-- Post Card -->
                <article class="group rounded-2xl border border-gray-800 bg-gradient-to-br from-gray-900/95 via-gray-900 to-gray-900/80 p-5 sm:p-6 shadow-sm hover:shadow-xl hover:border-gray-600 transition-all duration-200">
                    <!-- Post Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3 flex-1">
                            <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-300 font-semibold">
                                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            
                            <!-- Follow Button (only for other users) -->
                            @if(auth()->check() && $post->user_id !== auth()->id())
                                @php
                                    $isFollowing = $this->isFollowing($post->user_id);
                                @endphp
                                <button 
                                    wire:click="toggleFollow({{ $post->user_id }})"
                                    class="ml-auto px-4 py-1.5 text-sm rounded-lg font-medium transition-colors {{ $isFollowing ? 'bg-gray-800 hover:bg-gray-700 text-white border border-gray-700' : 'bg-blue-600 hover:bg-blue-700 text-white' }}">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            @endif
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

                    <!-- Post Title & Content -->
                    <div class="mb-4">
                        @if(!empty($post->title))
                            <h2 class="text-lg font-semibold text-white mb-1 group-hover:text-blue-400 transition-colors">
                                {{ $post->title }}
                            </h2>
                        @endif
                        @if($post->job_type)
                            <div class="mb-2">
                                <span class="inline-flex items-center px-2 py-1 bg-blue-600/20 text-blue-300 text-xs font-medium rounded border border-blue-600/50">
                                    {{ ucfirst(str_replace('-', ' ', $post->job_type)) }}
                                </span>
                            </div>
                        @endif
                        <p class="text-gray-200 leading-relaxed whitespace-pre-wrap text-sm sm:text-base">
                            {{ $post->content }}
                        </p>
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
                    @php
                        $hasLikedPost = auth()->check() && $post->likes->contains('user_id', auth()->id());
                    @endphp
                    <div class="flex items-center gap-6 pt-4 border-t border-gray-800">
                        <button
                            type="button"
                            wire:click="togglePostLike({{ $post->id }})"
                            class="flex items-center gap-2 text-sm {{ $hasLikedPost ? 'text-red-400' : 'text-gray-400 hover:text-red-400' }} transition-colors">
                            <svg class="w-5 h-5" fill="{{ $hasLikedPost ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>{{ $post->likes->count() }}</span>
                        </button>
                        <a
                            href="{{ route('posts.show', $post->slug) }}"
                            class="flex items-center gap-2 text-gray-400 hover:text-blue-400 transition-colors text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>{{ $post->comments->count() }}</span>
                        </a>
                        <a
                            href="{{ route('posts.show', $post->slug) }}"
                            class="flex items-center gap-2 text-gray-400 hover:text-blue-400 transition-colors text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>View Post</span>
                        </a>
                    </div>
                </article>
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
                                <label for="editTitle" class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                                <input
                                    type="text"
                                    wire:model="editTitle"
                                    id="editTitle"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Update title">
                                @error('editTitle') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

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
                                <label for="editJobType" class="block text-sm font-medium text-gray-300 mb-2">Job Type (Optional)</label>
                                <select
                                    wire:model="editJobType"
                                    wire:key="edit-job-type-input"
                                    id="editJobType"
                                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select job type...</option>
                                    <option value="remote">Remote</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                </select>
                                @error('editJobType')
                                    <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
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
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
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
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
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
                <button
                    type="button"
                    wire:click="setFeedMode('new')"
                    class="px-5 py-1.5 text-xs font-medium rounded {{ $feedMode === 'new' ? 'text-white bg-gray-800' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                    New
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('popular')"
                    class="px-5 py-1.5 text-xs font-medium rounded {{ $feedMode === 'popular' ? 'text-white bg-gray-800' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                    Popular
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('following')"
                    class="px-5 py-1.5 text-xs font-medium rounded {{ $feedMode === 'following' ? 'text-white bg-gray-800' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                    Following
                </button>
            </div>
        </div>
        <div class="grid h-full max-w-md grid-cols-7 mx-auto">
            <a href="{{ route('dashboard') }}" data-tooltip-target="tooltip-home"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                </svg>
                <span class="sr-only">Home</span>
            </a>
            <div id="tooltip-home" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Home
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            @php
                $unreadNotifications = auth()->check()
                    ? auth()->user()->notificationsCustom()->where('is_read', false)->count()
                    : 0;
            @endphp
            <button 
                wire:click="$dispatch('openNotifications')"
                data-tooltip-target="tooltip-notifications" 
                type="button"
                class="relative inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m2 0v1a3 3 0 11-6 0v-1h6z" />
                </svg>
                @if($unreadNotifications > 0)
                    <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-500 text-white border border-gray-900">
                        {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                    </span>
                @endif
                <span class="sr-only">Notifications</span>
            </button>
            <div id="tooltip-notifications" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Notifications
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button 
                wire:click="$dispatch('openCreatePost')"
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
            @php
                $chatService = app(\App\Services\ChatService::class);
                $totalUnreadMessages = auth()->check() ? $chatService->getTotalUnreadCount(auth()->id()) : 0;
            @endphp
            <button 
                onclick="window.dispatchEvent(new CustomEvent('openChatList'))"
                data-tooltip-target="tooltip-chat" 
                type="button"
                class="relative inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                @if($totalUnreadMessages > 0)
                    <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-500 text-white border border-gray-900">
                        {{ $totalUnreadMessages > 99 ? '99+' : $totalUnreadMessages }}
                    </span>
                @endif
                <span class="sr-only">Chat</span>
            </button>
            <div id="tooltip-chat" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Chat
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a 
                href="{{ route('cvs') }}"
                data-tooltip-target="tooltip-cvs"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors"
            >
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="sr-only">CVs</span>
            </a>
            <div id="tooltip-cvs" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                CVs
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a 
                href="{{ auth()->check() ? route('user.profile', auth()->user()->username ?? 'unknown') : route('profile.show') }}"
                data-tooltip-target="tooltip-profile"
                class="inline-flex flex-col items-center justify-center p-2 hover:bg-gray-700/80 group rounded-lg transition-colors"
            >
                <svg class="w-6 h-6 mb-1 text-gray-200 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                        d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 19.5a7.5 7.5 0 0 1 15 0v.75H4.5v-.75Z" />
                </svg>
                <span class="sr-only">Profile</span>
            </a>
            <div id="tooltip-profile" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Profile
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>
</div>
