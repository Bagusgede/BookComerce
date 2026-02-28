<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display customers list (guests)
     */
    public function index(Request $request)
    {
        $query = Order::selectRaw('guest_email, guest_name, guest_phone, guest_city, COUNT(*) as total_orders, SUM(total) as total_spent, MAX(created_at) as last_order')
            ->whereNotNull('guest_email')
            ->groupBy('guest_email', 'guest_name', 'guest_phone', 'guest_city');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('guest_email', 'like', "%{$search}%")
                    ->orWhere('guest_name', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('guest_city', $request->city);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'last_order');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(15);

        // Get unique cities for filter
        $cities = Order::selectRaw('DISTINCT guest_city')
            ->whereNotNull('guest_city')
            ->orderBy('guest_city')
            ->pluck('guest_city');

        return view('admin.customers.index', [
            'customers' => $customers,
            'cities' => $cities,
            'search' => $request->search,
            'selectedCity' => $request->city,
        ]);
    }

    /**
     * Show customer detail
     */
    public function show(Request $request)
    {
        $email = $request->query('email');

        $orders = Order::where('guest_email', $email)
            ->with('orderItems.book')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Pelanggan tidak ditemukan');
        }

        $customer = [
            'email' => $email,
            'name' => $orders->first()->guest_name,
            'phone' => $orders->first()->guest_phone,
            'city' => $orders->first()->guest_city,
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total'),
            'first_order' => $orders->last()->created_at,
            'last_order' => $orders->first()->created_at,
        ];

        return view('admin.customers.show', [
            'customer' => $customer,
            'orders' => $orders,
        ]);
    }

    /**
     * Send message to customer
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Send email manually or via queue
        \Illuminate\Support\Facades\Mail::raw($validated['message'], function ($mail) use ($validated) {
            $mail->to($validated['email'])
                ->subject($validated['subject']);
        });

        return back()->with('success', 'Pesan berhasil dikirim ke ' . $validated['email']);
    }

    /**
     * Export customers to CSV
     */
    public function export(Request $request)
    {
        $customers = Order::selectRaw('guest_email, guest_name, guest_phone, guest_city, COUNT(*) as total_orders, SUM(total) as total_spent, MAX(created_at) as last_order')
            ->whereNotNull('guest_email')
            ->groupBy('guest_email', 'guest_name', 'guest_phone', 'guest_city')
            ->orderBy('total_spent', 'desc')
            ->get();

        $filename = 'customers_' . date('Y-m-d_His') . '.csv';
        $csv = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Headers
        fputcsv($csv, ['Email', 'Name', 'Phone', 'City', 'Total Orders', 'Total Spent', 'Last Order']);

        foreach ($customers as $customer) {
            fputcsv($csv, [
                $customer->guest_email,
                $customer->guest_name,
                $customer->guest_phone,
                $customer->guest_city,
                $customer->total_orders,
                $customer->total_spent,
                $customer->last_order->format('Y-m-d H:i'),
            ]);
        }

        fclose($csv);
        exit();
    }
}
