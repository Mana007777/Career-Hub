<div
    class="min-h-screen dark:bg-black bg-black text-white pb-24"
    style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);"
    x-data="{ loaded: false }"
    x-init="
        loaded = false;

        const setLoaded = () => { loaded = true };
        const setLoading = () => { loaded = false };

        document.addEventListener('livewire:load', setLoaded);
        document.addEventListener('livewire:navigated', setLoaded);
        document.addEventListener('livewire:navigating', setLoading);
    "
>
    <!-- Skeleton while post detail is loading -->
    <div x-show="!loaded">
        <x-skeleton.post-detail />
    </div>

    <!-- Actual content -->
    <div class="max-w-4xl mx-auto px-4 py-8" x-show="loaded" x-cloak>
        <!-- Back Button -->
        <div 
            class="mb-6"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <a 
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 text-gray-500 dark:hover:text-white hover:text-gray-900 transition-all duration-300 transform hover:translate-x-1 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Posts</span>
            </a>
        </div>

        @if($post)
            <div 
                class="bg-black border border-white/10 rounded-3xl p-8 shadow-2xl relative overflow-hidden"
                style="background-image: radial-gradient(circle at top right, rgba(112, 11, 151, 0.05), transparent);"
                x-show="loaded"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            >
                <!-- Post Header -->
                <div class="flex items-start justify-between mb-4">
                    <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-brand-deep flex items-center justify-center ring-4 ring-white/5">
                            @if($post->user && $post->user->profile_photo_path)
                                <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[10px] font-black text-brand-violet">
                                    {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-[10px] font-black text-white uppercase tracking-widest">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                @if($post->suspension)
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest rounded-md bg-rose-500/10 text-rose-500 border border-rose-500/20">
                                        Suspended
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">{{ $post->created_at->format('F j, Y') }}</p>
                        </div>
                    </a>
                    
                    <div class="flex items-center gap-2">
                        @if ($post->user_id === auth()->id())
                            <a 
                                href="{{ route('dashboard') }}?edit={{ $post->id }}"
                                class="p-2 text-gray-500 hover:text-blue-400 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        @endif
                        
                        {{-- Admin Actions (Only visible to admins, not post owners) --}}
                        @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $post->user_id)
                            <div class="flex items-center gap-2">
                                @if($post->suspension)
                                    <button 
                                        wire:click="unsuspendPost"
                                        wire:confirm="Are you sure you want to unsuspend this post?"
                                        class="p-2 dark:text-green-400 text-green-600 hover:text-green-500 dark:hover:bg-green-900/20 hover:bg-green-50 rounded-lg transition-colors"
                                        title="Admin: Unsuspend Post">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button 
                                        wire:click="openSuspendModal"
                                        class="p-2 dark:text-yellow-400 text-yellow-600 hover:text-yellow-500 dark:hover:bg-yellow-900/20 hover:bg-yellow-50 rounded-lg transition-colors"
                                        title="Admin: Suspend Post">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </button>
                                @endif
                                <button 
                                    wire:click="deletePostAsAdmin({{ $post->id }})"
                                    wire:confirm="Are you sure you want to delete this post as admin? This action cannot be undone."
                                    class="p-2 text-rose-400 hover:text-red-500 dark:hover:bg-red-900/20 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Admin: Delete Post">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                        
                        {{-- Report Post Button (Visible to all users except post owner and admins) --}}
                        @if(auth()->check() && auth()->id() !== $post->user_id && !auth()->user()->isAdmin())
                            <button 
                                onclick="event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'post', targetId: {{ $post->id }} } }));"
                                type="button"
                                class="p-2 text-gray-500 hover:text-orange-400 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors relative z-10"
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
                        <h1 class="text-2xl font-black text-white uppercase tracking-tighter mb-2">{{ $post->title }}</h1>
                    @endif
                    @if($post->job_type)
                        <div class="mb-3">
                            <span class="inline-flex items-center px-3 py-1 bg-brand-purple/10 border border-brand-purple/20 rounded-lg text-brand-violet text-xs font-black uppercase tracking-widest">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $post->job_type)) }}
                            </span>
                        </div>
                    @endif
                    <p class="text-gray-300 leading-relaxed whitespace-pre-wrap text-lg">{{ $post->content }}</p>
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
                                    <div class="bg-brand-deep/30 p-4 rounded-xl border border-white/5">
                                        <a href="{{ $mediaUrl }}" target="_blank" class="flex items-center gap-2 text-brand-violet hover:text-brand-purple transition-colors">
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
                    <div class="mb-4 pt-4 border-t border-white/5">
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
                                    <span class="px-3 py-1.5 bg-brand-purple/10 border border-brand-purple/20 rounded-lg text-brand-violet text-[10px] font-black uppercase tracking-widest">
                                        {{ $specialty->name }} - {{ $subSpecialty->name }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Post Tags -->
                @if($post->tags && $post->tags->count() > 0)
                    <div class="mb-4 pt-4 border-t border-white/5">
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="px-3 py-1.5 bg-brand-purple/10 border border-brand-purple/20 rounded-lg text-brand-violet text-[10px] font-black uppercase tracking-widest">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Post Stats -->
                @php
                    $hasStarredPost = auth()->check() && $post->stars->contains('user_id', auth()->id());
                    $hasSavedPost = auth()->check() && $hasSavedPost;
                @endphp
                <div class="flex items-center gap-6 pt-4 border-t border-white/5">
                    <button
                        type="button"
                        wire:click="togglePostStar"
                        class="flex items-center gap-2 text-sm {{ $hasStarredPost ? 'text-brand-violet' : 'text-gray-500 hover:text-brand-violet' }} transition-colors group">
                        <svg class="w-5 h-5 {{ $hasStarredPost ? 'fill-brand-violet' : '' }} group-hover:scale-110 transition-transform" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <span>{{ $post->stars->count() }}</span>
                    </button>

                    <button
                        type="button"
                        wire:click="togglePostSave"
                        class="flex items-center gap-2 text-sm {{ $hasSavedPost ? 'text-brand-purple' : 'text-gray-500 hover:text-brand-purple' }} transition-colors">
                        <svg class="w-5 h-5" fill="{{ $hasSavedPost ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z"></path>
                        </svg>
                        <span>{{ $hasSavedPost ? 'Saved' : 'Save' }}</span>
                    </button>

                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>{{ $post->comments->count() }} comments</span>
                    </div>
                </div>

                <!-- CV Upload Section (only show if user is not the post owner and post has job_type) -->
                @auth
                    @if($post->user_id !== auth()->id() && $post->job_type)
                        <div class="mt-8 pt-6 border-t border-white/5">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-white">Apply for this Job</h2>
                                @if($hasUploadedCv)
                                    <button
                                        type="button"
                                        disabled
                                        class="px-4 py-2 bg-green-600/80 text-white text-sm font-medium rounded-lg cursor-not-allowed">
                                        CV Uploaded ✓
                                    </button>
                                @endif
                            </div>

                            @if(!$hasUploadedCv)
                                <form wire:submit.prevent="uploadCv" class="bg-brand-deep/20 border border-white/5 rounded-2xl p-6">
                                    <div class="space-y-6">
                                        <div>
                                            <label for="cvFile" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">CV File *</label>
                                            <input
                                                type="file"
                                                wire:model="cvFile"
                                                id="cvFile"
                                                accept=".pdf,.doc,.docx"
                                                class="w-full px-4 py-3 bg-black border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-transparent">
                                            @error('cvFile')
                                                <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                            @if($cvFile)
                                                <p class="text-xs text-gray-500 mt-1">Selected: {{ $cvFile->getClientOriginalName() }}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label for="cvMessage" class="block text-sm font-medium text-gray-400 mb-2">Message (Optional)</label>
                                            <textarea
                                                wire:model="cvMessage"
                                                id="cvMessage"
                                                rows="3"
                                                class="w-full px-4 py-3 bg-black border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-transparent resize-none shadow-inner"
                                                placeholder="Add a message to your application..."></textarea>
                                            @error('cvMessage')
                                                <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end">
                                            <button
                                                type="submit"
                                                wire:loading.attr="disabled"
                                                wire:target="uploadCv"
                                                class="px-8 py-2.5 bg-gradient-to-r from-brand-purple to-brand-violet text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-brand-purple/20">
                                                <span wire:loading.remove wire:target="uploadCv">Submit CV</span>
                                                <span wire:loading wire:target="uploadCv">Uploading...</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endif
                @endauth

                <!-- Comments Section -->
                <div class="mt-8 pt-6 border-t border-white/5">
                    <h2 class="text-lg font-semibold text-white mb-4">Comments</h2>

                    @auth
                        <form wire:submit.prevent="addComment" class="mb-6">
                            <div class="flex flex-col gap-3">
                                <textarea
                                    wire:model.defer="content"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-brand-deep/30 border border-white/5 rounded-2xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-transparent resize-none shadow-inner"
                                    placeholder="Write a comment..."></textarea>
                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        class="px-6 py-2.5 bg-brand-purple hover:bg-brand-violet text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-brand-purple/25">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-sm text-gray-500 mb-4">
                            <a href="{{ route('login') }}" class="text-brand-violet hover:text-brand-purple">Log in</a> to comment.
                        </p>
                    @endauth

                    <div class="space-y-4">
                        @php
                            $rootComments = $post->comments->whereNull('parent_id');
                        @endphp

                        @forelse($rootComments as $index => $comment)
                            @php
                                $hasClappedComment = auth()->check() && $comment->claps->contains('user_id', auth()->id());
                            @endphp
                            <div 
                                class="bg-brand-deep/10 border border-white/5 rounded-2xl p-6 hover:border-white/10 transition-all duration-300 transform hover:scale-[1.01]"
                                x-data="{ show: false }"
                                x-init="
                                    setTimeout(() => {
                                        show = true;
                                    }, {{ $index * 100 }});
                                "
                                x-show="show"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0"
                            >
                                <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-brand-deep flex items-center justify-center text-[10px] font-black text-brand-violet ring-2 ring-white/5">
                                        @if($comment->user && $comment->user->profile_photo_path)
                                            <img src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-white">
                                                    {{ $comment->user->name ?? 'Unknown User' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                {{-- Admin Delete Comment Button (Only visible to admins, not comment owners) --}}
                                                @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $comment->user_id)
                                                    <button 
                                                        wire:click="deleteCommentAsAdmin({{ $comment->id }})"
                                                        wire:confirm="Are you sure you want to delete this comment as admin?"
                                                        class="p-1.5 text-rose-400 hover:text-red-500 hover:bg-red-900/20 rounded-lg transition-colors"
                                                        title="Admin: Delete Comment">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                
                                                {{-- Report Comment Button (Visible to all users except comment owner and admins) --}}
                                                @if(auth()->check() && auth()->id() !== $comment->user_id && !auth()->user()->isAdmin())
                                                    <button 
                                                        onclick="event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'comment', targetId: {{ $comment->id }} } }));"
                                                        class="p-1.5 text-gray-500 hover:text-orange-400 hover:bg-gray-800 rounded transition-colors"
                                                        title="Report Comment">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-300 whitespace-pre-wrap">
                                            {{ $comment->content }}
                                        </p>

                                        <div class="mt-3" x-data="{ open: false }">
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <button
                                                    type="button"
                                                    wire:click="toggleCommentClap({{ $comment->id }})"
                                                    class="inline-flex items-center gap-1 {{ $hasClappedComment ? 'text-brand-violet' : 'hover:text-brand-violet' }} transition-colors">
                                                    <svg class="w-4 h-4" fill="{{ $hasClappedComment ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M7 11l2-2m0 0l2-2m-2 2l2 2m-2-2L7 9m6 2l2-2m0 0l2-2m-2 2l2 2m-2-2l-2 2M5 15a4 4 0 004 4h6a4 4 0 004-4v-1H5v1z" />
                                                    </svg>
                                                    <span>{{ $comment->claps->count() }}</span>
                                                </button>

                                                @auth
                                                    <button
                                                        type="button"
                                                        @click="open = !open"
                                                        class="inline-flex items-center gap-1 hover:text-blue-400 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V7a4 4 0 018 0v9"></path>
                                                        </svg>
                                                        <span>Reply</span>
                                                    </button>
                                                @endauth
                                            </div>

                                            @auth
                                                <div x-show="open" x-transition class="mt-4">
                                                    <form wire:submit.prevent="addReply({{ $comment->id }})" class="space-y-2">
                                                        <textarea
                                                            wire:model.defer="replyContent.{{ $comment->id }}"
                                                            rows="2"
                                                            class="w-full px-4 py-3 bg-black border border-white/5 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-transparent resize-none shadow-inner text-sm"
                                                            placeholder="Write a reply..."></textarea>
                                                        <div class="flex justify-end">
                                                            <button
                                                                type="submit"
                                                                class="px-4 py-2 bg-brand-purple hover:bg-brand-violet text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all shadow-lg">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endauth
                                        </div>

                                        @if($comment->replies && $comment->replies->count() > 0)
                                            <div class="mt-4 space-y-3 border-l border-white/5 pl-4">
                                                @foreach($comment->replies as $replyIndex => $reply)
                                                    @php
                                                        $hasClappedReply = auth()->check() && $reply->claps->contains('user_id', auth()->id());
                                                    @endphp
                                                    <div 
                                                        class="flex items-start gap-3 transition-all duration-300 transform hover:translate-x-1"
                                                        x-data="{ show: false }"
                                                        x-init="
                                                            setTimeout(() => {
                                                                show = true;
                                                            }, {{ ($index * 100) + ($replyIndex * 50) }});
                                                        "
                                                        x-show="show"
                                                        x-transition:enter="transition ease-out duration-400"
                                                        x-transition:enter-start="opacity-0 translate-x-2"
                                                        x-transition:enter-end="opacity-100 translate-x-0"
                                                    >
                                                        <div class="w-7 h-7 rounded-lg bg-brand-deep flex items-center justify-center text-[8px] font-black text-brand-violet ring-2 ring-white/5">
                                                            {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <p class="text-xs font-semibold text-white">
                                                                        {{ $reply->user->name ?? 'Unknown User' }}
                                                                    </p>
                                                                    <p class="text-[11px] text-gray-500">
                                                                        {{ $reply->created_at->diffForHumans() }}
                                                                    </p>
                                                                </div>
                                                                
                                                                {{-- Admin Delete Reply Button (Only visible to admins, not reply owners) --}}
                                                                @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $reply->user_id)
                                                                    <button 
                                                                        wire:click="deleteCommentAsAdmin({{ $reply->id }})"
                                                                        wire:confirm="Are you sure you want to delete this reply as admin?"
                                                                        class="p-1 text-rose-400 hover:text-red-500 dark:hover:bg-red-900/20 hover:bg-red-50 rounded transition-colors"
                                                                        title="Admin: Delete Reply">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <p class="mt-1 text-sm text-gray-300 whitespace-pre-wrap">
                                                                {{ $reply->content }}
                                                            </p>
                                                            <div class="mt-2 flex items-center gap-3 text-[11px] text-gray-500">
                                                                <button
                                                                    type="button"
                                                                    wire:click="toggleCommentClap({{ $reply->id }})"
                                                                    class="inline-flex items-center gap-1 {{ $hasClappedReply ? 'text-yellow-400' : 'hover:text-yellow-400' }} transition-colors">
                                                                    <svg class="w-3.5 h-3.5" fill="{{ $hasClappedReply ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M7 11l2-2m0 0l2-2m-2 2l2 2m-2-2L7 9m6 2l2-2m0 0l2-2m-2 2l2 2m-2-2l-2 2M5 15a4 4 0 004 4h6a4 4 0 004-4v-1H5v1z" />
                                                                    </svg>
                                                                    <span>{{ $reply->claps->count() }}</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <h3 class="text-xl font-medium dark:text-gray-400 text-gray-700 mb-2">Post not found</h3>
                <a href="{{ route('dashboard') }}" class="text-brand-violet hover:text-brand-purple">Go back to posts</a>
            </div>
        @endif
    </div>

    <!-- Bottom Navigation (Post detail page only) -->
    {{-- Bottom navigation is only shown on the main posts feed --}}
    <!-- Suspend Post Modal -->
    @if ($showSuspendModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-black/95 backdrop-blur-md" wire:click="closeSuspendModal"></div>

                <div class="inline-block align-bottom bg-black rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10" wire:click.stop>
                    <div class="px-6 py-4 bg-black/5 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Suspend Post</h3>
                        <button wire:click="closeSuspendModal" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="suspendPost" class="p-6 space-y-6">
                        <div class="space-y-2">
                            <label for="suspendReason" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Suspension Reason *</label>
                            <textarea
                                wire:model="suspendReason"
                                id="suspendReason"
                                rows="3"
                                class="w-full px-4 py-3 bg-brand-deep/30 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-transparent resize-none shadow-inner"
                                placeholder="Enter the reason for suspending this post..."></textarea>
                            @error('suspendReason')
                                <span class="text-rose-400 text-xs mt-1 ml-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="suspendExpiresAt" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Expires At (Optional)</label>
                            <input
                                type="datetime-local"
                                wire:model="suspendExpiresAt"
                                id="suspendExpiresAt"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full px-4 py-3 bg-brand-deep/30 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-brand-purple/50 focus:border-transparent cursor-pointer">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1 ml-1">Leave empty for permanent suspension</p>
                            @error('suspendExpiresAt')
                                <span class="text-rose-400 text-xs mt-1 ml-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-white/5">
                            <button 
                                type="button"
                                wire:click="closeSuspendModal"
                                class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">
                                Abort
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="suspendPost"
                                class="px-6 py-2 bg-brand-purple hover:bg-brand-violet text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-brand-purple/20">
                                <span wire:loading.remove wire:target="suspendPost">Suspend Post</span>
                                <span wire:loading wire:target="suspendPost">Suspending...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Likes and liker list removed; only stars and comments remain --}}
</div>
