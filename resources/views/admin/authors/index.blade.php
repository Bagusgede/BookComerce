@extends('admin.layout')

@section('title', 'Daftar Penulis')
@section('page-title', 'Manajemen Penulis')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <p style="color: #6b7280; margin: 0 0 0.5rem 0;">Total Penulis: <strong>{{ $authors->total() }}</strong></p>
        </div>
        <a href="{{ route('admin.authors.create') }}" class="btn btn-primary"
            style="text-decoration: none; padding: 0.75rem 1.5rem;">
            ➕ Tambah Penulis
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card" style="padding: 1rem; margin-bottom: 2rem;">
        <form method="GET" style="display: flex; gap: 1rem;">
            <input type="search" name="search" placeholder="Cari nama penulis..." value="{{ request('search') }}"
                style="flex: 1; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; cursor: pointer;">🔍 Cari</button>
        </form>
    </div>

    <!-- Authors Table -->
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Nama</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Buku</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Blog</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($authors as $author)
                    <tr style="border-bottom: 1px solid #e5e7eb; hover: { background: #f9fafb; }">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">{{ $author->name }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $author->slug }}</div>
                        </td>
                        <td style="padding: 1rem; color: #6b7280;">{{ $author->email ?? '-' }}</td>
                        <td style="padding: 1rem;">
                            <span
                                style="background: #dbeafe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                {{ $author->books_count ?? 0 }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span
                                style="background: #fce7f3; color: #be185d; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                {{ $author->blog_posts_count ?? 0 }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            @if ($author->is_active)
                                <span style="color: #059669;">✓ Aktif</span>
                            @else
                                <span style="color: #dc2626;">✗ Tidak Aktif</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('admin.authors.edit', $author) }}"
                                style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">✏️</a>
                            <form method="POST" action="{{ route('admin.authors.destroy', $author) }}"
                                style="display: inline;" onsubmit="return confirm('Hapus penulis ini?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="color: #ef4444; background: none; border: none; cursor: pointer;">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada penulis</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($authors->hasPages())
            <div style="padding: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
                {{ $authors->links() }}
            </div>
        @endif
    </div>
@endsection
