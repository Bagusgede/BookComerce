<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbookDelivery;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EbookDeliveryController extends Controller
{
    /**
     * Display ebook deliveries list
     */
    public function index(Request $request)
    {
        $query = EbookDelivery::with('orderItem.book', 'orderItem.order');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('email', 'like', "%{$search}%")
                ->orWhereHas('orderItem.order', function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%");
                });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'expired') {
                $query->where('expired_at', '<', now());
            } else {
                $query->where('expired_at', '>=', now());
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('sent_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sent_at', '<=', $request->date_to);
        }

        // Sorting
        $query->orderBy('sent_at', 'desc');

        $deliveries = $query->paginate(15);

        return view('admin.ebook-deliveries.index', [
            'deliveries' => $deliveries,
            'search' => $request->search,
            'selectedStatus' => $request->status,
        ]);
    }

    /**
     * Show delivery detail
     */
    public function show(EbookDelivery $delivery)
    {
        $delivery->load('orderItem.book', 'orderItem.order');

        return view('admin.ebook-deliveries.show', [
            'delivery' => $delivery,
        ]);
    }

    /**
     * Remove a delivery record
     */
    public function destroy(EbookDelivery $delivery)
    {
        $delivery->delete();

        return back()->with('success', 'Data pengiriman ebook berhasil dihapus');
    }

    /**
     * Resend delivery
     */
    public function resend(EbookDelivery $delivery)
    {
        $orderItem = $delivery->orderItem;
        $order = $orderItem->order;

        // Update expiry date
        $delivery->update([
            'expired_at' => now()->addDays(7),
            'sent_at' => now(),
        ]);

        // Send email
        \Illuminate\Support\Facades\Mail::send(new \App\Mail\EbookDownloadLink(
            $order,
            $orderItem->book,
            route('ebook.download', [
                'token' => $delivery->download_token,
                'order_item_id' => $orderItem->id,
            ]),
            $delivery
        ));

        return back()->with('success', 'Link ebook berhasil dikirim ulang');
    }

    /**
     * Generate new download token
     */
    public function regenerateToken(EbookDelivery $delivery)
    {
        $oldToken = $delivery->download_token;

        $delivery->update([
            'download_token' => \Illuminate\Support\Str::random(64),
            'expired_at' => now()->addDays(7),
        ]);

        Log::info('Ebook token regenerated', [
            'delivery_id' => $delivery->id,
            'old_token' => substr($oldToken, 0, 10) . '...',
        ]);

        return back()->with('success', 'Token download berhasil diperbarui');
    }

    /**
     * Download logs
     */
    public function logs(Request $request)
    {
        $query = EbookDelivery::with('orderItem.book')
            ->where('download_count', '>', 0);

        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }

        if ($request->filled('book_id')) {
            $query->whereHas('orderItem', function ($q) use ($request) {
                $q->where('book_id', $request->book_id);
            });
        }

        $logs = $query->orderBy('last_downloaded_at', 'desc')->paginate(15);

        return view('admin.ebook-deliveries.logs', [
            'logs' => $logs,
        ]);
    }

    /**
     * Export deliveries to CSV
     */
    public function export(Request $request)
    {
        $deliveries = EbookDelivery::with('orderItem.book')
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'expired') {
                    $q->where('expired_at', '<', now());
                } else {
                    $q->where('expired_at', '>=', now());
                }
            })
            ->get();

        $filename = 'ebook_deliveries_' . date('Y-m-d_His') . '.csv';
        $csv = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Headers
        fputcsv($csv, ['Email', 'Book', 'Sent At', 'Expires At', 'Downloads', 'Last Downloaded', 'Status']);

        foreach ($deliveries as $delivery) {
            fputcsv($csv, [
                $delivery->email,
                $delivery->orderItem->book->title,
                $delivery->sent_at->format('Y-m-d H:i'),
                $delivery->expired_at->format('Y-m-d H:i'),
                $delivery->download_count,
                $delivery->last_downloaded_at?->format('Y-m-d H:i') ?? '-',
                $delivery->isExpired() ? 'Expired' : 'Active',
            ]);
        }

        fclose($csv);
        exit();
    }

    /**
     * Analytics dashboard
     */
    public function analytics()
    {
        $analytics = [
            'total_deliveries' => EbookDelivery::count(),
            'active_links' => EbookDelivery::where('expired_at', '>=', now())->count(),
            'expired_links' => EbookDelivery::where('expired_at', '<', now())->count(),
            'total_downloads' => EbookDelivery::sum('download_count'),
            'never_downloaded' => EbookDelivery::where('download_count', 0)->count(),
            'by_week' => EbookDelivery::selectRaw('WEEK(created_at) as week, COUNT(*) as count')
                ->groupBy('week')
                ->orderBy('week', 'desc')
                ->limit(12)
                ->pluck('count', 'week'),
        ];

        return view('admin.ebook-deliveries.analytics', [
            'analytics' => $analytics,
        ]);
    }
}
