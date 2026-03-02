<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PhoneNotificationService
{
    private ?string $lastError = null;
    private ?string $lastTargetPhone = null;

    public function sendTrackingMessage(Order $order, string $message): bool
    {
        $this->lastError = null;
        $this->lastTargetPhone = null;

        $webhookUrl = config('services.phone_notification.webhook_url');
        $token = (string) config('services.phone_notification.token');
        $timeout = (int) config('services.phone_notification.timeout_seconds', 15);
        $retryCount = (int) config('services.phone_notification.retry_count', 1);
        $forceTo = (string) config('services.phone_notification.force_to', '');
        $rawPhone = $forceTo !== '' ? $forceTo : (string) $order->guest_phone;
        $phone = $this->normalizePhoneForWhatsapp($rawPhone);

        if (empty($webhookUrl)) {
            $this->lastError = 'Webhook URL not configured';
            Log::channel('payment')->warning('Phone notification skipped: missing webhook URL', [
                'order_id' => $order->id,
            ]);
            return false;
        }

        if (empty($phone)) {
            $this->lastError = 'Invalid guest phone number';
            Log::channel('payment')->warning('Phone notification skipped: invalid guest phone', [
                'order_id' => $order->id,
                'guest_phone' => $order->guest_phone,
                'force_to' => $forceTo,
            ]);
            return false;
        }

        $this->lastTargetPhone = $phone;

        $payload = [
            'phone' => $phone,
            'to' => $phone,
            'number' => $phone,
            'target' => $phone,
            'message' => $message,
            'text' => $message,
            'order_number' => $order->order_number,
        ];

        try {
            /** @var \Illuminate\Http\Client\PendingRequest $request */
            $request = Http::timeout(max(5, $timeout))
                ->retry(max(0, $retryCount), 250)
                ->acceptJson();

            $isFonnte = str_contains(strtolower((string) parse_url((string) $webhookUrl, PHP_URL_HOST)), 'fonnte.com');

            /** @var \Illuminate\Http\Client\Response $response */
            if ($isFonnte) {
                $fonntePayload = [
                    'target' => $phone,
                    'message' => $message,
                ];

                $response = $request
                    ->asForm()
                    ->withHeaders([
                        'Authorization' => $token,
                    ])
                    ->post($webhookUrl, $fonntePayload);
            } else {
                if (!empty($token)) {
                    $request = $request->withToken($token)
                        ->withHeaders(['Authorization' => 'Bearer ' . $token]);
                }

                $response = $request->post($webhookUrl, $payload);

                if (!$this->isSuccessfulWebhookResponse(
                    $this->responseStatus($response),
                    $this->responseJson($response),
                    $this->responseBody($response)
                )) {
                    $response = $request->asForm()->post($webhookUrl, $payload);
                }
            }

            /** @var \Illuminate\Http\Client\Response $response */
            if (!$this->isSuccessfulWebhookResponse(
                $this->responseStatus($response),
                $this->responseJson($response),
                $this->responseBody($response)
            )) {
                $this->lastError = $this->extractWebhookError(
                    $this->responseStatus($response),
                    $this->responseJson($response),
                    $this->responseBody($response)
                );

                Log::channel('payment')->warning('Phone notification failed response', [
                    'order_id' => $order->id,
                    'status' => $this->responseStatus($response),
                    'body' => Str::limit($this->responseBody($response), 500),
                    'phone' => $phone,
                ]);

                return false;
            }

            Log::channel('payment')->info('Phone notification sent', [
                'order_id' => $order->id,
                'phone' => $phone,
                'status' => $this->responseStatus($response),
                'body' => Str::limit($this->responseBody($response), 500),
            ]);

            return true;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();

            Log::channel('payment')->error('Phone notification exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'phone' => $phone,
            ]);

            return false;
        }
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function getLastTargetPhone(): ?string
    {
        return $this->lastTargetPhone;
    }

    private function normalizePhoneForWhatsapp(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        } elseif (str_starts_with($digits, '620')) {
            $digits = '62' . substr($digits, 3);
        }

        if (!str_starts_with($digits, '62')) {
            return null;
        }

        if (strlen($digits) < 10 || strlen($digits) > 15) {
            return null;
        }

        return $digits;
    }

    private function isSuccessfulWebhookResponse(int $status, mixed $json, string $body): bool
    {
        if ($status < 200 || $status >= 300) {
            return false;
        }

        if (is_array($json)) {
            $statusRaw = data_get($json, 'status');
            $statusValue = strtolower((string) data_get($json, 'status', data_get($json, 'result', '')));
            $okValue = data_get($json, 'ok');
            $successValue = data_get($json, 'success');

            if (is_bool($statusRaw) && $statusRaw === false) {
                return false;
            }

            if (is_bool($okValue) && $okValue === false) {
                return false;
            }

            if (is_bool($successValue) && $successValue === false) {
                return false;
            }

            if (in_array($statusValue, ['failed', 'error', 'invalid'], true)) {
                return false;
            }
        }

        $lowerBody = strtolower($body);
        if (str_contains($lowerBody, 'error') && !str_contains($lowerBody, 'success')) {
            return false;
        }

        return true;
    }

    private function responseStatus(mixed $response): int
    {
        return method_exists($response, 'status') ? (int) $response->status() : 0;
    }

    private function responseJson(mixed $response): mixed
    {
        return method_exists($response, 'json') ? $response->json() : null;
    }

    private function responseBody(mixed $response): string
    {
        return method_exists($response, 'body') ? (string) $response->body() : '';
    }

    private function extractWebhookError(int $status, mixed $json, string $body): string
    {
        $reason = '';

        if (is_array($json)) {
            $reason = (string) data_get($json, 'reason', data_get($json, 'message', data_get($json, 'error', '')));
        }

        $reasonLower = strtolower($reason);
        if (str_contains($reasonLower, 'disconnected device')) {
            return 'Perangkat WhatsApp provider sedang disconnect. Silakan reconnect device di dashboard provider WA.';
        }

        if ($reason !== '') {
            return 'Webhook rejected request: ' . $reason;
        }

        if ($status >= 200 && $status < 300) {
            return 'Webhook rejected request: respons provider menandakan gagal meskipun HTTP 2xx';
        }

        return 'Webhook rejected request (HTTP ' . $status . ')';
    }
}
