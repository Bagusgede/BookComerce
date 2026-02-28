<x-guest-layout>
    <div class="logo-container">
        <div class="logo">
            <i class="bi bi-key"></i>
        </div>
        <h1 class="title">Lupa Kata Sandi?</h1>
        <p class="subtitle">Tidak apa-apa! Masukkan email Anda dan kami akan mengirimkan tautan atur ulang kata sandi</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" class="form-control" type="email" name="email" required autofocus
                autocomplete="username" placeholder="nama@contoh.com" value="{{ old('email') }}" />
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-login">
            Kirim Tautan Atur Ulang Kata Sandi
        </button>

        <!-- Back to Login -->
        <div class="signup-link">
            Ingat kata sandi Anda? <a href="{{ route('login') }}">Kembali ke Halaman Masuk</a>
        </div>
    </form>
</x-guest-layout>
