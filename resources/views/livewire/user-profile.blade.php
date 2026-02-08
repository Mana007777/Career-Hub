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
            <button 
                onclick="window.history.back()"
                class="inline-flex items-center gap-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-all duration-300 transform hover:translate-x-1 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 dark:bg-green-900/50 bg-green-50 border dark:border-green-700 border-green-200 rounded-lg dark:text-green-200 text-green-800 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 dark:bg-red-900/50 bg-red-50 border dark:border-red-700 border-red-200 rounded-lg dark:text-red-200 text-red-800 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Profile Header -->
        <div 
            class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl p-6 mb-6 shadow-2xl"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        >
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Profile Photo/Avatar -->
                <div class="w-24 h-24 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center text-3xl font-bold dark:text-gray-300 text-gray-700">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>

                <!-- User Info -->
                <div class="flex-1 w-full">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-2xl font-bold dark:text-white text-gray-900">{{ $user->username }}</h1>
                                @if($user->role)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $user->role === 'seeker' ? 'dark:bg-blue-600/20 bg-blue-100 dark:text-blue-300 text-blue-700 dark:border-blue-600/50 border-blue-300' : 'dark:bg-purple-600/20 bg-purple-100 dark:text-purple-300 text-purple-700 dark:border-purple-600/50 border-purple-300' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @endif
                                @if($user->suspension)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full dark:bg-red-600/20 bg-red-100 dark:text-red-400 text-red-700 dark:border-red-600/50 border-red-300 border" title="Suspended">
                                        Suspended
                                    </span>
                                @endif
                            </div>
                            
                            @if($user->profile && $user->profile->bio)
                                <p class="dark:text-gray-300 text-gray-700 mb-4 max-w-xl">{{ $user->profile->bio }}</p>
                            @endif

                            <!-- Additional Info: Location and Website -->
                            <div class="flex flex-wrap items-center gap-4 mb-4 text-sm dark:text-gray-400 text-gray-600">
                                @if($user->profile && $user->profile->location)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>{{ $user->profile->location }}</span>
                                    </div>
                                @endif
                                @if($user->profile && $user->profile->website)
                                    @php
                                        $websiteUrl = $user->profile->website;
                                        if (!preg_match('/^https?:\/\//', $websiteUrl)) {
                                            $websiteUrl = 'https://' . $websiteUrl;
                                        }
                                        $websiteDisplay = parse_url($websiteUrl, PHP_URL_HOST) ?: $user->profile->website;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer" class="dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700 hover:underline">
                                            {{ $websiteDisplay }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Stats -->
                            <div class="flex gap-6 mb-2">
                                <div>
                                    <span class="dark:text-gray-400 text-gray-600 text-xs uppercase tracking-wide">Posts</span>
                                    <p class="dark:text-white text-gray-900 font-semibold">{{ $postsCount }}</p>
                                </div>
                                <button 
                                    type="button"
                                    wire:click="openFollowersModal"
                                    class="text-left hover:opacity-80 transition-opacity">
                                    <span class="dark:text-gray-400 text-gray-600 text-xs uppercase tracking-wide">Followers</span>
                                    <p class="dark:text-white text-gray-900 font-semibold cursor-pointer hover:text-blue-400 transition-colors">{{ $followersCount }}</p>
                                </button>
                                <button 
                                    type="button"
                                    wire:click="openFollowingModal"
                                    class="text-left hover:opacity-80 transition-opacity">
                                    <span class="dark:text-gray-400 text-gray-600 text-xs uppercase tracking-wide">Following</span>
                                    <p class="dark:text-white text-gray-900 font-semibold cursor-pointer hover:text-blue-400 transition-colors">{{ $followingCount }}</p>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Follow/Unfollow and Block/Unblock Buttons for other users -->
                            @if(Auth::check() && Auth::id() !== $user->id)
                                @if($isBlocked)
                                    <!-- Show blocked message when current user has blocked this user -->
                                    <div class="px-6 py-3 rounded-lg dark:bg-red-900/30 bg-red-50 dark:border-red-700/50 border-red-200 dark:text-red-200 text-red-800 font-medium">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            <span class="font-medium">You have blocked this user</span>
                                        </div>
                                    </div>
                                    <button 
                                        wire:click="toggleBlock"
                                        class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white border dark:border-gray-700 border-gray-700">
                                        Unblock
                                    </button>
                                @else
                                    <button 
                                        wire:click="toggleFollow"
                                        class="px-6 py-2 rounded-lg font-medium transition-colors
                                            @if($isFollowing)
                                                dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white border dark:border-gray-700 border-gray-700
                                            @else
                                                dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white
                                            @endif">
                                        @if($isFollowing)
                                            Unfollow
                                        @else
                                            Follow
                                        @endif
                                    </button>
                                    <button 
                                        wire:click="toggleBlock"
                                        wire:confirm="Are you sure you want to block this user? You won't be able to see their posts or profile."
                                        class="px-6 py-2 rounded-lg font-medium transition-colors bg-red-600 hover:bg-red-700 text-white">
                                        Block
                                    </button>
                                @endif
                            @endif

                            <!-- Self profile actions -->
                            @if(Auth::check() && Auth::id() === $user->id)
                                <a 
                                    href="{{ route('profile.show') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border dark:border-gray-700 border-gray-300 dark:bg-gray-800 bg-gray-200 dark:hover:bg-gray-700 hover:bg-gray-300 dark:text-gray-100 text-gray-900 transition-colors"
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
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg dark:border-red-700 border-red-300 dark:bg-red-700/20 bg-red-100 dark:hover:bg-red-700/40 hover:bg-red-200 dark:text-red-200 text-red-700 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l3 3m-3-3v12" />
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            @endif
                            
                            {{-- Admin Actions (Only visible to admins, cannot delete themselves) --}}
                            @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $user->id)
                                @php
                                    $user->loadMissing('suspension');
                                @endphp
                                <div class="relative" x-data="{ open: false }">
                                    <button 
                                        type="button"
                                        @click="open = !open"
                                        class="p-2 dark:text-gray-400 text-gray-600 hover:text-gray-900 dark:hover:text-white dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                        title="Admin Actions">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="open"
                                        @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-48 dark:bg-gray-800 bg-white border dark:border-gray-700 border-gray-200 rounded-lg shadow-lg z-50"
                                        style="display: none;">
                                        @if($user->suspension)
                                            <button 
                                                wire:click="openUnsuspendUserModal"
                                                class="w-full text-left px-4 py-2 text-sm dark:text-green-400 text-green-600 hover:dark:bg-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                                @click="open = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                                </svg>
                                                Unsuspend User
                                            </button>
                                        @else
                                            <button 
                                                wire:click="openSuspendUserModal"
                                                class="w-full text-left px-4 py-2 text-sm dark:text-yellow-400 text-yellow-600 hover:dark:bg-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                                @click="open = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Suspend User
                                            </button>
                                        @endif
                                        <button 
                                            wire:click="openDeleteUserModal"
                                            class="w-full text-left px-4 py-2 text-sm dark:text-red-400 text-red-600 hover:dark:bg-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                            @click="open = false">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove User
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            {{-- Report User Button (Visible to all users except themselves and admins) --}}
                            @if(Auth::check() && Auth::id() !== $user->id && !Auth::user()->isAdmin())
                                <button 
                                    onclick="event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'user', targetId: {{ $user->id }} } }));"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg dark:bg-orange-600/20 bg-orange-100 dark:text-orange-400 text-orange-700 dark:hover:bg-orange-600/30 hover:bg-orange-200 dark:border-orange-600/50 border-orange-300 border transition-colors"
                                    title="Report User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span>Report</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Posts -->
        @if($isBlocked)
            <div class="mb-6 p-8 bg-gray-900 border border-gray-800 rounded-xl text-center">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-400 mb-2">You have blocked this user</h3>
                <p class="text-sm text-gray-500">You cannot see this user's posts or interact with them.</p>
            </div>
        @else
            <div class="mb-6">
                <h2 class="text-xl font-bold dark:text-white text-gray-900 mb-4">Posts</h2>
                
                <div class="space-y-6">
                    @forelse ($posts as $index => $post)
                    <div 
                        class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-6 dark:hover:border-gray-700 hover:border-gray-300 hover:shadow-lg transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1"
                        x-data="{ show: false }"
                        x-init="
                            setTimeout(() => {
                                show = true;
                            }, {{ $index * 100 }});
                        "
                        x-show="show"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    >
                        <!-- Post Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center">
                                    <span class="dark:text-gray-300 text-gray-700 font-semibold">
                                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold dark:text-white text-gray-900">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-sm dark:text-gray-400 text-gray-600">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Post Title & Content -->
                        <div class="mb-4">
                            @if(!empty($post->title))
                                <h3 class="text-lg font-semibold dark:text-white text-gray-900 mb-1">{{ $post->title }}</h3>
                            @endif
                            <p class="dark:text-gray-200 text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
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
                            <div class="mb-2 pt-4 border-t dark:border-gray-800 border-gray-200">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->specialties as $specialty)
                                        @php
                                            $subSpecialty = $specialty->subSpecialties->firstWhere('id', $specialty->pivot->sub_specialty_id);
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
                            <div class="mb-4 @if(!$post->specialties || $post->specialties->count() === 0) pt-4 border-t border-gray-800 @endif">
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
                        <div class="flex items-center gap-6 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <div class="flex items-center gap-2 dark:text-gray-400 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>{{ $post->likes->count() }}</span>
                            </div>
                            <div class="flex items-center gap-2 dark:text-gray-400 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <span>{{ $post->stars->count() }}</span>
                            </div>
                            <div class="flex items-center gap-2 dark:text-gray-400 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>{{ $post->comments->count() }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700 text-sm">
                                View Post
                            </a>
                        </div>
                    </div>
                    @empty
                        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-12 text-center">
                            <p class="dark:text-gray-400 text-gray-600">No posts yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts && $posts->hasPages())
                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                @endif
    </div>
