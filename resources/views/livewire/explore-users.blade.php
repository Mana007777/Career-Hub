<div class="space-y-12 bg-transparent" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    <!-- Header -->
    <div 
        class="flex flex-col gap-4"
        x-show="loaded"
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
    >
        <div class="flex items-center gap-4">
            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter italic selection:bg-emerald-500/30">Personnel <span class="text-emerald-500">Archive</span></h1>
        </div>
        <p class="text-[11px] font-black text-zinc-500 uppercase tracking-[0.5em] italic pl-6">Global Index of Registered Personnel Nodes</p>
    </div>

    <!-- Scanner Protocols (Filters) -->
    <div 
        class="bg-zinc-950/60 border border-zinc-800/50 rounded-[2.5rem] p-10 backdrop-blur-3xl shadow-[0_30px_60px_rgba(0,0,0,0.3)] relative overflow-hidden group"
        x-show="loaded"
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000 delay-100"
        x-transition:enter-start="opacity-0 scale-95 blur-xl"
        x-transition:enter-end="opacity-100 scale-100 blur-0"
    >
        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Search Terminal -->
            <div class="space-y-4">
                <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">Signal Search</label>
                <div class="relative group/input">
                    <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-zinc-800 group-focus-within/input:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input 
                        type="text"
                        wire:model.live.debounce.300ms="query"
                        placeholder="SPECIFY IDENTITY..."
                        class="w-full pl-16 pr-6 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/30 transition-all text-[10px] font-black uppercase tracking-widest italic shadow-inner">
                </div>
            </div>

            <!-- Designation Cluster -->
            <div class="space-y-4">
                <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">Designation</label>
                <div class="relative group/select">
                    <select 
                        wire:model.live="role"
                        class="w-full px-8 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all appearance-none cursor-pointer italic">
                        @foreach($roleOptions as $value => $label)
                            <option value="{{ $value }}" class="bg-zinc-950">{{ strtoupper($label) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-zinc-800 group-focus-within/select:text-emerald-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>

            <!-- Sequence Priority -->
            <div class="space-y-4">
                <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">Sequence Priority</label>
                <div class="relative group/select">
                    <select 
                        wire:model.live="sort"
                        class="w-full px-8 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white text-[10px] font-black uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all appearance-none cursor-pointer italic">
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ $value }}" class="bg-zinc-950">{{ strtoupper($label) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-zinc-800 group-focus-within/select:text-emerald-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>
        </div>

        @if($query || $role || $sort !== 'newest')
            <div class="mt-8 pt-8 border-t border-zinc-800/50 flex justify-end">
                <button 
                    wire:click="clearFilters"
                    class="px-8 py-3 bg-zinc-950 border border-zinc-800 rounded-2xl text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-rose-500 hover:border-rose-500/30 transition-all italic">
                    Purge Filter Cache
                </button>
            </div>
        @endif
    </div>

    <!-- Identity Matrix (Grid) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach($users as $index => $user)
            <a 
                href="{{ route('user.profile', $user->username) }}"
                class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[2.5rem] p-8 transition-all duration-700 hover:border-emerald-500/30 hover:bg-emerald-500/[0.02] shadow-[0_20px_40px_rgba(0,0,0,0.2)] overflow-hidden"
                x-show="loaded"
                x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
                x-transition:enter-start="opacity-0 translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0"
                style="transition-delay: {{ $index * 50 }}ms"
            >
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                <div class="flex items-center gap-6">
                    <!-- Unit Avatar -->
                    <div class="w-16 h-16 rounded-[1.2rem] bg-zinc-950 border-2 border-zinc-800 overflow-hidden flex items-center justify-center p-0.5 shrink-0 group-hover:scale-105 group-hover:border-emerald-500/30 transition-all duration-700">
                        <div class="w-full h-full rounded-xl bg-zinc-900 flex items-center justify-center">
                            @if($user->profile_photo_path)
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-1000">
                            @else
                                <span class="text-xl font-black text-emerald-500/40 group-hover:text-emerald-500 transition-colors">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Unit Intel -->
                    <div class="min-w-0 flex-1">
                        <h3 class="text-[13px] font-black text-white group-hover:text-emerald-400 transition-colors uppercase tracking-tight italic truncate font-bold">{{ $user->name }}</h3>
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mt-1 opacity-80 decoration-emerald-500/50 italic">@ {{ $user->username }}</p>
                        @if(isset($user->followers_count))
                            <div class="flex items-center gap-2 mt-3">
                                <div class="w-1 h-1 rounded-full bg-emerald-500/50"></div>
                                <p class="text-[8px] font-black text-zinc-700 uppercase tracking-[0.2em]">{{ $user->followers_count }} Signals Synced</p>
                            </div>
                        @endif
                    </div>

                    <!-- Uplink Indicator -->
                    <div class="w-10 h-10 rounded-xl bg-zinc-950 border border-zinc-800 flex items-center justify-center text-zinc-800 group-hover:text-emerald-500 group-hover:border-emerald-500/30 transition-all duration-700">
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M14 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if($users->hasPages())
        <div class="pt-12">
            {{ $users->links() }}
        </div>
    @endif

    <!-- Null State -->
    @if($users->isEmpty())
        <div class="py-40 bg-zinc-900/20 border border-dashed border-zinc-800/50 rounded-[3.5rem] text-center group">
            <div class="w-24 h-24 bg-zinc-950 border border-zinc-800/50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner group-hover:scale-110 transition-all duration-1000">
                <svg class="w-12 h-12 text-zinc-800 group-hover:text-rose-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter italic">Identity Mismatch</h3>
            <p class="mt-4 text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em]">Zero personnel nodes matching the requested protocol.</p>
            <button 
                wire:click="clearFilters"
                class="mt-10 px-10 py-4 bg-emerald-500 text-black text-[10px] font-black rounded-2xl uppercase tracking-[0.3em] shadow-xl shadow-emerald-500/10 hover:bg-emerald-400 transition-all italic">
                Reset Detection Grid
            </button>
        </div>
    @endif
</div>
