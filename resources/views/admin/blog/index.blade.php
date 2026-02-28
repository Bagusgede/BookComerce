@extends('admin.layout')

@section('title', 'Manajemen Blog')
@section('page-title', 'Manajemen Blog Posts')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <p style="color: #6b7280; margin: 0 0 0.5rem 0;">Total Blog Posts: <strong>{{ $posts->total() }}</strong></p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"
            style="text-decoration: none; padding: 0.75rem 1.5rem;">
            ➕ Buat Blog Post
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <input type="search" name="search" placeholder="Cari judul atau konten..." value="{{ request('search') }}"
                style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">

            <select name="author_id" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">Semua Penulis</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>

            <select name="category_id" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">Semua Status</option>
                <option value="published" @selected(request('status') == 'published')>Dipublikasikan</option>
                <option value="draft" @selected(request('status') == 'draft')>Draft</option>
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; cursor: pointer;">
                🔍 Filter
            </button>
        </form>
    </div>

    <!-- Blog Posts Table -->
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Judul</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Penulis</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Kategori</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Tanggal</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">{{ $post->title }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $post->slug }}</div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-size: 0.875rem;">{{ $post->author->name ?? 'Unknown' }}</div>
                        </td>
                        <td style="padding: 1rem;">
                            @if ($post->category)
                                <span
                                    style="display: inline-block; background: #fce7f3; color: #be185d; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                    {{ $post->category->name }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem;">
                            @if ($post->is_published)
                                <span
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                    ✓ Dipublikasikan
                                </span>
                            @else
                                <span
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; background: #fef3c7; color: #a16207; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                    📝 Draft
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #6b7280;">
                            @if ($post->created_at)
                                <div>{{ $post->created_at->format('d M Y') }}</div>
                                <div style="color: #9ca3af;">{{ $post->created_at->format('H:i') }}</div>
                            @else
                                <div style="color: #9ca3af;">-</div>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('admin.blog.edit', $post) }}"
                                style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">✏️</a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" style="display: inline;"
                                onsubmit="return confirm('Hapus blog post ini?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="color: #ef4444; background: none; border: none; cursor: pointer;">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada blog posts
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($posts->hasPages())
            <div style="padding: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
