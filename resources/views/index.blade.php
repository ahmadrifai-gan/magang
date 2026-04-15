@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <!-- Hero Section -->
    <div style="padding: 80px 20px; text-align: center;">
        <h1 style="font-size: 48px; font-weight: 700; margin-bottom: 20px;">
            <i class="fas fa-leaf"></i> Leave Management System
        </h1>
        <p style="font-size: 20px; margin-bottom: 40px; opacity: 0.95;">
            Sistem manajemen cuti karyawan yang modern dan efisien
        </p>

        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            @auth
                <a href="{{ route('dashboard') }}" class="btn" style="background: white; color: #667eea; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i> Ke Dashboard
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn" style="background: white; color: #667eea; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-decoration: none;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 12px 30px; border-radius: 8px; font-weight: 600; border: 2px solid white; text-decoration: none;">
                    <i class="fas fa-user-plus"></i> Daftar
                </a>
            @endauth
        </div>
    </div>

    <!-- Features Section -->
    <div style="background: white; color: #333; padding: 80px 20px;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <h2 style="text-align: center; font-size: 32px; margin-bottom: 50px; color: #667eea;">
                Fitur Utama
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <!-- Feature 1 -->
                <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 48px; margin-bottom: 15px; color: #667eea;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 style="margin: 0 0 15px 0; font-size: 20px;">
                        Keamanan Login
                    </h3>
                    <p style="margin: 0; color: #666;">
                        Login aman dengan token authentication dan password encryption
                    </p>
                </div>

                <!-- Feature 2 -->
                <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 48px; margin-bottom: 15px; color: #4ade80;">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <h3 style="margin: 0 0 15px 0; font-size: 20px;">
                        Role Management
                    </h3>
                    <p style="margin: 0; color: #666;">
                        Sistem role berbeda untuk Employee dan Admin yang lengkap
                    </p>
                </div>

                <!-- Feature 3 -->
                <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 48px; margin-bottom: 15px; color: #f59e0b;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 style="margin: 0 0 15px 0; font-size: 20px;">
                        Manajemen Cuti
                    </h3>
                    <p style="margin: 0; color: #666;">
                        Ajukan cuti, tracking status, dan manajemen kuota dengan mudah
                    </p>
                </div>

                <!-- Feature 4 -->
                <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 48px; margin-bottom: 15px; color: #06b6d4;">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <h3 style="margin: 0 0 15px 0; font-size: 20px;">
                        Upload Dokumen
                    </h3>
                    <p style="margin: 0; color: #666;">
                        Upload file pendukung (PDF, DOC, IMG) untuk setiap pengajuan
                    </p>
                </div>

                <!-- Feature 5 -->
                <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 48px; margin-bottom: 15px; color: #ec4899;">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h3 style="margin: 0 0 15px 0; font-size: 20px;">
                        Approval Workflow
                    </h3>
                    <p style="margin: 0; color: #666;">
                        Admin dapat approve atau reject pengajuan dengan catatan
                    </p>
                </div>

                <!-- Feature 6 -->
                <div style="background: #f8f9fa; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);">
                    <div style="font-size: 48px; margin-bottom: 15px; color: #8b5cf6;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 style="margin: 0 0 15px 0; font-size: 20px;">
                        Dashboard Statistik
                    </h3>
                    <p style="margin: 0; color: #666;">
                        Lihat ringkasan cuti dan kuota sisa dengan visualisasi data
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 80px 20px; text-align: center;">
        <h2 style="font-size: 32px; margin-bottom: 20px;">
            Siap untuk memulai?
        </h2>
        <p style="font-size: 18px; margin-bottom: 30px; opacity: 0.95;">
            Daftar akun baru atau login ke sistem kami sekarang
        </p>

        @auth
            <a href="{{ route('dashboard') }}" class="btn" style="background: white; color: #667eea; padding: 12px 40px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 16px;">
                <i class="fas fa-arrow-right"></i> Ke Dashboard
            </a>
        @else
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('login') }}" class="btn" style="background: white; color: #667eea; padding: 12px 40px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 16px;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 12px 40px; border-radius: 8px; font-weight: 600; border: 2px solid white; text-decoration: none; font-size: 16px;">
                    <i class="fas fa-user-plus"></i> Daftar Gratis
                </a>
            </div>
        @endauth
    </div>

    <!-- Footer -->
    <div style="background: #1f2937; color: white; padding: 30px 20px; text-align: center;">
        <p style="margin: 0;">
            <strong>Leave Management System</strong> © 2026 - Sistem Manajemen Cuti Karyawan
        </p>
        <p style="margin: 10px 0 0 0; font-size: 13px; opacity: 0.8;">
            Backend built with Laravel • API with Sanctum Authentication
        </p>
    </div>
</div>
@endsection
