@extends('layouts.public')

@section('title', 'Koleksi E-Book - BacaYukk.id')

@section('content')

    {{-- BREADCRUMB --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">E-Book</li>
            </ol>
        </nav>
    </div>

    {{-- E-BOOK COLLECTIONS SECTION --}}
    <section class="py-5">
        <div class="container">
            <div class="row">
                {{-- SIDEBAR FILTER --}}
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <div class="bg-white p-4 rounded shadow-sm">
                        <h5 class="fw-bold mb-4">Filter Berdasarkan Penulis</h5>

                        <form action="{{ route('ebooks') }}" method="GET">
                            @forelse($authors as $author)
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="authors[]"
                                        value="{{ $author->id }}" id="author{{ $author->id }}"
                                        {{ in_array($author->id, request()->input('authors', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="author{{ $author->id }}">
                                        {{ $author->name }}
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted small mb-3">Belum ada data penulis.</p>
                            @endforelse

                            <button type="submit" class="btn btn-danger w-100 mt-2">Terapkan Filter</button>
                            <a href="{{ route('ebooks') }}" class="btn btn-outline-secondary w-100 mt-2">Atur Ulang</a>
                        </form>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="col-lg-9">
                    {{-- Header --}}
                    <div class="mb-4">
                        <h1 class="fw-bold mb-2" style="font-size: 2.5rem;">Koleksi E-Book</h1>
                        <p class="text-muted">
                            Jelajahi koleksi e-book kami yang siap dibaca kapan saja.<br>
                            Menampilkan buku dengan format <strong>ebook</strong> dan <strong>keduanya</strong>.
                        </p>
                        <span class="badge bg-secondary">{{ $books->count() }} buku tersedia</span>
                    </div>

                    {{-- Books Grid --}}
                    <div class="row g-4">
                        @forelse($books as $book)
                            <div class="col-lg-4 col-md-6">
                                <a href="{{ route('book.detail', $book->id) }}" class="text-decoration-none">
                                    <div class="card border-0 shadow-sm h-100 book-card-hover">
                                        <div class="position-relative overflow-hidden" style="height: 400px;">
                                            <span class="position-absolute top-0 end-0 m-3 badge bg-dark">
                                                {{ strtoupper($book->format) }}
                                            </span>
                                            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('image/book1.jpg') }}"
                                                data-fallback="{{ asset('image/book1.jpg') }}"
                                                onerror="this.onerror=null;this.src=this.dataset.fallback;"
                                                class="card-img-top h-100 w-100" style="object-fit: cover;"
                                                alt="{{ $book->title }}">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="fw-bold mb-2 text-dark">{{ $book->title }}</h5>
                                            <p class="text-muted small mb-1">
                                                {{ $book->authors->pluck('name')->implode(', ') ?: 'Penulis Tidak Diketahui' }}
                                            </p>
                                            <p class="text-muted small mb-3">
                                                {{ optional($book->category)->name ?? 'Umum' }}
                                            </p>

                                            <div class="mt-auto">
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
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
                                    <h4 class="text-muted mt-3">Belum ada E-Book tersedia</h4>
                                    <p class="text-muted">Silakan upload buku dengan format ebook atau both.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
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

        /* Custom Checkbox Style */
        .form-check-input {
            border-radius: 4px;
            border: 2px solid #dee2e6;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #DC3545;
            border-color: #DC3545;
        }

        .form-check-label {
            cursor: pointer;
            margin-left: 8px;
            color: #2C2C2C;
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
    </style>
@endpush
