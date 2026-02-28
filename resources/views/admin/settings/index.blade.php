@extends('admin.layout')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- Settings Form -->
        <div class="card" style="padding: 2rem;">
            <h3 style="margin-top: 0;">Konfigurasi Umum</h3>
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <!-- Support Email -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email Dukungan</label>
                    <input type="email" name="support_email"
                        value="{{ old('support_email', config('app.support_email', '')) }}"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <!-- Support Phone -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nomor Telepon Dukungan</label>
                    <input type="tel" name="support_phone"
                        value="{{ old('support_phone', config('app.support_phone', '')) }}"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <!-- Ebook Expiry Days -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                        Masa Berlaku Link Download Ebook <span style="font-size: 0.875rem; color: #6b7280;">(hari)</span>
                    </label>
                    <input type="number" name="ebook_expiry_days" value="{{ old('ebook_expiry_days', 7) }}"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <!-- Download Rate Limit -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                        Batas Download Per Jam
                    </label>
                    <input type="number" name="download_rate_limit" value="{{ old('download_rate_limit', 3) }}"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; cursor: pointer;">💾
                    Simpan Pengaturan</button>
            </form>
        </div>

        <!-- Admin Actions -->
        <div>
            <!-- Cache & Performance -->
            <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
                <h3 style="margin-top: 0;">Performa</h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Bersihkan cache untuk memastikan data
                    terbaru</p>

                <form method="POST" action="{{ route('admin.settings.clear-cache') }}" style="margin-bottom: 1rem;">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; padding: 0.75rem; background: #f59e0b; cursor: pointer;">🧹 Bersihkan
                        Cache</button>
                </form>

                <form method="POST" action="{{ route('admin.settings.migrate') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; padding: 0.75rem; background: #8b5cf6;"
                        onclick="return confirm('Jalankan migrasi database?')">🔄 Migrasi Database</button>
                </form>
            </div>

            <!-- Backup & Export -->
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-top: 0;">Backup</h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Buat backup database secara manual</p>

                <form method="POST" action="{{ route('admin.settings.backup') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; padding: 0.75rem; background: #10b981; cursor: pointer;">💾 Download
                        Backup</button>
                </form>
            </div>

            <!-- Logs -->
            <div class="card" style="padding: 2rem; margin-top: 2rem;">
                <h3 style="margin-top: 0;">Aktivitas Sistem</h3>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Last 10 activities</p>

                <div
                    style="background: #f9fafb; padding: 1rem; border-radius: 0.375rem; font-family: monospace; font-size: 0.75rem; max-height: 300px; overflow-y: auto;">
                    <p style="margin: 0; color: #6b7280;">Log entries akan ditampilkan di sini</p>
                </div>

                <a href="{{ route('admin.settings.logs') }}" class="btn btn-primary"
                    style="display: block; width: 100%; padding: 0.75rem; text-align: center; text-decoration: none; background: #3b82f6; margin-top: 1rem;">📋
                    Lihat Semua Log</a>
            </div>
        </div>
    </div>
@endsection
