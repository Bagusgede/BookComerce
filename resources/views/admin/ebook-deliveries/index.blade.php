@extends('admin.layout')

@section('title', 'Pengiriman Ebook')
@section('page-title', 'Manajemen Pengiriman Ebook')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Total Pengiriman</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0;">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Masih Aktif</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0; color: #059669;">{{ $stats['active'] ?? 0 }}
            </p>
        </div>
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Kadaluarsa</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0; color: #dc2626;">
                {{ $stats['expired'] ?? 0 }}</p>
        </div>
        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Total Download</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0;">{{ $stats['total_downloads'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card" style="padding: 1rem; margin-bottom: 2rem;">
        <form method="GET" style="display: grid; grid-template-columns: 1fr 150px 150px; gap: 1rem;">
            <input type="search" name="search" placeholder="Cari email atau nama buku..." value="{{ request('search') }}"
                style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            <select name="status" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">-- Status --</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="expired" @selected(request('status') === 'expired')>Kadaluarsa</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; cursor: pointer;">🔍 Cari</button>
        </form>
    </div>

    <!-- Deliveries Table -->
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Buku</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Download</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Kadaluarsa</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; font-family: monospace; font-size: 0.875rem;">
                            {{ $delivery->orderItem?->order?->guest_email ?? $delivery->email ?? '-' }}</td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">{{ $delivery->orderItem?->book?->title ?? 'Buku tidak ditemukan' }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Order:
                                {{ $delivery->orderItem?->order?->order_number ?? '-' }}</div>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <span
                                style="background: #dbeafe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                {{ $delivery->download_count ?? 0 }} kali
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem;">{{ $delivery->expired_at->format('d M Y') }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            @if ($delivery->isExpired())
                                <span
                                    style="background: #fee2e2; color: #7f1d1d; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Kadaluarsa</span>
                            @else
                                <span
                                    style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Aktif</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center; font-size: 0.875rem;">
                            @if (!$delivery->isExpired())
                                <form method="POST" action="{{ route('admin.ebook-deliveries.resend', $delivery) }}"
                                    style="display: inline; margin-right: 0.5rem;">
                                    @csrf
                                    <button type="submit"
                                        style="color: #3b82f6; background: none; border: none; cursor: pointer; text-decoration: underline;">Kirim
                                        Ulang</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.ebook-deliveries.destroy', $delivery) }}"
                                style="display: inline;" onsubmit="return confirm('Hapus?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="color: #ef4444; background: none; border: none; cursor: pointer; text-decoration: underline;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada pengiriman
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($deliveries->hasPages())
            <div style="padding: 1rem; display: flex; justify-content: center;">
                {{ $deliveries->links() }}
            </div>
        @endif
    </div>
@endsection
