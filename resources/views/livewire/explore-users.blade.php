<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold dark:text-white text-gray-900">Explore Users</h1>
    </div>

    {{-- URL-synced filters --}}
    <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl p-4 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Search</label>
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="query"
                    placeholder="Search by name or username..."
                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Role filter --}}
            <div>
                <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Role</label>
                <select 
                    wire:model.live="role"
                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($roleOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div>
                <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Sort by</label>
                <select 
                    wire:model.live="sort"
                    class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($sortOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($query || $role || $sort !== 'newest')
        <div class="mt-4 flex justify-end">
            <button 
                wire:click="clearFilters"
                class="px-4 py-2 dark:bg-gray-700 bg-gray-200 dark:hover:bg-gray-600 hover:bg-gray-300 dark:text-white text-gray-900 rounded-lg text-sm transition-colors">
                Clear Filters
            </button>
        </div>
        @endif
    </div>

    {{-- User grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($users as $user)
            <a 
                href="{{ route('user.profile', $user->username) }}"
                class="block dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl p-4 hover:shadow-lg dark:hover:border-gray-700 hover:border-gray-300 transition-all duration-300"
            >
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full dark:bg-gray-700 bg-gray-200 flex items-center justify-center text-lg font-semibold dark:text-gray-300 text-gray-700 shrink-0">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold dark:text-white text-gray-900 truncate">{{ $user->name }}</h3>
                        @if($user->username)
                            <p class="text-sm dark:text-gray-400 text-gray-600 truncate">@{{ $user->username }}</p>
                        @endif
                        @if(isset($user->followers_count))
                            <p class="text-xs dark:text-gray-500 text-gray-500 mt-1">{{ $user->followers_count }} followers</p>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif

    @if($users->isEmpty())
        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl p-12 text-center">
            <svg class="mx-auto h-12 w-12 dark:text-gray-600 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-medium dark:text-gray-400 text-gray-600 mb-2">No users found</h3>
            <p class="text-sm dark:text-gray-500 text-gray-500">Try adjusting your filters or search query</p>
            <button 
                wire:click="clearFilters"
                class="mt-4 px-4 py-2 dark:bg-blue-600 bg-blue-600 dark:text-white text-white rounded-lg text-sm hover:dark:bg-blue-700 hover:bg-blue-700 transition-colors">
                Clear Filters
            </button>
        </div>
    @endif
</div>
