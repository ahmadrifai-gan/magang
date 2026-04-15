/**
 * API Client Utility Function
 * Handles API requests dengan proper headers, error handling, dan response parsing
 */

/**
 * Get CSRF token dari meta tag atau cookie
 */
function getCsrfToken() {
    // Coba ambil dari meta tag
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (metaToken) {
        return metaToken;
    }
    
    // Fallback: ambil dari cookie
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
        const [name, value] = cookie.trim().split('=');
        if (name === 'XSRF-TOKEN') {
            return decodeURIComponent(value);
        }
    }
    
    console.warn('[API] CSRF token tidak ditemukan di meta tag atau cookie');
    return '';
}

/**
 * Make API request dengan proper error handling
 * @param {string} url - API endpoint URL
 * @param {object} options - Fetch options (method, body, etc)
 * @returns {Promise<{success: boolean, data: any, error: string}>}
 */
async function makeApiRequest(url, options = {}) {
    try {
        // Dapatkan CSRF token
        const csrfToken = getCsrfToken();
        
        // Setup default headers dengan Accept: application/json
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            ...options.headers
        };

        // Setup request config
        const config = {
            method: options.method || 'GET',
            headers: headers,
            credentials: 'same-origin', // Include cookies untuk session auth
            ...options,
            headers: headers // Override headers
        };

        // Log request (untuk debugging)
        console.log(`[API] ${config.method} ${url}`, {
            headers: {
                'Content-Type': config.headers['Content-Type'],
                'Accept': config.headers['Accept'],
                'X-CSRF-TOKEN': csrfToken ? '✓ Present' : '✗ Missing',
            },
            credentials: config.credentials
        });

        // Send request
        const response = await fetch(url, config);

        // Cek response status
        if (!response.ok) {
            // Coba parse error response sebagai JSON
            let errorMessage = `HTTP ${response.status}`;
            let errorData = null;

            try {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    errorData = await response.json();
                    errorMessage = errorData.message || JSON.stringify(errorData);
                } else {
                    // Jika bukan JSON, ambil text (untuk debug HTML error)
                    const text = await response.text();
                    if (text.length > 0) {
                        errorMessage = `Status ${response.status}: ${text.substring(0, 100)}...`;
                    }
                }
            } catch (parseError) {
                console.error('[API] Error parsing error response:', parseError);
            }

            // Log error detail
            console.error(`[API] Request failed`, {
                status: response.status,
                statusText: response.statusText,
                message: errorMessage,
                csrfTokenPresent: csrfToken ? 'Yes' : 'No',
                data: errorData
            });

            return {
                success: false,
                error: errorMessage,
                status: response.status,
                data: errorData
            };
        }

        // Response OK, coba parse JSON
        let responseData = null;
        try {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                responseData = await response.json();
            } else {
                responseData = await response.text();
            }
        } catch (parseError) {
            console.error('[API] Error parsing success response:', parseError);
            return {
                success: false,
                error: 'Invalid JSON response from server',
                data: null
            };
        }

        console.log(`[API] Request success`, {
            status: response.status,
            data: responseData
        });

        return {
            success: true,
            data: responseData,
            status: response.status
        };

    } catch (error) {
        // Network error atau error lainnya
        console.error('[API] Network or request error:', error);
        
        return {
            success: false,
            error: error.message || 'Network request failed. Check your connection.',
            data: null
        };
    }
}

/**
 * Helper untuk approve request
 */
async function approveLeaveRequest(requestId, notes = '') {
    showLoading();
    
    try {
        // Ensure CSRF cookie is set before making request
        await ensureCsrfCookie();
        
        const result = await makeApiRequest(`/api/leave-requests/${requestId}/approve`, {
            method: 'POST',
            body: JSON.stringify({ notes })
        });

        hideLoading();

        if (result.success) {
            showToast('✅ Pengajuan berhasil disetujui', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            // Tampilkan error message
            const errorMsg = result.data?.error || result.error || 'Gagal menyetujui pengajuan';
            showToast(`❌ ${errorMsg}`, 'error');
            
            // Log detail error untuk debugging
            console.error('Approve failed:', result);
        }
    } catch (error) {
        hideLoading();
        console.error('Unexpected error during approve:', error);
        showToast('❌ Terjadi error yang tidak terduga', 'error');
    }
}

/**
 * Helper untuk reject request
 */
async function rejectLeaveRequest(requestId, reason = '') {
    if (!reason.trim()) {
        showToast('⚠️ Alasan penolakan harus diisi', 'warning');
        return;
    }

    showLoading();
    
    try {
        // Ensure CSRF cookie is set before making request
        await ensureCsrfCookie();
        
        const result = await makeApiRequest(`/api/leave-requests/${requestId}/reject`, {
            method: 'POST',
            body: JSON.stringify({ reason })
        });

        hideLoading();

        if (result.success) {
            showToast('✅ Pengajuan berhasil ditolak', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            // Tampilkan error message
            const errorMsg = result.data?.error || result.error || 'Gagal menolak pengajuan';
            showToast(`❌ ${errorMsg}`, 'error');
            
            // Log detail error untuk debugging
            console.error('Reject failed:', result);
        }
    } catch (error) {
        hideLoading();
        console.error('Unexpected error during reject:', error);
        showToast('❌ Terjadi error yang tidak terduga', 'error');
    }
}

/**
 * Ensure CSRF cookie is set by hitting csrf-cookie endpoint
 */
async function ensureCsrfCookie() {
    try {
        const response = await fetch('/api/auth/csrf-cookie', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
            }
        });
        
        if (response.ok) {
            console.log('[API] CSRF cookie refreshed');
        }
    } catch (error) {
        console.warn('[API] Failed to refresh CSRF cookie:', error.message);
        // Non-blocking, continue anyway
    }
}
