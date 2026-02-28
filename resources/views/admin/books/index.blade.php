@extends('admin.layout')

@section('title', 'Kelola Buku')
@section('page-title', 'Kelola Buku')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">➕ Tambah Buku Baru</a>
            <a href="{{ route('admin.books.export') }}" class="btn btn-primary" style="margin-left: 0.5rem;">📥 Export CSV</a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <input type="text" name="search" placeholder="Cari judul, ISBN, atau penulis..."
                    value="{{ $search }}"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>
            <div>
                <select name="category_id"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($selectedCategory == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="format"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Semua Format</option>
                    <option value="physical" @selected($selectedFormat === 'physical')>Fisik</option>
                    <option value="ebook" @selected($selectedFormat === 'ebook')>Ebook</option>
                    <option value="both" @selected($selectedFormat === 'both')>Keduanya</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Cari</button>
            </div>
        </form>
    </div>

    <!-- Books Table -->
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40%;">Judul & Penulis</th>
                    <th>Format</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>
                            <p style="margin: 0; font-weight: 600;">{{ $book->title }}</p>
                            <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">
                                {{ $book->authors->pluck('name')->implode(', ') }}</p>
                            <p style="margin: 0; font-size: 0.75rem; color: #9ca3af;">{{ $book->category->name ?? '-' }}</p>
                        </td>
                        <td>
                            <span
                                class="badge @if ($book->format === 'ebook') badge-info @elseif($book->format === 'physical') badge-success @else badge-warning @endif">
                                {{ ucfirst($book->format) }}
                            </span>
                        </td>
                        <td>
                            <p style="margin: 0;">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                            @if ($book->discount_price)
                                <p style="margin: 0; font-size: 0.875rem; color: #059669;">Rp
                                    {{ number_format($book->discount_price, 0, ',', '.') }}</p>
                            @endif
                        </td>
                        <td>
                            <span
                                class="badge @if ($book->stock > 20) badge-success @elseif($book->stock > 5) badge-warning @else badge-danger @endif">
                                {{ $book->stock }}
                            </span>
                        </td>
                        <td>
                            @if ($book->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.books.show', $book) }}" class="btn btn-primary"
                                style="font-size: 0.75rem;">Lihat</a>
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary"
                                style="font-size: 0.75rem;">Edit</a>
                            <form method="POST" action="{{ route('admin.books.destroy', $book) }}"
                                style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="font-size: 0.75rem;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #6b7280;">
                            Tidak ada buku
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $books->links() }}
    </div>
@endsection
