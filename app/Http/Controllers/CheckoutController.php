<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        $physicalQuantity = 0;

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

            if (in_array($book->format, ['physical', 'both'], true)) {
                $physicalQuantity += (int) $cartItem['quantity'];
            }
        }

        $shipping_cost = $this->calculateShippingCost($physicalQuantity);
        $total = $subtotal + $shipping_cost;

        $shippingValidation = $this->validateShippingConfiguration($physicalQuantity > 0);

        if (!$shippingValidation['is_valid'] && $shippingValidation['message']) {
            Log::warning('Shipping configuration incomplete, using fallback shipping', [
                'message' => $shippingValidation['message'],
            ]);
        }

        return view('checkout.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total,
            'has_physical_items' => $physicalQuantity > 0,
            'shipping_validation' => $shippingValidation,
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
            'agree' => 'accepted',
        ], [
            'agree.accepted' => 'Anda harus menyetujui Kebijakan Privasi untuk melanjutkan checkout.',
        ]);

        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong');
        }

        try {
            DB::beginTransaction();

            // Calculate order totals
            $subtotal = 0;
            $physicalQuantity = 0;
            foreach ($cartItems as $bookId => $cartItem) {
                $book = Book::find($bookId);
                if ($book) {
                    $final_price = $book->discount_price ?? $book->price;
                    $subtotal += $final_price * $cartItem['quantity'];

                    if (in_array($book->format, ['physical', 'both'], true)) {
                        $physicalQuantity += (int) $cartItem['quantity'];
                    }
                }
            }

            $shippingData = $this->calculateShippingData($physicalQuantity, $validated['city']);
            $shipping_cost = $shippingData['cost'];
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
                $orderData['total_amount'] = $total;
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

            if (Schema::hasColumn('orders', 'shipping_method')) {
                $orderData['shipping_method'] = $shippingData['method'];
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

            if ((int) round($order->shipping_cost) > 0) {
                $itemDetails[] = [
                    'id' => 'shipping',
                    'price' => (int) round($order->shipping_cost),
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim',
                ];
            }

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

    private function calculateShippingCost(int $physicalQuantity, ?string $city = null): int
    {
        return $this->calculateShippingData($physicalQuantity, $city)['cost'];
    }

    private function calculateShippingData(int $physicalQuantity, ?string $city = null): array
    {
        if ($physicalQuantity <= 0) {
            return [
                'cost' => 0,
                'method' => null,
                'source' => 'none',
            ];
        }

        $defaultBaseCost = (int) config('shipping.default_base_cost', 18000);
        $additionalPerItem = (int) config('shipping.additional_per_item', 5000);
        $cityOverrides = (array) config('shipping.city_overrides', []);

        $normalizedCity = strtolower(trim((string) $city));
        $baseCost = $defaultBaseCost;

        if ($normalizedCity !== '' && array_key_exists($normalizedCity, $cityOverrides)) {
            $baseCost = (int) $cityOverrides[$normalizedCity];
        }

        $extraItems = max(0, $physicalQuantity - 1);
        $fallbackCost = $baseCost + ($extraItems * $additionalPerItem);

        $liveRate = $this->getLiveShippingRate($physicalQuantity, $city);
        if ($liveRate !== null) {
            return $liveRate;
        }

        return [
            'cost' => $fallbackCost,
            'method' => 'AUTO_FLAT_RATE',
            'source' => 'fallback',
        ];
    }

    private function getLiveShippingRate(int $physicalQuantity, ?string $destinationCity): ?array
    {
        $provider = (string) config('shipping.provider', 'flat');
        if ($provider !== 'rajaongkir') {
            return null;
        }

        $apiKey = (string) config('shipping.rajaongkir.api_key');
        $originCityId = (string) config('shipping.rajaongkir.origin_city_id');
        $originCityName = (string) config('shipping.rajaongkir.origin_city_name', '');
        $courier = strtolower((string) config('shipping.rajaongkir.courier', 'jne'));
        $preferredService = strtoupper((string) config('shipping.rajaongkir.preferred_service', 'REG'));
        $timeoutSeconds = (int) config('shipping.rajaongkir.timeout_seconds', 8);
        $cacheMinutes = (int) config('shipping.rajaongkir.cache_minutes', 60);

        if ($apiKey === '' || !$destinationCity) {
            return null;
        }

        if ($originCityId === '' && $originCityName !== '') {
            $originCityId = $this->resolveRajaOngkirCityId($originCityName, $apiKey, $timeoutSeconds, $cacheMinutes);
        }

        if ($originCityId === '') {
            return null;
        }

        $destinationCityId = $this->resolveRajaOngkirCityId($destinationCity, $apiKey, $timeoutSeconds, $cacheMinutes);
        if (!$destinationCityId) {
            return null;
        }

        $weightPerItem = (int) config('shipping.default_weight_per_item_gram', 500);
        $minimumWeight = (int) config('shipping.minimum_weight_gram', 500);
        $totalWeight = max($minimumWeight, $physicalQuantity * max(1, $weightPerItem));

        $cacheKey = 'shipping:rajaongkir:cost:' . md5($originCityId . '|' . $destinationCityId . '|' . $courier . '|' . $totalWeight . '|' . $preferredService);

        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function () use (
            $apiKey,
            $originCityId,
            $destinationCityId,
            $courier,
            $preferredService,
            $totalWeight,
            $timeoutSeconds
        ) {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout($timeoutSeconds)
                ->retry(1, 200)
                ->withHeaders(['key' => $apiKey])
                ->asForm()
                ->post('https://api.rajaongkir.com/starter/cost', [
                    'origin' => $originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $totalWeight,
                    'courier' => $courier,
                ]);

            if (!$response->ok()) {
                Log::warning('RajaOngkir cost API failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $results = data_get($response->json(), 'rajaongkir.results.0.costs', []);
            if (!is_array($results) || empty($results)) {
                return null;
            }

            $selected = null;

            foreach ($results as $service) {
                $serviceCode = strtoupper((string) data_get($service, 'service', ''));
                $costValue = (int) data_get($service, 'cost.0.value', 0);

                if ($costValue <= 0) {
                    continue;
                }

                if ($serviceCode === $preferredService) {
                    $selected = [
                        'cost' => $costValue,
                        'method' => strtoupper($courier) . '-' . $serviceCode,
                        'source' => 'rajaongkir',
                    ];
                    break;
                }

                if ($selected === null || $costValue < $selected['cost']) {
                    $selected = [
                        'cost' => $costValue,
                        'method' => strtoupper($courier) . '-' . $serviceCode,
                        'source' => 'rajaongkir',
                    ];
                }
            }

            return $selected;
        });
    }

    private function resolveRajaOngkirCityId(string $cityInput, string $apiKey, int $timeoutSeconds, int $cacheMinutes): ?string
    {
        $normalizedInput = $this->normalizeCityName($cityInput);
        if ($normalizedInput === '') {
            return null;
        }

        $cities = Cache::remember('shipping:rajaongkir:cities', now()->addMinutes(max(60, $cacheMinutes)), function () use ($apiKey, $timeoutSeconds) {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout($timeoutSeconds)
                ->retry(1, 200)
                ->withHeaders(['key' => $apiKey])
                ->get('https://api.rajaongkir.com/starter/city');

            if (!$response->ok()) {
                Log::warning('RajaOngkir city API failed', [
                    'status' => $response->status(),
                ]);

                return [];
            }

            return (array) data_get($response->json(), 'rajaongkir.results', []);
        });

        if (empty($cities)) {
            return null;
        }

        foreach ($cities as $city) {
            $cityName = $this->normalizeCityName((string) data_get($city, 'city_name', ''));
            if ($cityName === $normalizedInput) {
                return (string) data_get($city, 'city_id');
            }
        }

        foreach ($cities as $city) {
            $cityName = $this->normalizeCityName((string) data_get($city, 'city_name', ''));
            if ($cityName !== '' && (str_contains($cityName, $normalizedInput) || str_contains($normalizedInput, $cityName))) {
                return (string) data_get($city, 'city_id');
            }
        }

        return null;
    }

    private function normalizeCityName(string $name): string
    {
        $normalized = Str::lower(trim($name));
        $normalized = str_replace(['kabupaten', 'kab.', 'kota', '.', ','], ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?: '';

        return trim($normalized);
    }

    private function validateShippingConfiguration(bool $requiresPhysicalShipping): array
    {
        if (!$requiresPhysicalShipping) {
            return [
                'is_valid' => true,
                'message' => null,
                'provider' => (string) config('shipping.provider', 'flat'),
                'mode' => 'digital-only',
            ];
        }

        $provider = (string) config('shipping.provider', 'flat');

        if ($provider !== 'rajaongkir') {
            return [
                'is_valid' => true,
                'message' => 'Ongkir live API tidak aktif. Sistem menggunakan ongkir fallback.',
                'provider' => $provider,
                'mode' => 'fallback',
            ];
        }

        $apiKey = (string) config('shipping.rajaongkir.api_key', '');
        $originCityId = (string) config('shipping.rajaongkir.origin_city_id', '');
        $originCityName = (string) config('shipping.rajaongkir.origin_city_name', '');

        if ($apiKey === '') {
            return [
                'is_valid' => false,
                'message' => 'RAJAONGKIR_API_KEY belum diisi. Ongkir akan memakai fallback.',
                'provider' => $provider,
                'mode' => 'fallback',
            ];
        }

        if ($originCityId === '' && $originCityName === '') {
            return [
                'is_valid' => false,
                'message' => 'Origin RajaOngkir belum diset (RAJAONGKIR_ORIGIN_CITY_ID atau RAJAONGKIR_ORIGIN_CITY_NAME). Ongkir akan memakai fallback.',
                'provider' => $provider,
                'mode' => 'fallback',
            ];
        }

        return [
            'is_valid' => true,
            'message' => null,
            'provider' => $provider,
            'mode' => 'live',
        ];
    }
}
