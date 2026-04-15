/**
 * Leave Management System - Main JavaScript
 */

// Bootstrap initialization
import('./bootstrap');

// Document ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Leave Management System loaded');
});

// Global utility functions
window.showToast = function(message, type = 'info') {
    const types = {
        'success': { icon: 'check-circle', color: '#4ade80' },
        'error': { icon: 'exclamation-circle', color: '#ef4444' },
        'warning': { icon: 'warning', color: '#f59e0b' },
        'info': { icon: 'info-circle', color: '#3b82f6' }
    };

    const config = types[type] || types.info;
    
    const container = document.getElementById('toastContainer') || 
                     document.body.appendChild(Object.assign(document.createElement('div'), { 
                         id: 'toastContainer',
                         style: 'position: fixed; top: 20px; right: 20px; z-index: 9999;'
                     }));

    const toast = document.createElement('div');
    toast.style.cssText = `
        background: white;
        border-left: 4px solid ${config.color};
        border-radius: 8px;
        padding: 15px 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 10px;
        animation: slideIn 0.3s ease;
    `;

    toast.innerHTML = `
        <i class="fas fa-${config.icon}" style="color: ${config.color};"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
};

// API request helper
window.apiRequest = async function(url, options = {}) {
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        ...options.headers
    };

    const token = localStorage.getItem('api_token');
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    } else {
        console.warn('⚠️ No token found in localStorage');
    }

    try {
        const response = await fetch(url, {
            ...options,
            headers
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            console.error(`❌ API Error ${response.status}:`, {
                url,
                status: response.status,
                statusText: response.statusText,
                data: errorData
            });

            if (response.status === 401) {
                localStorage.removeItem('api_token');
                window.location.href = '/login';
            }
            throw new Error(`API Error: ${response.status} - ${errorData.message || response.statusText}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('❌ API Request Failed:', error.message);
        throw error;
    }
};

// Logger
window.logger = {
    log: (msg) => console.log(`[LMS] ${msg}`),
    error: (msg) => console.error(`[LMS ERROR] ${msg}`),
    warn: (msg) => console.warn(`[LMS WARN] ${msg}`)
};

export {};
