<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }} Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            background: #1f2937;
            color: white;
            min-height: 100vh;
        }

        .admin-main {
            flex: 1;
            background: #f9fafb;
        }

        .admin-header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
        }

        .admin-content {
            padding: 2rem;
        }

        .sidebar-logo {
            padding: 1.5rem;
            border-bottom: 1px solid #374151;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #d1d5db;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background: #374151;
            color: white;
        }

        .sidebar-menu a.active {
            background: #374151;
            border-left-color: #3b82f6;
            color: white;
        }

        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-card p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .stat-card .number {
            font-size: 1.875rem;
            font-weight: bold;
            color: #111827;
            margin: 0.5rem 0;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f3f4f6;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
        }

        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-logo">
                <h1 style="margin: 0; font-size: 1.25rem;"> Book Ecomerce</h1>
                <p style="margin: 0.5rem 0 0; font-size: 0.875rem; color: #9ca3af;">Panel Admin</p>
            </div>

            <div class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="@active(request()->routeIs('admin.dashboard'))" style="margin-top: 1rem;">
                    📊 Dasbor
                </a>

                <p
                    style="padding: 1rem 1.5rem 0.5rem; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; margin: 0;">
                    Konten</p>
                <a href="{{ route('admin.books.index') }}" class="@active(request()->routeIs('admin.books.*'))">
                    📚 Buku
                </a>
                <a href="{{ route('admin.authors.index') }}" class="@active(request()->routeIs('admin.authors.*'))">
                    ✍️ Penulis
                </a>
                <a href="{{ route('admin.categories.index') }}" class="@active(request()->routeIs('admin.categories.*'))">
                    🏷️ Kategori
                </a>
                <a href="{{ route('admin.blog.index') }}" class="@active(request()->routeIs('admin.blog.*'))">
                    📝 Blog
                </a>

                <p
                    style="padding: 1rem 1.5rem 0.5rem; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; margin: 0;">
                    Operasional</p>
                <a href="{{ route('admin.orders.index') }}" class="@active(request()->routeIs('admin.orders.*'))">
                    📦 Pesanan
                </a>
                <a href="{{ route('admin.payments.index') }}" class="@active(request()->routeIs('admin.payments.*'))">
                    💳 Pembayaran
                </a>
                <a href="{{ route('admin.ebook-deliveries.index') }}" class="@active(request()->routeIs('admin.ebook-deliveries.*'))">
                    📥 Pengiriman Ebook
                </a>

                <p
                    style="padding: 1rem 1.5rem 0.5rem; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; margin: 0;">
                    Manajemen</p>
                <a href="{{ route('admin.customers.index') }}" class="@active(request()->routeIs('admin.customers.*'))">
                    👥 Pelanggan
                </a>
                <a href="{{ route('admin.settings.index') }}" class="@active(request()->routeIs('admin.settings.*'))">
                    ⚙️ Pengaturan
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <div class="admin-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h1 style="margin: 0; font-size: 1.5rem;">@yield('page-title', 'Admin')</h1>
                <div>
                    <span style="margin-right: 1rem;">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;">Keluar</button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="admin-content">
                <!-- Alerts -->
                @if ($errors->any())
                    <div
                        style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                        <strong>Terjadi Kesalahan:</strong>
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div
                        style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem;">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    @yield('scripts')
</body>

</html>
