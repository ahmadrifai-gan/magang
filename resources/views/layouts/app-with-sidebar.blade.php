<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Leave Management System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
        }

        main {
            margin-left: 280px;
            margin-top: 56px;
            flex: 1;
            background-color: #f8f9fa;
            padding: 30px;
        }

        footer {
            margin-left: 0;
        }

        /* Loading State */
        .loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .loading-spinner.show {
            display: block;
        }

        .spinner-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 9998;
        }

        .spinner-overlay.show {
            display: block;
        }

        /* Content Animation */
        .content-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            main {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                top: 56px;
                left: 0;
                z-index: 1030;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1050;
        }

        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 300px;
        }

        .toast.success {
            border-left: 4px solid #4ade80;
        }

        .toast.error {
            border-left: 4px solid #ef4444;
        }

        .toast.info {
            border-left: 4px solid #667eea;
        }

        .toast.warning {
            border-left: 4px solid #f59e0b;
        }

        @yield('extra-css')
    </style>

    @yield('extra-css')
</head>
<body>
    <!-- Navbar -->
    @include('master.navbar')

    <!-- Main Container -->
    <div style="display: flex; flex: 1;">
        <!-- Sidebar -->
        @auth
            @include('master.sidebare')
        @endauth

        <!-- Main Content -->
        <main role="main" style="flex: 1; width: 100%;">
            @yield('content')
        </main>
    </div>

    <!-- Loading Spinner -->
    <div class="spinner-overlay" id="spinner-overlay"></div>
    <div class="loading-spinner" id="loading-spinner">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-3">Memuat...</p>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Footer -->
    @include('master.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (Optional but recommended) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Global JavaScript -->
    <script>
        // Get API token from localStorage
        const apiToken = localStorage.getItem('api_token');

        // Show/hide loading spinner
        function showLoading() {
            document.getElementById('loading-spinner').classList.add('show');
            document.getElementById('spinner-overlay').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loading-spinner').classList.remove('show');
            document.getElementById('spinner-overlay').classList.remove('show');
        }

        // Toggle sidebar on mobile
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Show toast notification
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type} p-3 mb-2`;
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <div>
                        ${getToastIcon(type)}
                        ${message}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            container.appendChild(toast);

            // Auto-remove after 4 seconds
            setTimeout(() => {
                toast.remove();
            }, 4000);
        }

        function getToastIcon(type) {
            const icons = {
                success: '<i class="fas fa-check-circle text-success me-2"></i>',
                error: '<i class="fas fa-exclamation-circle text-danger me-2"></i>',
                info: '<i class="fas fa-info-circle text-info me-2"></i>',
                warning: '<i class="fas fa-warning text-warning me-2"></i>'
            };
            return icons[type] || icons.info;
        }

        // API Request Helper
        async function apiRequest(url, options = {}) {
            const headers = {
                'Accept': 'application/json',
                ...options.headers
            };

            if (apiToken) {
                headers['Authorization'] = `Bearer ${apiToken}`;
            }

            const response = await fetch(url, {
                ...options,
                headers
            });

            if (!response.ok) {
                if (response.status === 401) {
                    // Token expired or invalid
                    localStorage.removeItem('api_token');
                    window.location.href = '/login';
                }
                throw new Error(`API Error: ${response.status}`);
            }

            return await response.json();
        }

        // Load menu items based on action
        function loadMenuAction(action) {
            showLoading();
            
            // Route actions to appropriate handlers
            const handlers = {
                'load-requests': loadUserRequests,
                'load-form': showLeaveForm,
                'load-balance': loadLeaveBalance,
                'load-pending': loadPendingRequests,
                'load-all-requests': loadAllRequests,
                'load-employees': loadEmployees,
                'load-reports': loadReports
            };

            const handler = handlers[action];
            if (handler) {
                handler();
            } else {
                hideLoading();
                showToast('Halaman tidak ditemukan', 'error');
            }
        }

        // Attach menu handlers
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-action]').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadMenuAction(this.getAttribute('data-action'));
                });
            });
        });

        // Placeholder handlers (to be implemented)
        function loadUserRequests() {
            hideLoading();
            showToast('Feature coming soon', 'info');
        }

        function showLeaveForm() {
            hideLoading();
            showToast('Feature coming soon', 'info');
        }

        function loadLeaveBalance() {
            hideLoading();
            showToast('Feature coming soon', 'info');
        }

        function loadPendingRequests() {
            hideLoading();
            showToast('Feature coming soon', 'info');
        }

        function loadAllRequests() {
            hideLoading();
            showToast('Feature coming soon', 'info');
        }

        function loadEmployees() {
            hideLoading();
            showToast('Feature coming soon', 'info');
        }

        function loadReports() {
            hideLoading();
            showToast('Feature coming soon', 'info');
        }

        @yield('extra-js')
    </script>
</body>
</html>
