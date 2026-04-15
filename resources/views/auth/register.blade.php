@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card card-auth">
                <div class="card-header-auth">
                    <h2>Daftar</h2>
                    <p>Buat akun baru</p>
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

                    <form action="{{ route('register.submit') }}" method="POST" data-auto-loading>
                        @csrf

                        <!-- Name Input -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Nama Lengkap
                            </label>
                            <div class="input-group-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    placeholder="John Doe"
                                    value="{{ old('name') }}"
                                    required
                                >
                            </div>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

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

                        <!-- Role Selection -->
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i> Role
                            </label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>
                                    <i class="fas fa-briefcase"></i> Karyawan
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    <i class="fas fa-user-shield"></i> Administrator
                                </option>
                            </select>
                            @error('role')
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
                                    placeholder="Min. 8 karakter"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="btn btn-link" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #999; cursor: pointer;">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small style="color: #999;">Minimal 8 karakter, kombinasi huruf & angka</small>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i> Konfirmasi Password
                            </label>
                            <div class="input-group-icon">
                                <i class="fas fa-lock input-icon"></i>
                                <input 
                                    type="password" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="Masukkan ulang password"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="btn btn-link" id="togglePasswordConfirm" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; color: #999; cursor: pointer;">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Register Button -->
                        <button type="submit" class="btn btn-login w-100" style="margin-top: 10px;">
                            <span class="button-text">
                                <i class="fas fa-user-plus"></i> Daftar
                            </span>
                            <span class="loading-text">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Sedang Daftar...
                            </span>
                        </button>

                        <!-- Footer -->
                        <div class="auth-footer">
                            <p>
                                Sudah punya akun? 
                                <a href="{{ route('login') }}">Login di sini</a>
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

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirmation');
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
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const role = document.getElementById('role').value;
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;

        if (!name || !email || !role || !password || !passwordConfirm) {
            e.preventDefault();
            showToast('Harap isi semua field yang diperlukan', 'warning');
            return false;
        }

        if (!isValidEmail(email)) {
            e.preventDefault();
            showToast('Format email tidak valid', 'warning');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            showToast('Password minimal 8 karakter', 'warning');
            return false;
        }

        if (password !== passwordConfirm) {
            e.preventDefault();
            showToast('Password dan konfirmasi tidak cocok', 'warning');
            return false;
        }
    });

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
</script>
@endsection
