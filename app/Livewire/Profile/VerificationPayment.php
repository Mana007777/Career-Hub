<?php

namespace App\Livewire\Profile;

use App\Models\Verification;
use FirstIraqiBank\FIBPaymentSDK\Model\FibPayment;
use FirstIraqiBank\FIBPaymentSDK\Services\FIBPaymentIntegrationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class VerificationPayment extends Component
{
    public ?Verification $verification = null;

    public ?string $paymentLink = null;

    public ?string $readableCode = null;

    public ?string $qrCode = null;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    public bool $canUsePayments = false;

    public int $amount = 0;

    public function mount(): void
    {
        $this->amount = (int) config('verification-payment.amount', 1000);
        $this->canUsePayments = $this->hasRequiredTables();

        if ($this->canUsePayments) {
            $this->verification = Verification::query()->firstOrCreate(
                [
                    'user_id' => (int) Auth::id(),
                    'type' => 'identity',
                ],
                [
                    'status' => 'pending',
                ]
            );

            if ($this->verification->fib_payment_id && ! $this->isPaidStatus($this->verification->payment_status)) {
                $this->refreshPaymentStatus();
            }
        }
    }

    public function startPayment(): void
    {
        if (! $this->canUsePayments || ! $this->verification) {
            $this->errorMessage = 'Payment setup is not ready yet. Please contact support.';

            return;
        }

        if ($this->isPaidStatus($this->verification->payment_status)) {
            $this->successMessage = 'Your blue tick is already paid.';

            return;
        }

        $this->errorMessage = null;
        $this->successMessage = null;
        $this->qrCode = null;

        try {
            /** @var FIBPaymentIntegrationService $paymentService */
            $paymentService = app(FIBPaymentIntegrationService::class);

            $missingConfig = collect([
                'FIB_BASE_URL' => env('FIB_BASE_URL'),
                'FIB_CLIENT_ID' => env('FIB_CLIENT_ID'),
                'FIB_CLIENT_SECRET' => env('FIB_CLIENT_SECRET'),
            ])->filter(fn (?string $value) => blank($value))->keys()->all();

            if (! empty($missingConfig)) {
                $this->errorMessage = 'Missing payment config: '.implode(', ', $missingConfig);

                return;
            }

            $callbackUrl = $this->normalizeFibUrl(config('verification-payment.callback_url') ?: route('payments.fib.callback'));
            $redirectUrl = $this->normalizeFibUrl(config('verification-payment.redirect_url') ?: route('settings'));
            $description = sprintf('Blue tick verification for user #%d', (int) Auth::id());

            $response = $paymentService->createPayment(
                $this->amount,
                $callbackUrl,
                $description,
                $redirectUrl
            );

            if (! $response || ! $response->successful()) {
                $this->errorMessage = sprintf(
                    'Could not create payment right now. FIB response: %s %s',
                    (string) $response?->status(),
                    trim((string) $response?->body())
                );

                return;
            }

            $payload = $response->json();
            $this->paymentLink = data_get($payload, 'personalAppLink');
            $this->readableCode = data_get($payload, 'readableCode');
            $this->qrCode = data_get($payload, 'qrCode');

            $this->verification->update([
                'fib_payment_id' => data_get($payload, 'paymentId'),
                'payment_status' => $this->normalizePaymentStatus(data_get($payload, 'status', FibPayment::PENDING)),
                'payment_amount' => $this->amount,
            ]);

            $this->verification = $this->verification->fresh();
            $this->successMessage = 'Payment created. Open FIB app link and complete payment.';
        } catch (\Throwable $e) {
            Log::error('Blue tick payment creation failed', [
                'user_id' => Auth::id(),
                'verification_id' => $this->verification->id,
                'error' => $e->getMessage(),
            ]);

            $this->errorMessage = app()->hasDebugModeEnabled()
                ? 'Payment failed to start: '.$e->getMessage()
                : 'Payment failed to start. Please try again in a moment.';
        }
    }

    public function refreshPaymentStatus(): void
    {
        if (! $this->verification?->fib_payment_id) {
            $this->errorMessage = 'No active payment found yet.';

            return;
        }

        $this->errorMessage = null;
        $this->successMessage = null;

        try {
            /** @var FIBPaymentIntegrationService $paymentService */
            $paymentService = app(FIBPaymentIntegrationService::class);

            $response = $paymentService->checkPaymentStatus($this->verification->fib_payment_id);

            if (! $response || ! $response->successful()) {
                $this->errorMessage = 'Could not refresh payment status.';

                return;
            }

            $status = $this->normalizePaymentStatus(data_get($response->json(), 'status'));

            $this->verification->payment_status = $status;
            if ($this->isPaidStatus($status) && ! $this->verification->paid_at) {
                $this->verification->paid_at = now();
            }
            $this->verification->save();

            $this->verification = $this->verification->fresh();

            if ($this->isPaidStatus($this->verification->payment_status)) {
                $this->successMessage = 'Payment confirmed. Your blue tick purchase is complete.';
            } else {
                $this->successMessage = 'Payment status updated: '.$this->verification->payment_status;
            }
        } catch (\Throwable $e) {
            Log::error('Blue tick payment status refresh failed', [
                'user_id' => Auth::id(),
                'verification_id' => $this->verification?->id,
                'error' => $e->getMessage(),
            ]);

            $this->errorMessage = app()->hasDebugModeEnabled()
                ? 'Failed to refresh payment status: '.$e->getMessage()
                : 'Failed to refresh payment status.';
        }
    }

    private function hasRequiredTables(): bool
    {
        return Auth::check()
            && Schema::hasTable('verifications')
            && Schema::hasColumns('verifications', ['fib_payment_id', 'payment_status', 'payment_amount', 'paid_at']);
    }

    private function normalizeFibUrl(?string $url): ?string
    {
        if (blank($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        if (in_array($host, ['localhost', '127.0.0.1'], true)) {
            return null;
        }

        // Stage rejects many non-https URLs; fall back to null when uncertain.
        return $scheme === 'https' ? $url : null;
    }

    private function normalizePaymentStatus(?string $status): string
    {
        $normalized = strtoupper((string) $status);

        if ($normalized === '') {
            return FibPayment::PENDING;
        }

        return match ($normalized) {
            'SUCCESS', 'COMPLETED' => FibPayment::PAID,
            default => $normalized,
        };
    }

    private function isPaidStatus(?string $status): bool
    {
        return $this->normalizePaymentStatus($status) === FibPayment::PAID;
    }

    public function render()
    {
        return view('livewire.profile.verification-payment');
    }
}
