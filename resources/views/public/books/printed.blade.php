@extends('layouts.public')

@section('title', 'Buku Cetak - BacaYukk.id')

@section('content')

    {{-- BREADCRUMB --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buku Cetak</li>
            </ol>
        </nav>
    </div>

    {{-- PRINTED BOOKS HEADER SECTION --}}
    <section class="py-5" style="background: linear-gradient(135deg, #F8F5EE 0%, #FAF7F0 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="fw-bold mb-3" style="font-size: 3rem;">Koleksi Buku Cetak</h1>
                    <p class="lead text-muted mb-4">
                        Nikmati pengalaman membaca buku fisik. Koleksi cetak kami menghadirkan edisi hardcover
                        berkualitas tinggi,
                        cocok untuk perpustakaan pribadi maupun hadiah berkesan.
                    </p>
                    <div class="d-flex gap-2 mb-3">
                        <span class="badge bg-dark px-3 py-2">📦 Physical Delivery</span>
                        <span class="badge bg-dark px-3 py-2">📚 Hardcover Edition</span>
                        <span class="badge bg-dark px-3 py-2">✨ Premium Quality</span>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('images/printed-books-illustration.png') }}"
                        data-fallback="{{ asset('image/book2.jpg') }}"
                        onerror="this.onerror=null;this.src=this.dataset.fallback;"
                        alt="Buku Cetak" class="img-fluid" style="max-width: 450px;">
                </div>
            </div>
        </div>
    </section>

    {{-- PRINTED BOOKS GRID SECTION --}}
    <section class="py-5">
        <div class="container">
            {{-- Section Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Buku Cetak Tersedia</h2>
                    <p class="text-muted mb-0">
                        <span class="badge bg-secondary">{{ $books->total() }} buku tersedia</span>
                    </p>
                </div>

                {{-- Sort Dropdown (Optional) --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel me-2"></i>Urutkan
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('printed', ['sort' => 'latest']) }}">Terbaru</a></li>
                        <li><a class="dropdown-item" href="{{ route('printed', ['sort' => 'price_low']) }}">Harga: Rendah ke
                            Tinggi</a></li>
                        <li><a class="dropdown-item" href="{{ route('printed', ['sort' => 'price_high']) }}">Harga: Tinggi ke
                            Rendah</a></li>
                        <li><a class="dropdown-item" href="{{ route('printed', ['sort' => 'title']) }}">Judul A-Z</a></li>
                    </ul>
                </div>
            </div>

            {{-- Books Grid --}}
            <div class="row g-4">
                @forelse($books as $book)
                    {{-- Printed Book Card --}}
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('book.detail', $book->id) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100 printed-book-card">
                                {{-- Book Cover --}}
                                <div class="position-relative overflow-hidden book-cover-wrapper">
                                    {{-- Stock Badge --}}
                                    @if (isset($book->stock) && $book->stock > 0)
                                        <span class="position-absolute top-0 start-0 m-3 badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Tersedia
                                        </span>
                                    @else
                                        <span class="position-absolute top-0 start-0 m-3 badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Stok Habis
                                        </span>
                                    @endif

                                    {{-- Hardcover Badge --}}
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark">
                                        <i class="bi bi-book me-1"></i>Edisi Hardcover
                                    </span>

                                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('image/book1.jpg') }}"
                                        data-fallback="{{ asset('image/book1.jpg') }}"
                                        onerror="this.onerror=null;this.src=this.dataset.fallback;"
                                        class="card-img-top" alt="{{ $book->title }}">
                                </div>

                                {{-- Book Info --}}
                                <div class="card-body">
                                    <h5 class="fw-bold mb-2 text-dark book-title">{{ $book->title }}</h5>
                                    <p class="text-muted small mb-1">
                                        <i
                                            class="bi bi-person me-1"></i>{{ $book->authors->pluck('name')->implode(', ') ?? 'Penulis Tidak Diketahui' }}
                                    </p>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-tag me-1"></i>{{ $book->category ?? 'Umum' }}
                                    </p>

                                    {{-- Book Specs --}}
                                    <div class="d-flex gap-2 mb-3 flex-wrap">
                                        @if (isset($book->pages))
                                            <span class="badge bg-light text-dark small">{{ $book->pages }} halaman</span>
                                        @endif
                                        @if (isset($book->language))
                                            <span class="badge bg-light text-dark small">{{ $book->language }}</span>
                                        @endif
                                    </div>

                                    {{-- Price --}}
                                    <div class="mt-auto">
                                        @if (isset($book->discount_price) && $book->discount_price < $book->price)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold text-danger fs-5">
                                                    IDR {{ number_format($book->discount_price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-muted small text-decoration-line-through">
                                                    IDR {{ number_format($book->price, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <span class="badge bg-danger small">
                                                {{ round((($book->price - $book->discount_price) / $book->price) * 100) }}%
                                                DISKON
                                            </span>
                                        @else
                                            <p class="fw-bold mb-0 text-dark fs-5">
                                                IDR {{ number_format($book->price ?? 0, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Card Footer --}}
                                <div class="card-footer bg-white border-0 pt-0">
                                    <button class="btn btn-outline-danger w-100">
                                        <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-book text-muted" style="font-size: 5rem;"></i>
                            <h3 class="text-muted mt-4 mb-3">Belum Ada Buku Cetak Tersedia</h3>
                            <p class="text-muted mb-4">
                                Kami sedang memperbarui koleksi buku cetak kami.<br>
                                Silakan cek kembali nanti atau jelajahi koleksi e-book kami.
                            </p>
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('ebooks') }}" class="btn btn-danger">
                                    <i class="bi bi-laptop me-2"></i>Lihat E-Book
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-dark">
                                    <i class="bi bi-house-door me-2"></i>Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($books->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $books->appends(['sort' => request('sort')])->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- WHY CHOOSE PRINTED BOOKS SECTION --}}
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Mengapa Memilih Buku Cetak?</h2>
            <div class="row g-4">
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="bi bi-hand-thumbs-up text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Pengalaman Nyata</h5>
                    <p class="text-muted small">
                        Rasakan kualitas kertas dan pengalaman membaca yang autentik
                    </p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="bi bi-gift text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Hadiah Sempurna</h5>
                    <p class="text-muted small">
                        Edisi hardcover cocok sebagai hadiah yang berkesan
                    </p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="bi bi-bookmark-star text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Layak Dikoleksi</h5>
                    <p class="text-muted small">
                        Bangun perpustakaan pribadi Anda dengan edisi premium
                    </p>
                </div>
                <div class="col-md-3 text-center">
                    <div class="mb-3">
                        <i class="bi bi-truck text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Pengiriman Cepat</h5>
                    <p class="text-muted small">
                        Dapatkan buku Anda dikirim aman hingga ke depan pintu
                    </p>
                </div>
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

        /* Printed Book Card */
        .printed-book-card {
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
        }

        .printed-book-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
        }

        /* Book Cover Wrapper */
        .book-cover-wrapper {
            height: 400px;
            background: linear-gradient(135deg, #E8E4D9 0%, #D4C5B0 100%);
            position: relative;
        }

        .book-cover-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .printed-book-card:hover .book-cover-wrapper img {
            transform: scale(1.08);
        }

        /* Book Title */
        .book-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 3rem;
        }

        /* Card Footer */
        .card-footer button {
            transition: all 0.3s ease;
        }

        .card-footer button:hover {
            background-color: #DC3545;
            color: white;
            transform: scale(1.02);
        }

        /* Pagination */
        .pagination {
            gap: 5px;
        }

        .page-link {
            border: 1px solid #dee2e6;
            color: #2C2C2C;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: #2C2C2C;
            border-color: #2C2C2C;
            color: white;
        }

        .page-item.active .page-link {
            background-color: #DC3545;
            border-color: #DC3545;
        }

        /* Badges */
        .badge {
            font-weight: 500;
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #F8F5EE;
            color: #DC3545;
        }
    </style>
@endpush
