<?php

namespace App\Jobs;

use App\Models\EbookDelivery;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\EbookDownloadLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEbookToEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $timeout = 120;
    public array $backoff = [60, 300, 900, 1800];
    // public string $queue = 'emails';

    protected $delivery;
    protected $order;
    protected $orderItem;

    /**
     * Create a new job instance.
     */
    public function __construct(EbookDelivery $delivery, Order $order, OrderItem $orderItem)
    {
        $this->delivery = $delivery;
        $this->order = $order;
        $this->orderItem = $orderItem;

        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Generate download link dengan secure token
            $downloadLink = route('ebook.download', [
                'token' => $this->delivery->download_token,
                'order_item_id' => $this->orderItem->id,
            ]);

            // Load relationships
            $this->orderItem->load('book');

            // Send email
            Mail::to($this->delivery->email)->send(
                new EbookDownloadLink(
                    $this->order,
                    $this->orderItem->book,
                    $downloadLink,
                    $this->delivery
                )
            );

            // Log untuk audit trail
            Log::channel('payment')->info('Ebook email sent', [
                'order_id' => $this->order->id,
                'order_item_id' => $this->orderItem->id,
                'email' => $this->delivery->email,
                'book_id' => $this->orderItem->book->id,
            ]);
        } catch (\Exception $e) {
            Log::channel('payment')->error('Failed to send ebook email', [
                'order_id' => $this->order->id,
                'order_item_id' => $this->orderItem->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception)
    {
        Log::channel('payment')->error('SendEbookToEmail job failed', [
            'order_id' => $this->order->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
