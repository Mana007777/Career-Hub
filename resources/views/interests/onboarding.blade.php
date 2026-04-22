<x-app-layout title="{{ __('Choose your interests') }}">
    <div class="max-w-3xl mx-auto px-4 py-10 text-zinc-100">
        <div class="rounded-3xl border border-zinc-800/70 bg-zinc-950/70 p-8">
            <h1 class="text-2xl font-bold mb-3">{{ __('Tell us what jobs interest you') }}</h1>
            <p class="text-zinc-400 mb-6">
                {{ __('Use comma-separated tags like: php, laravel, backend, remote. We will personalize your feed and recommendations based on these tags and your activity.') }}
            </p>

            <form method="POST" action="{{ route('interests.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="interests" class="block text-sm text-zinc-300 mb-2">{{ __('Interest tags') }}</label>
                    <textarea
                        id="interests"
                        name="interests"
                        rows="4"
                        class="w-full rounded-xl border border-zinc-700 bg-zinc-900 px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                        placeholder="php, django, devops, data science, ui-ux"
                    >{{ old('interests', implode(', ', $selectedTags ?? [])) }}</textarea>
                    @error('interests')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                @if(!empty($suggestedTags))
                    <div>
                        <p class="text-sm text-zinc-400 mb-2">{{ __('Popular tags you can use:') }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($suggestedTags as $tag)
                                <span class="px-3 py-1 rounded-full border border-zinc-700 text-xs text-zinc-300">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="submit" class="px-6 py-3 rounded-xl bg-emerald-500 text-black font-semibold hover:bg-emerald-400 transition">
                    {{ __('Save interests and continue') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
