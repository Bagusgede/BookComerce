@extends('layouts.app')

@section('title', 'Pembayaran - ' . config('app.name'))

@section('content')
@php
    $displaySubtotal = $order->subtotal ?? $order->orderItems->sum('subtotal');
@endphp
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-2">Pembayaran</h1>
        <p class="text-gray-600 mb-8">Nomor Pesanan: <strong>{{ $order->order_number }}</strong></p>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
                <h2 class="text-xl font-bold mb-4">Detail Pesanan</h2>

                <div class="space-y-4 mb-6">
                    <div>
                        <p class="text-gray-600 text-sm">Nama Pembeli</p>
                        <p class="font-semibold">{{ $order->guest_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-semibold">{{ $order->guest_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">No. WhatsApp</p>
                        <p class="font-semibold">{{ $order->guest_phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Alamat</p>
                        <p class="font-semibold text-sm">
                            {{ $order->guest_address }}, {{ $order->guest_city }} {{ $order->guest_postal_code }}
                        </p>
                    </div>
                </div>

                <hr class="my-6">

                <h3 class="font-bold mb-4">Item Pesanan</h3>
                <div class="space-y-3">
                    @foreach($order->orderItems as $item)
                        <div class="flex justify-between text-sm">
                            <span>
                                {{ $item->book_title }} 
                                <span class="text-gray-500">({{ $item->quantity }}x)</span>
                                <br>
                                <span class="inline-block mt-1 px-2 py-1 rounded text-xs
                                    @if($item->format === 'ebook')
                                        bg-blue-100 text-blue-800
                                    @elseif($item->format === 'physical')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($item->format) }}
                                </span>
                            </span>
                            <span class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment -->
            <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-3">
                <h2 class="text-xl font-bold mb-4">Ringkasan Pembayaran</h2>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">Rp {{ number_format($displaySubtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-semibold">
                            @if($order->shipping_cost == 0)
                                Gratis
                            @else
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="border-t pt-4 mb-8">
                    <div class="flex justify-between text-lg items-center">
                        <span class="font-bold">Total Pembayaran</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Midtrans Snap Button -->
                <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden bg-white">
                    <div id="snap-container" class="min-h-[640px]"></div>
                </div>

                <!-- Alert -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <strong>⚠️ Perhatian:</strong> Klik tombol pembayaran di atas untuk melanjutkan ke Midtrans. 
                        Anda akan diarahkan ke halaman pembayaran yang aman.
                    </p>
                </div>

                <a href="{{ route('checkout.index') }}" 
                   class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-lg text-center hover:bg-gray-50 text-sm">
                    ← Kembali ke Checkout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch("{{ route('checkout.snap-token', $order) }}", {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            snap.embed(data.snap_token, {
                embedId: 'snap-container',
                onSuccess: function(result) {
                    // Pembayaran berhasil
                    window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id + "&status_code=200";
                },
                onPending: function(result) {
                    // Pembayaran menunggu
                    console.log('Menunggu: ', result);
                },
                onError: function(result) {
                    // Pembayaran gagal
                    console.log('Galat: ', result);
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function() {
                    console.log('Pelanggan menutup popup sebelum menyelesaikan pembayaran');
                }
            });
        } else {
            alert('Gagal memuat metode pembayaran. Silakan reload halaman ini.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan reload halaman.');
    });
});
</script>
@endsection
