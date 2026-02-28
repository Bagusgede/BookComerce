@extends('admin.layout')

@section('title', 'Daftar Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <p style="color: #6b7280; margin: 0;">Total Kategori: <strong>{{ $categories->count() }}</strong></p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"
            style="text-decoration: none; padding: 0.75rem 1.5rem;">
            ➕ Tambah Kategori
        </a>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Nama Kategori</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Slug</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Deskripsi</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Jumlah Buku</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; font-weight: 600;">{{ $category->name }}</td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #6b7280; font-family: monospace;">
                            {{ $category->slug }}</td>
                        <td style="padding: 1rem; color: #6b7280; font-size: 0.875rem;">
                            {{ Str::limit($category->description, 50) }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span
                                style="background: #dbeafe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                {{ $category->books_count ?? 0 }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            @if ($category->is_active)
                                <span style="color: #059669;">✓ Aktif</span>
                            @else
                                <span style="color: #dc2626;">✗ Tidak Aktif</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                                style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">✏️</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                style="display: inline;" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="color: #ef4444; background: none; border: none; cursor: pointer;">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada kategori
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
