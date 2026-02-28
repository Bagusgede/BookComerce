<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Book;
use App\Models\Author;
use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard with analytics
     */
    public function index()
    {
        // Get date range for analytics (last 30 days)
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(30);

        // Sales & Orders Analytics
        $totalOrders = Order::whereIn('status', ['paid', 'processing', 'completed'])->count();
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'completed'])
            ->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        // Orders summary
        $orderStats = [
            'total' => $totalOrders,
            'revenue' => $totalRevenue,
            'pending' => $pendingOrders,
            'completed' => $completedOrders,
            'average_order_value' => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0,
        ];

        // Books analytics
        $totalBooks = Book::where('is_active', true)->count();
        $totalEbooks = Book::where('is_active', true)->where('format', '!=', 'physical')->count();
        $totalPhysical = Book::where('is_active', true)->where('format', '!=', 'ebook')->count();
        $lowStockBooks = Book::where('stock', '<=', 10)->where('format', 'physical')->count();

        $bookStats = [
            'total' => $totalBooks,
            'ebooks' => $totalEbooks,
            'physical' => $totalPhysical,
            'low_stock' => $lowStockBooks,
        ];

        // Authors & Content
        $totalAuthors = Author::where('is_active', true)->count();
        $totalBlogPosts = BlogPost::where('is_published', true)->count();
        $publishedToday = BlogPost::whereDate('published_at', today())->count();

        // Recent orders
        $recentOrders = Order::latest()->limit(5)->get();

        // Top selling books
        $topBooks = Book::with(['orderItems' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->whereIn('status', ['paid', 'processing', 'completed']);
            });
        }])
            ->get()
            ->map(function ($book) {
                $book->total_sold = $book->orderItems->sum('quantity');
                return $book;
            })
            ->sortByDesc('total_sold')
            ->take(5);

        // Revenue chart data (last 30 days)
        $revenueData = Order::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->pluck('revenue', 'date');

        // Order status breakdown
        $orderByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Customers & Ebook downloads
        $totalCustomers = Order::distinct('guest_email')->count('guest_email');
        $totalEbookDownloads = DB::table('ebook_deliveries')
            ->where('download_count', '>', 0)
            ->count();

        return view('admin.dashboard', [
            'orderStats' => $orderStats,
            'bookStats' => $bookStats,
            'totalAuthors' => $totalAuthors,
            'totalBlogPosts' => $totalBlogPosts,
            'publishedToday' => $publishedToday,
            'recentOrders' => $recentOrders,
            'topBooks' => $topBooks,
            'revenueData' => $revenueData,
            'orderByStatus' => $orderByStatus,
            'totalCustomers' => $totalCustomers,
            'totalEbookDownloads' => $totalEbookDownloads,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Get chart data for AJAX
     */
    public function chartData()
    {
        $days = 30;
        $startDate = Carbon::now()->subDays($days);

        // Daily revenue
        $revenue = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Daily orders
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'revenue' => $revenue,
            'orders' => $orders,
        ]);
    }

    /**
     * Quick stats for widget
     */
    public function stats()
    {
        return response()->json([
            'total_revenue' => Order::where('status', 'paid')->sum('total'),
            'total_orders' => Order::count(),
            'total_books' => Book::count(),
            'total_customers' => Order::distinct('guest_email')->count('guest_email'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock_books' => Book::where('stock', '<=', 10)->where('format', 'physical')->count(),
        ]);
    }
}
