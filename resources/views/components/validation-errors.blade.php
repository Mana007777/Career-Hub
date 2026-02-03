@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-xl backdrop-blur-sm']) }}>
        <div class="font-medium text-red-300">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-200">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
