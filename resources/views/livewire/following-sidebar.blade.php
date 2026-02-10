<aside class="overflow-hidden rounded-2xl dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 shadow-lg shadow-blue-500/5">
    <div class="px-6 pt-6 pb-4 border-b dark:border-gray-800 border-gray-200 bg-gradient-to-r from-blue-600/10 via-purple-600/5 to-pink-600/10">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 dark:text-blue-300 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 11a3 3 0 10-6 0 3 3 0 006 0z"/>
                    </svg>
                    <h2 class="text-lg font-bold dark:text-white text-gray-900">Following</h2>
                </div>
                <p class="text-sm dark:text-gray-400 text-gray-600 mt-1">{{ $followingCount }} people you follow</p>
            </div>
        </div>
    </div>
        
    <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
        @if($followingUsers->count() > 0)
            <div class="space-y-1 py-2">
                @foreach($followingUsers as $followingUser)
                    @php
                        $isActive = $followingUser->isActive();
                    @endphp
                    <div class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl dark:hover:bg-gray-900/60 hover:bg-gray-100 transition-all duration-200 group">
                        <a 
                            href="{{ route('user.profile', $followingUser->username ?? 'unknown') }}"
                            class="flex items-center gap-3 flex-1 min-w-0"
                        >
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px]">
                                    <div class="w-full h-full rounded-full overflow-hidden dark:bg-gray-900 bg-gray-200 flex items-center justify-center text-lg font-semibold dark:text-gray-100 text-gray-900">
                                        @if($followingUser->profile_photo_path)
                                            <img src="{{ $followingUser->profile_photo_url }}" alt="{{ $followingUser->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($followingUser->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                </div>
                                <span 
                                    class="absolute bottom-0 right-0 w-3.5 h-3.5 border-2 dark:border-gray-900 border-white rounded-full transition-all duration-300 user-status-indicator-{{ $followingUser->id }}"
                                    x-data="{ isOnline: {{ $isActive ? 'true' : 'false' }} }"
                                    :class="isOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-500'"
                                ></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium dark:text-white text-gray-900 group-hover:text-blue-400 transition-colors truncate">
                                        {{ $followingUser->name }}
                                    </p>
                                    <span 
                                        class="flex-shrink-0 px-2 py-0.5 text-xs font-medium rounded-full border transition-all duration-300 user-status-badge-{{ $followingUser->id }}"
                                        x-data="{ isOnline: {{ $isActive ? 'true' : 'false' }} }"
                                        :class="isOnline ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-gray-500/20 text-gray-400 border-gray-500/30'"
                                        x-text="isOnline ? 'Active' : 'Offline'"
                                    ></span>
                                </div>
                                @if($followingUser->username)
                                    <p class="text-xs dark:text-gray-400 text-gray-600 truncate">
                                        {{ '@'.$followingUser->username }}
                                    </p>
                                @endif
                                @if($followingUser->profile && $followingUser->profile->headline)
                                    <p class="text-xs dark:text-gray-500 text-gray-500 truncate mt-0.5">
                                        {{ $followingUser->profile->headline }}
                                    </p>
                                @endif
                            </div>
                        </a>
                        <button
                            type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-chat', { detail: { userId: {{ $followingUser->id }} } }))"
                            class="flex-shrink-0 p-2 rounded-lg dark:hover:bg-gray-800 hover:bg-gray-200 dark:text-gray-400 text-gray-600 hover:text-blue-400 transition-colors"
                            title="Start chat"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <svg class="mx-auto h-12 w-12 dark:text-gray-600 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 11a3 3 0 10-6 0 3 3 0 006 0z"></path>
                </svg>
                <p class="mt-2 text-sm dark:text-gray-400 text-gray-600">You're not following anyone yet</p>
                <p class="mt-1 text-xs dark:text-gray-500 text-gray-500">Start following people to see them here</p>
            </div>
        @endif
    </div>
</aside>

