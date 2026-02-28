@extends('admin.layout')

@section('title', 'Edit Buku: ' . $book->title)
@section('page-title', 'Edit Buku: ' . $book->title)

@section('content')
    <div class="card" style="padding: 2rem;">
        <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Left Column -->
                <div>
                    <!-- Title -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Judul Buku <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @error('title')
                            <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Kategori <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="category_id" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $book->category_id) == $cat->id)>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Authors -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Penulis <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="authors[]" multiple required size="5"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected($book->authors->contains($author))>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                        <p style="font-size: 0.75rem; color: #6b7280;">Ctrl + Click untuk pilih lebih dari satu</p>
                        @error('authors')
                            <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publisher -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Penerbit</label>
                        <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <!-- ISBN -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ISBN</label>
                        <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @error('isbn')
                            <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publication Year -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tahun Terbit</label>
                        <input type="number" name="publication_year"
                            value="{{ old('publication_year', $book->publication_year) }}"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <!-- Pages -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Jumlah Halaman</label>
                        <input type="number" name="pages" value="{{ old('pages', $book->pages) }}"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Format -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Format <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="format" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            <option value="physical" @selected(old('format', $book->format) === 'physical')>Buku Fisik</option>
                            <option value="ebook" @selected(old('format', $book->format) === 'ebook')>Ebook</option>
                            <option value="both" @selected(old('format', $book->format) === 'both')>Keduanya</option>
                        </select>
                    </div>

                    <!-- Price -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Harga Regular <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" name="price" step="0.01" value="{{ old('price', $book->price) }}"
                            required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <!-- Discount Percent & Price (auto) -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Diskon (%)</label>
                        <input type="number" name="discount_percent" id="discount_percent" min="0" max="100"
                            value="{{ old('discount_percent', optional($book)->discount_price ? round((($book->price - $book->discount_price) / $book->price) * 100) : null) }}"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <p style="font-size: 0.75rem; color: #6b7280; margin-top:0.5rem;">Masukkan nilai persen saja (mis.
                            15 untuk 15%).</p>

                        <label style="display: block; margin-top:0.75rem; margin-bottom: 0.5rem; font-weight: 600;">Harga
                            Diskon (otomatis)</label>
                        <input type="number" name="discount_price" id="discount_price" step="0.01"
                            value="{{ old('discount_price', $book->discount_price) }}"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">

                        <div id="discount_preview" style="font-size:0.9rem;color:#065f46;margin-top:0.5rem;display:none;">
                        </div>
                    </div>

                    <!-- Stock -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Stok <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <!-- Cover Image -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Gambar Cover</label>
                        @if ($book->cover_image)
                            <div style="margin-bottom: 0.5rem;">
                                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}"
                                    style="height: 150px; border-radius: 0.375rem;">
                            </div>
                        @endif
                        <input type="file" name="cover_image" accept="image/*"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <p style="font-size: 0.75rem; color: #6b7280;">Kosongkan untuk membiarkan tetap sama</p>
                    </div>

                    <!-- Ebook File -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">File Ebook</label>
                        @if ($book->ebook_file)
                            <p style="font-size: 0.75rem; color: #059669;">✓ File sudah ada:
                                {{ basename($book->ebook_file) }}</p>
                        @endif
                        <input type="file" name="ebook_file" accept=".pdf,.epub"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <!-- Status -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center;">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $book->is_active))
                                style="width: 1rem; height: 1rem; margin-right: 0.5rem;">
                            <span>Aktif</span>
                        </label>
                    </div>

                    <!-- Featured -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center;">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $book->is_featured))
                                style="width: 1rem; height: 1rem; margin-right: 0.5rem;">
                            <span>Buku Pilihan</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    Deskripsi <span style="color: #ef4444;">*</span>
                </label>
                <textarea name="description" rows="6" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: monospace;">{{ old('description', $book->description) }}</textarea>
            </div>

            <!-- Metadata -->
            <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.375rem; margin-bottom: 2rem;">
                <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: #6b7280;">
                    Dibuat: {{ $book->created_at->format('d M Y H:i') }} |
                    Diupdate: {{ $book->updated_at->format('d M Y H:i') }}
                </p>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">💾 Simpan
                    Perubahan</button>
                <a href="{{ route('admin.books.show', $book) }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #3b82f6; text-decoration: none;">👁️ Lihat Detail</a>
                <a href="{{ route('admin.books.index') }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Batal</a>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    @vite('resources/js/ckeditor.js')
    <script>
        (function() {
            const priceEl = document.querySelector('input[name="price"]');
            const percentEl = document.getElementById('discount_percent');
            const discountPriceEl = document.getElementById('discount_price');
            const preview = document.getElementById('discount_preview');

            function formatIDR(n) {
                if (n === null || n === undefined || n === '') return '';
                return 'Rp ' + Number(n).toLocaleString('id-ID', {
                    maximumFractionDigits: 0
                });
            }

            function update() {
                const price = parseFloat(priceEl?.value) || 0;
                const percent = parseFloat(percentEl?.value) || 0;

                if (percent > 0 && price > 0) {
                    const dp = +(price * (1 - percent / 100)).toFixed(2);
                    discountPriceEl.value = dp;
                    preview.style.display = 'block';
                    preview.textContent = 'Harga setelah diskon: ' + formatIDR(dp);
                } else if (discountPriceEl && discountPriceEl.value) {
                    preview.style.display = 'block';
                    preview.textContent = 'Harga diskon saat ini: ' + formatIDR(discountPriceEl.value);
                } else {
                    preview.style.display = 'none';
                }
            }

            [priceEl, percentEl, discountPriceEl].forEach(el => el && el.addEventListener('input', update));
            document.addEventListener('DOMContentLoaded', update);
            setTimeout(update, 200);
        })();
    </script>
@endsection
