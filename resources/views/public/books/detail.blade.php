@extends('layouts.public')

@section('title', 'Detail Buku - BacaYukk.id')

@section('content')

    {{-- BREADCRUMB --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/books" class="text-decoration-none text-muted">Buku</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Buku</li>
            </ol>
        </nav>
    </div>

    {{-- BOOK DETAIL SECTION --}}
    <section class="py-5">
        <div class="container">
            <div class="row">
                {{-- BOOK IMAGE --}}
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="bg-light rounded-3 p-5 text-center"
                        style="background: linear-gradient(135deg, #E8E4F3 0%, #D4D4E8 100%);">
                            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('image/book1.jpg') }}"
                            data-fallback="{{ asset('image/book1.jpg') }}"
                            onerror="this.onerror=null;this.src=this.dataset.fallback;"
                            alt="{{ $book->title }}" class="img-fluid shadow-lg"
                            style="max-width: 350px; border-radius: 8px;">
                    </div>
                </div>

                {{-- BOOK INFO --}}
                <div class="col-lg-7">
                    {{-- Title --}}
                    <h1 class="fw-bold mb-3" style="font-size: 2.5rem; color: #2C2C2C;">{{ $book->title }}</h1>

                    {{-- Author --}}
                    <p class="text-muted mb-4" style="font-size: 1.1rem;">Oleh {{ $book->authors->pluck('name')->join(', ') }}
                    </p>

                    {{-- Format Information --}}
                    <div class="mb-4">
                        @if ($book->format === 'ebook')
                            <span class="badge bg-dark px-3 py-2 mb-2">Ebook</span>
                            <p class="text-muted small mb-0">Buku ini hanya tersedia dalam format Ebook.</p>
                        @elseif (in_array($book->format, ['physical', 'printed']))
                            <span class="badge bg-dark px-3 py-2 mb-2">Edisi Cetak</span>
                            <p class="text-muted small mb-0">Buku ini hanya tersedia dalam format cetak/fisik.</p>
                        @elseif ($book->format === 'both')
                            <span class="badge bg-dark px-3 py-2 mb-2">Ebook & Cetak</span>
                            <p class="text-muted small mb-0">Buku ini tersedia dalam format ebook dan cetak/fisik.</p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="mb-4">
                        @if ($book->hasDiscount())
                            <div class="d-flex align-items-baseline gap-3">
                                <h2 class="text-danger fw-bold" style="font-size: 2rem;">IDR
                                    {{ number_format($book->discount_price, 0, ',', '.') }}</h2>
                                <small class="text-muted text-decoration-line-through">IDR
                                    {{ number_format($book->price, 0, ',', '.') }}</small>
                                <span class="badge bg-danger ms-2">-{{ $book->discountPercentage }}%</span>
                            </div>
                        @else
                            <h2 class="text-dark fw-bold" style="font-size: 2rem;">IDR
                                {{ number_format($book->price ?? 0, 0, ',', '.') }}</h2>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-3 mb-4">
                        <form action="{{ route('book.checkout', $book->id) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-danger px-5 py-3 w-100">
                                <i class="bi bi-lightning-fill me-2"></i> Lanjut Data Diri & Pembayaran
                            </button>
                        </form>

                        @if ($previewAvailable)
                            <a href="{{ route('book.preview', $book->id) }}" class="btn btn-outline-dark px-5 py-3 flex-grow-1">
                                <i class="bi bi-journal-richtext me-2"></i> Halaman Pratinjau Ebook
                            </a>
                        @else
                            <button class="btn btn-outline-dark px-5 py-3 flex-grow-1" disabled>
                                <i class="bi bi-journal-x me-2"></i> Pratinjau Ebook Tidak Tersedia
                            </button>
                        @endif
                    </div>

                    {{-- Book Meta Info --}}
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded">
                                <p class="text-muted small mb-1">PAGES</p>
                                <p class="fw-bold mb-0">{{ $book->pages ?? '-' }} Halaman</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded">
                                <p class="text-muted small mb-1">FORMAT</p>
                                <p class="fw-bold mb-0">{{ strtoupper($book->format ?? 'N/A') }}</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3 bg-light rounded">
                                <p class="text-muted small mb-1">STOK</p>
                                <p class="fw-bold mb-0">{{ $book->stock ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Book Description --}}
                    <div class="mt-4">
                        <h5 class="fw-bold mb-2">Deskripsi Buku</h5>
                        <p class="text-muted mb-0" style="line-height: 1.8; text-align: justify;">
                            {!! nl2br(e($book->description)) !!}
                        </p>
                    </div>

                    {{-- Pratinjau Ebook (3 Halaman Terlihat) --}}
                    @if ($previewAvailable)
                        <div class="mt-4">
                            <h5 class="fw-bold mb-2">Pratinjau Isi Ebook (3 Halaman)</h5>
                            <p class="text-muted small mb-3">
                                Halaman 1-3 dapat dibaca. Area berikutnya dikaburkan. Untuk akses penuh, lanjutkan ke checkout.
                            </p>

                            <div class="ebook-preview-stage js-limited-scroll" data-max-scroll="400">
                                <iframe src="{{ route('book.preview.file', $book->id) }}#toolbar=0&navpanes=0&scrollbar=1"
                                    title="Pratinjau Ebook {{ $book->title }}" loading="lazy"></iframe>

                                <div class="ebook-preview-scroll-lock" aria-hidden="true"></div>
                                <div class="ebook-preview-blur-layer" aria-hidden="true"></div>
                                <div class="ebook-preview-overlay-text">Halaman 2+ dikunci (blur)</div>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LANJUTKAN PERJALANAN SECTION --}}
    <section class="py-5" style="background-color: #F8F5EE;">
        <div class="container">
            <h2 class="fw-bold mb-5" style="color: #DC3545;">Lanjutkan Perjalanan Bacamu</h2>

            <div class="row g-4">
                @forelse ($relatedBooks as $relatedBook)
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('book.detail', $relatedBook->id) }}" class="text-decoration-none text-dark">
                            <div class="card border-0 shadow-sm h-100">
                                <img src="{{ $relatedBook->cover_image ? asset('storage/' . $relatedBook->cover_image) : asset('image/book3.jpg') }}"
                                    data-fallback="{{ asset('image/book3.jpg') }}"
                                    onerror="this.onerror=null;this.src=this.dataset.fallback;"
                                    class="card-img-top" alt="{{ $relatedBook->title }}">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-1">{{ $relatedBook->title }}</h6>
                                    <p class="text-muted small mb-1">{{ optional($relatedBook->category)->name ?? 'Umum' }}</p>
                                    <p class="text-muted small mb-0">
                                        {{ $relatedBook->authors->pluck('name')->implode(', ') ?: 'Penulis Tidak Diketahui' }}
                                    </p>
                                    <p class="fw-bold mt-2 mb-0">
                                        IDR
                                        {{ number_format($relatedBook->discount_price ?? $relatedBook->price ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted mb-0">Belum ada buku rekomendasi lainnya.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

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

        .btn-check:checked+.btn-outline-secondary {
            background-color: #2C2C2C;
            border-color: #2C2C2C;
            color: white;
        }

        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #2C2C2C;
        }

        .ebook-preview-stage {
            position: relative;
            width: 100%;
            height: 78vh;
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
