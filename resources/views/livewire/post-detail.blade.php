<div class="min-h-screen bg-gray-950 text-white pb-24">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a 
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Posts</span>
            </a>
        </div>

        @if($post)
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
                <!-- Post Header -->
                <div class="flex items-start justify-between mb-4">
                    <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-300 font-semibold">
                                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">{{ $post->user->name ?? 'Unknown User' }}</h3>
                            <p class="text-sm text-gray-400">{{ $post->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                    </a>
                    
                    @if ($post->user_id === auth()->id())
                        <div class="flex items-center gap-2">
                            <a 
                                href="{{ route('dashboard') }}?edit={{ $post->id }}"
                                class="p-2 text-gray-400 hover:text-blue-400 hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Post Title & Content -->
                <div class="mb-4">
                    @if(!empty($post->title))
                        <h1 class="text-2xl font-bold text-white mb-2">{{ $post->title }}</h1>
                    @endif
                    <p class="text-gray-200 leading-relaxed whitespace-pre-wrap text-lg">{{ $post->content }}</p>
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

                <!-- Post Stats -->
                @php
                    $hasLikedPost = auth()->check() && $post->likes->contains('user_id', auth()->id());
                @endphp
                <div class="flex items-center gap-6 pt-4 border-t border-gray-800">
                    <button
                        type="button"
                        wire:click="togglePostLike"
                        class="flex items-center gap-2 text-sm {{ $hasLikedPost ? 'text-red-400' : 'text-gray-400 hover:text-red-400' }} transition-colors">
                        <svg class="w-5 h-5" fill="{{ $hasLikedPost ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>{{ $post->likes->count() }}</span>
                    </button>

                    <button
                        type="button"
                        wire:click="toggleLikersModal"
                        class="text-xs text-gray-400 hover:text-blue-400 underline-offset-2 hover:underline">
                        See who liked ({{ $post->likes->count() }})
                    </button>

                    <div class="flex items-center gap-2 text-gray-400 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>{{ $post->comments->count() }} comments</span>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mt-8 pt-6 border-t border-gray-800">
                    <h2 class="text-lg font-semibold text-white mb-4">Comments</h2>

                    @auth
                        <form wire:submit.prevent="addComment" class="mb-6">
                            <div class="flex flex-col gap-3">
                                <textarea
                                    wire:model.defer="content"
                                    rows="3"
                                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    placeholder="Write a comment..."></textarea>
                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-sm text-gray-400 mb-4">
                            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Log in</a> to comment.
                        </p>
                    @endauth

                    <div class="space-y-4">
                        @php
                            $rootComments = $post->comments->whereNull('parent_id');
                        @endphp

                        @forelse($rootComments as $comment)
                            @php
                                $hasLikedComment = auth()->check() && $comment->likes->contains('user_id', auth()->id());
                            @endphp
                            <div class="bg-gray-900/60 border border-gray-800 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-semibold text-gray-300">
                                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-white">
                                                    {{ $comment->user->name ?? 'Unknown User' }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-200 whitespace-pre-wrap">
                                            {{ $comment->content }}
                                        </p>

                                        <div class="mt-3" x-data="{ open: false }">
                                            <div class="flex items-center gap-4 text-xs text-gray-400">
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
                                                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent resize-none"
                                                            placeholder="Write a reply..."></textarea>
                                                        <div class="flex justify-end">
                                                            <button
                                                                type="submit"
                                                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                                Reply
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endauth
                                        </div>

                                        @if($comment->replies && $comment->replies->count() > 0)
                                            <div class="mt-4 space-y-3 border-l border-gray-800 pl-4">
                                                @foreach($comment->replies as $reply)
                                                    @php
                                                        $hasLikedReply = auth()->check() && $reply->likes->contains('user_id', auth()->id());
                                                    @endphp
                                                    <div class="flex items-start gap-3">
                                                        <div class="w-7 h-7 rounded-full bg-gray-700 flex items-center justify-center text-[10px] font-semibold text-gray-300">
                                                            {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <p class="text-xs font-semibold text-white">
                                                                        {{ $reply->user->name ?? 'Unknown User' }}
                                                                    </p>
                                                                    <p class="text-[11px] text-gray-400">
                                                                        {{ $reply->created_at->diffForHumans() }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <p class="mt-1 text-sm text-gray-200 whitespace-pre-wrap">
                                                                {{ $reply->content }}
                                                            </p>
                                                            <div class="mt-2 flex items-center gap-3 text-[11px] text-gray-400">
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
                            <p class="text-sm text-gray-400">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <h3 class="text-xl font-medium text-gray-400 mb-2">Post not found</h3>
                <a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300">Go back to posts</a>
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

@if($post && $showLikersModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60">
        <div class="bg-gray-900 border border-gray-800 rounded-xl max-w-sm w-full mx-4 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white">People who liked this post</h3>
                <button
                    type="button"
                    wire:click="toggleLikersModal"
                    class="text-gray-400 hover:text:white">
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
                            <p class="text-sm text:white">{{ $user->name ?? 'Unknown User' }}</p>
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
