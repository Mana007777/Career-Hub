<x-app-layout>
    <div class="bg-transparent dark:text-white text-gray-900 min-h-screen">
        @livewire('search')
        @livewire('user-notifications')
        @livewire('chat-box')
        @livewire('chat-list')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-6">
            @if(isset($showCvs) && $showCvs)
                <section>
                    <livewire:cvs />
                </section>
            @elseif(isset($showReports) && $showReports)
                <section>
                    <livewire:reports />
                </section>
            @elseif(isset($profileUsername))
                <section>
                    <livewire:user-profile :username="$profileUsername" />
                </section>
            @elseif(isset($postSlug))
                <section>
                    <livewire:post-detail :slug="$postSlug" />
                </section>
            @elseif(isset($showSettings) && $showSettings)
                <section>
                    <livewire:settings />
                </section>
            @else
                @php
                    $user = auth()->user();
                    
                    // Get excluded user IDs (both blocked and blocked by)
                    $blockedIds = \DB::table('blocks')
                        ->where('blocker_id', $user->id)
                        ->pluck('blocked_id')
                        ->toArray();
                    
                    $blockedByIds = \DB::table('blocks')
                        ->where('blocked_id', $user->id)
                        ->pluck('blocker_id')
                        ->toArray();
                    
                    $excludedIds = array_unique(array_merge($blockedIds, $blockedByIds));
                    
                    // Get following users excluding blocked ones
                    // Note: following() returns a relationship, we need to get the IDs first
                    $followingIds = $user->following()->pluck('following_id')->toArray();
                    
                    // Filter out excluded IDs
                    if (!empty($excludedIds)) {
                        $followingIds = array_diff($followingIds, $excludedIds);
                    }
                    
                    // Get the filtered following users with profile
                    if (!empty($followingIds)) {
                        $followingUsers = \App\Models\User::whereIn('id', $followingIds)
                            ->with('profile')
                            ->get();
                    } else {
                        $followingUsers = collect();
                    }
                    
                    $followingCount = $followingUsers->count();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-[1fr_minmax(280px,320px)] gap-6 md:gap-10">
                    <!-- Main feed -->
                    <main class="space-y-4">
                        <section>
                            <livewire:post />
                        </section>
                    </main>

                    <!-- Right sidebar - Following list -->
                    <aside>
                        <div class="px-6 pt-6 pb-4 border-b dark:border-gray-800 border-gray-200">
                            <h2 class="text-lg font-bold dark:text-white text-gray-900">Following</h2>
                            <p class="text-sm dark:text-gray-400 text-gray-600 mt-1">{{ $followingCount }} people</p>
                        </div>
                            
                        <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
                                    @if($followingUsers->count() > 0)
                                        <div class="divide-y dark:divide-gray-800 divide-gray-200">
                                            @foreach($followingUsers as $followingUser)
                                                @php
                                                    $isActive = $followingUser->isActive();
                                                @endphp
                                                <div class="flex items-center gap-3 px-6 py-4 dark:hover:bg-gray-900/50 hover:bg-gray-100 transition-colors group">
                                                    <a 
                                                        href="{{ route('user.profile', $followingUser->username ?? 'unknown') }}"
                                                        class="flex items-center gap-3 flex-1 min-w-0"
                                                    >
                                                        <div class="relative flex-shrink-0">
                                                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px]">
                                                                <div class="w-full h-full rounded-full dark:bg-gray-900 bg-gray-200 flex items-center justify-center text-lg font-semibold dark:text-gray-100 text-gray-900">
                                                                    {{ strtoupper(substr($followingUser->name ?? 'U', 0, 1)) }}
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
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
