@extends('layouts.app')

@section('title', 'Pembayaran - ' . config('app.name'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <h1 class="text-3xl font-bold mb-6">Ringkasan Pesanan</h1>

                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3">Buku</th>
                                <th class="text-center py-3">Format</th>
                                <th class="text-right py-3">Harga</th>
                                <th class="text-center py-3">Qty</th>
                                <th class="text-right py-3">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-4">
                                        <div class="flex gap-4">
                                            @if ($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}"
                                                    alt="{{ $item['title'] }}" class="w-16 h-20 object-cover rounded">
                                            @else
                                                <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">Tanpa Gambar</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold">{{ $item['title'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-sm 
                                        @if ($item['format'] === 'ebook') bg-blue-100 text-blue-800
                                        @elseif($item['format'] === 'physical')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-purple-100 text-purple-800 @endif">
                                            {{ ucfirst($item['format']) }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right font-semibold">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-center">{{ $item['quantity'] }}</td>
                                    <td class="py-4 text-right font-bold">
                                        Rp {{ number_format($item['total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-500">
                                        Keranjang Anda kosong
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Continue Shopping -->
                <div class="flex gap-4">
                    <a href="{{ route('books.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        ← Lanjut Belanja
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                    <h2 class="text-xl font-bold mb-4">Ringkasan Harga</h2>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-semibold">
                                @if (!$has_physical_items)
                                    Gratis (E-Book)
                                @else
                                    Rp {{ number_format($shipping_cost, 0, ',', '.') }} <span class="text-xs text-gray-500">(Estimasi)</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between text-lg">
                            <span class="font-bold">Total Bayar</span>
                            <span class="font-bold text-green-600">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800">
                            <strong>ℹ️ Informasi:</strong> Anda tidak perlu masuk akun. Silakan lanjutkan ke tahap berikutnya
                            untuk mengisi data diri.
                            @if ($has_physical_items)
                                Estimasi ongkir akan dihitung otomatis berdasarkan kota tujuan sebelum pembayaran Midtrans.
                            @endif
                        </p>
                    </div>

                    @if ($has_physical_items && isset($shipping_validation))
                        @if (($shipping_validation['mode'] ?? '') === 'live')
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-green-800">
                                    <strong>✓ Ongkir Live Aktif:</strong> Estimasi ongkir menggunakan API kurir secara otomatis.
                                </p>
                            </div>
                        @elseif (!empty($shipping_validation['message']))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-yellow-800">
                                    <strong>⚠️ Mode Fallback:</strong> {{ $shipping_validation['message'] }}
                                </p>
                            </div>
                        @endif
                    @endif

                    <a href="{{ route('checkout.guest-form') }}"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg text-center transition">
                        Lanjutkan ke Formulir Data Diri →
                    </a>

                    <!-- Additional Info -->
                    <div class="mt-6 pt-6 border-t space-y-2 text-sm text-gray-600">
                        <p>✓ Proses pembayaran aman</p>
                        <p>✓ Pembayaran via Midtrans</p>
                        <p>✓ Ebook dikirim ke email</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
