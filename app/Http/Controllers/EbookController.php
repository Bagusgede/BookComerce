<?php

namespace App\Http\Controllers;

use App\Models\EbookDelivery;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EbookController extends Controller
{
    /**
     * Download ebook dengan validasi token
     */
    public function download(Request $request)
    {
        $token = $request->query('token');
        $orderItemId = $request->query('order_item_id');

        // Validate request
        if (!$token || !$orderItemId) {
            abort(400, 'Parameter tidak lengkap');
        }

        // Find ebook delivery by token
        $delivery = EbookDelivery::byToken($token)->first();

        if (!$delivery) {
            abort(403, 'Link download tidak valid');
        }

        // Check if link has expired
        if ($delivery->isExpired()) {
            abort(403, 'Link download sudah expired. Silakan hubungi customer service untuk mendapatkan link baru.');
        }

        // Verify order item ID matches
        if ($delivery->order_item_id != $orderItemId) {
            abort(403, 'Order item tidak sesuai');
        }

        // Get order item and book
        $orderItem = OrderItem::with('book')->find($orderItemId);

        if (!$orderItem || !$orderItem->book) {
            abort(404, 'Book tidak ditemukan');
        }

        $order = $orderItem->order;

        if (!$order || $order->status !== 'paid') {
            abort(403, 'Ebook hanya tersedia setelah pembayaran berhasil');
        }

        // Verify book format (harus ebook atau both)
        if ($orderItem->format !== 'ebook' && $orderItem->format !== 'both') {
            abort(403, 'Item ini bukan ebook');
        }

        // Check if ebook file exists
        $ebookPath = $this->resolveEbookPath($orderItem->book->ebook_file);

        if (!$ebookPath || !file_exists($ebookPath)) {
            Log::channel('payment')->error('Ebook file not found', [
                'path' => $ebookPath,
                'book_id' => $orderItem->book->id,
            ]);
            abort(404, 'File ebook tidak ditemukan');
        }

        try {
            // Rate limiting - maksimal 3x per hour
            $downloadedCount = $delivery->where('download_token', $token)
                ->where('last_downloaded_at', '>', now()->subHours(1))
                ->get()
                ->sum('download_count');

            if ($downloadedCount >= 3) {
                abort(429, 'Terlalu banyak download. Coba lagi dalam 1 jam.');
            }

            // Record download
            $delivery->recordDownload();

            // Get file info
            $fileName = $this->buildDownloadFilename(
                $orderItem->book->title,
                pathinfo($orderItem->book->ebook_file, PATHINFO_EXTENSION)
            );
            $fileSize = filesize($ebookPath);
            $fileMimeType = $this->getMimeType($orderItem->book->ebook_file);

            // Log download untuk audit
            Log::channel('payment')->info('Ebook downloaded', [
                'token' => substr($token, 0, 10) . '...', // Log partial token for privacy
                'order_item_id' => $orderItemId,
                'book_id' => $orderItem->book->id,
                'email' => $delivery->email,
                'download_count' => $delivery->download_count,
            ]);

            // Stream file ke client
            return new StreamedResponse(
                function () use ($ebookPath) {
                    readfile($ebookPath);
                },
                200,
                [
                    'Content-Type' => $fileMimeType,
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    'Content-Length' => $fileSize,
                    'Cache-Control' => 'no-cache, must-revalidate',
                    'Pragma' => 'no-cache',
                ]
            );
        } catch (\Exception $e) {
            Log::channel('payment')->error('Ebook download error', [
                'error' => $e->getMessage(),
                'order_item_id' => $orderItemId,
            ]);
            abort(500, 'Gagal mengunduh file');
        }
    }

    /**
     * Determine MIME type based on file extension
     */
    private function getMimeType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'epub' => 'application/epub+zip',
            'mobi' => 'application/x-mobipocket-ebook',
            'azw' => 'application/vnd.amazon.ebook',
            'zip' => 'application/zip',
            default => 'application/octet-stream',
        };
    }

    /**
     * Get download link (untuk generate ulang)
     */
    public function getDownloadLink(Request $request)
    {
        $email = $request->query('email');
        $orderNumber = $request->query('order_number');

        if (!$email || !$orderNumber) {
            return response()->json(['error' => 'Parameter tidak lengkap'], 400);
        }

        // Find order by guest email and order number
        $order = \App\Models\Order::where('guest_email', $email)
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        // Get active ebook deliveries
        $deliveries = EbookDelivery::whereIn(
            'order_item_id',
            $order->orderItems()->pluck('id')
        )
            ->active()
            ->get();

        if ($deliveries->isEmpty()) {
            return response()->json(['error' => 'Tidak ada ebook untuk diunduh'], 404);
        }

        $downloads = $deliveries->map(function ($delivery) {
            return [
                'book_title' => $delivery->orderItem->book->title,
                'download_link' => route('ebook.download', [
                    'token' => $delivery->download_token,
                    'order_item_id' => $delivery->order_item_id,
                ]),
                'expires_at' => $delivery->expired_at,
                'download_count' => $delivery->download_count,
            ];
        });

        return response()->json([
            'success' => true,
            'downloads' => $downloads,
        ]);
    }

    private function resolveEbookPath(?string $ebookFile): ?string
    {
        if (empty($ebookFile)) {
            return null;
        }

        $localRelativePath = 'ebooks/' . $ebookFile;

        if (Storage::disk('local')->exists($localRelativePath)) {
            return Storage::disk('local')->path($localRelativePath);
        }

        $legacyPath = storage_path('app/ebooks/' . $ebookFile);

        if (file_exists($legacyPath)) {
            return $legacyPath;
        }

        return null;
    }

    private function buildDownloadFilename(string $bookTitle, string $extension): string
    {
        $safeTitle = Str::of($bookTitle)
            ->replaceMatches('/[\\\\\/:*?"<>|]+/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->limit(120, '');

        if ($safeTitle->isEmpty()) {
            $safeTitle = Str::of('ebook');
        }

        $safeExtension = strtolower(trim($extension));

        return $safeExtension !== ''
            ? $safeTitle . '.' . $safeExtension
            : (string) $safeTitle;
    }
}
