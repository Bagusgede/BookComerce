@extends('layouts.public')

@section('title', 'Blog')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5">Dari Blog Kami</h1>
                    <p class="lead">Artikel dan cerita terbaru seputar buku, penulis, dan rekomendasi bacaan.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <form action="{{ route('blog.index') }}" method="get" class="d-flex justify-content-end">
                        <div class="search-wrapper">
                            <input name="search" value="{{ request('search') }}" class="search-input"
                                placeholder="Cari artikel...">
                            <button class="btn btn-link search-icon"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing">
        <div class="container">
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-12 col-md-6 col-lg-4">
                        <article class="book-card format-card">
                            <div class="book-image-wrapper">
                                @if ($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                                @else
                                    <img src="https://picsum.photos/seed/{{ $post->id }}/800/450"
                                        alt="{{ $post->title }}">
                                @endif
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">{{ optional($post->category)->name ?? 'Umum' }}</small>
                                    <small class="text-muted">{{ $post->published_at?->format('d M Y') }}</small>
                                </div>
                                <h5 class="mt-0"><a href="{{ route('blog.show', $post->id) }}"
                                        class="text-decoration-none text-primary-custom">{{ Str::limit($post->title, 70) }}</a>
                                </h5>
                                <p class="mb-3 text-muted">
                                    {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2"
                                            style="width:36px;height:36px;border-radius:50%;overflow:hidden;background:#e9e6df;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-person-fill" style="font-size:18px;color:#6b7280;"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight:600">{{ optional($post->author)->name ?? 'Redaksi' }}
                                            </div>
                                            <div style="font-size:12px;color:#6b7280">{{ $post->view_count ?? 0 }} kali dilihat
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('blog.show', $post->id) }}" class="btn btn-sm btn-primary">Baca</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada artikel yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $posts->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
@endsection
