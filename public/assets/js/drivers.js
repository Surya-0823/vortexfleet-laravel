/**
 * @file
 * Handles all client-side logic for the Drivers Management page.
 * Includes:
 * 1. Add/Edit Driver Modal (AJAX Form Submission, Validation)
 * 2. Delete Driver Modal (AJAX Deletion)
 * 3. Status Toggle (Verified -> Not Verified)
 * 4. OTP Verification (Send, Verify Modals)
 * 5. Password Reset (AJAX Request)
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
function getCsrfToken() {
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
function appendCsrf(formData) {
    formData.append('_token', getCsrfToken());
}

/**
 * Builds standard headers for AJAX requests.
 * @param {Object} [customHeaders={}] - Optional custom headers to merge.
 * @returns {Headers} A Headers object.
 */
function buildAjaxHeaders(customHeaders = {}) {
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
function showAlert(message, type) {
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


// =======================================================
// --- DOM Elements Cache ---
// =======================================================
let otpConfirmModal, otpConfirmPrimaryText, confirmOtpSendBtn, cancelOtpConfirmBtn;
let otpModal, otpForm, otpInput, otpError, otpMessage, otpResendBtn, otpTimer, closeOtpModal, otpUserIdInput;
let resetPasswordModal, resetPasswordText, confirmResetPasswordBtn, cancelResetPasswordBtn;
let showNewPasswordModal, newPasswordText, closeNewPasswordModal;
let addDriverModal, driverForm, driverIdInput, openAddDriverModal, closeAddDriverModal, cancelAddDriverModal;
let driverModalTitle, driverModalSubmitBtn, nameInput, emailInput, phoneInput;
let photoInput, photoPreview, uploadButton;
let deleteDriverModal, cancelDeleteModal, confirmDeleteBtn;
let statusConfirmModal, statusConfirmText, statusModalIcon, confirmStatusChangeBtn, cancelStatusChangeBtn;
let nameError, emailError, phoneError, photoError;

/**
 * Caches all required DOM elements into global variables.
 */
function cacheDOMElements() {
    // OTP Modals
    otpConfirmModal = document.getElementById('otpConfirmModal');
    otpConfirmPrimaryText = document.getElementById('otpConfirmPrimaryText');
    confirmOtpSendBtn = document.getElementById('confirmOtpSend');
    cancelOtpConfirmBtn = document.getElementById('cancelOtpConfirm');
    
    otpModal = document.getElementById('otpModal'); 
    otpForm = document.getElementById('otpForm');
    otpInput = document.getElementById('otp_code');
    otpError = document.getElementById('otpError');
    otpMessage = document.getElementById('otpMessage');
    otpResendBtn = document.getElementById('otpResendBtn');
    otpTimer = document.getElementById('otpTimer');
    closeOtpModal = document.getElementById('closeOtpModal');
    otpUserIdInput = document.getElementById('otp_user_id');

    // Reset Password Modals
    resetPasswordModal = document.getElementById('resetPasswordModal');
    resetPasswordText = document.getElementById('resetPasswordText');
    confirmResetPasswordBtn = document.getElementById('confirmResetPassword');
    cancelResetPasswordBtn = document.getElementById('cancelResetPassword');

    // This modal is no longer used by the reset flow, but we cache it
    // in case other parts of the system use it.
    showNewPasswordModal = document.getElementById('showNewPasswordModal');
    newPasswordText = document.getElementById('newPasswordText');
    closeNewPasswordModal = document.getElementById('closeNewPasswordModal');
    
    // Add/Edit Driver Modal
    addDriverModal = document.getElementById('addDriverModal');
    driverForm = document.getElementById('driverForm');
    driverIdInput = document.getElementById('driver_id');
    openAddDriverModal = document.getElementById('openAddDriverModal');
    closeAddDriverModal = document.getElementById('closeAddDriverModal');
    cancelAddDriverModal = document.getElementById('cancelAddDriverModal');
    
    // Add/Edit Driver Form Fields
    driverModalTitle = document.getElementById('driverModalTitle');
    driverModalSubmitBtn = document.getElementById('driverModalSubmitBtn');
    nameInput = document.getElementById('name');
    emailInput = document.getElementById('email');
    phoneInput = document.getElementById('phone');
    
    // Photo Upload
    photoInput = document.getElementById('photo');
    photoPreview = document.getElementById('photo-preview');
    uploadButton = document.getElementById('upload-button');

    // Delete Modal
    deleteDriverModal = document.getElementById('deleteDriverModal');
    cancelDeleteModal = document.getElementById('cancelDeleteModal');
    confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // Status Confirm Modal
    statusConfirmModal = document.getElementById('statusConfirmModal');
    statusConfirmText = document.getElementById('statusConfirmText');
    statusModalIcon = document.getElementById('statusModalIcon');
    confirmStatusChangeBtn = document.getElementById('confirmStatusChange');
    cancelStatusChangeBtn = document.getElementById('cancelStatusChange');

    // Validation Error Fields
    nameError = document.getElementById('nameError');
    emailError = document.getElementById('emailError');
    phoneError = document.getElementById('phoneError');
    photoError = document.getElementById('photoError');
}


// =======================================================
// --- Main Event Listener (DOMContentLoaded) ---
// =======================================================
document.addEventListener('DOMContentLoaded', function() {
    
    // Cache all elements first
    cacheDOMElements();

    // --- State variables ---
    let currentOtpUserId = null;
    let currentOtpUserEmail = null;
    let currentResetDriverId = null;
    let currentResetDriverName = null;
    let currentToggleSwitch = null; 
    let otpTimerInterval = null;

    // --- Validation patterns (Regex) ---
    const nameRegex = /^[a-zA-Z\s\.]{3,}$/; 
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
    const phoneRegex = /^\+?[0-9\s-]{10,15}$/; 
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    // =======================================================
    // --- Validation Helper Functions ---
    // =======================================================

    /**
     * Shows a validation error message for a form field.
     * @param {HTMLElement} input - The input element.
     * @param {HTMLElement} errorElement - The error display element.
     * @param {string} message - The error message.
     */
    function showError(input, errorElement, message) {
        if (input && input.classList) {
            input.classList.add('is-invalid');
        }
        if (errorElement) {
            errorElement.innerText = message;
        }
        // Auto-clear the error message after 3 seconds
        setTimeout(() => clearError(input, errorElement), 3000);
    }

    /**
     * Clears a validation error message for a form field.
     * @param {HTMLElement} input - The input element.
     * @param {HTMLElement} errorElement - The error display element.
     */
    function clearError(input, errorElement) {
        if (input && input.classList) {
            input.classList.remove('is-invalid');
        }
        if (errorElement) {
            errorElement.innerText = '';
        }
    }

    /**
     * Clears all validation errors from the driver form.
     */
    function clearAllDriverFormErrors() {
        clearError(nameInput, nameError);
        clearError(emailInput, emailError);
        clearError(phoneInput, phoneError);
        clearError(uploadButton, photoError);
    }

    // --- Individual Field Validation Functions ---

    function validateName() {
        if (!nameInput) return false;
        if (nameInput.value.trim() === '') {
            showError(nameInput, nameError, 'Full name is required.');
            return false;
        } else if (!nameRegex.test(nameInput.value)) {
            showError(nameInput, nameError, 'Please enter a valid name (min 3 letters).');
            return false;
        }
        clearError(nameInput, nameError);
        return true;
    }

    function validateEmail() {
        if (!emailInput) return false;
        if (emailInput.value.trim() === '') {
            showError(emailInput, emailError, 'Email is required.');
            return false;
        } else if (!emailRegex.test(emailInput.value)) {
            showError(emailInput, emailError, 'Please enter a valid email address.');
            return false;
        }
        clearError(emailInput, emailError);
        return true;
    }

    function validatePhone() {
        if (!phoneInput) return false;
        if (phoneInput.value.trim() === '') {
            showError(phoneInput, phoneError, 'Phone number is required.');
            return false;
        } else if (!phoneRegex.test(phoneInput.value)) {
            showError(phoneInput, phoneError, 'Please enter a valid 10-digit phone number.');
            return false;
        }
        clearError(phoneInput, phoneError);
        return true;
    }
    
    function validatePhoto() {
        if (!photoInput) return true; // No photo input, no error
        const file = photoInput.files[0];
        const isEditMode = driverIdInput.value !== '';
        
        // If in 'Add' mode, photo is required
        if (!file && !isEditMode) {
            showError(uploadButton, photoError, 'Driver image is required.');
            return false;
        }
        
        // If a file is selected (in Add or Edit mode)
        if (file) {
            if (file.size > MAX_FILE_SIZE) {
                showError(uploadButton, photoError, 'File is too large (Max 2MB).');
                return false;
            }
        }
        clearError(uploadButton, photoError);
        return true;
    }

    /**
     * Checks the validity of the entire driver form and enables/disables the submit button.
     */
    function checkFormValidity() {
        if (!nameInput) return; // Not on the right page
        
        const isNameValid = nameRegex.test(nameInput.value.trim());
        const isEmailValid = emailRegex.test(emailInput.value.trim());
        const isPhoneValid = phoneRegex.test(phoneInput.value.trim());
        
        const file = photoInput.files[0];
        const isEditMode = driverIdInput.value !== '';
        let isPhotoValid = true;
        
        if (!file && !isEditMode) { // Add mode, no file
            isPhotoValid = false;
        }
        if (file && file.size > MAX_FILE_SIZE) { // File too large
            isPhotoValid = false;
        }

        if (isNameValid && isEmailValid && isPhoneValid && isPhotoValid) {
            if (driverModalSubmitBtn) driverModalSubmitBtn.disabled = false;
        } else {
            if (driverModalSubmitBtn) driverModalSubmitBtn.disabled = true;
        }
    }

    // =======================================================
    // --- Add/Edit Driver Modal Functions ---
    // =======================================================

    /**
     * Resets the Add/Edit Driver modal to its default state.
     */
    const resetDriverModal = () => {
        if (driverModalTitle) driverModalTitle.innerText = 'Add New Driver';
        if (driverModalSubmitBtn) driverModalSubmitBtn.innerText = 'Submit Driver';
        
        if (driverForm) {
            driverForm.reset();
            driverForm.action = '/drivers/create';
        }
        if (driverIdInput) driverIdInput.value = '';

        if (photoPreview) {
            photoPreview.style.backgroundImage = 'none';
            photoPreview.style.borderStyle = 'dashed'; 
        }

        clearAllDriverFormErrors();
        if (driverModalSubmitBtn) driverModalSubmitBtn.disabled = true;
    };

    /**
     * Closes the Add/Edit Driver modal.
     */
    const closeDriverModal = () => {
        if (addDriverModal) addDriverModal.style.display = 'none'; 
        resetDriverModal(); 
    }

    // --- Open 'Add Driver' Modal ---
    if (openAddDriverModal) {
        openAddDriverModal.addEventListener('click', () => {
            resetDriverModal();
            if (addDriverModal) addDriverModal.style.display = 'flex';
            checkFormValidity();
        });
    }

    // --- Close 'Add Driver' Modal (Button/Cancel) ---
    if (closeAddDriverModal) closeAddDriverModal.addEventListener('click', closeDriverModal);
    if (cancelAddDriverModal) cancelAddDriverModal.addEventListener('click', closeDriverModal);
    
    // --- Close 'Add Driver' Modal (Overlay Click) ---
    if (addDriverModal) {
        addDriverModal.addEventListener('click', function(event) {
            if (event.target === addDriverModal) {
                closeDriverModal();
            }
        });
    }

    // =======================================================
    // --- Global Click Event Delegation ---
    // =======================================================
    document.addEventListener('click', function(event) {
        
        // --- 1. Edit Driver Button Click ---
        const editButton = event.target.closest('.js-edit-driver');
        if (editButton) {
            event.preventDefault(); 
            resetDriverModal();
            
            // Get data from button attributes
            const id = editButton.getAttribute('data-id');
            const name = editButton.getAttribute('data-name');
            const email = editButton.getAttribute('data-email');
            const phone = editButton.getAttribute('data-phone');
            const photoPath = editButton.getAttribute('data-photo');
            
            // Populate modal fields
            if (driverModalTitle) driverModalTitle.innerText = 'Edit Driver';
            if (driverModalSubmitBtn) driverModalSubmitBtn.innerText = 'Update Driver';
            if (driverForm) driverForm.action = '/drivers/update';
            if (driverIdInput) driverIdInput.value = id;
            if (nameInput) nameInput.value = name;
            if (emailInput) emailInput.value = email;
            if (phoneInput) phoneInput.value = phone;

            // Set photo preview
            if (photoPreview) {
                 if (photoPath && photoPath !== '') {
                    photoPreview.style.backgroundImage = `url('${photoPath}')`;
                    photoPreview.style.borderStyle = 'solid';
                } else {
                    // Fallback to Dicebear avatar if no photo path
                    photoPreview.style.backgroundImage = `url('https://api.dicebear.com/7.x/initials/svg?seed=${encodeURIComponent(name)}&backgroundColor=282c34&fontColor=86efac')`;
                    photoPreview.style.borderStyle = 'solid';
                }
            }
            if (addDriverModal) addDriverModal.style.display = 'flex';
            if (driverModalSubmitBtn) driverModalSubmitBtn.disabled = false;
        }

        // --- 2. Delete Driver Button Click ---
        const deleteButton = event.target.closest('.js-delete-driver');
        if (deleteButton) {
            event.preventDefault();
            const deleteId = deleteButton.getAttribute('data-delete-id');
            const deleteUrl = deleteButton.getAttribute('data-delete-url');

            // Pass data to the confirmation button
            if (confirmDeleteBtn) {
                confirmDeleteBtn.setAttribute('data-id', deleteId); 
                confirmDeleteBtn.setAttribute('data-url', deleteUrl);
            }
            if (deleteDriverModal) {
                deleteDriverModal.style.display = 'flex';
            }
        }
        
        // --- 3. Reset Password Button Click ---
        const resetPasswordButton = event.target.closest('.js-reset-password');
        if (resetPasswordButton) {
            event.preventDefault();
            currentResetDriverId = resetPasswordButton.getAttribute('data-id');
            currentResetDriverName = resetPasswordButton.getAttribute('data-name');
            
            if (resetPasswordText) {
                resetPasswordText.innerText = `Are you sure you want to reset the password for ${currentResetDriverName}?`;
            }
            if (resetPasswordModal) {
                resetPasswordModal.style.display = 'flex';
            }
        }
        
        // --- 4. Status Toggle Button Click (Verified -> Not Verified) ---
        // Note: This button is not in the blade file, but logic is here if added.
        const statusToggleButton = event.target.closest('.js-status-toggle');
        if (statusToggleButton) {
            event.preventDefault();
            currentToggleSwitch = statusToggleButton;
            const userName = statusToggleButton.getAttribute('data-user-name');
            const newStatusText = 'Not Verified'; 

            if (statusConfirmText) statusConfirmText.innerText = `Are you sure you want to set ${userName} as ${newStatusText}? This will disable their login.`;
            
            // Set modal to warning style
            if(statusModalIcon) {
                statusModalIcon.style.backgroundColor = 'hsla(var(--warning), 0.15)';
                statusModalIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--warning))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
            }
            if(confirmStatusChangeBtn) {
                confirmStatusChangeBtn.style.backgroundColor = 'hsl(var(--warning))';
                confirmStatusChangeBtn.style.color = '#000';
            }
            if (statusConfirmModal) statusConfirmModal.style.display = 'flex';
        }
        
        // --- 5. Send OTP Button Click (Not Verified -> OTP Confirm) ---
        const otpButton = event.target.closest('.js-send-otp');
        if (otpButton) {
            event.preventDefault();
            currentOtpUserId = otpButton.getAttribute('data-user-id');
            currentOtpUserEmail = otpButton.getAttribute('data-user-email');
            
            if (otpConfirmPrimaryText) {
                otpConfirmPrimaryText.innerText = `Send verification OTP to ${currentOtpUserEmail}?`;
            }
            if (otpConfirmModal) {
                otpConfirmModal.style.display = 'flex';
            }
        }
    });

    // =======================================================
    // --- Delete Modal Logic (AJAX) ---
    // =======================================================
    const closeDeleteModal = () => {
        if (deleteDriverModal) deleteDriverModal.style.display = 'none';
        if (confirmDeleteBtn) {
            confirmDeleteBtn.removeAttribute('data-id');
            confirmDeleteBtn.removeAttribute('data-url');
        }
    };
    
    if (cancelDeleteModal) {
        cancelDeleteModal.addEventListener('click', closeDeleteModal);
    }
    
    if (deleteDriverModal) {
        deleteDriverModal.addEventListener('click', (e) => {
            if (e.target === deleteDriverModal) closeDeleteModal();
        });
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            const deleteId = confirmDeleteBtn.getAttribute('data-id');
            const deleteUrl = confirmDeleteBtn.getAttribute('data-url');

            if (!deleteId || !deleteUrl) return;
                
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2" style="animation: spin 1s linear infinite;">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                <span>Deleting...</span>`;
            
            // Add spin animation
            if (!document.getElementById('animate-spin-style')) {
                const style = document.createElement('style');
                style.id = 'animate-spin-style';
                style.innerHTML = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
                document.head.appendChild(style);
            }

            const formData = new FormData();
            formData.append('id', deleteId);
            appendCsrf(formData);

            fetch(deleteUrl, {
                method: 'POST',
                body: formData,
                headers: buildAjaxHeaders({ Accept: 'application/json' })
            })
            .then(async response => {
                const data = await response.json();
                 if (!response.ok) {
                    return Promise.reject(data);
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    closeDeleteModal();
                    setTimeout(() => { location.reload(); }, 1500); 
                } else {
                    showAlert(data.message, 'danger');
                    closeDeleteModal();
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showAlert((error.message || 'An unknown error occurred.') + ' Please try again.', 'danger');
                closeDeleteModal();
            })
            .finally(() => {
                // Restore button
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    <span>Delete</span>`;
            });
        });
    }

    // =======================================================
    // --- Photo Upload Preview Logic ---
    // =======================================================
    if (uploadButton) {
        uploadButton.addEventListener('click', () => photoInput.click());
    }
    if (photoPreview) {
        photoPreview.addEventListener('click', () => photoInput.click());
    }
    if (photoInput) {
        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.style.backgroundImage = `url('${e.target.result}')`;
                    photoPreview.style.borderStyle = 'solid'; 
                }
                reader.readAsDataURL(file);
            }
            checkFormValidity(); // Check validity after photo change
        });
    }
    
    // --- Real-time form validation ---
    if (nameInput) nameInput.addEventListener('input', checkFormValidity);
    if (emailInput) emailInput.addEventListener('input', checkFormValidity);
    if (phoneInput) phoneInput.addEventListener('input', checkFormValidity);

    
    // =======================================================
    // --- Form Submit (Add/Edit) AJAX Logic ---
    // =======================================================
    if (driverForm) {
        driverForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            
            // Final validation check before submit
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPhoneValid = validatePhone();
            const isPhotoValid = validatePhoto();

            if (!isNameValid || !isEmailValid || !isPhoneValid || !isPhotoValid) {
                showAlert('Please fix the errors in the form.', 'danger');
                return;
            }
            
            driverModalSubmitBtn.disabled = true;
            driverModalSubmitBtn.innerText = 'Submitting...';
            
            clearAllDriverFormErrors();
            
            const formData = new FormData(driverForm);
            appendCsrf(formData);
            const url = driverForm.action;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: buildAjaxHeaders({ Accept: 'application/json' }) // No 'Content-Type' for FormData
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    throw new Error(`Server returned non-JSON response: ${text.substring(0, 200)}`);
                }
                const data = await response.json();
                if (!response.ok) {
                    return Promise.reject(data); // Pass server error data to catch block
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    closeDriverModal();
                    setTimeout(() => {
                        location.reload(); 
                    }, 1500);

                } else {
                    // This 'else' block is for data.success = false
                    showAlert(data.message || 'Validation failed.', 'danger');
                    if (data.errors) {
                        if (data.errors.name) showError(nameInput, nameError, data.errors.name);
                        if (data.errors.email) showError(emailInput, emailError, data.errors.email);
                        if (data.errors.phone) showError(phoneInput, phoneError, data.errors.phone);
                        if (data.errors.photo) showError(uploadButton, photoError, data.errors.photo);
                    }
                }
            })
            .catch(error => {
                // This 'catch' block is for network errors or rejected promises
                console.error('Fetch Error:', error);
                let errorMessage = 'An unknown error occurred. Please try again.';
                if (error && typeof error === 'object') {
                    errorMessage = error.message || errorMessage;
                    if (error.errors) {
                        if (error.errors.name) showError(nameInput, nameError, error.errors.name);
                        if (error.errors.email) showError(emailInput, emailError, error.errors.email);
                        if (error.errors.phone) showError(phoneInput, phoneError, error.errors.phone);
                        if (error.errors.photo) showError(uploadButton, photoError, error.errors.photo);
                    }
                }
                showAlert(errorMessage, 'danger');
            })
            .finally(() => {
                // Restore submit button
                driverModalSubmitBtn.disabled = false;
                const isEditMode = driverIdInput && driverIdInput.value !== '';
                driverModalSubmitBtn.innerText = isEditMode ? 'Update Driver' : 'Submit Driver';
            });
        });
    }

    
    // =======================================================
    // --- Status Toggle Confirmation (Verified -> Not Verified) ---
    // =======================================================
    const closeStatusModal = () => {
        if (statusConfirmModal) statusConfirmModal.style.display = 'none';
        currentToggleSwitch = null; 
    };

    if (confirmStatusChangeBtn) {
        confirmStatusChangeBtn.addEventListener('click', () => {
            if (currentToggleSwitch) {
                const driverId = currentToggleSwitch.getAttribute('data-user-id');
                const newStatus = 0; // Set to 'Not Verified'
                
                const formData = new FormData();
                formData.append('id', driverId);
                formData.append('is_verified', newStatus);
                appendCsrf(formData);

                fetch('/drivers/update-status', {
                    method: 'POST', 
                    body: formData,
                    headers: buildAjaxHeaders({ Accept: 'application/json' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(err => showAlert('An error occurred while updating status.', 'danger'));
                
                closeStatusModal();
            }
        });
    }

    if (cancelStatusChangeBtn) {
        cancelStatusChangeBtn.addEventListener('click', closeStatusModal);
    }

    if (statusConfirmModal) {
        statusConfirmModal.addEventListener('click', (e) => {
            if (e.target === statusConfirmModal) closeStatusModal();
        });
    }

    // =======================================================
    // --- OTP Modal 1: Confirmation ("Send OTP?") ---
    // =======================================================

    const closeOtpConfirmModal = () => {
        if (otpConfirmModal) otpConfirmModal.style.display = 'none';
        currentOtpUserId = null;
        currentOtpUserEmail = null;
    };
    
    if (cancelOtpConfirmBtn) {
        cancelOtpConfirmBtn.addEventListener('click', closeOtpConfirmModal);
    }
    
    if (otpConfirmModal) {
        otpConfirmModal.addEventListener('click', (e) => {
            if (e.target === otpConfirmModal) closeOtpConfirmModal();
        });
    }

    if (confirmOtpSendBtn) {
        confirmOtpSendBtn.addEventListener('click', () => {
            if (currentOtpUserId) {
                if (otpUserIdInput) otpUserIdInput.value = currentOtpUserId;
                showOtpEntryModal(); // Open Modal 2
                sendOtpRequest(currentOtpUserId); // Send the request
            }
            closeOtpConfirmModal(); // Close Modal 1
        });
    }

    // =======================================================
    // --- OTP Modal 2: Entry ("Enter Code") ---
    // =======================================================
    
    /**
     * Shows the OTP entry modal.
     */
    function showOtpEntryModal() {
        if (otpModal) otpModal.style.display = 'flex';
    }
    
    /**
     * Closes and resets the OTP entry modal.
     */
    function closeOtpEntryModal() {
        if (otpModal) otpModal.style.display = 'none';
        if (otpForm) otpForm.reset();
        if (otpMessage) {
            otpMessage.innerText = 'An OTP has been sent to the driver\'s email.';
            otpMessage.style.color = 'hsl(var(--foreground))';
        }
        if (otpError) otpError.innerText = '';
        clearInterval(otpTimerInterval);
        if (otpTimer) otpTimer.innerText = '';
    }
    
    if (closeOtpModal) closeOtpModal.addEventListener('click', closeOtpEntryModal);
    if (otpModal) otpModal.addEventListener('click', (e) => {
        if (e.target === otpModal) closeOtpEntryModal();
    });

    /**
     * Sends the AJAX request to generate and send an OTP.
     * @param {string} userId - The ID of the driver.
     */
    function sendOtpRequest(userId) {
        const formData = new FormData();
        formData.append('driver_id', userId);
        appendCsrf(formData);

        startOtpTimer(60); // Start 60-second timer
        if (otpMessage) {
             otpMessage.innerText = 'Sending OTP...';
             otpMessage.style.color = 'hsl(var(--muted-foreground))';
        }
        if (otpError) otpError.innerText = ''; 

        fetch('/drivers/send-otp', {
            method: 'POST', 
            body: formData,
            headers: buildAjaxHeaders({ Accept: 'application/json' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (otpMessage) {
                    otpMessage.innerText = data.message;
                    otpMessage.style.color = 'hsl(var(--success))';
                }
            } else {
                // If sending failed (e.g., rate limit)
                clearInterval(otpTimerInterval);
                if (otpTimer) otpTimer.innerText = '';
                if (otpResendBtn) otpResendBtn.disabled = false;
                if (otpMessage) {
                    otpMessage.innerText = data.message;
                    otpMessage.style.color = 'hsl(var(--destructive))';
                }
            }
        })
        .catch(err => {
            if (otpMessage) {
                otpMessage.innerText = 'Error sending OTP.';
                otpMessage.style.color = 'hsl(var(--destructive))';
            }
            clearInterval(otpTimerInterval);
            if (otpTimer) otpTimer.innerText = '';
            if (otpResendBtn) otpResendBtn.disabled = false;
        });
    }
    
    // --- Resend OTP Button ---
    if (otpResendBtn) {
        otpResendBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const userId = otpUserIdInput.value;
            if (userId) {
                sendOtpRequest(userId);
            }
        });
    }
    
    /**
     * Starts the countdown timer for OTP resend.
     * @param {number} duration - The duration in seconds.
     */
    function startOtpTimer(duration) {
        let timer = duration;
        if (otpResendBtn) otpResendBtn.disabled = true;
        
        clearInterval(otpTimerInterval); // Clear any existing timer
        if (otpTimer) otpTimer.innerText = `(Resend in ${timer}s)`;

        otpTimerInterval = setInterval(() => {
            timer--;
            if (otpTimer) otpTimer.innerText = `(Resend in ${timer}s)`;
            if (timer <= 0) {
                clearInterval(otpTimerInterval);
                if (otpTimer) otpTimer.innerText = '';
                if (otpResendBtn) otpResendBtn.disabled = false;
            }
        }, 1000);
    }

    // --- Verify OTP Form Submit ---
    if (otpForm) {
        otpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const otp = otpInput.value;
            const userId = otpUserIdInput.value;
            
            if (otp.length < 6) {
                if (otpError) otpError.innerText = 'OTP must be 6 digits.';
                return;
            }
            if (otpError) otpError.innerText = '';

            const formData = new FormData();
            formData.append('driver_id', userId);
            formData.append('otp_code', otp);
            appendCsrf(formData);

            fetch('/drivers/verify-otp', {
                method: 'POST',
                body: formData,
                headers: buildAjaxHeaders({ Accept: 'application/json' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Success! Show alert, close modal, reload page
                    showAlert(data.message, 'success');
                    closeOtpEntryModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    // Failed verification
                    if (otpError) otpError.innerText = data.message;
                    if (data.message.includes('locked')) {
                        // Close modal if locked out
                        setTimeout(() => closeOtpEntryModal(), 2000);
                    }
                }
            })
            .catch(err => {
                if (otpError) otpError.innerText = 'An error occurred during verification.';
            });
        });
    }

    
    // =======================================================
    // --- PUTHU MAATRAM: Reset Password Modal Logic ---
    // =======================================================
    
    /**
     * Closes and resets the Password Reset modal.
     */
    const closeResetPasswordModal = () => {
        if (resetPasswordModal) resetPasswordModal.style.display = 'none';
        currentResetDriverId = null;
        currentResetDriverName = null;
    };
    
    if (cancelResetPasswordBtn) {
        cancelResetPasswordBtn.addEventListener('click', closeResetPasswordModal);
    }
    
    if (resetPasswordModal) {
        resetPasswordModal.addEventListener('click', (e) => {
            if (e.target === resetPasswordModal) closeResetPasswordModal();
        });
    }

    // --- Confirm Reset Password Button Click (AJAX) ---
    if (confirmResetPasswordBtn) {
        confirmResetPasswordBtn.addEventListener('click', () => {
            if (!currentResetDriverId) return;
            
            confirmResetPasswordBtn.disabled = true;
            confirmResetPasswordBtn.innerText = 'Resetting...';

            const formData = new FormData();
            formData.append('id', currentResetDriverId);
            appendCsrf(formData);

            fetch('/drivers/reset-password', {
                method: 'POST',
                body: formData,
                headers: buildAjaxHeaders({ Accept: 'application/json' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // --- ITHU THAAN ANTHA PUTHU LOGIC ---
                    // Namma ippo password modal-ah kaatta maatom.
                    // Message kaatitu, page-ah reload pannuvom.
                    
                    // 1. Modal-ah close pannurom
                    closeResetPasswordModal();
                    
                    // 2. Success alert kaatrom
                    showAlert(data.message, 'success');
                    
                    // 3. Page-ah reload pannurom
                    // Driver ippo 'Not Verified' nu kaatum
                    setTimeout(() => {
                        location.reload();
                    }, 1500); // 1.5s kalichi reload pannurom

                } else {
                    // Error
                    showAlert(data.message || 'An error occurred.', 'danger');
                    closeResetPasswordModal();
                }
            })
            .catch(err => {
                showAlert('An unknown error occurred.', 'danger');
                closeResetPasswordModal();
            })
            .finally(() => {
                // Button-ah pazhaya nilaikku kondu varom
                confirmResetPasswordBtn.disabled = false;
                confirmResetPasswordBtn.innerText = 'Reset Password';
            });
        });
    }
    
    // --- 'Show New Password' Modal (Ithu ippo use aagathu, aana logic irukattu) ---
    const closeShowNewPasswordModal = () => {
        if (showNewPasswordModal) {
            showNewPasswordModal.style.display = 'none';
        }
        if (newPasswordText) {
            newPasswordText.value = ''; // Input-ah clear pannidrom
        }
    };

    if (closeNewPasswordModal) {
        closeNewPasswordModal.addEventListener('click', closeShowNewPasswordModal);
    }
    
    if (showNewPasswordModal) {
        showNewPasswordModal.addEventListener('click', (e) => {
            if (e.target === showNewPasswordModal) {
                 closeShowNewPasswordModal();
            }
        });
    }
    // --- PUTHU MAATRAM MUDINJATHU ---
    
});