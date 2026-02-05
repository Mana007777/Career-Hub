<div class="min-h-screen bg-gray-950 text-white pb-24">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-lg text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Profile Header -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Profile Photo/Avatar -->
                <div class="w-24 h-24 rounded-full bg-gray-700 flex items-center justify-center text-3xl font-bold text-gray-300">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-white">{{ $user->username }}</h1>
                    </div>
                    
                    @if($user->profile && $user->profile->bio)
                        <p class="text-gray-300 mb-4">{{ $user->profile->bio }}</p>
                    @endif

                    <!-- Stats -->
                    <div class="flex gap-6 mb-4">
                        <div>
                            <span class="text-gray-400 text-sm">Posts</span>
                            <p class="text-white font-semibold">{{ $postsCount }}</p>
                        </div>
                        <div>
                            <span class="text-gray-400 text-sm">Followers</span>
                            <p class="text-white font-semibold">{{ $followersCount }}</p>
                        </div>
                        <div>
                            <span class="text-gray-400 text-sm">Following</span>
                            <p class="text-white font-semibold">{{ $followingCount }}</p>
                        </div>
                    </div>

                    <!-- Follow/Unfollow Button -->
                    @if(Auth::check() && Auth::id() !== $user->id)
                        <button 
                            wire:click="toggleFollow"
                            class="px-6 py-2 rounded-lg font-medium transition-colors
                                @if($isFollowing)
                                    bg-gray-800 hover:bg-gray-700 text-white border border-gray-700
                                @else
                                    bg-blue-600 hover:bg-blue-700 text-white
                                @endif">
                            @if($isFollowing)
                                Unfollow
                            @else
                                Follow
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Posts -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-white mb-4">Posts</h2>
            
            <div class="space-y-6">
                @forelse ($posts as $post)
                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 hover:border-gray-700 transition-colors">
                        <!-- Post Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-300 font-semibold">
                                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-white">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Post Title & Content -->
                        <div class="mb-4">
                            @if(!empty($post->title))
                                <h3 class="text-lg font-semibold text-white mb-1">{{ $post->title }}</h3>
                            @endif
                            <p class="text-gray-200 leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
                        </div>

                        <!-- Post Media -->
                        @if ($post->media)
                            <div class="mb-4 rounded-lg overflow-hidden">
                                @php
                                    $mediaUrl = $this->getMediaUrl($post);
                                    $isImage = in_array(strtolower(pathinfo($post->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                
                                @if($isImage)
                                    <img src="{{ $mediaUrl }}" alt="Post media" class="w-full h-auto rounded-lg">
                                @else
                                    <video src="{{ $mediaUrl }}" controls class="w-full rounded-lg">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @endif

                        <!-- Post Specialties -->
                        @if($post->specialties && $post->specialties->count() > 0)
                            <div class="mb-2 pt-4 border-t border-gray-800">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->specialties as $specialty)
                                        @php
                                            $subSpecialty = $specialty->subSpecialties->firstWhere('id', $specialty->pivot->sub_specialty_id);
                                        @endphp
                                        @if($subSpecialty)
                                            <span class="px-3 py-1 bg-blue-600/20 border border-blue-600/50 rounded-lg text-blue-300 text-xs">
                                                {{ $specialty->name }} - {{ $subSpecialty->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Post Tags -->
                        @if($post->tags && $post->tags->count() > 0)
                            <div class="mb-4 @if(!$post->specialties || $post->specialties->count() === 0) pt-4 border-t border-gray-800 @endif">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                        <span class="px-3 py-1 bg-purple-600/20 border border-purple-600/50 rounded-lg text-purple-300 text-xs">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Post Stats -->
                        <div class="flex items-center gap-6 pt-4 border-t border-gray-800">
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>{{ $post->likes->count() }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>{{ $post->comments->count() }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-400 hover:text-blue-300 text-sm">
                                View Post
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-12 text-center">
                        <p class="text-gray-400">No posts yet.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
