<div class="min-h-screen bg-gray-950 text-white">
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
                <div class="flex items-center gap-6 pt-4 border-t border-gray-800">
                    <button class="flex items-center gap-2 text-gray-400 hover:text-red-400 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>{{ $post->likes->count() }}</span>
                    </button>
                    <button class="flex items-center gap-2 text-gray-400 hover:text-blue-400 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>{{ $post->comments->count() }}</span>
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <h3 class="text-xl font-medium text-gray-400 mb-2">Post not found</h3>
                <a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300">Go back to posts</a>
            </div>
        @endif
    </div>
</div>
