<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\PhoneNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class SendShippingTrackingWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public array $backoff = [60, 300, 900];

    public function __construct(public Order $order, public string $message)
    {
        $this->afterCommit();
    }

    public function handle(PhoneNotificationService $phoneNotificationService): void
    {
        $sent = $phoneNotificationService->sendTrackingMessage($this->order, $this->message);

        if (!$sent) {
            $error = $phoneNotificationService->getLastError() ?? 'Unknown WhatsApp delivery error';
            throw new RuntimeException($error);
        }
    }
}
