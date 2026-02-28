<x-guest-layout>
    <div class="logo-container">
        <div class="logo">
            <i class="bi bi-shield-lock"></i>
        </div>
        <h1 class="title">Atur Ulang Kata Sandi</h1>
        <p class="subtitle">Masukkan kata sandi baru Anda di bawah ini</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token Reset Kata Sandi -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" class="form-control" type="email" name="email" required autofocus
                autocomplete="username" placeholder="nama@contoh.com" value="{{ old('email', $request->email) }}" />
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

        <!-- Tombol Reset -->
        <button type="submit" class="btn-login">
            Atur Ulang Kata Sandi
        </button>
    </form>
</x-guest-layout>
