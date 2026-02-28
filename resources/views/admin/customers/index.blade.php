@extends('admin.layout')

@section('title', 'Pelanggan Tamu')
@section('page-title', 'Manajemen Pelanggan Tamu')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <p style="color: #6b7280; margin: 0;">Total Pelanggan Unik: <strong>{{ $customers->count() }}</strong></p>
        </div>
        <a href="{{ route('admin.customers.index', ['export' => 'csv']) }}" class="btn btn-primary"
            style="text-decoration: none; padding: 0.75rem 1.5rem;">
            📥 Export CSV
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card" style="padding: 1rem; margin-bottom: 2rem;">
        <form method="GET" style="display: flex; gap: 1rem;">
            <input type="search" name="search" placeholder="Cari email atau nama..." value="{{ request('search') }}"
                style="flex: 1; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; cursor: pointer;">🔍 Cari</button>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Nama</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Total Pesanan</th>
                    <th style="padding: 1rem; text-align: right; font-weight: 600;">Total Belanja</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Pembelian Terakhir</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; font-family: monospace; font-size: 0.875rem;">{{ $customer->guest_email }}
                        </td>
                        <td style="padding: 1rem; font-weight: 600;">{{ $customer->guest_name }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span
                                style="background: #dbeafe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                {{ $customer->orders_count ?? 0 }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right; font-weight: 600;">
                            Rp{{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</td>
                        <td style="padding: 1rem; color: #6b7280; font-size: 0.875rem;">
                            {{ $customer->last_order_date?->format('d M Y') ?? '-' }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <form method="POST"
                                action="{{ route('admin.customers.send-message', ['email' => $customer->guest_email]) }}"
                                style="display: inline;">
                                @csrf
                                <input type="hidden" name="email" value="{{ $customer->guest_email }}">
                                <input type="hidden" name="subject" value="Halo {{ $customer->guest_name ?? 'Pelanggan' }}, terima kasih sudah berbelanja di BookComerce">
                                <input type="hidden" name="message" value="Halo {{ $customer->guest_name ?? 'Pelanggan' }}, terima kasih sudah berbelanja di BookComerce. Jika ada pertanyaan terkait pesanan, silakan balas email ini.">
                                <button type="submit"
                                    onclick="return confirm('Kirim pesan cepat ke {{ $customer->guest_email }}?')"
                                    style="color: #3b82f6; background: none; border: none; cursor: pointer; text-decoration: underline;"
                                    title="Kirim email template singkat ke pelanggan">📧 Pesan Cepat</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada pelanggan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($customers instanceof Illuminate\Pagination\Paginator && $customers->hasPages())
            <div style="padding: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
@endsection
