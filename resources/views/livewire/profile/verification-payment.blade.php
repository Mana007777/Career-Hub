<div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 overflow-hidden shadow-xl sm:rounded-lg mb-10">
    <div class="p-6 sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold dark:text-white text-gray-900">Blue Tick Verification</h3>
                <p class="mt-1 text-sm dark:text-gray-300 text-gray-600">
                    Buy verification to get the blue tick badge on your profile.
                </p>
            </div>

            <div class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border
                {{ $verification?->payment_status === \FirstIraqiBank\FIBPaymentSDK\Model\FibPayment::PAID
                    ? 'bg-green-100 text-green-700 border-green-300 dark:bg-green-900/20 dark:text-green-300 dark:border-green-700/50'
                    : 'bg-blue-100 text-blue-700 border-blue-300 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-700/50' }}">
                {{ $verification?->payment_status === \FirstIraqiBank\FIBPaymentSDK\Model\FibPayment::PAID ? 'Paid' : 'Not Paid' }}
            </div>
        </div>

        @if (! $canUsePayments)
            <div class="mt-4 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800 dark:border-yellow-700/60 dark:bg-yellow-900/20 dark:text-yellow-200">
                Payment setup is not ready yet. Run migrations first, then refresh this page.
            </div>
        @else
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-lg border dark:border-gray-700 border-gray-200 p-4">
                    <p class="text-xs uppercase tracking-wide dark:text-gray-400 text-gray-500">Price</p>
                    <p class="mt-1 text-lg font-semibold dark:text-white text-gray-900">{{ number_format($amount) }} IQD</p>
                </div>
                <div class="rounded-lg border dark:border-gray-700 border-gray-200 p-4">
                    <p class="text-xs uppercase tracking-wide dark:text-gray-400 text-gray-500">Verification Type</p>
                    <p class="mt-1 text-lg font-semibold dark:text-white text-gray-900">{{ ucfirst($verification?->type ?? 'identity') }}</p>
                </div>
                <div class="rounded-lg border dark:border-gray-700 border-gray-200 p-4">
                    <p class="text-xs uppercase tracking-wide dark:text-gray-400 text-gray-500">Current Status</p>
                    <p class="mt-1 text-lg font-semibold dark:text-white text-gray-900">{{ $verification?->payment_status ?? 'Not started' }}</p>
                </div>
            </div>

            @if ($paymentLink)
                <div class="mt-6 rounded-lg border dark:border-blue-700/40 border-blue-300 bg-blue-50 dark:bg-blue-900/20 p-4">
                    <p class="text-sm font-medium dark:text-blue-200 text-blue-800">Payment is ready</p>
                    @if ($readableCode)
                        <p class="mt-1 text-sm dark:text-blue-100 text-blue-700">
                            Code: <span class="font-semibold">{{ $readableCode }}</span>
                        </p>
                    @endif
                    <a href="{{ $paymentLink }}" target="_blank"
                       class="mt-3 inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                        Open FIB Payment Link
                    </a>
                </div>
            @endif

            @if ($errorMessage)
                <div class="mt-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-700/60 dark:bg-red-900/20 dark:text-red-200">
                    {{ $errorMessage }}
                </div>
            @endif

            @if ($successMessage)
                <div class="mt-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-700/60 dark:bg-green-900/20 dark:text-green-200">
                    {{ $successMessage }}
                </div>
            @endif

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="button"
                        wire:click="startPayment"
                        wire:loading.attr="disabled"
                        wire:target="startPayment"
                        class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-60 transition">
                    <span wire:loading.remove wire:target="startPayment">Buy Blue Tick</span>
                    <span wire:loading wire:target="startPayment">Creating payment...</span>
                </button>

                <button type="button"
                        wire:click="refreshPaymentStatus"
                        wire:loading.attr="disabled"
                        wire:target="refreshPaymentStatus"
                        class="inline-flex items-center px-4 py-2 rounded-md border dark:border-gray-600 border-gray-300 dark:text-white text-gray-800 text-sm font-medium dark:hover:bg-gray-800 hover:bg-gray-100 disabled:opacity-60 transition">
                    <span wire:loading.remove wire:target="refreshPaymentStatus">Refresh Status</span>
                    <span wire:loading wire:target="refreshPaymentStatus">Refreshing...</span>
                </button>
            </div>
        @endif
    </div>
</div>
