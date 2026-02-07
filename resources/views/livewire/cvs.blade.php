<div class="min-h-screen dark:text-white text-gray-900 pb-24" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
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

        <!-- Header -->
        <div 
            class="mb-8"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <h1 class="text-3xl font-bold dark:text-white text-gray-900 bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Received CVs</h1>
            <p class="dark:text-gray-400 text-gray-600 mt-2">View all CVs submitted for your job posts</p>
        </div>

        <!-- CVs List -->
        <div class="space-y-4">
            @forelse($cvs as $index => $cv)
                <div 
                    class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-6 shadow-lg hover:shadow-xl dark:hover:border-gray-600 hover:border-gray-300 transition-all duration-300 transform hover:scale-[1.02] hover:-translate-y-1"
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
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center">
                                    <span class="dark:text-gray-300 text-gray-700 font-semibold text-sm">
                                        {{ strtoupper(substr($cv->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold dark:text-white text-gray-900">{{ $cv->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-sm dark:text-gray-400 text-gray-600">{{ $cv->created_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>

                            <!-- Post Info -->
                            <div class="mt-4 p-3 dark:bg-gray-800/50 bg-gray-50 rounded-lg">
                                <p class="text-xs dark:text-gray-400 text-gray-600 mb-1">For Post:</p>
                                <a 
                                    href="{{ route('posts.show', $cv->post->slug) }}"
                                    class="dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700 font-medium">
                                    {{ $cv->post->title ?: 'Untitled Post' }}
                                </a>
                                @if($cv->post->job_type)
                                    <span class="ml-2 px-2 py-1 dark:bg-blue-600/20 bg-blue-100 dark:text-blue-300 text-blue-700 text-xs rounded dark:border-blue-600/50 border-blue-300 border font-medium">
                                        {{ ucfirst(str_replace('-', ' ', $cv->post->job_type)) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Message -->
                            @if($cv->message)
                                <div class="mt-3 p-3 dark:bg-gray-800/30 bg-gray-50 rounded-lg">
                                    <p class="text-sm dark:text-gray-300 text-gray-700">{{ $cv->message }}</p>
                                </div>
                            @endif

                            <!-- CV File Info -->
                            <div class="mt-3 flex items-center gap-2 text-sm dark:text-gray-400 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ $cv->original_filename }}</span>
                            </div>
                        </div>

                        <!-- Download Button -->
                        <div>
                            <button
                                wire:click="downloadCv({{ $cv->id }})"
                                wire:loading.attr="disabled"
                                wire:target="downloadCv"
                                class="px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="downloadCv">Download CV</span>
                                <span wire:loading wire:target="downloadCv">Downloading...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-12 text-center">
                    <svg class="w-16 h-16 mx-auto dark:text-gray-600 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold dark:text-white text-gray-900 mb-2">No CVs yet</h3>
                    <p class="dark:text-gray-400 text-gray-600">CVs submitted for your job posts will appear here.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $cvs->links() }}
        </div>
    </div>
</div>
