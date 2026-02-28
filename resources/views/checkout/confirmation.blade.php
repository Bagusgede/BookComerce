@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan - ' . config('app.name'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Success/Status Message -->
            @if ($order->status === 'paid')
                <div class="mb-8 bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <h2 class="text-2xl font-bold text-green-800">Pembayaran Berhasil!</h2>
                    </div>
                    <p class="text-green-700">Pesanan Anda telah kami terima dan pembayaran berhasil diproses.</p>
                </div>
            @elseif($order->status === 'processing')
                <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3a1 1 0 102 0V7zm-1 8a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"
                                clip-rule="evenodd" />
                        </svg>
                        <h2 class="text-2xl font-bold text-blue-800">Pesanan Sedang Dipacking</h2>
                    </div>
                    <p class="text-blue-700">Pembayaran berhasil. Admin sedang menyiapkan paket fisik Anda.</p>
                </div>
            @elseif($order->status === 'shipped')
                <div class="mb-8 bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 3a1 1 0 00-1 1v11a2 2 0 002 2h1a3 3 0 006 0h2a3 3 0 006 0h1a1 1 0 001-1V9.414a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0017.586 6H15V4a1 1 0 00-1-1H3z" />
                        </svg>
                        <h2 class="text-2xl font-bold text-indigo-800">Pesanan Sudah Dikirim</h2>
                    </div>
                    <p class="text-indigo-700">Resi pengiriman sudah tersedia. Cek detail pelacakan di bawah.</p>
                </div>
            @elseif($order->status === 'pending')
                <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <h2 class="text-2xl font-bold text-yellow-800">Pembayaran Menunggu Konfirmasi</h2>
                    </div>
                    <p class="text-yellow-700">Status pembayaran Anda sedang diverifikasi. Mohon tunggu beberapa saat.</p>
                </div>
            @else
                <div class="mb-8 bg-red-50 border border-red-200 rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-red-800 mb-2">Pembayaran Gagal</h2>
                    <p class="text-red-700">Status pesanan Anda: <strong>{{ $order->status }}</strong></p>
                </div>
            @endif

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Detail Pesanan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Customer Info -->
                    <div>
                        <h3 class="font-bold text-lg mb-4">Informasi Pembeli</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-600">Nomor Pesanan</p>
                                <p class="font-semibold text-base">{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nama</p>
                                <p class="font-semibold">{{ $order->guest_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Email</p>
                                <p class="font-semibold break-all">{{ $order->guest_email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">No. WhatsApp</p>
                                <p class="font-semibold">{{ $order->guest_phone }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Alamat</p>
                                <p class="font-semibold text-xs">
                                    {{ $order->guest_address }}<br>
                                    {{ $order->guest_city }} {{ $order->guest_postal_code }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div>
                        <h3 class="font-bold text-lg mb-4">Status Pesanan</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-600">Status Pembayaran</p>
                                <p
                                    class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-semibold
                                @if ($order->status === 'paid') bg-green-100 text-green-800
                                @elseif($order->status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800 @endif">
                                    @if ($order->status === 'paid')
                                        Lunas
                                    @elseif($order->status === 'pending')
                                        Menunggu
                                    @elseif($order->status === 'processing')
                                        Diproses
                                    @elseif($order->status === 'shipped')
                                        Dikirim
                                    @else
                                        {{ ucfirst($order->status) }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tanggal Pesanan</p>
                                <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Total Pembayaran</p>
                                <p class="font-bold text-base text-green-600">Rp
                                    {{ number_format($order->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-8">

                <!-- Order Items -->
                <h3 class="font-bold text-lg mb-4">Item Pesanan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Buku</th>
                                <th class="text-center py-2">Format</th>
                                <th class="text-right py-2">Harga</th>
                                <th class="text-center py-2">Jumlah</th>
                                <th class="text-right py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3">
                                        <div>
                                            <p class="font-semibold">{{ $item->book_title }}</p>
                                            @if ($item->book && $item->book->authors->count())
                                                <p class="text-gray-600 text-xs">
                                                    {{ $item->book->authors->pluck('name')->implode(', ') }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center py-3">
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold
                                        @if ($item->format === 'ebook') bg-blue-100 text-blue-800
                                        @elseif($item->format === 'physical')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-purple-100 text-purple-800 @endif">
                                            {{ ucfirst($item->format) }}
                                        </span>
                                    </td>
                                    <td class="text-right py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center py-3">{{ $item->quantity }}</td>
                                    <td class="text-right py-3 font-semibold">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>

                                <!-- Ebook Info if order is paid -->
                                @if ($order->status === 'paid' && ($item->format === 'ebook' || $item->format === 'both'))
                                    <tr class="bg-blue-50">
                                        <td colspan="5" class="py-3 px-4">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm3 1a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <div class="text-sm text-blue-800">
                                                    <p class="font-semibold mb-1">📚 Ebook akan dikirim ke email Anda</p>
                                                    <p>Email akan berisi tautan unduhan yang berlaku selama 7 hari. Pastikan
                                                        email {{ $order->guest_email }} dapat menerima email dari kami.</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 border-t pt-8">
                    <div class="grid grid-cols-3 gap-4 text-right">
                        <div>
                            <p class="text-gray-600 text-sm">Subtotal</p>
                            <p class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Ongkos Kirim</p>
                            <p class="font-semibold">
                                @if ($order->shipping_cost == 0)
                                    Gratis
                                @else
                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Total</p>
                            <p class="font-bold text-lg text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                @if ($order->shipping_tracking_number)
                    <div class="mt-6 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <p class="text-indigo-800 font-semibold mb-2">Informasi Pengiriman</p>
                        <p class="text-sm text-indigo-700 mb-1">No. Resi: <strong>{{ $order->shipping_tracking_number }}</strong></p>
                        <p class="text-sm text-indigo-700 mb-1">Kurir: <strong>{{ $order->shipping_method ?? '-' }}</strong></p>
                        @if ($order->shipping_tracking_url)
                            <a href="{{ $order->shipping_tracking_url }}" target="_blank" rel="noopener"
                                class="inline-block mt-2 text-sm text-indigo-700 underline">Lacak paket</a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Next Steps -->
            @if (in_array($order->status, ['paid', 'processing', 'shipped']))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Ebook Email -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-2">📧</span>
                            <h3 class="font-bold">Cek Email</h3>
                        </div>
                        <p class="text-sm text-gray-700">Ebook Anda akan dikirim ke email yang terdaftar dalam waktu
                            singkat.</p>
                    </div>

                    <!-- Tracking -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-2">📦</span>
                            <h3 class="font-bold">Status Pengiriman</h3>
                        </div>
                        <p class="text-sm text-gray-700">Untuk buku fisik, Anda akan menerima notifikasi pengiriman via
                            WhatsApp.</p>
                    </div>

                    <!-- Support -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-2">💬</span>
                            <h3 class="font-bold">Bantuan</h3>
                        </div>
                        <p class="text-sm text-gray-700">Jika ada pertanyaan, hubungi kami via WhatsApp atau email.</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-4 justify-center">
                <a href="{{ route('home') }}"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    ← Kembali ke Beranda
                </a>
                @if ($order->status !== 'paid')
                    <form action="{{ route('checkout.cancel', $order) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-6 py-3 border border-red-500 text-red-500 hover:bg-red-50 rounded-lg font-semibold transition"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif
            </div>

            <!-- Info Box -->
            <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h3 class="font-bold mb-3">❓ Informasi Penting</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li>✓ Email konfirmasi telah dikirim ke {{ $order->guest_email }}</li>
                    <li>✓ Simpan nomor pesanan <strong>{{ $order->order_number }}</strong> untuk referensi</li>
                    <li>✓ Link download ebook berlaku selama 7 hari sejak pembayaran</li>
                    <li>✓ Untuk pertanyaan, hubungi: {{ config('app.support_email') }} atau
                        {{ config('app.support_phone') }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
