<div 
    x-data
    x-on:open-report-modal.window="
        $wire.openModal(event.detail.targetType, event.detail.targetId);
    "
>
    @if($show)
    <div 
        class="fixed inset-0 z-[250] overflow-y-auto"
        x-data="{ show: true }"
        x-show="show"
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
        x-transition:enter-start="opacity-0 blur-xl"
        x-transition:enter-end="opacity-100 blur-0"
        x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
        x-transition:leave-start="opacity-100 blur-0"
        x-transition:leave-end="opacity-0 blur-xl"
    >
        <div class="flex items-center justify-center min-h-screen px-6 pt-4 pb-20 text-center">
            <div class="fixed inset-0 transition-opacity bg-zinc-950/95 backdrop-blur-2xl" wire:click="close"></div>

            <div class="inline-block align-bottom bg-zinc-900 border border-zinc-800 rounded-[3rem] text-left overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,1)] transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative" wire:click.stop>
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-rose-500/30 to-transparent"></div>
                
                <div class="px-10 py-8 border-b border-zinc-800/50 bg-zinc-950/40">
                    <div class="flex items-center justify-between">
                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.5em] italic">{{ __('Report Content') }}</h3>
                        <button 
                            type="button"
                            wire:click="close"
                            class="w-10 h-10 rounded-2xl bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-white transition-all flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-10 bg-zinc-900/50">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center border border-rose-500/20">
                            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-white uppercase tracking-widest italic">{{ __('Target Type') }}: {{ strtoupper(__($targetType)) }}</p>
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mt-1">{{ __('Select a reason for this report.') }}</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="submit" class="space-y-6">
                        <div class="space-y-3">
                            @foreach($availableReasons as $key => $label)
                                <label class="group flex items-center p-5 bg-zinc-950 border border-zinc-800 rounded-2xl cursor-pointer hover:bg-rose-500/5 hover:border-rose-500/30 transition-all">
                                    <div class="relative flex items-center justify-center">
                                        <input 
                                            type="radio" 
                                            wire:model="selectedReason" 
                                            value="{{ $key }}"
                                            class="peer appearance-none w-5 h-5 rounded-full border-2 border-zinc-800 checked:border-rose-500 transition-all cursor-pointer">
                                        <div class="absolute w-2.5 h-2.5 rounded-full bg-rose-500 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="ml-4 text-[10px] font-black text-zinc-400 group-hover:text-white uppercase tracking-widest transition-colors">{{ __($label) }}</span>
                                </label>
                            @endforeach
                        </div>

                        @error('selectedReason')
                            <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4">{{ $message }}</p>
                        @enderror

                        @if($selectedReason === 'other')
                            <div class="space-y-4 pt-4 border-t border-zinc-800/50">
                                <label for="customReason" class="block text-[9px] font-black text-zinc-600 uppercase tracking-[0.4em] italic pl-4">{{ __('Details') }}</label>
                                <textarea 
                                    wire:model="customReason"
                                    id="customReason"
                                    rows="4"
                                    class="w-full px-8 py-5 bg-zinc-950 border border-zinc-800 rounded-[2rem] text-white placeholder-zinc-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all text-[11px] font-medium resize-none shadow-inner"
                                    placeholder="{{ __('Write report details...') }}"></textarea>
                                @error('customReason')
                                    <p class="text-[8px] font-black text-rose-500 uppercase tracking-widest ml-4">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="flex flex-col gap-4 pt-8">
                            <button 
                                type="submit"
                                class="w-full py-5 bg-rose-600 text-white text-[10px] font-black rounded-2xl uppercase tracking-[0.4em] shadow-xl shadow-rose-900/30 hover:bg-rose-500 hover:scale-[1.02] active:scale-[0.98] transition-all italic">
                                {{ __('Submit Report') }}
                            </button>
                            <button 
                                type="button"
                                wire:click="close"
                                class="w-full py-4 text-[9px] font-black text-zinc-600 uppercase tracking-widest hover:text-white transition-colors">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
