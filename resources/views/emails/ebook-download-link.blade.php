<x-mail::message>
    # 📚 File Ebook Anda Siap Diunduh!

    Terima kasih telah membeli **{{ $book->title }}**!

    ---

    ## Informasi Pesanan
    - **Nomor Pesanan:** {{ $order->order_number }}
    - **Nama Pelanggan:** {{ $order->guest_name ?? 'Pelanggan' }}
    - **Email:** {{ $order->guest_email ?? '-' }}
    - **Tanggal Pembelian:** {{ $order->created_at->format('d M Y H:i') }}

    ---

    ## Detail Buku
    - **Judul:** {{ $book->title }}
    - **Penulis:** {{ $book->authors->pluck('name')->implode(', ') }}
    - **Harga:** Rp {{ number_format($order->orderItems->first()->price, 0, ',', '.') }}

    ---

    ## 📥 Download Ebook Anda

    Klik tombol di bawah untuk mengunduh file ebook Anda. Link ini berlaku hingga
    **{{ $delivery->expired_at->format('d M Y H:i') }}**.

    <x-mail::button :url="$downloadLink" color="primary">
        Unduh Ebook Sekarang
    </x-mail::button>

    ---

    ## ⚠️ Perhatian Penting
    - Link download **berlaku selama 7 hari** dari email ini dikirim
    - Anda dapat mengunduh berkali-kali selama link masih aktif
    - Jika tautan sudah kedaluwarsa, silakan hubungi layanan pelanggan kami
    - File ini **hanya untuk penggunaan pribadi** dan tidak boleh didistribusikan

    ---

    ## 📧 Butuh Bantuan?
    Jika Anda mengalami masalah saat mengunduh, silakan hubungi kami melalui:
    - Email: {{ config('app.support_email') }}
    - WhatsApp: {{ config('app.support_phone') }}

    ---

    Terima kasih telah berbelanja di **{{ config('app.name') }}**!

    Selamat membaca! 📖

    @component('mail::subcopy')
        Ini adalah email otomatis, harap tidak membalas email ini.
    @endcomponent
</x-mail::message>
