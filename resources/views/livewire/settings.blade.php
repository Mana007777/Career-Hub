<div
    class="min-h-screen dark:bg-black bg-white dark:text-white text-gray-900 pb-24"
    style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);"
    x-data="{ loaded: false }"
    x-init="
        loaded = false;

        const setLoaded = () => { loaded = true };
        const setLoading = () => { loaded = false };

        document.addEventListener('livewire:load', setLoaded);
        document.addEventListener('livewire:navigated', setLoaded);
        document.addEventListener('livewire:navigating', setLoading);
    "
>
    <!-- Skeleton while settings are loading -->
    <div x-show="!loaded">
        <x-skeleton.page-cards />
    </div>

    <!-- Actual content -->
    <div class="max-w-4xl mx-auto px-4 py-8" x-show="loaded" x-cloak>
        <!-- Back Button -->
        <div 
            class="mb-6"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
        >
            <a 
                href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 transition-all duration-300 transform hover:translate-x-1 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 dark:bg-green-900/50 bg-green-50 border dark:border-green-700 border-green-200 rounded-lg dark:text-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 dark:bg-red-900/50 bg-red-50 border dark:border-red-700 border-red-200 rounded-lg dark:text-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <!-- Settings Header -->
        <div 
            class="dark:bg-gray-900 bg-gray-100 dark:border-gray-800 border-gray-300 rounded-xl p-6 mb-6 shadow-2xl"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        >
            <h1 class="text-3xl font-bold dark:text-white text-gray-900 mb-2">Settings</h1>
            <p class="dark:text-gray-400 text-gray-600">Manage your account settings and preferences</p>
        </div>

        <!-- Settings Options -->
        <div 
            class="space-y-4"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <!-- Profile Edit Card -->
            <div class="dark:bg-gray-900 bg-gray-100 dark:border-gray-800 border-gray-300 rounded-xl p-6 dark:hover:border-gray-700 hover:border-gray-400 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full dark:bg-blue-600/20 bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 dark:text-blue-400 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">Profile Information</h3>
                            <p class="text-sm dark:text-gray-400 text-gray-600">Edit your profile details and information</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openProfileModal"
                        class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Blocked Users Card -->
            <div class="dark:bg-gray-900 bg-gray-100 dark:border-gray-800 border-gray-300 rounded-xl p-6 dark:hover:border-gray-700 hover:border-gray-400 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-600/20 flex items-center justify-center">
                            <svg class="w-6 h-6 dark:text-red-400 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">Blocked Users</h3>
                            <p class="text-sm dark:text-gray-400 text-gray-600">Manage users you have blocked</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openBlocksModal"
                        class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white">
                        View Blocked Users
                    </button>
                </div>
            </div>

            <!-- Suspended Items Card (Only for admins) -->
            @if(auth()->check() && auth()->user()->isAdmin())
            <div class="dark:bg-gray-900 bg-gray-100 dark:border-gray-800 border-gray-300 rounded-xl p-6 dark:hover:border-gray-700 hover:border-gray-400 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-600/20 flex items-center justify-center">
                            <svg class="w-6 h-6 dark:text-orange-400 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">Suspended Items</h3>
                            <p class="text-sm dark:text-gray-400 text-gray-600">Manage suspended users and posts</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openSuspendedItemsModal"
                        class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white">
                        View Suspended Items
                    </button>
                </div>
            </div>
            @endif

            <!-- My Reports Card (Only for non-admin users) -->
            @if(auth()->check() && !auth()->user()->isAdmin())
            <div class="dark:bg-gray-900 bg-gray-100 dark:border-gray-800 border-gray-300 rounded-xl p-6 dark:hover:border-gray-700 hover:border-gray-400 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-600/20 flex items-center justify-center">
                            <svg class="w-6 h-6 dark:text-orange-400 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">My Reports</h3>
                            <p class="text-sm dark:text-gray-400 text-gray-600">View reports you have submitted</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openReportsModal"
                        class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white">
                        View Reports
                    </button>
                </div>
            </div>
            @endif

            <!-- Theme Preference Card -->
            <div class="dark:bg-gray-900 bg-gray-100 dark:border-gray-800 border-gray-300 rounded-xl p-6 dark:hover:border-gray-700 hover:border-gray-400 transition-colors" x-data="{ isOpen: false }">
                <div class="flex items-center justify-between mb-4 cursor-pointer" @click="isOpen = !isOpen">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center">
                            <svg class="w-6 h-6 dark:text-purple-400 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">Theme Preference</h3>
                            <p class="text-sm dark:text-gray-400 text-gray-600">Choose your preferred color theme</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 dark:text-gray-400 text-gray-600 transition-transform duration-200" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="space-y-3" 
                     x-show="isOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     style="display: none;">
                    <label class="flex items-center gap-3 p-4 dark:bg-gray-800 bg-white dark:border-gray-700 border-gray-300 rounded-lg cursor-pointer dark:hover:border-gray-600 hover:border-gray-400 transition-colors">
                        <input type="radio" 
                               wire:model="themePreference" 
                               wire:change="updateThemePreference"
                               value="light" 
                               class="w-4 h-4 text-blue-600 dark:bg-gray-700 bg-gray-200 dark:border-gray-600 border-gray-400 focus:ring-blue-500 focus:ring-2">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="dark:text-white text-gray-900 font-medium">Light Mode</span>
                            </div>
                            <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Use light theme</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 dark:bg-gray-800 bg-white dark:border-gray-700 border-gray-300 rounded-lg cursor-pointer dark:hover:border-gray-600 hover:border-gray-400 transition-colors">
                        <input type="radio" 
                               wire:model="themePreference" 
                               wire:change="updateThemePreference"
                               value="dark" 
                               class="w-4 h-4 text-blue-600 dark:bg-gray-700 bg-gray-200 dark:border-gray-600 border-gray-400 focus:ring-blue-500 focus:ring-2">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <span class="dark:text-white text-gray-900 font-medium">Dark Mode</span>
                            </div>
                            <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Use dark theme</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 dark:bg-gray-800 bg-white dark:border-gray-700 border-gray-300 rounded-lg cursor-pointer dark:hover:border-gray-600 hover:border-gray-400 transition-colors">
                        <input type="radio" 
                               wire:model="themePreference" 
                               wire:change="updateThemePreference"
                               value="system" 
                               class="w-4 h-4 text-blue-600 dark:bg-gray-700 bg-gray-200 dark:border-gray-600 border-gray-400 focus:ring-blue-500 focus:ring-2">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 dark:text-blue-400 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="dark:text-white text-gray-900 font-medium">System Default</span>
                            </div>
                            <p class="text-xs dark:text-gray-400 text-gray-600 mt-1">Follow your system preference</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocked Users Modal -->
    @if($showBlocksModal)
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center dark:bg-black/60 bg-black/60 backdrop-blur-sm"
        wire:click="closeBlocksModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden flex flex-col mx-4"
            wire:click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b dark:border-gray-800 border-gray-200">
                <h2 class="text-2xl font-bold dark:text-white text-gray-900">Blocked Users</h2>
                <button 
                    wire:click="closeBlocksModal"
                    class="p-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">
                @if($blockedUsers && count($blockedUsers) > 0)
                    <div class="space-y-3">
                        @foreach($blockedUsers as $blockedUser)
                            <div class="flex items-center justify-between p-4 dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg dark:hover:border-gray-600 hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px] flex-shrink-0">
                                        <div class="w-full h-full rounded-full dark:bg-gray-900 bg-gray-200 flex items-center justify-center text-lg font-semibold dark:text-gray-100 text-gray-900">
                                            {{ strtoupper(substr($blockedUser->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="dark:text-white text-gray-900 font-medium truncate">{{ $blockedUser->name ?? 'Unknown User' }}</p>
                                        @if(!empty($blockedUser->username))
                                            <p class="dark:text-gray-400 text-gray-600 text-sm truncate">{{ '@' . $blockedUser->username }}</p>
                                        @endif
                                    </div>
                                </div>
                                <button 
                                    wire:click="unblockUser({{ $blockedUser->id }})"
                                    wire:confirm="Are you sure you want to unblock this user?"
                                    class="px-4 py-2 rounded-lg font-medium transition-colors dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white flex-shrink-0">
                                    Unblock
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg">
                        <svg class="w-12 h-12 mx-auto dark:text-gray-600 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        <p class="dark:text-gray-400 text-gray-600">No blocked users</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Profile Edit Modal -->
    @if($showProfileModal)
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center dark:bg-black/60 bg-black/60 backdrop-blur-sm"
        wire:click="closeProfileModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col mx-4"
            wire:click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b dark:border-gray-800 border-gray-200">
                <h2 class="text-2xl font-bold dark:text-white text-gray-900">Edit Profile</h2>
                <button 
                    wire:click="closeProfileModal"
                    class="p-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <form wire:submit.prevent="updateProfile">
                    <!-- Profile Photo -->
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div x-data="{photoName: null, photoPreview: null}" class="mb-6">
                            <label class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Profile Photo</label>
                            
                            <!-- Current Profile Photo -->
                            <div class="mb-4" x-show="! photoPreview">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="rounded-full size-24 object-cover border-2 dark:border-gray-700 border-gray-300">
                            </div>

                            <!-- New Profile Photo Preview -->
                            <div class="mb-4" x-show="photoPreview" style="display: none;">
                                <span class="block rounded-full size-24 bg-cover bg-no-repeat bg-center border-2 dark:border-gray-700 border-gray-300"
                                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                            </div>

                            <input type="file" id="photo" class="hidden"
                                    wire:model.live="photo"
                                    x-ref="photo"
                                    accept="image/*"
                                    x-on:change="
                                            if ($refs.photo.files[0]) {
                                                photoName = $refs.photo.files[0].name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    photoPreview = e.target.result;
                                                };
                                                reader.readAsDataURL($refs.photo.files[0]);
                                            }
                                    " />

                            <button type="button" 
                                    x-on:click.prevent="$refs.photo.click()"
                                    class="px-4 py-2 rounded-lg font-medium transition-colors dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white border dark:border-gray-700 border-gray-700">
                                Select New Photo
                            </button>

                            @if (auth()->user()->profile_photo_path)
                                <button type="button" 
                                        wire:click="deleteProfilePhoto"
                                        class="ml-2 px-4 py-2 rounded-lg font-medium transition-colors dark:bg-red-600/20 bg-red-100 dark:hover:bg-red-600/30 hover:bg-red-200 dark:text-red-400 text-red-700 dark:border-red-700/50 border-red-300">
                                    Remove Photo
                                </button>
                            @endif

                            @error('photo')
                                <p class="mt-2 text-sm dark:text-red-400 text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">Name</label>
                        <input type="text" 
                               id="name" 
                               wire:model="name" 
                               required
                               class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-2 text-sm dark:text-red-400 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               wire:model="email" 
                               required
                               class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-2 text-sm dark:text-red-400 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-6">
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                        <input type="text" 
                               id="username" 
                               wire:model="username" 
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="username">
                        <p class="mt-1 text-xs text-gray-500">Only letters, numbers, and underscores allowed</p>
                        @error('username')
                            <p class="mt-2 text-sm dark:text-red-400 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div class="mb-6">
                        <label for="bio" class="block text-sm font-medium text-gray-300 mb-2">Bio</label>
                        <textarea id="bio" 
                                  wire:model="bio" 
                                  rows="4"
                                  class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Tell us about yourself..."></textarea>
                        @error('bio')
                            <p class="mt-2 text-sm dark:text-red-400 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-6">
                        <label for="location" class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                        <input type="text" 
                               id="location" 
                               wire:model="location" 
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="City, Country">
                        @error('location')
                            <p class="mt-2 text-sm dark:text-red-400 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div class="mb-6">
                        <label for="website" class="block text-sm font-medium text-gray-300 mb-2">Website</label>
                        <input type="url" 
                               id="website" 
                               wire:model="website" 
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://example.com">
                        @error('website')
                            <p class="mt-2 text-sm dark:text-red-400 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-800">
                        <button type="button" 
                                wire:click="closeProfileModal"
                                class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white border dark:border-gray-700 border-gray-700">
                            Cancel
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                wire:target="photo,updateProfile"
                                class="px-6 py-2 rounded-lg font-medium transition-colors dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white bg-gray-800 hover:bg-gray-900 text-white">
                            <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                            <span wire:loading wire:target="updateProfile">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- My Reports Modal (Only for non-admin users) -->
    @if($showReportsModal && auth()->check() && !auth()->user()->isAdmin())
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center dark:bg-black/60 bg-black/60 backdrop-blur-sm"
        wire:click="closeReportsModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col mx-4"
            wire:click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b dark:border-gray-800 border-gray-200">
                <h2 class="text-2xl font-bold dark:text-white text-gray-900">My Reports</h2>
                <button 
                    wire:click="closeReportsModal"
                    class="p-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">
                @if($reports->count() > 0)
                    <div class="space-y-4">
                        @foreach($reports as $report)
                            <div class="dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                @if($report->status === 'pending') dark:bg-yellow-600/20 bg-yellow-100 dark:text-yellow-400 text-yellow-700
                                                @elseif($report->status === 'resolved') dark:bg-green-600/20 bg-green-100 dark:text-green-400 text-green-700
                                                @else dark:bg-gray-600/20 bg-gray-100 dark:text-gray-400 text-gray-700
                                                @endif">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full dark:bg-blue-600/20 bg-blue-100 dark:text-blue-400 text-blue-700">
                                                {{ ucfirst($report->target_type) }}
                                            </span>
                                        </div>
                                        <p class="text-sm dark:text-gray-300 text-gray-700 mb-2">
                                            <span class="font-semibold">Reason:</span> {{ $report->reason }}
                                        </p>
                                        <p class="text-xs dark:text-gray-400 text-gray-600">
                                            Reported on {{ $report->created_at->format('M d, Y \a\t g:i A') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Target Details -->
                                <div class="mt-4 p-4 dark:bg-gray-900 bg-white rounded-lg border dark:border-gray-700 border-gray-200">
                                    @if($report->target_type === 'post' && $report->target)
                                        <div>
                                            <p class="text-sm font-semibold dark:text-white text-gray-900 mb-2">
                                                Post by: 
                                                <a href="{{ route('user.profile', $report->target->user->username ?? 'unknown') }}" class="dark:text-blue-400 text-blue-600 hover:underline">
                                                    {{ $report->target->user->name }}
                                                </a>
                                            </p>
                                            @if($report->target->title)
                                                <p class="text-sm font-medium dark:text-gray-200 text-gray-700 mb-1">{{ $report->target->title }}</p>
                                            @endif
                                            <p class="text-sm dark:text-gray-300 text-gray-600">{{ Str::limit($report->target->content, 200) }}</p>
                                            <a href="{{ route('posts.show', $report->target->slug) }}" target="_blank" class="text-xs dark:text-blue-400 text-blue-600 hover:underline mt-2 inline-block">
                                                View Post →
                                            </a>
                                        </div>
                                    @elseif($report->target_type === 'user' && $report->target)
                                        <div>
                                            <p class="text-sm font-semibold dark:text-white text-gray-900 mb-2">
                                                User: 
                                                <a href="{{ route('user.profile', $report->target->username ?? 'unknown') }}" class="dark:text-blue-400 text-blue-600 hover:underline">
                                                    {{ $report->target->name ?? 'Unknown User' }}@if(!empty($report->target->username)) ({{ '@' . $report->target->username }})@endif
                                                </a>
                                            </p>
                                            <p class="text-xs dark:text-gray-400 text-gray-600">{{ $report->target->email ?? 'N/A' }}</p>
                                        </div>
                                    @elseif($report->target_type === 'comment' && $report->target)
                                        <div>
                                            <p class="text-sm font-semibold dark:text-white text-gray-900 mb-2">
                                                Comment by: 
                                                <a href="{{ route('user.profile', $report->target->user->username ?? 'unknown') }}" class="dark:text-blue-400 text-blue-600 hover:underline">
                                                    {{ $report->target->user->name }}
                                                </a>
                                            </p>
                                            <p class="text-sm dark:text-gray-300 text-gray-600">{{ Str::limit($report->target->content, 200) }}</p>
                                        </div>
                                    @else
                                        <p class="text-sm dark:text-gray-400 text-gray-600">Target no longer available (may have been deleted)</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="p-8 text-center dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg">
                        <svg class="w-12 h-12 mx-auto dark:text-gray-600 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="dark:text-gray-400 text-gray-600">You haven't submitted any reports yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Suspended Items Modal (Only for admins) -->
    @if($showSuspendedItemsModal && auth()->check() && auth()->user()->isAdmin())
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center dark:bg-black/60 bg-black/60 backdrop-blur-sm"
        wire:click="closeSuspendedItemsModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col mx-4"
            wire:click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b dark:border-gray-800 border-gray-200">
                <h2 class="text-2xl font-bold dark:text-white text-gray-900">Suspended Items</h2>
                <button 
                    wire:click="closeSuspendedItemsModal"
                    class="p-2 dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Suspended Users -->
                    <div>
                        <h3 class="text-lg font-semibold dark:text-white text-gray-900 mb-4">Suspended Users ({{ $suspendedUsers->count() }})</h3>
                        @if($suspendedUsers->count() > 0)
                            <div class="space-y-3">
                                @foreach($suspendedUsers as $suspendedUser)
                                    <div class="dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <p class="font-semibold dark:text-white text-gray-900">{{ $suspendedUser->name }}</p>
                                                <p class="text-sm dark:text-gray-400 text-gray-600">{{ $suspendedUser->email }}</p>
                                                @if($suspendedUser->suspension)
                                                    <p class="text-xs dark:text-gray-500 text-gray-500 mt-1">
                                                        <span class="font-medium">Reason:</span> {{ $suspendedUser->suspension->reason }}
                                                    </p>
                                                    @if($suspendedUser->suspension->expires_at)
                                                        <p class="text-xs dark:text-gray-500 text-gray-500 mt-1">
                                                            <span class="font-medium">Expires:</span> {{ $suspendedUser->suspension->expires_at->format('M d, Y \a\t g:i A') }}
                                                        </p>
                                                    @else
                                                        <p class="text-xs dark:text-red-400 text-red-600 mt-1 font-medium">Permanent Suspension</p>
                                                    @endif
                                                @endif
                                            </div>
                                            <button 
                                                wire:click="unsuspendUser({{ $suspendedUser->id }})"
                                                wire:confirm="Are you sure you want to unsuspend this user?"
                                                class="px-4 py-2 rounded-lg font-medium transition-colors dark:bg-green-600 dark:hover:bg-green-700 dark:text-white bg-green-600 hover:bg-green-700 text-white text-sm">
                                                Unsuspend
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg">
                                <p class="dark:text-gray-400 text-gray-600">No suspended users</p>
                            </div>
                        @endif
                    </div>

                    <!-- Suspended Posts -->
                    <div>
                        <h3 class="text-lg font-semibold dark:text-white text-gray-900 mb-4">Suspended Posts ({{ $suspendedPosts->count() }})</h3>
                        @if($suspendedPosts->count() > 0)
                            <div class="space-y-3">
                                @foreach($suspendedPosts as $suspendedPost)
                                    <div class="dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <p class="font-semibold dark:text-white text-gray-900">
                                                    Post by: {{ $suspendedPost->user->name ?? 'Unknown' }}
                                                </p>
                                                <p class="text-sm dark:text-gray-400 text-gray-600 mt-1">
                                                    {{ Str::limit($suspendedPost->content ?? 'No content', 100) }}
                                                </p>
                                                @if($suspendedPost->suspension)
                                                    <p class="text-xs dark:text-gray-500 text-gray-500 mt-1">
                                                        <span class="font-medium">Reason:</span> {{ $suspendedPost->suspension->reason }}
                                                    </p>
                                                    @if($suspendedPost->suspension->expires_at)
                                                        <p class="text-xs dark:text-gray-500 text-gray-500 mt-1">
                                                            <span class="font-medium">Expires:</span> {{ $suspendedPost->suspension->expires_at->format('M d, Y \a\t g:i A') }}
                                                        </p>
                                                    @else
                                                        <p class="text-xs dark:text-red-400 text-red-600 mt-1 font-medium">Permanent Suspension</p>
                                                    @endif
                                                @endif
                                            </div>
                                            <button 
                                                wire:click="unsuspendPost({{ $suspendedPost->id }})"
                                                wire:confirm="Are you sure you want to unsuspend this post?"
                                                class="px-4 py-2 rounded-lg font-medium transition-colors dark:bg-green-600 dark:hover:bg-green-700 dark:text-white bg-green-600 hover:bg-green-700 text-white text-sm">
                                                Unsuspend
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center dark:bg-gray-800 bg-gray-50 border dark:border-gray-700 border-gray-200 rounded-lg">
                                <p class="dark:text-gray-400 text-gray-600">No suspended posts</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
