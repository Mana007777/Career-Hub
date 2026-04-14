<div class="grid h-full max-w-xl grid-cols-9 gap-1 mx-auto relative px-2">
    <!-- Home Sector -->
    <a href="{{ route('dashboard') }}" data-tooltip-target="tooltip-home"
        class="inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500 relative">
        <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:scale-110" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
        </svg>
        <span class="sr-only">{{ __('Home') }}</span>
        @if(Request::routeIs('dashboard'))
            <div class="absolute bottom-1.5 w-1 h-1 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
        @endif
    </a>
    <div id="tooltip-home" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Home') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

    <!-- Intel Stream -->
    <button 
        onclick="if (window.openNotifications) window.openNotifications()"
        data-tooltip-target="tooltip-notifications" 
        type="button"
        class="relative inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500">
        <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:scale-110" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m2 0v1a3 3 0 11-6 0v-1h6z" />
        </svg>
        @if($unreadNotifications > 0)
            <span class="absolute top-2 right-2 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-lg text-[8px] font-black bg-emerald-500 text-black border-2 border-zinc-950 shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
            </span>
        @endif
        <span class="sr-only">{{ __('Notifications') }}</span>
    </button>
    <div id="tooltip-notifications" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Notifications') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

    <!-- Node Deployment -->
    <button 
        wire:click="$dispatch('openCreatePost')"
        data-tooltip-target="tooltip-post" 
        type="button"
        class="inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500">
        <div class="relative">
            <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:rotate-90" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7 7V5" />
            </svg>
        </div>
        <span class="sr-only">{{ __('Create Post') }}</span>
    </button>
    <div id="tooltip-post" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Create Post') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

    <!-- Global Scan -->
    <button 
        wire:click="$dispatch('openSearch')"
        data-tooltip-target="tooltip-search" 
        type="button"
        class="inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500">
        <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:scale-110" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke="currentColor" stroke-linecap="round" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
        </svg>
        <span class="sr-only">{{ __('Search') }}</span>
    </button>
    <div id="tooltip-search" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Search') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

    <!-- Bookmarks -->
    <a 
        href="{{ route('bookmarks') }}"
        data-tooltip-target="tooltip-bookmarks"
        class="relative inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500"
    >
        <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:scale-110" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                d="M5 5a2 2 0 012-2h10a1 1 0 011 1v15.382a1 1 0 01-1.555.832L12 17.5l-4.445 2.714A1 1 0 016 19.382V4a1 1 0 011-1z" />
        </svg>
        @if($savedPostsCount > 0)
            <span class="absolute top-2 right-2 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-lg text-[8px] font-black bg-emerald-500 text-black border-2 border-zinc-950 shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                {{ $savedPostsCount > 99 ? '99+' : $savedPostsCount }}
            </span>
        @endif
        <span class="sr-only">{{ __('Archive') }}</span>
    </a>
    <div id="tooltip-bookmarks" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Bookmarks') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

    <!-- Chat -->
    <button 
        onclick="window.dispatchEvent(new CustomEvent('openChatList'))"
        data-tooltip-target="tooltip-chat" 
        type="button"
        class="relative inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500">
        <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:scale-110" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        @if($totalUnreadMessages > 0)
            <span class="absolute top-2 right-2 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-lg text-[8px] font-black bg-emerald-500 text-black border-2 border-zinc-950 shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                {{ $totalUnreadMessages > 99 ? '99+' : $totalUnreadMessages }}
            </span>
        @endif
        <span class="sr-only">{{ __('Uplink') }}</span>
    </button>
    <div id="tooltip-chat" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Chat') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

    @if(auth()->check() && auth()->user()->isAdmin())
        <!-- Overseer Panel -->
        <a 
            href="{{ route('reports') }}"
            data-tooltip-target="tooltip-reports"
            class="relative inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500"
        >
            <svg class="w-6 h-6 text-zinc-600 group-hover:text-amber-500 transition-all duration-500 transform group-hover:scale-110" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6" />
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M8 13h2" />
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M8 17h2" />
            </svg>
            @if($pendingReportsCount > 0)
                <span class="absolute top-2 right-2 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-lg text-[8px] font-black bg-amber-500 text-black border-2 border-zinc-950 shadow-[0_0_15px_rgba(245,158,11,0.4)]">
                    {{ $pendingReportsCount > 99 ? '99+' : $pendingReportsCount }}
                </span>
            @endif
            <span class="sr-only">{{ __('Overseer') }}</span>
        </a>
        <div id="tooltip-reports" role="tooltip"
            class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
            {{ __('Reports') }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    @else
        <!-- Intelligence Dossier -->
        <a 
            href="{{ route('cvs') }}"
            data-tooltip-target="tooltip-cvs"
            class="inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500"
        >
            <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:scale-110" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="sr-only">{{ __('Dossier') }}</span>
        </a>
        <div id="tooltip-cvs" role="tooltip"
            class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
            {{ __('CVs') }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    @endif

    <!-- Settings -->
    <a 
        href="{{ route('settings') }}"
        data-tooltip-target="tooltip-settings" 
        class="inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500">
        <svg class="w-6 h-6 text-zinc-600 group-hover:text-emerald-400 transition-all duration-500 transform group-hover:rotate-180" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924-1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        <span class="sr-only">{{ __('Config') }}</span>
    </a>
    <div id="tooltip-settings" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Settings') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>

    <!-- Profile -->
    <a 
        href="{{ auth()->check() ? route('user.profile', auth()->user()->username ?? 'unknown') : route('profile.show') }}"
        data-tooltip-target="tooltip-profile"
        class="inline-flex flex-col items-center justify-center p-3 hover:bg-emerald-500/5 group rounded-2xl transition-all duration-500 relative"
    >
        <div class="w-6 h-6 rounded-lg overflow-hidden border-2 border-zinc-700/50 group-hover:border-emerald-500/50 transition-all duration-500 flex items-center justify-center bg-zinc-800/50">
            @if(auth()->check() && auth()->user()->profile_photo_path)
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Me" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
            @else
                <svg class="w-4 h-4 text-zinc-600 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 19.5a7.5 7.5 0 0 1 15 0v.75H4.5v-.75Z" />
                </svg>
            @endif
        </div>
        <span class="sr-only">{{ __('Identity') }}</span>
        @if(Request::routeIs('user.profile') || Request::routeIs('profile.show'))
            <div class="absolute bottom-1.5 w-1 h-1 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
        @endif
    </a>
    <div id="tooltip-profile" role="tooltip"
        class="absolute z-10 invisible inline-block px-4 py-2 text-[9px] font-black text-white transition-opacity duration-500 bg-zinc-900 rounded-xl shadow-3xl opacity-0 tooltip uppercase tracking-[0.3em] border border-zinc-800/50 backdrop-blur-xl">
        {{ __('Profile') }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
</div>
