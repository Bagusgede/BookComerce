<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    /**
     * Show checkout page with cart items
     */
    public function index(Request $request)
    {
        // Get cart items from session
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong');
        }

        // Map cart items with book data
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $bookId => $cartItem) {
            $book = Book::find($bookId);

            if (!$book) {
                unset($cartItems[$bookId]);
                continue;
            }

            $final_price = $book->discount_price ?? $book->price;
            $item_total = $final_price * $cartItem['quantity'];

            $items[] = [
                'book_id' => $book->id,
                'title' => $book->title,
                'image' => $book->cover_image,
                'format' => $book->format,
                'price' => $final_price,
                'quantity' => $cartItem['quantity'],
                'total' => $item_total,
            ];

            $subtotal += $item_total;
        }

        // Calculate shipping (optional - bisa disesuaikan)
        $shipping_cost = 0; // Bisa ditambahkan logic shipping calculation
        $total = $subtotal + $shipping_cost;

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total,
        ]);
    }

    /**
     * Show guest form to collect customer data
     */
    public function guestForm(Request $request)
    {
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('home');
        }

        return view('checkout.guest-form');
    }

    /**
     * Process guest checkout and create order
     */
    public function processGuest(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string|max:10',
        ]);

        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong');
        }

        try {
            DB::beginTransaction();

            // Calculate order totals
            $subtotal = 0;
            foreach ($cartItems as $bookId => $cartItem) {
                $book = Book::find($bookId);
                if ($book) {
                    $final_price = $book->discount_price ?? $book->price;
                    $subtotal += $final_price * $cartItem['quantity'];
                }
            }

            $shipping_cost = 0;
            $total = $subtotal + $shipping_cost;

            $orderData = [
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'shipping_cost' => $shipping_cost,
                'total' => $total,
            ];

            if (Schema::hasColumn('orders', 'guest_email')) {
                $orderData['guest_email'] = $validated['email'];
            }

            if (Schema::hasColumn('orders', 'guest_name')) {
                $orderData['guest_name'] = $validated['name'];
            }

            if (Schema::hasColumn('orders', 'guest_phone')) {
                $orderData['guest_phone'] = $validated['phone'];
            }

            if (Schema::hasColumn('orders', 'guest_address')) {
                $orderData['guest_address'] = $validated['address'];
            }

            if (Schema::hasColumn('orders', 'guest_city')) {
                $orderData['guest_city'] = $validated['city'];
            }

            if (Schema::hasColumn('orders', 'guest_postal_code')) {
                $orderData['guest_postal_code'] = $validated['postal_code'];
            }

            if (Schema::hasColumn('orders', 'subtotal')) {
                $orderData['subtotal'] = $subtotal;
            }

            if (Schema::hasColumn('orders', 'total_amount')) {
                $orderData['total_amount'] = $subtotal;
            }

            if (Schema::hasColumn('orders', 'shipping_name')) {
                $orderData['shipping_name'] = $validated['name'];
            }

            if (Schema::hasColumn('orders', 'shipping_email')) {
                $orderData['shipping_email'] = $validated['email'];
            }

            if (Schema::hasColumn('orders', 'shipping_phone')) {
                $orderData['shipping_phone'] = $validated['phone'];
            }

            if (Schema::hasColumn('orders', 'shipping_address')) {
                $orderData['shipping_address'] = $validated['address'];
            }

            if (Schema::hasColumn('orders', 'shipping_city')) {
                $orderData['shipping_city'] = $validated['city'];
            }

            if (Schema::hasColumn('orders', 'shipping_postal_code')) {
                $orderData['shipping_postal_code'] = $validated['postal_code'];
            }

            if (Schema::hasColumn('orders', 'user_id')) {
                $orderData['user_id'] = $this->resolveGuestUserId($validated);
            }

            // Create order
            $order = Order::create($orderData);

            // Create order items
            foreach ($cartItems as $bookId => $cartItem) {
                $book = Book::find($bookId);

                if ($book) {
                    $final_price = $book->discount_price ?? $book->price;
                    $format = $book->format === 'printed' ? 'physical' : $book->format;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $book->id,
                        'book_title' => $book->title,
                        'price' => $final_price,
                        'quantity' => $cartItem['quantity'],
                        'format' => $format,
                    ]);
                }
            }

            DB::commit();

            // Clear session cart
            session()->forget('cart');

            // Redirect to Midtrans payment
            return redirect()->route('checkout.payment', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Guest checkout create order failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $errorMessage = app()->isLocal()
                ? 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage()
                : 'Terjadi kesalahan saat membuat pesanan';

            return redirect()->back()->withErrors(['error' => $errorMessage]);
        }
    }

    private function resolveGuestUserId(array $validated): int
    {
        $existingUser = User::where('email', $validated['email'])->first();

        if ($existingUser) {
            return $existingUser->id;
        }

        $guestUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Str::random(24),
        ]);

        return $guestUser->id;
    }

    /**
     * Show payment page (Midtrans)
     */
    public function payment(Order $order)
    {
        // Check if order belongs to guest
        if (!$order->guest_email) {
            abort(403, 'Order tidak ditemukan');
        }

        // Get order items
        $order->load('orderItems.book');

        return view('checkout.payment', [
            'order' => $order,
        ]);
    }

    /**
     * Generate Midtrans Snap token for checkout payment page
     */
    public function snapToken(Order $order)
    {
        if (!$order->guest_email) {
            return response()->json(['message' => 'Order tidak ditemukan'], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order tidak bisa diproses untuk pembayaran'], 422);
        }

        $order->load('orderItems');

        try {
            $transactionId = $order->transaction_id ?: $order->order_number;

            if (!$order->transaction_id || $order->payment_method !== 'midtrans') {
                $order->update([
                    'transaction_id' => $transactionId,
                    'payment_method' => 'midtrans',
                ]);
            }

            $itemDetails = $order->orderItems->map(function ($item) {
                return [
                    'id' => (string) $item->book_id,
                    'price' => (int) round($item->price),
                    'quantity' => (int) $item->quantity,
                    'name' => mb_strimwidth($item->book_title, 0, 50, '...'),
                ];
            })->values()->all();

            $payload = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => (int) round($order->total),
                ],
                'customer_details' => [
                    'first_name' => $order->guest_name,
                    'email' => $order->guest_email,
                    'phone' => $order->guest_phone,
                ],
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('payment.finish'),
                ],
            ];

            $snapToken = Snap::getSnapToken($payload);

            return response()->json([
                'snap_token' => $snapToken,
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans snap token error', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal membuat token pembayaran',
            ], 500);
        }
    }

    /**
     * Show order confirmation
     */
    public function confirmation(Order $order)
    {
        // Verify order by order number or email
        if (!auth()->check() && !session('guest_order_' . $order->id)) {
            abort(403, 'Anda tidak memiliki akses ke order ini');
        }

        $order->load('orderItems.book');

        return view('checkout.confirmation', [
            'order' => $order,
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order ini tidak bisa dibatalkan');
        }

        DB::beginTransaction();
        try {
            $order->update(['status' => 'cancelled']);

            // Restore book stock
            foreach ($order->orderItems as $item) {
                if ($item->format === 'physical' || $item->format === 'both') {
                    $item->book->increment('stock', $item->quantity);
                }
            }

            DB::commit();

            return redirect()->route('home')->with('success', 'Order dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal membatalkan order']);
        }
    }
}
