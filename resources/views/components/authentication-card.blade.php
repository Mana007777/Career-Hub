<div class="min-h-screen text-white overflow-x-hidden relative flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
    style="background: linear-gradient(135deg, #111827 0%, #1F2937 50%, #374151 100%);">

    <!-- ANIMATED BACKGROUND EFFECTS -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <!-- Brand Orbs in gray palette -->
        <div class="absolute -top-40 -left-40 w-[800px] h-[800px] bg-gray-900/40 rounded-full blur-3xl animate-pulse"
            style="animation-duration: 4s;"></div>
        <div class="absolute top-1/2 -left-20 w-[600px] h-[600px] bg-gray-800/30 rounded-full blur-3xl animate-pulse"
            style="animation-duration: 5s; animation-delay: 1s;"></div>

        <!-- Additional Orbs -->
        <div class="absolute -top-20 -right-40 w-[700px] h-[700px] bg-gray-700/40 rounded-full blur-3xl animate-pulse"
            style="animation-duration: 6s;"></div>
        <div class="absolute bottom-1/4 -right-20 w-[500px] h-[500px] bg-gray-700/35 rounded-full blur-3xl animate-pulse"
            style="animation-duration: 4.5s; animation-delay: 0.5s;"></div>

        <!-- Mixed Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/30 via-transparent to-gray-700/30"></div>

        <!-- Animated Grid Pattern -->
        <div class="absolute inset-0 opacity-10"
            style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 50px 50px;">
        </div>
    </div>

    <!-- Logo -->
    <div class="relative z-10 mb-4">
        {{ $logo }}
    </div>

    <!-- Form Card with Glassmorphism -->
    <div class="w-full sm:max-w-md mt-6 px-8 py-8 relative z-10 backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl auth-form-wrapper">
        {{ $slot }}
    </div>
</div>

<style>
    /* Override labels and inputs for auth forms with dark background */
    .auth-form-wrapper label {
        color: white !important;
    }
    
    .auth-form-wrapper input,
    .auth-form-wrapper select,
    .auth-form-wrapper textarea {
        background-color: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(4px);
        border-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    
    .auth-form-wrapper input::placeholder,
    .auth-form-wrapper textarea::placeholder,
    .auth-form-wrapper select option {
        color: rgba(156, 163, 175, 0.8) !important;
    }
    
    .auth-form-wrapper input:focus,
    .auth-form-wrapper select:focus,
    .auth-form-wrapper textarea:focus {
        border-color: #60a5fa !important;
        ring-color: #60a5fa !important;
        outline: none;
    }
    
    .auth-form-wrapper input:-webkit-autofill,
    .auth-form-wrapper input:-webkit-autofill:hover,
    .auth-form-wrapper input:-webkit-autofill:focus {
        -webkit-text-fill-color: white !important;
        -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.1) inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>
