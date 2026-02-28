<x-guest-layout>
    <div class="logo-container">
        <div class="logo">
            <i class="bi bi-person-plus"></i>
        </div>
        <h1 class="title">Buat Akun Baru</h1>
        <p class="subtitle">Daftar untuk memulai berkontribusi</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" class="form-control" type="text" name="name" required autofocus
                autocomplete="name" placeholder="Nama anda" value="{{ old('name') }}" />
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" class="form-control" type="email" name="email" required autocomplete="username"
                placeholder="nama@contoh.com" value="{{ old('email') }}" />
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input id="password" class="form-control" type="password" name="password" required
                autocomplete="new-password" placeholder="••••••••" />
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="••••••••" />
            @error('password_confirmation')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn-login">
            Daftar
        </button>

        <!-- Login Link -->
        <div class="signup-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>
