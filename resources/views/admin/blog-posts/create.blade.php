@extends('admin.layout')

@section('title', 'Tulis Blog Post Baru')
@section('page-title', 'Tulis Blog Post Baru')

@section('content')
    <div class="card" style="padding: 2rem;">
        <form method="POST" action="{{ route('admin.blog-posts.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <!-- Left Column -->
                <div>
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

                    <!-- Content -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Konten <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea name="content" rows="12" required placeholder="Tulis konten blog post..."
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: monospace; font-size: 0.875rem;">{{ old('content') }}</textarea>
                        <p style="font-size: 0.75rem; color: #6b7280;">💡 Tip: Gunakan markdown untuk formatting</p>
                        @error('content')
                            <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Featured Image -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Gambar Unggulan</label>
                        <input type="file" name="featured_image" accept="image/*"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <p style="font-size: 0.75rem; color: #6b7280;">Max 2MB</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Author -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Author <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="author_id" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            <option value="">-- Pilih Author --</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(old('author_id') == $author->id)>{{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kategori</label>
                        <select name="category_id"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Publish Status -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center;">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published'))
                                style="width: 1rem; height: 1rem; margin-right: 0.5rem;">
                            <span>Publish Sekarang</span>
                        </label>
                        <p style="font-size: 0.75rem; color: #6b7280; margin: 0.5rem 0 0 2rem;">Jika tidak dicentang, post
                            akan disimpan sebagai draft</p>
                    </div>

                    <!-- Meta Description -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Meta Description
                            (SEO)</label>
                        <textarea name="meta_description" rows="3" placeholder="Deskripsi singkat untuk SEO (max 160 char)"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">{{ old('meta_description') }}</textarea>
                    </div>

                    <!-- Publishing Info -->
                    <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.375rem; margin-top: 2rem;">
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                            ℹ️ Akan dipublikasikan: <strong>sekarang</strong><br>
                            Slug akan dibuat otomatis dari judul
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">💾 Simpan Post</button>
                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Batal</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/ckeditor.js'])
@endsection
@endsection