@endif

<!-- Report Modal -->
@livewire('report-modal')

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
        @if(auth()->check() && auth()->user()->isAdmin())
            {{-- Reports Icon (Admin Only) --}}
            <a 
                href="{{ route('reports') }}"
                data-tooltip-target="tooltip-reports"
                class="relative inline-flex flex-col items-center justify-center p-2 dark:hover:bg-gray-700/80 hover:bg-gray-200 group rounded-lg transition-colors"
            >
                @php
                    $pendingReportsCount = \App\Models\Report::where('status', 'pending')->count();
                @endphp
                <svg class="w-6 h-6 mb-1 dark:text-gray-200 text-gray-700 group-hover:text-orange-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                @if($pendingReportsCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-red-500 text-white border border-gray-900">
                        {{ $pendingReportsCount > 99 ? '99+' : $pendingReportsCount }}
                    </span>
                @endif
                <span class="sr-only">Reports</span>
            </a>
            <div id="tooltip-reports" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-dark rounded-base shadow-xs opacity-0 tooltip">
                Reports
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        @else
            {{-- CVs Icon (Regular Users) --}}
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
        @endif
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

<!-- Followers Modal -->
@if($showFollowersModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center dark:bg-black/60 bg-black/60 backdrop-blur-sm" wire:click="closeFollowersModal">
        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl max-w-md w-full mx-4 max-h-[80vh] overflow-hidden flex flex-col" wire:click.stop>
            <div class="flex items-center justify-between p-5 border-b dark:border-gray-800 border-gray-200">
                <h3 class="text-lg font-semibold dark:text-white text-gray-900">Followers</h3>
                <button
                    type="button"
                    wire:click="closeFollowersModal"
                    class="dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-4">
                @if($user->followers && $user->followers->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->followers as $follower)
                            <a 
                                href="{{ route('user.profile', $follower->username ?? 'unknown') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg dark:hover:bg-gray-800/80 hover:bg-gray-100 transition-colors group">
                                <div class="w-12 h-12 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center text-sm font-semibold dark:text-gray-300 text-gray-700 flex-shrink-0">
                                    {{ strtoupper(substr($follower->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold dark:text-white text-gray-900 group-hover:text-blue-400 transition-colors truncate">
                                        {{ $follower->name ?? 'Unknown User' }}
                                    </p>
                                    @if(!empty($follower->username))
                                        <p class="text-xs dark:text-gray-400 text-gray-600 truncate">
                                            {{ '@' . $follower->username }}
                                        </p>
                                    @endif
                                    @if($follower->profile && !empty($follower->profile->bio))
                                        <p class="text-xs dark:text-gray-500 text-gray-500 truncate mt-0.5">
                                            {{ Str::limit($follower->profile->bio, 50) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-sm dark:text-gray-400 text-gray-600">No followers yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Following Modal -->
@if($showFollowingModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center dark:bg-black/60 bg-black/60 backdrop-blur-sm" wire:click="closeFollowingModal">
        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl max-w-md w-full mx-4 max-h-[80vh] overflow-hidden flex flex-col" wire:click.stop>
            <div class="flex items-center justify-between p-5 border-b dark:border-gray-800 border-gray-200">
                <h3 class="text-lg font-semibold dark:text-white text-gray-900">Following</h3>
                <button
                    type="button"
                    wire:click="closeFollowingModal"
                    class="dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-4">
                @if($user->following && $user->following->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->following as $followedUser)
                            <a 
                                href="{{ route('user.profile', $followedUser->username ?? 'unknown') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg dark:hover:bg-gray-800/80 hover:bg-gray-100 transition-colors group">
                                <div class="w-12 h-12 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center text-sm font-semibold dark:text-gray-300 text-gray-700 flex-shrink-0">
                                    {{ strtoupper(substr($followedUser->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold dark:text-white text-gray-900 group-hover:text-blue-400 transition-colors truncate">
                                        {{ $followedUser->name ?? 'Unknown User' }}
                                    </p>
                                    @if(!empty($followedUser->username))
                                        <p class="text-xs dark:text-gray-400 text-gray-600 truncate">
                                            {{ '@' . $followedUser->username }}
                                        </p>
                                    @endif
                                    @if($followedUser->profile && !empty($followedUser->profile->bio))
                                        <p class="text-xs dark:text-gray-500 text-gray-500 truncate mt-0.5">
                                            {{ Str::limit($followedUser->profile->bio, 50) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-sm dark:text-gray-400 text-gray-600">Not following anyone yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Admin Actions Modal -->
@if ($showAdminActionsModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="closeAdminActionsModal"></div>

            <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200">
                    <h3 class="text-lg font-semibold dark:text-white text-gray-900">
                        @if($adminActionType === 'suspend')
                            Suspend User
                        @elseif($adminActionType === 'unsuspend')
                            Unsuspend User
                        @elseif($adminActionType === 'delete')
                            Remove User
                        @endif
                    </h3>
                </div>
                
                @if($adminActionType === 'suspend')
                    <form wire:submit.prevent="suspendUser" class="dark:bg-gray-900 bg-white px-6 py-4">
                        <div class="mb-4">
                            <label for="suspendReason" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Suspension Reason *</label>
                            <textarea
                                wire:model="suspendReason"
                                id="suspendReason"
                                rows="3"
                                class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent resize-none"
                                placeholder="Enter the reason for suspending this user..."></textarea>
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
                                class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Leave empty for permanent suspension</p>
                            @error('suspendExpiresAt')
                                <span class="dark:text-red-400 text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeAdminActionsModal"
                                class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="suspendUser"
                                class="px-4 py-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:text-white bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="suspendUser">Suspend User</span>
                                <span wire:loading wire:target="suspendUser">Suspending...</span>
                            </button>
                        </div>
                    </form>
                @elseif($adminActionType === 'unsuspend')
                    <div class="dark:bg-gray-900 bg-white px-6 py-4">
                        <p class="mb-4 dark:text-gray-300 text-gray-700">Are you sure you want to unsuspend this user? They will regain access to their account.</p>
                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeAdminActionsModal"
                                class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="button"
                                wire:click="unsuspendUser"
                                wire:loading.attr="disabled"
                                wire:target="unsuspendUser"
                                class="px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:text-white bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="unsuspendUser">Unsuspend User</span>
                                <span wire:loading wire:target="unsuspendUser">Unsuspending...</span>
                            </button>
                        </div>
                    </div>
                @elseif($adminActionType === 'delete')
                    <div class="dark:bg-gray-900 bg-white px-6 py-4">
                        <p class="mb-4 dark:text-red-400 text-red-600 font-semibold">Warning: This action cannot be undone!</p>
                        <p class="mb-4 dark:text-gray-300 text-gray-700">Are you sure you want to permanently remove this user and all their data?</p>
                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-800 border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeAdminActionsModal"
                                class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="button"
                                wire:click="deleteUserAsAdmin({{ $user->id }})"
                                wire:loading.attr="disabled"
                                wire:target="deleteUserAsAdmin"
                                class="px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:text-white bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="deleteUserAsAdmin">Remove User</span>
                                <span wire:loading wire:target="deleteUserAsAdmin">Removing...</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
