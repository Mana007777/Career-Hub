@auth
    <div class="fixed top-4 right-4 z-50 dark:bg-gray-900 bg-white border-2 border-blue-500 rounded-lg px-4 py-2 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[1px]">
                <div class="w-full h-full rounded-full dark:bg-gray-900 bg-white flex items-center justify-center text-xs font-semibold dark:text-gray-100 text-gray-900">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            </div>
            <div>
                <p class="text-sm font-medium dark:text-white text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs dark:text-gray-400 text-gray-600">ID: {{ auth()->id() }} | Session: {{ substr(session()->getId(), 0, 8) }}</p>
                <p class="text-xs dark:text-yellow-400 text-yellow-600 mt-1">Cookie: {{ substr(request()->cookie(config('session.cookie')), 0, 8) ?? 'N/A' }}</p>
                @if(app()->environment('local'))
                    <a href="{{ route('test.users') }}" class="text-xs dark:text-blue-400 text-blue-600 dark:hover:text-blue-300 hover:text-blue-700 mt-1 block">Switch User</a>
                @endif
            </div>
        </div>
    </div>
@endauth
