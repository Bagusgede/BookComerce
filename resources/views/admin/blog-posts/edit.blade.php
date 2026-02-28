@extends('admin.layout')

@section('title', 'Edit Post: ' . $post->title)
@section('page-title', 'Edit Post: ' . $post->title)

@section('content')
    <div class="card" style="padding: 2rem;">
        <form method="POST" action="{{ route('admin.blog-posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <!-- Left Column -->
                <div>
                    <!-- Title -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Judul <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <!-- Content -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            Konten <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea name="content" rows="12" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: monospace; font-size: 0.875rem;">{{ old('content', $post->content) }}</textarea>
                    </div>

                    <!-- Featured Image -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Gambar Unggulan</label>
                        @if ($post->featured_image)
                            <div style="margin-bottom: 0.5rem;">
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}"
                                    style="max-height: 200px; border-radius: 0.375rem;">
                            </div>
                        @endif
                        <input type="file" name="featured_image" accept="image/*"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Author -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Author</label>
                        <select name="author_id"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(old('author_id', $post->author_id) == $author->id)>{{ $author->name }}
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
                                <option value="{{ $cat->id }}" @selected(old('category_id', $post->category_id) == $cat->id)>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center;">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))
                                style="width: 1rem; height: 1rem; margin-right: 0.5rem;">
                            <span>Published</span>
                        </label>
                    </div>

                    <!-- Meta Description -->
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Meta Description</label>
                        <textarea name="meta_description" rows="3"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">{{ old('meta_description', $post->meta_description) }}</textarea>
                    </div>

                    <!-- Metadata -->
                    <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.375rem;">
                        <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">
                            <strong>Slug:</strong> {{ $post->slug }}<br>
                            <strong>Views:</strong> {{ $post->view_count ?? 0 }}<br>
                            <strong>Dibuat:</strong> {{ $post->created_at->format('d M Y H:i') }}<br>
                            @if ($post->published_at)
                                <strong>Dipublikasikan:</strong> {{ $post->published_at->format('d M Y H:i') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">💾 Simpan Perubahan</button>
                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Batal</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/ckeditor.js'])
@endsection
