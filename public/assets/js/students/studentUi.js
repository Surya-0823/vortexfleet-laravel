/**
 * @file
 * Handles all UI logic for the Students Management page.
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

// OTP Modals
export let otpConfirmModal, otpConfirmPrimaryText, confirmOtpSendBtn, cancelOtpConfirmBtn;
export let otpModal, otpForm, otpInput, otpError, otpMessage, otpResendBtn, otpTimer, closeOtpModal, otpUserIdInput;

// Reset Password Modals
export let resetPasswordModal, resetPasswordText, confirmResetPasswordBtn, cancelResetPasswordBtn;
export let showNewPasswordModal, newPasswordText, closeNewPasswordModal;

// Add/Edit Student Modal
export let addStudentModal, studentForm, studentIdInput, openAddStudentModal, closeAddStudentModal, cancelAddStudentModal;
export let driverModalTitle, studentModalSubmitBtn, nameInput, emailInput, phoneInput;
export let gradeInput, routeInput; // student-ku mattum

// Photo Upload
export let photoInput, photoPreview, uploadButton;

// Delete Modal
export let deleteDriverModal, cancelDeleteModal, confirmDeleteBtn;

// Status Confirm Modal
export let statusConfirmModal, statusConfirmText, statusModalIcon, confirmStatusChangeBtn, cancelStatusChangeBtn;

// Validation Error Fields
export let nameError, emailError, phoneError, gradeError, routeError, photoError;
export let appUsernameError, appPasswordError; // (Blade-la illai, aana JS-la irundhuchu)

// (Blade-la illadha inputs)
let appUsernameInput, appPasswordInput;

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

    // Add/Edit Student Modal
    addStudentModal = document.getElementById('addStudentModal');
    studentForm = document.getElementById('studentForm');
    studentIdInput = document.getElementById('student_id');
    openAddStudentModal = document.getElementById('openAddStudentModal');
    closeAddStudentModal = document.getElementById('closeAddStudentModal');
    cancelAddStudentModal = document.getElementById('cancelAddStudentModal');

    // Add/Edit Student Form Fields
    driverModalTitle = document.getElementById('studentModalTitle');
    studentModalSubmitBtn = document.getElementById('studentModalSubmitBtn');
    nameInput = document.getElementById('name');
    emailInput = document.getElementById('email');
    phoneInput = document.getElementById('phone');
    gradeInput = document.getElementById('grade'); // Blade-la illai
    routeInput = document.getElementById('route_name'); 
    appUsernameInput = document.getElementById('app_username'); // Blade-la illai
    appPasswordInput = document.getElementById('app_password'); // Blade-la illai

    // Photo Upload
    photoInput = document.getElementById('photo');
    photoPreview = document.getElementById('photo-preview');
    uploadButton = document.getElementById('upload-button');

    // Delete Modal
    deleteDriverModal = document.getElementById('deleteStudentModal');
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
    gradeError = document.getElementById('gradeError');
    routeError = document.getElementById('route_nameError');
    photoError = document.getElementById('photoError');
    appUsernameError = document.getElementById('app_usernameError');
    appPasswordError = document.getElementById('app_passwordError');
}

// --- Validation patterns (Regex) ---
const nameRegex = /^[a-zA-Z\s\.]{3,}$/; 
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
const phoneRegex = /^\+?[0-9\s-]{10,15}$/; 
const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

// =======================================================
// --- Validation Helper Functions ---
// =======================================================

export function showError(input, errorElement, message) {
    if (input && input.classList) { 
        input.classList.add('is-invalid');
    }
    if (errorElement) { 
        errorElement.innerText = message;
    }
    setTimeout(() => clearError(input, errorElement), 3000);
}

export function clearError(input, errorElement) {
    if (input && input.classList) { 
        input.classList.remove('is-invalid');
    }
    if (errorElement) { 
        errorElement.innerText = '';
    }
}

export function clearAllStudentFormErrors() {
    clearError(nameInput, nameError);
    clearError(emailInput, emailError);
    clearError(phoneInput, phoneError);
    clearError(gradeInput, gradeError);
    clearError(routeInput, routeError);
    clearError(uploadButton, photoError);
    clearError(appUsernameInput, appUsernameError);
    clearError(appPasswordInput, appPasswordError);
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

export function validateGrade() {
    if (!gradeInput) return true; // Blade-la illai
    if (gradeInput.value.trim() === '') {
        showError(gradeInput, gradeError, 'Grade is required.');
        return false;
    }
    clearError(gradeInput, gradeError);
    return true;
}

export function validateRoute() {
    if (!routeInput) return false;
    if (routeInput.value.trim() === '') {
        showError(routeInput, routeError, 'Please select a bus route.');
        return false;
    }
    clearError(routeInput, routeError);
    return true;
}

export function validatePhoto() {
    if (!photoInput) return true; 
    const file = photoInput.files[0];
    if (file) {
        if (file.size > MAX_FILE_SIZE) {
            showError(uploadButton, photoError, 'File is too large (Max 2MB).');
            return false;
        }
    }
    clearError(uploadButton, photoError);
    return true;
}

export function validateAppUsername() {
    if (!appUsernameInput) return true; // Blade-la illai
    if (appUsernameInput.value.trim() === '') {
        showError(appUsernameInput, appUsernameError, 'App username is required.');
        return false;
    }
    clearError(appUsernameInput, appUsernameError);
    return true;
}

export function validateAppPassword() {
    if (!appPasswordInput) return true; // Blade-la illai
    if (appPasswordInput.value.trim().length < 6) {
        showError(appPasswordInput, appPasswordError, 'App password must be at least 6 characters.');
        return false;
    }
    clearError(appPasswordInput, appPasswordError);
    return true;
}

/**
 * Checks the validity of the entire student form.
 */
