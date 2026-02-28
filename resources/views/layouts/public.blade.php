<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BacaYukk.id')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #F5F1E8;
            font-family: 'Poppins', sans-serif;
            color: #2C2C2C;
        }

        /* NAVBAR */
        .navbar {
            background-color: #F5F1E8;
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: #2C2C2C !important;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: #5C5C5C !important;
            font-weight: 400;
            font-size: 1.05rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #2C2C2C !important;
        }

        /* SEARCH BAR */
        .search-wrapper {
            position: relative;
            max-width: 400px;
        }

        .search-input {
            border: 1px solid #E8E4D9;
            border-radius: 25px;
            padding: 8px 40px 8px 20px;
            background-color: white;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input:focus {
            outline: none;
            border-color: #2C2C2C;
            box-shadow: 0 0 0 3px rgba(44, 44, 44, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #5C5C5C;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .search-icon:hover {
            color: #2C2C2C;
        }

        /* HERO SECTION */
        .hero {
            background-color: #F8F5EE;
            padding: 90px 0;
            margin-bottom: 60px;
        }

        .hero h1 {
            font-weight: 600;
            line-height: 1.3;
        }

        .btn-danger {
            background-color: #DC3545;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #C82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        /* FORMAT CARDS */
        .format-card {
            background: #FAF7F0;
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 1px solid #E8E4D9;
        }

        .format-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        /* BOOK CARD */
        .book-card {
            border-radius: 14px;
            overflow: hidden;
            transition: transform .3s ease, box-shadow .3s ease;
            background: white;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        /* IMAGE LANDSCAPE 16:9 */
        .book-image-wrapper {
            width: 100%;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background-color: #E8E4D9;
        }

        .book-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .book-card:hover .book-image-wrapper img {
            transform: scale(1.05);
        }

        /* CARD BODY */
        .book-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 180px;
        }

        /* AUTHOR SECTION */
        .author-image {
            position: relative;
        }

        .author-image img {
            border: 8px solid #fff;
        }

        /* FOOTER */
        footer {
            background-color: #1A1A1A;
            border-top: 1px solid #E8E4D9;
            color: white;
        }

        .footer-brand {
            font-size: 1.8rem;
            font-weight: 600;
            color: white;
        }

        .footer-link {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-bottom: 10px;
        }

        .footer-link:hover {
            color: #DC3545;
        }

        .footer-heading {
            color: #DC3545;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* SOCIAL MEDIA ICONS */
        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: transparent;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
        }

        .social-icon:hover {
            background-color: white;
            border-color: white;
            color: #1A1A1A;
            transform: translateY(-3px);
        }

        .social-icon i {
            font-size: 1.5rem;
        }

        /* SECTION SPACING */
        .section-spacing {
            padding: 80px 0;
        }

        /* UTILITIES */
        .text-primary-custom {
            color: #2C2C2C !important;
        }

        .bg-primary-custom {
            background-color: #F8F5EE !important;
        }

        .bg-secondary-custom {
            background-color: #FAF7F0 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-wrapper {
                width: 100%;
                margin-top: 15px;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR PUBLIK --}}
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">BacaYukk.id</a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navPublic">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav mx-auto gap-4">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('tentang-kami') ? 'fw-semibold' : '' }}"
                            href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('produk') ? 'fw-semibold' : '' }}" href="/produk">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('services') ? 'fw-semibold' : '' }}"
                            href="{{ route('services') }}">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('blog') ? 'fw-semibold' : '' }}" href="/blog">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services') }}#contact">Kontak</a>
                    </li>
                </ul>

                {{-- SEARCH BAR --}}
                <div class="search-wrapper ms-lg-3">
                    <form action="{{ route('search') }}" method="GET" class="position-relative">
                        <input type="text" name="q" class="form-control search-input" placeholder="Cari Buku"
                            value="{{ request('q') }}" required>
                        <button type="submit" class="border-0 bg-transparent search-icon">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h5 class="footer-brand mb-3">BacaYukk.id</h5>
                    <p class=" small text-footer" style="max-width: 400px; line-height: 1.8; color:white;">
                        BacaYukk.id adalah platform buku digital dan cetak yang membantu Anda menemukan bacaan
                        berkualitas untuk belajar, bekerja, dan pengembangan diri.
                    </p>

                    {{-- Social Media Icons --}}
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="social-icon">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="footer-heading">Navigasi</h6>
                            <a href="{{ route('about') }}" class="footer-link">Tentang Kami</a>
                            <a href="/produk" class="footer-link">Produk</a>
                            <a href="{{ route('services') }}" class="footer-link">Layanan</a>
                            <a href="/blog" class="footer-link">Blog</a>
                            <a href="{{ route('services') }}#contact" class="footer-link">Kontak</a>
                            <a href="/privacy" class="footer-link">Kebijakan Privasi</a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color: #444;">

            <div class="text-center">
                <small class="text-muted">
                    © 2025 BacaYukk.id, Semua Hak Dilindungi
                </small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Search Form Submit on Enter --}}
    <script>
        // Auto-submit search form on Enter key
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.closest('form').submit();
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
