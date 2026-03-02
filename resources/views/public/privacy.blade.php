@extends('layouts.public')

@section('title', 'Kebijakan Privasi')

@section('content')
    <section class="py-5 bg-white border-top border-bottom">
        <div class="container">
            <h1 class="fw-bold mb-3">Kebijakan Privasi BacaYukk.id</h1>
            <p class="text-muted mb-1">Tanggal Berlaku: 1 Maret 2026</p>
            <p class="text-muted mb-0">Terakhir Diperbarui: 1 Maret 2026</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container" style="max-width: 980px;">
            <p class="text-muted" style="line-height: 1.8;">
                BacaYukk.id ("kami") berkomitmen melindungi data pribadi pengguna. Kebijakan ini menjelaskan cara kami
                mengumpulkan, menggunakan, menyimpan, melindungi, dan membagikan data saat Anda menggunakan layanan kami,
                termasuk pada proses checkout dan pembayaran melalui payment gateway.
            </p>

            <h2 class="fw-bold mt-5 mb-3">1. Data yang Kami Kumpulkan</h2>
            <ul class="text-muted" style="line-height: 1.8;">
                <li>Data identitas: nama, email, nomor telepon.</li>
                <li>Data akun: informasi login dan profil pengguna.</li>
                <li>Data transaksi: produk, nominal pembayaran, status transaksi, riwayat pesanan.</li>
                <li>Data pengiriman: nama penerima, alamat, nomor telepon penerima (untuk buku cetak).</li>
                <li>Data teknis: alamat IP, jenis perangkat, browser, waktu akses, dan log sistem.</li>
            </ul>

            <h2 class="fw-bold mt-5 mb-3">2. Data Pembayaran & Payment Gateway</h2>
            <p class="text-muted" style="line-height: 1.8;">
                Untuk memproses pembayaran, kami menggunakan penyedia payment gateway resmi. Informasi pembayaran sensitif
                (seperti data kartu secara penuh dan CVV) diproses langsung oleh payment gateway dan tidak disimpan oleh
                sistem utama BacaYukk.id.
            </p>
            <p class="text-muted" style="line-height: 1.8;">
                Kami hanya menerima data yang diperlukan untuk validasi transaksi, seperti kode transaksi, status
                pembayaran, metode pembayaran, waktu transaksi, dan referensi order.
            </p>

            <h2 class="fw-bold mt-5 mb-3">3. Tujuan Penggunaan Data</h2>
            <ul class="text-muted" style="line-height: 1.8;">
                <li>Memproses pesanan dan menyelesaikan transaksi pembayaran.</li>
                <li>Memverifikasi pembayaran serta mencegah penyalahgunaan/fraud.</li>
                <li>Mengirimkan produk digital atau mengelola pengiriman buku cetak.</li>
                <li>Mengirim notifikasi transaksi, invoice, dan pembaruan layanan.</li>
                <li>Menyediakan bantuan pelanggan dan menindaklanjuti pertanyaan pengguna.</li>
                <li>Memenuhi kewajiban hukum, audit, perpajakan, dan kepatuhan.</li>
            </ul>

            <h2 class="fw-bold mt-5 mb-3">4. Dasar Hukum Pemrosesan</h2>
            <p class="text-muted" style="line-height: 1.8;">
                Pemrosesan data dilakukan berdasarkan persetujuan pengguna, pelaksanaan perjanjian transaksi, kewajiban
                hukum, dan kepentingan sah untuk keamanan layanan. Kami mengacu pada peraturan perlindungan data yang
                berlaku di Indonesia, termasuk UU No. 27 Tahun 2022 tentang Perlindungan Data Pribadi.
            </p>

            <h2 class="fw-bold mt-5 mb-3">5. Pembagian Data kepada Pihak Ketiga</h2>
            <p class="text-muted" style="line-height: 1.8;">
                Kami hanya membagikan data seperlunya kepada pihak ketiga berikut:
            </p>
            <ul class="text-muted" style="line-height: 1.8;">
                <li>Penyedia payment gateway dan mitra perbankan untuk pemrosesan pembayaran.</li>
                <li>Mitra logistik/pengiriman untuk pemenuhan pesanan fisik.</li>
                <li>Penyedia infrastruktur teknologi (hosting, email, monitoring) untuk operasional layanan.</li>
                <li>Otoritas berwenang jika diwajibkan oleh hukum.</li>
            </ul>

            <h2 class="fw-bold mt-5 mb-3">6. Penyimpanan dan Retensi Data</h2>
            <p class="text-muted" style="line-height: 1.8;">
                Data disimpan selama diperlukan untuk tujuan layanan, kepatuhan hukum, penyelesaian sengketa, dan audit.
                Data transaksi dapat disimpan hingga 5 (lima) tahun atau mengikuti ketentuan hukum yang berlaku.
            </p>

            <h2 class="fw-bold mt-5 mb-3">7. Keamanan Data</h2>
            <ul class="text-muted" style="line-height: 1.8;">
                <li>Transmisi data menggunakan protokol aman (HTTPS/TLS).</li>
                <li>Akses data internal dibatasi berdasarkan kebutuhan kerja (least privilege).</li>
                <li>Pencatatan aktivitas sistem (audit log) untuk pemantauan keamanan.</li>
                <li>Tinjauan berkala terhadap kerentanan dan pembaruan sistem.</li>
            </ul>

            <h2 class="fw-bold mt-5 mb-3">8. Hak Pengguna</h2>
            <p class="text-muted" style="line-height: 1.8;">Anda memiliki hak untuk:</p>
            <ul class="text-muted" style="line-height: 1.8;">
                <li>Meminta akses terhadap data pribadi Anda.</li>
                <li>Memperbarui atau mengoreksi data yang tidak akurat.</li>
                <li>Meminta penghapusan data sesuai ketentuan hukum yang berlaku.</li>
                <li>Menarik persetujuan pemrosesan data tertentu.</li>
                <li>Mengajukan pertanyaan atau keberatan terkait pemrosesan data.</li>
            </ul>

            <h2 class="fw-bold mt-5 mb-3">9. Cookie dan Teknologi Serupa</h2>
            <p class="text-muted" style="line-height: 1.8;">
                Kami menggunakan cookie untuk menjaga sesi login, menyimpan preferensi pengguna, serta membantu keamanan
                dan analitik penggunaan website. Anda dapat mengelola preferensi cookie melalui pengaturan browser Anda.
            </p>

            <h2 class="fw-bold mt-5 mb-3">10. Perubahan Kebijakan Privasi</h2>
            <p class="text-muted" style="line-height: 1.8;">
                Kami dapat memperbarui kebijakan ini dari waktu ke waktu. Perubahan akan ditampilkan di halaman ini
                beserta tanggal pembaruan terbaru.
            </p>

            <h2 class="fw-bold mt-5 mb-3">11. Kontak Perlindungan Data</h2>
            <p class="text-muted mb-1">Email: alit20147@gmail.com</p>
            <p class="text-muted mb-1">
                WhatsApp: <a href="https://wa.me/6282261560301?text=Halo%20BookComerce%2C%20saya%20ingin%20bertanya%20terkait%20layanan%20dan%20kebijakan%20privasi." target="_blank" rel="noopener" class="text-decoration-none">WhatsApp Me (082261560301)</a>
            </p>
            <p class="text-muted mb-1">Alamat: Perum Mutiara Abianbase Permai Blok IV No. 84, Jln. Raya Abianbase, Kel. Abianbase, Kec. Mengwi, Kab. Badung</p>
            <p class="text-muted">Kota &amp; Kode Pos: Badung, 80351</p>
        </div>
    </section>
@endsection
