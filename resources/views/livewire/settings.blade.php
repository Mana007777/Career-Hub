<div class="min-h-screen bg-black text-white pb-24" style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
    <div class="max-w-4xl mx-auto px-4 py-8">
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
                class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-all duration-300 transform hover:translate-x-1 group">
                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-lg text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Settings Header -->
        <div 
            class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6 shadow-2xl"
            x-show="loaded"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        >
            <h1 class="text-3xl font-bold text-white mb-2">Settings</h1>
            <p class="text-gray-400">Manage your account settings and preferences</p>
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
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-gray-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Profile Information</h3>
                            <p class="text-sm text-gray-400">Edit your profile details and information</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openProfileModal"
                        class="px-6 py-2 rounded-lg font-medium transition-colors bg-blue-600 hover:bg-blue-700 text-white">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Blocked Users Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-gray-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-600/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Blocked Users</h3>
                            <p class="text-sm text-gray-400">Manage users you have blocked</p>
                        </div>
                    </div>
                    <button 
                        wire:click="openBlocksModal"
                        class="px-6 py-2 rounded-lg font-medium transition-colors bg-blue-600 hover:bg-blue-700 text-white">
                        View Blocked Users
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocked Users Modal -->
    @if($showBlocksModal)
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
        wire:click="closeBlocksModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-gray-900 border border-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden flex flex-col mx-4"
            wire:click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-800">
                <h2 class="text-2xl font-bold text-white">Blocked Users</h2>
                <button 
                    wire:click="closeBlocksModal"
                    class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
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
                            <div class="flex items-center justify-between p-4 bg-gray-800 border border-gray-700 rounded-lg hover:border-gray-600 transition-colors">
                                <div class="flex items-center gap-4 flex-1 min-w-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-[2px] flex-shrink-0">
                                        <div class="w-full h-full rounded-full bg-gray-900 flex items-center justify-center text-lg font-semibold text-gray-100">
                                            {{ strtoupper(substr($blockedUser->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-medium truncate">{{ $blockedUser->name ?? 'Unknown User' }}</p>
                                        @if(!empty($blockedUser->username))
                                            <p class="text-gray-400 text-sm truncate">{{ '@' . $blockedUser->username }}</p>
                                        @endif
                                    </div>
                                </div>
                                <button 
                                    wire:click="unblockUser({{ $blockedUser->id }})"
                                    wire:confirm="Are you sure you want to unblock this user?"
                                    class="px-4 py-2 rounded-lg font-medium transition-colors bg-blue-600 hover:bg-blue-700 text-white flex-shrink-0">
                                    Unblock
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center bg-gray-800 border border-gray-700 rounded-lg">
                        <svg class="w-12 h-12 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        <p class="text-gray-400">No blocked users</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Profile Edit Modal -->
    @if($showProfileModal)
    <div 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
        wire:click="closeProfileModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-gray-900 border border-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col mx-4"
            wire:click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-800">
                <h2 class="text-2xl font-bold text-white">Edit Profile</h2>
                <button 
                    wire:click="closeProfileModal"
                    class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
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
                            <label class="block text-sm font-medium text-gray-300 mb-2">Profile Photo</label>
                            
                            <!-- Current Profile Photo -->
                            <div class="mb-4" x-show="! photoPreview">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="rounded-full size-24 object-cover border-2 border-gray-700">
                            </div>

                            <!-- New Profile Photo Preview -->
                            <div class="mb-4" x-show="photoPreview" style="display: none;">
                                <span class="block rounded-full size-24 bg-cover bg-no-repeat bg-center border-2 border-gray-700"
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
                                    class="px-4 py-2 rounded-lg font-medium transition-colors bg-gray-800 hover:bg-gray-700 text-white border border-gray-700">
                                Select New Photo
                            </button>

                            @if (auth()->user()->profile_photo_path)
                                <button type="button" 
                                        wire:click="deleteProfilePhoto"
                                        class="ml-2 px-4 py-2 rounded-lg font-medium transition-colors bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-700/50">
                                    Remove Photo
                                </button>
                            @endif

                            @error('photo')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                        <input type="text" 
                               id="name" 
                               wire:model="name" 
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               wire:model="email" 
                               required
                               class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
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
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
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
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
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
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
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
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-800">
                        <button type="button" 
                                wire:click="closeProfileModal"
                                class="px-6 py-2 rounded-lg font-medium transition-colors bg-gray-800 hover:bg-gray-700 text-white border border-gray-700">
                            Cancel
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                wire:target="photo,updateProfile"
                                class="px-6 py-2 rounded-lg font-medium transition-colors bg-blue-600 hover:bg-blue-700 text-white">
                            <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                            <span wire:loading wire:target="updateProfile">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
