@extends('layouts.app-with-sidebar')

@section('title', 'Dashboard - Leave Management System')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section mb-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold mb-2">
                    <i class="fas fa-wave-hand" style="color: #667eea;"></i>
                    Selamat Datang, {{ Auth::user()->name }}!
                </h1>
                <p class="text-muted">
                    @if(Auth::user()->isAdmin())
                        Dashboard Admin - Kelola pengajuan cuti karyawan
                    @else
                        Dashboard Karyawan - Kelola cuti Anda dengan mudah
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <p class="text-muted small mb-0">{{ now()->format('l, d F Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- ============ EMPLOYEE DASHBOARD ============ -->
    @if(Auth::user()->isEmployee())
        <!-- Quick Stats -->
        <div class="row mb-4" id="employee-stats">
            <div class="col-md-4 mb-3">
                <div class="stat-card stat-card-primary">
                    <div class="stat-header">
                        <i class="fas fa-calendar-day"></i>
                        <span class="stat-label">Sisa Cuti Tahun Ini</span>
                    </div>
                    <div class="stat-value" id="leave-balance-display">-</div>
                    <div class="stat-footer">Dari total 12 hari kerja</div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="stat-card stat-card-warning">
                    <div class="stat-header">
                        <i class="fas fa-hourglass-end"></i>
                        <span class="stat-label">Pengajuan Pending</span>
                    </div>
                    <div class="stat-value" id="pending-count">-</div>
                    <div class="stat-footer">Menunggu persetujuan</div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="stat-card stat-card-success">
                    <div class="stat-header">
                        <i class="fas fa-check-circle"></i>
                        <span class="stat-label">Disetujui</span>
                    </div>
                    <div class="stat-value" id="approved-count">-</div>
                    <div class="stat-footer">Pengajuan tersetujui</div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="row mb-4">
            <!-- Leave Request Form -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle text-primary"></i> Ajukan Cuti Baru
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="leave-request-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Alasan Cuti</label>
                                <textarea class="form-control form-control-lg" id="reason" name="reason" rows="3" placeholder="Jelaskan alasan pengajuan cuti Anda..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="attachment" class="form-label">Lampiran (Opsional)</label>
                                <input type="file" class="form-control form-control-lg" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> Ajukan Cuti
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Leave Balance Info -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-info"></i> Informasi Cuti
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <p class="text-muted small mb-2">Total Cuti Tersedia</p>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" id="balance-progress" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    <span id="balance-percentage">0%</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <p class="text-muted small mb-1">Tersedia</p>
                                <h6 class="fw-bold text-success" id="balance-available">0 hari</h6>
                            </div>
                            <div class="col-6">
                                <p class="text-muted small mb-1">Terpakai</p>
                                <h6 class="fw-bold text-danger" id="balance-used">0 hari</h6>
                            </div>
                        </div>

                        <hr>

                        <div class="bg-light p-3 rounded">
                            <p class="text-muted small mb-2"><i class="fas fa-lightbulb"></i> Tips:</p>
                            <ul class="small text-muted mb-0">
                                <li>Ajukan cuti minimal 2 hari sebelumnya</li>
                                <li>Pastikan alasan cuti jelas dan terperinci</li>
                                <li>Lampiran dokumen melengkapi pengajuan</li>
                                <li>Admin akan merespon dalam 2x24 jam</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Leave Requests -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary"></i> Pengajuan Cuti Saya
                </h5>
                <div>
                    <button class="btn btn-sm btn-outline-primary" id="refresh-requests">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="requests-table-container" style="min-height: 300px;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <th>Periode</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="requests-tbody">
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-loading fa-spin"></i> Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- ============ ADMIN DASHBOARD ============ -->
    @if(Auth::user()->isAdmin())
        <!-- Quick Stats -->
        <div class="row mb-4" id="admin-stats">
            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-danger">
                    <div class="stat-header">
                        <i class="fas fa-hourglass-end"></i>
                        <span class="stat-label">Pending Approval</span>
                    </div>
                    <div class="stat-value" id="admin-pending-count">-</div>
                    <div class="stat-footer">Perlu tindakan segera</div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-success">
                    <div class="stat-header">
                        <i class="fas fa-check-circle"></i>
                        <span class="stat-label">Disetujui hari ini</span>
                    </div>
                    <div class="stat-value" id="admin-approved-today">-</div>
                    <div class="stat-footer">Tahun ini</div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-warning">
                    <div class="stat-header">
                        <i class="fas fa-times-circle"></i>
                        <span class="stat-label">Ditolak</span>
                    </div>
                    <div class="stat-value" id="admin-rejected-count">-</div>
                    <div class="stat-footer">Tahun ini</div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-info">
                    <div class="stat-header">
                        <i class="fas fa-users"></i>
                        <span class="stat-label">Total Karyawan</span>
                    </div>
                    <div class="stat-value" id="admin-total-employees">-</div>
                    <div class="stat-footer">Aktif</div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Table -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Pengajuan Menunggu Persetujuan
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="pending-table-container" style="min-height: 300px;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Karyawan</th>
                                <th>Periode</th>
                                <th>Alasan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pending-tbody">
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-loading fa-spin"></i> Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Leave Requests -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-history text-primary"></i> Riwayat Pengajuan Terbaru
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" id="recent-table-container" style="min-height: 300px;">
                            <table class="table table-hover mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Karyawan</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-loading fa-spin"></i> Memuat data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Chart -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie text-info"></i> Statistik Pengajuan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div id="admin-stats-chart">
                            <div class="text-center py-5">
                                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Grafik statistik</p>
                            </div>
                        </div>

                        <hr>

                        <div class="small">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Disetujui</span>
                                <span class="badge bg-success" id="stat-approved">0</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Pending</span>
                                <span class="badge bg-warning" id="stat-pending">0</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Ditolak</span>
                                <span class="badge bg-danger" id="stat-rejected">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Styles -->
<style>
    .dashboard-container {
        animation: fadeIn 0.5s ease-in-out;
    }

    .welcome-section {
        padding: 20px;
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 12px;
    }

    .stat-card {
        padding: 25px;
        border-radius: 12px;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card-success {
        background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
    }

    .stat-card-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    }

    .stat-card-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .stat-card-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .stat-header i {
        font-size: 1.5rem;
        opacity: 0.8;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stat-footer {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .card {
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .status-approved {
        background-color: rgba(74, 222, 128, 0.1);
        color: #22c55e;
    }

    .status-rejected {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .form-control-lg {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
        font-size: 1rem;
    }

    .form-control-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-lg {
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 500;
    }

    .progress {
        border-radius: 10px;
        background-color: #e0e0e0;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Scripts -->
<script>
    // Load dashboard data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardData();
        
        // Setup form submission for leave request
        if (document.getElementById('leave-request-form')) {
            document.getElementById('leave-request-form').addEventListener('submit', handleLeaveRequest);
        }

        // Setup refresh button
        const refreshBtn = document.getElementById('refresh-requests');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                loadDashboardData();
            });
        }
    });

    async function loadDashboardData() {
        @if(Auth::user()->isEmployee())
            await loadEmployeeDashboard();
        @else
            await loadAdminDashboard();
        @endif
    }

    async function loadEmployeeDashboard() {
        try {
            // Load leave balance
            const balanceResponse = await apiRequest('/api/leave-balance');
            if (balanceResponse.data) {
                const balance = balanceResponse.data;
                const available = Math.max(0, balance.total_days - balance.used_days);
                const percentage = ((available / balance.total_days) * 100).toFixed(0);

                document.getElementById('leave-balance-display').textContent = available + ' hari';
                document.getElementById('balance-available').textContent = available + ' hari';
                document.getElementById('balance-used').textContent = balance.used_days + ' hari';
                document.getElementById('balance-percentage').textContent = percentage + '%';
                document.getElementById('balance-progress').style.width = percentage + '%';
                document.getElementById('balance-progress').setAttribute('aria-valuenow', percentage);
            }

            // Load leave requests
            const requestsResponse = await apiRequest('/api/leave-requests');
            const requests = requestsResponse.data || [];

            const pending = requests.filter(r => r.status === 'pending').length;
            const approved = requests.filter(r => r.status === 'approved').length;

            document.getElementById('pending-count').textContent = pending;
            document.getElementById('approved-count').textContent = approved;

            // Load requests table
            const tbody = document.getElementById('requests-tbody');
            tbody.innerHTML = '';

            if (requests.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox"></i> Belum ada pengajuan cuti
                        </td>
                    </tr>
                `;
            } else {
                requests.forEach(request => {
                    const startDate = new Date(request.start_date);
                    const endDate = new Date(request.end_date);
                    const createdDate = new Date(request.created_at);
                    const daysCount = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

                    tbody.innerHTML += `
                        <tr>
                            <td class="small">${createdDate.toLocaleDateString('id-ID')}</td>
                            <td class="small">
                                ${startDate.toLocaleDateString('id-ID')} - ${endDate.toLocaleDateString('id-ID')}
                                <br>
                                <small class="text-muted">${daysCount} hari kerja</small>
                            </td>
                            <td class="small">${request.reason.substring(0, 30)}...</td>
                            <td>
                                <span class="status-badge status-${request.status}">
                                    ${request.status}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        } catch (error) {
            console.error('Error loading employee dashboard:', error);
            showToast('Gagal memuat data dashboard', 'error');
        }
    }

    async function loadAdminDashboard() {
        try {
            // Load all leave requests
            const response = await apiRequest('/api/leave-requests');
            const allRequests = response.data || [];

            const pending = allRequests.filter(r => r.status === 'pending');
            const approved = allRequests.filter(r => r.status === 'approved');
            const rejected = allRequests.filter(r => r.status === 'rejected');

            // Update stats
            document.getElementById('admin-pending-count').textContent = pending.length;
            document.getElementById('admin-approved-today').textContent = approved.length;
            document.getElementById('admin-rejected-count').textContent = rejected.length;
            document.getElementById('admin-total-employees').textContent = new Set(allRequests.map(r => r.user_id)).size || '-';

            // Update stat badges
            document.getElementById('stat-approved').textContent = approved.length;
            document.getElementById('stat-pending').textContent = pending.length;
            document.getElementById('stat-rejected').textContent = rejected.length;

            // Load pending table
            const pendingTbody = document.getElementById('pending-tbody');
            pendingTbody.innerHTML = '';

            if (pending.length === 0) {
                pendingTbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-check-circle" style="color: #4ade80;"></i>
                            <p class="mt-2">Tidak ada pengajuan yang pending</p>
                        </td>
                    </tr>
                `;
            } else {
                pending.forEach(request => {
                    const startDate = new Date(request.start_date);
                    const endDate = new Date(request.end_date);
                    const createdDate = new Date(request.created_at);
                    const daysCount = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

                    pendingTbody.innerHTML += `
                        <tr>
                            <td class="small fw-bold">${request.user?.name || 'N/A'}</td>
                            <td class="small">
                                ${startDate.toLocaleDateString('id-ID')} - ${endDate.toLocaleDateString('id-ID')}
                                <br>
                                <small class="text-muted">${daysCount} hari</small>
                            </td>
                            <td class="small">${request.reason.substring(0, 25)}...</td>
                            <td class="small text-muted">${createdDate.toLocaleDateString('id-ID')}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-success btn-approve" data-id="${request.id}" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-reject" data-id="${request.id}" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-view" data-id="${request.id}" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                // Add event listeners for buttons
                document.querySelectorAll('.btn-approve').forEach(btn => {
                    btn.addEventListener('click', function() {
                        approveRequest(this.getAttribute('data-id'));
                    });
                });

                document.querySelectorAll('.btn-reject').forEach(btn => {
                    btn.addEventListener('click', function() {
                        rejectRequest(this.getAttribute('data-id'));
                    });
                });
            }

            // Load recent requests table
            const recentTbody = document.getElementById('recent-tbody');
            recentTbody.innerHTML = '';
            const recentRequests = allRequests.slice(0, 10);

            if (allRequests.length === 0) {
                recentTbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-history"></i> Belum ada pengajuan
                        </td>
                    </tr>
                `;
            } else {
                recentRequests.forEach(request => {
                    const startDate = new Date(request.start_date);
                    const endDate = new Date(request.end_date);
                    const createdDate = new Date(request.created_at);

                    recentTbody.innerHTML += `
                        <tr>
                            <td>${request.user?.name || 'N/A'}</td>
                            <td>${startDate.toLocaleDateString('id-ID')} - ${endDate.toLocaleDateString('id-ID')}</td>
                            <td>
                                <span class="status-badge status-${request.status}">
                                    ${request.status}
                                </span>
                            </td>
                            <td class="text-muted">${createdDate.toLocaleDateString('id-ID')}</td>
                        </tr>
                    `;
                });
            }

        } catch (error) {
            console.error('Error loading admin dashboard:', error);
            showToast('Gagal memuat data dashboard', 'error');
        }
    }

    async function handleLeaveRequest(e) {
        e.preventDefault();
        showLoading();

        try {
            const formData = new FormData(this);
            
            const response = await fetch('/api/leave-requests', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + apiToken,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showToast('Pengajuan cuti berhasil dibuat!', 'success');
                this.reset();

                // Reload data
                setTimeout(() => {
                    loadDashboardData();
                }, 1000);
            } else {
                showToast(data.message || 'Gagal membuat pengajuan', 'error');
            }
        } catch (error) {
            console.error('Error creating leave request:', error);
            showToast('Terjadi kesalahan saat membuat pengajuan', 'error');
        } finally {
            hideLoading();
        }
    }

    async function approveRequest(requestId) {
        showLoading();
        try {
            const response = await fetch(`/api/leave-requests/${requestId}/approve`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + apiToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    notes: 'Disetujui oleh admin'
                })
            });

            const data = await response.json();

            if (response.ok) {
                showToast('Pengajuan cuti disetujui!', 'success');
                loadDashboardData();
            } else {
                showToast(data.message || 'Gagal menyetujui pengajuan', 'error');
            }
        } catch (error) {
            console.error('Error approving request:', error);
            showToast('Terjadi kesalahan', 'error');
        } finally {
            hideLoading();
        }
    }

    async function rejectRequest(requestId) {
        const reason = prompt('Alasan penolakan:');
        if (!reason) return;

        showLoading();
        try {
            const response = await fetch(`/api/leave-requests/${requestId}/reject`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + apiToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reason: reason
                })
            });

            const data = await response.json();

            if (response.ok) {
                showToast('Pengajuan cuti ditolak!', 'success');
                loadDashboardData();
            } else {
                showToast(data.message || 'Gagal menolak pengajuan', 'error');
            }
        } catch (error) {
            console.error('Error rejecting request:', error);
            showToast('Terjadi kesalahan', 'error');
        } finally {
            hideLoading();
        }
    }

    // Update pending badge periodically
    setInterval(function() {
        @if(Auth::user()->isAdmin())
            const pending = document.getElementById('admin-pending-count');
            if (pending) {
                const count = pending.textContent.trim();
                const badge = document.getElementById('pending-badge');
                if (badge && count > 0) {
                    badge.style.display = 'inline-block';
                    badge.textContent = count;
                }
            }
        @endif
    }, 5000);
</script>
@endsection
