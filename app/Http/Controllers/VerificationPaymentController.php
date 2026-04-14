<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use FirstIraqiBank\FIBPaymentSDK\Model\FibPayment;
use FirstIraqiBank\FIBPaymentSDK\Services\FIBPaymentIntegrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationPaymentController extends Controller
{
    public function createCheckout(Request $request, Verification $verification, FIBPaymentIntegrationService $paymentService): JsonResponse
    {
        if ($verification->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($this->isVerificationActive($verification)) {
            return response()->json([
                'message' => 'Verification has already been paid.',
            ], 422);
        }

        $amount = (int) config('verification-payment.amount');
        $callbackUrl = $this->normalizeFibUrl(config('verification-payment.callback_url') ?: route('payments.fib.callback'));
        $redirectUrl = $this->normalizeFibUrl(config('verification-payment.redirect_url') ?: route('settings'));
        $description = sprintf(
            'Verification payment for verification #%d (%s)',
            $verification->id,
            $verification->type
        );

        $response = $paymentService->createPayment(
            $amount,
            $callbackUrl,
            $description,
            $redirectUrl
        );

        if (! $response || ! $response->successful()) {
            Log::error('Failed to create verification payment', [
                'verification_id' => $verification->id,
                'response_status' => $response?->status(),
                'response_body' => $response?->body(),
            ]);

            return response()->json([
                'message' => 'Unable to create payment at the moment.',
            ], 502);
        }

        $payload = $response->json();
        $fibPaymentId = data_get($payload, 'paymentId');
        $paymentStatus = $this->normalizePaymentStatus(data_get($payload, 'status', FibPayment::PENDING));

        $verification->update([
            'fib_payment_id' => $fibPaymentId,
            'payment_status' => $paymentStatus,
            'payment_amount' => $amount,
            'paid_at' => null,
        ]);

        return response()->json([
            'verification_id' => $verification->id,
            'fib_payment_id' => $fibPaymentId,
            'payment_status' => $verification->payment_status,
            'readable_code' => data_get($payload, 'readableCode'),
            'personal_app_link' => data_get($payload, 'personalAppLink'),
            'valid_until' => data_get($payload, 'validUntil'),
        ]);
    }

    public function refreshStatus(Request $request, Verification $verification, FIBPaymentIntegrationService $paymentService): JsonResponse
    {
        if ($verification->user_id !== $request->user()->id) {
            abort(403);
        }

        if (! $verification->fib_payment_id) {
            return response()->json([
                'message' => 'No payment is linked to this verification.',
            ], 404);
        }

        $response = $paymentService->checkPaymentStatus($verification->fib_payment_id);

        if (! $response || ! $response->successful()) {
            return response()->json([
                'message' => 'Unable to fetch payment status.',
                'status_code' => $response?->status(),
            ], 502);
        }

        $status = $this->normalizePaymentStatus(data_get($response->json(), 'status'));
        $verification->payment_status = $status;

        if ($status === FibPayment::PAID && ! $verification->paid_at) {
            $verification->paid_at = now();
        }

        $verification->save();

        return response()->json([
            'verification_id' => $verification->id,
            'fib_payment_id' => $verification->fib_payment_id,
            'payment_status' => $verification->payment_status,
            'paid_at' => $verification->paid_at,
        ]);
    }

    public function handleCallback(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $verification = Verification::query()
            ->where('fib_payment_id', $validated['id'])
            ->first();

        if (! $verification) {
            return response()->json([
                'message' => 'Verification payment not found.',
            ], 404);
        }

        $verification->payment_status = $this->normalizePaymentStatus($validated['status']);

        if ($verification->payment_status === FibPayment::PAID && ! $verification->paid_at) {
            $verification->paid_at = now();
        }

        $verification->save();

        return response()->json(['message' => 'Callback processed successfully.']);
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

    private function isVerificationActive(Verification $verification): bool
    {
        return $verification->payment_status === FibPayment::PAID
            && $verification->paid_at
            && $verification->paid_at->copy()->addMonth()->isFuture();
    }
}
