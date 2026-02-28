<x-guest-layout>
    <div class="logo-container">
        <div class="logo">
            <i class="bi bi-book-half"></i>
        </div>
        <h1 class="title">Selamat Datang</h1>
        <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="success-message">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
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

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input id="password" class="form-control" type="password" name="password" required
                autocomplete="current-password" placeholder="••••••••" />
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="remember-forgot">
            <label for="remember_me" class="remember-me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn-login">
            Masuk
        </button>

        <!-- Register Link -->
        <div class="signup-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </form>
</x-guest-layout>
