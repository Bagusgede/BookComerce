# Admin Views Template Builder Guide

This guide shows how to create the remaining optional admin view templates using the established patterns.

## Quick Template Patterns

### 1. Author Show View
**Path:** `resources/views/admin/authors/show.blade.php`

```blade
@extends('admin.layout')

@section('title', 'Profil Penulis: ' . $author->name)
@section('page-title', 'Profil Penulis: ' . $author->name)

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Sidebar -->
    <div>
        <div class="card" style="padding: 1.5rem;">
            @if($author->photo)
                <img src="{{ Storage::url($author->photo) }}" alt="{{ $author->name }}" style="width: 100%; border-radius: 0.375rem; margin-bottom: 1rem;">
            @endif
            
            <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-primary" style="display: block; width: 100%; padding: 0.75rem; text-align: center; text-decoration: none; margin-bottom: 0.5rem;">✏️ Edit</a>
            
            <a href="{{ route('admin.authors.index') }}" class="btn btn-primary" style="display: block; width: 100%; padding: 0.75rem; text-align: center; background: #6b7280; text-decoration: none;">← Kembali</a>
        </div>
    </div>

    <!-- Main Content -->
    <div>
        <div class="card" style="padding: 2rem;">
            <h2 style="margin: 0 0 1rem 0;">{{ $author->name }}</h2>
            
            <p style="color: #6b7280;">📧 {{ $author->email ?? 'N/A' }}</p>
            
            <hr style="margin: 2rem 0;">
            
            <h3>Biografi</h3>
            <p style="color: #374151; line-height: 1.6;">{{ $author->bio ?? 'Tidak ada biografi' }}</p>
            
            <h3 style="margin-top: 2rem;">Media Sosial</h3>
            <ul style="list-style: none; padding: 0;">
                @if($author->social_media['twitter'] ?? null)
                    <li><a href="{{ $author->social_media['twitter'] }}" target="_blank" style="color: #3b82f6;">🐦 Twitter</a></li>
                @endif
                @if($author->social_media['instagram'] ?? null)
                    <li><a href="{{ $author->social_media['instagram'] }}" target="_blank" style="color: #3b82f6;">📷 Instagram</a></li>
                @endif
                @if($author->social_media['facebook'] ?? null)
                    <li><a href="{{ $author->social_media['facebook'] }}" target="_blank" style="color: #3b82f6;">👥 Facebook</a></li>
                @endif
                @if($author->social_media['website'] ?? null)
                    <li><a href="{{ $author->social_media['website'] }}" target="_blank" style="color: #3b82f6;">🌐 Website</a></li>
                @endif
            </ul>
            
            <h3 style="margin-top: 2rem;">Statistik</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="background: #dbeafe; padding: 1rem; border-radius: 0.375rem;">
                    <p style="color: #0369a1; font-size: 0.875rem; margin: 0;">Buku Diterbitkan</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0; color: #0369a1;">{{ $author->books_count ?? 0 }}</p>
                </div>
                <div style="background: #fce7f3; padding: 1rem; border-radius: 0.375rem;">
                    <p style="color: #be185d; font-size: 0.875rem; margin: 0;">Blog Post</p>
                    <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0; color: #be185d;">{{ $author->blog_posts_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 2. Blog Post Show View
**Path:** `resources/views/admin/blog-posts/show.blade.php`

```blade
@extends('admin.layout')

@section('title', $post->title)
@section('page-title', $post->title)

