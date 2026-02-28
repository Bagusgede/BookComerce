@extends('admin.layout')

@section('title', $author->name)
@section('page-title', 'Detail Penulis: ' . $author->name)

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
        <!-- Main Content -->
        <div>
            <!-- Info Card -->
            <div class="card" style="margin-bottom: 2rem; padding: 2rem;">
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 2rem; margin-bottom: 2rem;">
                    @if ($author->photo)
                        <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}"
                            style="width: 150px; height: 150px; border-radius: 0.5rem; object-fit: cover;">
                    @else
                        <div
                            style="width: 150px; height: 150px; background: #e5e7eb; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #6b7280;">
                            No Photo
                        </div>
                    @endif

                    <div>
                        <h2 style="margin: 0 0 0.5rem 0;">{{ $author->name }}</h2>
                        <p style="margin: 0 0 1rem 0; color: #6b7280;">{{ $author->slug }}</p>

                        @if ($author->email)
                            <p style="margin: 0 0 0.5rem 0;"><strong>Email:</strong> {{ $author->email }}</p>
                        @endif

                        @if ($author->bio)
                            <div style="margin: 1rem 0;">
                                <p style="margin: 0 0 0.5rem 0;"><strong>Biografi:</strong></p>
                                <p style="margin: 0; color: #6b7280; line-height: 1.5;">{{ $author->bio }}</p>
                            </div>
                        @endif

                        <div style="margin-top: 1rem;">
                            <span class="badge @if ($author->is_active) badge-success @else badge-danger @endif">
                                @if ($author->is_active)
                                    ✓ Aktif
                                @else
                                    ✗ Tidak Aktif
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                @if ($author->social_media && count($author->social_media) > 0)
                    <div style="padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                        <h3 style="margin: 0 0 1rem 0;">Media Sosial</h3>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            @if ($author->social_media['twitter'] ?? null)
                                <a href="{{ $author->social_media['twitter'] }}" target="_blank"
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #dbeafe; border-radius: 0.375rem; color: #0369a1; text-decoration: none;">
                                    🐦 Twitter
                                </a>
                            @endif

                            @if ($author->social_media['instagram'] ?? null)
                                <a href="{{ $author->social_media['instagram'] }}" target="_blank"
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #fce7f3; border-radius: 0.375rem; color: #be185d; text-decoration: none;">
                                    📸 Instagram
                                </a>
                            @endif

                            @if ($author->social_media['facebook'] ?? null)
                                <a href="{{ $author->social_media['facebook'] }}" target="_blank"
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #dbeafe; border-radius: 0.375rem; color: #0369a1; text-decoration: none;">
                                    👤 Facebook
                                </a>
                            @endif

                            @if ($author->social_media['tiktok'] ?? null)
                                <a href="{{ $author->social_media['tiktok'] }}" target="_blank"
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #000; border-radius: 0.375rem; color: #fff; text-decoration: none;">
                                    🎵 TikTok
                                </a>
                            @endif

                            @if ($author->social_media['website'] ?? null)
                                <a href="{{ $author->social_media['website'] }}" target="_blank"
                                    style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #f0f9ff; border-radius: 0.375rem; color: #075985; text-decoration: none;">
                                    🌐 Website
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Books -->
            <div class="card" style="margin-bottom: 2rem;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="margin: 0;">📚 Buku ({{ $author->books->count() }})</h3>
                </div>
                <div style="padding: 1.5rem;">
                    @forelse($author->books as $book)
                        <div
                            style="display: flex; gap: 1rem; padding: 1rem; border-bottom: 1px solid #f3f4f6; align-items: flex-start;">
                            @if ($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                    style="width: 60px; height: 90px; object-fit: cover; border-radius: 0.375rem;">
                            @endif
                            <div style="flex: 1;">
                                <p style="margin: 0 0 0.5rem 0; font-weight: 600;">{{ $book->title }}</p>
                                <p style="margin: 0 0 0.5rem 0; font-size: 0.875rem; color: #6b7280;">
                                    {{ $book->category->name ?? 'No Category' }}</p>
                                <p style="margin: 0; font-weight: 600;">@rupiah($book->price)</p>
                            </div>
                        </div>
                    @empty
                        <p style="color: #6b7280; text-align: center; padding: 2rem;">Belum ada buku</p>
                    @endforelse
                </div>
            </div>

            <!-- Blog Posts -->
            <div class="card">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="margin: 0;">✍️ Blog Posts ({{ $author->blogPosts->count() }})</h3>
                </div>
                <div style="padding: 1.5rem;">
                    @forelse($author->blogPosts as $post)
                        <div style="padding: 1rem; border-bottom: 1px solid #f3f4f6;">
                            <p style="margin: 0 0 0.5rem 0; font-weight: 600;">{{ $post->title }}</p>
                            <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">
                                {{ $post->created_at->format('d M Y') }}
                            </p>
                        </div>
                    @empty
                        <p style="color: #6b7280; text-align: center; padding: 2rem;">Belum ada blog posts</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div>
            <div class="card" style="padding: 1.5rem;">
                <h3 style="margin: 0 0 1rem 0; font-size: 1rem;">Aksi</h3>

                <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-primary"
                    style="display: block; width: 100%; text-align: center; padding: 0.75rem; margin-bottom: 0.5rem; text-decoration: none;">
                    ✏️ Edit
                </a>

                <form action="{{ route('admin.authors.destroy', $author) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus penulis ini?');"
                    style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; padding: 0.75rem; background: #ef4444; cursor: pointer;">
                        🗑️ Hapus
                    </button>
                </form>

                <div
                    style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; font-size: 0.875rem; color: #6b7280;">
                    <p style="margin: 0 0 0.5rem 0;">
                        <strong>Dibuat:</strong><br>{{ $author->created_at->format('d M Y H:i') }}</p>
                    <p style="margin: 0;"><strong>Diupdate:</strong><br>{{ $author->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <a href="{{ route('admin.authors.index') }}" class="btn btn-primary"
                style="display: block; width: 100%; text-align: center; padding: 0.75rem; margin-top: 1rem; text-decoration: none;">
                ← Kembali
            </a>
        </div>
    </div>
@endsection
