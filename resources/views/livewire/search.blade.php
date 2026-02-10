<div>
    @if($showSearch)
    <!-- Search Overlay -->
    <div 
        x-data="{ 
            init() {
                // Lock body scroll when modal opens
                document.body.style.overflow = 'hidden';
                
                // Cleanup on component destroy
                this.$el.addEventListener('livewire:destroy', () => {
                    document.body.style.overflow = '';
                });
            }
        }"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @transition:leave-end="document.body.style.overflow = ''"
        class="fixed inset-0 z-50 dark:bg-gray-900/90 bg-gray-900/90 backdrop-blur-sm"
        @click.self="$wire.closeSearch()"
        @keydown.escape.window="$wire.closeSearch()"
        wire:key="search-modal-{{ $showSearch }}"
    >
        <!-- Search Container -->
        <div 
            class="fixed inset-0 flex items-start justify-center pt-20 px-4"
            wire:click.stop
        >
            <div class="w-full max-w-2xl">
                <!-- Search Header -->
                <div class="dark:bg-gray-800 bg-white rounded-t-xl p-4 border-b dark:border-gray-700 border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold dark:text-white text-gray-900">Search</h2>
                        <button 
                            wire:click="closeSearch"
                            class="p-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 dark:text-gray-400 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text"
                            wire:model.live.debounce.300ms="query"
                            placeholder="Search for posts or users..."
                            class="w-full pl-12 pr-4 py-3 dark:bg-gray-700 bg-gray-100 border dark:border-gray-600 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-400 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            autofocus>
                    </div>
                </div>

                <!-- Search Results -->
                <div 
                    x-data="{ loaded: false }"
                    x-init="setTimeout(() => loaded = true, 200)"
                >
                <div class="dark:bg-gray-800 bg-white rounded-b-xl max-h-[60vh] overflow-y-auto">
                    @if($query && strlen(trim($query)) > 0)
                        <!-- Users Results -->
                        @if($users->count() > 0)
                            <div class="p-4 border-b dark:border-gray-700 border-gray-200">
                                <h3 class="text-lg font-semibold dark:text-white text-gray-900 mb-3">Users</h3>
                                <div class="space-y-3">
                                    @foreach($users as $index => $user)
                                        <a 
                                            href="{{ route('user.profile', $user->username ?? 'unknown') }}"
                                            wire:click="closeSearch"
                                            class="block dark:bg-gray-900 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg p-4 dark:hover:border-gray-600 hover:border-gray-300 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 cursor-pointer"
                                            x-data="{ show: false }"
                                            x-init="
                                                setTimeout(() => {
                                                    show = true;
                                                }, {{ $index * 50 }});
                                            "
                                            x-show="show"
                                            x-transition:enter="transition ease-out duration-400"
                                            x-transition:enter-start="opacity-0 translate-y-4"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                        >
                                            <div class="flex items-center gap-4">
                                                <!-- User Avatar -->
                                                <div class="w-12 h-12 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center text-lg font-semibold dark:text-gray-300 text-gray-700">
                                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                
                                                <!-- User Info -->
                                                <div class="flex-1">
                                                    <h4 class="text-base font-semibold dark:text-white text-gray-900">
                                                        {!! str_ireplace(e($query), '<mark class="bg-yellow-500/30 text-yellow-200">' . e($query) . '</mark>', e($user->name ?? 'Unknown User')) !!}
                                                    </h4>
                                                    @if($user->username)
                                                        <p class="text-sm dark:text-gray-400 text-gray-600">
                                                            {!! '@' . str_ireplace(e($query), '<mark class="bg-yellow-500/30 text-yellow-200">' . e($query) . '</mark>', e($user->username)) !!}
                                                        </p>
                                                    @endif
                                                </div>
                                                
                                                <!-- View Profile -->
                                                <svg class="w-5 h-5 dark:text-gray-400 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                
                                <!-- Users Pagination -->
                                @if($users->hasPages())
                                    <div class="mt-4 pt-4 border-t dark:border-gray-700 border-gray-200">
                                        {{ $users->links() }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Posts Results -->
                        @if($posts->count() > 0)
                            <div class="p-4 {{ $users->count() > 0 ? 'border-t dark:border-gray-700 border-gray-200' : '' }}">
                                <h3 class="text-lg font-semibold dark:text-white text-gray-900 mb-3">Posts</h3>
                                <div class="space-y-4">
                                    @foreach($posts as $index => $post)
                                    <a 
                                        href="{{ route('posts.show', $post->slug) }}"
                                        wire:click="closeSearch"
                                        class="block dark:bg-gray-900 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg p-4 dark:hover:border-gray-600 hover:border-gray-300 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1 cursor-pointer"
                                        x-data="{ show: false }"
                                        x-init="
                                            setTimeout(() => {
                                                show = true;
                                            }, {{ $index * 50 }});
                                        "
                                        x-show="show"
                                        x-transition:enter="transition ease-out duration-400"
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                    >
                                        
                                        <!-- Post Meta (no user profile card) -->
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs dark:text-gray-400 text-gray-600">
                                                Posted by {{ $post->user->name ?? 'Unknown User' }} · {{ $post->created_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        <!-- Post Title & Content (Highlighted) -->
                                        <div class="mb-2">
                                            @if(!empty($post->title))
                                                <h3 class="text-sm font-semibold dark:text-white text-gray-900 mb-1">
                                                    {!! str_ireplace(e($query), '<mark class="bg-yellow-500/30 text-yellow-200">' . e($query) . '</mark>', e($post->title)) !!}
                                                </h3>
                                            @endif
                                            <p class="dark:text-gray-200 text-gray-700 text-sm leading-relaxed line-clamp-3">
                                                {{ \Illuminate\Support\Str::limit($post->content, 140) }}
                                            </p>
                                        </div>

                                        <!-- Post Specialties -->
                                        @if($post->specialties && $post->specialties->count() > 0)
                                            <div class="mb-2">
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
                                                            <span class="px-2 py-0.5 bg-blue-600/20 border border-blue-600/40 rounded-lg text-blue-300 text-xs">
                                                                {{ $specialty->name }} - {{ $subSpecialty->name }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Post Tags -->
                                        @if($post->tags && $post->tags->count() > 0)
                                            <div class="mb-2">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($post->tags as $tag)
                                                        <span class="px-2 py-0.5 bg-purple-600/20 border border-purple-600/40 rounded-lg text-purple-300 text-xs">
                                                            #{{ $tag->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Post Stats -->
                                        <div class="flex items-center gap-4 pt-2 border-t dark:border-gray-700 border-gray-200">
                                            <div class="flex items-center gap-1 dark:text-gray-400 text-gray-600 text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span>{{ $post->comments->count() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                                    <!-- Posts Pagination -->
                                    @if($posts->hasPages())
                                        <div class="mt-4 pt-4 border-t dark:border-gray-700 border-gray-200">
                                            {{ $posts->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- No Results -->
                        @if($posts->count() === 0 && $users->count() === 0)
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 dark:text-gray-600 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium dark:text-gray-400 text-gray-600 mb-2">No results found</h3>
                                <p class="text-sm dark:text-gray-500 text-gray-500">Try searching with different keywords</p>
                            </div>
                        @endif
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-400 mb-2">Start searching</h3>
                            <p class="text-sm text-gray-500">Enter keywords to find posts</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
