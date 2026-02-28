@extends('admin.layout')

@section('title', 'Buat Blog Post Baru')
@section('page-title', 'Buat Blog Post Baru')

@section('content')
    <div class="card" style="padding: 2rem; max-width: 1000px;">
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    Judul <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('title')
                    <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Author & Category -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                        Penulis <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="author_id" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="">Pilih Penulis</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" @selected(old('author_id') == $author->id)>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('author_id')
                        <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kategori</label>
                    <select name="category_id"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Excerpt -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ringkasan</label>
                <textarea name="excerpt" rows="2" placeholder="Ringkasan singkat untuk preview..."
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ old('excerpt') }}</textarea>
                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0;">Max 500 karakter</p>
            </div>

            <!-- Featured Image -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Gambar Featured</label>
                <input type="file" name="featured_image" accept="image/*"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <p style="font-size: 0.75rem; color: #6b7280;">Max 2MB, JPG/PNG</p>
            </div>

            <!-- Content -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    Konten <span style="color: #ef4444;">*</span>
                </label>
                <textarea name="content" rows="10" placeholder="Mulai menulis..."
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: monospace;">{{ old('content') }}</textarea>
                @error('content')
                    <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Publish Status -->
            <div
                style="margin-bottom: 2rem; padding: 1rem; background: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 0.375rem;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', false))
                        style="width: 1rem; height: 1rem; margin-right: 0.75rem;">
                    <span>
                        <strong>Publikasikan sekarang</strong>
                        <div style="font-size: 0.875rem; color: #6b7280;">Jika unchecked, disimpan sebagai draft</div>
                    </span>
                </label>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">💾 Simpan Blog Post</button>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Batal</a>
            </div>
        </form>
    </div>
@endsection

{{-- CKEditor will initialize via Vite-built resources/js/app.js which imports resources/js/ckeditor.js --}}
