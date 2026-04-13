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
        @transitionend="document.body.style.overflow = ''"
        class="fixed inset-0 z-50 bg-black/95 backdrop-blur-xl"
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
                <div class="bg-black rounded-t-[2.5rem] p-6 border-b border-white/5 shadow-2xl shadow-black/50">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Search</h2>
                        <button 
                            wire:click="closeSearch"
                            class="p-2 text-gray-500 hover:text-white hover:bg-white/5 rounded-xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Result Type Tabs (URL filter: ?type=users|posts|all) -->
                    @if($query && strlen(trim($query)) > 0)
                    <div class="flex gap-2 mb-4">
                        <button 
                            wire:click="setResultType('all')"
                            class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ ($resultType ?? 'all') === 'all' ? 'bg-brand-purple text-white shadow-lg shadow-brand-purple/20' : 'bg-brand-deep/30 text-gray-400 hover:bg-brand-deep/50 hover:text-white' }}">
                            All
                        </button>
                        <button 
                            wire:click="setResultType('users')"
                            class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ ($resultType ?? 'all') === 'users' ? 'bg-brand-purple text-white shadow-lg shadow-brand-purple/20' : 'bg-brand-deep/30 text-gray-400 hover:bg-brand-deep/50 hover:text-white' }}">
                            Users
                        </button>
                        <button 
                            wire:click="setResultType('posts')"
                            class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ ($resultType ?? 'all') === 'posts' ? 'bg-brand-purple text-white shadow-lg shadow-brand-purple/20' : 'bg-brand-deep/30 text-gray-400 hover:bg-brand-deep/50 hover:text-white' }}">
                            Posts
                        </button>
                    </div>
                    @endif

                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text"
                            wire:model.live.debounce.300ms="query"
                            placeholder="Search for posts or users..."
                            class="w-full pl-12 pr-4 py-4 bg-brand-deep/30 border border-white/10 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-transparent transition-all shadow-inner"
                            autofocus>
                    </div>
                </div>

                <!-- Search Results -->
                <div 
                    x-data="{ loaded: false }"
                    x-init="setTimeout(() => loaded = true, 200)"
                >
                <div class="bg-black rounded-b-[2.5rem] max-h-[60vh] overflow-y-auto border-t border-white/5 shadow-2xl shadow-black/50">
                    @if($query && strlen(trim($query)) > 0)
                        <!-- Users Results -->
                        @if(in_array($resultType ?? 'all', ['all', 'users']) && $users->count() > 0)
                            <div class="p-6 border-b border-white/5">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 ml-1">Users</h3>
                                <div class="space-y-4">
                                    @foreach($users as $index => $user)
                                        <a 
                                            href="{{ route('user.profile', $user->username ?? 'unknown') }}"
                                            wire:click="closeSearch"
                                            class="block bg-brand-deep/10 border border-white/5 rounded-2xl p-5 hover:border-white/20 hover:bg-brand-deep/20 transition-all duration-500 group"
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
                                                <div class="w-14 h-14 rounded-2xl bg-brand-deep border border-white/5 overflow-hidden flex items-center justify-center text-xl font-black text-brand-violet ring-4 ring-white/5 group-hover:scale-110 transition-transform duration-500">
                                                    @if($user->profile_photo_path)
                                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    @endif
                                                </div>
                                                
                                                <!-- User Info -->
                                                <div class="flex-1">
                                                    <h4 class="text-base font-black text-white group-hover:text-brand-violet transition-colors">
                                                        {!! str_ireplace(e($query), '<mark class="bg-brand-purple/30 text-white">' . e($query) . '</mark>', e($user->name ?? 'Unknown User')) !!}
                                                    </h4>
                                                    @if($user->username)
                                                        <p class="text-sm text-gray-500">
                                                            {!! '@' . str_ireplace(e($query), '<mark class="bg-brand-purple/30 text-brand-violet">' . e($query) . '</mark>', e($user->username)) !!}
                                                        </p>
                                                    @endif
                                                </div>
                                                
                                                <!-- View Profile -->
                                                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-gray-500 group-hover:text-white group-hover:bg-brand-purple transition-all duration-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                
                                <!-- Users Pagination -->
                                @if($users->hasPages())
                                    <div class="mt-4 pt-4 border-t border-white/5">
                                        {{ $users->links() }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Posts Results -->
                        @if(in_array($resultType ?? 'all', ['all', 'posts']) && $posts->count() > 0)
                            <div class="p-6 {{ (in_array($resultType ?? 'all', ['all', 'users']) && $users->count() > 0) ? 'border-t border-white/5' : '' }}">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 ml-1">Posts</h3>
                                <div class="space-y-6">
                                    @foreach($posts as $index => $post)
                                    <a 
                                        href="{{ route('posts.show', $post->slug) }}"
                                        wire:click="closeSearch"
                                        class="block bg-brand-deep/10 border border-white/5 rounded-3xl p-6 hover:border-white/20 hover:bg-brand-deep/20 transition-all duration-500 group"
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
                                        
                                        <!-- Post Meta -->
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                                {{ $post->user->name ?? 'Unknown User' }} · {{ $post->created_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        <!-- Post Title & Content (Highlighted) -->
                                        <div class="mb-4">
                                            @if(!empty($post->title))
                                                <h3 class="text-lg font-black text-white mb-2 group-hover:text-brand-violet transition-colors">
                                                    {!! str_ireplace(e($query), '<mark class="bg-brand-purple/30 text-white">' . e($query) . '</mark>', e($post->title)) !!}
                                                </h3>
                                            @endif
                                            <p class="text-gray-300 text-sm leading-relaxed line-clamp-2">
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
                                                            $subSpecialty = $subSpecialtyId && $specialty->subSpecialties 
                                                                ? $specialty->subSpecialties->firstWhere('id', $subSpecialtyId) 
                                                                : null;
                                                        @endphp
                                                        @if($subSpecialty)
                                                            <span class="px-2.5 py-1 bg-brand-purple/10 border border-brand-purple/20 rounded-lg text-brand-violet text-[10px] font-black uppercase tracking-widest">
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
                                                        <span class="px-2.5 py-1 bg-brand-violet/10 border border-brand-violet/20 rounded-lg text-brand-violet text-[10px] font-black uppercase tracking-widest">
                                                            #{{ $tag->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Post Stats -->
                                        <div class="flex items-center gap-4 pt-4 border-t border-white/5">
                                            <div class="flex items-center gap-2 text-gray-500 text-[10px] font-black uppercase tracking-widest">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span>{{ $post->comments->count() }} Comments</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                                    <!-- Posts Pagination -->
                                    @if($posts->hasPages())
                                        <div class="mt-4 pt-4 border-t border-white/5">
                                            {{ $posts->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- No Results -->
                        @if((($resultType ?? 'all') === 'all' && $posts->count() === 0 && $users->count() === 0) || (($resultType ?? 'all') === 'users' && $users->count() === 0) || (($resultType ?? 'all') === 'posts' && $posts->count() === 0))
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-500 mb-2">No results found</h3>
                                <p class="text-sm text-gray-600">Try searching with different keywords</p>
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
