@extends('layouts.public')

@section('title', 'Produk - BacaYukk.id')

@section('content')

    {{-- BREADCRUMB --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Produk</li>
            </ol>
        </nav>
    </div>

    {{-- PRODUK SECTION --}}
    <section class="py-5">
        <div class="container">
            {{-- Header --}}
            <div class="mb-5">
                <h1 class="fw-bold mb-2" style="font-size: 2.5rem;">Semua Produk</h1>
                <p class="text-muted">
                    Jelajahi koleksi buku lengkap kami dalam berbagai format.<br>
                    Temukan bacaan favorit Anda berikutnya dari perpustakaan kami.
                </p>
            </div>

            {{-- Filter Tabs --}}
            <div class="mb-4">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('format') ? 'active' : '' }}" href="{{ route('produk') }}">
                            Semua Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('format') == 'ebook' ? 'active' : '' }}"
                            href="{{ route('produk', ['format' => 'ebook']) }}">
                            Buku Digital
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('format') == 'printed' ? 'active' : '' }}"
                            href="{{ route('produk', ['format' => 'printed']) }}">
                            Buku Cetak
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Books Grid --}}
            <div class="row g-4">
                @forelse($books as $book)
                    {{-- Book Card --}}
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="{{ route('book.detail', $book->id) }}" class="text-decoration-none">
                            <div class="card border-0 shadow-sm h-100 book-card-hover">
                                <div class="position-relative overflow-hidden" style="height: 400px;">
                                    {{-- Format Badge --}}
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark">
                                        {{ ucfirst($book->format) }}
                                    </span>

                                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('image/book1.jpg') }}"
                                        data-fallback="{{ asset('image/book1.jpg') }}"
                                        onerror="this.onerror=null;this.src=this.dataset.fallback;"
                                        class="card-img-top h-100 w-100" style="object-fit: cover;"
                                        alt="{{ $book->title }}">
                                </div>
                                <div class="card-body">
                                    <h5 class="fw-bold mb-2 text-dark">{{ $book->title }}</h5>
                                    <p class="text-muted small mb-1">
                                        {{ $book->authors->pluck('name')->implode(', ') ?? 'Penulis Tidak Diketahui' }}</p>

                                    @if (isset($book->discount_price) && $book->discount_price < $book->price)
                                        <p class="fw-bold mb-0 text-danger">
                                            IDR {{ number_format($book->discount_price, 0, ',', '.') }}
                                        </p>
                                        <p class="text-muted small text-decoration-line-through mb-0">
                                            IDR {{ number_format($book->price, 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="fw-bold mb-0 text-dark">
                                            IDR {{ number_format($book->price ?? 0, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Belum ada produk tersedia</h4>
                            <p class="text-muted">Silakan cek kembali nanti untuk produk terbaru</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($books->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $books->appends(['format' => request('format')])->links() }}
                </div>
            @endif
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

        /* Nav Pills */
        .nav-pills .nav-link {
            color: #5C5C5C;
            border-radius: 25px;
            padding: 10px 24px;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: #F8F5EE;
            color: #2C2C2C;
        }

        .nav-pills .nav-link.active {
            background-color: #DC3545;
            color: white;
        }

        /* Book Card Hover Effect */
        .book-card-hover {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .book-card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
        }

        .book-card-hover img {
            transition: transform 0.3s ease;
        }

        .book-card-hover:hover img {
            transform: scale(1.05);
        }

        /* Pagination Styling */
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
    </style>
@endpush
