@extends('admin.layout')

@section('title', 'Edit Penulis: ' . $author->name)
@section('page-title', 'Edit Penulis: ' . $author->name)

@section('content')
    <div class="card" style="padding: 2rem; max-width: 800px;">
        <form method="POST" action="{{ route('admin.authors.update', $author) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    Nama <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $author->name) }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('name')
                    <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
                <input type="email" name="email" value="{{ old('email', $author->email) }}"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>

            <!-- Bio -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Biografi</label>
                <textarea name="bio" rows="4" placeholder="Deskripsi singkat tentang penulis"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ old('bio', $author->bio) }}</textarea>
            </div>

            <!-- Photo -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Foto</label>
                @if ($author->photo)
                    <div style="margin-bottom: 0.5rem;">
                        <img src="{{ Storage::url($author->photo) }}" alt="{{ $author->name }}"
                            style="height: 150px; border-radius: 0.375rem;">
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>

            <!-- Social Media -->
            <fieldset style="padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; margin-bottom: 1.5rem;">
                <legend style="font-weight: 600; padding: 0 0.5rem;">Media Sosial (Opsional)</legend>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">🐦 Twitter</label>
                    <input type="url" name="social_media[twitter]" placeholder="https://twitter.com/..."
                        value="{{ old('social_media.twitter', $author->social_media['twitter'] ?? '') }}"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">📸 Instagram</label>
                    <input type="url" name="social_media[instagram]" placeholder="https://instagram.com/..."
                        value="{{ old('social_media.instagram', $author->social_media['instagram'] ?? '') }}"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">👥 Facebook</label>
                    <input type="url" name="social_media[facebook]" placeholder="https://facebook.com/..."
                        value="{{ old('social_media.facebook', $author->social_media['facebook'] ?? '') }}"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">🎵 TikTok</label>
                    <input type="url" name="social_media[tiktok]" placeholder="https://tiktok.com/@..."
                        value="{{ old('social_media.tiktok', $author->social_media['tiktok'] ?? '') }}"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">🌐 Website</label>
                    <input type="url" name="social_media[website]" placeholder="https://example.com"
                        value="{{ old('social_media.website', $author->social_media['website'] ?? '') }}"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>
            </fieldset>

            <!-- Status -->
            <div style="margin-bottom: 2rem;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $author->is_active))
                        style="width: 1rem; height: 1rem; margin-right: 0.5rem;">
                    <span>Aktif</span>
                </label>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">💾 Simpan Perubahan</button>
                <a href="{{ route('admin.authors.index') }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Batal</a>
            </div>
        </form>
    </div>
@endsection
