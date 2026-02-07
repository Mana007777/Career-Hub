@auth
    <div class="fixed top-4 right-4 z-50 bg-gray-900 border-2 border-blue-500 rounded-lg px-4 py-2 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[1px]">
                <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-xs font-semibold text-gray-100">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">ID: {{ auth()->id() }} | Session: {{ substr(session()->getId(), 0, 8) }}</p>
                <p class="text-xs text-yellow-400 mt-1">Cookie: {{ substr(request()->cookie(config('session.cookie')), 0, 8) ?? 'N/A' }}</p>
                @if(app()->environment('local'))
                    <a href="{{ route('test.users') }}" class="text-xs text-blue-400 hover:text-blue-300 mt-1 block">Switch User</a>
                @endif
            </div>
        </div>
    </div>
@endauth
