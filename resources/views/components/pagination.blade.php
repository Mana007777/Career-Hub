@props(['paginator'])

@php
    // Build pagination elements manually
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $elements = [];
    
    if ($lastPage <= 7) {
        // Show all pages if 7 or fewer
        for ($i = 1; $i <= $lastPage; $i++) {
            $elements[] = $i;
        }
    } else {
        // Show first page
        $elements[] = 1;
        
        if ($currentPage > 3) {
            $elements[] = '...';
        }
        
        // Show pages around current page
        $start = max(2, $currentPage - 1);
        $end = min($lastPage - 1, $currentPage + 1);
        
        for ($i = $start; $i <= $end; $i++) {
            $elements[] = $i;
        }
        
        if ($currentPage < $lastPage - 2) {
            $elements[] = '...';
        }
        
        // Show last page
        if ($lastPage > 1) {
            $elements[] = $lastPage;
        }
    }
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="mt-12 mb-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
            {{-- Page Info --}}
            <div class="text-sm dark:text-gray-300 text-gray-700 font-medium">
                Showing 
                <span class="dark:text-white text-gray-900 font-bold">{{ $paginator->firstItem() }}</span>
                to
                <span class="dark:text-white text-gray-900 font-bold">{{ $paginator->lastItem() }}</span>
                of
                <span class="dark:text-white text-gray-900 font-bold">{{ $paginator->total() }}</span>
                posts
            </div>

            <div class="flex items-center gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-400 dark:text-gray-600 bg-white dark:bg-gray-800/50 border border-gray-300 dark:border-gray-700 rounded-xl cursor-not-allowed opacity-60">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="ml-2 hidden sm:inline">Previous</span>
                    </span>
                @else
                    <button 
                        wire:click="previousPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled" 
                        rel="prev" 
                        class="relative inline-flex items-center px-4 py-2.5 text-sm font-medium dark:text-gray-200 text-gray-700 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 dark:hover:from-gray-700 dark:hover:to-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="ml-2 hidden sm:inline">Previous</span>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                <div class="flex items-center gap-1.5">
                    @foreach ($elements as $page)
                        @if ($page === '...')
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $page }}
                            </span>
                        @elseif ($page == $currentPage)
                            <span 
                                aria-current="page" 
                                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 dark:from-blue-500 dark:via-purple-500 dark:to-pink-500 border-2 border-blue-500 dark:border-blue-400 rounded-xl shadow-lg shadow-blue-500/50 transform scale-110 ring-2 ring-blue-300 dark:ring-blue-600 ring-offset-2 dark:ring-offset-gray-900 transition-all duration-200">
                                {{ $page }}
                            </span>
                        @else
                            <button 
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" 
                                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium dark:text-gray-200 text-gray-700 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gradient-to-br hover:from-blue-50 hover:via-purple-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:to-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 hover:shadow-md hover:shadow-blue-500/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                </div>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button 
                        wire:click="nextPage('{{ $paginator->getPageName() }}')" 
                        wire:loading.attr="disabled" 
                        rel="next" 
                        class="relative inline-flex items-center px-4 py-2.5 text-sm font-medium dark:text-gray-200 text-gray-700 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 dark:hover:from-gray-700 dark:hover:to-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="mr-2 hidden sm:inline">Next</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <span class="relative inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-400 dark:text-gray-600 bg-white dark:bg-gray-800/50 border border-gray-300 dark:border-gray-700 rounded-xl cursor-not-allowed opacity-60">
                        <span class="mr-2 hidden sm:inline">Next</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