export function checkFormValidity() {
    if (!nameInput) return; 

    clearAllStudentFormErrors();

    const isNameValid = nameRegex.test(nameInput.value.trim());
    const isEmailValid = emailRegex.test(emailInput.value.trim());
    const isPhoneValid = phoneRegex.test(phoneInput.value.trim());
    const isGradeValid = validateGrade(); // Check pannum (true varum)
    const isRouteValid = validateRoute();
    const isPhotoValid = validatePhoto();
    const isAppUsernameValid = validateAppUsername(); // Check pannum (true varum)
    const isAppPasswordValid = validateAppPassword(); // Check pannum (true varum)

    if (isNameValid && isEmailValid && isPhoneValid && isGradeValid && isRouteValid && isPhotoValid && isAppUsernameValid && isAppPasswordValid) {
        if (studentModalSubmitBtn) studentModalSubmitBtn.disabled = false;
    } else {
        if (studentModalSubmitBtn) studentModalSubmitBtn.disabled = true;
    }
}

// =======================================================
// --- Modal Control Functions ---
// =======================================================

export const resetStudentModal = () => {
    if (driverModalTitle) driverModalTitle.innerText = 'Add New Student';
    if (studentModalSubmitBtn) studentModalSubmitBtn.innerText = 'Submit Student';

    if (studentForm) {
        studentForm.reset();
        studentForm.action = '/students/create';
    }
    if (studentIdInput) studentIdInput.value = '';

    if (appUsernameInput) { // Blade-la illai
        delete appUsernameInput.dataset.userEdited;
        appUsernameInput.value = '';
    }
    if (appPasswordInput) { // Blade-la illai
        delete appPasswordInput.dataset.userEdited;
        appPasswordInput.value = '';
    }

    if (photoPreview) {
        photoPreview.style.backgroundImage = 'none';
        photoPreview.style.borderStyle = 'dashed'; 
    }

    clearAllStudentFormErrors();
    if (studentModalSubmitBtn) studentModalSubmitBtn.disabled = true;
};

export const closeStudentModal = () => {
    if (addStudentModal) addStudentModal.style.display = 'none'; 
    resetStudentModal(); 
}

export const closeDeleteModal = () => {
    if (deleteDriverModal) deleteDriverModal.style.display = 'none';
    if (confirmDeleteBtn) {
        confirmDeleteBtn.removeAttribute('data-id');
        confirmDeleteBtn.removeAttribute('data-url');
    }
};

export const closeStatusModal = () => {
    if (statusConfirmModal) statusConfirmModal.style.display = 'none';
};

export const closeOtpConfirmModal = () => {
    if (otpConfirmModal) otpConfirmModal.style.display = 'none';
};

export function showOtpEntryModal() {
    if (otpModal) otpModal.style.display = 'flex';
}

export function closeOtpEntryModal(otpTimerInterval) {
    if (otpModal) otpModal.style.display = 'none';
    if (otpForm) otpForm.reset();
    if (otpMessage) {
        otpMessage.innerText = 'An OTP has been sent to the student\'s email.';
        otpMessage.style.color = 'hsl(var(--foreground))';
    }
    if (otpError) otpError.innerText = '';
    clearInterval(otpTimerInterval);
    if (otpTimer) otpTimer.innerText = '';
}

export function startOtpTimer(duration) {
    let timer = duration;
    if (otpResendBtn) otpResendBtn.disabled = true;

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

export const closeResetPasswordModal = () => {
    if (resetPasswordModal) resetPasswordModal.style.display = 'none';
};

export const closeShowNewPasswordModal = () => {
    if (showNewPasswordModal) {
        showNewPasswordModal.style.display = 'none';
    }
    if (newPasswordText) {
        newPasswordText.value = ''; 
    }
};

// =======================================================
// --- UI Event Listeners (Internal to UI) ---
// =======================================================

function attachInternalUiListeners() {
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
            checkFormValidity();
        });
    }

    // Real-time validation
    if (nameInput) nameInput.addEventListener('input', checkFormValidity);
    if (emailInput) emailInput.addEventListener('input', checkFormValidity);
    if (phoneInput) phoneInput.addEventListener('input', checkFormValidity);
    if (gradeInput) gradeInput.addEventListener('input', checkFormValidity); // Blade-la illai
    if (routeInput) routeInput.addEventListener('change', checkFormValidity);

    // Blade-la illadha inputs-kaga listeners
    if (appUsernameInput) {
        appUsernameInput.addEventListener('input', () => {
            appUsernameInput.dataset.userEdited = 'true';
        });
    }
    if (appPasswordInput) {
        appPasswordInput.addEventListener('input', () => {
            appPasswordInput.dataset.userEdited = 'true';
        });
    }
}

// =======================================================
// --- Init Function ---
// =======================================================

export function init() {
    cacheDOMElements();
    attachInternalUiListeners();
}