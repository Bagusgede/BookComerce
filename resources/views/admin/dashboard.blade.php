@extends('admin.layout')

@section('title', 'Dasbor')
@section('page-title', 'Dasbor')

@section('content')
    <!-- Stats Cards -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card stat-card">
            <p>Total Pesanan</p>
            <div class="number">{{ $orderStats['total'] }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Periode: {{ $startDate->format('d M Y') }} -
                {{ $endDate->format('d M Y') }}</p>
        </div>

        <div class="card stat-card">
            <p>Total Penjualan</p>
            <div class="number">Rp {{ number_format($orderStats['revenue'], 0, ',', '.') }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Dari {{ $orderStats['total'] }} pesanan</p>
        </div>

        <div class="card stat-card">
            <p>Rata-rata Pesanan</p>
            <div class="number">Rp {{ number_format($orderStats['average_order_value'], 0, ',', '.') }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Per transaksi</p>
        </div>

        <div class="card stat-card">
            <p>Pesanan Menunggu</p>
            <div class="number" style="color: #f97316;">{{ $orderStats['pending'] }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Status menunggu</p>
        </div>

        <div class="card stat-card">
            <p>Total Pelanggan</p>
            <div class="number">{{ $totalCustomers }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Checkout tanpa akun</p>
        </div>

        <div class="card stat-card">
            <p>Unduhan Ebook</p>
            <div class="number">{{ $totalEbookDownloads }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Total unduhan</p>
        </div>
    </div>

    <!-- Books & Content Stats -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card stat-card">
            <p>Total Buku</p>
            <div class="number">{{ $bookStats['total'] }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Aktif</p>
        </div>

        <div class="card stat-card">
            <p>Buku Digital</p>
            <div class="number">{{ $bookStats['ebooks'] }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Format buku digital</p>
        </div>

        <div class="card stat-card">
            <p>Buku Fisik</p>
            <div class="number">{{ $bookStats['physical'] }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Format cetak</p>
        </div>

        <div class="card stat-card">
            <p>Stok Rendah</p>
            <div class="number" style="color: #ef4444;">{{ $bookStats['low_stock'] }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">≤ 10 unit</p>
        </div>

        <div class="card stat-card">
            <p>Total Penulis</p>
            <div class="number">{{ $totalAuthors }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Aktif</p>
        </div>

        <div class="card stat-card">
            <p>Artikel Blog</p>
            <div class="number">{{ $totalBlogPosts }}</div>
            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">Dipublikasikan hari ini: {{ $publishedToday }}</p>
        </div>
    </div>

    <!-- Top Books & Recent Orders -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Top Selling Books -->
        <div class="card">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h2 style="margin: 0; font-size: 1.125rem;">Buku Terlaris</h2>
            </div>
            <div style="padding: 1.5rem;">
                @forelse($topBooks as $book)
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                        <div>
                            <p style="margin: 0; font-weight: 600;">{{ $book->title }}</p>
                            <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">
                                {{ $book->authors->pluck('name')->implode(', ') }}</p>
                        </div>
                        <span class="badge badge-info">{{ $book->total_sold }} terjual</span>
                    </div>
                @empty
                    <p style="color: #6b7280;">Tidak ada data</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h2 style="margin: 0; font-size: 1.125rem;">Pesanan Terbaru</h2>
            </div>
            <div style="padding: 1.5rem;">
                @forelse($recentOrders as $order)
                    <div
                        style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="margin: 0; font-weight: 600;">{{ $order->order_number }}</p>
                            <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">{{ $order->guest_name }}</p>
                            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">
                                {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="margin: 0; font-weight: 600;">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span
                                class="badge @if ($order->status === 'paid') badge-success @elseif($order->status === 'pending') badge-warning @else badge-danger @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p style="color: #6b7280;">Tidak ada pesanan</p>
                @endforelse
            </div>
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary"
                    style="width: 100%; text-align: center;">Lihat Semua Pesanan →</a>
            </div>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div
        style="margin-top: 2rem; padding: 1.5rem; background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 1rem;">Aksi Cepat</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary"
                style="text-align: center; padding: 1rem; text-decoration: none;">
                ➕ Tambah Buku
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary"
                style="text-align: center; padding: 1rem; text-decoration: none;">
                📦 Kelola Pesanan
            </a>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"
                style="text-align: center; padding: 1rem; text-decoration: none;">
                ✍️ Buat Blog
            </a>
            <a href="{{ route('admin.payments.analytics') }}" class="btn btn-primary"
                style="text-align: center; padding: 1rem; text-decoration: none;">
                📊 Analitik
            </a>
        </div>
    </div>
@endsection
