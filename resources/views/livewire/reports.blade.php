<div class="min-h-screen dark:bg-black bg-white dark:text-white text-gray-900 pb-24">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold dark:text-white text-gray-900 bg-gradient-to-r from-red-400 via-orange-400 to-yellow-400 bg-clip-text text-transparent">
                Reported content
            </h1>
            <p class="mt-2 text-sm dark:text-gray-400 text-gray-600">
                Content that people have reported. Review each item and dismiss the report or delete the reported user or post.
            </p>
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

        <!-- Reports List -->
        <div class="space-y-4">
            @forelse ($reports as $report)
                <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-6 shadow-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    @if($report->status === 'pending') dark:bg-yellow-600/20 bg-yellow-100 dark:text-yellow-300 text-yellow-700
                                    @elseif($report->status === 'resolved') dark:bg-green-600/20 bg-green-100 dark:text-green-300 text-green-700
                                    @else dark:bg-gray-600/20 bg-gray-100 dark:text-gray-300 text-gray-700
                                    @endif">
                                    {{ ucfirst($report->status) }}
                                </span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full dark:bg-blue-600/20 bg-blue-100 dark:text-blue-300 text-blue-700">
                                    {{ ucfirst($report->target_type) }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="text-sm dark:text-gray-400 text-gray-600 mb-1">
                                    <strong>Reported by:</strong> 
                                    <a href="{{ route('user.profile', $report->reporter->username ?? 'unknown') }}" class="dark:text-blue-400 text-blue-600 hover:underline">
                                        {{ $report->reporter->name }}
                                    </a>
                                </p>
                                <p class="text-sm dark:text-gray-400 text-gray-600">
                                    <strong>Reason:</strong> {{ $report->reason }}
                                </p>
                                <p class="text-xs dark:text-gray-500 text-gray-500 mt-2">
                                    Reported {{ $report->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Target Preview -->
                            <div class="mt-4 p-4 dark:bg-gray-800 bg-gray-50 rounded-lg border dark:border-gray-700 border-gray-200">
                                @if($report->target_type === 'post' && $report->target)
                                    <div>
                                        <p class="text-sm font-semibold dark:text-white text-gray-900 mb-2">
                                            Post by: 
                                            <a href="{{ route('user.profile', $report->target->user->username ?? 'unknown') }}" class="dark:text-blue-400 text-blue-600 hover:underline">
                                                {{ $report->target->user->name }}
                                            </a>
                                        </p>
                                        @if($report->target->title)
                                            <p class="text-sm font-medium dark:text-gray-200 text-gray-700 mb-1">{{ $report->target->title }}</p>
                                        @endif
                                        <p class="text-sm dark:text-gray-300 text-gray-600">{{ Str::limit($report->target->content, 200) }}</p>
                                        <a href="{{ route('posts.show', $report->target->slug) }}" target="_blank" class="text-xs dark:text-blue-400 text-blue-600 hover:underline mt-2 inline-block">
                                            View Post →
                                        </a>
                                    </div>
                                @elseif($report->target_type === 'user' && $report->target)
                                    <div>
                                        <p class="text-sm font-semibold dark:text-white text-gray-900 mb-2">
                                            User: 
                                            <a href="{{ route('user.profile', $report->target->username ?? 'unknown') }}" class="dark:text-blue-400 text-blue-600 hover:underline">
                                                {{ $report->target->name ?? 'Unknown User' }}@if(!empty($report->target->username)) ({{ '@' . $report->target->username }})@endif
                                            </a>
                                        </p>
                                        <p class="text-xs dark:text-gray-400 text-gray-600">{{ $report->target->email ?? 'N/A' }}</p>
                                    </div>
                                @elseif($report->target_type === 'comment' && $report->target)
                                    <div>
                                        <p class="text-sm font-semibold dark:text-white text-gray-900 mb-2">
                                            Comment by: 
                                            <a href="{{ route('user.profile', $report->target->user->username ?? 'unknown') }}" class="dark:text-blue-400 text-blue-600 hover:underline">
                                                {{ $report->target->user->name }}
                                            </a>
                                        </p>
                                        <p class="text-sm dark:text-gray-300 text-gray-600">{{ Str::limit($report->target->content, 200) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($report->status === 'pending')
                            <div class="flex flex-col gap-2 ml-4">
                                <button 
                                    wire:click="openActionModal({{ $report->id }}, 'delete')"
                                    class="px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Delete
                                </button>
                                <button 
                                    wire:click="openActionModal({{ $report->id }}, 'dismiss')"
                                    class="px-4 py-2 dark:bg-gray-600 dark:hover:bg-gray-700 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Dismiss
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-400">No reports yet</h3>
                    <p class="mt-1 text-sm text-gray-500">All reports have been reviewed.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    </div>

    <!-- Action Confirmation Modal -->
    @if($showActionModal && $selectedReport)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="closeActionModal"></div>

                <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200">
                        <h3 class="text-lg font-semibold dark:text-white text-gray-900">
                            {{ $actionType === 'delete' ? 'Delete ' . ucfirst($selectedReport->target_type) : 'Dismiss Report' }}
                        </h3>
                    </div>
                    
                    <div class="dark:bg-gray-900 bg-white px-6 py-4">
                        <p class="dark:text-gray-300 text-gray-700 mb-4">
                            @if($actionType === 'delete')
                                Are you sure you want to delete this {{ $selectedReport->target_type }}? This action cannot be undone.
                            @else
                                Are you sure you want to dismiss this report? The report will be marked as dismissed.
                            @endif
                        </p>
                    </div>

                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-t dark:border-gray-800 border-gray-200 flex justify-end gap-3">
                        <button 
                            type="button"
                            wire:click="closeActionModal"
                            class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button 
                            wire:click="executeAction"
                            class="px-4 py-2 {{ $actionType === 'delete' ? 'dark:bg-red-600 dark:hover:bg-red-700 bg-red-600 hover:bg-red-700' : 'dark:bg-gray-600 dark:hover:bg-gray-700 bg-gray-600 hover:bg-gray-700' }} text-white rounded-lg transition-colors">
                            {{ $actionType === 'delete' ? 'Delete' : 'Dismiss' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
