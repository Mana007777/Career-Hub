<!-- resources/views/livewire/landing-page.blade.php -->
<div class="relative min-h-screen overflow-hidden bg-zinc-50 font-sans selection:bg-emerald-500/25 selection:text-emerald-900 dark:bg-zinc-950 dark:selection:bg-emerald-500/30 dark:selection:text-emerald-200" x-data="landingUI()">
    <!-- ANIMATED BACKGROUND ARCHITECTURE -->
    <div class="fixed inset-0 -z-10 bg-zinc-50 dark:bg-zinc-950">
        <!-- Digital Grid -->
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: linear-gradient(rgba(16,185,129,0.2) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,0.2) 1px, transparent 1px); background-size: 60px 60px;">
        </div>
        
        <!-- Bio-Luminescent Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] bg-emerald-500/10 rounded-full blur-[120px] animate-[pulse_8s_infinite]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-cyan-500/5 rounded-full blur-[150px] animate-[pulse_10s_infinite]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] rounded-full bg-zinc-200/40 blur-[180px] dark:bg-zinc-900/20"></div>
    </div>

    <!-- MAIN INTERFACE -->
    <main class="relative min-h-screen flex items-center justify-center px-6 py-24 overflow-hidden">
        <div class="text-center max-w-5xl mx-auto relative z-10">
            
            <!-- Status Tag -->
            <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 mb-10 overflow-hidden group dark:border-emerald-500/20 dark:bg-emerald-500/5">
                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse ring-4 ring-emerald-500/20"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-emerald-500/80 group-hover:text-emerald-400 transition-colors">{{ __('Career Hub Is Live') }}</span>
            </div>

            <!-- PRIMARY HEADLINE -->
            <div class="mb-12 relative">
                <h1 class="text-4xl md:text-7xl lg:text-8xl font-black tracking-tighter mb-8 leading-[0.9] uppercase italic text-zinc-900 dark:text-white">
                    {{ __('Career') }} <span class="text-transparent bg-clip-text bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-500 animate-gradient">{{ __('Hub') }}</span>
                </h1>
                <p class="text-xl md:text-2xl font-medium max-w-2xl mx-auto leading-relaxed tracking-tight text-zinc-600 dark:text-zinc-400">
                    {{ __('Discover jobs, share your work, and grow your professional network in one place.') }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mt-16">
                <!-- Primary Action -->
                <button
                    wire:click="getStarted"
                    class="group relative inline-flex items-center justify-center min-w-[240px] px-12 py-6 rounded-[1.5rem] bg-emerald-500 text-black overflow-hidden hover:bg-emerald-400 hover:shadow-[0_0_50px_rgba(16,185,129,0.3)] active:scale-95 transition-all duration-300">
                    <span class="relative z-10 flex items-center gap-4 text-xs font-black uppercase tracking-[0.2em]">
                        {{ __('Get Started') }}
                        <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
                </button>

                <!-- Secondary Action -->
                <button
                    wire:click="aboutUs"
                    class="group relative inline-flex items-center justify-center min-w-[240px] px-12 py-6 rounded-[1.5rem] border border-zinc-200 bg-zinc-100/70 backdrop-blur-2xl text-zinc-600 overflow-hidden transition-all duration-300 hover:border-zinc-300 hover:text-zinc-900 active:scale-95 dark:border-zinc-800 dark:bg-zinc-900/50 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-white">
                    <span class="relative z-10 text-xs font-black uppercase tracking-[0.2em]">
                        {{ __('About Us') }}
                    </span>
                </button>
            </div>

            <!-- AUTH OVERRIDE -->
            @guest
            <div class="mt-12 opacity-60 hover:opacity-100 transition-opacity">
                <p class="text-xs font-bold uppercase tracking-widest leading-relaxed text-zinc-600 dark:text-zinc-500">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}" class="text-zinc-900 underline decoration-emerald-500/40 underline-offset-8 transition-all hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400 dark:decoration-emerald-500/20">{{ __('Sign in') }}</a>
                </p>
            </div>
            @endguest

            <!-- Quick Highlights -->
            <div class="mt-20 flex justify-center items-center gap-8">
                <div class="flex flex-col items-center gap-2">
                    <div class="h-10 w-px bg-gradient-to-b from-transparent via-emerald-500/50 to-transparent"></div>
                    <span class="text-[8px] font-black uppercase tracking-[0.3em] vertical-rl text-zinc-500 dark:text-zinc-600">{{ __('Jobs') }}</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="h-10 w-px bg-gradient-to-b from-transparent via-cyan-500/50 to-transparent"></div>
                    <span class="text-[8px] font-black uppercase tracking-[0.3em] vertical-rl text-zinc-500 dark:text-zinc-600">{{ __('Posts') }}</span>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="h-10 w-px bg-gradient-to-b from-transparent via-teal-500/50 to-transparent"></div>
                    <span class="text-[8px] font-black uppercase tracking-[0.3em] vertical-rl text-zinc-500 dark:text-zinc-600">{{ __('Network') }}</span>
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
            Alpine.data('landingUI', () => ({}));
        });
    </script>
</div>