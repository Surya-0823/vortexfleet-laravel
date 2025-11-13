function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;
    
    const iconSvg = type === 'success' 
        ? `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="alert-global-icon"><path d="M20 6 9 17l-5-5"/></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="alert-global-icon"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>`;
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert-global alert-global-${type}`;
    

    
    const messageSpan = document.createElement('span');
    messageSpan.className = 'alert-global-text';
    messageSpan.textContent = message; 
    
    alertDiv.innerHTML = iconSvg; 
    alertDiv.appendChild(messageSpan); 

    alertContainer.prepend(alertDiv);
    
    // =======================================================
    // --- ITHU THAAN ANTHA MUKKIYAMANA FIX ---
    // =======================================================
    // 3000ms (3 seconds) kazhichi, 'alert-global-fade-out' class-ah add pannum
    setTimeout(() => {
        alertDiv.classList.add('alert-global-fade-out');
        
        // Animation (fade out) mudinjathum, antha element-ah remove pannidum
        alertDiv.addEventListener('transitionend', () => {
            if (alertDiv) {
                alertDiv.remove();
            }
        });
    }, 3000); // 3000ms = 3 seconds
    // =======================================================
}


function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function appendCsrf(formData) {
    if (!(formData instanceof FormData)) {
        return formData;
    }

    const token = getCsrfToken();
    if (!token) {
        return formData;
    }

    if (typeof formData.set === 'function') {
        formData.set('_token', token);
    } else {
        formData.append('_token', token);
    }

    return formData;
}

function buildAjaxHeaders(extraHeaders = {}) {
    const token = getCsrfToken();
    const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        ...extraHeaders,
    };

    if (token) {
        headers['X-CSRF-TOKEN'] = token;
    }

    return headers;
}

if (typeof window !== 'undefined') {
    window.getCsrfToken = getCsrfToken;
    window.appendCsrf = appendCsrf;
    window.buildAjaxHeaders = buildAjaxHeaders;
}