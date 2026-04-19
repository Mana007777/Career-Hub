<div
    class="min-h-screen text-gray-100 pb-24"
    x-data="{ loaded: true }"
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
        <!-- Floating Glass Header -->
        <div class="sticky top-6 z-40 px-4 mb-6 transition-all duration-500">
            <div class="max-w-5xl mx-auto bg-zinc-950/60 backdrop-blur-3xl border border-white/10 rounded-[2.5rem] h-24 flex items-center justify-between px-10 shadow-[0_50px_100px_rgba(0,0,0,0.5)] relative overflow-hidden group">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>
                <div class="absolute -inset-x-0 -inset-y-0 bg-gradient-to-br from-emerald-500/5 via-transparent to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-1000 pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="flex flex-col">
                        <h1 class="text-2xl font-black text-white tracking-tighter leading-none italic">
                            Career <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 via-cyan-500 to-emerald-500 bg-[length:200%_auto] animate-gradient">Hub</span>
                        </h1>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mt-1">{{ __('Community Feed') }}</p>
                    </div>
                </div>
                
                <div class="relative z-10 flex items-center gap-4">
                    <button 
                        wire:click="toggleFilters"
                        class="group px-6 py-3 rounded-2xl bg-zinc-900/50 hover:bg-zinc-800/50 border border-zinc-800/50 hover:border-emerald-500/30 text-zinc-400 hover:text-white transition-all duration-500 flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl">
                        <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>{{ __('Filters') }}</span>
                        @if($selectedJobType || $selectedTags || $selectedSpecialties)
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto w-full px-0 sm:px-2 lg:px-0 py-6">
        
        <!-- Filter Node -->
        @if($showFilters)
        <div 
            class="mb-10 bg-zinc-950/60 border border-zinc-800/50 rounded-[2rem] p-10 backdrop-blur-3xl shadow-3xl overflow-hidden relative"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
            x-transition:enter-start="opacity-0 -translate-y-8 blur-lg"
            x-transition:enter-end="opacity-100 translate-y-0 blur-0"
        >
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 blur-[60px]"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
                <!-- Sort Sector -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Sort Order') }}</label>
                    <div class="relative group">
                        <select 
                            wire:model.live="sortOrder"
                            class="w-full px-5 py-4 bg-zinc-950/50 border border-zinc-800/50 rounded-2xl text-zinc-100 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500/50 transition-all appearance-none cursor-pointer group-hover:bg-zinc-900/50 uppercase tracking-widest">
                            <option value="desc">{{ __('Newest First') }}</option>
                            <option value="asc">{{ __('Oldest First') }}</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Category Node -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Job Type') }}</label>
                    <div class="relative group">
                        <select 
                            wire:model.live="selectedJobType"
                            class="w-full px-5 py-4 bg-zinc-950/50 border border-zinc-800/50 rounded-2xl text-zinc-100 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500/50 transition-all appearance-none cursor-pointer group-hover:bg-zinc-900/50 uppercase tracking-widest">
                            <option value="">{{ __('All Job Types') }}</option>
                            @foreach($jobTypes as $jobType)
                                <option value="{{ $jobType }}">{{ strtoupper($jobType) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Tag Matrix -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Tags') }}</label>
                    <div class="relative group">
                        <select 
                            wire:model.live="selectedTags"
                            class="w-full px-5 py-4 bg-zinc-950/50 border border-zinc-800/50 rounded-2xl text-zinc-100 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500/50 transition-all appearance-none cursor-pointer group-hover:bg-zinc-900/50 uppercase tracking-widest">
                            <option value="">{{ __('All Tags') }}</option>
                            @foreach($allTags as $tag)
                                <option value="{{ $tag->id }}">#{{ strtoupper($tag->name) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Specialty Array -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Specialties') }}</label>
                    <div class="relative group">
                        <select 
                            wire:model.live="selectedSpecialties"
                            class="w-full px-5 py-4 bg-zinc-950/50 border border-zinc-800/50 rounded-2xl text-zinc-100 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500/50 transition-all appearance-none cursor-pointer group-hover:bg-zinc-900/50 uppercase tracking-widest">
                            <option value="">{{ __('All Specialties') }}</option>
                            @foreach($allSpecialties as $specialty)
                                <option value="{{ $specialty->id }}">{{ strtoupper($specialty->name) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Matrix Controls -->
            <div class="mt-10 flex justify-end gap-6 pt-8 border-t border-zinc-800/50 relative z-10">
                <button 
                    wire:click="clearFilters"
                    class="text-[10px] font-black text-zinc-500 hover:text-rose-500 uppercase tracking-[0.2em] transition-colors">
                    {{ __('Reset Filters') }}
                </button>
                <button 
                    wire:click="toggleFilters"
                    class="px-8 py-3 bg-emerald-500 text-black rounded-xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-emerald-400 transition-all shadow-[0_0_20px_rgba(16,185,129,0.2)] active:scale-95">
                    {{ __('Apply Filters') }}
                </button>
            </div>
        </div>
        @endif

    
        <!-- Encryption Input Module -->
        @if($showCreateForm)
        <div 
            class="mb-16 top-4 z-40 transform transition-all duration-500"
            id="create-post-form"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 -translate-y-12 scale-95 blur-md"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100 blur-0"
        >
            <div class="bg-zinc-900/60 border border-zinc-800/60 rounded-[2.5rem] overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,0.5)] backdrop-blur-3xl relative">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>
                
                <div class="px-10 py-6 bg-zinc-950/40 border-b border-zinc-800/50 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em]">{{ __('Create New Post') }}</h3>
                    </div>
                    <button wire:click="closeCreateForm" class="text-zinc-600 hover:text-white transition-colors p-2 hover:bg-zinc-800 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="create" wire:key="create-post-form" class="p-10 space-y-10">
                    <div class="space-y-4">
                        <label for="title" class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Title') }}</label>
                        <input
                            type="text"
                            wire:model="title"
                            wire:key="title-input"
                            id="title"
                            class="w-full px-8 py-5 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500/50 transition-all font-bold text-sm tracking-tight"
                            placeholder="{{ __('What is the primary goal?') }}">
                        @error('title')
                            <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 block ml-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <label for="content" class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Content') }}</label>
                        <textarea 
                            wire:model="content"
                            wire:key="content-input"
                            id="content"
                            rows="6"
                            class="w-full px-8 py-5 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500/50 transition-all resize-none text-sm leading-relaxed font-medium"
                            placeholder="{{ __('Write your post...') }}"></textarea>
                        @error('content') 
                            <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 block ml-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Job Type -->
                        <div class="space-y-4">
                            <label for="jobType" class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Job Type') }}</label>
                            <div class="relative group">
                                <select
                                    wire:model="jobType"
                                    wire:key="job-type-input"
                                    id="jobType"
                                    class="w-full px-6 py-4 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500/50 transition-all appearance-none cursor-pointer text-xs font-bold tracking-widest uppercase">
                                    <option value="">{{ __('Select Job Type') }}</option>
                                    <option value="full-time">{{ __('Full-time') }}</option>
                                    <option value="part-time">{{ __('Part-time') }}</option>
                                    <option value="contract">{{ __('Contract') }}</option>
                                    <option value="freelance">{{ __('Freelance') }}</option>
                                    <option value="internship">{{ __('Internship') }}</option>
                                    <option value="remote">{{ __('Remote') }}</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Media Integration -->
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Media') }}</label>
                            <label for="media" class="relative group flex items-center justify-center w-full px-6 py-4 bg-zinc-950/40 border border-zinc-800/50 border-dashed rounded-2xl hover:bg-emerald-500/5 hover:border-emerald-500/30 transition-all cursor-pointer">
                                <div class="flex items-center gap-4 text-zinc-500 group-hover:text-emerald-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $media ? strtoupper($media->getClientOriginalName()) : __('Upload media') }}</span>
                                </div>
                                <input type="file" wire:model="media" wire:key="media-input" id="media" accept="image/*,video/*" class="hidden">
                            </label>
                            @error('media') 
                                <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-1 block ml-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <!-- Specialty Synthesis -->
                    <div class="space-y-6 pt-8 border-t border-zinc-800/50">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Add Specialty') }}</label>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input 
                                type="text"
                                wire:model="specialtyName"
                                placeholder="{{ __('Specialty') }}"
                                class="flex-1 px-6 py-4 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all text-[10px] font-black uppercase tracking-widest">
                            <input 
                                type="text"
                                wire:model="subSpecialtyName"
                                placeholder="{{ __('Sub-specialty') }}"
                                class="flex-1 px-6 py-4 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all text-[10px] font-black uppercase tracking-widest">
                            <button 
                                type="button"
                                wire:click="addSpecialty"
                                class="px-8 py-4 bg-zinc-800 hover:bg-zinc-700 text-white text-[10px] font-black rounded-2xl transition-all border border-zinc-800 uppercase tracking-widest shadow-lg active:scale-95">
                                {{ __('Add') }}
                            </button>
                        </div>
                        
                        @if(count($specialties) > 0)
                            <div class="flex flex-wrap gap-3">
                                @foreach($specialties as $index => $spec)
                                    <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/20 rounded-xl text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] group">
                                        {{ $spec['specialty_name'] }} <span class="text-zinc-700">/</span> {{ $spec['sub_specialty_name'] }}
                                        <button type="button" wire:click="removeSpecialty({{ $index }})" class="hover:text-rose-500 transition-colors p-1 hover:bg-rose-500/10 rounded-lg">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Tag Synthesis -->
                    <div class="space-y-6 pt-8 border-t border-zinc-800/50">
                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">{{ __('Add Tag') }}</label>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input
                                type="text"
                                wire:model="tagName"
                                placeholder="{{ __('Tag name') }}"
                                class="flex-1 px-6 py-4 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all text-[10px] font-black uppercase tracking-widest">
                            <button
                                type="button"
                                wire:click="addTag"
                                class="px-8 py-4 bg-zinc-800 hover:bg-zinc-700 text-white text-[10px] font-black rounded-2xl transition-all border border-zinc-800 uppercase tracking-widest shadow-lg active:scale-95">
                                {{ __('Add') }}
                            </button>
                        </div>

                        @error('tags.*.name')
                            <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 block ml-1">{{ $message }}</span>
                        @enderror

                        @if(count($tags) > 0)
                            <div class="flex flex-wrap gap-3">
                                @foreach($tags as $index => $tag)
                                    <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/20 rounded-xl text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] group">
                                        #{{ $tag['name'] }}
                                        <button type="button" wire:click="removeTag({{ $index }})" class="hover:text-rose-500 transition-colors p-1 hover:bg-rose-500/10 rounded-lg">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Final Action Array -->
                    <div class="flex items-center justify-end gap-6 pt-10 border-t border-zinc-800/50">
                        <button 
                            type="button"
                            wire:click="closeCreateForm"
                            class="text-[10px] font-black text-zinc-500 hover:text-white transition-colors uppercase tracking-[0.3em]">
                            {{ __('Cancel') }}
                        </button>
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="create"
                            class="relative group px-12 py-5 bg-emerald-500 text-black text-[10px] font-black rounded-2xl transition-all shadow-[0_0_40px_rgba(16,185,129,0.2)] hover:bg-emerald-400 hover:shadow-[0_0_50px_rgba(16,185,129,0.4)] active:scale-95 disabled:opacity-50 uppercase tracking-[0.3em] overflow-hidden">
                            <span wire:loading.remove wire:target="create" class="relative z-10 flex items-center gap-3">
                                {{ __('Publish Post') }}
                            </span>
                            <span wire:loading wire:target="create" class="relative z-10 flex items-center gap-3">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                {{ __('Publishing...') }}
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-8 p-6 bg-emerald-950/20 border border-emerald-900/30 rounded-3xl text-emerald-500 text-[10px] font-black uppercase tracking-widest backdrop-blur-xl shadow-2xl flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-8 p-6 bg-red-950/20 border border-red-900/30 rounded-3xl text-red-500 text-[10px] font-black uppercase tracking-widest backdrop-blur-xl shadow-2xl flex items-center gap-3">
                <div class="w-8 h-8 bg-red-600/10 rounded-xl flex items-center justify-center border border-red-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Intellectual Property Grid -->
        <div class="grid gap-8 md:grid-cols-2 lg:mx-auto max-w-5xl">
            @forelse ($posts as $index => $post)
                <!-- Intelligence Card -->
                <article 
                    onclick="window.location.href='{{ route('posts.show', $post->slug) }}'"
                    class="group relative flex flex-col rounded-[2.5rem] border border-zinc-800/50 bg-zinc-950/60 backdrop-blur-3xl p-10 shadow-[0_20px_40px_rgba(0,0,0,0.3)] hover:border-emerald-500/30 transition-all duration-700 transform hover:scale-[1.02] hover:-translate-y-2 cursor-pointer overflow-hidden"
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, {{ ($index % 10) * 100 }})"
                    x-show="show"
                    x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
                    x-transition:enter-start="opacity-0 translate-y-12 scale-95 blur-md"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100 blur-0"
                >
                    <!-- Energy Aura -->
                    <div class="absolute -top-32 -right-32 w-64 h-64 bg-emerald-500/5 blur-[120px] group-hover:bg-emerald-500/10 transition-all duration-1000"></div>

                    <!-- Operative Identity -->
                    <div class="flex items-start justify-between mb-8 relative z-10">
                        <div class="flex items-center gap-5 flex-1">
                            <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" 
                               onclick="event.stopPropagation()" 
                               class="relative flex-shrink-0 group/avatar">
                                <div class="w-14 h-14 rounded-2xl overflow-hidden bg-zinc-950 ring-2 ring-zinc-800/50 group-hover/avatar:ring-emerald-500/50 transition-all duration-500 p-0.5">
                                    <div class="w-full h-full rounded-[0.8rem] overflow-hidden">
                                        @if($post->user && $post->user->profile_photo_path)
                                            <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover grayscale group-hover/avatar:grayscale-0 transition-all duration-700">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-zinc-900 text-emerald-500 font-black text-xs uppercase tracking-tighter">
                                                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-4.5 h-4.5 bg-emerald-500 border-[3px] border-zinc-900 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                            </a>
                            
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.3em] group-hover:text-emerald-400 transition-colors truncate">{{ $post->user->name ?? 'Unknown Operative' }}</h3>
                                    @if($post->user && $post->user->hasBlueTick())
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 shadow-[0_0_14px_rgba(16,185,129,0.35)] animate-pulse" title="Verified">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M12 2l2.3 5.1L20 9l-4 4.1L17 19l-5-2.9L7 19l1-5.9L4 9l5.7-1.9L12 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    @if($post->isUnderActiveSuspension())
                                        <span class="px-2.5 py-1 text-[8px] font-black uppercase tracking-widest rounded-lg bg-rose-500/10 text-rose-500 border border-rose-500/20 animate-pulse">
                                            Account Locked
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] font-bold text-zinc-600 uppercase tracking-widest mt-1.5">{{ \App\Support\SoraniTime::human($post->created_at) }}</p>
                            </div>
                            
                            <!-- Control Interface -->
                            @if(auth()->check() && $post->user_id !== auth()->id())
                                @php $isFollowing = $this->isFollowing($post->user_id); @endphp
                                <button 
                                    wire:click.stop="toggleFollow({{ $post->user_id }})"
                                    class="ml-auto px-5 py-2 text-[8px] font-black uppercase tracking-[0.2em] rounded-xl transition-all {{ $isFollowing ? 'bg-zinc-800 text-zinc-400 border border-zinc-700/50 hover:bg-zinc-700/50' : 'bg-emerald-500 text-black hover:bg-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.2)]' }}">
                                    {{ $isFollowing ? __('Following') : __('Follow') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Transmission Data -->
                    <div class="space-y-6 mb-8 relative z-10 flex-1">
                        @if(!empty($post->title))
                            <h2 class="text-2xl font-black text-white uppercase tracking-tighter leading-[1.1] group-hover:text-emerald-400 transition-colors duration-500 italic">
                                {{ $post->title }}
                            </h2>
                        @endif

                        @if($post->job_type)
                            <div class="flex">
                                <span class="inline-flex items-center gap-2.5 px-4 py-1.5 bg-emerald-500/5 text-emerald-500 text-[8px] font-black uppercase tracking-[0.3em] rounded-xl border border-emerald-500/20 group-hover:bg-emerald-500/10 transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 ring-4 ring-emerald-500/20 animate-pulse"></span>
                                    {{ str_replace('-', ' ', $post->job_type) }}
                                </span>
                            </div>
                        @endif

                        <p class="text-zinc-400 leading-relaxed text-sm font-medium line-clamp-4 group-hover:text-zinc-300 transition-colors">
                            {{ $post->content }}
                        </p>

                        @if(\Illuminate\Support\Str::length($post->content) > 280)
                            <button wire:click.stop="openInlinePostModal({{ $post->id }})" class="text-[9px] font-black text-emerald-500 hover:text-emerald-400 transition-all uppercase tracking-[0.4em] flex items-center gap-2 mt-4 group/more">
                                Expand Intelligence
                                <svg class="w-3 h-3 group-hover/more:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4">
                                    <path d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Sector Markers -->
                    @if(($post->specialties && $post->specialties->count() > 0) || ($post->tags && $post->tags->count() > 0))
                    <div class="flex flex-wrap gap-3 mb-8 relative z-10 pt-6 border-t border-zinc-800/50">
                        @if($post->specialties)
                            @foreach($post->specialties as $specialty)
                                @php
                                    $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null;
                                    $subSpecialty = $subSpecialtyId && $specialty->subSpecialties ? $specialty->subSpecialties->firstWhere('id', $subSpecialtyId) : null;
                                @endphp
                                @if($subSpecialty)
                                    <span class="px-3 py-1.5 bg-zinc-950/50 text-zinc-500 text-[9px] font-black uppercase tracking-widest rounded-lg border border-zinc-800/50 hover:text-zinc-300 transition-colors">
                                        {{ $specialty->name }} <span class="text-zinc-800 mx-1">/</span> {{ $subSpecialty->name }}
                                    </span>
                                @endif
                            @endforeach
                        @endif
                        @if($post->tags)
                            @foreach($post->tags as $tag)
                                <span class="px-3 py-1.5 bg-emerald-500/5 text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-500/10 hover:bg-emerald-500/10 transition-colors">
                                    #{{ strtoupper($tag->name) }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                    @endif

                    <!-- Operational Buttons -->
                    <div class="flex items-center justify-between mt-auto pt-8 border-t border-zinc-800/50 relative z-10">
                        <div class="flex items-center gap-8">
                            @php
                                $hasStarred = auth()->check() && $post->relationLoaded('stars') && $post->stars->isNotEmpty();
                                $hasSaved = auth()->check() && in_array($post->id, $savedPostIds ?? []);
                            @endphp
                            
                            <button wire:click.stop="togglePostStar({{ $post->id }})" 
                                class="flex items-center gap-3 group/btn">
                                <div class="p-2.5 rounded-2xl transition-all duration-300 {{ $hasStarred ? 'bg-emerald-500/10 text-emerald-500' : 'text-zinc-600 group-hover/btn:bg-emerald-500/10 group-hover/btn:text-emerald-500' }}">
                                    <svg class="w-5 h-5 transition-transform group-hover/btn:scale-125" fill="{{ $hasStarred ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-black {{ $hasStarred ? 'text-emerald-500' : 'text-zinc-600' }} tracking-widest">{{ $post->stars_count ?? $post->stars->count() }}</span>
                            </button>

                            <button onclick="event.stopPropagation()" class="flex items-center gap-3 group/btn">
                                <div class="p-2.5 rounded-2xl transition-all duration-300 text-zinc-600 group-hover/btn:bg-cyan-500/10 group-hover/btn:text-cyan-500">
                                    <svg class="w-5 h-5 transition-transform group-hover/btn:scale-125" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-black text-zinc-600 tracking-widest">{{ $post->comments_count ?? $post->comments->count() }}</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-3">
                            <button wire:click.stop="togglePostSave({{ $post->id }})" 
                                class="p-2.5 rounded-2xl transition-all duration-300 {{ $hasSaved ? 'bg-emerald-500/10 text-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.1)]' : 'text-zinc-600 hover:bg-emerald-500/10 hover:text-emerald-500' }}">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-125" fill="{{ $hasSaved ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z"></path>
                                </svg>
                            </button>
                            
                            @if ($post->user_id === auth()->id() || (auth()->check() && auth()->user()->isAdmin()))
                                <div class="relative" x-data="{ open: false }">
                                    <button @click.stop="open = !open" class="p-2.5 rounded-2xl text-zinc-600 hover:bg-zinc-800 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                         class="absolute right-0 bottom-full mb-4 w-56 bg-zinc-950/95 border border-zinc-800/80 rounded-[1.5rem] shadow-3xl overflow-hidden py-2 backdrop-blur-3xl z-50">
                                        @if($post->user_id === auth()->id())
                                            <button wire:click.stop="openEditModal({{ $post->id }})" class="w-full text-left px-5 py-3 text-[10px] font-black tracking-widest text-zinc-400 hover:text-emerald-400 hover:bg-emerald-500/5 flex items-center gap-3 transition-colors uppercase">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Modify Node
                                            </button>
                                        @endif
                                        <button wire:click.stop="openDeleteModal({{ $post->id }})" class="w-full text-left px-5 py-3 text-[10px] font-black tracking-widest text-rose-500 hover:bg-rose-500/10 flex items-center gap-3 transition-colors uppercase">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Purge Sector
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-32 text-center">
                    <div class="inline-flex p-10 rounded-full bg-zinc-900/50 mb-8 border border-zinc-800/50 shadow-inner group">
                        <svg class="w-16 h-16 text-zinc-700 group-hover:text-emerald-500/50 transition-colors duration-1000" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter">Void Detected</h3>
                    <p class="text-zinc-500 mt-4 font-bold uppercase tracking-[0.3em] text-[10px]">No active signals in this sector.</p>
                </div>
            @endforelse
        </div>

        <!-- Sync / More Data -->
        @if($posts->hasMorePages())
            <div class="mt-20 flex justify-center">
                <button 
                    wire:click="loadMore"
                    wire:loading.attr="disabled"
                    class="group relative flex items-center gap-6 text-zinc-600 hover:text-emerald-400 transition-all duration-700 font-black uppercase tracking-[0.5em] text-[10px]">
                    <span class="h-px w-24 bg-zinc-800/50 group-hover:bg-emerald-500/30 transition-all duration-1000"></span>
                    <span class="flex items-center gap-4">
                        <span wire:loading.remove wire:target="loadMore">Deeper Scan</span>
                        <span wire:loading wire:target="loadMore" class="flex items-center gap-4">
                            <svg class="animate-spin h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-80" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Syncing...
                        </span>
                    </span>
                    <span class="h-px w-24 bg-zinc-800/50 group-hover:bg-emerald-500/30 transition-all duration-1000"></span>
                </button>
            </div>
        @endif
    </div>


    <!-- Edit Post Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" x-data="{}" x-init="$el.focus()">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/95 backdrop-blur-md" wire:click="closeEditModal"></div>

                <div class="inline-block align-bottom bg-zinc-950/95 border border-white/5 rounded-3xl text-left overflow-hidden shadow-3xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full backdrop-blur-xl" wire:click.stop>
                    <div class="px-8 py-5 bg-white/5 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-[10px] font-black text-white uppercase tracking-widest">Adjust Mission Parameters</h3>
                        <button wire:click="closeEditModal" class="text-gray-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="update" class="p-8 space-y-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <label for="editTitle" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Headline</label>
                            <input
                                type="text"
                                wire:model="editTitle"
                                id="editTitle"
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all text-sm font-medium"
                                placeholder="UPDATE MISSION TITLE">
                            @error('editTitle') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Content -->
                        <div class="space-y-2">
                            <label for="editContent" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Data Stream</label>
                            <textarea 
                                wire:model="editContent"
                                id="editContent"
                                rows="6"
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all text-sm resize-none shadow-inner leading-relaxed"
                                placeholder="MODIFY BROADCAST..."></textarea>
                            @error('editContent') <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Job Type -->
                            <div class="space-y-2">
                                <label for="editJobType" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 ml-1">Job Specification</label>
                                <select wire:model="editJobType" id="editJobType" class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-[10px] font-black uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all appearance-none cursor-pointer">
                                    <option value="" class="bg-zinc-950">Select type</option>
                                    <option value="full-time" class="bg-zinc-950">Full-time</option>
                                    <option value="part-time" class="bg-zinc-950">Part-time</option>
                                    <option value="contract" class="bg-zinc-950">Contract</option>
                                    <option value="freelance" class="bg-zinc-950">Freelance</option>
                                    <option value="internship" class="bg-zinc-950">Internship</option>
                                    <option value="remote" class="bg-zinc-950">Remote</option>
                                </select>
                            </div>

                            <!-- Media -->
                            <div class="space-y-2">
                                <label for="editMedia" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 ml-1">Update Attachment</label>
                                <label class="flex items-center justify-center w-full px-5 py-[13px] bg-white/5 border border-white/10 border-dashed rounded-xl hover:bg-white/10 transition-all cursor-pointer">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate">{{ $editMedia ? $editMedia->getClientOriginalName() : 'Replace File' }}</span>
                                    <input type="file" wire:model="editMedia" id="editMedia" accept="image/*,video/*" class="hidden">
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/5">
                            <button type="button" wire:click="closeEditModal" class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">
                                Abort
                            </button>
                            <button type="submit" class="px-8 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-black text-[10px] font-black rounded-xl transition-all shadow-lg shadow-emerald-500/20 uppercase tracking-widest italic">
                                Commit Changes
                            </button>
                        </div>
                   </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Report Modal -->
    @livewire('report-modal')

    <!-- Purge Confirmation Proto -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-[70] overflow-y-auto" x-data="{}" x-init="$el.focus()">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-zinc-950/98 backdrop-blur-2xl" wire:click="closeDeleteModal"></div>

                <div class="inline-block align-bottom bg-zinc-900 border border-zinc-800/50 rounded-[3rem] text-left overflow-hidden shadow-3xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full relative" wire:click.stop>
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-8 animate-pulse shadow-[0_0_30px_rgba(244,63,94,0.1)]">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4 uppercase tracking-tighter italic">Purge Sector?</h3>
                        <p class="text-zinc-500 text-[11px] font-bold tracking-widest leading-relaxed uppercase">
                            This action results in permanent data loss. The node will be disconnected from the feed.
                        </p>
                    </div>

                    <div class="px-12 py-8 bg-zinc-950/50 flex gap-4">
                        <button wire:click="closeDeleteModal" class="flex-1 px-6 py-4 text-[10px] font-black text-zinc-500 hover:text-white transition-colors uppercase tracking-[0.3em]">
                            Abort
                        </button>
                        <button wire:click="delete" class="flex-1 px-8 py-4 bg-rose-600 hover:bg-rose-500 text-white text-[10px] font-black rounded-2xl transition-all shadow-[0_0_30px_rgba(225,29,72,0.3)] uppercase tracking-[0.3em] active:scale-95">
                            Purge Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Overseer Protocol Modal -->
    @if ($showAdminActionsModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-zinc-950/95 backdrop-blur-2xl" wire:click="closeAdminActionsModal"></div>

                <div class="inline-block align-bottom bg-zinc-900 border border-zinc-800/50 rounded-[2.5rem] text-left overflow-hidden shadow-3xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full backdrop-blur-3xl relative" wire:click.stop>
                    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>
                    
                    <div class="bg-zinc-950/40 px-8 py-8 border-b border-zinc-800/50 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                            <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em]">
                                @if($adminActionType === 'suspend')
                                    Lock Interaction Sector
                                @elseif($adminActionType === 'unsuspend')
                                    Restore Protocol
                                @elseif($adminActionType === 'delete')
                                    Terminal Sector Purge
                                @endif
                            </h3>
                        </div>
                    </div>
                    
                    @if($adminActionType === 'suspend')
                        <form wire:submit.prevent="suspendPost" class="px-10 py-10 space-y-8">
                            <div class="space-y-4">
                                <label for="suspendReason" class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">Lockdown Rationale *</label>
                                <textarea
                                    wire:model="suspendReason"
                                    id="suspendReason"
                                    rows="3"
                                    class="w-full px-8 py-5 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white placeholder-zinc-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all resize-none text-xs font-bold leading-relaxed"
                                    placeholder="ENTER AUTHORIZATION CODE & REASON..."></textarea>
                                @error('suspendReason')
                                    <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 block ml-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="space-y-4">
                                <label for="suspendExpiresAt" class="block text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">Lock Duration Target</label>
                                <input
                                    type="datetime-local"
                                    wire:model="suspendExpiresAt"
                                    id="suspendExpiresAt"
                                    min="{{ now()->format('Y-m-d\TH:i') }}"
                                    class="w-full px-8 py-5 bg-zinc-950/40 border border-zinc-800/50 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all text-xs font-black uppercase">
                                @error('suspendExpiresAt')
                                    <span class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-2 block ml-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end gap-6 pt-10 border-t border-zinc-800/50">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="text-[10px] font-black text-zinc-500 hover:text-white transition-colors uppercase tracking-[0.3em]">
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="suspendPost"
                                    class="px-10 py-5 bg-emerald-500 text-black text-[10px] font-black rounded-2xl transition-all shadow-[0_0_30px_rgba(16,185,129,0.2)] uppercase tracking-[0.3em] active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="suspendPost">Confirm Lockdown</span>
                                    <span wire:loading wire:target="suspendPost">Initiating...</span>
                                </button>
                            </div>
                        </form>
                    @elseif($adminActionType === 'unsuspend')
                        <div class="px-10 py-10 space-y-8">
                            <p class="text-zinc-400 text-xs font-bold tracking-widest leading-relaxed uppercase italic">Authorize the restoration of this data sector? Terminal transparency will be re-established.</p>
                            <div class="flex justify-end gap-6 pt-10 border-t border-zinc-800/50">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="text-[10px] font-black text-zinc-500 hover:text-white transition-colors uppercase tracking-[0.3em]">
                                    Abort
                                </button>
                                <button 
                                    type="button"
                                    wire:click="unsuspendPost({{ $adminActionPostId }})"
                                    wire:loading.attr="disabled"
                                    wire:target="unsuspendPost"
                                    class="px-10 py-5 bg-emerald-500 text-black text-[10px] font-black rounded-2xl transition-all shadow-[0_0_30px_rgba(16,185,129,0.2)] uppercase tracking-[0.3em] active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="unsuspendPost">Restore Protocol</span>
                                    <span wire:loading wire:target="unsuspendPost">Restoring...</span>
                                </button>
                            </div>
                        </div>
                    @elseif($adminActionType === 'delete')
                        <div class="px-10 py-10 space-y-8">
                            <p class="text-rose-500 font-black text-[11px] uppercase tracking-[0.2em] italic">FATAL WARNING: COMMAND IS IRREVERSIBLE.</p>
                            <p class="text-zinc-400 text-xs font-bold tracking-widest leading-relaxed uppercase">Initiate terminal removal of this intelligence node?</p>
                            <div class="flex justify-end gap-6 pt-10 border-t border-zinc-800/50">
                                <button 
                                    type="button"
                                    wire:click="closeAdminActionsModal"
                                    class="text-[10px] font-black text-zinc-500 hover:text-white transition-colors uppercase tracking-[0.3em]">
                                    Cancel
                                </button>
                                <button 
                                    type="button"
                                    wire:click="deletePostAsAdmin({{ $adminActionPostId }})"
                                    wire:loading.attr="disabled"
                                    wire:target="deletePostAsAdmin"
                                    class="px-10 py-5 bg-rose-600 text-white text-[10px] font-black rounded-2xl transition-all shadow-[0_0_30px_rgba(225,29,72,0.3)] uppercase tracking-[0.3em] active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="deletePostAsAdmin">Purge Node</span>
                                    <span wire:loading wire:target="deletePostAsAdmin">Purging...</span>
                                </button>
                            </div>
                        </div>
                    @endif
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
                            <div class="w-10 h-10 rounded-xl overflow-hidden bg-zinc-950 border border-white/5 flex items-center justify-center">
                                @if($inlinePost->user && $inlinePost->user->profile_photo_path)
                                    <img src="{{ $inlinePost->user->profile_photo_url }}" alt="{{ $inlinePost->user->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[10px] font-black text-emerald-500">
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
                            class="p-2 rounded-lg text-gray-500 hover:bg-white/10 hover:text-white transition-colors"
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
                                    <div class="bg-emerald-500/10 p-6 flex items-center justify-center">
                                        <a href="{{ $mediaUrl }}" target="_blank" class="flex items-center gap-3 text-emerald-500 hover:text-emerald-400 transition-all font-black uppercase tracking-widest text-xs italic">
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
                            class="px-8 py-2.5 rounded-xl bg-emerald-500 text-black font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-emerald-500/20 italic"
                            wire:click="closeInlinePostModal"
                        >
                            View Full Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Protocol Navigation Matrix -->
    <div 
        x-data="{ 
            isVisible: true,
            lastScroll: 0,
            init() {
                this.lastScroll = window.pageYOffset || window.scrollY;
                window.addEventListener('scroll', () => {
                    const currentScroll = window.pageYOffset || window.scrollY;
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
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
        x-transition:enter-start="opacity-0 transform translate-y-full -translate-x-1/2 blur-lg"
        x-transition:enter-end="opacity-100 transform translate-y-0 -translate-x-1/2 blur-0"
        x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
        x-transition:leave-start="opacity-100 transform translate-y-0 -translate-x-1/2 blur-0"
        x-transition:leave-end="opacity-0 transform translate-y-full -translate-x-1/2 blur-lg"
        class="fixed bottom-0 z-50 max-w-xl w-full left-1/2 -translate-x-1/2 bg-zinc-950/40 backdrop-blur-3xl rounded-[2.5rem] shadow-[0_50px_100px_rgba(0,0,0,0.5)] mb-8 mx-auto px-6 py-4 border border-zinc-800/50"
    >
        <div class="w-full mb-4">
            <div class="grid max-w-md grid-cols-3 gap-2 p-1.5 mx-auto bg-zinc-900/50 rounded-2xl border border-zinc-800/50 shadow-inner" role="group">
                <button
                    type="button"
                    wire:click="setFeedMode('new')"
                    class="px-6 py-3 text-[9px] font-black uppercase tracking-[0.2em] rounded-xl transition-all {{ $feedMode === 'new' ? 'text-black bg-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.3)]' : 'text-zinc-500 hover:text-zinc-300 hover:bg-zinc-800/50' }}">
                    {{ __('Fresh') }}
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('popular')"
                    class="px-6 py-3 text-[9px] font-black uppercase tracking-[0.2em] rounded-xl transition-all {{ $feedMode === 'popular' ? 'text-black bg-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.3)]' : 'text-zinc-500 hover:text-zinc-300 hover:bg-zinc-800/50' }}">
                    {{ __('Trends') }}
                </button>
                <button
                    type="button"
                    wire:click="setFeedMode('following')"
                    class="px-6 py-3 text-[9px] font-black uppercase tracking-[0.2em] rounded-xl transition-all {{ $feedMode === 'following' ? 'text-black bg-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.3)]' : 'text-zinc-500 hover:text-zinc-300 hover:bg-zinc-800/50' }}">
                    {{ __('Network') }}
                </button>
            </div>
        </div>
        {{-- Bottom Navigation Component --}}
        <livewire:bottom-navigation />
    </div>
</div>
