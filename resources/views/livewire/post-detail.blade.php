<div
    class="min-h-screen bg-transparent text-white pb-24"
    style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);"
    x-data="{ loaded: false }"
    x-init="setTimeout(() => loaded = true, 50)"
>
    <!-- Skeleton -->
    <div x-show="!loaded">
        <x-skeleton.post-detail />
    </div>

    <!-- Actual content -->
    <div class="max-w-4xl mx-auto px-6 py-12" x-show="loaded" x-cloak>
        <!-- Back Button -->
        <div 
            class="mb-12"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <a 
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-4 text-emerald-500/70 hover:text-emerald-400 transition-all duration-500 group">
                <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800/50 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-black transition-all duration-500 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic text-bold">{{ __('Back') }}</span>
            </a>
        </div>

        @if($post)
            <article 
                class="group relative bg-zinc-950/60 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl shadow-[0_50px_100px_rgba(0,0,0,0.5)] overflow-hidden"
                x-show="loaded"
                x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
                x-transition:enter-start="opacity-0 translate-y-20 scale-95 blur-xl font-bold"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100 blur-0"
            >
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>

                <!-- Post Header -->
                <div class="flex items-start justify-between mb-12">
                    <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" class="flex items-center gap-6 group/author">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-zinc-950 border-2 border-zinc-800 overflow-hidden flex items-center justify-center p-0.5 group-hover/author:border-emerald-500/30 transition-all duration-700">
                             <div class="w-full h-full rounded-[1.2rem] bg-zinc-950 flex items-center justify-center">
                                @if($post->user && $post->user->profile_photo_path)
                                    <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover grayscale opacity-50 group-hover/author:grayscale-0 group-hover/author:opacity-100 transition-all duration-1000">
                                @else
                                    <span class="text-emerald-500/40 font-black text-xl italic">{{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}</span>
                                @endif
                             </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-sm font-black text-white uppercase tracking-tight italic group-hover/author:text-emerald-400 transition-colors">{{ $post->user->name ?? __('Unknown') }}</h3>
                                @if($post->user && $post->user->hasBlueTick())
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 shadow-[0_0_14px_rgba(16,185,129,0.35)] animate-pulse" title="{{ __('Verified') }}">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 2l2.3 5.1L20 9l-4 4.1L17 19l-5-2.9L7 19l1-5.9L4 9l5.7-1.9L12 2z"/>
                                        </svg>
                                    </span>
                                @endif
                                @if($post->suspension)
                                    <span class="px-4 py-1.5 text-[8px] font-black uppercase tracking-widest rounded-lg bg-rose-500/10 text-rose-500 border border-rose-500/20 italic">{{ __('Suspended') }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] font-black text-zinc-600 uppercase tracking-widest mt-1 italic">{{ $post->created_at->format('Y.m.d // H:i') }}</p>
                        </div>
                    </a>
                    
                    <div class="flex items-center gap-4">
                        @if ($post->user_id === auth()->id())
                            <a href="{{ route('dashboard') }}?edit={{ $post->id }}" class="w-12 h-12 rounded-2xl bg-zinc-950 border border-zinc-800 flex items-center justify-center text-zinc-600 hover:text-emerald-500 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                        @endif
                        
                        @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $post->user_id)
                            <div class="flex items-center gap-2">
                                @if($post->suspension)
                                    <button wire:click="unsuspendPost" class="w-12 h-12 rounded-2xl bg-zinc-950 border border-emerald-500/30 text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-black transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg></button>
                                @else
                                    <button wire:click="openSuspendModal" class="w-12 h-12 rounded-2xl bg-zinc-950 border border-amber-500/30 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-black transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg></button>
                                @endif
                                <button wire:click="deletePostAsAdmin({{ $post->id }})" class="w-12 h-12 rounded-2xl bg-zinc-950 border border-rose-500/30 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </div>
                        @endif
                        
                        @if(auth()->check() && auth()->id() !== $post->user_id && !auth()->user()->isAdmin())
                            <button onclick="window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'post', targetId: {{ $post->id }} } }));" class="w-12 h-12 rounded-2xl bg-zinc-950 border border-zinc-800 text-zinc-600 hover:text-rose-500 transition-all flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg></button>
                        @endif
                    </div>
                </div>

                <!-- Post Body -->
                <div class="mb-12">
                    @if(!empty($post->title))
                        <h1 class="text-4xl font-black text-white uppercase tracking-tighter mb-6 italic">{{ $post->title }}</h1>
                    @endif
                    
                    @if($post->job_type)
                        @php
                            $jobTypeLabel = match($post->job_type) {
                                'full-time' => __('Full-time'),
                                'part-time' => __('Part-time'),
                                'contract' => __('Contract'),
                                'freelance' => __('Freelance'),
                                'internship' => __('Internship'),
                                'remote' => __('Remote'),
                                default => __($post->job_type),
                            };
                        @endphp
                        <div class="mb-8 p-6 bg-emerald-500/5 border border-emerald-500/20 rounded-2xl flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></div>
                            <div>
                                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest italic">{{ __('Job Type') }}</p>
                                <p class="text-[13px] font-black text-emerald-400 uppercase tracking-tight italic">{{ $jobTypeLabel }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="prose prose-invert max-w-none">
                        <p class="text-zinc-300 text-lg leading-relaxed italic selection:bg-emerald-500/20 whitespace-pre-wrap font-bold font-medium">"{{ $post->content }}"</p>
                    </div>
                </div>

                <!-- Post Media -->
                @if ($post->media)
                    <div class="mb-12 rounded-[2rem] overflow-hidden border border-zinc-800 shadow-2xl relative group/media">
                        @php
                            $mediaUrl = $this->getMediaUrl($post);
                            $isImage = in_array(strtolower(pathinfo($post->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if ($isImage)
                            <img src="{{ $mediaUrl }}" alt="{{ __('Post media') }}" class="w-full h-full object-cover grayscale-0 opacity-80 group-hover/media:opacity-100 transition-opacity duration-1000">
                            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/50 to-transparent"></div>
                        @else
                            <div class="bg-zinc-950 p-12 flex flex-col items-center justify-center group-hover/media:bg-emerald-500/[0.02] transition-colors duration-1000">
                                <a href="{{ $mediaUrl }}" target="_blank" class="flex flex-col items-center gap-6 group/btn">
                                    <div class="w-20 h-20 rounded-[2rem] bg-emerald-500/10 border-2 border-emerald-500/20 flex items-center justify-center group-hover/btn:scale-110 group-hover/btn:border-emerald-500/50 transition-all duration-700">
                                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </div>
                                    <span class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em] group-hover/btn:text-emerald-400 transition-colors">{{ __('View Attachment') }}</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Tags & Specialties -->
                @if(($post->specialties && $post->specialties->count() > 0) || ($post->tags && $post->tags->count() > 0))
                    <div class="flex flex-wrap gap-4 mb-12 py-8 border-y border-zinc-800/50">
                        @foreach($post->specialties as $specialty)
                             @php $subSpecialtyId = $specialty->pivot->sub_specialty_id ?? null; $subSpecialty = $subSpecialtyId && $specialty->subSpecialties ? $specialty->subSpecialties->firstWhere('id', $subSpecialtyId) : null; @endphp
                             @if($subSpecialty)
                                <span class="px-6 py-2 bg-zinc-950 border border-emerald-500/20 rounded-xl text-emerald-500 text-[9px] font-black uppercase tracking-widest italic">{{ $specialty->name }} <span class="text-zinc-700 mx-2">//</span> {{ $subSpecialty->name }}</span>
                             @endif
                        @endforeach
                        @foreach($post->tags as $tag)
                            <span class="px-6 py-2 bg-zinc-950 border border-zinc-800 rounded-xl text-zinc-600 text-[9px] font-black uppercase tracking-widest italic group-hover:text-emerald-500/50 transition-colors">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Post Stats -->
                <div class="flex items-center gap-10">
                    <button wire:click="togglePostStar" class="flex items-center gap-4 text-xs font-black uppercase tracking-widest {{ $hasStarredPost ? 'text-emerald-500' : 'text-zinc-600 hover:text-emerald-400' }} transition-all group/stat italic">
                        <div class="w-12 h-12 rounded-2xl bg-zinc-950 border border-zinc-800 flex items-center justify-center group-hover/stat:border-emerald-500/30 transition-all">
                            <svg class="w-5 h-5 {{ $hasStarredPost ? 'fill-emerald-500' : '' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783-.57-1.838-.197-1.538-1.118l1.518-4.674c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        </div>
                        <span>{{ __(':count reactions', ['count' => $post->stars->count()]) }}</span>
                    </button>

                    <button wire:click="togglePostSave" class="flex items-center gap-4 text-xs font-black uppercase tracking-widest {{ $hasSavedPost ? 'text-cyan-500' : 'text-zinc-600 hover:text-cyan-400' }} transition-all group/stat italic">
                        <div class="w-12 h-12 rounded-2xl bg-zinc-950 border border-zinc-800 flex items-center justify-center group-hover/stat:border-cyan-500/30 transition-all">
                             <svg class="w-5 h-5 {{ $hasSavedPost ? 'fill-cyan-500' : '' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z" /></svg>
                        </div>
                        <span>{{ $hasSavedPost ? __('Saved') : __('Save Post') }}</span>
                    </button>
                </div>
            </article>

            <!-- CV Upload Protocol -->
            @auth
                @if($post->user_id !== auth()->id() && $post->job_type)
                    <div class="mt-12 group">
                        <div class="bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl group-hover:border-emerald-500/20 transition-all duration-700">
                             <div class="flex items-center justify-between mb-10">
                                <h2 class="text-2xl font-black text-white uppercase tracking-tighter italic">{{ __('Apply to Job') }}</h2>
                                @if($hasUploadedCv)
                                    <div class="px-8 py-3 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-500 text-[10px] font-black uppercase tracking-widest italic">{{ __('CV submitted') }} ✓</div>
                                @endif
                             </div>

                             @if(!$hasUploadedCv)
                                <form wire:submit.prevent="uploadCv" class="space-y-10">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                        <div class="space-y-4">
                                            <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">{{ __('CV File') }} (.PDF, .DOCX)</label>
                                            <div class="relative group/file">
                                                <input type="file" wire:model="cvFile" id="cvFile" accept=".pdf,.doc,.docx" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                                <div class="w-full px-8 py-4 bg-zinc-950 border-2 border-dashed border-zinc-800 rounded-3xl group-hover/file:border-emerald-500/30 transition-all flex items-center justify-between">
                                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $cvFile ? 'text-emerald-500' : 'text-zinc-700' }}">{{ $cvFile ? $cvFile->getClientOriginalName() : __('Select file...') }}</span>
                                                    <svg class="w-6 h-6 text-zinc-800 group-hover/file:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                </div>
                                            </div>
                                            @error('cvFile') <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-4">
                                            <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">{{ __('Message (Optional)') }}</label>
                                            <textarea wire:model="cvMessage" rows="2" class="w-full px-8 py-4 bg-zinc-950 border border-zinc-800 rounded-3xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all text-xs resize-none italic font-bold" placeholder="{{ __('Add additional details...') }}"></textarea>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" wire:loading.attr="disabled" class="px-12 py-5 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-emerald-500/10 hover:bg-emerald-400 transition-all italic">{{ __('Submit Application') }}</button>
                                    </div>
                                </form>
                             @endif
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Comments Matrix -->
            <div class="mt-20">
                <div class="flex items-center gap-6 mb-10">
                     <h2 class="text-3xl font-black text-white uppercase tracking-tighter italic">{{ __('Comments') }}</h2>
                     <div class="h-px flex-1 bg-gradient-to-r from-emerald-500/20 to-transparent"></div>
                </div>

                @auth
                    <form wire:submit.prevent="addComment" class="mb-12">
                        <div class="relative group/input">
                            <textarea wire:model.defer="content" rows="3" class="w-full px-10 py-8 bg-zinc-900/40 border border-zinc-800/80 rounded-[2.5rem] text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm italic font-bold focus:bg-zinc-900" placeholder="{{ __('Write a comment...') }}"></textarea>
                            <div class="absolute bottom-6 right-8">
                                <button type="submit" class="px-8 py-3 bg-emerald-500 text-black text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-400 shadow-xl shadow-emerald-500/10 transition-all italic">{{ __('Post Comment') }}</button>
                            </div>
                        </div>
                    </form>
                @endauth

                <div class="space-y-8">
                    @forelse($post->comments->whereNull('parent_id') as $index => $comment)
                        <div 
                            class="bg-zinc-900/20 border border-zinc-800/50 rounded-[2.5rem] p-8 hover:bg-zinc-900/40 transition-all duration-500 group/comment"
                            x-show="loaded"
                            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
                            x-transition:enter-start="opacity-0 translate-x-10"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            style="transition-delay: {{ $index * 100 }}ms"
                        >
                            <div class="flex items-start gap-6">
                                <div class="w-12 h-12 rounded-2xl bg-zinc-950 border border-zinc-800 overflow-hidden flex items-center justify-center p-0.5 group-hover/comment:border-emerald-500/30 transition-all">
                                    <div class="w-full h-full rounded-xl bg-zinc-900 flex items-center justify-center">
                                        @if($comment->user && $comment->user->profile_photo_path)
                                            <img src="{{ $comment->user->profile_photo_url }}" class="w-full h-full object-cover grayscale opacity-50">
                                        @else
                                            <span class="text-[10px] font-black text-emerald-500/40">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h4 class="text-[11px] font-black text-white uppercase tracking-widest italic group-hover/comment:text-emerald-400 transition-colors">{{ $comment->user->name }}</h4>
                                            <p class="text-[8px] font-black text-zinc-700 uppercase tracking-widest mt-1 italic">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $comment->user_id))
                                                 <button wire:click="deleteCommentAsAdmin({{ $comment->id }})" class="w-10 h-10 rounded-xl bg-zinc-950 border border-zinc-800 text-zinc-800 hover:text-rose-500 transition-all flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-zinc-400 text-sm leading-relaxed italic italic font-bold font-medium select-none">"{{ $comment->content }}"</p>
                                    
                                    <div class="mt-6 flex items-center gap-6" x-data="{ open: false }">
                                        <button wire:click="toggleCommentClap({{ $comment->id }})" class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest {{ $comment->claps->where('user_id', auth()->id())->first() ? 'text-emerald-500' : 'text-zinc-600 hover:text-emerald-400' }} transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M7 11l2-2m0 0l2-2m-2 2l2 2m-2-2L7 9m6 2l2-2m0 0l2-2m-2 2l2 2m-2-2l-2 2M5 15a4 4 0 004 4h6a4 4 0 004-4v-1H5v1z" /></svg>
                                            <span>{{ __(':count claps', ['count' => $comment->claps->count()]) }}</span>
                                        </button>
                                        @auth
                                            <button @click="open = !open" class="flex items-center gap-2 text-[9px] font-black text-zinc-600 uppercase tracking-widest hover:text-white transition-colors"><span>{{ __('Reply') }}</span></button>
                                        @endauth

                                        <div x-show="open" class="mt-6 w-full" x-transition>
                                            <form wire:submit.prevent="addReply({{ $comment->id }})" class="space-y-4">
                                                <textarea wire:model.defer="replyContent.{{ $comment->id }}" rows="2" class="w-full px-6 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-xs text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all italic" placeholder="{{ __('Write a reply...') }}"></textarea>
                                                <div class="flex justify-end"><button type="submit" class="px-6 py-2 bg-emerald-500 text-black text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-400 transition-all italic">{{ __('Post Reply') }}</button></div>
                                            </form>
                                        </div>
                                    </div>

                                    @if($comment->replies->count() > 0)
                                        <div class="mt-8 space-y-6 border-l-2 border-zinc-800/50 pl-8">
                                            @foreach($comment->replies as $reply)
                                                <div class="flex items-start gap-4">
                                                    <div class="w-8 h-8 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center justify-center text-[8px] font-black text-emerald-500/40 italic">{{ strtoupper(substr($reply->user->name, 0, 1)) }}</div>
                                                    <div class="flex-1">
                                                        <h5 class="text-[10px] font-black text-white uppercase tracking-widest italic">{{ $reply->user->name }}</h5>
                                                        <p class="text-zinc-500 text-xs mt-2 italic">"{{ $reply->content }}"</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-[10px] font-black text-zinc-700 uppercase tracking-[0.4em] text-center py-20 italic">{{ __('No comments yet.') }}</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    <!-- Modals -->
    @if ($showSuspendModal)
        <div class="fixed inset-0 z-[400] flex items-center justify-center p-6 bg-zinc-950/98 backdrop-blur-3xl" wire:click="closeSuspendModal">
             <div class="bg-zinc-900 border border-amber-500/30 rounded-[3rem] max-w-lg w-full overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,1)] relative" wire:click.stop>
                 <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-amber-500/30 to-transparent"></div>
                 <div class="p-10 border-b border-zinc-800/50 bg-zinc-950/40">
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic">{{ __('Suspend Post') }}</h3>
                 </div>
                 <form wire:submit.prevent="suspendPost" class="p-10 space-y-8">
                    <div class="space-y-4">
                        <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">{{ __('Reason') }} *</label>
                        <textarea wire:model="suspendReason" rows="3" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-3xl text-sm text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all italic font-bold"></textarea>
                        @error('suspendReason') <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-4">
                        <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">{{ __('Expiry date (Optional)') }}</label>
                        <input type="datetime-local" wire:model="suspendExpiresAt" class="w-full px-8 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-xs text-white uppercase focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all">
                    </div>
                    <div class="flex justify-end gap-6 pt-6">
                        <button type="button" wire:click="closeSuspendModal" class="text-[9px] font-black text-zinc-600 uppercase tracking-widest hover:text-white transition-colors italic">{{ __('Cancel') }}</button>
                        <button type="submit" class="px-10 py-4 bg-amber-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-xl shadow-xl shadow-amber-500/10 hover:bg-amber-400 transition-all font-bold italic">{{ __('Suspend') }}</button>
                    </div>
                 </form>
             </div>
        </div>
    @endif
</div>
