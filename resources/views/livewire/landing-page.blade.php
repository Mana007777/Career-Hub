<!-- resources/views/livewire/landing-page.blade.php -->
<div>
    <div
        x-data="landingUI()"
        x-init="init()"
        @scroll.window="onScroll"
        class="min-h-screen text-white overflow-x-hidden relative bg-gradient-to-br from-gray-950 via-gray-900 to-gray-800"
    >

        <!-- ANIMATED BACKGROUND EFFECTS -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <!-- Brand Orbs in gray palette -->
            <div class="absolute -top-40 -left-40 w-[800px] h-[800px] bg-gray-900/40 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 4s;"></div>
            <div class="absolute top-1/2 -left-20 w-[600px] h-[600px] bg-gray-800/30 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 5s; animation-delay: 1s;"></div>

            <div class="absolute -top-20 -right-40 w-[700px] h-[700px] bg-gray-800/40 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 6s;"></div>
            <div class="absolute bottom-1/4 -right-20 w-[500px] h-[500px] bg-gray-700/35 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 4.5s; animation-delay: 0.5s;"></div>

            <!-- Mixed Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-950/40 via-gray-900/20 to-gray-800/40"></div>

            <!-- Animated Grid Pattern -->
            <div class="absolute inset-0 opacity-10"
                style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 50px 50px;">
            </div>
        </div>

        <!-- MAIN HERO SECTION -->
        <section class="relative min-h-screen flex items-center justify-center px-6 py-20">
            <div x-show="visible.hero" x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 translate-y-20 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                class="text-center max-w-6xl mx-auto relative z-10">
                <!-- TOP HEADLINE -->
                <div class="mb-8">
                    <h1 class="text-3xl md:text-6xl lg:text-6xl font-black tracking-tight mb-6 text-white">
                        <span>
                            KRD Career Opportunities
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-white font-light max-w-3xl mx-auto leading-relaxed">
                        Discover endless possibilities and unlock your potential with our cutting-edge platform
                    </p>
                </div>

                <!-- BUTTONS CONTAINER -->
                <div class="mt-16 flex flex-col sm:flex-row justify-center items-center gap-6">
                    <!-- Get Started Button with Arrow -->
                    <button
                        wire:click="getStarted"
                        @mouseenter="hovered = 'getStarted'"
                        @mouseleave="hovered = null"
                        class="group relative inline-flex items-center justify-center min-w-[220px] px-10 py-5 rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-700 hover:from-gray-800 hover:via-gray-700 hover:to-gray-900 transition-all duration-500 shadow-2xl shadow-gray-900/40 hover:shadow-gray-800/40 transform hover:-translate-y-1 overflow-hidden">
                        <!-- Animated Background -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-gray-700 via-gray-800 to-gray-900 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <!-- Button Content -->
                        <span class="relative flex items-center justify-center gap-3 text-lg font-semibold text-white">
                            Find Jobs
                            <svg class="w-6 h-6 transform group-hover:translate-x-2 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>

                        <!-- Shine Effect -->
                        <div
                            class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent">
                        </div>
                    </button>

                    <!-- About Us Button -->
                    <button
                        wire:click="aboutUs"
                        @mouseenter="hovered = 'aboutUs'"
                        @mouseleave="hovered = null"
                        class="group relative inline-flex items-center justify-center min-w-[220px] px-10 py-5 rounded-2xl bg-gray-800/60 backdrop-blur-xl border-2 border-gray-700 hover:border-gray-500 hover:bg-gray-700/80 transition-all duration-500 transform hover:-translate-y-1 overflow-hidden">
                        <!-- Glassmorphism Effect -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent"></div>

                        <!-- Button Content -->
                        <span class="relative text-lg font-semibold text-white">
                            About Us
                        </span>

                        <!-- Glow Effect -->
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-r from-gray-700/0 via-gray-800/0 to-gray-700/0 group-hover:from-gray-700/20 group-hover:via-gray-800/20 group-hover:to-gray-700/20 transition-all duration-500 blur-xl">
                        </div>
                    </button>
                </div>


                <!-- Decorative Elements -->
                <div class="mt-20 flex justify-center gap-4">
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-pulse"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-500 animate-pulse" style="animation-delay: 0.3s;"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-pulse" style="animation-delay: 0.6s;"></div>
                </div>

                @guest
                <div class="mt-10">
                    <p class="text-xl md:text-xl text-white font-light max-w-3xl mx-auto leading-relaxed text-center">
                        if you have already registered, please
                        <a href="{{ route('login') }}" class="text-white underline font-bold">login</a> to your account.
                    </p>
                </div>
                @endguest

            </div>

            <!-- Floating Particles -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <template x-for="i in 20" :key="i">
                    <div class="absolute rounded-full"
                        :style="`left: ${Math.random() * 100}%; top: ${Math.random() * 100}%; width: ${Math.random() * 4 + 2}px; height: ${Math.random() * 4 + 2}px; background: ${Math.random() > 0.5 ? 'rgba(75, 85, 99, 0.6)' : 'rgba(31, 41, 55, 0.6)'}; animation: float ${Math.random() * 3 + 2}s ease-in-out infinite; animation-delay: ${Math.random() * 2}s;`">
                    </div>
                </template>
            </div>
        </section>
    </div>

    <!-- Custom Styles -->
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) translateX(0);
                opacity: 0.5;
            }

            50% {
                transform: translateY(-20px) translateX(10px);
                opacity: 1;
            }
        }

        @keyframes gradient {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 3s ease infinite;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>

    <!-- Alpine Logic -->
    <script>
        function landingUI() {
            return {
                visible: {
                    hero: false,
                },
                hovered: null,
                init() {
                    this.visible.hero = true;
                }
            }
        }
    </script>
</div>