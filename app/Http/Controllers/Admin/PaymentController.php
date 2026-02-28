<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display payments list
     */
    public function index(Request $request)
    {
        $query = Order::whereIn('status', ['paid', 'pending', 'failed'])
            ->with('payment');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhere('guest_email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $payments = $query->paginate(15);

        return view('admin.payments.index', [
            'payments' => $payments,
            'search' => $request->search,
            'selectedStatus' => $request->status,
            'selectedPaymentMethod' => $request->payment_method,
        ]);
    }

    /**
     * Show payment detail
     */
    public function show(Order $order)
    {
        $order->load('payment', 'orderItems');

        return view('admin.payments.show', [
            'order' => $order,
        ]);
    }

    /**
     * Verify payment with Midtrans
     */
    public function verify(Order $order)
    {
        if (!$order->transaction_id) {
            return back()->withErrors(['error' => 'Order ini tidak memiliki transaction ID']);
        }

        try {
            // Call Midtrans API to verify
            $response = \Midtrans\Transaction::status($order->transaction_id);

            if ($response->transaction_status === 'settlement' || $response->transaction_status === 'capture') {
                // Payment verified
                $order->update([
                    'status' => 'paid',
                    'payment_method' => $response->payment_type ?? 'midtrans',
                    'payment_details' => json_encode($response),
                ]);

                Log::info('Payment verified manually', [
                    'order_id' => $order->id,
                    'transaction_id' => $order->transaction_id,
                ]);

                return back()->with('success', 'Pembayaran berhasil diverifikasi');
            } else {
                return back()->withErrors(['error' => 'Status pembayaran: ' . $response->transaction_status]);
            }
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Gagal verifikasi pembayaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Mark payment as failed
     */
    public function markFailed(Order $order)
    {
        $order->update([
            'status' => 'failed',
            'notes' => ($order->notes ?? '') . "\nMarked as failed by admin at " . now(),
        ]);

        Log::info('Payment marked as failed', [
            'order_id' => $order->id,
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran ditandai sebagai gagal');
    }

    /**
     * Refund payment
     */
    public function refund(Request $request, Order $order)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'amount' => 'nullable|numeric|min:0|max:' . $order->total,
        ]);

        $refundAmount = $validated['amount'] ?? $order->total;

        try {
            // Call Midtrans refund API
            \Midtrans\Transaction::refund($order->transaction_id, [
                'reason' => $validated['reason'],
                'amount' => $refundAmount,
            ]);

            $order->update([
                'status' => 'refunded',
                'notes' => ($order->notes ?? '') . "\nRefunded: " . $validated['reason'],
            ]);

            Log::info('Payment refunded', [
                'order_id' => $order->id,
                'amount' => $refundAmount,
                'reason' => $validated['reason'],
                'admin_id' => auth()->id(),
            ]);

            return back()->with('success', 'Pembayaran berhasil direfund sebesar Rp ' . number_format($refundAmount, 0));
        } catch (\Exception $e) {
            Log::error('Refund failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Gagal refund: ' . $e->getMessage()]);
        }
    }

    /**
     * Payment logs & analytics
     */
    public function logs(Request $request)
    {
        $logs = Log::channel('single')
            ->where('type', 'payment')
            ->when($request->filled('order_number'), function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->order_number}%");
            })
            ->when($request->filled('date_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.payments.logs', [
            'logs' => $logs,
        ]);
    }

    /**
     * Analytics & reports
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = \Carbon\Carbon::now()->subDays($period);

        $analytics = [
            'total_revenue' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'paid')
                ->sum('total'),
            'total_transactions' => Order::where('created_at', '>=', $startDate)->count(),
            'successful_payments' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'paid')
                ->count(),
            'failed_payments' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'failed')
                ->count(),
            'pending_payments' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'pending')
                ->count(),
            'average_transaction' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'paid')
                ->avg('total'),
            'by_method' => Order::where('created_at', '>=', $startDate)
                ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
                ->groupBy('payment_method')
                ->pluck('total', 'payment_method'),
        ];

        return view('admin.payments.analytics', [
            'analytics' => $analytics,
            'period' => $period,
        ]);
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $orders = Order::with('payment')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->whereIn('status', ['paid', 'pending', 'failed'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'payments_' . date('Y-m-d_His') . '.csv';
        $csv = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $headers = ['Order Number', 'Email', 'Amount', 'Method', 'Status', 'Transaction ID', 'Date'];
        fputcsv($csv, $headers);

        foreach ($orders as $order) {
            fputcsv($csv, [
                $order->order_number,
                $order->guest_email,
                $order->total,
                $order->payment_method ?? 'N/A',
                $order->status,
                $order->transaction_id ?? 'N/A',
                $order->created_at->format('Y-m-d H:i'),
            ]);
        }

        fclose($csv);
        exit();
    }
}
