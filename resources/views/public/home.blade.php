@extends('layouts.public')

@section('title', 'Beranda - BacaYukk.id')

@section('content')

    {{-- HERO SECTION --}}
    <section class="hero home-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        “Buku adalah jendela ilmu. <br>
                        Lewat buku, seseorang bisa melihat dunia.”<br> 
                    </h1>
                    <h3 class="text-white-50 mb-3">"Abdul Malik Fadjar"</h3>
                    <p class="mb-4 hero-subtitle">
                        Temukan koleksi buku terbaik untuk menambah wawasan<br>
                        dan menemani aktivitas membaca Anda.
                    </p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="#books" class="btn btn-danger px-4 py-2">Lihat Buku</a>
                        <a href="#author" class="btn btn-light border px-4 py-2">Lihat Penulis</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PILIH FORMAT SECTION --}}
    <section class="py-5" id="books">
        <div class="container">
            <h2 class="text-center fw-bold mb-2">Pilih Berdasarkan Format</h2>
            <p class="text-center text-muted mb-5 small">Pilih format yang paling sesuai dengan kebutuhan Anda</p>

            <div class="row g-4">

                {{-- Edisi E-Book --}}
                <div class="col-md-4">
                    <a href="{{ route('ebooks') }}" class="text-decoration-none d-block format-link">
                        <div class="format-card-modern">
                            {{-- Image --}}
                            <div class="format-img-wrap">
                                <img src="{{ asset('image/book1.png') }}"
                                    onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&q=80'"
                                    alt="E-Book Editions" class="format-img">
                                <div class="format-overlay"></div>
                                <div class="format-badge">
                                    <i class="bi bi-tablet"></i>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="format-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="format-label mb-1">Format 01</p>
                                        <h4 class="format-title mb-0">Edisi E-Book</h4>
                                    </div>
                                    <div class="format-arrow">
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                                <p class="format-desc mt-3 mb-0">
                                    Baca kapan saja & di mana saja di semua perangkat Anda.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Edisi Cetak --}}
                <div class="col-md-4">
                    <a href="{{ route('printed') }}" class="text-decoration-none d-block format-link">
                        <div class="format-card-modern">
                            {{-- Image --}}
                            <div class="format-img-wrap">
                                <img src="{{ asset('image/book4.png') }}"
                                    onerror="this.src='https://images.unsplash.com/photo-1512820790803-83ca734da794?w=600&q=80'"
                                    alt="Printed Editions" class="format-img">
                                <div class="format-overlay"></div>
                                <div class="format-badge">
                                    <i class="bi bi-book"></i>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="format-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="format-label mb-1">Format 02</p>
                                        <h4 class="format-title mb-0">Edisi Cetak</h4>
                                    </div>
                                    <div class="format-arrow">
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                                <p class="format-desc mt-3 mb-0">
                                    Nikmati pengalaman membaca fisik dengan buku cetak berkualitas tinggi.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Layanan --}}
                <div class="col-md-4">
                    <a href="{{ route('services') }}" class="text-decoration-none d-block format-link">
                        <div class="format-card-modern">
                            {{-- Image --}}
                            <div class="format-img-wrap">
                                <img src="{{ asset('images/formats/service-1.jpg') }}"
                                    onerror="this.src='https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&q=80'"
                                    alt="Service" class="format-img">
                                <div class="format-overlay"></div>
                                <div class="format-badge">
                                    <i class="bi bi-headset"></i>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="format-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="format-label mb-1">Format 03</p>
                                        <h4 class="format-title mb-0">Layanan Kami</h4>
                                    </div>
                                    <div class="format-arrow">
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                                <p class="format-desc mt-3 mb-0">
                                    Konsultasi, ringkasan buku, hingga pembuatan presentasi profesional.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- BUKU TERBARU SECTION --}}
    @if (isset($books) && $books->count() > 0)
        <section class="py-5 bg-white">
            <div class="container">
                <h2 class="text-center fw-bold mb-5">Buku Terbaru</h2>

                <div class="row g-4">
                    @foreach ($books as $book)
                        <div class="col-md-4">
                            <div class="book-card card border-0 shadow-sm h-100">
                                <div class="book-image-wrapper">
                                    @if (isset($book->cover_image) && $book->cover_image)
                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                            class="card-img-top"
                                            data-fallback="{{ asset('images/books/default-book.jpg') }}"
                                            onerror="this.onerror=null;this.src=this.dataset.fallback;">
                                    @else
                                        <img src="{{ asset('images/books/default-book.jpg') }}" alt="{{ $book->title }}"
                                            class="card-img-top">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $book->title }}</h5>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($book->description ?? 'Deskripsi belum tersedia', 80) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="badge bg-secondary">{{ $book->format ?? 'Buku' }}</span>
                                        <a href="{{ route('book.detail', $book->id) }}"
                                            class="btn btn-sm btn-outline-dark">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- KENALI PENULIS SECTION --}}
    <section class="py-5" id="author" style="background-color: #F8F5EE;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="text-center">
                        <img src="{{ asset('image/authors/author.png') }}"
                            onerror="this.src='https://via.placeholder.com/400x400/6B5D52/ffffff?text=Author+Photo'"
                            alt="Mr Alit Asmara" class="img-fluid rounded-4 shadow"
                            style="max-width: 500px; height:400px; width: 100%;">
                    </div>
                </div>
                <div class="col-lg-7">
                    <p class="text-danger fw-semibold mb-2">Kenali Penulis</p>
                    <h2 class="fw-bold mb-4" style="font-size: 2.5rem;">Mr Alit Asmara</h2>
                    <p class="text-muted" style="line-height: 1.8;">
                        Alit Asmara adalah penulis yang berfokus pada pengembangan literasi dan berbagi wawasan praktis
                        melalui buku-buku yang relevan dengan kebutuhan pembaca modern.
                    </p>
                    <p class="text-muted" style="line-height: 1.8;">
                        Melalui karya-karyanya, beliau menghadirkan pembahasan yang mudah dipahami, aplikatif, dan
                        membantu pembaca mendapatkan nilai nyata dari setiap bacaan.
                    </p>

                    {{-- Social Media Icons --}}
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-decoration-none">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; border: 2px solid #E5E5E5;">
                                <i class="bi bi-instagram fs-5"></i>
                            </div>
                        </a>
                        <a href="#" class="text-decoration-none">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; border: 2px solid #E5E5E5;">
                                <i class="bi bi-tiktok fs-5"></i>
                            </div>
                        </a>
                        <a href="#" class="text-decoration-none">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; border: 2px solid #E5E5E5;">
                                <i class="bi bi-facebook fs-5"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        .home-hero {
            position: relative;
            padding: 110px 0;
            margin-bottom: 60px;
            background-image: linear-gradient(120deg, rgba(20, 20, 20, 0.72), rgba(20, 20, 20, 0.48)), url("{{ asset('image/Book-cover2.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            overflow: hidden;
        }

        .home-hero h1 {
            color: #ffffff;
            text-shadow: 0 3px 12px rgba(0, 0, 0, 0.35);
        }

        .home-hero .hero-subtitle {
            color: rgba(255, 255, 255, 0.9);
        }

        .home-hero .btn-light {
            background: rgba(255, 255, 255, 0.92);
            border-color: rgba(255, 255, 255, 0.92);
        }

        .home-hero .btn-light:hover {
            background: #ffffff;
            border-color: #ffffff;
        }

        @media (max-width: 768px) {
            .home-hero {
                padding: 85px 0;
            }
        }

        /* FORMAT CARD — Minimalis & EleganKonsep: Refined Editorial */

        .format-link {
            color: inherit;
        }

        /* Card wrapper */
        .format-card-modern {
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #EBEBEB;
            transition: box-shadow 0.35s ease, transform 0.35s ease;
        }

        .format-link:hover .format-card-modern {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.10);
        }

        /* ---- Image area ---- */
        .format-img-wrap {
            position: relative;
            width: 100%;
            height: 240px;
            overflow: hidden;
            background: #F0EDE6;
        }

        .format-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.55s ease;
            display: block;
        }

        /* Slow zoom saat hover */
        .format-link:hover .format-img {
            transform: scale(1.07);
        }

        /* Gradient bawah — kedalaman halus */
        .format-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg,
                    transparent 55%,
                    rgba(0, 0, 0, 0.06) 100%);
            pointer-events: none;
        }

        /* Badge icon pojok kanan atas — frosted glass */
        .format-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #2C2C2C;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.10);
            transition: background 0.3s ease, color 0.3s ease;
        }
/* asdasdas */
        .format-link:hover .format-badge {
            background: #DC3545;
            color: #ffffff;
        }

        /* ---- Text body ---- */
        .format-body {
            padding: 20px 22px 22px;
        }

        /* Nomor label kecil merah */
        .format-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #DC3545;
        }

        /* Judul format */
        .format-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1A1A1A;
            transition: color 0.3s ease;
            line-height: 1.2;
        }

        .format-link:hover .format-title {
            color: #DC3545;
        }

        /* Deskripsi singkat */
        .format-desc {
            font-size: 0.82rem;
            color: #8A8A8A;
            line-height: 1.65;
            border-top: 1px solid #F2F2F2;
            padding-top: 12px;
            margin-top: 12px !important;
        }

        /* Panah bundar — geser kanan + warna saat hover */
        .format-arrow {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1.5px solid #DEDEDE;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: #6C6C6C;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .format-link:hover .format-arrow {
            background: #DC3545;
            border-color: #DC3545;
            color: #ffffff;
            transform: translateX(4px);
        }
    </style>
@endpush
