<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Pengiriman</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2 style="margin-bottom: 8px;">Pesanan Anda Sudah Dikirim 📦</h2>
    <p>Halo {{ $order->guest_name ?? 'Pelanggan' }},</p>
    <p>Pesanan dengan nomor <strong>{{ $order->order_number }}</strong> sudah diserahkan ke kurir.</p>

    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; margin: 16px 0;">
        <p style="margin: 0 0 8px 0;"><strong>No. Resi:</strong> {{ $order->shipping_tracking_number }}</p>
        <p style="margin: 0 0 8px 0;"><strong>Kurir:</strong> {{ $order->shipping_method ?? '-' }}</p>
        <p style="margin: 0;"><strong>Tanggal Kirim:</strong> {{ optional($order->shipped_at)->format('d M Y H:i') ?? now()->format('d M Y H:i') }}</p>
    </div>

    @if($order->shipping_tracking_url)
        <p>
            <a href="{{ $order->shipping_tracking_url }}" style="display: inline-block; padding: 10px 14px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px;">
                Lacak Paket
            </a>
        </p>
    @endif

    <p>Notifikasi resi ini juga dikirim ke nomor HP: <strong>{{ $order->guest_phone ?? '-' }}</strong>.</p>
    <p>Terima kasih telah berbelanja di {{ config('app.name') }}.</p>
</body>
</html>
