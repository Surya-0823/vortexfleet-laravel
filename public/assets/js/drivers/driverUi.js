/**
 * @file
 * Handles all UI logic for the Drivers Management page.
 * Includes:
 * 1. DOM Element Caching
 * 2. Form Validation
 * 3. Modal Controls (Add/Edit, Delete, OTP, etc.)
 * 4. Photo Preview
 *
 * @license GNU
 */

// ../utils.js la irundhu (oru folder veliya) import panrom
import { showAlert } from '../utils.js';

// =======================================================
// --- DOM Elements Cache ---
// =======================================================
// Ella DOM element variables-ayum inga export panrom
export let otpConfirmModal, otpConfirmPrimaryText, confirmOtpSendBtn, cancelOtpConfirmBtn;
export let otpModal, otpForm, otpInput, otpError, otpMessage, otpResendBtn, otpTimer, closeOtpModal, otpUserIdInput;
export let resetPasswordModal, resetPasswordText, confirmResetPasswordBtn, cancelResetPasswordBtn;
export let showNewPasswordModal, newPasswordText, closeNewPasswordModal;
export let addDriverModal, driverForm, driverIdInput, openAddDriverModal, closeAddDriverModal, cancelAddDriverModal;
export let driverModalTitle, driverModalSubmitBtn, nameInput, emailInput, phoneInput;
export let photoInput, photoPreview, uploadButton;
export let deleteDriverModal, cancelDeleteModal, confirmDeleteBtn;
export let statusConfirmModal, statusConfirmText, statusModalIcon, confirmStatusChangeBtn, cancelStatusChangeBtn;
export let nameError, emailError, phoneError, photoError;

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
export function showError(input, errorElement, message) {
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
export function clearError(input, errorElement) {
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
export function clearAllDriverFormErrors() {
    clearError(nameInput, nameError);
    clearError(emailInput, emailError);
    clearError(phoneInput, phoneError);
    clearError(uploadButton, photoError);
}

// --- Individual Field Validation Functions ---

export function validateName() {
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

export function validateEmail() {
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

export function validatePhone() {
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

export function validatePhoto() {
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
export function checkFormValidity() {
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
// --- Modal Control Functions ---
// =======================================================

/**
 * Resets the Add/Edit Driver modal to its default state.
 */
export const resetDriverModal = () => {
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
export const closeDriverModal = () => {
    if (addDriverModal) addDriverModal.style.display = 'none'; 
    resetDriverModal(); 
}

/**
 * Closes the Delete modal.
 */
export const closeDeleteModal = () => {
    if (deleteDriverModal) deleteDriverModal.style.display = 'none';
    if (confirmDeleteBtn) {
        confirmDeleteBtn.removeAttribute('data-id');
        confirmDeleteBtn.removeAttribute('data-url');
    }
};

/**
 * Closes the Status Change modal.
 */
export const closeStatusModal = () => {
    if (statusConfirmModal) statusConfirmModal.style.display = 'none';
    // currentToggleSwitch will be reset in drivers.js
};

/**
 * Closes the "Send OTP?" confirmation modal.
 */
export const closeOtpConfirmModal = () => {
    if (otpConfirmModal) otpConfirmModal.style.display = 'none';
    // currentOtpUserId/Email will be reset in drivers.js
};

/**
 * Shows the OTP entry modal.
 */
export function showOtpEntryModal() {
    if (otpModal) otpModal.style.display = 'flex';
}

/**
 * Closes and resets the OTP entry modal.
 * @param {number} otpTimerInterval - The interval ID to clear.
 */
export function closeOtpEntryModal(otpTimerInterval) {
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

/**
 * Starts the countdown timer for OTP resend.
 * @returns {number} The interval ID.
 */
export function startOtpTimer(duration) {
    let timer = duration;
    if (otpResendBtn) otpResendBtn.disabled = true;

    // Clear any existing timer (idhu drivers.js la irundhu varum)
    // clearInterval(otpTimerInterval); 
    if (otpTimer) otpTimer.innerText = `(Resend in ${timer}s)`;

    const intervalId = setInterval(() => {
        timer--;
        if (otpTimer) otpTimer.innerText = `(Resend in ${timer}s)`;
        if (timer <= 0) {
            clearInterval(intervalId);
            if (otpTimer) otpTimer.innerText = '';
            if (otpResendBtn) otpResendBtn.disabled = false;
        }
    }, 1000);
    return intervalId;
}

/**
 * Closes and resets the Password Reset modal.
 */
export const closeResetPasswordModal = () => {
    if (resetPasswordModal) resetPasswordModal.style.display = 'none';
    // currentResetDriverId/Name will be reset in drivers.js
};

/**
 * Closes the "Show New Password" modal.
 */
export const closeShowNewPasswordModal = () => {
    if (showNewPasswordModal) {
        showNewPasswordModal.style.display = 'none';
    }
    if (newPasswordText) {
        newPasswordText.value = ''; // Input-ah clear pannidrom
    }
};

// =======================================================
// --- UI Event Listeners (Internal to UI) ---
// =======================================================

/**
 * Attaches listeners for photo preview and real-time validation.
 */
function attachInternalUiListeners() {
    // --- Photo Upload Preview Logic ---
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
}

// =======================================================
// --- Init Function ---
// =======================================================

/**
 * Initializes the UI module.
 * Caches DOM elements and attaches internal listeners.
 */
export function init() {
    cacheDOMElements();
    attachInternalUiListeners();
}