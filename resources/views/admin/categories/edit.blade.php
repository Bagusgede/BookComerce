@extends('admin.layout')

@section('title', 'Edit Kategori: ' . $category->name)
@section('page-title', 'Edit Kategori: ' . $category->name)

@section('content')
    <div class="card" style="padding: 2rem; max-width: 600px;">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                    Nama Kategori <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                @error('name')
                    <p style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Deskripsi</label>
                <textarea name="description" rows="4" placeholder="Deskripsi kategori"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ old('description', $category->description) }}</textarea>
            </div>

            <!-- Status -->
            <div style="margin-bottom: 2rem;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))
                        style="width: 1rem; height: 1rem; margin-right: 0.5rem;">
                    <span>Aktif</span>
                </label>
            </div>

            <!-- Info -->
            <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.375rem; margin-bottom: 2rem;">
                <p style="margin: 0; font-size: 0.875rem; color: #6b7280;">
                    Dibuat: {{ $category->created_at->format('d M Y H:i') }} |
                    Diupdate: {{ $category->updated_at->format('d M Y H:i') }}
                </p>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">💾 Simpan Perubahan</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-primary"
                    style="padding: 0.75rem 2rem; background: #6b7280; text-decoration: none;">← Batal</a>
            </div>
        </form>
    </div>
@endsection
