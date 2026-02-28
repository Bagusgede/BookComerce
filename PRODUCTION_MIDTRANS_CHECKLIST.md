# Production Checklist - Midtrans + Ebook + Pengiriman Fisik

Checklist ini memastikan alur pembayaran Midtrans, pengiriman ebook, dan pengiriman buku fisik (resi + notifikasi) siap dipakai saat production.

## 0) Strategi Rilis Aman (Disarankan)

Jangan langsung full-live saat masih development. Gunakan 2 tahap:

1. **Soft Launch**
	- Midtrans tetap sandbox
	- Mailtrap/sandbox email
	- Uji internal 10-20 order sampai stabil
2. **Full Launch**
	- Midtrans production
	- SMTP production
	- webhook notifikasi nomor HP aktif

Jika langsung full-live, risiko bug akan langsung berdampak ke transaksi uang asli.

## 1) Environment Wajib (Production)

Set di `.env` server production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

MIDTRANS_IS_PRODUCTION=true
MIDTRANS_SERVER_KEY=Mid-server-xxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxx

QUEUE_CONNECTION=database
DB_QUEUE=default

MAIL_MAILER=smtp
MAIL_HOST=smtp.provider.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@domain-anda.com
MAIL_FROM_NAME="BookComerce"

PHONE_NOTIFICATION_WEBHOOK_URL=https://provider-anda.com/send
PHONE_NOTIFICATION_TOKEN=token-rahasia-provider

LOG_CHANNEL=stack
LOG_STACK=daily
PAYMENT_LOG_LEVEL=info
PAYMENT_LOG_DAYS=30
```

## 2) Midtrans Dashboard (Production)

Atur URL callback dan redirect di Midtrans:

- `Payment Notification URL` -> `https://domain-anda.com/payment/midtrans-callback`
- `Finish Redirect URL` -> `https://domain-anda.com/payment/finish`

Pastikan server URL memakai HTTPS valid (bukan self-signed).

## 3) Database & Cache

Jalankan deployment command:

```bash
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 4) Queue Worker (WAJIB untuk auto email)

Job email ebook sekarang menggunakan queue `emails`. Worker harus aktif 24/7.

Contoh Supervisor (`/etc/supervisor/conf.d/bookcomerce-worker.conf`):

```ini
[program:bookcomerce-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bookcomerce/artisan queue:work database --queue=emails,default --sleep=3 --tries=5 --timeout=120
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/bookcomerce/storage/logs/worker.log
```

Reload supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart bookcomerce-worker:*
```

## 5) Monitoring Penting

Pantau log berikut:

- `storage/logs/payment-*.log` (callback Midtrans, email delivery, download audit)
- `storage/logs/laravel-*.log`
- `storage/logs/worker.log`

Pantau failed jobs:

```bash
php artisan queue:failed
php artisan queue:retry all
```

Tambahan monitoring untuk alur pengiriman fisik:

- Pastikan order fisik setelah pembayaran masuk ke status `processing` (dipacking)
- Saat admin input resi, status harus berubah ke `shipped`
- Pastikan `shipping_tracking_number` tersimpan di order
- Pastikan email resi terkirim
- Pastikan webhook notifikasi nomor HP return sukses

## 6) Security Checklist

- Jangan commit `MIDTRANS_SERVER_KEY` ke repository.
- Aktifkan firewall/WAF dan batasi akses server seperlunya.
- Pastikan callback Midtrans hanya diproses jika signature valid.
- Pastikan nominal callback sama dengan total order.
- Download ebook hanya untuk order `paid` dan link bertoken dengan masa berlaku.

## 7) Uji End-to-End Sebelum Go Live

1. Buat order ebook dari checkout.
2. Bayar menggunakan Midtrans production test card/method.
3. Verifikasi status order berubah ke `paid`.
4. Verifikasi email masuk berisi link download.
5. Klik link download -> file terunduh.
6. Verifikasi rate-limit download dan expired link bekerja.

### Tambahan uji untuk buku fisik / campuran

1. Buat order yang berisi item format `physical` atau `both`.
2. Bayar via Midtrans.
3. Verifikasi status otomatis berubah ke `processing` (dipacking).
4. Admin input kurir + nomor resi di detail order admin.
5. Verifikasi status order berubah ke `shipped`.
6. Verifikasi customer menerima:
	- Email berisi nomor resi dan link tracking
	- Notifikasi ke nomor HP (via webhook provider)
7. Verifikasi user dapat melihat nomor resi di halaman konfirmasi order.

## 8) Rollback Plan

Jika email tidak terkirim:

1. Cek `queue:failed` dan `payment log`.
2. Retry failed jobs.
3. Sementara set `QUEUE_CONNECTION=sync` (darurat jangka pendek), lalu rollback setelah worker stabil.

Jika notifikasi nomor HP gagal:

1. Biarkan order tetap `shipped` (jangan rollback status order).
2. Cek log channel `payment` untuk error webhook.
3. Perbaiki credential/token provider.
4. Kirim ulang notifikasi manual dari panel admin/customer service.

---

Jika Anda mau, langkah berikutnya saya bisa siapkan **health-check command** satu perintah untuk memeriksa Midtrans, mail, queue worker, dan webhook notifikasi HP.
