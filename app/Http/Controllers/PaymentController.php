<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\EbookDelivery;
use App\Jobs\SendEbookToEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    /**
     * Handle Midtrans webhook callback
     */
    public function handleCallback(Request $request)
    {
        $payload = $request->all();

        foreach (['order_id', 'status_code', 'gross_amount', 'signature_key', 'transaction_status'] as $requiredField) {
            if (!array_key_exists($requiredField, $payload)) {
                return response()->json(['error' => 'Invalid payload'], 422);
            }
        }

        // Verify Midtrans signature for security
        $serverKey = config('midtrans.server_key');
        $token = $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey;
        $expectedSignature = hash('sha256', $token);

        if (!hash_equals($expectedSignature, (string) $payload['signature_key'])) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $order = Order::where('transaction_id', $payload['order_id'])
            ->orWhere('order_number', $payload['order_id'])
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $grossAmount = (int) round((float) $payload['gross_amount']);
        $orderTotal = (int) round((float) $order->total);

        if ($grossAmount !== $orderTotal) {
            Log::channel('payment')->warning('Midtrans callback amount mismatch', [
                'order_id' => $order->id,
                'midtrans_order_id' => $payload['order_id'],
                'gross_amount' => $grossAmount,
                'order_total' => $orderTotal,
            ]);

            return response()->json(['error' => 'Gross amount mismatch'], 422);
        }

        try {
            DB::beginTransaction();

            if (!$order->transaction_id) {
                $order->transaction_id = $payload['order_id'];
            }

            $order->payment_method = 'midtrans';
            $order->payment_details = json_encode($payload);

            $previousStatus = $order->status;
            $transactionStatus = $payload['transaction_status'];
            $fraudStatus = $payload['fraud_status'] ?? null;

            // Handle payment status from Midtrans
            if (
                $transactionStatus === 'settlement' ||
                ($transactionStatus === 'capture' && in_array($fraudStatus, [null, 'accept']))
            ) {
                // Payment successful
                $order->status = $this->resolvePaidOrderStatus($order);
                $order->save();

                // Trigger email job for ebook items
                $this->sendEbookDeliveries($order);
            } elseif ($transactionStatus === 'pending') {
                $order->status = 'pending';
                $order->save();
            } elseif (
                in_array($transactionStatus, ['deny', 'cancel', 'expire'])
            ) {

                // Payment failed - restore stock
                if (!in_array($previousStatus, ['failed', 'cancelled'])) {
                    $this->restoreStock($order);
                }

                $order->status = 'failed';
                $order->save();
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('payment')->error('Midtrans callback error: ' . $e->getMessage(), [
                'payload' => $payload,
            ]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Send ebook deliveries to customer email
     */
    private function sendEbookDeliveries(Order $order)
    {
        // Get ebook items dari order
        $ebookItems = $order->orderItems()
            ->whereIn('format', ['ebook', 'both'])
            ->get();

        foreach ($ebookItems as $item) {
            // Check if ebook delivery already exists
            if ($item->ebookDelivery) {
                continue;
            }

            // Generate unique download token
            $downloadToken = Str::random(64);

            // Create ebook delivery record
            $delivery = EbookDelivery::create([
                'order_item_id' => $item->id,
                'email' => $order->guest_email ?? $order->user->email,
                'download_token' => $downloadToken,
                'sent_at' => now(),
                'expired_at' => now()->addDays(7), // Link berlaku 7 hari
            ]);

            // Queue email job
            try {
                SendEbookToEmail::dispatch($delivery, $order, $item);
            } catch (\Throwable $e) {
                Log::channel('payment')->error('Failed to dispatch ebook email job', [
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Restore book stock if payment failed
     */
    private function restoreStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            if ($item->format === 'physical' || $item->format === 'both') {
                $item->book->increment('stock', $item->quantity);
            }
        }
    }

    /**
     * Get payment status (untuk frontend polling)
     */
    public function getStatus(Order $order)
    {
        return response()->json([
            'status' => $order->status,
            'transaction_id' => $order->transaction_id,
        ]);
    }

    /**
     * Redirect user after payment from Midtrans
     */
    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');

        $order = Order::where('transaction_id', $orderId)
            ->orWhere('order_number', $orderId)
            ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan');
        }

        try {
            $transaction = Transaction::status($orderId);

            if (is_array($transaction)) {
                $transaction = (object) $transaction;
            }

            $this->synchronizeOrderFromMidtrans($order, $transaction);
        } catch (\Throwable $e) {
            Log::channel('payment')->warning('Failed to verify finish status to Midtrans', [
                'order_id' => $order->id,
                'midtrans_order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
        }

        // Set session untuk verifikasi guest order
        session(['guest_order_' . $order->id => true]);

        if ($order->status === 'paid') {
            return redirect()->route('checkout.confirmation', $order);
        }

        return redirect()->route('checkout.confirmation', $order)
            ->with('warning', 'Pembayaran masih diproses');
    }

    private function synchronizeOrderFromMidtrans(Order $order, object $transaction): void
    {
        $transactionStatus = $transaction->transaction_status ?? null;
        $fraudStatus = $transaction->fraud_status ?? null;

        if (
            $transactionStatus === 'settlement' ||
            ($transactionStatus === 'capture' && in_array($fraudStatus, [null, 'accept']))
        ) {
            $order->update([
                'status' => $this->resolvePaidOrderStatus($order),
                'payment_method' => $transaction->payment_type ?? 'midtrans',
                'payment_details' => json_encode($transaction),
                'transaction_id' => $order->transaction_id ?: ($transaction->order_id ?? $order->order_number),
            ]);

            $this->sendEbookDeliveries($order);

            return;
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $order->update([
                'status' => 'failed',
                'payment_method' => $transaction->payment_type ?? 'midtrans',
                'payment_details' => json_encode($transaction),
                'transaction_id' => $order->transaction_id ?: ($transaction->order_id ?? $order->order_number),
            ]);
        }
    }

    private function resolvePaidOrderStatus(Order $order): string
    {
        $order->loadMissing('orderItems');

        return $order->hasPhysicalItems() ? 'processing' : 'paid';
    }
}
