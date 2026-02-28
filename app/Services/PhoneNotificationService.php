<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhoneNotificationService
{
    public function sendTrackingMessage(Order $order, string $message): bool
    {
        $webhookUrl = config('services.phone_notification.webhook_url');

        if (empty($webhookUrl) || empty($order->guest_phone)) {
            return false;
        }

        try {
            Http::timeout(12)
                ->withToken((string) config('services.phone_notification.token'))
                ->post($webhookUrl, [
                    'phone' => $order->guest_phone,
                    'message' => $message,
                    'order_number' => $order->order_number,
                ]);

            return true;
        } catch (\Throwable $e) {
            Log::channel('payment')->error('Phone notification exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
