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
        }
    }

    public function startPayment(FIBPaymentIntegrationService $paymentService): void
    {
        if (! $this->canUsePayments || ! $this->verification) {
            $this->errorMessage = 'Payment setup is not ready yet. Please contact support.';

            return;
        }

        if ($this->verification->payment_status === FibPayment::PAID) {
            $this->successMessage = 'Your blue tick is already paid.';

            return;
        }

        $this->errorMessage = null;
        $this->successMessage = null;

        try {
            $callbackUrl = config('verification-payment.callback_url') ?: route('payments.fib.callback');
            $redirectUrl = config('verification-payment.redirect_url') ?: route('settings');
            $description = sprintf('Blue tick verification for user #%d', (int) Auth::id());

            $response = $paymentService->createPayment(
                $this->amount,
                $callbackUrl,
                $description,
                $redirectUrl,
                ['verification_id' => $this->verification->id, 'type' => 'blue_tick']
            );

            if (! $response || ! $response->successful()) {
                $this->errorMessage = 'Could not create payment right now. Please try again.';

                return;
            }

            $payload = $response->json();
            $this->paymentLink = data_get($payload, 'personalAppLink');
            $this->readableCode = data_get($payload, 'readableCode');

            $this->verification->update([
                'fib_payment_id' => data_get($payload, 'paymentId'),
                'payment_status' => FibPayment::PENDING,
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

            $this->errorMessage = 'Payment failed to start. Please try again in a moment.';
        }
    }

    public function refreshPaymentStatus(FIBPaymentIntegrationService $paymentService): void
    {
        if (! $this->verification?->fib_payment_id) {
            $this->errorMessage = 'No active payment found yet.';

            return;
        }

        $this->errorMessage = null;
        $this->successMessage = null;

        try {
            $response = $paymentService->checkPaymentStatus($this->verification->fib_payment_id);

            if (! $response || ! $response->successful()) {
                $this->errorMessage = 'Could not refresh payment status.';

                return;
            }

            $status = data_get($response->json(), 'status');

            $this->verification->payment_status = $status;
            if ($status === FibPayment::PAID && ! $this->verification->paid_at) {
                $this->verification->paid_at = now();
            }
            $this->verification->save();

            $this->verification = $this->verification->fresh();

            if ($this->verification->payment_status === FibPayment::PAID) {
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

            $this->errorMessage = 'Failed to refresh payment status.';
        }
    }

    private function hasRequiredTables(): bool
    {
        return Schema::hasTable('verifications')
            && Schema::hasTable('fib_payments')
            && Schema::hasColumns('verifications', ['fib_payment_id', 'payment_status', 'payment_amount', 'paid_at']);
    }

    public function render()
    {
        return view('livewire.profile.verification-payment');
    }
}
