<x-app-layout>
    <div class="bg-transparent text-white min-h-screen">
        @livewire('search')
        @livewire('notifications')
        @livewire('chat-box')
        @livewire('chat-list')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-6">
            @if(isset($showCvs) && $showCvs)
                <section>
                    <livewire:cvs />
                </section>
            @elseif(isset($profileUsername))
                <section>
                    <livewire:user-profile :username="$profileUsername" />
                </section>
            @elseif(isset($postSlug))
                <section>
                    <livewire:post-detail :slug="$postSlug" />
                </section>
            @else
                @php
                    $user = auth()->user();
                    $followingCount = $user->following()->count();
                    $followingUsers = $user->following()->with('profile')->get();
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
                        <div class="px-6 pt-6 pb-4 border-b border-gray-800">
                            <h2 class="text-lg font-bold text-white">Following</h2>
                            <p class="text-sm text-gray-400 mt-1">{{ $followingCount }} people</p>
                        </div>
                            
                        <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
                                    @if($followingUsers->count() > 0)
                                        <div class="divide-y divide-gray-800">
                                            @foreach($followingUsers as $followingUser)
                                                @php
                                                    $isActive = $followingUser->isActive();
                                                @endphp
                                                <div class="flex items-center gap-3 px-6 py-4 hover:bg-gray-900/50 transition-colors group">
                                                    <a 
                                                        href="{{ route('user.profile', $followingUser->username ?? 'unknown') }}"
                                                        class="flex items-center gap-3 flex-1 min-w-0"
                                                    >
                                                        <div class="relative flex-shrink-0">
                                                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px]">
                                                                <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-lg font-semibold text-gray-100">
                                                                    {{ strtoupper(substr($followingUser->name ?? 'U', 0, 1)) }}
                                                                </div>
                                                            </div>
                                                            <span 
                                                                class="absolute bottom-0 right-0 w-3.5 h-3.5 border-2 border-gray-900 rounded-full transition-all duration-300 user-status-indicator-{{ $followingUser->id }}"
                                                                x-data="{ isOnline: {{ $isActive ? 'true' : 'false' }} }"
                                                                :class="isOnline ? 'bg-green-500 animate-pulse' : 'bg-gray-500'"
                                                            ></span>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center gap-2">
                                                                <p class="text-sm font-medium text-white group-hover:text-blue-400 transition-colors truncate">
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
                                                                <p class="text-xs text-gray-400 truncate">
                                                                    {{ '@'.$followingUser->username }}
                                                                </p>
                                                            @endif
                                                            @if($followingUser->profile && $followingUser->profile->headline)
                                                                <p class="text-xs text-gray-500 truncate mt-0.5">
                                                                    {{ $followingUser->profile->headline }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    <button
                                                        type="button"
                                                        onclick="window.dispatchEvent(new CustomEvent('open-chat', { detail: { userId: {{ $followingUser->id }} } }))"
                                                        class="flex-shrink-0 p-2 rounded-lg hover:bg-gray-800 text-gray-400 hover:text-blue-400 transition-colors"
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
                                            <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 11a3 3 0 10-6 0 3 3 0 006 0z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-400">You're not following anyone yet</p>
                                            <p class="mt-1 text-xs text-gray-500">Start following people to see them here</p>
                                        </div>
                                    @endif
                        </div>
                    </aside>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
