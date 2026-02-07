<div 
    x-data
    x-on:open-report-modal.window="
        $wire.openModal(event.detail.targetType, event.detail.targetId);
    "
>
    @if($show)
    <div 
        class="fixed inset-0 z-50 overflow-y-auto"
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity dark:bg-gray-900 bg-gray-900 bg-opacity-75" wire:click="close"></div>

            <div class="inline-block align-bottom dark:bg-gray-900 bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-800 border-gray-200" wire:click.stop>
                    <div class="dark:bg-gray-900 bg-white px-6 py-4 border-b dark:border-gray-800 border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold dark:text-white text-gray-900">Report {{ ucfirst($targetType) }}</h3>
                            <button 
                                type="button"
                                wire:click="close"
                                class="dark:text-gray-400 text-gray-600 dark:hover:text-white hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="dark:bg-gray-900 bg-white px-6 py-4">
                        <p class="text-sm dark:text-gray-300 text-gray-700 mb-4">
                            Help us understand what's happening. Why are you reporting this {{ $targetType }}?
                        </p>

                        <form wire:submit.prevent="submit">
                            <div class="space-y-3 mb-4">
                                @foreach($availableReasons as $key => $label)
                                    <label class="flex items-center p-3 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg cursor-pointer hover:dark:bg-gray-700 hover:bg-gray-200 transition-colors">
                                        <input 
                                            type="radio" 
                                            wire:model="selectedReason" 
                                            value="{{ $key }}"
                                            class="w-4 h-4 dark:text-blue-600 text-blue-600 dark:bg-gray-700 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-3 text-sm dark:text-gray-300 text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @error('selectedReason')
                                <p class="text-sm text-red-600 dark:text-red-400 mb-3">{{ $message }}</p>
                            @enderror

                            @if($selectedReason === 'other')
                                <div class="mb-4">
                                    <label for="customReason" class="block text-sm font-medium dark:text-gray-300 text-gray-700 mb-2">
                                        Please provide more details
                                    </label>
                                    <textarea 
                                        wire:model="customReason"
                                        id="customReason"
                                        rows="3"
                                        class="w-full px-4 py-2 dark:bg-gray-800 bg-gray-100 border dark:border-gray-700 border-gray-300 rounded-lg dark:text-white text-gray-900 dark:placeholder-gray-500 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                        placeholder="Describe the issue..."></textarea>
                                    @error('customReason')
                                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div class="flex justify-end gap-3 mt-6">
                                <button 
                                    type="button"
                                    wire:click="close"
                                    class="px-4 py-2 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-white bg-gray-800 hover:bg-gray-900 rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    class="px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                    Submit Report
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
