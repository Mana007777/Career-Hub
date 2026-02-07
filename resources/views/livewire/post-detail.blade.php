<div class="min-h-screen dark:bg-black bg-white dark:text-white text-gray-900 pb-24" style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
    <div class="max-w-4xl mx-auto px-4 py-8">
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
                class="inline-flex items-center gap-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-all duration-300 transform hover:translate-x-1 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Posts</span>
            </a>
        </div>

        @if($post)
            <div 
                class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-6 shadow-2xl"
                x-show="loaded"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            >
                <!-- Post Header -->
                <div class="flex items-start justify-between mb-4">
                    <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <div class="w-12 h-12 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center">
                            <span class="dark:text-gray-300 text-gray-700 font-semibold">
                                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold dark:text-white text-gray-900">{{ $post->user->name ?? 'Unknown User' }}</h3>
                            <p class="text-sm dark:text-gray-400 text-gray-600">{{ $post->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                    </a>
                    
                    <div class="flex items-center gap-2">
                        @if ($post->user_id === auth()->id())
                            <a 
                                href="{{ route('dashboard') }}?edit={{ $post->id }}"
                                class="p-2 dark:text-gray-400 text-gray-600 hover:text-blue-400 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        @endif
                        
                        {{-- Admin Delete Post Button (Only visible to admins, not post owners) --}}
                        @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $post->user_id)
                            <button 
                                wire:click="deletePostAsAdmin({{ $post->id }})"
                                wire:confirm="Are you sure you want to delete this post as admin? This action cannot be undone."
                                class="p-2 dark:text-red-400 text-red-600 hover:text-red-500 dark:hover:bg-red-900/20 hover:bg-red-50 rounded-lg transition-colors"
                                title="Admin: Delete Post">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Post Title & Content -->
                <div class="mb-4">
                    @if(!empty($post->title))
                        <h1 class="text-2xl font-bold dark:text-white text-gray-900 mb-2">{{ $post->title }}</h1>
                    @endif
                    @if($post->job_type)
                        <div class="mb-3">
                            <span class="inline-flex items-center px-3 py-1 dark:bg-blue-600/20 bg-blue-100 dark:text-blue-300 text-blue-700 text-sm font-medium rounded-lg dark:border-blue-600/50 border-blue-300">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $post->job_type)) }}
                            </span>
                        </div>
                    @endif
                    <p class="dark:text-gray-200 text-gray-700 leading-relaxed whitespace-pre-wrap text-lg">{{ $post->content }}</p>
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
                                    $subSpecialty = $subSpecialtyId ? \App\Models\SubSpecialty::find($subSpecialtyId) : null;
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
                    $hasLikedPost = auth()->check() && $post->likes->contains('user_id', auth()->id());
                    $hasStarredPost = auth()->check() && $post->stars->contains('user_id', auth()->id());
                @endphp
                <div class="flex items-center gap-6 pt-4 border-t dark:border-gray-800 border-gray-200">
                    <button
                        type="button"
                        wire:click="togglePostLike"
                        class="flex items-center gap-2 text-sm {{ $hasLikedPost ? 'text-red-400' : 'dark:text-gray-400 text-gray-600 hover:text-red-400' }} transition-colors">
                        <svg class="w-5 h-5" fill="{{ $hasLikedPost ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>{{ $post->likes->count() }}</span>
                    </button>
                    <button
                        type="button"
                        wire:click="togglePostStar"
                        class="flex items-center gap-2 text-sm {{ $hasStarredPost ? 'text-yellow-400' : 'dark:text-gray-400 text-gray-600 hover:text-yellow-400' }} transition-colors">
                        <svg class="w-5 h-5" fill="{{ $hasStarredPost ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <span>{{ $post->stars->count() }}</span>
                    </button>

                    <button
                        type="button"
                        wire:click="toggleLikersModal"
                        class="text-xs dark:text-gray-400 text-gray-600 hover:text-blue-400 underline-offset-2 hover:underline">
                        See who liked ({{ $post->likes->count() }})
                    </button>

                    <div class="flex items-center gap-2 dark:text-gray-400 text-gray-600 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>{{ $post->comments->count() }} comments</span>
                    </div>
                </div>

                <!-- CV Upload Section (only show if user is not the post owner and post has job_type) -->
                @auth
                    @if($post->user_id !== auth()->id() && $post->job_type)
                        <div class="mt-8 pt-6 border-t dark:border-gray-800 border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold dark:text-white text-gray-900">Apply for this Job</h2>
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
                                <form wire:submit.prevent="uploadCv" class="dark:bg-gray-900/60 bg-gray-50 border dark:border-gray-800 border-gray-200 rounded-lg p-4">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="cvFile" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">CV File *</label>
                                            <input
                                                type="file"
                                                wire:model="cvFile"
                                                id="cvFile"
                                                accept=".pdf,.doc,.docx"
                                                class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            @error('cvFile')
                                                <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                                            @enderror
                                            @if($cvFile)
                                                <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Selected: {{ $cvFile->getClientOriginalName() }}</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label for="cvMessage" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Message (Optional)</label>
                                            <textarea
                                                wire:model="cvMessage"
                                                id="cvMessage"
                                                rows="3"
                                                class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
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
                                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50">
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
                <div class="mt-8 pt-6 border-t dark:border-gray-800 border-gray-200">
                    <h2 class="text-lg font-semibold dark:text-white text-gray-900 mb-4">Comments</h2>

                    @auth
                        <form wire:submit.prevent="addComment" class="mb-6">
                            <div class="flex flex-col gap-3">
                                <textarea
                                    wire:model.defer="content"
                                    rows="3"
                                    class="w-full px-4 py-3 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    placeholder="Write a comment..."></textarea>
                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        class="px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-sm dark:text-gray-400 text-gray-600 mb-4">
                            <a href="{{ route('login') }}" class="dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700">Log in</a> to comment.
                        </p>
                    @endauth

                    <div class="space-y-4">
                        @php
                            $rootComments = $post->comments->whereNull('parent_id');
                        @endphp

                        @forelse($rootComments as $index => $comment)
                            @php
                                $hasLikedComment = auth()->check() && $comment->likes->contains('user_id', auth()->id());
                            @endphp
                            <div 
                                class="dark:bg-gray-900/60 bg-gray-50 border dark:border-gray-800 border-gray-200 rounded-lg p-4 dark:hover:border-gray-700 hover:border-gray-300 transition-all duration-300 transform hover:scale-[1.01]"
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
                                    <div class="w-8 h-8 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center text-xs font-semibold dark:text-gray-300 text-gray-700">
                                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-semibold dark:text-white text-gray-900">
                                                    {{ $comment->user->name ?? 'Unknown User' }}
                                                </p>
                                                <p class="text-xs dark:text-gray-400 text-gray-600">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            
                                            {{-- Admin Delete Comment Button (Only visible to admins, not comment owners) --}}
                                            @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $comment->user_id)
                                                <button 
                                                    wire:click="deleteCommentAsAdmin({{ $comment->id }})"
                                                    wire:confirm="Are you sure you want to delete this comment as admin?"
                                                    class="p-1.5 dark:text-red-400 text-red-600 hover:text-red-500 dark:hover:bg-red-900/20 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Admin: Delete Comment">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                        <p class="mt-2 text-sm dark:text-gray-200 text-gray-700 whitespace-pre-wrap">
                                            {{ $comment->content }}
                                        </p>

                                        <div class="mt-3" x-data="{ open: false }">
                                            <div class="flex items-center gap-4 text-xs dark:text-gray-400 text-gray-600">
                                                <button
                                                    type="button"
                                                    wire:click="toggleCommentLike({{ $comment->id }})"
                                                    class="inline-flex items-center gap-1 {{ $hasLikedComment ? 'text-red-400' : 'hover:text-red-400' }} transition-colors">
                                                    <svg class="w-4 h-4" fill="{{ $hasLikedComment ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    <span>{{ $comment->likes->count() }}</span>
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
                                                <div x-show="open" x-transition class="mt-3">
                                                    <form wire:submit.prevent="addReply({{ $comment->id }})" class="space-y-2">
                                                        <textarea
                                                            wire:model.defer="replyContent.{{ $comment->id }}"
                                                            rows="2"
                                                            class="w-full px-3 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent resize-none"
                                                            placeholder="Write a reply..."></textarea>
                                                        <div class="flex justify-end">
                                                            <button
                                                                type="submit"
                                                                class="px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white text-xs font-medium rounded-lg transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg dark:hover:shadow-blue-500/50 hover:shadow-gray-800/50">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endauth
                                        </div>

                                        @if($comment->replies && $comment->replies->count() > 0)
                                            <div class="mt-4 space-y-3 border-l dark:border-gray-800 border-gray-200 pl-4">
                                                @foreach($comment->replies as $replyIndex => $reply)
                                                    @php
                                                        $hasLikedReply = auth()->check() && $reply->likes->contains('user_id', auth()->id());
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
                                                        <div class="w-7 h-7 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center text-[10px] font-semibold dark:text-gray-300 text-gray-700">
                                                            {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <p class="text-xs font-semibold dark:text-white text-gray-900">
                                                                        {{ $reply->user->name ?? 'Unknown User' }}
                                                                    </p>
                                                                    <p class="text-[11px] dark:text-gray-400 text-gray-600">
                                                                        {{ $reply->created_at->diffForHumans() }}
                                                                    </p>
                                                                </div>
                                                                
                                                                {{-- Admin Delete Reply Button (Only visible to admins, not reply owners) --}}
                                                                @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $reply->user_id)
                                                                    <button 
                                                                        wire:click="deleteCommentAsAdmin({{ $reply->id }})"
                                                                        wire:confirm="Are you sure you want to delete this reply as admin?"
                                                                        class="p-1 dark:text-red-400 text-red-600 hover:text-red-500 dark:hover:bg-red-900/20 hover:bg-red-50 rounded transition-colors"
                                                                        title="Admin: Delete Reply">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            <p class="mt-1 text-sm dark:text-gray-200 text-gray-700 whitespace-pre-wrap">
                                                                {{ $reply->content }}
                                                            </p>
                                                            <div class="mt-2 flex items-center gap-3 text-[11px] dark:text-gray-400 text-gray-600">
                                                                <button
                                                                    type="button"
                                                                    wire:click="toggleCommentLike({{ $reply->id }})"
                                                                    class="inline-flex items-center gap-1 {{ $hasLikedReply ? 'text-red-400' : 'hover:text-red-400' }} transition-colors">
                                                                    <svg class="w-3.5 h-3.5" fill="{{ $hasLikedReply ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                                    </svg>
                                                                    <span>{{ $reply->likes->count() }}</span>
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
                            <p class="text-sm dark:text-gray-400 text-gray-600">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <h3 class="text-xl font-medium dark:text-gray-400 text-gray-700 mb-2">Post not found</h3>
                <a href="{{ route('dashboard') }}" class="dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700">Go back to posts</a>
            </div>
        @endif
    </div>

    <!-- Bottom Navigation -->
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
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-full"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-full"
        class="fixed bottom-0 z-50 max-w-md w-full -translate-x-1/2 dark:bg-gray-600/60 bg-white backdrop-blur-sm rounded-2xl left-1/2 shadow-lg mb-2 mx-auto px-4 py-2 border dark:border-gray-700 border-gray-200"
    >
        <div class="grid h-full max-w-md grid-cols-8 mx-auto">
            <a href="{{ route('dashboard') }}" data-tooltip-target="tooltip-home"
                class="inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
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
                class="relative inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
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
                class="inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
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
                class="inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
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
                if (auth()->check()) {
                    $chatService = app(\App\Services\ChatService::class);
                    $totalUnreadMessages = $chatService->getTotalUnreadCount(auth()->id());
                } else {
                    $totalUnreadMessages = 0;
                }
            @endphp
            <button 
                onclick="
                    window.location.href = '{{ route('dashboard') }}';
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('openChatList'));
                    }, 100);
                "
                data-tooltip-target="tooltip-chat" 
                type="button"
                class="relative inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
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
            class="inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors"
        >
            <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
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
            href="{{ route('settings') }}"
            data-tooltip-target="tooltip-settings"
                class="inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors">
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="sr-only">Settings</span>
            </a>
            <div id="tooltip-settings" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Settings
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a 
                href="{{ auth()->check() ? route('user.profile', auth()->user()->username ?? 'unknown') : route('profile.show') }}"
                data-tooltip-target="tooltip-profile"
                class="inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors"
            >
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-blue-400" aria-hidden="true"
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

@if($post && $showLikersModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
        <div class="bg-gray-900 border border-gray-800 rounded-xl max-w-sm w-full mx-4 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white">People who liked this post</h3>
                <button
                    type="button"
                    wire:click="toggleLikersModal"
                    class="text-gray-400 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="max-h-64 overflow-y-auto space-y-2">
                @forelse($post->likedBy as $user)
                    <div class="flex items-center gap-3 px-2 py-1 rounded-lg hover:bg-gray-800/80">
                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-semibold text-gray-300">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm text-white">{{ $user->name ?? 'Unknown User' }}</p>
                            @if(!empty($user->username))
                                <p class="text-xs text-gray-400">@{{ $user->username }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No likes yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endif
