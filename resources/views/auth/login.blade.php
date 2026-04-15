@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card card-auth">
                <div class="card-header-auth">
                    <h2>Login</h2>
                    <p>Masuk ke akun Anda</p>
                </div>

                <div class="card-body" style="padding: 40px;">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div style="margin-bottom: 10px;">
                                <strong><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan</strong>
                            </div>
                            @foreach ($errors->all() as $error)
                                <div style="font-size: 14px;">{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('login.submit') }}" method="POST" data-auto-loading>
                        @csrf

                        <!-- Email Input -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <div class="input-group-icon">
                                <i class="fas fa-envelope input-icon"></i>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    placeholder="contoh@email.com"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                >
                            </div>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <div class="input-group-icon">
                                <i class="fas fa-lock input-icon"></i>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Masukkan password"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="btn btn-link" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #999; cursor: pointer;">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check" style="margin-bottom: 20px;">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="remember" 
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn btn-login w-100" id="loginBtn">
                            <span class="button-text">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </span>
                            <span class="loading-text">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Sedang Login...
                            </span>
                        </button>

                        <!-- Divider -->
                        <div style="text-align: center; margin: 20px 0; color: #ccc;">
                            <small>atau</small>
                        </div>

                        <!-- Demo Credentials Info -->
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <small style="color: #666;">
                                <strong>Demo Account:</strong><br>
                                📧 <code>employee1@example.com</code><br>
                                🔑 <code>password</code>
                            </small>
                        </div>

                        <!-- Footer -->
                        <div class="auth-footer">
                            <p>
                                Belum punya akun? 
                                <a href="{{ route('register') }}">Daftar di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Info -->
            <div style="text-align: center; margin-top: 30px; color: white;">
                <p style="margin: 10px 0; font-size: 13px;">
                    <i class="fas fa-info-circle"></i> 
                    Leave Management System API
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email || !password) {
            e.preventDefault();
            showToast('Harap isi semua field yang diperlukan', 'warning');
            return false;
        }

        if (!isValidEmail(email)) {
            e.preventDefault();
            showToast('Format email tidak valid', 'warning');
            return false;
        }
    });

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Auto-fill demo credentials
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user has been to this page before
        const demoInfo = document.querySelector('[style*="Demo Account"]');
        if (demoInfo) {
            demoInfo.style.cursor = 'pointer';
            demoInfo.addEventListener('click', function() {
                document.getElementById('email').value = 'employee1@example.com';
                document.getElementById('password').value = 'password';
                showToast('Demo credentials filled. Click Login to proceed.', 'info');
            });
        }
    });
</script>
@endsection
