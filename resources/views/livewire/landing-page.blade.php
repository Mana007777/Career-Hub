<!-- resources/views/livewire/landing-page.blade.php -->
<div class="relative min-h-screen bg-zinc-950 overflow-hidden font-sans selection:bg-emerald-500/30 selection:text-emerald-200" x-data="landingUI()">
    <!-- ANIMATED BACKGROUND ARCHITECTURE -->
    <div class="fixed inset-0 -z-10 bg-zinc-950">
        <!-- Digital Grid -->
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: linear-gradient(rgba(16,185,129,0.2) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,0.2) 1px, transparent 1px); background-size: 60px 60px;">
        </div>
        
        <!-- Bio-Luminescent Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] bg-emerald-500/10 rounded-full blur-[120px] animate-[pulse_8s_infinite]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-cyan-500/5 rounded-full blur-[150px] animate-[pulse_10s_infinite]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] bg-zinc-900/20 rounded-full blur-[180px]"></div>
    </div>

    <!-- MAIN INTERFACE -->
    <main class="relative min-h-screen flex items-center justify-center px-6 py-24 overflow-hidden">
        <div x-show="visible.hero" 
             x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-1000"
             x-transition:enter-start="opacity-0 translate-y-24 scale-90 blur-xl"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100 blur-0"
             class="text-center max-w-5xl mx-auto relative z-10">
            
            <!-- Operational Tag -->
            <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full border border-emerald-500/20 bg-emerald-500/5 mb-10 overflow-hidden group">
                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse ring-4 ring-emerald-500/20"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-emerald-500/80 group-hover:text-emerald-400 transition-colors">Career Intelligence System Online</span>
            </div>

            <!-- PRIMARY HEADLINE -->
            <div class="mb-12 relative">
                <h1 class="text-4xl md:text-7xl lg:text-8xl font-black tracking-tighter mb-8 text-white leading-[0.9] uppercase italic">
                    Career <span class="text-transparent bg-clip-text bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-500 animate-gradient">Hub</span> Protocols
                </h1>
                <p class="text-xl md:text-2xl text-zinc-400 font-medium max-w-2xl mx-auto leading-relaxed tracking-tight">
                    Optimize your professional trajectory through our high-performance <span class="text-emerald-400">Emerald-class</span> navigation matrix.
                </p>
            </div>

            <!-- ACTION ARRAY -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mt-16">
                <!-- Deployment Button -->
                <button
                    wire:click="getStarted"
                    class="group relative inline-flex items-center justify-center min-w-[240px] px-12 py-6 rounded-[1.5rem] bg-emerald-500 text-black overflow-hidden hover:bg-emerald-400 hover:shadow-[0_0_50px_rgba(16,185,129,0.3)] active:scale-95 transition-all duration-300">
                    <span class="relative z-10 flex items-center gap-4 text-xs font-black uppercase tracking-[0.2em]">
                        Initialize Hunt
                        <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
                </button>

                <!-- Knowledge Base Button -->
                <button
                    wire:click="aboutUs"
                    class="group relative inline-flex items-center justify-center min-w-[240px] px-12 py-6 rounded-[1.5rem] border border-zinc-800 bg-zinc-900/50 backdrop-blur-2xl text-zinc-400 overflow-hidden hover:text-white hover:border-zinc-600 transition-all duration-300 active:scale-95">
                    <span class="relative z-10 text-xs font-black uppercase tracking-[0.2em]">
                        Station Intel
                    </span>
                </button>
            </div>

            <!-- AUTH OVERRIDE -->
            @guest
            <div class="mt-12 opacity-60 hover:opacity-100 transition-opacity">
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest leading-relaxed">
                    Existing operative? 
                    <a href="{{ route('login') }}" class="text-white hover:text-emerald-400 underline decoration-emerald-500/20 underline-offset-8 transition-all">Establish session</a>
                </p>
            </div>
            @endguest

            <!-- STATUS INDICATORS -->
            <div class="mt-20 flex justify-center items-center gap-8">
                <div class="flex flex-col items-center gap-2">
                    <div class="h-10 w-px bg-gradient-to-b from-transparent via-emerald-500/50 to-transparent"></div>
                    <span class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.3em] vertical-rl">Node 01</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="h-10 w-px bg-gradient-to-b from-transparent via-cyan-500/50 to-transparent"></div>
                    <span class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.3em] vertical-rl">Node 02</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="h-10 w-px bg-gradient-to-b from-transparent via-teal-500/50 to-transparent"></div>
                    <span class="text-[8px] font-black text-zinc-600 uppercase tracking-[0.3em] vertical-rl">Node 03</span>
                </div>
            </div>
        </div>
    </main>

    <!-- FLOATING DATA PARTICLES -->
    <div class="absolute inset-0 pointer-events-none opacity-40">
        <template x-for="i in 30" :key="i">
            <div class="absolute bg-emerald-500/20 rounded-full"
                :style="`left: ${Math.random() * 100}%; top: ${Math.random() * 100}%; width: ${Math.random() * 3 + 1}px; height: ${Math.random() * 3 + 1}px; animation: float_particle ${Math.random() * 10 + 5}s linear infinite; animation-delay: -${Math.random() * 10}s;`">
            </div>
        </template>
    </div>

    <!-- STYLESHEET OVERRIDE -->
    <style>
        @keyframes float_particle {
            0% { transform: translateY(0) rotate(0deg); opacity: 0; }
            20% { opacity: 0.5; }
            80% { opacity: 0.5; }
            100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
        }

        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .animate-gradient {
            background-size: 200% auto;
            animation: gradient 5s ease infinite;
        }

        .vertical-rl {
            writing-mode: vertical-rl;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>

    <!-- REACTIVE CONTROL -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('landingUI', () => ({
                visible: { hero: false },
                init() {
                    setTimeout(() => { this.visible.hero = true; }, 100);
                }
            }))
        })
    </script>
</div>