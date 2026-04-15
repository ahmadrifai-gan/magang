<footer class="bg-dark text-white mt-5 py-5 border-top">
    <div class="container-fluid">
        <div class="row mb-4">
            <!-- Company Info -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-calendar-check" style="color: #667eea;"></i>
                    Leave Management System
                </h5>
                <p class="text-muted small">
                    Sistem manajemen cuti terintegrasi untuk mengelola pengajuan cuti karyawan dengan efisien.
                </p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="text-muted" title="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-muted" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-muted" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h6 class="fw-bold mb-3 text-uppercase">Navigasi</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none small hover-link">
                            Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none small hover-link">
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none small hover-link">
                            Bantuan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h6 class="fw-bold mb-3 text-uppercase">Dukungan</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <a href="mailto:support@leave.system" class="text-muted text-decoration-none small hover-link">
                            support@leave.system
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <span class="text-muted small">+62 (0) 123-4567-890</span>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        <span class="text-muted small">Jl. Contoh No. 123, Jakarta</span>
                    </li>
                </ul>
            </div>

            <!-- Stats -->
            <div class="col-md-3">
                <h6 class="fw-bold mb-3 text-uppercase">Statistik</h6>
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="bg-dark-light p-3 rounded" style="background-color: rgba(102, 126, 234, 0.1) !important;">
                            <p class="text-muted small mb-1">Total Pengajuan</p>
                            <h6 class="fw-bold text-primary mb-0" id="total-requests">-</h6>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="bg-dark-light p-3 rounded" style="background-color: rgba(102, 126, 234, 0.1) !important;">
                            <p class="text-muted small mb-1">Disetujui</p>
                            <h6 class="fw-bold text-success mb-0" id="total-approved">-</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr style="border-color: rgba(255, 255, 255, 0.1);">

        <!-- Copyright -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-muted small mb-0">
                    &copy; {{ date('Y') }} Leave Management System. Semua hak dilindungi.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-muted text-decoration-none small me-3 hover-link">Kebijakan Privasi</a>
                <a href="#" class="text-muted text-decoration-none small hover-link">Syarat Layanan</a>
            </div>
        </div>
    </div>
</footer>

<style>
    footer {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
        margin-top: auto;
    }

    .hover-link {
        transition: color 0.3s ease;
    }

    .hover-link:hover {
        color: #667eea !important;
    }

    .bg-dark-light {
        background-color: rgba(255, 255, 255, 0.05);
    }

    footer a {
        color: inherit;
    }

    footer a:hover {
        color: #667eea;
    }

    .text-primary {
        color: #667eea !important;
    }

    .text-success {
        color: #4ade80 !important;
    }
</style>

<script>
    // Load footer statistics when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadFooterStats();
    });

    function loadFooterStats() {
        // This will be populated by the dashboard or main layout
        // For now, we'll load basic stats
        const token = localStorage.getItem('api_token');
        
        if (token) {
            fetch('/api/leave-requests', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const requests = data.data || [];
                const approved = requests.filter(r => r.status === 'approved').length;
                
                document.getElementById('total-requests').textContent = requests.length;
                document.getElementById('total-approved').textContent = approved;
            })
            .catch(error => console.log('Could not load footer stats:', error));
        }
    }
</script>
