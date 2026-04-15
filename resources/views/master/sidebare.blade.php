<aside class="sidebar bg-light">
    <div class="sidebar-header py-4 px-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="avatar me-3" style="width: 40px; height: 40px;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&bold=true" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 100%; height: 100%;">
            </div>
            <div class="flex-grow-1 min-width-0">
                <p class="mb-0 fw-bold text-truncate">{{ Auth::user()->name }}</p>
                <small class="text-muted text-truncate d-block">
                    <i class="fas fa-user-tag"></i>
                    @if(Auth::user()->isAdmin())
                        <span class="badge bg-danger">Admin</span>
                    @else
                        <span class="badge bg-primary">Employee</span>
                    @endif
                </small>
            </div>
        </div>
    </div>

    <nav class="sidebar-menu p-3">
        <!-- EMPLOYEE MENU -->
        @if(Auth::user()->isEmployee())
            <div class="menu-section mb-4">
                <h6 class="text-uppercase text-muted small fw-bold px-2 mb-3">Menu Utama</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}" class="menu-item {{ Route::current()->getName() === 'dashboard' ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('employee.my-requests') }}" class="menu-item {{ Route::current()->getName() === 'employee.my-requests' ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Pengajuan Saya</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('employee.create-request') }}" class="menu-item {{ Route::current()->getName() === 'employee.create-request' ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span>Ajukan Cuti</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <!-- ADMIN MENU -->
        @if(Auth::user()->isAdmin())
            <div class="menu-section mb-4">
                <h6 class="text-uppercase text-muted small fw-bold px-2 mb-3">Dashboard Admin</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}" class="menu-item {{ Route::current()->getName() === 'dashboard' ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.pending-approvals') }}" class="menu-item {{ Route::current()->getName() === 'admin.pending-approvals' ? 'active' : '' }}">
                            <i class="fas fa-hourglass-end"></i>
                            <span>Persetujuan Pending</span>
                            <span class="badge bg-danger rounded-pill ms-auto" id="pending-badge" style="display: none;"></span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.all-requests') }}" class="menu-item {{ Route::current()->getName() === 'admin.all-requests' ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Semua Pengajuan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="menu-section mb-4">
                <h6 class="text-uppercase text-muted small fw-bold px-2 mb-3">Manajemen</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="menu-item" data-action="load-employees">
                            <i class="fas fa-users"></i>
                            <span>Daftar Karyawan</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="menu-item" data-action="load-reports">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <!-- COMMON MENU -->
        <div class="menu-section">
            <h6 class="text-uppercase text-muted small fw-bold px-2 mb-3">Lainnya</h6>
            <ul class="list-unstyled">
                <li class="mb-2">
                    <a href="#" class="menu-item" title="Coming Soon">
                        <i class="fas fa-question-circle"></i>
                        <span>Bantuan</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<style>
    .sidebar {
        width: 280px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 56px;
        overflow-y: auto;
        border-right: 1px solid #e0e0e0;
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%) !important;
    }

    .sidebar-header {
        background: white;
    }

    .sidebar-menu {
        padding-top: 1.5rem !important;
    }

    .menu-section h6 {
        letter-spacing: 0.5px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #555;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
    }

    .menu-item i {
        width: 24px;
        margin-right: 12px;
        color: #667eea;
    }

    .menu-item span:first-of-type {
        flex-grow: 1;
    }

    .menu-item:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        padding-left: 20px;
    }

    .menu-item.active {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .menu-item.active i {
        color: white;
    }

    .badge {
        font-size: 0.65rem;
        padding: 0.35rem 0.6rem;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #bbb;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 240px;
            z-index: 1040;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .menu-item span:first-of-type {
            white-space: nowrap;
        }
    }
</style>
