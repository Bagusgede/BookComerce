@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <section class="section-spacing">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="book-card mb-4">
                        @if ($post->featured_image)
                            <div class="book-image-wrapper">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                            </div>
                        @endif
                        <div class="card-body p-4">
                            <div class="mb-3 text-muted">{{ optional($post->category)->name ?? 'Umum' }} •
                                {{ $post->published_at?->format('d M Y') }}</div>
                            <h1 class="mb-3">{{ $post->title }}</h1>
                            <div class="mb-4 text-muted">Ditulis oleh {{ optional($post->author)->name ?? 'Redaksi' }}</div>

                            <div class="post-content" style="line-height:1.8;color:#2c2c2c">
                                {!! $post->content !!}
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">← Kembali ke Blog</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
