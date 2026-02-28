@extends('admin.layout')

@section('title', 'Detail Buku: ' . $book->title)
@section('page-title', 'Detail Buku: ' . $book->title)

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Sidebar -->
        <div>
            <div class="card" style="padding: 1.5rem;">
                @if ($book->cover_image)
                    <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}"
                        style="width: 100%; border-radius: 0.375rem; margin-bottom: 1rem;">
                @else
                    <div
                        style="width: 100%; height: 300px; background: #e5e7eb; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; color: #9ca3af;">
                        Tidak ada gambar
                    </div>
                @endif

                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary"
                        style="flex: 1; padding: 0.75rem; text-align: center; text-decoration: none;">✏️ Edit</a>
                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}" style="flex: 1;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-primary"
                            style="width: 100%; padding: 0.75rem; background: #ef4444;"
                            onclick="return confirm('Hapus buku ini?')">🗑️ Hapus</button>
                    </form>
                </div>

                <a href="{{ route('admin.books.index') }}" class="btn btn-primary"
                    style="display: block; width: 100%; padding: 0.75rem; text-align: center; text-decoration: none; background: #6b7280;">←
                    Kembali</a>
            </div>
        </div>

        <!-- Main Content -->
        <div>
            <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
                <h2 style="margin: 0 0 1rem 0;">{{ $book->title }}</h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem;">KATEGORI</p>
                        <p style="margin: 0; font-weight: 600;">{{ $book->category->name }}</p>
                    </div>
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem;">FORMAT</p>
                        <p style="margin: 0; font-weight: 600;">
                            @if ($book->format === 'physical')
                                Buku Fisik
                            @elseif($book->format === 'ebook')
                                Ebook
                            @else
                                Keduanya
                            @endif
                        </p>
                    </div>
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem;">PENULIS</p>
                        <p style="margin: 0;">
                            @foreach ($book->authors as $author)
                                <span
                                    style="display: inline-block; background: #e0e7ff; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; margin-right: 0.5rem;">
                                    {{ $author->name }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                    <div>
                        <p style="color: #6b7280; font-size: 0.875rem;">PENERBIT</p>
                        <p style="margin: 0; font-weight: 600;">{{ $book->publisher ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr style="margin: 2rem 0; border-color: #e5e7eb;">

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                    <div style="background: #f0fdf4; padding: 1rem; border-radius: 0.375rem;">
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">HARGA NORMAL</p>
                        <p style="margin: 0; font-weight: 700; font-size: 1.25rem;">
                            Rp{{ number_format($book->price, 0, ',', '.') }}</p>
                    </div>
                    <div style="background: #fef3c7; padding: 1rem; border-radius: 0.375rem;">
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">HARGA DISKON</p>
                        <p style="margin: 0; font-weight: 700; font-size: 1.25rem;">
                            {{ $book->discount_price ? 'Rp' . number_format($book->discount_price, 0, ',', '.') : '-' }}
                        </p>
                    </div>
                    <div style="background: #eff6ff; padding: 1rem; border-radius: 0.375rem;">
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">STOK</p>
                        <p style="margin: 0; font-weight: 700; font-size: 1.25rem;">{{ $book->stock }}</p>
                    </div>
                    <div style="background: #f5f3ff; padding: 1rem; border-radius: 0.375rem;">
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">STATUS</p>
                        <p style="margin: 0; font-weight: 700;">
                            @if ($book->is_active)
                                <span style="color: #059669;">✓ Aktif</span>
                            @else
                                <span style="color: #dc2626;">✗ Tidak Aktif</span>
                            @endif
                        </p>
                    </div>
                </div>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Deskripsi</h3>
                <p style="color: #374151; line-height: 1.6; white-space: pre-wrap;">{{ $book->description }}</p>
            </div>

            <!-- Book Details Card -->
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-top: 0;">Informasi Tambahan</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">ISBN</td>
                        <td style="padding: 1rem;">{{ $book->isbn ?? 'N/A' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Tahun Terbit</td>
                        <td style="padding: 1rem;">{{ $book->publication_year ?? 'N/A' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Jumlah Halaman</td>
                        <td style="padding: 1rem;">{{ $book->pages ?? 'N/A' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Buku Pilihan</td>
                        <td style="padding: 1rem;">
                            @if ($book->is_featured)
                                <span
                                    style="background: #dbeafe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px;">Ya</span>
                            @else
                                <span
                                    style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 9999px;">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Dibuat</td>
                        <td style="padding: 1rem;">{{ $book->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Terakhir Diupdate</td>
                        <td style="padding: 1rem;">{{ $book->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
