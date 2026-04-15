<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Leave Management System') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary-color: #0d6efd;
                --secondary-color: #6c757d;
                --success-color: #198754;
                --danger-color: #dc3545;
                --warning-color: #ffc107;
                --info-color: #0dcaf0;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .app-container {
                width: 100%;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .content {
                flex: 1;
            }

            .card-auth {
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                border: none;
                border-radius: 15px;
                overflow: hidden;
            }

            .card-header-auth {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 30px 20px;
                text-align: center;
                border: none;
            }

            .card-header-auth h2 {
                margin: 0;
                font-weight: 700;
                font-size: 28px;
                margin-bottom: 5px;
            }

            .card-header-auth p {
                margin: 0;
                opacity: 0.9;
                font-size: 14px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                display: block;
            }

            .form-control {
                border: 2px solid #e0e0e0;
                border-radius: 8px;
                padding: 12px 15px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
            }

            .form-control.is-invalid {
                border-color: var(--danger-color);
            }

            .invalid-feedback {
                display: block;
                color: var(--danger-color);
                font-size: 13px;
                margin-top: 5px;
            }

            .btn-login {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                color: white;
                font-weight: 600;
                padding: 12px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
                color: white;
            }

            .btn-login:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            .btn-register {
                background: white;
                border: 2px solid var(--primary-color);
                color: var(--primary-color);
                font-weight: 600;
                padding: 12px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .btn-register:hover {
                background: var(--primary-color);
                color: white;
            }

            .form-check-label {
                font-size: 14px;
                color: #555;
            }

            .form-check-input {
                border-radius: 4px;
                cursor: pointer;
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .auth-footer {
                text-align: center;
                padding-top: 20px;
                border-top: 1px solid #e0e0e0;
                margin-top: 20px;
            }

            .auth-footer p {
                margin: 0;
                color: #666;
                font-size: 14px;
            }

            .auth-footer a {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .auth-footer a:hover {
                color: #764ba2;
                text-decoration: underline;
            }

            .alert {
                border-radius: 8px;
                border: none;
                margin-bottom: 20px;
            }

            .alert-danger {
                background-color: #f8d7da;
                color: #721c24;
                padding: 15px;
            }

            .alert-success {
                background-color: #d4edda;
                color: #155724;
                padding: 15px;
            }

            .input-group-icon {
                position: relative;
            }

            .input-group-icon .form-control {
                padding-left: 40px;
            }

            .input-group-icon .input-icon {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #999;
                font-size: 16px;
                pointer-events: none;
            }

            .spinner-border-sm {
                width: 1rem;
                height: 1rem;
                border-width: 0.2em;
                margin-right: 8px;
            }

            .loading-text {
                display: none;
            }

            .loading-text.show {
                display: inline-block;
            }

            @media (max-width: 576px) {
                .card-header-auth h2 {
                    font-size: 24px;
                }

                .card {
                    margin: 20px;
                }
            }

            /* Toast Notification */
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
            }

            .custom-toast {
                background: white;
                border-left: 4px solid var(--danger-color);
                border-radius: 8px;
                padding: 15px 20px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 10px;
                animation: slideIn 0.3s ease;
            }

            .custom-toast.success {
                border-left-color: var(--success-color);
            }

            .custom-toast.error {
                border-left-color: var(--danger-color);
            }

            .custom-toast.warning {
                border-left-color: var(--warning-color);
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
        </style>

        @yield('extra-css')
    </head>

    <body>
        <div class="app-container">
            <div class="content">
                @yield('content')
            </div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Global function untuk menampilkan toast
            function showToast(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `custom-toast ${type}`;
                
                const icon = {
                    success: '<i class="fas fa-check-circle"></i>',
                    error: '<i class="fas fa-exclamation-circle"></i>',
                    warning: '<i class="fas fa-warning"></i>',
                    info: '<i class="fas fa-info-circle"></i>'
                };

                toast.innerHTML = `
                    ${icon[type]}
                    <span>${message}</span>
                `;

                container.appendChild(toast);

                setTimeout(() => {
                    toast.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            }

            // Handle form submission dengan loading state
            document.querySelectorAll('form[data-auto-loading]').forEach(form => {
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = true;
                        const loadingText = btn.querySelector('.loading-text');
                        if (loadingText) {
                            loadingText.classList.add('show');
                        }
                    }
                });
            });
        </script>

        @yield('extra-js')
    </body>
</html>
