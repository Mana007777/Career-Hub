<div
    class="min-h-screen bg-zinc-950 text-white pb-24 font-sans"
    style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);"
    x-data="{ loaded: false }"
    x-init="setTimeout(() => loaded = true, 50)"
>
    <!-- Skeleton -->
    <div x-show="!loaded">
        <x-skeleton.page-cards />
    </div>

    <!-- Actual content -->
    <div class="max-w-4xl mx-auto px-6 py-12" x-show="loaded" x-cloak>
        <!-- Back Button -->
        <div 
            class="mb-12"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-700"
            x-transition:enter-start="opacity-0 -translate-x-10"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <a 
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-4 text-emerald-500/70 hover:text-emerald-400 transition-all duration-500 group">
                <div class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800/50 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-black transition-all duration-500 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">Abort Database Session</span>
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-10 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-3xl text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] backdrop-blur-3xl animate-pulse flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center border border-emerald-500/20"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg></div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-10 p-6 bg-rose-500/10 border border-rose-500/20 rounded-3xl text-rose-400 text-[10px] font-black uppercase tracking-[0.3em] backdrop-blur-3xl flex items-center gap-4">
                <div class="w-10 h-10 bg-rose-500/10 rounded-xl flex items-center justify-center border border-rose-500/20"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Settings Header -->
        <div 
            class="mb-20 text-center"
            x-show="loaded"
            x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
            x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <div class="flex items-center gap-6 mb-6">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
                <h2 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.6em] italic">Node Config Protocols</h2>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent"></div>
            </div>
            <h1 class="text-6xl font-black text-white uppercase tracking-tighter italic italic">Advanced <span class="text-emerald-500">Settings</span></h1>
            <p class="text-zinc-500 text-sm mt-6 uppercase tracking-[0.2em] italic font-medium">Calibrate identity parameters and interface environment.</p>
        </div>

        <div class="mb-12">
            @livewire('profile.verification-payment')
        </div>

        <!-- Operational Matrix -->
        <div class="space-y-8">
            <!-- Profile Identity Card -->
            <div class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl hover:bg-emerald-500/[0.02] hover:border-emerald-500/30 transition-all duration-700 shadow-[0_30px_60px_rgba(0,0,0,0.3)]">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-[1.8rem] bg-zinc-950 border-2 border-zinc-800 flex items-center justify-center p-0.5 group-hover:scale-105 group-hover:border-emerald-500/30 transition-all duration-700">
                             <div class="w-full h-full rounded-[1.4rem] bg-zinc-900 flex items-center justify-center">
                                <svg class="w-8 h-8 text-emerald-500/40 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                             </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic mb-1">Personnel Identity</h3>
                            <p class="text-2xl font-black text-white uppercase tracking-tight italic">Biometric Calibration</p>
                        </div>
                    </div>
                    <button wire:click="openProfileModal" class="w-full md:w-auto px-10 py-5 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-emerald-500/10 hover:shadow-emerald-500/20 hover:bg-emerald-400 transition-all italic">Adjust Signal Bio</button>
                </div>
            </div>

            <!-- Blocked Users Card -->
            <div class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl hover:bg-rose-500/[0.02] hover:border-rose-500/30 transition-all duration-700 shadow-[0_30px_60px_rgba(0,0,0,0.3)]">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-rose-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-[1.8rem] bg-zinc-950 border-2 border-zinc-800 flex items-center justify-center p-0.5 group-hover:scale-105 group-hover:border-rose-500/30 transition-all duration-700">
                             <div class="w-full h-full rounded-[1.4rem] bg-zinc-900 flex items-center justify-center">
                                <svg class="w-8 h-8 text-rose-500/40 group-hover:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                             </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic mb-1">Containment Protocols</h3>
                            <p class="text-2xl font-black text-white uppercase tracking-tight italic">Blacklisted Entity Stream</p>
                        </div>
                    </div>
                    <button wire:click="openBlocksModal" class="w-full md:w-auto px-10 py-5 bg-zinc-950 border border-zinc-800 text-rose-500 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-rose-500 hover:text-white transition-all italic">Inspect Blacklist</button>
                </div>
            </div>

            <!-- Suspended Items (Admin) -->
            @if(auth()->check() && auth()->user()->isAdmin())
            <div class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl hover:bg-amber-500/[0.02] hover:border-amber-500/30 transition-all duration-700 shadow-[0_30px_60px_rgba(0,0,0,0.3)]">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-amber-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-[1.8rem] bg-zinc-950 border-2 border-zinc-800 flex items-center justify-center p-0.5 group-hover:scale-105 group-hover:border-amber-500/30 transition-all duration-700">
                             <div class="w-full h-full rounded-[1.4rem] bg-zinc-900 flex items-center justify-center">
                                <svg class="w-8 h-8 text-amber-500/40 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                             </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic mb-1">Administrative Hold</h3>
                            <p class="text-2xl font-black text-white uppercase tracking-tight italic">Quarantine Master Matrix</p>
                        </div>
                    </div>
                    <button wire:click="openSuspendedItemsModal" class="w-full md:w-auto px-10 py-5 bg-amber-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-amber-500/10 hover:shadow-amber-500/20 hover:bg-amber-400 transition-all italic">Oversee Quarantine</button>
                </div>
            </div>
            @endif

            <!-- My Reports (User) -->
            @if(auth()->check() && !auth()->user()->isAdmin())
            <div class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl hover:bg-emerald-500/[0.02] hover:border-emerald-500/30 transition-all duration-700 shadow-[0_30px_60px_rgba(0,0,0,0.3)]">
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-[1.8rem] bg-zinc-950 border-2 border-zinc-800 flex items-center justify-center p-0.5 group-hover:scale-105 group-hover:border-emerald-500/30 transition-all duration-700">
                             <div class="w-full h-full rounded-[1.4rem] bg-zinc-900 flex items-center justify-center">
                                <svg class="w-8 h-8 text-emerald-500/40 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                             </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic mb-1">Mission Intelligence</h3>
                            <p class="text-2xl font-black text-white uppercase tracking-tight italic">Reported Incident Logs</p>
                        </div>
                    </div>
                    <button wire:click="openReportsModal" class="w-full md:w-auto px-10 py-5 bg-zinc-950 border border-zinc-800 text-emerald-500 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-emerald-500 hover:text-black transition-all italic">Inspect Records</button>
                </div>
            </div>
            @endif

            <!-- Interface Environment -->
            <div class="group relative bg-zinc-900/40 border border-zinc-800/50 rounded-[3rem] p-10 backdrop-blur-3xl hover:bg-emerald-500/[0.02] transition-all duration-700" x-data="{ isOpen: false }">
                <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="flex items-center gap-8">
                        <div class="w-20 h-20 rounded-[1.8rem] bg-zinc-950 border-2 border-zinc-800 flex items-center justify-center p-0.5 group-hover:scale-105 group-hover:border-emerald-500/30 transition-all duration-700">
                             <div class="w-full h-full rounded-[1.4rem] bg-zinc-900 flex items-center justify-center">
                                <svg class="w-8 h-8 text-emerald-500/40 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                             </div>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] italic mb-1">Visual Environment</h3>
                            <p class="text-2xl font-black text-white uppercase tracking-tight italic">Interface Calibration</p>
                        </div>
                    </div>
                    <button @click="isOpen = !isOpen" class="w-full md:w-auto px-10 py-5 bg-zinc-950 border border-zinc-800 text-emerald-500/70 text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-zinc-800 hover:text-emerald-400 transition-all flex items-center justify-center gap-4 italic group/btn">
                        <span>Modify Mode</span>
                        <svg class="w-4 h-4 transition-transform duration-500" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 pt-12 border-t border-zinc-800/50" x-show="isOpen" x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display:none">
                    @foreach(['light' => ['Legacy', 'Surface Mode'], 'dark' => ['Active', 'Absolute Dark'], 'system' => ['Auto', 'Sync Protocol']] as $mode => $labels)
                        <label class="relative flex items-center gap-6 p-8 bg-zinc-950 border border-zinc-800 rounded-2xl hover:border-emerald-500/30 transition-all cursor-pointer group/mode {{ $themePreference === $mode ? 'border-emerald-500/50 ring-2 ring-emerald-500/10' : '' }}">
                            <div class="relative flex items-center justify-center">
                                <input type="radio" wire:model="themePreference" wire:change="updateThemePreference" value="{{ $mode }}" class="peer appearance-none w-6 h-6 rounded-full border-2 border-zinc-800 checked:border-emerald-500 transition-all cursor-pointer">
                                <div class="absolute w-3 h-3 rounded-full bg-emerald-500 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                            <div>
                                <span class="block text-[8px] font-black text-zinc-600 uppercase tracking-widest group-hover/mode:text-emerald-500 transition-colors">{{ $labels[0] }}</span>
                                <span class="block text-sm font-black text-white uppercase tracking-tight italic">{{ $labels[1] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Profile, Blocks, Reports, Suspended) -->
    @if($showProfileModal)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-zinc-950/98 backdrop-blur-3xl" wire:click="closeProfileModal">
            <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-[0_50px_100px_rgba(0,0,0,1)] relative" wire:click.stop>
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>
                <div class="flex items-center justify-between p-10 border-b border-zinc-800/50 bg-zinc-950/40">
                    <h2 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic">Identity Calibration Matrix</h2>
                    <button wire:click="closeProfileModal" class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-rose-500 transition-all flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto p-10 custom-scrollbar bg-zinc-900/50">
                    <form wire:submit.prevent="updateProfile" class="space-y-10">
                        <!-- Photo Cluster -->
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <div x-data="{photoName: null, photoPreview: null}" class="p-8 bg-zinc-950 border border-zinc-800 rounded-[2.5rem]">
                                <label class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic mb-6">Biometric Visual</label>
                                <div class="flex items-center gap-10">
                                    <div class="relative group/avatar">
                                        <div class="w-32 h-32 rounded-[2rem] overflow-hidden bg-zinc-900 border-4 border-zinc-800/50 flex-shrink-0" x-show="!photoPreview">
                                            <img src="{{ auth()->user()->profile_photo_url }}" class="w-full h-full object-cover grayscale opacity-50 transition-all duration-700">
                                        </div>
                                        <div class="w-32 h-32 rounded-[2rem] overflow-hidden bg-zinc-900 border-4 border-emerald-500/30 flex-shrink-0" x-show="photoPreview" style="display: none;">
                                            <span class="block w-full h-full bg-cover bg-no-repeat bg-center" x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-4">
                                        <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo" x-on:change="if($refs.photo.files[0]){ photoName=$refs.photo.files[0].name; const reader=new FileReader(); reader.onload=(e)=>{photoPreview=e.target.result;}; reader.readAsDataURL($refs.photo.files[0]); }">
                                        <button type="button" x-on:click.prevent="$refs.photo.click()" class="px-8 py-3 bg-zinc-800 text-white text-[9px] font-black uppercase tracking-widest rounded-xl border border-zinc-700 hover:bg-zinc-700 transition-all shadow-lg italic">Scan New Capture</button>
                                        @if (auth()->user()->profile_photo_path)
                                            <button type="button" wire:click="deleteProfilePhoto" class="px-8 py-3 bg-rose-500/10 text-rose-500 text-[9px] font-black uppercase tracking-widest rounded-xl border border-rose-500/20 hover:bg-rose-500/20 transition-all italic">Purge Capture</button>
                                        @endif
                                    </div>
                                </div>
                                @error('photo') <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest mt-4 ml-2">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <!-- Input Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach(['name' => 'Operational Name', 'email' => 'Primary Data Uplink', 'username' => 'Unique Designation', 'location' => 'Deployment Sector', 'website' => 'External Node'] as $field => $label)
                                @php $inputType = $field === 'email' ? 'email' : ($field === 'website' ? 'url' : 'text'); @endphp
                                <div class="space-y-3">
                                    <label for="{{ $field }}" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">{{ $label }}</label>
                                    <input type="{{ $inputType }}" id="{{ $field }}" wire:model="{{ $field }}" class="w-full px-6 py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all text-xs font-black uppercase tracking-widest italic shadow-inner">
                                    @error($field) <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4 mt-2">{{ $message }}</p> @enderror
                                </div>
                            @endforeach
                            <div class="col-span-full space-y-3">
                                <label for="bio" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">Mission Mandate (Bio)</label>
                                <textarea id="bio" wire:model="bio" rows="4" class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-[2rem] text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all text-[11px] font-medium resize-none italic shadow-inner font-bold"></textarea>
                                @error('bio') <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4 mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-6 pt-10 border-t border-zinc-800/50">
                            <button type="button" wire:click="closeProfileModal" class="text-[9px] font-black text-zinc-600 uppercase tracking-widest hover:text-white transition-colors">Abort</button>
                            <button type="submit" class="px-12 py-5 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-emerald-500/10 hover:bg-emerald-400 transition-all italic">Commit Calibration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Blocked Users Modal -->
    @if($showBlocksModal)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-zinc-950/98 backdrop-blur-3xl" wire:click="closeBlocksModal">
            <div class="bg-zinc-900 border border-rose-500/30 rounded-[3rem] shadow-[0_50px_100px_rgba(0,0,0,1)] w-full max-w-2xl max-h-[80vh] overflow-hidden flex flex-col relative" wire:click.stop>
                 <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-rose-500/30 to-transparent"></div>
                 <div class="p-10 border-b border-zinc-800/50 bg-zinc-950/40 flex items-center justify-between">
                    <h2 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic italic">Personnel Blacklist</h2>
                    <button wire:click="closeBlocksModal" class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-rose-500 transition-all flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg></button>
                 </div>
                 <div class="flex-1 overflow-y-auto p-10 custom-scrollbar bg-zinc-900/50">
                    @forelse($blockedUsers as $blockedUser)
                        <div class="flex items-center justify-between p-6 bg-zinc-950 border border-zinc-800/50 rounded-3xl mb-4 group hover:border-rose-500/30 transition-all duration-500">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 rounded-2xl bg-zinc-900 border border-zinc-800 overflow-hidden flex items-center justify-center grayscale opacity-40 group-hover:grayscale-0 group-hover:opacity-100 transition-all">
                                    <span class="text-[11px] font-black text-rose-500/40 uppercase">{{ substr($blockedUser->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="text-[13px] font-black text-white uppercase tracking-tight italic">{{ $blockedUser->name }}</h4>
                                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mt-1 italic">@ {{ $blockedUser->username }}</p>
                                </div>
                            </div>
                            <button wire:click="unblockUser({{ $blockedUser->id }})" class="px-8 py-3 bg-zinc-950 border border-rose-500/30 text-rose-500 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500 hover:text-white transition-all italic">Restore Access</button>
                        </div>
                    @empty
                         <div class="py-20 text-center">
                            <svg class="w-16 h-16 text-zinc-800 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            <p class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em]">Zero entities blacklisted.</p>
                         </div>
                    @endforelse
                 </div>
            </div>
        </div>
    @endif

    <!-- My Reports Modal (Non-Admin) -->
    @if($showReportsModal && auth()->check() && !auth()->user()->isAdmin())
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-zinc-950/98 backdrop-blur-3xl" wire:click="closeReportsModal">
            <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-[0_50px_100px_rgba(0,0,0,1)] relative" wire:click.stop>
                <div class="p-10 border-b border-zinc-800/50 bg-zinc-950/40 flex items-center justify-between">
                    <h2 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic italic">Transmission Archive</h2>
                    <button wire:click="closeReportsModal" class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-white transition-all flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto p-10 custom-scrollbar bg-zinc-900/50">
                    <div class="space-y-6">
                        @forelse($reports as $report)
                            <div class="bg-zinc-950 border border-zinc-800/50 rounded-[2.5rem] p-8 group hover:border-emerald-500/30 transition-all duration-700 shadow-xl">
                                <div class="flex items-center justify-between mb-8">
                                    <div class="flex items-center gap-6">
                                        <span class="px-6 py-2 bg-zinc-900 rounded-xl text-[8px] font-black uppercase tracking-[0.3em] font-bold border {{ $report->status === 'resolved' ? 'border-emerald-500/30 text-emerald-400' : 'border-amber-500/30 text-amber-500' }}">
                                            {{ $report->status }}
                                        </span>
                                        <span class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em] italic">{{ $report->created_at->format('Y.m.d // H:i') }}</span>
                                    </div>
                                    <span class="text-[8px] font-black text-emerald-500/40 uppercase tracking-[0.4em]">{{ $report->target_type }} Unit</span>
                                </div>
                                <div class="space-y-4">
                                    <p class="text-sm font-black text-white italic uppercase tracking-tight"><span class="text-emerald-500 opacity-50 mr-2">/</span>{{ $report->reason }}</p>
                                    <div class="bg-zinc-900/40 border border-zinc-800/50 rounded-2xl p-6 italic text-zinc-500 text-[11px] leading-relaxed font-bold font-medium selection:bg-emerald-500/20">
                                        @if($report->target_type === 'post' && $report->target)
                                            <p class="text-white mb-2 font-black italic">Log: {{ $report->target->title ?? 'Intelligence Data' }}</p>
                                            <p class="opacity-80">Source: @ {{ $report->target->user->username }}</p>
                                        @elseif($report->target_type === 'user' && $report->target)
                                            <p class="text-white mb-2 font-black italic">Identity: {{ $report->target->name }}</p>
                                            <p class="opacity-80">Designation: @ {{ $report->target->username }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center">
                                <p class="text-[10px] font-black text-zinc-700 uppercase tracking-[0.5em]">Zero incident transmissions recorded.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-10">{{ $reports->links() }}</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Suspended Items (Admin) - Updating visuals but keeping core logic -->
    @if($showSuspendedItemsModal && auth()->check() && auth()->user()->isAdmin())
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-zinc-950/98 backdrop-blur-3xl" wire:click="closeSuspendedItemsModal">
            <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col shadow-[0_50px_100px_rgba(0,0,0,1)]" wire:click.stop>
                <div class="p-10 border-b border-zinc-800/50 bg-zinc-950/40 flex items-center justify-between">
                    <h2 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic italic">Quarantine Master Index</h2>
                    <button wire:click="closeSuspendedItemsModal" class="w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-white transition-all flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto p-10 custom-scrollbar grid grid-cols-1 lg:grid-cols-2 gap-10 bg-zinc-900/50">
                    <!-- Suspended Nodes -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.5em] italic pl-4 mb-4">Identity Quarantines ({{ $suspendedUsers->count() }})</h3>
                        @forelse($suspendedUsers as $sUser)
                            <div class="bg-zinc-950 border border-amber-500/20 rounded-[2rem] p-8 group hover:border-amber-500/50 transition-all duration-700">
                                <div class="flex items-start justify-between gap-6">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-black text-white uppercase tracking-tight italic mb-1">{{ $sUser->name }}</h4>
                                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-4 italic">{{ $sUser->email }}</p>
                                        @if($sUser->suspension)
                                             <div class="bg-amber-500/5 border border-amber-500/10 rounded-xl p-4 mb-4">
                                                 <p class="text-[9px] font-black text-amber-500/70 uppercase tracking-widest mb-2 italic">Isolation Protocol: {{ $sUser->suspension->reason }}</p>
                                                 <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest italic italic">{{ $sUser->suspension->expires_at ? 'Isolation Expiry: ' . $sUser->suspension->expires_at->format('Y.m.d // H:i') : 'Permanent Exclusion' }}</p>
                                             </div>
                                        @endif
                                    </div>
                                    <button wire:click="unsuspendUser({{ $sUser->id }})" class="px-8 py-3 bg-amber-500 text-black text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-400 shadow-lg shadow-amber-500/10 transition-all italic italic font-bold">Restore Unit</button>
                                </div>
                            </div>
                        @empty
                             <div class="p-10 bg-zinc-950/50 rounded-[2rem] text-center border border-zinc-800/50">
                                <p class="text-[9px] font-black text-zinc-700 uppercase tracking-widest">Zero identity quarantines active.</p>
                             </div>
                        @endforelse
                    </div>

                    <!-- Suspended Intelligence -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.5em] italic pl-4 mb-4">Log Quarantines ({{ $suspendedPosts->count() }})</h3>
                        @forelse($suspendedPosts as $sPost)
                            <div class="bg-zinc-950 border border-amber-500/20 rounded-[2rem] p-8 group hover:border-amber-500/50 transition-all duration-700">
                                <div class="flex items-start justify-between gap-6">
                                    <div class="flex-1 min-w-0 text-bold">
                                        <h4 class="text-[11px] font-black text-zinc-500 uppercase tracking-widest mb-3 italic">Source: @ {{ $sPost->user->username ?? 'Unknown' }}</h4>
                                        <p class="text-sm font-black text-white italic uppercase tracking-tight italic mb-4 line-clamp-2">"{{ $sPost->content }}"</p>
                                        @if($sPost->suspension)
                                            <div class="bg-amber-500/5 border border-amber-500/10 rounded-xl p-4 mb-4">
                                                 <p class="text-[9px] font-black text-amber-500/70 uppercase tracking-widest mb-1 italic">Reason: {{ $sPost->suspension->reason }}</p>
                                                 <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest italic">{{ $sPost->suspension->expires_at ? 'Expiry: ' . $sPost->suspension->expires_at->format('Y.m.d // H:i') : 'Permanent Vault' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <button wire:click="unsuspendPost({{ $sPost->id }})" class="px-8 py-3 bg-amber-500 text-black text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-400 shadow-lg shadow-amber-500/10 transition-all italic font-bold">Resync Log</button>
                                </div>
                            </div>
                        @empty
                             <div class="p-10 bg-zinc-950/50 rounded-[2rem] text-center border border-zinc-800/50">
                                <p class="text-[9px] font-black text-zinc-700 uppercase tracking-widest">Zero log quarantines active.</p>
                             </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
