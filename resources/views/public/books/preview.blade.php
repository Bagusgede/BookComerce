@extends('layouts.public')

@section('title', 'Pratinjau Ebook - BacaYukk.id')

@section('content')
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('book.detail', $book->id) }}"
                        class="text-decoration-none text-muted">Detail Buku</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pratinjau Ebook</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
            <div>
                <h1 class="h3 fw-bold mb-1">Pratinjau Ebook: {{ $book->title }}</h1>
                <p class="text-muted mb-0">Halaman 1-3 terbuka, sisanya dikaburkan. Untuk akses penuh silakan lanjutkan checkout.</p>
            </div>
            <form action="{{ route('book.checkout', $book->id) }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-credit-card-2-front me-2"></i>Lanjut Data Diri & Pembayaran
                </button>
            </form>
        </div>

        <div class="ebook-preview-stage js-limited-scroll" data-max-scroll="400">
            <iframe src="{{ route('book.preview.file', $book->id) }}#toolbar=1&navpanes=0&scrollbar=1"
                title="Pratinjau Ebook {{ $book->title }}" loading="lazy"></iframe>
            <div class="ebook-preview-scroll-lock" aria-hidden="true"></div>
            <div class="ebook-preview-blur-layer" aria-hidden="true"></div>
            <div class="ebook-preview-overlay-text">Halaman 2+ dikunci (blur)</div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
            color: #6c757d;
        }

        .ebook-preview-stage {
            position: relative;
            width: 100%;
            height: 82vh;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        .ebook-preview-stage iframe {
            width: 100%;
            height: 100%;
            border: 0;
            pointer-events: none;
            user-select: none;
            transform: translateY(calc(var(--limited-scroll-offset, 0px) * -1));
            transition: transform 0.08s linear;
        }

        .ebook-preview-scroll-lock {
            position: absolute;
            inset: 0;
            z-index: 2;
            background: transparent;
        }

        .ebook-preview-blur-layer {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 38%;
            z-index: 3;
            backdrop-filter: blur(7px);
            background: rgba(255, 255, 255, 0.18);
            pointer-events: none;
        }

        .ebook-preview-overlay-text {
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 4;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.72);
            color: #fff;
            font-size: 0.85rem;
            pointer-events: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const limitedPreviews = document.querySelectorAll('.js-limited-scroll');

            limitedPreviews.forEach((preview) => {
                let offset = 0;
                const maxScroll = parseInt(preview.dataset.maxScroll || '180', 10);

                preview.addEventListener('wheel', function(event) {
                    event.preventDefault();

                    const delta = event.deltaY > 0 ? 24 : -24;
                    offset = Math.max(0, Math.min(maxScroll, offset + delta));
                    preview.style.setProperty('--limited-scroll-offset', `${offset}px`);
                }, {
                    passive: false
                });
            });
        })();
    </script>
@endpush
