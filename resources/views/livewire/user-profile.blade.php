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
                <div class="flex-1 w-full">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-2xl font-bold text-white">{{ $user->username }}</h1>
                            </div>
                            
                            @if($user->profile && $user->profile->bio)
                                <p class="text-gray-300 mb-4 max-w-xl">{{ $user->profile->bio }}</p>
                            @endif

                            <!-- Stats -->
                            <div class="flex gap-6 mb-2">
                                <div>
                                    <span class="text-gray-400 text-xs uppercase tracking-wide">Posts</span>
                                    <p class="text-white font-semibold">{{ $postsCount }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-xs uppercase tracking-wide">Followers</span>
                                    <p class="text-white font-semibold">{{ $followersCount }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-xs uppercase tracking-wide">Following</span>
                                    <p class="text-white font-semibold">{{ $followingCount }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Follow/Unfollow Button for other users -->
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

                            <!-- Self profile actions -->
                            @if(Auth::check() && Auth::id() === $user->id)
                                <a 
                                    href="{{ route('profile.show') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-gray-700 bg-gray-800 hover:bg-gray-700 text-gray-100 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a3 3 0 014.243 4.243L9 19.95 4 21l1.05-5 10.182-10.768z" />
                                    </svg>
                                    <span>Edit Profile</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-red-700 bg-red-700/20 hover:bg-red-700/40 text-red-200 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l3 3m-3-3v12" />
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
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