@section('content')
<div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
    <!-- Main Content -->
    <div>
        @if($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" style="width: 100%; border-radius: 0.375rem; margin-bottom: 2rem; max-height: 400px; object-fit: cover;">
        @endif
        
        <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
            <div style="white-space: pre-wrap; color: #374151; line-height: 1.8;">{{ $post->content }}</div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-primary" style="display: block; width: 100%; padding: 0.75rem; text-align: center; text-decoration: none; margin-bottom: 0.5rem;">✏️ Edit</a>
            <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-primary" style="display: block; width: 100%; padding: 0.75rem; text-align: center; background: #6b7280; text-decoration: none;">← Kembali</a>
        </div>

        <div class="card" style="padding: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Author</p>
            <p style="font-weight: 600; margin: 0.5rem 0 0 0;">{{ $post->author->name }}</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Kategori</p>
            <p style="font-weight: 600; margin: 0.5rem 0 0 0;">{{ $post->category->name ?? '-' }}</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Status</p>
            <p style="margin-top: 0.5rem; margin-bottom: 0;">
                @if($post->is_published)
                    <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Published</span>
                @else
                    <span style="background: #f3f4f6; color: #6b7280; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">Draft</span>
                @endif
            </p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Views</p>
            <p style="font-weight: 700; margin: 0.5rem 0 0 0;">{{ $post->view_count ?? 0 }}</p>
        </div>
    </div>
</div>
@endsection
```

### 3. Customer Detail View
**Path:** `resources/views/admin/customers/show.blade.php`

```blade
@extends('admin.layout')

@section('title', 'Profil Pelanggan: ' . $email)
@section('page-title', 'Profil Pelanggan: ' . $email)

@section('content')
<div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
    <!-- Orders -->
    <div class="card" style="padding: 2rem;">
        <h2>Riwayat Pesanan</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f3f4f6;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">No. Pesanan</th>
                    <th style="padding: 1rem; text-align: center;">Jumlah</th>
                    <th style="padding: 1rem; text-align: right;">Total</th>
                    <th style="padding: 1rem; text-align: center;">Status</th>
                    <th style="padding: 1rem; text-align: center;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem;"><a href="{{ route('admin.orders.show', $order) }}" style="color: #3b82f6; text-decoration: none;">{{ $order->order_number }}</a></td>
                        <td style="padding: 1rem; text-align: center;">{{ $order->items->count() }}</td>
                        <td style="padding: 1rem; text-align: right; font-weight: 600;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="background: #dbeafe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center; font-size: 0.875rem;">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div>
        <div class="card" style="padding: 1.5rem;">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Email</p>
            <p style="font-weight: 600; margin: 0.5rem 0 0 0; word-break: break-all;">{{ $email }}</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Total Pesanan</p>
            <p style="font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0;">{{ $orders->count() }}</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Total Belanja</p>
            <p style="font-size: 1.25rem; font-weight: 700; margin: 0.5rem 0 0 0;">Rp{{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</p>

            <a href="{{ route('admin.customers.index') }}" class="btn btn-primary" style="display: block; width: 100%; padding: 0.75rem; text-align: center; text-decoration: none; margin-top: 1.5rem;">← Kembali</a>
        </div>
    </div>
</div>
@endsection
```

### 4. Ebook Delivery Show View
**Path:** `resources/views/admin/ebook-deliveries/show.blade.php`

```blade
@extends('admin.layout')

@section('title', 'Detail Pengiriman Ebook')
@section('page-title', 'Detail Pengiriman Ebook')

@section('content')
<div class="card" style="padding: 2rem;">
    <h2>{{ $delivery->order_item->book->title }}</h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div>
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Email Penerima</p>
            <p style="font-weight: 600; margin: 0.5rem 0 0 0;">{{ $delivery->order_item->order->guest_email }}</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Nomor Pesanan</p>
            <p style="font-weight: 600; margin: 0.5rem 0 0 0;">{{ $delivery->order_item->order->order_number }}</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Status</p>
            <p style="margin-top: 0.5rem; margin-bottom: 0;">
                @if($delivery->isExpired())
                    <span style="background: #fee2e2; color: #7f1d1d; padding: 0.25rem 0.75rem; border-radius: 9999px;">Kadaluarsa</span>
                @else
                    <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px;">Aktif</span>
                @endif
            </p>
        </div>

        <div>
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">Total Download</p>
            <p style="font-weight: 700; margin: 0.5rem 0 0 0; font-size: 1.25rem;">{{ $delivery->download_count ?? 0 }} kali</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Kadaluarsa</p>
            <p style="font-weight: 600; margin: 0.5rem 0 0 0;">{{ $delivery->expired_at->format('d M Y H:i') }}</p>

            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 0;">Download Terakhir</p>
            <p style="font-weight: 600; margin: 0.5rem 0 0 0;">{{ $delivery->last_download_at?->format('d M Y H:i') ?? '-' }}</p>
        </div>
    </div>

    <h3>Download Logs</h3>
    <div style="background: #f9fafb; padding: 1rem; border-radius: 0.375rem; max-height: 300px; overflow-y: auto;">
        @if($logs)
            @foreach(json_decode($logs, true) as $log)
                <p style="margin: 0.5rem 0; font-size: 0.875rem; color: #6b7280;">
                    🕐 {{ $log['timestamp'] }} - {{ $log['ip'] }}
                </p>
            @endforeach
        @else
            <p style="margin: 0; color: #6b7280;">Belum ada download</p>
        @endif
    </div>

    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
        @if(!$delivery->isExpired())
            <form method="POST" action="{{ route('admin.ebook-deliveries.resend', $delivery) }}" style="flex: 1;">
                @csrf
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">📧 Kirim Ulang</button>
            </form>
        @endif
        <a href="{{ route('admin.ebook-deliveries.index') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Kembali</a>
    </div>
</div>
@endsection
```

### 5. Payment Show View
**Path:** `resources/views/admin/payments/show.blade.php`

```blade
@extends('admin.layout')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')

@section('content')
<div class="card" style="padding: 2rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div>
            <h2 style="margin: 0;">{{ $payment->transaction_id }}</h2>
            <p style="color: #6b7280; margin: 0.5rem 0;">{{ $payment->created_at->format('d M Y H:i') }}</p>
        </div>
        <div style="text-align: right;">
            <span style="background: @if($payment->status === 'success') #d1fae5 @elseif($payment->status === 'failed') #fee2e2 @else #fef3c7 @endif;
                         color: @if($payment->status === 'success') #065f46 @elseif($payment->status === 'failed') #7f1d1d @else #92400e @endif;
                         padding: 0.75rem 1.5rem; border-radius: 9999px; font-weight: 600;">
                @if($payment->status === 'success') ✓ Berhasil
                @elseif($payment->status === 'failed') ✗ Gagal
                @else ⏳ Pending
                @endif
            </span>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Pesanan</td>
            <td style="padding: 1rem;"><a href="{{ route('admin.orders.show', $payment->order) }}" style="color: #3b82f6; text-decoration: none;">{{ $payment->order->order_number }}</a></td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Email</td>
            <td style="padding: 1rem;">{{ $payment->order->guest_email }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Jumlah</td>
            <td style="padding: 1rem; font-weight: 600;">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #e5e7eb;">
            <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Metode</td>
            <td style="padding: 1rem;">{{ $payment->payment_method ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 1rem; color: #6b7280; font-weight: 600;">Deskripsi</td>
            <td style="padding: 1rem;">{{ $payment->description ?? '-' }}</td>
        </tr>
    </table>

    @if($payment->status === 'pending')
        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" style="margin-top: 2rem;">
            @csrf
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">✓ Verifikasi Pembayaran</button>
        </form>
    @elseif($payment->status === 'success')
        <form method="POST" action="{{ route('admin.payments.refund', $payment) }}" style="margin-top: 2rem;">
            @csrf
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; background: #ef4444;" onclick="return confirm('Lakukan pengembalian dana?')">💸 Refund</button>
        </form>
    @endif

    <a href="{{ route('admin.payments.index') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none; display: inline-block; margin-top: 1rem;">← Kembali</a>
</div>
@endsection
```

## How to Implement

1. **Copy the code** from each template above
2. **Create the file** at the specified path  
3. **Update route names** to match your routes/admin.php file
4. **Test the view** by navigating to the route in your browser

## Integration Checklist

- [ ] Create all 5 show views using templates above
- [ ] Add links to list views for show pages
- [ ] Test navigation between list → show → edit → list
- [ ] Verify all data displays correctly
- [ ] Test delete confirmation dialogs
- [ ] Confirm success/error messages display

---

## Quick Copy-Paste Command

To create all files at once, run in terminal:

```bash
# Create directories if not exist
mkdir -p resources/views/admin/authors
mkdir -p resources/views/admin/blog-posts
mkdir -p resources/views/admin/customers
mkdir -p resources/views/admin/ebook-deliveries
mkdir -p resources/views/admin/payments

# Then create each file using your editor
```

All templates are production-ready and follow the established admin UI patterns!
