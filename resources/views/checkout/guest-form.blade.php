@extends('layouts.app')

@section('title', 'Data Diri - Checkout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-2">Informasi Pengiriman</h1>
            <p class="text-gray-600 mb-8">Silakan isi data diri Anda untuk melanjutkan checkout</p>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-lg">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('checkout.process-guest') }}" method="POST" class="bg-white rounded-lg shadow-md p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('email') border-red-500 @enderror"
                            placeholder="contoh@email.com" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('name') border-red-500 @enderror"
                                placeholder="Nama lengkap Anda" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No. HP -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            No. WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('phone') border-red-500 @enderror"
                            placeholder="081234567890" required>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('address') border-red-500 @enderror"
                            placeholder="Jalan, No. Rumah, Blok, Kompleks, dll" required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kota -->
                    <div>
                        <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kota / Kabupaten <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('city') border-red-500 @enderror"
                            placeholder="Jakarta" required>
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Pos <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('postal_code') border-red-500 @enderror"
                            placeholder="12345" required>
                        @error('postal_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Syarat & Ketentuan -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <label class="flex items-center">
                        <input type="checkbox" name="agree" value="1" required class="w-4 h-4 text-green-600 rounded">
                        <span class="ml-3 text-sm text-gray-700">
                            Saya setuju dengan <a href="{{ route('privacy') }}" target="_blank" rel="noopener"
                                class="text-green-600 hover:underline">Kebijakan Privasi</a>
                        </span>
                    </label>
                    @error('agree')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <a href="{{ route('checkout.index') }}"
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-center font-semibold">
                        ← Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                        Lanjut ke Pembayaran →
                    </button>
                </div>

                <!-- Info -->
                <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>ℹ️ Catatan:</strong> Data Anda aman dan hanya digunakan untuk pengiriman pesanan serta
                        komunikasi terkait pesanan.
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection
