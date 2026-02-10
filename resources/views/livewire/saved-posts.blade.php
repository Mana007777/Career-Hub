<div
    class="min-h-screen dark:text-white text-gray-900 pb-24"
    x-data="{ loaded: false }"
    x-init="
        loaded = false;

        const setLoaded = () => { loaded = true };
        const setLoading = () => { loaded = false };

        document.addEventListener('livewire:load', setLoaded);
        document.addEventListener('livewire:navigated', setLoaded);
        document.addEventListener('livewire:navigating', setLoading);
    "
>
    <!-- Skeleton while saved posts are loading -->
    <div x-show="!loaded">
        <x-skeleton.page-cards />
    </div>

    <!-- Actual content -->
    <div class="w-full px-0 sm:px-2 lg:px-0 py-4" x-show="loaded" x-cloak>
        <!-- Back Button -->
        <div class="mb-6">
            <button 
                type="button"
                onclick="window.history.back()"
                class="inline-flex items-center gap-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-all duration-300 transform hover:-translate-x-1 group"
            >
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </button>
        </div>

        <div class="mb-6">
            <h1 class="text-3xl font-bold dark:text-white text-gray-900">Saved posts</h1>
            <p class="mt-1 text-sm dark:text-gray-400 text-gray-600">Posts you’ve bookmarked to view later.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($posts as $post)
                <article
                    onclick="window.location.href='{{ route('posts.show', $post->slug) }}'"
                    class="group h-full flex flex-col rounded-2xl border dark:border-gray-800 border-gray-200 dark:bg-gradient-to-br dark:from-gray-900/95 dark:via-gray-900 dark:to-gray-900/80 bg-gradient-to-br from-white via-gray-50 to-white p-5 sm:p-6 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 dark:hover:border-gray-600 hover:border-gray-300 transition-all duration-300 cursor-pointer transform hover:scale-[1.02] hover:-translate-y-1"
                    style="position: relative;"
                >
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3 flex-1">
                            <a href="{{ route('user.profile', $post->user->username ?? 'unknown') }}" onclick="event.stopPropagation()" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center">
                                    @if($post->user && $post->user->profile_photo_path)
                                        <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="dark:text-gray-300 text-gray-700 font-semibold">
                                            {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-semibold dark:text-white text-gray-900">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-xs dark:text-gray-400 text-gray-600">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="mb-4">
                        @if(!empty($post->title))
                            <h2 class="text-lg font-semibold dark:text-white text-gray-900 mb-1 group-hover:text-blue-400 transition-colors">
                                {{ $post->title }}
                            </h2>
                        @endif
                        <p class="dark:text-gray-200 text-gray-700 leading-relaxed line-clamp-3 text-sm sm:text-base">
                            {{ $post->content }}
                        </p>
                    </div>

                    @if ($post->media)
                        <div class="mb-4 rounded-lg overflow-hidden">
                            @php
                                $mediaUrl = app(\App\Services\PostService::class)->getMediaUrl($post);
                                $isImage = in_array(strtolower(pathinfo($post->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            @if($isImage)
                                <img src="{{ $mediaUrl }}" alt="Post media" class="w-full h-auto rounded-lg">
                            @else
                                <div class="dark:bg-gray-800 bg-gray-100 p-4 rounded-lg">
                                    <a href="{{ $mediaUrl }}" target="_blank" class="flex items-center gap-2 dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L13.732 14M5 18H13a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>View attachment</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center gap-6 pt-4 border-t dark:border-gray-800 border-gray-200 relative z-10">
                        <div class="flex items-center gap-2 text-sm dark:text-gray-400 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976-2.888c-.783-.57-1.838-.197-1.538-1.118l1.518-4.674c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            <span>{{ $post->stars->count() }}</span>
                        </div>

                        <button
                            type="button"
                            wire:click.stop="togglePostSave({{ $post->id }})"
                            class="flex items-center gap-2 text-sm text-blue-400 hover:text-blue-300 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z"></path>
                            </svg>
                            <span>Unsave</span>
                        </button>
                    </div>
                </article>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-400">No saved posts yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Save posts from your feed to see them here.</p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="mt-8">
                <x-pagination :paginator="$posts" />
            </div>
        @endif
    </div>
</div>

