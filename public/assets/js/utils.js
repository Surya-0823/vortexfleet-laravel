/**
 * @file
 * Common utility functions for the project.
 * Includes:
 * 1. CSRF Token Helpers
 * 2. Global Alert Function
 *
 * @license GNU
 */

// =======================================================
// --- CSRF Token Helper Function ---
// =======================================================
/**
 * Gets the CSRF token from the meta tag.
 * @returns {string} The CSRF token value.
 */
export function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        return token.getAttribute('content');
    }
    console.error('CSRF token meta tag not found!');
    return '';
}

/**
 * Appends the CSRF token to a FormData object.
 * @param {FormData} formData The FormData object to append to.
 */
export function appendCsrf(formData) {
    formData.append('_token', getCsrfToken());
}

/**
 * Builds standard headers for AJAX requests.
 * @param {Object} [customHeaders={}] - Optional custom headers to merge.
 * @returns {Headers} A Headers object.
 */
export function buildAjaxHeaders(customHeaders = {}) {
    const headers = new Headers();
    headers.append('X-CSRF-TOKEN', getCsrfToken());
    headers.append('X-Requested-With', 'XMLHttpRequest');

    // Add any custom headers
    for (const [key, value] of Object.entries(customHeaders)) {
        headers.append(key, value);
    }
    return headers;
}

// =======================================================
// --- Global Alert Function ---
// =======================================================
/**
 * Shows a global alert message at the top of the page.
 * @param {string} message - The message to display.
 * @param {string} type - 'success' or 'danger'.
 */
export function showAlert(message, type) {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;

    const alertId = 'alert-' + Date.now();
    const alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';

    const alertHTML = `
        <div id="${alertId}" class="alert-global ${alertClass}">
            <span class="alert-icon">
                ${type === 'success' ? 
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>' :
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>'
                }
            </span>
            <span class="alert-message">${message}</span>
            <button class="alert-close-btn" data-dismiss="${alertId}">&times;</button>
        </div>
    `;

    alertContainer.innerHTML = alertHTML + alertContainer.innerHTML;

    const newAlert = document.getElementById(alertId);
    if (!newAlert) return;

    // Auto-dismiss after 5 seconds
    const timer = setTimeout(() => {
        if (newAlert) {
            newAlert.style.opacity = '0';
            setTimeout(() => newAlert.remove(), 300);
        }
    }, 5000);

    // Manual close button
    const closeBtn = newAlert.querySelector('.alert-close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            clearTimeout(timer);
            if (newAlert) {
                newAlert.style.opacity = '0';
                setTimeout(() => newAlert.remove(), 300);
            }
        });
    }
}