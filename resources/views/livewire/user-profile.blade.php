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
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">Return to Command Center</span>
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
            class="bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 mb-12 shadow-[0_50px_100px_rgba(0,0,0,0.5)] backdrop-blur-3xl relative overflow-hidden group"
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
                <div class="flex-1 w-full">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <h1 class="text-4xl font-black text-white uppercase tracking-tighter italic selection:bg-emerald-500/30">{{ $user->username }}</h1>
                                @if($user->role)
                                    <span class="px-4 py-1.5 text-[9px] font-black uppercase tracking-[0.3em] rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                        {{ $user->role }}
                                    </span>
                                @endif
                                @if($user->isSuspended())
                                    <span class="px-4 py-1.5 text-[9px] font-black uppercase tracking-[0.3em] rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500">
                                        OFFLINE
                                    </span>
                                @endif
                            </div>
                            
                            @if($user->profile && $user->profile->bio)
                                <p class="text-zinc-400 text-sm leading-relaxed mb-8 max-w-2xl italic font-medium selection:bg-emerald-500/20 opacity-80 border-l-2 border-emerald-500/20 pl-6">{{ $user->profile->bio }}</p>
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
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em]">Logs</span>
                                        <p class="text-white text-2xl font-black italic">{{ $postsCount }}</p>
                                    </div>
                                    <button wire:click="openFollowersModal" class="text-left group flex flex-col gap-2">
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em] group-hover:text-emerald-500/50 transition-colors">Signal In</span>
                                        <p class="text-white text-2xl font-black italic group-hover:text-emerald-500 transition-colors">{{ $followersCount }}</p>
                                    </button>
                                    <button wire:click="openFollowingModal" class="text-left group flex flex-col gap-2">
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em] group-hover:text-emerald-500/50 transition-colors">Signal Out</span>
                                        <p class="text-white text-2xl font-black italic group-hover:text-emerald-500 transition-colors">{{ $followingCount }}</p>
                                    </button>
                                    <div class="flex flex-col gap-2">
                                        <span class="text-zinc-600 text-[8px] font-black uppercase tracking-[0.3em]">Integrity</span>
                                        <p class="text-white text-2xl font-black italic">{{ $endorsementCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4" x-data="{ openOptions: false }">
                            @if(Auth::check() && Auth::id() !== $user->id)
                                @if($isBlocked)
                                    <button wire:click="toggleBlock" class="px-8 py-3 bg-rose-500 text-black text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-rose-400 transition-all shadow-lg shadow-rose-900/20">Authorize Unit</button>
                                @else
                                    <button 
                                        wire:click="toggleFollow"
                                        class="px-10 py-4 rounded-2xl font-black uppercase tracking-[0.3em] text-[10px] transition-all duration-500 shadow-xl
                                            {{ $isFollowing ? 'bg-zinc-800 text-zinc-400 border border-zinc-700/50 hover:bg-zinc-700' : 'bg-emerald-500 text-black hover:bg-emerald-400 shadow-emerald-500/10 hover:shadow-emerald-500/20' }}">
                                        {{ $isFollowing ? 'Sever Signal' : 'Align Signal' }}
                                    </button>

                                    <div class="relative">
                                        <button @click="openOptions = !openOptions" class="w-12 h-12 rounded-2xl bg-zinc-800/50 border border-zinc-700/30 flex items-center justify-center text-zinc-500 hover:text-white hover:bg-zinc-700 transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 12h.01M12 12h.01M19 12h.01" /></svg>
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
                                                <span>Terminate Unit</span>
                                            </button>
                                            <button wire:click="openEndorseModal" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-emerald-400 hover:bg-emerald-500/10 rounded-2xl transition-all uppercase tracking-widest" @click="openOptions = false">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                                                <span>Validate Integrity</span>
                                            </button>
                                            @if(!Auth::user()->isAdmin())
                                                <button onclick="window.dispatchEvent(new CustomEvent('open-report-modal', { detail: { targetType: 'user', targetId: {{ $user->id }} } }))" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-amber-500 hover:bg-amber-500/10 rounded-2xl transition-all uppercase tracking-widest" @click="openOptions = false">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                    <span>Flag Discrepancy</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if(Auth::check() && Auth::id() === $user->id)
                                <a href="{{ route('profile.show') }}" class="px-8 py-3.5 bg-zinc-800 text-white text-[9px] font-black uppercase tracking-[0.3em] rounded-2xl border border-zinc-700/50 hover:bg-zinc-700 transition-all flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.232 5.232a3 3 0 014.243 4.243L9 19.95 4 21l1.05-5 10.182-10.768z" /></svg>
                                    Configure Node
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="px-8 py-3.5 bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-[0.3em] rounded-2xl border border-rose-500/20 hover:bg-rose-500/20 transition-all flex items-center gap-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l3 3m-3-3v12" /></svg>
                                        Disconnect
                                    </button>
                                </form>
                            @endif

                            @if(auth()->check() && auth()->user()->isAdmin() && auth()->id() !== $user->id)
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-3.5 bg-zinc-900 border border-amber-500/30 text-amber-500 rounded-2xl hover:bg-amber-500/10 transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-4 w-64 bg-zinc-950 border border-zinc-800 rounded-[2rem] shadow-3xl z-50 p-3">
                                        <button wire:click="{{ $user->isSuspended() ? 'openUnsuspendUserModal' : 'openSuspendUserModal' }}" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black {{ $user->isSuspended() ? 'text-emerald-500' : 'text-amber-500' }} hover:bg-zinc-800 rounded-2xl transition-all uppercase tracking-widest italic" @click="open = false">
                                            <span>{{ $user->isSuspended() ? 'RESURRECT IDENTITY' : 'SUSPEND IDENTITY' }}</span>
                                        </button>
                                        <button wire:click="openDeleteUserModal" class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-black text-rose-500 hover:bg-rose-500/10 rounded-2xl transition-all uppercase tracking-widest italic" @click="open = false text-rose-500">
                                            <span>PURGE DATA NODE</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission Stream -->
        @if($isBlocked)
            <div class="p-20 bg-zinc-900/40 border border-dashed border-rose-500/30 rounded-[3rem] text-center backdrop-blur-3xl relative">
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 px-6 py-2 bg-rose-500 text-black text-[10px] font-black uppercase tracking-[0.4em] rounded-full shadow-[0_0_20px_rgba(244,63,94,0.4)]">Communications Severed</div>
                <div class="w-24 h-24 bg-rose-500/10 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 border border-rose-500/20">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white uppercase tracking-tighter italic mb-4">Signal Lost</h3>
                <p class="text-[10px] font-black text-rose-500/60 uppercase tracking-[0.3em] leading-relaxed">This node has been blacklisted. Uplink protocols are disabled.</p>
            </div>
        @else
            <!-- Feed Matrix -->
            <div class="space-y-12">
                <h2 class="text-[11px] font-black text-zinc-500 uppercase tracking-[0.5em] flex items-center gap-4 px-4 italic">
                    <span class="w-4 h-px bg-zinc-800"></span>
                    Operational Chronology
                </h2>
                
                <div class="space-y-8">
                    @forelse ($posts as $index => $post)
                        @livewire('post-card', ['post' => $post, 'uniqueId' => 'profile-post-'.$post->id], key($post->id))
                    @empty
                        <div class="py-32 bg-zinc-900/20 border border-dashed border-zinc-800/50 rounded-[3rem] text-center group">
                            <div class="w-24 h-24 bg-zinc-950 border border-zinc-900/50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner group-hover:scale-110 transition-all duration-1000">
                                <svg class="w-10 h-10 text-zinc-800 group-hover:text-emerald-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-white italic uppercase tracking-tighter">Null Stream</h3>
                            <p class="mt-4 text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em]">Node has not initialized any public broadcasts.</p>
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
                    <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em] italic">{{ $modalType }} Node Mapping</h3>
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
                            <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.5em]">Zero connections indexed.</p>
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
                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.4em] italic">Integrity Validation</h3>
                <button wire:click="closeEndorseModal" class="w-10 h-10 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-rose-500 transition-all flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>

            <form wire:submit.prevent="endorseUser" class="p-8 space-y-8">
                @if(count($endorsableSkills) > 0)
                    <div class="space-y-4">
                        <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic">Established Core</label>
                        <select wire:model.live="selectedSkillToEndorse" class="w-full px-6 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-emerald-500/30 transition-all appearance-none cursor-pointer">
                            @foreach($endorsableSkills as $skill)
                                <option value="{{ $skill }}" class="bg-zinc-950">{{ $skill }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic">Initialize New Capability</label>
                    <input type="text" wire:model="customSkill" placeholder="SPECIFY TAG..." class="w-full px-6 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest placeholder:text-zinc-800 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <button type="button" wire:click="closeEndorseModal" class="px-8 py-3 text-[10px] font-black text-zinc-500 uppercase tracking-widest hover:text-white transition-colors">Abort</button>
                    <button type="submit" class="px-10 py-4 bg-emerald-500 text-black text-[10px] font-black rounded-2xl uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all">Confirm Data</button>
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
            <h3 class="text-2xl font-black text-white uppercase tracking-tighter italic mb-4">PURGE IDENTITY?</h3>
            <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-[0.2em] leading-relaxed mb-10">This will permanently delete all data linked to <span class="text-rose-500">{{ $user->username }}</span>. This action is IRREVERSIBLE.</p>
            <div class="flex gap-4">
                <button wire:click="closeDeleteUserModal" class="flex-1 py-4 text-[10px] font-black text-zinc-500 uppercase tracking-widest hover:text-white transition-all">ABORT</button>
                <button wire:click="deleteUser" class="flex-1 py-4 bg-rose-600 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest shadow-xl shadow-rose-900/30 hover:bg-rose-500">EXECUTE PURGE</button>
            </div>
        </div>
    </div>
@endif
