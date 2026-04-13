<div
    class="min-h-screen text-gray-100 pb-24"
    x-init="
        // Default to loaded if no navigation is happening
        loaded = true;

        const setLoaded = () => { loaded = true };
        const setLoading = () => { loaded = false };

        document.addEventListener('livewire:navigated', setLoaded);
        document.addEventListener('livewire:navigating', setLoading);
    "
>
    <!-- Skeleton while page / data is loading -->
    <div x-show="!loaded">
        <x-skeleton.post-list />
    </div>

    <!-- Actual content -->
    <div x-show="loaded" x-cloak>
        <!-- Sticky Header with Backdrop Blur -->
        <div class="sticky top-0 z-40 bg-black/95 backdrop-blur-2xl border-b border-white/10 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 h-20 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex flex-col">
                        <h1 class="text-2xl font-black text-white tracking-tight leading-none">
                            Career <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-purple via-brand-violet to-brand-purple bg-[length:200%_auto] animate-gradient">Hub</span>
                        </h1>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mt-1">Community Feed</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <button 
                        wire:click="toggleFilters"
                        class="group px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 hover:border-brand-purple/30 text-gray-200 hover:text-white transition-all duration-300 flex items-center gap-2 text-sm font-black uppercase tracking-widest shadow-lg shadow-black/50">
                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Filters</span>
                        @if($selectedJobType || $selectedTags || $selectedSpecialties)
                            <span class="flex h-2 w-2 rounded-full bg-brand-purple animate-pulse"></span>
                        @endif
                    </button>

                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto w-full px-0 sm:px-2 lg:px-0 py-6">
        
        <!-- Filter Section -->
        @if($showFilters)
        <div 
            class="mb-8 bg-brand-deep/20 border border-white/5 rounded-2xl p-6 backdrop-blur-md shadow-2xl"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Sort Order -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Sort Order</label>
                    <select 
                        wire:model.live="sortOrder"
                        class="w-full px-4 py-2.5 bg-brand-deep border border-white/10 rounded-xl text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-brand-purple/50 transition-all appearance-none cursor-pointer">
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                </div>
                
                <!-- Job Type -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Job Type</label>
                    <select 
                        wire:model.live="selectedJobType"
                        class="w-full px-4 py-2.5 bg-brand-deep border border-white/10 rounded-xl text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-brand-purple/50 transition-all appearance-none cursor-pointer">
                        <option value="">All Types</option>
                        @foreach($jobTypes as $jobType)
                            <option value="{{ $jobType }}">{{ ucfirst($jobType) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Tags -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Tags</label>
                    <select 
                        wire:model.live="selectedTags"
                        class="w-full px-4 py-2.5 bg-brand-deep border border-white/10 rounded-xl text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-brand-purple/50 transition-all appearance-none cursor-pointer">
                        <option value="">All Tags</option>
                        @foreach($allTags as $tag)
                            <option value="{{ $tag->id }}">#{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Specialties -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Specialties</label>
                    <select 
                        wire:model.live="selectedSpecialties"
                        class="w-full px-4 py-2.5 bg-brand-deep border border-white/10 rounded-xl text-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-brand-purple/50 transition-all appearance-none cursor-pointer">
                        <option value="">All Specialties</option>
                        @foreach($allSpecialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Clear Filters Button -->
            <div class="mt-6 flex justify-end gap-3 pt-6 border-t border-white/5">
                <button 
                    wire:click="clearFilters"
                    class="px-5 py-2 text-sm font-medium text-gray-400 hover:text-white transition-colors">
                    Reset all
                </button>
                <button 
                    wire:click="toggleFilters"
                    class="px-5 py-2 bg-brand-purple/10 hover:bg-brand-purple/20 text-brand-purple rounded-xl text-sm font-bold transition-all">
                    Apply Filters
                </button>
            </div>
        </div>
        @endif

    
        @if($showCreateForm)
        <div 
            class="mb-12 top-4 z-40 transform transition-all duration-500"
            id="create-post-form"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        >
            <div class="bg-black border border-white/10 rounded-2xl overflow-hidden shadow-2xl shadow-black/50">
                <div class="px-6 py-4 bg-white/5 border-b border-white/5 flex items-center justify-between">
                    <h3 class="text-sm font-black text-white uppercase tracking-[0.2em]">Create New Post</h3>
                    <button wire:click="closeCreateForm" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="create" wire:key="create-post-form" class="p-6 space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <label for="title" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Headline</label>
                        <input
                            type="text"
                            wire:model="title"
                            wire:key="title-input"
                            id="title"
                            class="w-full px-4 py-3 bg-black border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all font-medium"
                            placeholder="What's the main topic?">
                        @error('title')
                            <span class="text-rose-400 text-xs font-medium mt-1 block ml-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="space-y-2">
                        <label for="content" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Draft</label>
                        <textarea 
                            wire:model="content"
                            wire:key="content-input"
                            id="content"
                            rows="5"
                            class="w-full px-4 py-3 bg-black border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all resize-none shadow-inner"
                            placeholder="Share your thoughts, career updates, or job opportunities..."></textarea>
                        @error('content') 
                            <span class="text-rose-400 text-xs font-medium mt-1 block ml-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Job Type -->
                        <div class="space-y-2">
                            <label for="jobType" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Job Type</label>
                            <select
                                wire:model="jobType"
                                wire:key="job-type-input"
                                id="jobType"
                                class="w-full px-4 py-3 bg-brand-deep/30 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all appearance-none cursor-pointer">
                                <option value="">Select type (Optional)</option>
                                <option value="full-time">Full-time</option>
                                <option value="part-time">Part-time</option>
                                <option value="contract">Contract</option>
                                <option value="freelance">Freelance</option>
                                <option value="internship">Internship</option>
                                <option value="remote">Remote</option>
                            </select>
                        </div>

                        <!-- Media -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Media</label>
                            <label for="media" class="relative group flex items-center justify-center w-full px-4 py-3 bg-brand-deep/30 border border-white/10 border-dashed rounded-xl hover:bg-white/5 transition-all cursor-pointer">
                                <div class="flex items-center gap-3 text-gray-400 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $media ? $media->getClientOriginalName() : 'Upload Photo/Video' }}</span>
                                </div>
                                <input 
                                    type="file"
                                    wire:model="media"
                                    wire:key="media-input"
                                    id="media"
                                    accept="image/*,video/*"
                                    class="hidden">
                            </label>
                            @error('media') 
                                <span class="text-rose-400 text-xs font-medium mt-1 block ml-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Specialty Selection -->
                    <div class="space-y-4 pt-4 border-t border-white/5">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Specialties</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input 
                                type="text"
                                wire:model="specialtyName"
                                placeholder="Main Category"
                                class="flex-1 px-4 py-2.5 bg-brand-deep/30 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all text-sm">
                            <input 
                                type="text"
                                wire:model="subSpecialtyName"
                                placeholder="Sub Category"
                                class="flex-1 px-4 py-2.5 bg-brand-deep/30 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all text-sm">
                            <button 
                                type="button"
                                wire:click="addSpecialty"
                                class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white text-sm font-bold rounded-xl transition-all border border-white/5">
                                Add
                            </button>
                        </div>
                        
                        @if(count($specialties) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($specialties as $index => $spec)
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-brand-purple/10 border border-brand-purple/20 rounded-lg text-brand-violet text-xs font-black uppercase tracking-widest">
                                        {{ $spec['specialty_name'] }} <span class="text-brand-purple/50">/</span> {{ $spec['sub_specialty_name'] }}
                                        <button type="button" wire:click="removeSpecialty({{ $index }})" class="hover:text-rose-400 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-white/5">
                        <button 
                            type="button"
                            wire:click="closeCreateForm"
                            class="px-6 py-2.5 text-sm font-bold text-gray-400 hover:text-white transition-colors">
                            Discard
                        </button>
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="create"
                            class="px-10 py-2.5 bg-gradient-to-r from-brand-purple to-brand-violet hover:opacity-90 text-white font-black rounded-xl transition-all shadow-lg shadow-brand-purple/25 disabled:opacity-50 flex items-center gap-2 tracking-widest uppercase text-xs"
                            <span wire:loading.remove wire:target="create">Publish Post</span>
                            <span wire:loading wire:target="create" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Publishing...
                            </span>
                        </button>
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
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-2 lg:mx-auto max-w-4xl">
            @forelse ($posts as $index => $post)
                <!-- Post Card -->
                <article 
                    onclick="window.location.href='{{ route('posts.show', $post->slug) }}'"
                    class="group relative flex flex-col rounded-[2.5rem] border border-white/20 bg-gradient-to-br from-white/[0.08] to-white/[0.02] backdrop-blur-2xl p-7 shadow-2xl hover:from-white/[0.12] hover:to-white/[0.05] hover:border-brand-purple/40 transition-all duration-500 cursor-pointer overflow-hidden"
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, {{ ($index % 10) * 100 }})"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-y-12 scale-[0.98]"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                >
                    <!-- Background Glow -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-brand-purple/5 blur-[100px] group-hover:bg-brand-purple/10 transition-all duration-700"></div>

                    <!-- Post Header -->
                    <div class="flex items-start justify-between mb-6 relative z-10">
                        <div class="flex items-center gap-4 flex-1">
                            <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" 
                               onclick="event.stopPropagation()" 
                               class="relative flex-shrink-0 group/avatar">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-800 ring-2 ring-white/5 group-hover/avatar:ring-brand-purple/50 transition-all duration-300">
                                    @if($post->user && $post->user->profile_photo_path)
                                        <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-600 to-violet-600 text-white font-black text-lg">
                                            {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-gray-950 rounded-full shadow-sm"></div>
                            </a>
                            
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-gray-100 truncate group-hover:text-indigo-400 transition-colors">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                    @if($post->suspension)
                                        <span class="px-2 py-0.5 text-[10px] font-black uppercase tracking-widest rounded-md bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            Suspended
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs font-medium text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                            
                            <!-- Follow Button -->
                            @if(auth()->check() && $post->user_id !== auth()->id())
                                @php $isFollowing = $this->isFollowing($post->user_id); @endphp
                                <button 
                                    wire:click.stop="toggleFollow({{ $post->user_id }})"
                                    class="ml-auto px-4 py-1.5 text-xs font-bold rounded-xl transition-all {{ $isFollowing ? 'bg-white/5 text-gray-300 border border-white/5 hover:bg-white/10' : 'bg-white text-gray-950 hover:bg-indigo-50' }}">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Post Body -->
                    <div class="space-y-4 mb-6 relative z-10">
                        @if(!empty($post->title))
                            <h2 class="text-xl font-black text-white leading-tight group-hover:text-indigo-400 transition-colors duration-300">
                                {{ $post->title }}
                            </h2>
                        @endif

                        @if($post->job_type)
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-brand-purple/10 text-indigo-400 text-[10px] font-black uppercase tracking-wider rounded-lg border border-brand-purple/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-purple"></span>
                                    {{ str_replace('-', ' ', $post->job_type) }}
                                </span>
                            </div>
                        @endif

                        <p class="text-gray-300 leading-relaxed text-sm line-clamp-4">
                            {{ $post->content }}
                        </p>

                        @if(\Illuminate\Support\Str::length($post->content) > 280)
                            <button wire:click.stop="openInlinePostModal({{ $post->id }})" class="text-sm font-bold text-indigo-400 hover:text-brand-violet transition-colors uppercase tracking-widest text-xs">
                                Read More +
                            </button>
                        @endif
                    </div>

                    <!-- Tags & Specialties -->
                    @if(($post->specialties && $post->specialties->count() > 0) || ($post->tags && $post->tags->count() > 0))
                    <div class="flex flex-wrap gap-2 mb-6 relative z-10 pt-4 border-t border-white/5">
                        @if($post->specialties)
                            @foreach($post->specialties as $specialty)
                                @php
                                    $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null;
                                    $subSpecialty = $subSpecialtyId && $specialty->subSpecialties ? $specialty->subSpecialties->firstWhere('id', $subSpecialtyId) : null;
                                @endphp
                                @if($subSpecialty)
                                    <span class="px-2.5 py-1 bg-white/5 text-gray-400 text-[10px] font-bold rounded-md border border-white/5">
                                        {{ $specialty->name }} • {{ $subSpecialty->name }}
                                    </span>
                                @endif
                            @endforeach
                        @endif
                        @if($post->tags)
                            @foreach($post->tags as $tag)
                                <span class="px-2.5 py-1 bg-violet-500/5 text-violet-400 text-[10px] font-bold rounded-md border border-violet-500/10">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                    @endif

                    <!-- Interactions -->
                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-white/10 relative z-10">
                        <div class="flex items-center gap-6">
                            @php
                                $hasStarred = auth()->check() && $post->relationLoaded('stars') && $post->stars->isNotEmpty();
                                $hasSaved = auth()->check() && in_array($post->id, $savedPostIds ?? []);
                            @endphp
                            
                            <button wire:click.stop="togglePostStar({{ $post->id }})" 
                                class="flex items-center gap-2 group/btn">
                                <div class="p-2 rounded-xl transition-all duration-300 {{ $hasStarred ? 'bg-brand-violet/10 text-brand-violet' : 'text-gray-500 group-hover/btn:bg-brand-violet/10 group-hover/btn:text-brand-violet' }}">
                                    <svg class="w-5 h-5" fill="{{ $hasStarred ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold {{ $hasStarred ? 'text-brand-violet' : 'text-gray-500' }}">{{ $post->stars_count ?? $post->stars->count() }}</span>
                            </button>

                            <button onclick="event.stopPropagation()" class="flex items-center gap-2 group/btn">
                                <div class="p-2 rounded-xl transition-all duration-300 text-gray-500 group-hover/btn:bg-brand-purple/10 group-hover/btn:text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-gray-500">{{ $post->comments_count ?? $post->comments->count() }}</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            <button wire:click.stop="togglePostSave({{ $post->id }})" 
                                class="p-2 rounded-xl transition-all duration-300 {{ $hasSaved ? 'bg-brand-violet/10 text-brand-violet' : 'text-gray-500 hover:bg-brand-violet/10 hover:text-brand-violet' }}">
                                <svg class="w-5 h-5" fill="{{ $hasSaved ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z"></path>
                                </svg>
                            </button>
                            
                            @if ($post->user_id === auth()->id() || (auth()->check() && auth()->user()->isAdmin()))
                                <div class="relative" x-data="{ open: false }">
                                    <button @click.stop="open = !open" class="p-2 rounded-xl text-gray-500 hover:bg-white/10 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-2 w-48 bg-black border border-white/10 rounded-2xl shadow-2xl overflow-hidden py-1">
                                        @if($post->user_id === auth()->id())
                                            <button wire:click.stop="openEditModal({{ $post->id }})" class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-300 hover:bg-white/5 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit Post
                                            </button>
                                        @endif
                                        <button wire:click.stop="openDeleteModal({{ $post->id }})" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-500/10 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Delete Post
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex p-6 rounded-full bg-white/5 mb-4 shadow-inner border border-white/5">
                        <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-white">Quiet feed...</h3>
                    <p class="text-gray-500 mt-2 font-medium">Be the first to spark a conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination / Load More -->
        @if($posts->hasMorePages())
            <div class="mt-12 flex justify-center">
                <button 
                    wire:click="loadMore"
                    wire:loading.attr="disabled"
                    class="group relative flex items-center gap-4 text-gray-500 hover:text-brand-violet transition-all duration-300 font-bold uppercase tracking-[0.2em] text-xs">
                    <span class="h-px w-24 bg-white/20 group-hover:bg-brand-purple/50 transition-colors"></span>
                    <span class="flex items-center gap-2">
                        <span wire:loading.remove wire:target="loadMore">See more</span>
                        <span wire:loading wire:target="loadMore" class="flex items-center gap-2">
                            <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </span>
                    <span class="h-px w-24 bg-white/20 group-hover:bg-brand-purple/50 transition-colors"></span>
                </button>
            </div>
        @endif
    </div>


    <!-- Edit Post Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" x-data="{}" x-init="$el.focus()">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/95 backdrop-blur-md" wire:click="closeEditModal"></div>

                <div class="inline-block align-bottom bg-black rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/5" wire:click.stop>
                    <div class="px-6 py-4 bg-white/5 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-sm font-black text-gray-200 uppercase tracking-widest">Edit Post</h3>
                        <button wire:click="closeEditModal" class="text-gray-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="update" class="p-6 space-y-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <label for="editTitle" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Title</label>
                            <input
                                type="text"
                                wire:model="editTitle"
                                id="editTitle"
                                class="w-full px-4 py-3 bg-brand-deep/30 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all text-sm font-medium"
                                placeholder="Update your headline">
                            @error('editTitle') <span class="text-rose-400 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Content -->
                        <div class="space-y-2">
                            <label for="editContent" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Content</label>
                            <textarea 
                                wire:model="editContent"
                                id="editContent"
                                rows="6"
                                class="w-full px-4 py-3 bg-brand-deep/30 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all text-sm resize-none shadow-inner"
                                placeholder="What's changing?"></textarea>
                            @error('editContent') <span class="text-rose-400 text-xs mt-1 ml-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Job Type -->
                            <div class="space-y-2">
                                <label for="editJobType" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Job Type</label>
                                <select wire:model="editJobType" id="editJobType" class="w-full px-4 py-3 bg-brand-deep/30 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all appearance-none cursor-pointer">
                                    <option value="">Select type</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                    <option value="remote">Remote</option>
                                </select>
                            </div>

                            <!-- Media -->
                            <div class="space-y-2">
                                <label for="editMedia" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Update Media</label>
                                <label class="flex items-center justify-center w-full px-4 py-[13px] bg-brand-deep/30 border border-white/10 border-dashed rounded-xl hover:bg-white/5 transition-all cursor-pointer">
                                    <span class="text-xs font-bold text-gray-400 truncate">{{ $editMedia ? $editMedia->getClientOriginalName() : 'Change File' }}</span>
                                    <input type="file" wire:model="editMedia" id="editMedia" accept="image/*,video/*" class="hidden">
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/5">
                            <button type="button" wire:click="closeEditModal" class="px-6 py-2.5 text-xs font-bold text-gray-400 hover:text-white transition-colors uppercase tracking-widest">
                                Cancel
                            </button>
                            <button type="submit" class="px-8 py-2.5 bg-brand-purple hover:bg-brand-purple text-white text-xs font-black rounded-xl transition-all shadow-lg shadow-brand-purple/20 uppercase tracking-widest">
                                Save Changes
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
        <div class="fixed inset-0 z-[70] overflow-y-auto" x-data="{}" x-init="$el.focus()">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-brand-deep/30/90 backdrop-blur-md" wire:click="closeDeleteModal"></div>

                <div class="inline-block align-bottom bg-black rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-white/10" wire:click.stop>
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tight">Delete Post?</h3>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">
                            This action is permanent and cannot be undone. All interactions and comments will be lost.
                        </p>
                    </div>

                    <div class="px-8 py-6 bg-white/5 flex gap-3">
                        <button wire:click="closeDeleteModal" class="flex-1 px-6 py-3 text-xs font-black text-gray-400 hover:text-white transition-colors uppercase tracking-widest">
                            Keep it
                        </button>
                        <button wire:click="delete" class="flex-1 px-6 py-3 bg-rose-600 hover:bg-rose-500 text-white text-xs font-black rounded-xl transition-all shadow-lg shadow-rose-500/20 uppercase tracking-widest">
                            Delete Now
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
                <div class="fixed inset-0 transition-opacity bg-black/95 backdrop-blur-md" wire:click="closeAdminActionsModal"></div>

                <div class="inline-block align-bottom bg-black border border-white/5 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" wire:click.stop>
                    <div class="bg-black px-6 py-6 border-b border-white/5">
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">
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
                        <form wire:submit.prevent="suspendPost" class="bg-black px-6 py-6">
                            <div class="mb-4">
                                <label for="suspendReason" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Suspension Reason *</label>
                                <textarea
                                    wire:model="suspendReason"
                                    id="suspendReason"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-brand-deep/30 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 transition-all resize-none shadow-inner"
                                    placeholder="Enter reason..."></textarea>
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
                                    class="w-full px-4 py-2 bg-brand-deep bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Leave empty for permanent suspension</p>
                                @error('suspendExpiresAt')
                                    <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end gap-3 pt-6 border-t border-white/5">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="px-6 py-2.5 text-xs font-bold text-gray-400 hover:text-white transition-colors uppercase tracking-widest">
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="suspendPost"
                                    class="px-8 py-2.5 bg-brand-purple hover:bg-brand-violet text-white text-xs font-black rounded-xl transition-all shadow-lg shadow-brand-purple/20 disabled:opacity-50 uppercase tracking-widest">
                                    <span wire:loading.remove wire:target="suspendPost">Confirm Suspension</span>
                                    <span wire:loading wire:target="suspendPost">Processing...</span>
                                </button>
                            </div>
                        </form>
                    @elseif($adminActionType === 'unsuspend')
                        <div class="bg-black px-6 py-4">
                            <p class="mb-4 dark:text-gray-300 text-gray-700">Are you sure you want to unsuspend this post? It will become publicly visible again.</p>
                            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="px-4 py-2 dark:text-gray-300 bg-brand-deep hover:bg-white/5 text-white bg-gray-800 hover:bg-black rounded-lg transition-colors">
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
                        <div class="bg-black px-6 py-4">
                            <p class="mb-4 dark:text-red-400 text-red-600 font-semibold">Warning: This action cannot be undone!</p>
                            <p class="mb-4 dark:text-gray-300 text-gray-700">Are you sure you want to permanently remove this post?</p>
                            <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="px-4 py-2 dark:text-gray-300 bg-brand-deep hover:bg-white/5 text-white bg-gray-800 hover:bg-black rounded-lg transition-colors">
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
                <div class="fixed inset-0 transition-opacity bg-black/95 backdrop-blur-md" wire:click="closeSuspendModal"></div>

                <div class="inline-block align-bottom bg-black border border-white/5 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" wire:click.stop>
                    <div class="bg-black px-6 py-6 border-b border-white/5">
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">Suspend Post</h3>
                    </div>
                    
                    <form wire:submit.prevent="suspendPost" class="bg-black px-6 py-4">
                        <div class="mb-4">
                            <label for="suspendReason" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Suspension Reason *</label>
                            <textarea
                                wire:model="suspendReason"
                                id="suspendReason"
                                rows="3"
                                class="w-full px-4 py-2 bg-brand-deep bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent resize-none"
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
                                class="w-full px-4 py-2 bg-brand-deep bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Leave empty for permanent suspension</p>
                            @error('suspendExpiresAt')
                                <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeSuspendModal"
                                class="px-4 py-2 dark:text-gray-300 bg-brand-deep hover:bg-white/5 text-white bg-gray-800 hover:bg-black rounded-lg transition-colors">
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
                <div class="fixed inset-0 transition-opacity bg-black/95 backdrop-blur-md" wire:click="closeInlinePostModal"></div>

                <div class="inline-block align-bottom bg-black rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-white/5" wire:click.stop>
                    <div class="bg-black px-6 py-6 border-b border-white/5 flex items-center justify-between">
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
                                <h3 class="text-xl font-black text-white tracking-tight">
                                    {{ $inlinePost->title ?: 'Post' }}
                                </h3>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">
                                    {{ $inlinePost->created_at->format('F j, Y') }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="closeInlinePostModal"
                            class="p-2 rounded-lg dark:text-gray-400 text-gray-600 hover:bg-brand-deep hover:bg-gray-100 hover:text-gray-900 dark:hover:text-white transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="bg-black px-6 py-8 space-y-4">
                        @if(!empty($inlinePost->title))
                            <h2 class="text-2xl font-black text-white leading-tight">
                                {{ $inlinePost->title }}
                            </h2>
                        @endif

                        <p class="text-gray-300 leading-relaxed whitespace-pre-wrap text-base">
                            {{ $inlinePost->content }}
                        </p>

                        @if ($inlinePost->media)
                            <div class="mt-6 rounded-2xl overflow-hidden border border-white/5 shadow-2xl">
                                @php
                                    $mediaUrl = $this->getMediaUrl($inlinePost);
                                    $isImage = in_array(strtolower(pathinfo($inlinePost->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                @if ($isImage)
                                    <img src="{{ $mediaUrl }}" alt="Post media" class="w-full h-auto">
                                @else
                                    <div class="bg-brand-deep/20 p-6 flex items-center justify-center">
                                        <a href="{{ $mediaUrl }}" target="_blank" class="flex items-center gap-3 text-brand-violet hover:text-brand-purple transition-all font-black uppercase tracking-widest text-xs">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>View Attachment</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="bg-black px-6 py-6 border-t border-white/5 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            wire:click="closeInlinePostModal"
                            class="px-6 py-2.5 rounded-xl text-gray-400 hover:text-white font-bold text-xs uppercase tracking-widest transition-all"
                        >
                            Close
                        </button>
                        <a
                            href="{{ route('posts.show', $inlinePost->slug) }}"
                            class="px-8 py-2.5 rounded-xl bg-brand-purple text-white font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-brand-purple/20"
                            wire:click="closeInlinePostModal"
                        >
                            View Full Details
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
        class="fixed bottom-0 z-50 max-w-xl w-full -translate-x-1/2 bg-black/40 backdrop-blur-2xl rounded-[2rem] left-1/2 shadow-2xl mb-4 mx-auto px-4 py-3 border border-white/10"
    >
        <div class="w-full mb-2">
            <div class="grid max-w-xs grid-cols-3 gap-1 p-1 mx-auto bg-white/5 rounded-xl border border-white/5 shadow-inner" role="group">
                <button
                    type="button"
                    wire:click="setFeedMode('new')"
                    class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all {{ $feedMode === 'new' ? 'text-white bg-brand-purple shadow-lg shadow-brand-purple/20' : 'text-gray-500 hover:text-gray-300 hover:bg-white/5' }}">
                    New
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('popular')"
                    class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all {{ $feedMode === 'popular' ? 'text-white bg-brand-purple shadow-lg shadow-brand-purple/20' : 'text-gray-500 hover:text-gray-300 hover:bg-white/5' }}">
                    Popular
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('following')"
                    class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all {{ $feedMode === 'following' ? 'text-white bg-brand-purple shadow-lg shadow-brand-purple/20' : 'text-gray-500 hover:text-gray-300 hover:bg-white/5' }}">
                    Following
                </button>
            </div>
        </div>
        {{-- Bottom Navigation Component --}}
        <livewire:bottom-navigation />

        {{-- User notifications are mounted globally in the dashboard layout --}}
    </div>
</div>
