<div class="min-h-screen bg-black text-white pb-24" style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div 
            class="mb-8"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <button 
                onclick="window.history.back()"
                class="inline-flex items-center gap-2 text-brand-violet hover:text-brand-purple transition-all duration-300 transform hover:-translate-x-1 group">
                <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center group-hover:bg-brand-purple group-hover:text-white transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-xs font-black uppercase tracking-widest">Back to Mission Control</span>
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-8 p-6 bg-emerald-950/20 border border-emerald-900/30 rounded-3xl text-emerald-500 text-[10px] font-black uppercase tracking-widest backdrop-blur-xl shadow-2xl flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-600/10 rounded-xl flex items-center justify-center border border-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-8 p-6 bg-red-950/20 border border-red-900/30 rounded-3xl text-red-500 text-[10px] font-black uppercase tracking-widest backdrop-blur-xl shadow-2xl flex items-center gap-3">
                <div class="w-8 h-8 bg-red-600/10 rounded-xl flex items-center justify-center border border-red-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Profile Header -->
        <div 
            class="bg-brand-deep/10 border border-white/5 rounded-3xl p-8 mb-10 shadow-3xl backdrop-blur-xl transition-all duration-1000"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        >
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Profile Photo/Avatar (same approach as post cards - profile_photo_url) -->
                <div class="relative w-32 h-32 rounded-3xl overflow-hidden bg-brand-deep border-4 border-white/5 flex items-center justify-center text-4xl font-black text-brand-violet shrink-0 ring-4 ring-brand-purple/20 group hover:scale-105 transition-all duration-500">
                    @if($user->profile_photo_path)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1 w-full">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <h1 class="text-3xl font-black text-white uppercase tracking-tighter">{{ $user->username }}</h1>
                                @if($user->role)
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-brand-purple/10 border border-brand-purple/20 text-brand-violet">
                                        {{ $user->role }}
                                    </span>
                                @endif
                                @if($user->isSuspended())
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-red-600/10 border border-red-600/30 text-red-500" title="Suspended">
                                        Suspended
                                    </span>
                                @endif
                            </div>
                            
                            @if($user->profile && $user->profile->bio)
                                <p class="text-gray-300 text-sm leading-relaxed mb-6 max-w-xl">{{ $user->profile->bio }}</p>
                            @endif

                            <!-- Additional Info: Location and Website -->
                            <div class="flex flex-wrap items-center gap-6 mb-6 text-[10px] font-black uppercase tracking-widest text-gray-500">
                                @if($user->profile && $user->profile->location)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-brand-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>{{ $user->profile->location }}</span>
                                    </div>
                                @endif
                                @if($user->profile && $user->profile->website)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-brand-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        <a href="{{ $websiteUrl }}" target="_blank" rel="noopener noreferrer" class="text-brand-violet hover:text-brand-purple transition-colors">
                                            {{ $websiteDisplay }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Stats -->
                            <div class="flex flex-wrap items-start gap-10 mb-2">
                                <div class="flex flex-col gap-1 min-w-[60px]">
                                    <span class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Posts</span>
                                    <p class="text-white text-xl font-black uppercase">{{ $postsCount }}</p>
                                </div>
                                <button 
                                    type="button"
                                    wire:click="openFollowersModal"
                                    class="text-left hover:opacity-80 transition-opacity flex flex-col gap-1 min-w-[80px]">
                                    <span class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Followers</span>
                                    <p class="text-white text-xl font-black uppercase hover:text-brand-violet transition-colors">{{ $followersCount }}</p>
                                </button>
                                <button 
                                    type="button"
                                    wire:click="openFollowingModal"
                                    class="text-left hover:opacity-80 transition-opacity flex flex-col gap-1 min-w-[80px]">
                                    <span class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Following</span>
                                    <p class="text-white text-xl font-black uppercase hover:text-brand-violet transition-colors">{{ $followingCount }}</p>
                                </button>
                                <div class="flex flex-col gap-1 min-w-[80px]">
                                    <span class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Reactions</span>
                                    <p class="text-white text-xl font-black uppercase">{{ $endorsementCount }}</p>
                                </div>
                                
                                <!-- Organizations -->
                                @if($organizationMemberships && count($organizationMemberships) > 0)
                                    <div class="flex flex-col gap-1 min-w-[120px]">
                                        <span class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Mission Groups</span>
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            @foreach($organizationMemberships as $org)
                                                <a
                                                    href="{{ route('user.profile', $org->username ?? 'unknown') }}"
                                                    class="w-8 h-8 rounded-xl overflow-hidden bg-brand-deep flex items-center justify-center hover:ring-2 hover:ring-brand-purple transition-all"
                                                >
                                                    @if($org->profile_photo_path)
                                                        <img src="{{ $org->profile_photo_url }}" alt="{{ $org->name ?? $org->username }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-[10px] font-black text-brand-violet">
                                                            {{ strtoupper(substr($org->name ?? $org->username ?? 'C', 0, 1)) }}
                                                        </span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3" x-data="{ openOptions: false }">
                            <!-- Follow/Unfollow + options for other users -->
                            @if(Auth::check() && Auth::id() !== $user->id)
                                @if($isBlocked)
                                    <!-- Show blocked message when current user has blocked this user -->
                                    <div class="px-6 py-3 rounded-lg dark:bg-red-900/30 bg-red-50 dark:border-red-700/50 border-red-200 dark:text-red-200 text-red-800 font-medium">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            <span class="font-medium">You have blocked this user</span>
                                        </div>
                                    </div>
                                    <button 
                                        wire:click="toggleBlock"
                                        class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white border dark:border-gray-700 border-gray-700">
                                        Unblock
                                    </button>
                                @else
                                    <!-- Follow / Unfollow primary button -->
                                    <button 
                                        wire:click="toggleFollow"
                                        class="px-8 py-2.5 rounded-xl font-black uppercase tracking-widest text-xs transition-all duration-300 shadow-lg shadow-brand-purple/20
                                            @if($isFollowing)
                                                bg-white/5 border border-white/10 text-white hover:bg-white/10
                                            @else
                                                bg-brand-purple hover:bg-brand-violet text-white
                                            @endif">
                                        @if($isFollowing)
                                            Unfollow
                                        @else
                                            Follow
                                        @endif
                                    </button>

                                    <!-- More options dropdown (Block, Invite, Report, etc.) -->
                                    <div class="relative">
                                        <button
                                            type="button"
                                            @click="openOptions = !openOptions"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-lg dark:bg-gray-800 bg-gray-200 dark:hover:bg-gray-700 hover:bg-gray-300 dark:text-gray-200 text-gray-800 transition-colors"
                                            title="More options"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01" />
                                            </svg>
                                        </button>

                                        <div
                                            x-show="openOptions"
                                            @click.away="openOptions = false"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute right-0 mt-2 w-56 bg-brand-deep/95 border border-white/10 rounded-2xl shadow-3xl z-40 backdrop-blur-xl"
                                            style="display: none;"
                                        >
                                            <!-- Block -->
                                            <button
                                                type="button"
                                                wire:click="toggleBlock"
                                                wire:confirm="Are you sure you want to block this user? You won't be able to see their posts or profile."
                                                class="w-full flex items-center gap-2 px-4 py-2 text-sm dark:text-red-400 text-red-600 hover:dark:bg-gray-800 hover:bg-gray-50 transition-colors"
                                                @click="openOptions = false"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                                <span>Block user</span>
                                            </button>

                                            <!-- Endorse -->
                                            <button
                                                type="button"
                                                wire:click="openEndorseModal"
                                                class="w-full flex items-center gap-2 px-4 py-2 text-sm dark:text-emerald-400 text-emerald-600 hover:dark:bg-gray-800 hover:bg-gray-50 transition-colors"
                                                @click="openOptions = false"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                                </svg>
                                                <span>Endorse skills</span>
                                            </button>

                                            <!-- Company: Invite to organization (only if user is not already in viewer's org) -->
                                            @if(Auth::user()->isCompany() && !$viewerCompanyAlreadyMember)
                                                <button
                                                    type="button"
                                                    wire:click="inviteToOrganization"
                                                    @if($pendingOrganizationInvitationId) disabled @endif
                                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm
                                                        @if($pendingOrganizationInvitationId)
                                                            dark:text-gray-500 text-gray-400 cursor-not-allowed
                                                        @else
                                                            dark:text-emerald-400 text-emerald-600 hover:dark:bg-gray-800 hover:bg-gray-50
                                                        @endif
                                                        transition-colors"
                                                    @click="openOptions = false"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                    </svg>
                                                    @if($pendingOrganizationInvitationId)
                                                        <span>Invitation sent</span>
                                                    @else
                                                        <span>Invite to organization</span>
                                                    @endif
                                                </button>
                                            @endif

                                            <!-- Report -->
                                            @if(!Auth::user()->isAdmin())
                                                <button
                                                    type="button"
                                                    data-target-id="{{ $user->id }}"
                                                    @click="openOptions = false; window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'user', targetId: parseInt({{ '$el' }}.dataset.targetId) } }))"
                                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm dark:text-orange-300 text-orange-700 hover:dark:bg-gray-800 hover:bg-gray-50 transition-colors"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <span>Report user</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif

                             <!-- Self profile actions -->
                             @if(Auth::check() && Auth::id() === $user->id)
                                 <a 
                                     href="{{ route('profile.show') }}"
                                     class="inline-flex items-center gap-2 px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-white transition-all shadow-lg shadow-black/50"
                                 >
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a3 3 0 014.243 4.243L9 19.95 4 21l1.05-5 10.182-10.768z" />
                                     </svg>
                                     <span>Edit Terminal</span>
                                 </a>
                                 <form method="POST" action="{{ route('logout') }}">
                                     @csrf
                                     <button 
                                         type="submit"
                                         class="inline-flex items-center gap-2 px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl border border-red-900/50 bg-red-600/10 hover:bg-red-600/20 text-red-500 transition-all shadow-lg shadow-red-900/10"
                                     >
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l3 3m-3-3v12" />
                                         </svg>
                                         <span>Logout</span>
                                     </button>
                                 </form>
                             @endif
                            
                            {{-- Admin Actions (Only visible to admins, cannot delete themselves) --}}
                            @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $user->id)
                                @php
                                    $user->loadMissing('suspension');
                                @endphp
                                <div class="relative" x-data="{ open: false }">
                                    <button 
                                        type="button"
                                        @click="open = !open"
                                        class="p-2.5 text-gray-500 hover:text-white rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all"
                                        title="Admin Actions">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    
                                    <div 
                                        x-show="open"
                                        @click.away="open = false"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 mt-3 w-56 bg-brand-deep/95 border border-white/10 rounded-2xl shadow-3xl z-50 backdrop-blur-xl p-2"
                                        style="display: none;">
                                        @if($user->isSuspended())
                                            <button 
                                                wire:click="openUnsuspendUserModal"
                                                class="w-full text-left px-4 py-2 text-[10px] font-black uppercase tracking-widest text-emerald-500 hover:bg-emerald-500/10 rounded-xl flex items-center gap-3 transition-all"
                                                @click="open = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                                </svg>
                                                Unsuspend Unit
                                            </button>
                                        @else
                                            <button 
                                                wire:click="openSuspendUserModal"
                                                class="w-full text-left px-4 py-2 text-[10px] font-black uppercase tracking-widest text-amber-500 hover:bg-amber-500/10 rounded-xl flex items-center gap-3 transition-all"
                                                @click="open = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Suspend Unit
                                            </button>
                                        @endif
                                        <button 
                                            wire:click="openDeleteUserModal"
                                            class="w-full text-left px-4 py-2 text-[10px] font-black uppercase tracking-widest text-red-500 hover:bg-red-500/10 rounded-xl flex items-center gap-3 transition-all"
                                            @click="open = false">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Purge Identity
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Posts -->
        @if($isBlocked)
            <div class="mb-10 p-12 bg-red-950/20 border border-red-900/30 rounded-3xl text-center backdrop-blur-xl shadow-2xl shadow-red-900/5">
                <div class="w-20 h-20 bg-red-600/10 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-red-600/20">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-white uppercase tracking-tighter italic mb-2">Access Denied</h3>
                <p class="text-[10px] items-center gap-1 font-black text-red-500 uppercase tracking-widest truncate mt-2 opacity-80">You have terminated connection with this unit.</p>
            </div>
        @else
            {{-- Pending organization invites --}}
            @if(Auth::check() && Auth::id() === $user->id && $user->organizationInvitations && $user->organizationInvitations->count() > 0)
                <div class="mb-10">
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-4 ml-1 italic">Incoming Invitations</h2>
                    <div class="space-y-4">
                        @foreach($user->organizationInvitations as $invite)
                            @php
                                $membership = \App\Models\OrganizationMembership::where('company_id', $invite->id)
                                    ->where('user_id', $user->id)
                                    ->where('status', 'pending')
                                    ->first();
                            @endphp
                            @if($membership)
                                <div class="flex items-center justify-between px-6 py-6 rounded-3xl bg-brand-deep/20 border border-white/5 backdrop-blur-xl shadow-2xl">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-brand-deep border border-brand-purple/20 flex items-center justify-center text-[10px] font-black text-brand-violet">
                                            {{ strtoupper(substr($invite->name ?? $invite->username ?? 'C', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-white uppercase tracking-tight">
                                                {{ $invite->name ?? $invite->username }}
                                            </p>
                                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">
                                                MISSION OPPORTUNITY AVAILABLE
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            wire:click="acceptOrganizationInvite({{ $membership->id }})"
                                            class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-all shadow-lg shadow-emerald-900/20"
                                        >
                                            Accept
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="rejectOrganizationInvite({{ $membership->id }})"
                                            class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white transition-all"
                                        >
                                            Decline
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Endorsements section --}}
            @if(count($endorsementsBySkill) > 0)
                <div class="mb-10">
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-4 ml-1">Rank Endorsements</h2>
                    <div class="space-y-4">
                        @foreach($endorsementsBySkill as $endorsementGroup)
                            <div class="bg-brand-deep/10 border border-white/5 rounded-2xl p-6 backdrop-blur-xl">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-black text-white uppercase tracking-widest">{{ $endorsementGroup->skill }}</span>
                                    <span class="text-[10px] font-black text-brand-violet uppercase tracking-widest">{{ $endorsementGroup->count }} validation{{ $endorsementGroup->count !== 1 ? 's' : '' }}</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($endorsementGroup->endorsers as $endorser)
                                        @if($endorser)
                                            <a href="{{ route('user.profile', $endorser->username ?? '') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all group">
                                                <div class="w-6 h-6 rounded-lg overflow-hidden bg-brand-deep flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                                    @if($endorser->profile_photo_path)
                                                        <img src="{{ $endorser->profile_photo_url }}" alt="" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-[8px] font-black text-brand-violet">{{ strtoupper(substr($endorser->name ?? 'U', 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover:text-white transition-colors">{{ $endorser->name ?? $endorser->username ?? 'Unknown' }}</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                                @if(Auth::check() && Auth::id() !== $user->id)
                                    @php
                                        $hasEndorsed = \App\Actions\User\EndorseUser::class;
                                        $endorseAction = app($hasEndorsed);
                                        $currentUserEndorsed = $endorseAction->hasEndorsed($user, $endorsementGroup->skill);
                                    @endphp
                                    @if($currentUserEndorsed)
                                        <button
                                            type="button"
                                            wire:click="removeEndorsement({{ \Illuminate\Support\Js::from($endorsementGroup->skill) }})"
                                            class="mt-2 text-xs dark:text-gray-400 text-gray-600 hover:dark:text-red-400 hover:text-red-600 transition-colors"
                                        >
                                            Remove my endorsement
                                        </button>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-10">
                <h2 class="text-xl font-black text-white uppercase tracking-tighter mb-4 ml-1">Mission Log Entries</h2>
                
                <div class="space-y-6">
                    @forelse ($posts as $index => $post)
                    <div 
                        class="bg-brand-deep/10 border border-white/5 rounded-3xl p-8 hover:border-white/10 transition-all duration-500 transform hover:scale-[1.01] hover:-translate-y-1 shadow-2xl backdrop-blur-xl"
                        x-data="{ show: false }"
                        x-init="
                            setTimeout(() => {
                                show = true;
                            }, {{ $index * 100 }});
                        "
                        x-show="show"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0"
                    >
                        <!-- Post Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-brand-deep flex items-center justify-center border border-white/5">
                                    @if($post->user && $post->user->profile_photo_path)
                                        <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] font-black text-brand-violet">
                                            {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-[10px] font-black text-white uppercase tracking-widest">{{ $post->user->name ?? 'Unknown User' }}</h3>
                                    <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mt-0.5">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Post Title & Content -->
                        <div class="mb-4">
                            @if(!empty($post->title))
                                <h3 class="text-lg font-black text-white uppercase tracking-tighter mb-2">{{ $post->title }}</h3>
                            @endif
                            <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
                        </div>
 Broadway

                        <!-- Post Media -->
                        @if ($post->media)
                            <div class="mb-4 rounded-lg overflow-hidden">
                                @php
                                    $mediaUrl = $this->getMediaUrl($post);
                                    $isImage = in_array(strtolower(pathinfo($post->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                
                                @if($isImage)
                                    <img src="{{ $mediaUrl }}" alt="Post media" class="w-full h-auto rounded-lg">
                                @else
                                    <video src="{{ $mediaUrl }}" controls class="w-full rounded-lg">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @endif

                        <!-- Post Specialties -->
                        @if($post->specialties && $post->specialties->count() > 0)
                            <div class="mb-2 pt-4 border-t dark:border-gray-800 border-gray-200">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->specialties as $specialty)
                                        @php
                                            $subSpecialty = $specialty->subSpecialties->firstWhere('id', $specialty->pivot->sub_specialty_id);
                                        @endphp
                                        @if($subSpecialty)
                                            <span class="px-3 py-1.5 bg-brand-violet/10 border border-brand-violet/30 rounded-xl text-brand-violet text-[8px] font-black uppercase tracking-widest">
                                                {{ $specialty->name }} / {{ $subSpecialty->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Post Tags -->
                        @if($post->tags && $post->tags->count() > 0)
                            <div class="mb-4 @if(!$post->specialties || $post->specialties->count() === 0) pt-4 border-t border-white/5 @endif">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                        <span class="px-3 py-1.5 bg-brand-purple/10 border border-brand-purple/30 rounded-xl text-brand-purple text-[8px] font-black uppercase tracking-widest">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Post Stats (stars + comments only) -->
                        <div class="flex items-center gap-8 pt-6 border-t border-white/5 mt-auto">
                            <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-500">
                                <svg class="w-4 h-4 text-brand-violet" fill="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <span>{{ $post->stars->count() }} Stars</span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-500">
                                <svg class="w-4 h-4 text-brand-purple" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>{{ $post->comments->count() }} Comms</span>
                            </div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="ml-auto text-[10px] font-black uppercase tracking-widest text-brand-violet hover:text-brand-purple transition-all">
                                Open Entry
                            </a>
                        </div>
                    </div>
                    @empty
                        <div class="col-span-full bg-brand-deep/10 border border-white/5 rounded-3xl p-16 text-center backdrop-blur-xl">
                            <div class="w-20 h-20 bg-brand-deep/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-white/5">
                                <svg class="h-10 w-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-white uppercase tracking-tighter">No Logs found</h3>
                            <p class="mt-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">This unit has not broadcasted any logs yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts && $posts->hasPages())
                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                @endif
    </div>
@endif

<!-- Report Modal -->
@livewire('report-modal')

<!-- Followers Modal -->
@if($showFollowersModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md" wire:click="closeFollowersModal">
        <div class="bg-brand-deep/95 border border-white/5 rounded-3xl max-w-md w-full mx-4 max-h-[80vh] overflow-hidden flex flex-col shadow-3xl backdrop-blur-xl" wire:click.stop>
            <div class="flex items-center justify-between p-6 border-b border-white/5">
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Followers</h3>
                <button
                    type="button"
                    wire:click="closeFollowersModal"
                    class="dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6 space-y-4">
                @if($user->followers && $user->followers->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->followers as $follower)
                            <a 
                                href="{{ route('user.profile', $follower->username ?? 'unknown') }}"
                                class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all group">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-brand-deep flex items-center justify-center text-[10px] font-black text-brand-violet flex-shrink-0 group-hover:scale-110 transition-transform">
                                    @if($follower->profile_photo_path)
                                        <img src="{{ $follower->profile_photo_url }}" alt="{{ $follower->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($follower->name ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black text-white uppercase tracking-tight group-hover:text-brand-violet transition-colors truncate">
                                        {{ $follower->name ?? 'Unknown User' }}
                                    </p>
                                    @if(!empty($follower->username))
                                        <p class="text-[10px] items-center gap-1 font-black text-gray-500 uppercase tracking-widest truncate">
                                            {{ '@' . $follower->username }}
                                        </p>
                                    @endif
                                    @if($follower->profile && !empty($follower->profile->bio))
                                        <p class="text-xs dark:text-gray-500 text-gray-500 truncate mt-0.5">
                                            {{ Str::limit($follower->profile->bio, 50) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">No followers yet broadcasted.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Following Modal -->
@if($showFollowingModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md" wire:click="closeFollowingModal">
        <div class="bg-brand-deep/95 border border-white/5 rounded-3xl max-w-md w-full mx-4 max-h-[80vh] overflow-hidden flex flex-col shadow-3xl backdrop-blur-xl" wire:click.stop>
            <div class="flex items-center justify-between p-6 border-b border-white/5">
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Following</h3>
                <button
                    type="button"
                    wire:click="closeFollowingModal"
                    class="dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6 space-y-4">
                @if($user->following && $user->following->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->following as $followedUser)
                            <a 
                                href="{{ route('user.profile', $followedUser->username ?? 'unknown') }}"
                                class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all group">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-brand-deep flex items-center justify-center text-[10px] font-black text-brand-violet flex-shrink-0 group-hover:scale-110 transition-transform">
                                    @if($followedUser->profile_photo_path)
                                        <img src="{{ $followedUser->profile_photo_url }}" alt="{{ $followedUser->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($followedUser->name ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black text-white uppercase tracking-tight group-hover:text-brand-violet transition-colors truncate">
                                        {{ $followedUser->name ?? 'Unknown User' }}
                                    </p>
                                    @if(!empty($followedUser->username))
                                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest truncate">
                                            {{ '@' . $followedUser->username }}
                                        </p>
                                    @endif
                                    @if($followedUser->profile && !empty($followedUser->profile->bio))
                                        <p class="text-xs dark:text-gray-500 text-gray-500 truncate mt-0.5">
                                            {{ Str::limit($followedUser->profile->bio, 50) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">No active scans for this unit.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Endorse Modal -->
@if($showEndorseModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md" wire:click="closeEndorseModal">
        <div class="bg-brand-deep/95 border border-white/5 rounded-3xl max-w-md w-full mx-4 shadow-3xl backdrop-blur-xl" wire:click.stop>
            <div class="flex items-center justify-between p-6 border-b border-white/5">
                <h3 class="text-sm font-black text-white uppercase tracking-widest italic">Validate Identity: {{ $user->username }}</h3>
                <button
                    type="button"
                    wire:click="closeEndorseModal"
                    class="dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="endorseUser" class="p-6 space-y-6">
                @if(count($endorsableSkills) > 0)
                    <div>
                        <label for="selectedSkillToEndorse" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 italic">Known Capabilities</label>
                        <select
                            wire:model.live="selectedSkillToEndorse"
                            id="selectedSkillToEndorse"
                            class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-black uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-brand-purple transition-all appearance-none cursor-pointer">
                            @foreach($endorsableSkills as $skill)
                                <option value="{{ $skill }}" class="bg-brand-deep text-white">{{ $skill }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="customSkill" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 italic">
                        @if(count($endorsableSkills) > 0)
                            New Capability Descriptor
                        @else
                            Capability Descriptor
                        @endif
                    </label>
                    <input
                        type="text"
                        wire:model="customSkill"
                        id="customSkill"
                        placeholder="ENTER TAG..."
                        maxlength="255"
                        class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-black uppercase tracking-widest placeholder:text-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-purple transition-all">
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button
                        type="button"
                        wire:click="closeEndorseModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">
                        Abort
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="endorseUser"
                        class="px-8 py-2.5 bg-brand-purple hover:bg-brand-violet text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-brand-purple/20 disabled:opacity-50">
                        <span wire:loading.remove wire:target="endorseUser">Submit Validation</span>
                        <span wire:loading wire:target="endorseUser">Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- Admin Actions Modal -->
@if ($showAdminActionsModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/80 backdrop-blur-md" wire:click="closeAdminActionsModal"></div>

            <div class="inline-block align-bottom bg-brand-deep/95 border border-white/10 rounded-3xl text-left overflow-hidden shadow-3xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full backdrop-blur-xl" wire:click.stop>
                <div class="px-8 py-6 border-b border-white/5 bg-white/5">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest italic">
                        @if($adminActionType === 'suspend')
                            Execute Suspension
                        @elseif($adminActionType === 'unsuspend')
                            Reactivate Identity
                        @elseif($adminActionType === 'delete')
                            Purge Identity
                        @endif
                    </h3>
                </div>
                
                @if($adminActionType === 'suspend')
                    <form wire:submit.prevent="suspendUser" class="px-8 py-6 space-y-6">
                        <div>
                            <label for="suspendReason" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 italic">Suspension Protocol Reason *</label>
                            <textarea
                                wire:model="suspendReason"
                                id="suspendReason"
                                rows="3"
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-black uppercase tracking-widest placeholder:text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all resize-none"
                                placeholder="ENTER REASON..."></textarea>
                            @error('suspendReason')
                                <span class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="suspendExpiresAt" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 italic">Temporal Expiration (Optional)</label>
                            <input
                                type="datetime-local"
                                wire:model="suspendExpiresAt"
                                id="suspendExpiresAt"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full px-5 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-black uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all">
                            <p class="text-[8px] font-black text-gray-600 uppercase tracking-widest mt-2 italic">EMPTY = PERMANENT REMOVAL FROM NETWORK</p>
                            @error('suspendExpiresAt')
                                <span class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-white/5">
                            <button 
                                type="button"
                                wire:click="closeAdminActionsModal"
                                class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">
                                Abort
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="suspendUser"
                                class="px-8 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-amber-900/20 disabled:opacity-50">
                                <span wire:loading.remove wire:target="suspendUser">Execute Protocol</span>
                                <span wire:loading wire:target="suspendUser">Processing...</span>
                            </button>
                        </div>
                    </form>
                @elseif($adminActionType === 'unsuspend')
                    <div class="px-8 py-8">
                        <p class="mb-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-loose">ARE YOU SURE YOU WANT TO RESTORE NETWORK ACCESS FOR THIS UNIT? ALL LOGGING AND IDENTITY PARAMETERS WILL BE REACTIVATED.</p>
                        <div class="flex justify-end gap-3 pt-6 border-t border-white/5">
                            <button 
                                type="button"
                                wire:click="closeAdminActionsModal"
                                class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">
                                Abort
                            </button>
                            <button 
                                type="button"
                                wire:click="unsuspendUser"
                                wire:loading.attr="disabled"
                                wire:target="unsuspendUser"
                                class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-900/20 disabled:opacity-50">
                                <span wire:loading.remove wire:target="unsuspendUser">Restore Identity</span>
                                <span wire:loading wire:target="unsuspendUser">Processing...</span>
                            </button>
                        </div>
                    </div>
                @elseif($adminActionType === 'delete')
                    <div class="px-8 py-8 bg-red-950/20">
                        <p class="mb-4 text-xs font-black text-red-500 uppercase tracking-widest italic">WARNING: PROTOCOL IS IRREVERSIBLE!</p>
                        <p class="mb-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-loose">CONFIRM PERMANENT PURGE OF ALL IDENTITY LOGS, MISSION DATA, AND NETWORK FOOTPRINT FOR THIS UNIT?</p>
                        <div class="flex justify-end gap-3 pt-6 border-t border-white/10">
                            <button 
                                type="button"
                                wire:click="closeAdminActionsModal"
                                class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">
                                Abort
                            </button>
                            <button 
                                type="button"
                                wire:click="deleteUserAsAdmin({{ $user->id }})"
                                wire:loading.attr="disabled"
                                wire:target="deleteUserAsAdmin"
                                class="px-8 py-2.5 bg-red-600 hover:bg-red-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-red-900/20 disabled:opacity-50">
                                <span wire:loading.remove wire:target="deleteUserAsAdmin">Confirm Purge</span>
                                <span wire:loading wire:target="deleteUserAsAdmin">Executing...</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
