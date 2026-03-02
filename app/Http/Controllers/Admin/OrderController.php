<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendShippingTrackingWhatsapp;
use App\Mail\ShippingTrackingNotification;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display orders list
     */
    public function index(Request $request)
    {
        $query = Order::with('orderItems.book');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('guest_email', 'like', "%{$search}%")
                    ->orWhere('guest_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $orders = $query->paginate(15);

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $request->search,
            'selectedStatus' => $request->status,
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
        ]);
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        $order->load('orderItems.book', 'ebookDeliveries', 'payment');

        $statusTimeline = [
            'pending' => $order->created_at,
            'paid' => $order->updated_at,
            'processing' => null,
            'completed' => null,
            'cancelled' => null,
        ];

        return view('admin.orders.show', [
            'order' => $order,
            'statusTimeline' => $statusTimeline,
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,processing,completed,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        // Check valid status transitions
        $validTransitions = [
            'pending' => ['paid', 'cancelled'],
            'paid' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'completed' => ['refunded'],
            'cancelled' => [],
            'refunded' => [],
        ];

        if (!in_array($validated['status'], $validTransitions[$order->status] ?? [])) {
            return back()->withErrors(['error' => 'Status transition tidak valid']);
        }

        $oldStatus = $order->status;
        $order->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $order->notes,
        ]);

        // Log activity
        Log::info('Order status updated', [
            'order_id' => $order->id,
            'from' => $oldStatus,
            'to' => $validated['status'],
            'admin_id' => Auth::id(),
        ]);

        return back()->with('success', 'Status order berhasil diupdate');
    }

    public function submitShipping(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:120',
            'shipping_method' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:255',
        ]);

        $order->loadMissing('orderItems');

        if (!$order->hasPhysicalItems()) {
            return back()->withErrors(['error' => 'Order ini tidak memiliki item fisik']);
        }

        if (!in_array($order->status, ['processing', 'paid', 'shipped'], true)) {
            return back()->withErrors(['error' => 'Status order belum siap untuk input resi']);
        }

        $order->update([
            'shipping_tracking_number' => $validated['tracking_number'],
            'shipping_tracking_url' => $validated['tracking_url'] ?? $order->shipping_tracking_url,
            'shipping_method' => $validated['shipping_method'] ?? $order->shipping_method,
            'status' => 'shipped',
            'packed_at' => $order->packed_at ?? now(),
            'shipped_at' => now(),
        ]);

        $emailSent = false;
        $phoneQueued = false;

        if (!empty($order->guest_email)) {
            try {
                Mail::to($order->guest_email)->queue(new ShippingTrackingNotification($order));
                $emailSent = true;
            } catch (\Throwable $e) {
                Log::channel('payment')->error('Failed sending shipping tracking email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $phoneMessage = "Pesanan {$order->order_number} sudah dikirim. No Resi: {$order->shipping_tracking_number}."
            . ($order->shipping_tracking_url ? " Lacak: {$order->shipping_tracking_url}" : '');

        if (!empty($order->guest_phone)) {
            try {
                SendShippingTrackingWhatsapp::dispatch($order, $phoneMessage);
                $phoneQueued = true;
            } catch (\Throwable $e) {
                Log::channel('payment')->error('Failed queueing shipping tracking whatsapp', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($emailSent || $phoneQueued) {
            $order->forceFill(['tracking_notified_at' => now()])->save();
        }

        $notice = [];
        $notice[] = 'Resi berhasil disimpan.';
        $notice[] = $emailSent ? 'Notifikasi email masuk antrian kirim.' : 'Notifikasi email belum terkirim.';
        $notice[] = $phoneQueued
            ? 'Notifikasi WhatsApp masuk antrian kirim.'
            : 'Notifikasi WhatsApp belum masuk antrian.';

        return back()->with('success', implode(' ', $notice));
    }

    /**
     * Resend ebook delivery
     */
    public function resendEbook(Order $order, OrderItem $orderItem)
    {
        if (!$orderItem->book || ($orderItem->format !== 'ebook' && $orderItem->format !== 'both')) {
            return back()->withErrors(['error' => 'Item ini bukan ebook']);
        }

        // Generate new ebook delivery
        $delivery = $orderItem->ebookDelivery ?? new \App\Models\EbookDelivery();

        if (!$delivery->exists) {
            $delivery->fill([
                'order_item_id' => $orderItem->id,
                'email' => $order->guest_email,
                'download_token' => \Illuminate\Support\Str::random(64),
                'sent_at' => now(),
                'expired_at' => now()->addDays(7),
                'download_count' => 0,
            ])->save();
        } else {
            // Reset expired date
            $delivery->update([
                'expired_at' => now()->addDays(7),
                'sent_at' => now(),
            ]);
        }

        // Queue email
        \Illuminate\Support\Facades\Mail::to($order->guest_email)->queue(new \App\Mail\EbookDownloadLink(
            $order,
            $orderItem->book,
            route('ebook.download', [
                'token' => $delivery->download_token,
                'order_item_id' => $orderItem->id,
            ]),
            $delivery
        ));

        return back()->with('success', 'Link ebook berhasil dimasukkan ke antrian kirim ke ' . $order->guest_email);
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $orders = Order::with('orderItems')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->get();

        $filename = 'orders_' . date('Y-m-d_His') . '.csv';
        $csv = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Headers
        fputcsv($csv, ['Order Number', 'Customer', 'Email', 'Total', 'Items', 'Status', 'Date']);

        // Data
        foreach ($orders as $order) {
            fputcsv($csv, [
                $order->order_number,
                $order->guest_name,
                $order->guest_email,
                $order->total,
                $order->orderItems->count(),
                $order->status,
                $order->created_at->format('Y-m-d H:i'),
            ]);
        }

        fclose($csv);
        exit();
    }

    /**
     * Get order analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days

        $startDate = \Carbon\Carbon::now()->subDays($period);

        $analytics = [
            'total_orders' => Order::where('created_at', '>=', $startDate)->count(),
            'total_revenue' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'paid')
                ->sum('total'),
            'average_order_value' => Order::where('created_at', '>=', $startDate)
                ->where('status', 'paid')
                ->avg('total'),
            'by_status' => Order::where('created_at', '>=', $startDate)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
        ];

        return view('admin.orders.analytics', [
            'analytics' => $analytics,
            'period' => $period,
        ]);
    }
}
