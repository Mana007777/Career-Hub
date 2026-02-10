<div
    class="min-h-screen dark:text-white text-gray-900 pb-24"
    x-data="{ loaded: false }"
    x-init="
        // Start in loading state
        loaded = false;

        const setLoaded = () => { loaded = true };
        const setLoading = () => { loaded = false };

        // When Livewire finishes initial load / navigation, show real content
        document.addEventListener('livewire:load', setLoaded);
        document.addEventListener('livewire:navigated', setLoaded);

        // When Livewire starts navigating, show skeletons
        document.addEventListener('livewire:navigating', setLoading);
    "
>
    <!-- Skeleton while page / data is loading -->
    <div x-show="!loaded">
        <x-skeleton.post-list />
    </div>

    <!-- Actual content -->
    <div x-show="loaded" x-cloak>
        <div class="w-full px-0 sm:px-2 lg:px-0 py-4">
        <!-- Header -->
        <div 
            class="mb-8"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold dark:text-white text-gray-900 bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Posts</h1>
                <button 
                    wire:click="toggleFilters"
                    class="px-4 py-2 dark:bg-gray-800 bg-gray-800 dark:hover:bg-gray-700 hover:bg-gray-900 text-white rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Filters</span>
                </button>
            </div>
        </div>
        
        <!-- Filter Section -->
        @if($showFilters)
        <div 
            class="mb-6 dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl p-6 shadow-lg"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Sort Order -->
                <div>
                    <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Sort Order</label>
                    <select 
                        wire:model.live="sortOrder"
                        class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="desc">Newest First (DESC)</option>
                        <option value="asc">Oldest First (ASC)</option>
                    </select>
                </div>
                
                <!-- Job Type -->
                <div>
                    <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Job Type</label>
                    <select 
                        wire:model.live="selectedJobType"
                        class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Job Types</option>
                        @foreach($jobTypes as $jobType)
                            <option value="{{ $jobType }}">{{ ucfirst($jobType) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Tags -->
                <div>
                    <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Tags</label>
                    <select 
                        wire:model.live="selectedTags"
                        class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Tags</option>
                        @foreach($allTags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Specialties -->
                <div>
                    <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Specialties</label>
                    <select 
                        wire:model.live="selectedSpecialties"
                        class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Specialties</option>
                        @foreach($allSpecialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Clear Filters Button -->
            <div class="mt-4 flex justify-end">
                <button 
                    wire:click="clearFilters"
                    class="px-4 py-2 dark:bg-gray-700 bg-gray-200 dark:hover:bg-gray-600 hover:bg-gray-300 dark:text-white text-gray-900 rounded-lg transition-colors">
                    Clear All Filters
                </button>
            </div>
        </div>
        @endif

    
        @if($showCreateForm)
        <div 
            class="mb-2 top-4 z-40"
            id="create-post-form"
        >
            <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl p-6 shadow-lg">
                <form wire:submit.prevent="create" wire:key="create-post-form">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Title</label>
                        <input
                            type="text"
                            wire:model="title"
                            wire:key="title-input"
                            id="title"
                            class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Give your post a clear title">
                        @error('title')
                            <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <textarea 
                            wire:model="content"
                            wire:key="content-input"
                            id="content"
                            rows="4"
                            class="w-full px-4 py-3 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="What's on your mind?"></textarea>
                        @error('content') 
                            <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Job Type Selection -->
                    <div class="mb-4">
                        <label for="jobType" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Job Type (Optional)</label>
                        <select
                            wire:model="jobType"
                            wire:key="job-type-input"
                            id="jobType"
                            class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select job type...</option>
                            <option value="full-time">Full-time</option>
                            <option value="part-time">Part-time</option>
                            <option value="contract">Contract</option>
                            <option value="freelance">Freelance</option>
                            <option value="internship">Internship</option>
                            <option value="remote">Remote</option>
                        </select>
                        @error('job_type')
                            <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Specialty Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Add Specialty & Sub-Specialty *</label>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <input 
                                    type="text"
                                    wire:model="specialtyName"
                                    wire:key="specialty-name-input"
                                    placeholder="Enter Specialty (e.g., Web Development)"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <input 
                                    type="text"
                                    wire:model="subSpecialtyName"
                                    wire:key="sub-specialty-name-input"
                                    placeholder="Enter Sub-Specialty (e.g., Frontend Developer)"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        <button 
                            type="button"
                            wire:click="addSpecialty"
                            class="px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white text-sm rounded-lg transition-colors">
                            Add Specialty
                        </button>
                        @error('specialties') 
                            <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        
                        <!-- Selected Specialties -->
                        @if(count($specialties) > 0)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($specialties as $index => $spec)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 dark:bg-blue-600/20 bg-blue-100 dark:border-blue-600/50 border-blue-300 rounded-lg dark:text-blue-300 text-blue-700 text-sm font-medium">
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
                        <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Add Tags (Optional)</label>
                        <div class="flex gap-3 mb-3">
                            <input 
                                type="text"
                                wire:model="tagName"
                                wire:key="tag-name-input"
                                wire:keydown.enter.prevent="addTag"
                                placeholder="Enter tag (e.g., #laravel, #php, #webdev)"
                                class="flex-1 px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button 
                                type="button"
                                wire:click="addTag"
                                class="px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white text-sm rounded-lg transition-colors">
                                Add Tag
                            </button>
                        </div>
                        @error('tags') 
                            <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                        
                        <!-- Selected Tags -->
                        @if(count($tags) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $index => $tag)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 dark:bg-purple-600/20 bg-purple-100 dark:border-purple-600/50 border-purple-300 rounded-lg dark:text-purple-300 text-purple-700 text-sm font-medium">
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
                            <label for="media" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 dark:bg-gray-800 bg-gray-100 dark:hover:bg-gray-700 hover:bg-gray-200 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-gray-300 text-gray-700 transition-colors">
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
                                <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                            @if ($media)
                                <p class="mt-2 text-sm dark:text-gray-400 text-gray-600">Selected: {{ $media->getClientOriginalName() }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <button 
                                type="button"
                                wire:click="closeCreateForm"
                                class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <!-- DEBUG: Test button to verify Livewire is working -->
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="create"
                                class="px-6 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors disabled:opacity-50">
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
            <div class="mb-6 p-4 dark:bg-green-900/50 bg-green-50 border dark:border-green-700 border-green-200 rounded-lg dark:text-green-200 text-green-800 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Posts List -->
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($posts as $index => $post)
                <!-- Post Card -->
                <article 
                    onclick="window.location.href='{{ route('posts.show', $post->slug) }}'"
                    class="group h-full flex flex-col rounded-2xl border dark:border-gray-800 border-gray-200 dark:bg-gradient-to-br dark:from-gray-900/95 dark:via-gray-900 dark:to-gray-900/80 bg-gradient-to-br from-white via-gray-50 to-white p-5 sm:p-6 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 dark:hover:border-gray-600 hover:border-gray-300 transition-all duration-300 cursor-pointer transform hover:scale-[1.02] hover:-translate-y-1"
                    x-data="{ show: false }"
                    x-init="
                        setTimeout(() => {
                            show = true;
                        }, {{ $index * 100 }});
                    "
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    style="position: relative;"
                >
                    <!-- Post Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3 flex-1">
                            <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" onclick="event.stopPropagation()" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center">
                                    @if($post->user && $post->user->profile_photo_path)
                                        <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="dark:text-gray-300 text-gray-700 font-semibold">
                                            {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold dark:text-white text-gray-900">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                        @if($post->suspension)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded dark:bg-red-600/20 bg-red-100 dark:text-red-400 text-red-700 dark:border-red-600/50 border-red-300 border" title="Suspended">
                                                Suspended
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm dark:text-gray-400 text-gray-600">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            
                            <!-- Follow Button (only for other users) -->
                            @if(auth()->check() && $post->user_id !== auth()->id())
                                @php
                                    $isFollowing = $this->isFollowing($post->user_id);
                                @endphp
                                <button 
                                    wire:click.stop="toggleFollow({{ $post->user_id }})"
                                    type="button"
                                    class="ml-auto px-4 py-1.5 text-sm rounded-lg font-medium transition-colors relative z-10 {{ $isFollowing ? 'dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white border dark:border-gray-700 border-gray-700' : 'dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white' }}">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2 relative z-10">
                            @if ($post->user_id === auth()->id())
                                <button 
                                    wire:click.stop="openEditModal({{ $post->id }})"
                                    type="button"
                                    class="p-2 dark:text-gray-400 text-gray-600 hover:text-blue-400 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button 
                                    wire:click.stop="openDeleteModal({{ $post->id }})"
                                    type="button"
                                    class="p-2 dark:text-gray-400 text-gray-600 hover:text-red-400 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                            
                            {{-- Admin Actions (Only visible to admins, not post owners) --}}
                            @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $post->user_id)
                                <div class="relative" x-data="{ open: false }">
                                    <button 
                                        type="button"
                                        @click.stop="open = !open"
                                        class="p-2 dark:text-gray-400 text-gray-600 hover:text-gray-900 dark:hover:text-white dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                        title="Admin Actions">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="open"
                                        @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-48 dark:bg-gray-800 bg-white border dark:border-gray-700 border-gray-200 rounded-lg shadow-lg z-50"
                                        style="display: none;">
                                        @if($post->suspension)
                                            <button 
                                                wire:click.stop="openAdminActionsModal('unsuspend', {{ $post->id }})"
                                                class="w-full text-left px-4 py-2 text-sm dark:text-green-400 text-green-600 hover:dark:bg-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                                @click="open = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                                </svg>
                                                Unsuspend Post
                                            </button>
                                        @else
                                            <button 
                                                wire:click.stop="openAdminActionsModal('suspend', {{ $post->id }})"
                                                class="w-full text-left px-4 py-2 text-sm dark:text-yellow-400 text-yellow-600 hover:dark:bg-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                                @click="open = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Suspend Post
                                            </button>
                                        @endif
                                        <button 
                                            wire:click.stop="openAdminActionsModal('delete', {{ $post->id }})"
                                            class="w-full text-left px-4 py-2 text-sm dark:text-red-400 text-red-600 hover:dark:bg-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                            @click="open = false">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove Post
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Report Button (Visible to all users except post owner and admins) --}}
                            @if(auth()->check() && auth()->id() !== $post->user_id && !auth()->user()->isAdmin())
                                <button 
                                    onclick="event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'post', targetId: {{ $post->id }} } }));"
                                    type="button"
                                    class="p-2 dark:text-gray-400 text-gray-600 hover:text-orange-400 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors relative z-10"
                                    title="Report Post">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Post Title & Content -->
                    <div class="mb-4">
                        @if(!empty($post->title))
                            <h2 class="text-lg font-semibold dark:text-white text-gray-900 mb-1 group-hover:text-blue-400 transition-colors">
                                {{ $post->title }}
                            </h2>
                        @endif
                        @if($post->job_type)
                            <div class="mb-2">
                                <span class="inline-flex items-center px-2 py-1 dark:bg-blue-600/20 bg-blue-100 dark:text-blue-300 text-blue-700 text-xs font-medium rounded dark:border-blue-600/50 border-blue-300">
                                    {{ ucfirst(str_replace('-', ' ', $post->job_type)) }}
                                </span>
                            </div>
                        @endif
                        <p class="dark:text-gray-200 text-gray-700 leading-relaxed whitespace-pre-wrap text-sm sm:text-base line-clamp-4">
                            {{ $post->content }}
                        </p>
                        @if(\Illuminate\Support\Str::length($post->content) > 280)
                            <button
                                type="button"
                                wire:click.stop="openInlinePostModal({{ $post->id }})"
                                class="mt-2 text-sm font-medium dark:text-blue-400 text-blue-600 hover:underline"
                            >
                                See more
                            </button>
                        @endif
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
                                <div class="dark:bg-gray-800 bg-gray-100 p-4 rounded-lg">
                                    <a href="{{ $mediaUrl }}" target="_blank" class="flex items-center gap-2 dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700">
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
                        <div class="mb-4 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->specialties as $specialty)
                                    @php
                                        $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null;
                                        // Use already-loaded subSpecialties collection instead of DB query
                                        $subSpecialty = $subSpecialtyId && $specialty->subSpecialties 
                                            ? $specialty->subSpecialties->firstWhere('id', $subSpecialtyId) 
                                            : null;
                                    @endphp
                                    @if($subSpecialty)
                                        <span class="px-3 py-1 dark:bg-blue-600/20 bg-blue-100 dark:border-blue-600/50 border-blue-300 rounded-lg dark:text-blue-300 text-blue-700 text-xs font-medium">
                                            {{ $specialty->name }} - {{ $subSpecialty->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Post Tags -->
                    @if($post->tags && $post->tags->count() > 0)
                        <div class="mb-4 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <span class="px-3 py-1 dark:bg-purple-600/20 bg-purple-100 dark:border-purple-600/50 border-purple-300 rounded-lg dark:text-purple-300 text-purple-700 text-xs font-medium">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Post Stats -->
                    @php
                        $hasStarredPost = auth()->check() && $post->stars->contains('user_id', auth()->id());
                        $hasSavedPost = auth()->check() && in_array($post->id, $savedPostIds ?? []);
                    @endphp
                    <div class="flex items-center gap-6 pt-4 border-t dark:border-gray-800 border-gray-200 relative z-10">
                        <button
                            type="button"
                            wire:click.stop="togglePostStar({{ $post->id }})"
                            class="flex items-center gap-2 text-sm {{ $hasStarredPost ? 'text-yellow-400' : 'dark:text-gray-400 text-gray-600 hover:text-yellow-400' }} transition-colors">
                            <svg class="w-5 h-5" fill="{{ $hasStarredPost ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            <span>{{ $post->stars->count() }}</span>
                        </button>
                        <button
                            type="button"
                            wire:click.stop="togglePostSave({{ $post->id }})"
                            class="flex items-center gap-2 text-sm {{ $hasSavedPost ? 'text-blue-400' : 'dark:text-gray-400 text-gray-600 hover:text-blue-400' }} transition-colors">
                            <svg class="w-5 h-5" fill="{{ $hasSavedPost ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z"></path>
                            </svg>
                            <span>{{ $hasSavedPost ? 'Saved' : 'Save' }}</span>
                        </button>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>{{ $post->comments->count() }}</span>
                        </div>
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
        @if($posts->hasPages())
            <div class="mt-12">
                <x-pagination :paginator="$posts" />
            </div>
        @endif
    </div>


    <!-- Edit Post Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="closeEditModal"></div>

                <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                    <form wire:submit.prevent="update">
                        <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200">
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">Edit Post</h3>
                        </div>
                        
                        <div class="dark:bg-gray-900 bg-white px-6 py-4">
                            <div class="mb-4">
                                <label for="editTitle" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Title</label>
                                <input
                                    type="text"
                                    wire:model="editTitle"
                                    id="editTitle"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Update title">
                                @error('editTitle') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editContent" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Content</label>
                                <textarea 
                                    wire:model="editContent"
                                    id="editContent"
                                    rows="6"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="What's on your mind?"></textarea>
                                @error('editContent') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editJobType" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Job Type (Optional)</label>
                                <select
                                    wire:model="editJobType"
                                    wire:key="edit-job-type-input"
                                    id="editJobType"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select job type...</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                    <option value="remote">Remote</option>
                                </select>
                                @error('editJobType')
                                    <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editMedia" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Media (Optional)</label>
                                <input 
                                    type="file"
                                    wire:model="editMedia"
                                    id="editMedia"
                                    accept="image/*,video/*"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold dark:file:bg-blue-600 dark:file:hover:bg-blue-700 dark:file:text-white file:bg-gray-800 file:hover:bg-gray-900 file:text-white">
                                @error('editMedia') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                @if ($editMedia)
                                    <p class="mt-2 text-sm dark:text-gray-400 text-gray-600">New file: {{ $editMedia->getClientOriginalName() }}</p>
                                @else
                                    <p class="mt-2 text-sm dark:text-gray-400 text-gray-600">Leave empty to keep current media</p>
                                @endif
                            </div>

                            <!-- Edit Specialty Selection -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Add Specialty & Sub-Specialty *</label>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <input 
                                            type="text"
                                            wire:model="editSpecialtyName"
                                            placeholder="Enter Specialty"
                                            class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <input 
                                            type="text"
                                            wire:model="editSubSpecialtyName"
                                            placeholder="Enter Sub-Specialty"
                                            class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    wire:click="addEditSpecialty"
                                    class="px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white text-sm rounded-lg transition-colors">
                                    Add Specialty
                                </button>
                                @error('editSpecialties') 
                                    <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                                
                                <!-- Selected Edit Specialties -->
                                @if(count($editSpecialties) > 0)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($editSpecialties as $index => $spec)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 dark:bg-blue-600/20 bg-blue-100 dark:border-blue-600/50 border-blue-300 rounded-lg dark:text-blue-300 text-blue-700 text-sm font-medium">
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
                                <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Add Tags (Optional)</label>
                                <div class="flex gap-3 mb-3">
                                    <input 
                                        type="text"
                                        wire:model="editTagName"
                                        wire:keydown.enter.prevent="addEditTag"
                                        placeholder="Enter tag"
                                        class="flex-1 px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button 
                                        type="button"
                                        wire:click="addEditTag"
                                        class="px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white text-sm rounded-lg transition-colors">
                                        Add Tag
                                    </button>
                                </div>
                                @error('editTags') 
                                    <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                                
                                <!-- Selected Edit Tags -->
                                @if(count($editTags) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($editTags as $index => $tag)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 dark:bg-purple-600/20 bg-purple-100 dark:border-purple-600/50 border-purple-300 rounded-lg dark:text-purple-300 text-purple-700 text-sm font-medium">
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

                        <div class="dark:bg-gray-900 bg-white px-6 py-4 border-t dark:border-gray-800 border-gray-200 flex justify-end gap-3">
                            <button 
                                type="button"
                                wire:click="closeEditModal"
                                class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white rounded-lg transition-colors">
                                Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Report Modal -->
    @livewire('report-modal')

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="closeDeleteModal"></div>

                <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200">
                        <h3 class="text-lg font-semibold dark:text-white text-gray-900">Delete Post</h3>
                    </div>
                    
                    <div class="dark:bg-gray-900 bg-white px-6 py-4">
                        <p class="dark:text-gray-300 text-gray-700">Are you sure you want to delete this post? This action cannot be undone.</p>
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

    <!-- Admin Actions Modal -->
    @if ($showAdminActionsModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="closeAdminActionsModal"></div>

                <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200">
                        <h3 class="text-lg font-semibold dark:text-white text-gray-900">
                            @if($adminActionType === 'suspend')
                                Suspend Post
                            @elseif($adminActionType === 'unsuspend')
                                Unsuspend Post
                            @elseif($adminActionType === 'delete')
                                Remove Post
                            @endif
                        </h3>
                    </div>
                    
                    @if($adminActionType === 'suspend')
                        <form wire:submit.prevent="suspendPost" class="dark:bg-gray-900 bg-white px-6 py-4">
                            <div class="mb-4">
                                <label for="suspendReason" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Suspension Reason *</label>
                                <textarea
                                    wire:model="suspendReason"
                                    id="suspendReason"
                                    rows="3"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent resize-none"
                                    placeholder="Enter the reason for suspending this post..."></textarea>
                                @error('suspendReason')
                                    <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="suspendExpiresAt" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Expires At (Optional)</label>
                                <input
                                    type="datetime-local"
                                    wire:model="suspendExpiresAt"
                                    id="suspendExpiresAt"
                                    min="{{ now()->format('Y-m-d\TH:i') }}"
                                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Leave empty for permanent suspension</p>
                                @error('suspendExpiresAt')
                                    <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="suspendPost"
                                    class="px-4 py-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:text-white bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="suspendPost">Suspend Post</span>
                                    <span wire:loading wire:target="suspendPost">Suspending...</span>
                                </button>
                            </div>
                        </form>
                    @elseif($adminActionType === 'unsuspend')
                        <div class="dark:bg-gray-900 bg-white px-6 py-4">
                            <p class="mb-4 dark:text-gray-300 text-gray-700">Are you sure you want to unsuspend this post? It will become publicly visible again.</p>
                            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button 
                                    type="button"
                                    wire:click="unsuspendPost({{ $adminActionPostId }})"
                                    wire:loading.attr="disabled"
                                    wire:target="unsuspendPost"
                                    class="px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:text-white bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="unsuspendPost">Unsuspend Post</span>
                                    <span wire:loading wire:target="unsuspendPost">Unsuspending...</span>
                                </button>
                            </div>
                        </div>
                    @elseif($adminActionType === 'delete')
                        <div class="dark:bg-gray-900 bg-white px-6 py-4">
                            <p class="mb-4 dark:text-red-400 text-red-600 font-semibold">Warning: This action cannot be undone!</p>
                            <p class="mb-4 dark:text-gray-300 text-gray-700">Are you sure you want to permanently remove this post?</p>
                            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button 
                                    type="button"
                                    wire:click="deletePostAsAdmin({{ $adminActionPostId }})"
                                    wire:loading.attr="disabled"
                                    wire:target="deletePostAsAdmin"
                                    class="px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:text-white bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="deletePostAsAdmin">Remove Post</span>
                                    <span wire:loading wire:target="deletePostAsAdmin">Removing...</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Suspend Post Modal (Legacy - keeping for backward compatibility) -->
    @if ($showSuspendModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="closeSuspendModal"></div>

                <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200">
                        <h3 class="text-lg font-semibold dark:text-white text-gray-900">Suspend Post</h3>
                    </div>
                    
                    <form wire:submit.prevent="suspendPost" class="dark:bg-gray-900 bg-white px-6 py-4">
                        <div class="mb-4">
                            <label for="suspendReason" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Suspension Reason *</label>
                            <textarea
                                wire:model="suspendReason"
                                id="suspendReason"
                                rows="3"
                                class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent resize-none"
                                placeholder="Enter the reason for suspending this post..."></textarea>
                            @error('suspendReason')
                                <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="suspendExpiresAt" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Expires At (Optional)</label>
                            <input
                                type="datetime-local"
                                wire:model="suspendExpiresAt"
                                id="suspendExpiresAt"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Leave empty for permanent suspension</p>
                            @error('suspendExpiresAt')
                                <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeSuspendModal"
                                class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="suspendPost"
                                class="px-4 py-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:text-white bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="suspendPost">Suspend Post</span>
                                <span wire:loading wire:target="suspendPost">Suspending...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Inline Post Detail Modal (for "See more") -->
    @if ($showInlinePostModal && $inlinePost)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="closeInlinePostModal"></div>

                <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden dark:bg-gray-700 bg-gray-200 flex items-center justify-center">
                                @if($inlinePost->user && $inlinePost->user->profile_photo_path)
                                    <img src="{{ $inlinePost->user->profile_photo_url }}" alt="{{ $inlinePost->user->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="dark:text-gray-300 text-gray-700 font-semibold">
                                        {{ strtoupper(substr($inlinePost->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-base font-semibold dark:text-white text-gray-900">
                                    {{ $inlinePost->title ?: 'Post by ' . ($inlinePost->user->name ?? 'Unknown User') }}
                                </h3>
                                <p class="text-xs dark:text-gray-400 text-gray-600">
                                    {{ $inlinePost->created_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="closeInlinePostModal"
                            class="p-2 rounded-lg dark:text-gray-400 text-gray-600 hover:dark:bg-gray-800 hover:bg-gray-100 hover:text-gray-900 dark:hover:text-white transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="dark:bg-gray-900 bg-white px-6 py-4 space-y-4">
                        @if(!empty($inlinePost->title))
                            <h2 class="text-xl font-bold dark:text-white text-gray-900">
                                {{ $inlinePost->title }}
                            </h2>
                        @endif

                        <p class="dark:text-gray-200 text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $inlinePost->content }}
                        </p>

                        @if ($inlinePost->media)
                            <div class="mt-4 rounded-lg overflow-hidden">
                                @php
                                    $mediaUrl = $this->getMediaUrl($inlinePost);
                                    $isImage = in_array(strtolower(pathinfo($inlinePost->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                @if ($isImage)
                                    <img src="{{ $mediaUrl }}" alt="Post media" class="w-full h-auto rounded-lg">
                                @else
                                    <div class="dark:bg-gray-800 bg-gray-100 p-4 rounded-lg">
                                        <a href="{{ $mediaUrl }}" target="_blank" class="flex items-center gap-2 dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>View attachment</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-t dark:border-gray-800 border-gray-200 flex justify-end">
                        <button
                            type="button"
                            wire:click="closeInlinePostModal"
                            class="px-4 py-2 rounded-lg dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white transition-colors"
                        >
                            Close
                        </button>
                        <a
                            href="{{ route('posts.show', $inlinePost->slug) }}"
                            class="ml-3 px-4 py-2 rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white transition-colors"
                            wire:click="closeInlinePostModal"
                        >
                            Open full page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bottom Navigation (Home / Posts page only) -->
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
        class="fixed bottom-0 z-50 max-w-xl w-full -translate-x-1/2 dark:bg-gray-600/60 bg-white backdrop-blur-sm rounded-2xl left-1/2 shadow-lg mb-2 mx-auto px-4 py-2 border dark:border-gray-700 border-gray-200"
    >
        <div class="w-full">
            <div class="grid max-w-xs grid-cols-3 gap-1 p-1 mx-auto my-1 dark:bg-gray-700/80 bg-gray-200 rounded-lg" role="group">
                <button
                    type="button"
                    wire:click="setFeedMode('new')"
                    class="px-5 py-1.5 text-xs font-medium rounded {{ $feedMode === 'new' ? 'dark:text-white text-gray-900 dark:bg-gray-800 bg-gray-300' : 'dark:text-gray-200 text-gray-700 dark:hover:bg-gray-800 hover:bg-gray-300 dark:hover:text-white hover:text-gray-900' }}">
                    New
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('popular')"
                    class="px-5 py-1.5 text-xs font-medium rounded {{ $feedMode === 'popular' ? 'dark:text-white text-gray-900 dark:bg-gray-800 bg-gray-300' : 'dark:text-gray-200 text-gray-700 dark:hover:bg-gray-800 hover:bg-gray-300 dark:hover:text-white hover:text-gray-900' }}">
                    Popular
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('following')"
                    class="px-5 py-1.5 text-xs font-medium rounded {{ $feedMode === 'following' ? 'dark:text-white text-gray-900 dark:bg-gray-800 bg-gray-300' : 'dark:text-gray-200 text-gray-700 dark:hover:bg-gray-800 hover:bg-gray-300 dark:hover:text-white hover:text-gray-900' }}">
                    Following
                </button>
            </div>
        </div>
        {{-- Bottom Navigation Component --}}
        <livewire:bottom-navigation />

        {{-- User notifications are mounted globally in the dashboard layout --}}
    </div>
</div>
