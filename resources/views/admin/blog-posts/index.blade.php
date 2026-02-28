@extends('admin.layout')

@section('title', 'Daftar Blog Post')
@section('page-title', 'Manajemen Blog')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <p style="color: #6b7280; margin: 0;">Total Post: <strong>{{ $posts->total() }}</strong></p>
        </div>
        <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary"
            style="text-decoration: none; padding: 0.75rem 1.5rem;">
            ➕ Tulis Post Baru
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card" style="padding: 1rem; margin-bottom: 2rem;">
        <form method="GET" style="display: grid; grid-template-columns: 1fr 150px 150px 150px; gap: 1rem;">
            <input type="search" name="search" placeholder="Cari title atau content..." value="{{ request('search') }}"
                style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            <select name="author_id" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">-- Author --</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
            <select name="published" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                <option value="">-- Status --</option>
                <option value="1" @selected(request('published') === '1')>Published</option>
                <option value="0" @selected(request('published') === '0')>Draft</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; cursor: pointer;">🔍 Cari</button>
        </form>
    </div>

    <!-- Posts Table -->
    <div class="card">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Judul</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Author</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Kategori</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Status</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Tanggal</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;">{{ $post->title }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">
                                {{ Str::limit(strip_tags($post->content), 50) }}</div>
                        </td>
                        <td style="padding: 1rem;">{{ $post->author->name }}</td>
                        <td style="padding: 1rem;">{{ $post->category->name ?? '-' }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            @if ($post->is_published)
                                <span
                                    style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Published</span>
                            @else
                                <span
                                    style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Draft</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #6b7280;">
                            {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('admin.blog-posts.edit', $post) }}"
                                style="color: #3b82f6; text-decoration: none; margin-right: 1rem;">✏️</a>
                            <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}"
                                style="display: inline;" onsubmit="return confirm('Hapus post ini?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="color: #ef4444; background: none; border: none; cursor: pointer;">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada post</td>
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
