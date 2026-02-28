@extends('admin.layout')

@section('title', $post->title)
@section('page-title', 'Detail Blog Post')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
        <!-- Main Content -->
        <div>
            <!-- Header -->
            <div class="card" style="margin-bottom: 2rem; padding: 2rem;">
                @if ($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                        style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                @else
                    <div
                        style="width: 100%; height: 300px; background: #f3f4f6; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; color: #6b7280;">
                        📷 Tidak ada Featured Image
                    </div>
                @endif

                <h1 style="margin: 0 0 0.5rem 0;">{{ $post->title }}</h1>
                <p style="margin: 0 0 1rem 0; color: #6b7280; font-size: 0.875rem;">{{ $post->slug }}</p>

                <div
                    style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; padding: 1rem 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        @if ($post->author && $post->author->photo)
                            <img src="{{ asset('storage/' . $post->author->photo) }}" alt="{{ $post->author->name }}"
                                style="width: 40px; height: 40px; border-radius: 9999px; object-fit: cover;">
                        @else
                            <div
                                style="width: 40px; height: 40px; border-radius: 9999px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 0.875rem;">
                                👤
                            </div>
                        @endif
                        <div>
                            <div style="font-weight: 600; font-size: 0.875rem;">{{ $post->author->name ?? 'Unknown' }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">
                                @if ($post->created_at)
                                    {{ $post->created_at->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($post->category)
                        <span
                            style="display: inline-block; background: #fce7f3; color: #be185d; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                            {{ $post->category->name }}
                        </span>
                    @endif

                    <div style="margin-left: auto;">
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
                    </div>
                </div>

                <!-- Excerpt -->
                @if ($post->excerpt)
                    <div
                        style="margin-top: 1.5rem; padding: 1rem; background: #f0f9ff; border-left: 4px solid #0284c7; border-radius: 0.375rem;">
                        <p style="margin: 0; color: #075985; font-style: italic;">{{ $post->excerpt }}</p>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
                <div style="color: #374151; line-height: 1.8;">
                    {{-- Render HTML content saved by WYSIWYG editor. Content is assumed trusted from admin. --}}
                    {!! $post->content !!}
                </div>
            </div>

            <!-- Statistics -->
            <div class="card" style="padding: 1.5rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.875rem;">Views</p>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: 600;">{{ $post->view_count ?? 0 }}</p>
                </div>
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.875rem;">Dibuat</p>
                    <p style="margin: 0; font-size: 0.875rem;">
                        @if ($post->created_at)
                            {{ $post->created_at->format('d M Y H:i') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.875rem;">Diupdate</p>
                    <p style="margin: 0; font-size: 0.875rem;">
                        @if ($post->updated_at)
                            {{ $post->updated_at->format('d M Y H:i') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div>
            <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="margin: 0 0 1rem 0; font-size: 1rem;">Aksi</h3>

                <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-primary"
                    style="display: block; width: 100%; text-align: center; padding: 0.75rem; margin-bottom: 0.5rem; text-decoration: none;">
                    ✏️ Edit
                </a>

                @if (!$post->is_published)
                    <form method="POST" action="{{ route('admin.blog.publish', $post) }}" style="margin-bottom: 0.5rem;">
                        @csrf
                        <button type="submit" class="btn btn-primary"
                            style="width: 100%; padding: 0.75rem; background: #22c55e; cursor: pointer;">
                            📤 Publikasikan
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus blog post ini?');"
                    style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; padding: 0.75rem; background: #ef4444; cursor: pointer;">
                        🗑️ Hapus
                    </button>
                </form>
            </div>

            <!-- Info Card -->
            <div class="card" style="padding: 1.5rem; font-size: 0.875rem; color: #6b7280;">
                <h3 style="margin: 0 0 1rem 0; font-size: 0.875rem; font-weight: 600;">Info</h3>

                <p style="margin: 0 0 0.75rem 0;">
                    <strong>Status:</strong><br>
                    @if ($post->is_published && $post->published_at)
                        <span style="color: #166534;">Dipublikasikan {{ $post->published_at->format('d M Y') }}</span>
                    @else
                        <span style="color: #a16207;">Draft</span>
                    @endif
                </p>

                <p style="margin: 0 0 0.75rem 0;">
                    <strong>Penulis:</strong><br>
                    {{ $post->author->name ?? 'Unknown' }}
                </p>

                @if ($post->category)
                    <p style="margin: 0 0 0.75rem 0;">
                        <strong>Kategori:</strong><br>
                        {{ $post->category->name }}
                    </p>
                @endif

                <p style="margin: 0;">
                    <strong>Slug:</strong><br>
                    {{ $post->slug }}
                </p>
            </div>

            <a href="{{ route('admin.blog.index') }}" class="btn btn-primary"
                style="display: block; width: 100%; text-align: center; padding: 0.75rem; margin-top: 1rem; text-decoration: none;">
                ← Kembali
            </a>
        </div>
    </div>
@endsection
