<x-app-layout>
    <div class="bg-transparent text-white min-h-screen">
        @livewire('search')
        @livewire('notifications')

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
                    $profile = $user->profile ?? null;
                    $postsCount = $user->posts()->count();
                    $followersCount = $user->followers()->count();
                    $followingCount = $user->following()->count();
                @endphp

                <div class="lg:grid lg:grid-cols-[minmax(280px,320px)_minmax(0,1fr)] lg:gap-10 space-y-6 lg:space-y-0">
                    <!-- Left profile sidebar -->
                    <aside class="hidden lg:block">
                        <div class="rounded-2xl border border-gray-800 bg-[#0D1117] shadow-xl overflow-hidden">
                            <div class="px-6 pt-6 pb-5">
                                <div class="flex items-start gap-4">
                                    <div class="relative">
                                        <div class="w-32 h-32 rounded-full bg-gradient-to-tr from-red-500 via-rose-500 to-orange-500 p-[3px]">
                                            <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-3xl font-bold text-gray-100">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 pt-2">
                                        <h1 class="text-2xl font-bold text-white leading-tight">
                                            {{ $user->name }}
                                        </h1>
                                        @if($user->username)
                                            <p class="text-sm text-gray-400">
                                                {{ '@'.$user->username }}
                                            </p>
                                        @endif
                                        @if($profile && $profile->headline)
                                            <p class="mt-2 text-sm text-gray-300">
                                                {{ $profile->headline }}
                                            </p>
                                        @elseif($profile && $profile->bio)
                                            <p class="mt-2 text-sm text-gray-300 line-clamp-2">
                                                {{ $profile->bio }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <a 
                                    href="{{ route('user.profile', $user->username ?? 'unknown') }}"
                                    class="mt-5 inline-flex w-full items-center justify-center px-4 py-2 text-sm font-medium rounded-md border border-gray-700 bg-[#21262D] hover:bg-[#30363D] text-gray-100 transition-colors"
                                >
                                    Edit profile
                                </a>
                            </div>

                            <div class="border-t border-gray-800 px-6 py-4">
                                <div class="flex items-center gap-4 text-sm text-gray-300">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8a6 6 0 11-12 0 6 6 0 0112 0zM12 14v7"></path>
                                        </svg>
                                        <span>{{ $postsCount }} posts</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M15 11a3 3 0 10-6 0 3 3 0 006 0z"></path>
                                        </svg>
                                        <span>{{ $followersCount }} followers</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-gray-500">·</span>
                                        <span>{{ $followingCount }} following</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <!-- Right main feed -->
                    <main class="space-y-4 lg:pl-2">
                        <section class="rounded-2xl border border-gray-800 bg-gradient-to-r from-gray-900/90 via-gray-900/80 to-gray-800/70 px-5 py-4 sm:px-6 sm:py-5 shadow-lg">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                                        Welcome back, {{ $user->name }} 👋
                                    </h1>
                                    <p class="mt-1 text-sm text-gray-400">
                                        Share what you’re working on, discover people by specialties, and stay up to date with your network.
                                    </p>
                                </div>
                                <div class="hidden sm:flex items-center gap-3">
                                    <button
                                        wire:click="$dispatch('openSearch')"
                                        type="button"
                                        class="inline-flex items-center gap-2 rounded-full border border-gray-700 bg-gray-900/70 px-3 py-1.5 text-xs font-medium text-gray-200 hover:border-blue-500 hover:text-blue-300 hover:bg-gray-900 transition-colors"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                        <span>Quick search</span>
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section>
                            <livewire:post />
                        </section>
                    </main>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
