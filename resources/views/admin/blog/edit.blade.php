@extends('admin.layout')

@section('title', 'Edit Blog Post: ' . $post->title)
@section('page-title', 'Edit Blog Post')

@section('content')
    <div class="card" style="padding: 2rem; max-width: 1000px;">
        <form method="POST" action="{{ route('admin.blog.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    Judul <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
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
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" @selected(old('author_id', $post->author_id) == $author->id)>
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
                            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>
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
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ old('excerpt', $post->excerpt) }}</textarea>
                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0;">Max 500 karakter</p>
            </div>

            <!-- Featured Image -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Gambar Featured</label>

                @if ($post->featured_image)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                            style="max-width: 200px; max-height: 200px; border-radius: 0.375rem;">
                        <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem;">Gambar saat ini</p>
                    </div>
                @endif

                <input type="file" name="featured_image" accept="image/*"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <p style="font-size: 0.75rem; color: #6b7280;">Max 2MB, JPG/PNG (kosongkan jika tidak ingin mengubah)</p>
            </div>

            <!-- Content -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    Konten <span style="color: #ef4444;">*</span>
                </label>
                <textarea name="content" rows="10" placeholder="Mulai menulis..."
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: monospace;">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Publish Status -->
            <div
                style="margin-bottom: 2rem; padding: 1rem; @if ($post->is_published) background: #dcfce7; border-left-color: #22c55e; @else background: #fef3c7; border-left-color: #f59e0b; @endif border-left: 4px solid; border-radius: 0.375rem;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))
                        style="width: 1rem; height: 1rem; margin-right: 0.75rem;">
                    <span>
                        <strong>Status Publikasi</strong>
                        @if ($post->is_published)
                            <div style="font-size: 0.875rem; color: #166534;">✓ Dipublikasikan pada
                                {{ $post->published_at->format('d M Y H:i') }}</div>
                        @else
                            <div style="font-size: 0.875rem; color: #a16207;">📝 Masih draft</div>
                        @endif
                    </span>
                </label>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">💾 Update Blog Post</button>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Batal</a>
            </div>

            <!-- Metadata -->
            <div
                style="margin-top: 2rem; padding: 1rem; background: #f3f4f6; border-radius: 0.375rem; font-size: 0.875rem; color: #6b7280;">
                <p style="margin: 0 0 0.5rem;">
                    <strong>Slug:</strong> {{ $post->slug }}
                </p>
                <p style="margin: 0 0 0.5rem;">
                    <strong>Dibuat:</strong> {{ $post->created_at->format('d M Y H:i') }}
                </p>
                <p style="margin: 0;">
                    <strong>Diupdate:</strong> {{ $post->updated_at->format('d M Y H:i') }}
                </p>
            </div>
        </form>

        <!-- Delete Form (outside the update form) -->
        <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" style="margin-top: 2rem;"
            onsubmit="return confirm('Yakin hapus blog post ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; background: #ef4444;">
                🗑️ Hapus Post
            </button>
        </form>
    </div>
@endsection
{{-- CKEditor will be initialized via Vite-built JS (resources/js/ckeditor.js imported by app.js) --}}
