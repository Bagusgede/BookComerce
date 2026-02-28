@extends('admin.layout')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('admin.orders.analytics') }}" class="btn btn-primary">📊 Analitik</a>
            <a href="{{ route('admin.orders.export') }}" class="btn btn-primary" style="margin-left: 0.5rem;">📥 Export CSV</a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <input type="text" name="search" placeholder="Cari nomor pesanan, email, atau nama..."
                    value="{{ $search }}"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>
            <div>
                <select name="status"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected($selectedStatus === 'pending')>Menunggu</option>
                    <option value="paid" @selected($selectedStatus === 'paid')>Dibayar</option>
                    <option value="processing" @selected($selectedStatus === 'processing')>Diproses</option>
                    <option value="completed" @selected($selectedStatus === 'completed')>Selesai</option>
                    <option value="cancelled" @selected($selectedStatus === 'cancelled')>Dibatalkan</option>
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Cari</button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <p style="margin: 0; font-weight: 600;">{{ $order->order_number }}</p>
                        </td>
                        <td>
                            <p style="margin: 0; font-weight: 600;">{{ $order->guest_name }}</p>
                            <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">{{ $order->guest_email }}</p>
                            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">{{ $order->guest_city }}</p>
                        </td>
                        <td>
                            <p style="margin: 0; font-weight: 600;">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </td>
                        <td>
                            {{ $order->orderItems->count() }} item
                        </td>
                        <td>
                            <span
                                class="badge @if ($order->status === 'paid') badge-success @elseif($order->status === 'pending') badge-warning @elseif($order->status === 'processing') badge-info @else badge-danger @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td style="font-size: 0.875rem; color: #6b7280;">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-primary"
                                style="font-size: 0.75rem;">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">
                            Tidak ada pesanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
@endsection
