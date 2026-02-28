@extends('layouts.public')

@section('title', 'Layanan - BacaYukk.id')

@section('content')

    {{-- BREADCRUMB --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Layanan</li>
            </ol>
        </nav>
    </div>

    {{-- HERO SECTION --}}
    <section class="py-5" style="background: linear-gradient(135deg, #F8F5EE 0%, #FAF7F0 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-danger mb-3">LAYANAN KAMI</span>
                    <h1 class="display-4 fw-bold mb-3" style="color: #2C2C2C;">
                        Layanan Profesional untuk Kebutuhan Akademik & Bisnis Anda
                    </h1>
                    <p class="lead text-muted mb-4">
                        Kami menyediakan layanan profesional untuk membantu Anda dalam berbagai kebutuhan akademik dan
                        bisnis.
                        Dari layanan Buku Memori Kehidupan (Life Memory Book) hingga pembuatan presentasi, tim ahli
                        kami siap membantu Anda.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#services" class="btn btn-danger btn-lg px-4">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Lihat Layanan
                        </a>
                        <a href="#contact" class="btn btn-outline-dark btn-lg px-4">
                            <i class="bi bi-envelope me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <img src="{{ asset('images/service-illustration.png') }}"
                        onerror="this.src='https://via.placeholder.com/500x400/E8E4D9/2C2C2C?text=Services'" alt="Services"
                        class="img-fluid" style="max-width: 450px;">
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICES SECTION --}}
    <section class="py-5" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Layanan Kami</h2>
                <p class="text-muted">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="row g-4">
                {{-- Service 1: Buku Memori Kehidupan (Life Memory Book) --}}
                <div class="col-lg-6">
                    <div class="service-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div class="service-content">
                            <h3 class="fw-bold mb-3">Buku Memori Kehidupan (Life Memory Book)</h3>
                            <p class="badge bg-info text-dark mb-3">Biografi & Dokumentasi Kisah</p>
                            <p class="text-muted mb-3">
                                Abadikan perjalanan hidup, pengalaman berharga, dan cerita keluarga Anda menjadi buku
                                yang rapi, menyentuh, dan bermakna. Layanan ini cocok untuk hadiah spesial, warisan
                                cerita keluarga, maupun dokumentasi pribadi jangka panjang.
                            </p>

                            <div class="service-features mb-4">
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Wawancara dan Penggalian Cerita</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Penyusunan Alur Kisah yang Personal</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Penulisan dan Penyuntingan Profesional</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Desain Tata Letak Siap Cetak</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Mulai dari</span>
                                    <h4 class="fw-bold text-danger mb-0">IDR 500.000</h4>
                                </div>
                                <a href="#contact" class="btn btn-danger">
                                    <i class="bi bi-calendar-check me-2"></i>Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Service 2: Ringkasan Buku --}}
                <div class="col-lg-6">
                    <div class="service-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-book-fill"></i>
                        </div>
                        <div class="service-content">
                            <h3 class="fw-bold mb-3">Layanan Ringkasan Buku</h3>
                            <p class="badge bg-warning text-dark mb-3">Ringkasan Profesional</p>
                            <p class="text-muted mb-3">
                                Tidak punya waktu untuk membaca buku lengkap? Kami akan meringkas buku untuk Anda dengan
                                poin-poin penting dan insight yang dapat langsung diterapkan.
                            </p>

                            <div class="service-features mb-4">
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Ringkasan Komprehensif</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Poin Penting & Insight</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Format PDF & Word</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Pengerjaan 2-3 Hari</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Mulai dari</span>
                                    <h4 class="fw-bold text-danger mb-0">IDR 150.000</h4>
                                </div>
                                <a href="#contact" class="btn btn-danger">
                                    <i class="bi bi-calendar-check me-2"></i>Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Service 3: File to PPT Conversion --}}
                <div class="col-lg-6">
                    <div class="service-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-file-earmark-slides-fill"></i>
                        </div>
                        <div class="service-content">
                            <h3 class="fw-bold mb-3">Konversi File ke PowerPoint</h3>
                            <p class="badge bg-primary text-white mb-3">Konversi Dokumen</p>
                            <p class="text-muted mb-3">
                                Ubah dokumen Word, PDF, atau file lainnya menjadi presentasi PowerPoint yang menarik dan
                                profesional.
                                Kami desain dengan visual yang eye-catching.
                            </p>

                            <div class="service-features mb-4">
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Konversi dari Word/PDF/Excel</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Desain Modern & Profesional</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Infografis & Visual</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Revisi 2x</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Mulai dari</span>
                                    <h4 class="fw-bold text-danger mb-0">IDR 200.000</h4>
                                </div>
                                <a href="#contact" class="btn btn-danger">
                                    <i class="bi bi-calendar-check me-2"></i>Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Service 4: Pembuatan PPT Kustom --}}
                <div class="col-lg-6">
                    <div class="service-card h-100">
                        <div class="service-icon">
                            <i class="bi bi-easel-fill"></i>
                        </div>
                        <div class="service-content">
                            <h3 class="fw-bold mb-3">Pembuatan PowerPoint Kustom</h3>
                            <p class="badge bg-success text-white mb-3">Buat dari Nol</p>
                            <p class="text-muted mb-3">
                                Butuh presentasi untuk pitch, seminar, atau meeting? Kami buat PowerPoint sesuai topik dan
                                kebutuhan Anda dengan desain custom yang impactful.
                            </p>

                            <div class="service-features mb-4">
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Custom Design Sesuai Brand</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Riset & Pembuatan Konten</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Animasi & Transisi</span>
                                </div>
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Revisi Tanpa Batas</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">Mulai dari</span>
                                    <h4 class="fw-bold text-danger mb-0">IDR 350.000</h4>
                                </div>
                                <a href="#contact" class="btn btn-danger">
                                    <i class="bi bi-calendar-check me-2"></i>Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MENGAPA MEMILIH KAMI SECTION --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Mengapa Memilih Kami?</h2>
                <p class="text-muted">Alasan mengapa ribuan klien mempercayai kami</p>
            </div>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="why-card text-center">
                        <div class="why-icon">
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <h5 class="fw-bold mt-3 mb-2">Tim Profesional</h5>
                        <p class="text-muted small">
                            Tim ahli berpengalaman di bidangnya masing-masing
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="why-card text-center">
                        <div class="why-icon">
                            <i class="bi bi-lightning-charge-fill text-danger"></i>
                        </div>
                        <h5 class="fw-bold mt-3 mb-2">Pengerjaan Cepat</h5>
                        <p class="text-muted small">
                            Pengerjaan cepat tanpa mengorbankan kualitas
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="why-card text-center">
                        <div class="why-icon">
                            <i class="bi bi-shield-check text-success"></i>
                        </div>
                        <h5 class="fw-bold mt-3 mb-2">Jaminan Kualitas</h5>
                        <p class="text-muted small">
                            Garansi revisi hingga Anda puas
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="why-card text-center">
                        <div class="why-icon">
                            <i class="bi bi-cash-coin text-primary"></i>
                        </div>
                        <h5 class="fw-bold mt-3 mb-2">Harga Terjangkau</h5>
                        <p class="text-muted small">
                            Harga terjangkau dengan kualitas premium
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CARA KERJA SECTION --}}
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Cara Kerja</h2>
                <p class="text-muted">Proses pemesanan yang mudah dan cepat</p>
            </div>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5 class="fw-bold mt-3 mb-2">Pilih Layanan</h5>
                        <p class="text-muted small">
                            Pilih layanan yang sesuai dengan kebutuhan Anda
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5 class="fw-bold mt-3 mb-2">Hubungi Kami</h5>
                        <p class="text-muted small">
                            Konsultasi gratis untuk diskusi detail proyek
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5 class="fw-bold mt-3 mb-2">Pembayaran</h5>
                        <p class="text-muted small">
                            Lakukan pembayaran sesuai paket yang dipilih
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h5 class="fw-bold mt-3 mb-2">Terima Hasil</h5>
                        <p class="text-muted small">
                            Dapatkan hasil sesuai deadline yang disepakati
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- KONTAK SECTION --}}
    <section class="py-5 bg-dark text-white" id="contact">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold mb-3">Siap Memulai?</h2>
                    <p class="mb-4">
                        Hubungi kami sekarang untuk konsultasi gratis dan dapatkan penawaran terbaik untuk proyek Anda.
                    </p>
                    <div class="contact-info mb-3">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <span>services@bacayukk.id</span>
                    </div>
                    <div class="contact-info mb-3">
                        <i class="bi bi-whatsapp me-2"></i>
                        <span>+62 812-3456-7890</span>
                    </div>
                    <div class="contact-info">
                        <i class="bi bi-clock-fill me-2"></i>
                        <span>Senin - Jumat, 09:00 - 17:00 WIB</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-form bg-white text-dark p-4 rounded">
                        <h4 class="fw-bold mb-4">Form Kontak Cepat</h4>
                        <form>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <select class="form-select" required>
                                    <option value="">Pilih Layanan</option>
                                    <option value="life-memory-book">Buku Memori Kehidupan (Life Memory Book)</option>
                                    <option value="summary">Ringkasan Buku</option>
                                    <option value="conversion">File to PPT</option>
                                    <option value="custom">PPT Kustom</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="4" placeholder="Pesan Anda" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        /* Service Card */
        .service-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s ease;
            border: 2px solid #E8E4D9;
            position: relative;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: #DC3545;
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #DC3545 0%, #C82333 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .service-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .service-features {
            border-top: 1px solid #E8E4D9;
            border-bottom: 1px solid #E8E4D9;
            padding: 20px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #5C5C5C;
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        /* Why Choose Us */
        .why-card {
            padding: 30px 20px;
            background: white;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .why-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .why-icon i {
            font-size: 3rem;
        }

        /* Steps */
        .step-card {
            text-align: center;
            padding: 20px;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #DC3545 0%, #C82333 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto;
        }

        /* Contact Form */
        .contact-info {
            font-size: 1.1rem;
        }

        .contact-info i {
            color: #DC3545;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
            color: #6c757d;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Smooth scroll to section
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endpush
