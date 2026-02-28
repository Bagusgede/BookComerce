@extends('admin.layout')

@section('title', 'Detail Pesanan ' . $order->order_number)
@section('page-title', 'Detail Pesanan ' . $order->order_number)

@section('content')
    <style>
        .order-status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        .order-status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .order-status-processing {
            background: #fef3c7;
            color: #92400e;
        }

        .order-status-cancelled {
            background: #fee2e2;
            color: #7f1d1d;
        }

        .order-status-default {
            background: #dbeafe;
            color: #0c4a6e;
        }
    </style>
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Main Content -->
        <div>
            <!-- Order Info -->
            <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
                    <div>
                        <h2 style="margin: 0 0 0.5rem 0;">{{ $order->order_number }}</h2>
                        <p style="color: #6b7280; margin: 0;">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <span
                        class="order-status-badge @if ($order->status === 'completed') order-status-completed @elseif($order->status === 'processing') order-status-processing @elseif($order->status === 'cancelled') order-status-cancelled @else order-status-default @endif">
                        @if ($order->status === 'pending')
                            ⏳ Menunggu
                        @elseif($order->status === 'paid')
                            ✓ Dibayar
                        @elseif($order->status === 'processing')
                            ⚙️ Diproses
                        @elseif($order->status === 'completed')
                            ✅ Selesai
                        @elseif($order->status === 'cancelled')
                            ❌ Dibatalkan
                        @endif
                    </span>
                </div>

                <!-- Customer Info -->
                <div style="background: #f9fafb; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0 0 1rem 0; font-size: 1rem;">Data Pelanggan</h3>
                    <p style="margin: 0.5rem 0;"><strong>{{ $order->guest_name }}</strong></p>
                    <p style="margin: 0.5rem 0; color: #6b7280;">📧 {{ $order->guest_email }}</p>
                    <p style="margin: 0.5rem 0; color: #6b7280;">📱 {{ $order->guest_phone ?? '-' }}</p>
                    <p style="margin: 0.5rem 0; color: #6b7280;">📍 {{ $order->guest_address }}, {{ $order->guest_city }}
                    </p>
                </div>

                <!-- Items -->
                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Produk yang Dipesan</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Produk</th>
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Qty</th>
                            <th style="padding: 0.75rem; text-align: right; font-weight: 600;">Harga</th>
                            <th style="padding: 0.75rem; text-align: right; font-weight: 600;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 1rem;">
                                    <div style="font-weight: 600;">{{ $item->book->title }}</div>
                                    <div style="font-size: 0.875rem; color: #6b7280;">
                                        @if ($item->format === 'physical')
                                            Fisik
                                        @elseif($item->format === 'ebook')
                                            Ebook
                                        @else
                                            Keduanya
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 1rem; text-align: center;">{{ $item->quantity }}</td>
                                <td style="padding: 1rem; text-align: right;">
                                    Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td style="padding: 1rem; text-align: right; font-weight: 600;">
                                    Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Payment Info -->
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-top: 0;">Informasi Pembayaran</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">ID Transaksi</td>
                        <td style="padding: 1rem; font-family: monospace;">{{ $order->transaction_id ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Metode</td>
                        <td style="padding: 1rem;">{{ $order->payment_method ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Status Pembayaran</td>
                        <td style="padding: 1rem;">
                            @if ($order->status === 'paid' || $order->status === 'completed')
                                <span style="color: #059669;">✓ Lunas</span>
                            @else
                                <span style="color: #dc2626;">✗ Belum Bayar</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Summary -->
            <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="margin-top: 0;">Ringkasan Pesanan</h3>
                <div style="border-bottom: 1px solid #e5e7eb; padding: 1rem 0;">
                    <p style="color: #6b7280; margin: 0 0 0.5rem 0;">Subtotal</p>
                    <p style="font-size: 1.25rem; font-weight: 700; margin: 0;">
                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="margin-top: 0;">Aksi</h3>

                @if ($order->status !== 'completed' && $order->status !== 'cancelled')
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" style="margin-bottom: 1rem;">
                        @csrf @method('PATCH')
                        <select name="status"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; margin-bottom: 0.5rem;">
                            <option value="pending" @selected($order->status === 'pending')>Menunggu</option>
                            <option value="paid" @selected($order->status === 'paid')>Dibayar</option>
                            <option value="processing" @selected($order->status === 'processing')>Diproses</option>
                            <option value="completed" @selected($order->status === 'completed')>Selesai</option>
                            <option value="cancelled" @selected($order->status === 'cancelled')>Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn btn-primary"
                            style="width: 100%; padding: 0.75rem; cursor: pointer;">Perbarui Status</button>
                    </form>
                @endif

                @foreach ($order->orderItems->where('format', '!=', 'physical') as $item)
                    @if ($item->ebookDelivery)
                        <form method="POST" action="{{ route('admin.ebook-deliveries.resend', $item->ebookDelivery) }}"
                            style="margin-bottom: 1rem;">
                            @csrf
                            <button type="submit" class="btn btn-primary"
                                style="width: 100%; padding: 0.75rem; background: #3b82f6; cursor: pointer;">📧 Kirim Ulang
                                Ebook</button>
                        </form>
                    @endif
                @endforeach

                @if ($order->orderItems->whereIn('format', ['physical', 'both'])->count() > 0)
                    <div style="border-top: 1px solid #e5e7eb; margin-top: 1rem; padding-top: 1rem;">
                        <h4 style="margin: 0 0 0.75rem 0;">Input Resi Pengiriman</h4>
                        <form method="POST" action="{{ route('admin.orders.submit-shipping', $order) }}" style="display: grid; gap: 0.5rem;">
                            @csrf
                            <input type="text" name="shipping_method" value="{{ old('shipping_method', $order->shipping_method) }}"
                                placeholder="Kurir (contoh: JNE)"
                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->shipping_tracking_number) }}"
                                placeholder="Nomor resi" required
                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            <input type="url" name="tracking_url" value="{{ old('tracking_url', $order->shipping_tracking_url) }}"
                                placeholder="Link pelacakan (opsional)"
                                style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            <button type="submit" class="btn btn-primary"
                                style="width: 100%; padding: 0.75rem; background: #2563eb; cursor: pointer;">Simpan Resi & Kirim Notifikasi</button>
                        </form>

                        @if ($order->shipping_tracking_number)
                            <p style="margin: 0.75rem 0 0 0; color: #374151; font-size: 0.875rem;">
                                Resi aktif: <strong>{{ $order->shipping_tracking_number }}</strong>
                            </p>
                        @endif
                    </div>
                @endif

                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary"
                    style="display: block; width: 100%; padding: 0.75rem; text-align: center; background: #6b7280; text-decoration: none; margin-top: 1rem;">←
                    Kembali</a>
            </div>

            <!-- Metadata -->
            <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.375rem; font-size: 0.875rem; color: #6b7280;">
                <p style="margin: 0 0 0.5rem 0;">Dibuat: {{ $order->created_at->format('d M Y H:i') }}</p>
                <p style="margin: 0;">Diupdate: {{ $order->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
