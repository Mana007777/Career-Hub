<div>
<div class="min-h-screen bg-transparent text-white pb-24" style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    <div class="max-w-4xl mx-auto px-6 py-12">
        <!-- Back Button -->
        <div 
            class="mb-12"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <button 
                onclick="window.history.back()"
                class="inline-flex items-center gap-4 text-emerald-500/70 hover:text-emerald-400 transition-all duration-500 group">
                <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800/50 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-black transition-all duration-500 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">{{ __('Back to Home') }}</span>
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-10 p-6 bg-zinc-900/50 border border-emerald-500/30 rounded-[2rem] text-emerald-500 text-[10px] font-black uppercase tracking-[0.3em] backdrop-blur-3xl animate-pulse flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center border border-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Profile Header Core -->
        <div 
            class="bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 mb-12 shadow-[0_50px_100px_rgba(0,0,0,0.5)] backdrop-blur-3xl relative group"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
            x-transition:enter-start="opacity-0 translate-y-20 blur-xl scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 blur-0 scale-100"
        >
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
            
            <div class="flex flex-col md:flex-row items-start md:items-center gap-10 relative z-10">
                <!-- Identity Module -->
                <div class="relative group/avatar">
                    <div class="w-40 h-40 rounded-[2.5rem] overflow-hidden bg-zinc-950 border-4 border-zinc-800/50 flex items-center justify-center text-5xl font-black text-emerald-500 shrink-0 shadow-2xl transition-all duration-700 group-hover/avatar:scale-105 group-hover/avatar:border-emerald-500/30 p-1">
                        <div class="w-full h-full rounded-[2rem] overflow-hidden bg-zinc-900 flex items-center justify-center">
                            @if($user->profile_photo_path)
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover grayscale opacity-70 group-hover/avatar:grayscale-0 group-hover/avatar:opacity-100 transition-all duration-1000">
                            @else
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                    </div>
                    @if($user->isActive())
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 border-[6px] border-zinc-900 rounded-full shadow-[0_0_20px_rgba(16,185,129,0.6)] animate-pulse"></div>
                    @endif
                </div>

                <!-- Data Stream -->
                <div class="flex-1 w-full min-w-0">
                    <div class="flex flex-col gap-8">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-4 mb-6">
                                <h1 class="text-4xl font-black text-white uppercase tracking-tighter italic selection:bg-emerald-500/30">{{ $user->username }}</h1>
                                @if($user->hasBlueTick())
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 text-[9px] font-black uppercase tracking-[0.3em] rounded-xl bg-emerald-500/15 border border-emerald-400/40 text-emerald-200 shadow-[0_0_22px_rgba(16,185,129,0.35)] animate-pulse">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 2l2.3 5.1L20 9l-4 4.1L17 19l-5-2.9L7 19l1-5.9L4 9l5.7-1.9L12 2z"/>
                                        </svg>
                                        Verified Badge
                                    </span>
                                @endif
                                @if($user->role)
                                    <span class="px-4 py-1.5 text-[9px] font-black uppercase tracking-[0.3em] rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                        {{ $user->role }}
                                    </span>
                                @endif
                                @if($user->isSuspended())
                                    <span class="px-4 py-1.5 text-[9px] font-black uppercase tracking-[0.3em] rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500">
                                        {{ __('OFFLINE') }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($user->profile && $user->profile->bio)
                                <p class="text-zinc-400 text-sm leading-relaxed mb-8 max-w-none md:max-w-2xl italic font-medium selection:bg-emerald-500/20 opacity-80 border-l-2 border-emerald-500/20 pl-6">{{ $user->profile->bio }}</p>
                            @endif

                            @if(collect($organizationMemberships)->isNotEmpty())
                                <div class="mb-8 p-5 rounded-2xl border border-emerald-500/20 bg-emerald-500/5">
                                    <p class="text-[8px] font-black uppercase tracking-[0.35em] text-emerald-400 mb-4">{{ __('Works at') }}</p>
                                    <div class="flex flex-wrap items-center gap-3">
                                        @foreach(collect($organizationMemberships)->take(4) as $orgCompany)
                                            <a
                                                href="{{ route('user.profile', $orgCompany->username) }}"
                                                class="inline-flex items-center gap-3 px-3 py-2 rounded-xl bg-zinc-900/70 border border-zinc-800/60 hover:border-emerald-500/30 transition-all"
                                            >
                                                <div class="w-8 h-8 rounded-lg overflow-hidden border border-zinc-700/60 bg-zinc-900 flex items-center justify-center">
                                                    @if($orgCompany->profile_photo_path)
                                                        <img src="{{ $orgCompany->profile_photo_url }}" alt="{{ $orgCompany->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-[10px] font-black text-emerald-500/70 uppercase">{{ strtoupper(substr($orgCompany->name ?? 'C', 0, 1)) }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-300">{{ $orgCompany->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center gap-8 mb-8 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600">
                                @if($user->profile && $user->profile->location)
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-emerald-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <span class="text-zinc-400">{{ $user->profile->location }}</span>
                                    </div>
                                @endif
                                @php 
                                    $websiteRecord = $user->profile && $user->profile->website ? $user->profile->website : null; 
                                    $websiteUrl = $websiteRecord ? (preg_match('~^[a-zA-Z]+://~', $websiteRecord) ? $websiteRecord : 'http://' . $websiteRecord) : '#';
                                    $websiteDisplay = $websiteRecord ? preg_replace('~^https?://~', '', $websiteRecord) : '';
                                @endphp
                                @if($websiteRecord)
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-emerald-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        <a href="{{ $websiteUrl }}" target="_blank" class="text-emerald-500/60 hover:text-emerald-400 transition-colors uppercase">{{ $websiteDisplay }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-x-12 gap-y-8">
                                <div class="flex items-center gap-12">
                                    <div class="flex flex-col gap-2">
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em]">{{ ($profileShowsReposts ?? false) ? __('Reposts') : __('Posts') }}</span>
                                        <p class="text-white text-2xl font-black italic">{{ $postsCount }}</p>
                                    </div>
                                    <button wire:click="openFollowersModal" class="text-left group flex flex-col gap-2">
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em] group-hover:text-emerald-500/50 transition-colors">{{ __('Followers') }}</span>
                                        <p class="text-white text-2xl font-black italic group-hover:text-emerald-500 transition-colors">{{ $followersCount }}</p>
                                    </button>
                                    <button wire:click="openFollowingModal" class="text-left group flex flex-col gap-2">
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em] group-hover:text-emerald-500/50 transition-colors">{{ __('Following') }}</span>
                                        <p class="text-white text-2xl font-black italic group-hover:text-emerald-500 transition-colors">{{ $followingCount }}</p>
                                    </button>
                                    <div class="flex flex-col gap-2">
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em]">{{ __('Endorsements') }}</span>
                                        <p class="text-white text-2xl font-black italic">{{ $endorsementCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 sm:gap-4 w-full" x-data="{ openOptions: false }">
                            @if(Auth::check() && Auth::id() !== $user->id)
                                @if($isBlocked)
                                    <button wire:click="toggleBlock" class="shrink-0 px-8 py-3 bg-rose-500 text-black text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-rose-400 transition-all shadow-lg shadow-rose-900/20">{{ __('Unblock User') }}</button>
                                @else
                                    <button 
                                        wire:click="toggleFollow"
                                        class="shrink-0 px-10 py-4 rounded-2xl font-black uppercase tracking-[0.3em] text-[10px] transition-all duration-500 shadow-xl
                                            {{ $isFollowing ? 'bg-zinc-800 text-zinc-400 border border-zinc-700/50 hover:bg-zinc-700' : 'bg-emerald-500 text-black hover:bg-emerald-400 shadow-emerald-500/10 hover:shadow-emerald-500/20' }}">
                                        {{ $isFollowing ? __('Unfollow') : ($isFollowedBy ? __('Follow Back') : __('Follow')) }}
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="openChat"
                                        class="shrink-0 w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500 hover:text-black transition-all flex items-center justify-center"
                                        title="{{ __('Chat') }}"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-8 6l2.5-2.5A3 3 0 0 1 9.62 17H18a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3v7a3 3 0 0 0 3 3h1.5L5 20z" />
                                        </svg>
                                    </button>

                                    <div class="relative shrink-0">
                                        <button @click="openOptions = !openOptions" class="w-12 h-12 rounded-2xl bg-zinc-800/50 border border-zinc-700/30 flex items-center justify-center text-zinc-300 hover:text-white hover:bg-zinc-700 transition-all">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <circle cx="12" cy="6" r="1.8"></circle>
                                                <circle cx="12" cy="12" r="1.8"></circle>
                                                <circle cx="12" cy="18" r="1.8"></circle>
                                            </svg>
                                        </button>

                                        <div 
                                            x-show="openOptions" 
                                            @click.away="openOptions = false"
                                            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-4"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="absolute right-0 mt-4 w-72 bg-zinc-900 border border-zinc-800 rounded-[2rem] shadow-[0_30px_60px_rgba(0,0,0,0.8)] z-50 backdrop-blur-3xl p-3"
                                        >
                                            <button wire:click="toggleBlock" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-rose-500 hover:bg-rose-500/10 rounded-2xl transition-all uppercase tracking-widest" @click="openOptions = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                <span>{{ __('Block User') }}</span>
                                            </button>
                                            <button wire:click="openEndorseModal" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-emerald-400 hover:bg-emerald-500/10 rounded-2xl transition-all uppercase tracking-widest" @click="openOptions = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                                                <span>{{ __('Endorse User') }}</span>
                                            </button>
                                            @if(!Auth::user()->isAdmin())
                                                <button onclick="window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'user', targetId: {{ $user->id }} } }))" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-amber-500 hover:bg-amber-500/10 rounded-2xl transition-all uppercase tracking-widest" @click="openOptions = false">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                    <span>{{ __('Report User') }}</span>
                                                </button>
                                            @endif
                                            @if(Auth::user()->isCompany() && ! $user->isCompany())
                                                @if($viewerCompanyAlreadyMember)
                                                    <div class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-emerald-400 bg-emerald-500/5 rounded-2xl uppercase tracking-widest">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                        <span>{{ __('Already in your company') }}</span>
                                                    </div>
                                                @elseif($pendingOrganizationInvitationId)
                                                    <div class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-amber-400 bg-amber-500/5 rounded-2xl uppercase tracking-widest">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                                                        <span>{{ __('Invitation pending') }}</span>
                                                    </div>
                                                @else
                                                    <button wire:click="inviteToOrganization" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-cyan-400 hover:bg-cyan-500/10 rounded-2xl transition-all uppercase tracking-widest" @click="openOptions = false">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8m-4-4v8M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" /></svg>
                                                        <span>{{ __('Invite to company') }}</span>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if(Auth::check() && Auth::id() === $user->id)
                                <a href="{{ route('profile.show') }}" class="shrink-0 px-8 py-3.5 bg-zinc-800 text-white text-[9px] font-black uppercase tracking-[0.3em] rounded-2xl border border-zinc-700/50 hover:bg-zinc-700 transition-all flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.232 5.232a3 3 0 014.243 4.243L9 19.95 4 21l1.05-5 10.182-10.768z" /></svg>
                                    {{ __('Edit Profile') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                                    @csrf
                                    <button type="submit" class="px-8 py-3.5 bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-[0.3em] rounded-2xl border border-rose-500/20 hover:bg-rose-500/20 transition-all flex items-center gap-3 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l3 3m-3-3v12" /></svg>
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            @endif

                            @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $user->id)
                                <div class="relative shrink-0" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-3.5 bg-zinc-900 border border-amber-500/30 text-amber-500 rounded-2xl hover:bg-amber-500/10 transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-4 w-64 bg-zinc-950 border border-zinc-800 rounded-[2rem] shadow-3xl z-50 p-3">
                                        <button wire:click="{{ $user->isSuspended() ? 'openUnsuspendUserModal' : 'openSuspendUserModal' }}" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black {{ $user->isSuspended() ? 'text-emerald-500' : 'text-amber-500' }} hover:bg-zinc-800 rounded-2xl transition-all uppercase tracking-widest italic" @click="open = false">
                                            <span>{{ $user->isSuspended() ? __('Unsuspend User') : __('Suspend User') }}</span>
                                        </button>
                                        <button wire:click="openDeleteUserModal" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-rose-500 hover:bg-rose-500/10 rounded-2xl transition-all uppercase tracking-widest italic" @click="open = false">
                                            <span>{{ __('Delete User') }}</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified Competencies (Endorsements) -->
        <div class="mb-12">
            <h2 class="text-[11px] font-black text-zinc-500 uppercase tracking-[0.5em] flex items-center gap-4 px-4 italic mb-6">
                <span class="w-4 h-px bg-zinc-800"></span>
                {{ __('Verified Competencies') }}
            </h2>
            @if(count($endorsementsBySkill) > 0)
                <div class="flex flex-wrap gap-4 px-2">
                    @foreach($endorsementsBySkill as $endorsementData)
                        @php
                            $skillName = data_get($endorsementData, 'skill');
                            $endorseCount = data_get($endorsementData, 'count');
                            $endorsersList = collect(data_get($endorsementData, 'endorsers', []));
                        @endphp
                        <div class="group relative h-9 pl-5 {{ Auth::check() && Auth::id() === $user->id ? 'pr-1.5' : 'pr-5' }} bg-zinc-900/40 border border-zinc-800/50 rounded-full flex items-center hover:bg-emerald-500/5 hover:border-emerald-500/30 transition-all shadow-md cursor-default">
                            <span class="text-[10px] font-black text-white group-hover:text-emerald-400 transition-colors uppercase tracking-widest whitespace-nowrap">{{ $skillName }}</span>
                            <div class="w-px h-3 bg-zinc-800 mx-3 group-hover:bg-emerald-500/30 transition-colors shrink-0"></div>
                            <span class="text-[10px] font-black text-emerald-500/80 shrink-0">{{ $endorseCount }}</span>
                            
                            <!-- Tooltip showing endorsers -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-max max-w-xs bg-zinc-950 border border-zinc-800/80 rounded-2xl p-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 shadow-2xl backdrop-blur-xl">
                                <div class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-3 border-b border-zinc-800/50 pb-2 italic">{{ __('Endorsed By') }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($endorsersList->take(5) as $endorser)
                                        @if($endorser)
                                            <div class="w-8 h-8 rounded-[0.4rem] bg-zinc-900 border border-zinc-800 overflow-hidden shadow-inner" title="{{ data_get($endorser, 'name', 'U') }}">
                                                @if(!empty(data_get($endorser, 'profile_photo_path')))
                                                    <img src="{{ data_get($endorser, 'profile_photo_url', '') }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-[10px] font-black text-emerald-500">{{ strtoupper(substr(data_get($endorser, 'name', 'U'), 0, 1)) }}</div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($endorsersList->count() > 5)
                                        <div class="w-8 h-8 rounded-[0.4rem] bg-zinc-900 border border-zinc-800 flex items-center justify-center text-[9px] font-black text-emerald-500/50">
                                            +{{ $endorsersList->count() - 5 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Remove Endorsement Button if owner -->
                            @if(Auth::check() && Auth::id() === $user->id)
                                <button wire:click.stop="removeEndorsement('{{ addslashes($skillName) }}')" class="ml-3 w-6 h-6 shrink-0 rounded-full flex items-center justify-center text-zinc-600 hover:text-rose-500 hover:bg-rose-500/10 hover:border-rose-500/10 transition-all opacity-0 group-hover:opacity-100" title="{{ __('Remove Endorsement') }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 py-10 rounded-[2rem] border border-dashed border-zinc-800/70 bg-zinc-900/20">
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.35em]">
                        {{ __('No endorsements yet.') }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Mission Stream -->
        @if($isBlocked)
            <div class="p-20 bg-zinc-900/40 border border-dashed border-rose-500/30 rounded-[3rem] text-center backdrop-blur-3xl relative">
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 px-6 py-2 bg-rose-500 text-black text-[10px] font-black uppercase tracking-[0.4em] rounded-full shadow-[0_0_20px_rgba(244,63,94,0.4)]">{{ __('User Blocked') }}</div>
                <div class="w-24 h-24 bg-rose-500/10 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 border border-rose-500/20">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white uppercase tracking-tighter italic mb-4">{{ __('Profile Unavailable') }}</h3>
                <p class="text-[10px] font-black text-rose-500/60 uppercase tracking-[0.3em] leading-relaxed">{{ __('This user is blocked. Unblock them to view posts and interact.') }}</p>
            </div>
        @else
            <!-- Feed Matrix -->
            <div class="space-y-12">
                <h2 class="text-[11px] font-black text-zinc-500 uppercase tracking-[0.5em] flex items-center gap-4 px-4 italic">
                    <span class="w-4 h-px bg-zinc-800"></span>
                    {{ ($profileShowsReposts ?? false) ? __('Reposted listings') : __('Recent Posts') }}
                </h2>
                
                <div class="space-y-8">
                    @forelse (($posts ?? collect()) as $index => $post)
                        
                        <article
                            onclick="window.location.href='{{ route('posts.show', $post->slug) }}'"
                            class="group relative h-full flex flex-col bg-zinc-950/60 border border-zinc-800/50 rounded-[2.5rem] p-8 transition-all duration-700 hover:border-emerald-500/30 hover:bg-emerald-500/[0.02] shadow-[0_30px_60px_rgba(0,0,0,0.3)] backdrop-blur-3xl cursor-pointer overflow-hidden"
                            style="transition-delay: {{ $index * 50 }}ms"
                        >
                            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                            <div class="flex items-start justify-between mb-8 relative z-10">
                                <div class="flex items-center gap-4 group/author">
                                    <div class="w-12 h-12 rounded-xl bg-zinc-950 border border-zinc-800 overflow-hidden flex items-center justify-center p-0.5 shrink-0 group-hover/author:border-emerald-500/30 transition-all duration-700">
                                        @if($post->user && $post->user->profile_photo_path)
                                            <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover grayscale opacity-50 group-hover/author:grayscale-0 group-hover/author:opacity-100 transition-all duration-1000">
                                        @else
                                            <span class="text-emerald-500/40 font-black text-xs">
                                                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-[11px] font-black text-zinc-400 group-hover/author:text-white transition-colors truncate uppercase tracking-widest">{{ $post->user->name ?? __('Unknown') }}</h3>
                                            @if($post->user && $post->user->hasBlueTick())
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/10 border border-emerald-400/30 text-emerald-300 shadow-[0_0_14px_rgba(16,185,129,0.35)] animate-pulse" title="Verified">
                                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path d="M12 2l2.3 5.1L20 9l-4 4.1L17 19l-5-2.9L7 19l1-5.9L4 9l5.7-1.9L12 2z"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[8px] font-black text-zinc-700 uppercase tracking-[0.2em] mt-1 italic">{{ \App\Support\SoraniTime::human($post->created_at) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-8 flex-1 relative z-10">
                                @if(!empty($post->title))
                                    <h2 class="text-lg font-black text-white mb-3 group-hover:text-emerald-400 transition-colors uppercase tracking-tight italic">
                                        {{ $post->title }}
                                    </h2>
                                @endif
                                <p class="text-zinc-500 text-sm leading-relaxed line-clamp-4 italic selection:bg-emerald-500/20 font-medium font-bold">
                                    {{ $post->content }}
                                </p>
                            </div>

                            @if ($post->media)
                                <div class="mb-8 rounded-2xl overflow-hidden border border-zinc-800 relative group/media">
                                    @php
                                        $mediaUrl = app(\App\Services\PostService::class)->getMediaUrl($post);
                                        $isImage = in_array(strtolower(pathinfo($post->media, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp
                                    @if($isImage)
                                        <img src="{{ $mediaUrl }}" alt="{{ __('Post media') }}" class="w-full h-40 object-cover grayscale opacity-50 group-hover/media:grayscale-0 group-hover/media:opacity-100 transition-all duration-1000">
                                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/80 to-transparent"></div>
                                    @else
                                        <div class="bg-zinc-950 py-6 px-8 flex items-center justify-between group-hover:bg-emerald-500/5 transition-colors duration-700">
                                            <div class="flex items-center gap-4">
                                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L13.732 14M5 18H13a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg></div>
                                                <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">{{ __('Multimedia Payload') }}</span>
                                            </div>
                                            <svg class="w-4 h-4 text-emerald-500/30 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-auto pt-8 border-t border-zinc-800/50 flex items-center justify-between relative z-10">
                                <div class="flex items-center gap-6">
                                    <div class="flex items-center gap-2 group/stat">
                                        <svg class="w-4 h-4 text-zinc-600 group-hover/stat:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                        <span class="text-[9px] font-black tracking-widest text-zinc-500">{{ $post->stars_count ?? $post->stars->count() }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 group/stat">
                                        <svg class="w-4 h-4 text-zinc-600 group-hover/stat:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                        <span class="text-[9px] font-black tracking-widest text-zinc-500">{{ $post->comments_count ?? $post->comments->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="py-32 bg-zinc-900/20 border border-dashed border-zinc-800/50 rounded-[3rem] text-center group">
                            <div class="w-24 h-24 bg-zinc-950 border border-zinc-900/50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner group-hover:scale-110 transition-all duration-1000">
                                <svg class="w-10 h-10 text-zinc-800 group-hover:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-white italic uppercase tracking-tighter">{{ ($profileShowsReposts ?? false) ? __('No reposts yet') : __('No Posts Yet') }}</h3>
                            <p class="mt-4 text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em]">{{ ($profileShowsReposts ?? false) ? __('This user has not reposted any company listings yet.') : __('This user has not posted anything yet.') }}</p>
                        </div>
                    @endforelse
                </div>

                @if($posts && $posts->hasPages())
                    <div class="pt-12 px-2">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@livewire('report-modal')

<!-- Modal Overlays (Followers/Following/Endorse/Admin) -->
@foreach(['Followers', 'Following'] as $modalType)
    @php $showModalVar = "show{$modalType}Modal"; $closeModalSub = "close{$modalType}Modal"; $itemsVar = strtolower($modalType); @endphp
    @if($$showModalVar)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-zinc-950/98 backdrop-blur-2xl" wire:click="{{ $closeModalSub }}">
            <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] max-w-lg w-full max-h-[85vh] overflow-hidden flex flex-col shadow-[0_50px_100px_rgba(0,0,0,1)]" wire:click.stop x-data>
                <div class="flex items-center justify-between p-8 border-b border-zinc-800/50 bg-zinc-950/40">
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em] italic">{{ __($modalType) }} {{ __('List') }}</h3>
                    <button wire:click="{{ $closeModalSub }}" class="w-10 h-10 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-rose-500 hover:bg-rose-500/10 transition-all flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-y-auto flex-1 p-8 space-y-4 custom-scrollbar">
                    @forelse($user->$itemsVar as $itemUser)
                        @php $u = ($itemsVar === 'followers' ? $itemUser : $itemUser); @endphp
                        <a href="{{ route('user.profile', $u->username ?? 'unknown') }}" class="flex items-center gap-5 px-6 py-5 rounded-3xl bg-zinc-950/60 border border-zinc-800/50 hover:bg-emerald-500/5 hover:border-emerald-500/30 transition-all group">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden bg-zinc-900 border border-zinc-800 flex items-center justify-center shrink-0 p-0.5 group-hover:scale-105 transition-all">
                                <div class="w-full h-full rounded-xl overflow-hidden">
                                    @if($u->profile_photo_path)
                                        <img src="{{ $u->profile_photo_url }}" class="w-full h-full object-cover grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[10px] font-black text-emerald-500/40 group-hover:text-emerald-500">{{ substr($u->name, 0, 1) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-black text-white uppercase tracking-widest italic group-hover:text-emerald-400 transition-colors">{{ $u->name }}</p>
                                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mt-1 opacity-80 decoration-emerald-500/50">{{ '@' . $u->username }}</p>
                                @if($u->profile && $u->profile->bio)
                                    <p class="text-[10px] text-zinc-500 truncate mt-2 italic">{{ $u->profile->bio }}</p>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-20">
                            <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.5em]">{{ __('Zero connections indexed.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- Endorse Modal -->
@if($showEndorseModal)
    <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-zinc-950/98 backdrop-blur-2xl" wire:click="closeEndorseModal">
        <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] max-w-md w-full shadow-[0_50px_100px_rgba(0,0,0,1)]" wire:click.stop>
            <div class="flex items-center justify-between p-8 border-b border-zinc-800/50 bg-zinc-950/40">
                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em] italic">{{ __('Endorse User') }}</h3>
                <button wire:click="closeEndorseModal" class="w-10 h-10 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-rose-500 transition-all flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <form wire:submit.prevent="endorseUser" class="p-8 space-y-8">
                @if(count($endorsableSkills) > 0)
                    <div class="space-y-4">
                        <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic">{{ __('Existing Skills') }}</label>
                        <select wire:model.live="selectedSkillToEndorse" class="w-full px-6 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-emerald-500/30 transition-all appearance-none cursor-pointer">
                            @foreach($endorsableSkills as $skill)
                                <option value="{{ $skill }}" class="bg-zinc-950">{{ $skill }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic">{{ __('Add Custom Skill') }}</label>
                    <input type="text" wire:model="customSkill" placeholder="{{ __('Type a skill...') }}" class="w-full px-6 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest placeholder:text-zinc-800 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" wire:click="closeEndorseModal" class="px-8 py-3 text-[10px] font-black text-zinc-500 uppercase tracking-widest hover:text-white transition-colors">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-10 py-4 bg-emerald-500 text-black text-[10px] font-black rounded-2xl uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all">{{ __('Submit Endorsement') }}</button>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- Admin Modals (Purge/Suspend) - Keeping logic but updating styling -->
@if($showDeleteUserModal)
    <div class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-rose-950/20 backdrop-blur-3xl" wire:click="closeDeleteUserModal">
        <div class="bg-zinc-950 border border-rose-500/30 rounded-[3rem] max-w-md w-full p-10 text-center shadow-[0_0_100px_rgba(244,63,94,0.1)]" wire:click.stop>
            <div class="w-20 h-20 bg-rose-500/10 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 border border-rose-500/20"><svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
            <h3 class="text-2xl font-black text-white uppercase tracking-tighter italic mb-4">{{ __('Delete User?') }}</h3>
            <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-[0.2em] leading-relaxed mb-10">{{ __('This will permanently delete all data linked to') }} <span class="text-rose-500">{{ $user->username }}</span>. {{ __('This action is IRREVERSIBLE.') }}</p>
            <div class="flex gap-4">
                <button wire:click="closeDeleteUserModal" class="flex-1 py-4 text-[10px] font-black text-zinc-500 uppercase tracking-widest hover:text-white transition-all">{{ __('Cancel') }}</button>
                <button wire:click="deleteUser" class="flex-1 py-4 bg-rose-600 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest shadow-xl shadow-rose-900/30 hover:bg-rose-500">{{ __('Delete User') }}</button>
            </div>
        </div>
    </div>
@endif

@if($showAdminActionsModal)
    <div class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-zinc-950/98 backdrop-blur-3xl" wire:click="closeAdminActionsModal">
        <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] max-w-lg w-full shadow-[0_0_100px_rgba(0,0,0,0.35)] overflow-hidden" wire:click.stop>
            <div class="px-10 py-8 border-b border-zinc-800/50 bg-zinc-950/40">
                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic">
                    @if($adminActionType === 'suspend')
                        {{ __('Suspend User') }}
                    @elseif($adminActionType === 'unsuspend')
                        {{ __('Unsuspend User') }}
                    @endif
                </h3>
            </div>

            @if($adminActionType === 'suspend')
                <form wire:submit.prevent="handleAdminAction" class="px-10 py-10 space-y-8">
                    <div class="space-y-4">
                        <label for="suspendReason" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">
                            {{ __('Reason') }} *
                        </label>
                        <textarea
                            id="suspendReason"
                            wire:model="suspendReason"
                            rows="3"
                            class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-3xl text-sm text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all italic font-bold"
                            placeholder="{{ __('Provide suspension reason...') }}"
                        ></textarea>
                        @error('suspendReason')
                            <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <label for="suspendExpiresAt" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">
                            {{ __('Expiry date (Optional)') }}
                        </label>
                        <input
                            id="suspendExpiresAt"
                            type="datetime-local"
                            wire:model="suspendExpiresAt"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full px-8 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-xs text-white uppercase focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all"
                        >
                        @error('suspendExpiresAt')
                            <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-6 pt-6 border-t border-zinc-800/50">
                        <button type="button" wire:click="closeAdminActionsModal" class="text-[9px] font-black text-zinc-600 uppercase tracking-widest hover:text-white transition-colors italic">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-10 py-4 bg-amber-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-xl shadow-xl shadow-amber-500/10 hover:bg-amber-400 transition-all italic">
                            {{ __('Suspend') }}
                        </button>
                    </div>
                </form>
            @elseif($adminActionType === 'unsuspend')
                <div class="px-10 py-10 space-y-8">
                    <p class="text-zinc-400 text-xs font-bold tracking-widest leading-relaxed uppercase italic">
                        {{ __('Restore this user account now?') }}
                    </p>
                    <div class="flex justify-end gap-6 pt-6 border-t border-zinc-800/50">
                        <button type="button" wire:click="closeAdminActionsModal" class="text-[9px] font-black text-zinc-600 uppercase tracking-widest hover:text-white transition-colors italic">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" wire:click="handleAdminAction" class="px-10 py-4 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-xl shadow-xl shadow-emerald-500/10 hover:bg-emerald-400 transition-all italic">
                            {{ __('Unsuspend') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
</div>
