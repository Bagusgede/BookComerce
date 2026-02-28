@extends('admin.layout')

@section('title', 'Daftar Pembayaran')
@section('page-title', 'Manajemen Pembayaran')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Total Transaksi</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0;">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Berhasil</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0; color: #059669;">
                {{ $stats['success'] ?? 0 }}</p>
        </div>
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Gagal</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0; color: #dc2626;">
                {{ $stats['failed'] ?? 0 }}</p>
        </div>
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Total Pendapatan</p>
            <p style="font-size: 1.25rem; font-weight: 700; margin: 0.5rem 0 0 0;">
                Rp{{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card" style="padding: 1rem; margin-bottom: 2rem;">
        <form method="GET" style="display: grid; grid-template-columns: 1fr 150px 150px; gap: 1rem;">
            <input type="search" name="search" placeholder="Cari ID transaksi atau email..."
                value="{{ request('search') }}"
                style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            <select name="status" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">-- Status --</option>
                <option value="success" @selected(request('status') === 'success')>Berhasil</option>
                <option value="failed" @selected(request('status') === 'failed')>Gagal</option>
                <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; cursor: pointer;">🔍 Cari</button>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">ID Transaksi</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Pesanan</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: right; font-weight: 600;">Jumlah</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Metode</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Tanggal</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; font-family: monospace; font-size: 0.75rem;">
                            {{ Str::limit($payment->transaction_id, 15) }}</td>
                        <td style="padding: 1rem; font-weight: 600;">{{ $payment->order->order_number }}</td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #6b7280;">{{ $payment->order->guest_email }}
                        </td>
                        <td style="padding: 1rem; text-align: right; font-weight: 600;">
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td style="padding: 1rem; font-size: 0.875rem;">{{ $payment->payment_method ?? '-' }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span
                                class="badge @if ($payment->status === 'success') badge-success @elseif($payment->status === 'failed') badge-danger @else badge-warning @endif">
                                @if ($payment->status === 'success')
                                    ✓ Berhasil
                                @elseif($payment->status === 'failed')
                                    ✗ Gagal
                                @else
                                    ⏳ Menunggu
                                @endif
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #6b7280;">
                            {{ $payment->created_at->format('d M Y H:i') }}</td>
                        <td style="padding: 1rem; text-align: center; font-size: 0.875rem;">
                            @if ($payment->status === 'pending')
                                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit"
                                        style="color: #3b82f6; background: none; border: none; cursor: pointer; text-decoration: underline;">Verifikasi</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($payments->hasPages())
            <div style="padding: 1rem; display: flex; justify-content: center;">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection
