/**
 * @file
 * Handles all event listeners for the Students Management page.
 * Connects UI events (from studentUi.js) to API calls (from studentApi.js).
 *
 * @license GNU
 */

// Common utilities
import { showAlert } from '../utils.js';

// API module
import * as studentApi from './studentApi.js';

// UI module
import * as ui from './studentUi.js';

/**
 * Initializes all event listeners for the page.
 */
export function init() {

    // --- State variables ---
    let currentOtpUserId = null;
    let currentOtpUserEmail = null;
    let currentResetStudentId = null;
    let currentResetStudentName = null;
    let currentToggleSwitch = null; 
    let otpTimerInterval = null;

    // =======================================================
    // --- Add/Edit Student Modal Functions ---
    // =======================================================

    if (ui.openAddStudentModal) {
        ui.openAddStudentModal.addEventListener('click', () => {
            ui.resetStudentModal();
            if (ui.addStudentModal) ui.addStudentModal.style.display = 'flex';
            ui.checkFormValidity();
        });
    }

    if (ui.closeAddStudentModal) ui.closeAddStudentModal.addEventListener('click', ui.closeStudentModal);
    if (ui.cancelAddStudentModal) ui.cancelAddStudentModal.addEventListener('click', ui.closeStudentModal);

    if (ui.addStudentModal) {
        ui.addStudentModal.addEventListener('click', function(event) {
            if (event.target === ui.addStudentModal) {
                ui.closeStudentModal();
            }
        });
    }

    // =======================================================
    // --- Global Click Event Delegation ---
    // =======================================================
    document.addEventListener('click', function(event) {

        // --- 1. Status Toggle (Verified -> Pending) ---
        const statusToggleButton = event.target.closest('.js-status-toggle');
        if (statusToggleButton) {
            event.preventDefault();
            currentToggleSwitch = statusToggleButton; 
            const userName = statusToggleButton.getAttribute('data-user-name');
            if (ui.statusConfirmText) ui.statusConfirmText.innerText = `Are you sure you want to set ${userName} to 'Pending'? This will disable their login.`;
            if (ui.statusConfirmModal) ui.statusConfirmModal.style.display = 'flex';
        }

        // --- 2. Send OTP Button Click (Pending -> Verified) ---
        const otpButton = event.target.closest('.js-send-otp');
        if (otpButton) {
            event.preventDefault();
            currentOtpUserId = otpButton.getAttribute('data-user-id');
            currentOtpUserEmail = otpButton.getAttribute('data-user-email');
            if (ui.otpConfirmPrimaryText) ui.otpConfirmPrimaryText.innerText = `Send verification OTP to ${currentOtpUserEmail}?`;
            if (ui.otpConfirmModal) ui.otpConfirmModal.style.display = 'flex';
        }

        // --- 3. Edit Student Button Click ---
        const editButton = event.target.closest('.js-edit-student');
        if (editButton) {
            event.preventDefault(); 
            ui.resetStudentModal();

            const id = editButton.getAttribute('data-id');
            const name = editButton.getAttribute('data-name');
            const email = editButton.getAttribute('data-email');
            const phone = editButton.getAttribute('data-phone');
            const grade = editButton.getAttribute('data-grade'); // Blade-la illai
            const route = editButton.getAttribute('data-route-name'); 
            const photoPath = editButton.getAttribute('data-photo');

            if (ui.driverModalTitle) ui.driverModalTitle.innerText = 'Edit Student';
            if (ui.studentModalSubmitBtn) ui.studentModalSubmitBtn.innerText = 'Update Student';
            if (ui.studentForm) ui.studentForm.action = '/students/update';
            if (ui.studentIdInput) ui.studentIdInput.value = id;

            if (ui.nameInput) ui.nameInput.value = name;
            if (ui.emailInput) ui.emailInput.value = email;
            if (ui.phoneInput) ui.phoneInput.value = phone;
            if (ui.gradeInput) ui.gradeInput.value = grade; // Blade-la illai
            if (ui.routeInput) ui.routeInput.value = route; 

            if (ui.photoPreview) {
                 if (photoPath && photoPath !== '') {
                    ui.photoPreview.style.backgroundImage = `url('${photoPath}')`;
                    ui.photoPreview.style.borderStyle = 'solid';
                } else {
                    ui.photoPreview.style.backgroundImage = `url('https://api.dicebear.com/7.x/initials/svg?seed=${encodeURIComponent(name)}&backgroundColor=282c34&fontColor=86efac')`;
                    ui.photoPreview.style.borderStyle = 'solid';
                }
            }
            if (ui.addStudentModal) ui.addStudentModal.style.display = 'flex';
            if (ui.studentModalSubmitBtn) ui.studentModalSubmitBtn.disabled = false;
        }

        // --- 4. Delete Student Button Click ---
        const deleteButton = event.target.closest('.js-delete-student');
        if (deleteButton) {
            event.preventDefault();
            const deleteId = deleteButton.getAttribute('data-delete-id');
            const deleteUrl = deleteButton.getAttribute('data-delete-url');
            if (ui.confirmDeleteBtn) {
                ui.confirmDeleteBtn.setAttribute('data-id', deleteId);
                ui.confirmDeleteBtn.setAttribute('data-url', deleteUrl);
            }
            if (ui.deleteDriverModal) ui.deleteDriverModal.style.display = 'flex';
        }

        // --- 5. Reset Password Button Click ---
        const resetPasswordButton = event.target.closest('.js-reset-password');
        if (resetPasswordButton) {
            event.preventDefault();
            currentResetStudentId = resetPasswordButton.getAttribute('data-id');
            currentResetStudentName = resetPasswordButton.getAttribute('data-name');
            if (ui.resetPasswordText) ui.resetPasswordText.innerText = `Are you sure you want to reset the password for ${currentResetStudentName}?`;
            if (ui.resetPasswordModal) ui.resetPasswordModal.style.display = 'flex';
        }
    });

    // =======================================================
    // --- Delete Modal Logic (AJAX) ---
    // =======================================================

    if (ui.cancelDeleteModal) {
        ui.cancelDeleteModal.addEventListener('click', ui.closeDeleteModal);
    }

    if (ui.deleteDriverModal) {
        ui.deleteDriverModal.addEventListener('click', (e) => {
            if (e.target === ui.deleteDriverModal) ui.closeDeleteModal();
        });
    }

    if (ui.confirmDeleteBtn) {
        ui.confirmDeleteBtn.addEventListener('click', async () => {
            const deleteId = ui.confirmDeleteBtn.getAttribute('data-id');
            const deleteUrl = ui.confirmDeleteBtn.getAttribute('data-url'); 

            if (!deleteId || !deleteUrl) return;

            ui.confirmDeleteBtn.disabled = true;
            ui.confirmDeleteBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2" style="animation: spin 1s linear infinite;">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>
                <span>Deleting...</span>`;

            if (!document.getElementById('animate-spin-style')) {
                const style = document.createElement('style');
                style.id = 'animate-spin-style';
                style.innerHTML = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
                document.head.appendChild(style);
            }

            try {
                const data = await studentApi.deleteStudent(deleteUrl, deleteId);
                if (data.success) {
                    showAlert(data.message, 'success');
                    ui.closeDeleteModal();
                    setTimeout(() => { location.reload(); }, 1500); 
                } else {
                    showAlert(data.message, 'danger');
                    ui.closeDeleteModal();
                }
            } catch (error) {
                console.error('Delete Error:', error);
                showAlert((error.message || 'An unknown error occurred.') + ' Please try again.', 'danger');
                ui.closeDeleteModal();
            } finally {
                ui.confirmDeleteBtn.disabled = false;
                ui.confirmDeleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    <span>Delete</span>`;
            }
        });
    }

    // =======================================================
    // --- Form Submit (Add/Edit) AJAX Logic ---
    // =======================================================
    if (ui.studentForm) {
        ui.studentForm.addEventListener('submit', async function(event) {
            event.preventDefault(); 

            const isNameValid = ui.validateName();
            const isEmailValid = ui.validateEmail();
            const isPhoneValid = ui.validatePhone();
            const isGradeValid = ui.validateGrade();
            const isRouteValid = ui.validateRoute();
            const isPhotoValid = ui.validatePhoto();
            const isAppUsernameValid = ui.validateAppUsername();
            const isAppPasswordValid = ui.validateAppPassword();

            if (!isNameValid || !isEmailValid || !isPhoneValid || !isGradeValid || !isRouteValid || !isPhotoValid || !isAppUsernameValid || !isAppPasswordValid) {
                showAlert('Please fix the errors in the form.', 'danger');
                return;
            }

            ui.studentModalSubmitBtn.disabled = true;
            ui.studentModalSubmitBtn.innerText = 'Submitting...';

            ui.clearAllStudentFormErrors();

            const formData = new FormData(ui.studentForm);
            const url = ui.studentForm.action;

            try {
                const data = await studentApi.submitStudentForm(url, formData);

                if (data.success) {
                    showAlert(data.message, 'success');
                    ui.closeStudentModal();
                    setTimeout(() => { location.reload(); }, 1500);
                } 
            } catch (error) {
                console.error('Submit Error:', error);
                if (error && typeof error === 'object' && 'message' in error) {
                    showAlert(error.message || 'An error occurred. Please try again.', 'danger');
                    if (error.errors) {
                        if (error.errors.name) ui.showError(ui.nameInput, ui.nameError, error.errors.name);
                        if (error.errors.email) ui.showError(ui.emailInput, ui.emailError, error.errors.email);
                        if (error.errors.phone) ui.showError(ui.phoneInput, ui.phoneError, error.errors.phone);
                        if (error.errors.grade) ui.showError(ui.gradeInput, ui.gradeError, error.errors.grade);
                        if (error.errors.route_name || error.errors.route) {
                            ui.showError(ui.routeInput, ui.routeError, error.errors.route_name || error.errors.route);
                        }
                        if (error.errors.photo) ui.showError(ui.uploadButton, ui.photoError, error.errors.photo);
                        if (error.errors.app_username) ui.showError(ui.appUsernameInput, ui.appUsernameError, error.errors.app_username);
                        if (error.errors.app_password) ui.showError(ui.appPasswordInput, ui.appPasswordError, error.errors.app_password);
                    }
                } else {
                    showAlert('An unknown error occurred. Please try again.', 'danger');
                }
            } finally {
                ui.studentModalSubmitBtn.disabled = false;
                const isEditMode = ui.studentIdInput && ui.studentIdInput.value !== '';
                ui.studentModalSubmitBtn.innerText = isEditMode ? 'Update Student' : 'Submit Student';
            }
        });
    }

    // =======================================================
    // --- Status Toggle Confirmation ---
    // =======================================================

    if (ui.confirmStatusChangeBtn) {
        ui.confirmStatusChangeBtn.addEventListener('click', async () => {
            if (currentToggleSwitch) {
                const studentId = currentToggleSwitch.getAttribute('data-user-id');

                try {
                    const data = await studentApi.updateStudentStatus(studentId, '0');
                    if (data.success) {
                        showAlert(data.message, 'success');
                        setTimeout(() => location.reload(), 1500); 
                    } else {
                        showAlert(data.message, 'danger');
                    }
                } catch (err) {
                    showAlert('An error occurred.', 'danger');
                }
                ui.closeStatusModal();
                currentToggleSwitch = null; 
            }
        });
    }

    if (ui.cancelStatusChangeBtn) {
        ui.cancelStatusChangeBtn.addEventListener('click', () => {
            ui.closeStatusModal();
            currentToggleSwitch = null; 
        });
    }

    if (ui.statusConfirmModal) {
        ui.statusConfirmModal.addEventListener('click', (e) => {
            if (e.target === ui.statusConfirmModal) {
                ui.closeStatusModal();
                currentToggleSwitch = null; 
            }
        });
    }

    // =======================================================
    // --- OTP Modal 1: Confirmation ("Send OTP?") ---
    // =======================================================

    const resetOtpConfirmState = () => {
        ui.closeOtpConfirmModal();
        currentOtpUserId = null;
        currentOtpUserEmail = null;
    }

    if (ui.cancelOtpConfirmBtn) {
        ui.cancelOtpConfirmBtn.addEventListener('click', resetOtpConfirmState);
    }

    if (ui.otpConfirmModal) {
        ui.otpConfirmModal.addEventListener('click', (e) => {
            if (e.target === ui.otpConfirmModal) resetOtpConfirmState();
        });
    }

    if (ui.confirmOtpSendBtn) {
        ui.confirmOtpSendBtn.addEventListener('click', () => {
            if (currentOtpUserId) {
                if (ui.otpUserIdInput) ui.otpUserIdInput.value = currentOtpUserId;
                ui.showOtpEntryModal();
                sendOtpRequest(currentOtpUserId); 
            }
            resetOtpConfirmState();
        });
    }

    // =======================================================
    // --- OTP Modal 2: Entry ("Enter Code") ---
    // =======================================================

    const resetOtpEntryState = () => {
        ui.closeOtpEntryModal(otpTimerInterval);
        clearInterval(otpTimerInterval);
        otpTimerInterval = null;
    }

    if (ui.closeOtpModal) ui.closeOtpModal.addEventListener('click', resetOtpEntryState);
    if (ui.otpModal) ui.otpModal.addEventListener('click', (e) => {
        if (e.target === ui.otpModal) resetOtpEntryState();
    });

    async function sendOtpRequest(userId) {
        clearInterval(otpTimerInterval); 
        otpTimerInterval = ui.startOtpTimer(60); 

        if (ui.otpMessage) {
             ui.otpMessage.innerText = 'Sending OTP...';
             ui.otpMessage.style.color = 'hsl(var(--muted-foreground))';
        }
        if (ui.otpError) ui.otpError.innerText = ''; 

        try {
            const data = await studentApi.sendOtp(userId);
            if (data.success) {
                if (ui.otpMessage) {
                    ui.otpMessage.innerText = data.message;
                    ui.otpMessage.style.color = 'hsl(var(--success))';
                }
            } else {
                clearInterval(otpTimerInterval);
                if (ui.otpTimer) ui.otpTimer.innerText = '';
                if (ui.otpResendBtn) ui.otpResendBtn.disabled = false;
                if (ui.otpMessage) {
                    ui.otpMessage.innerText = data.message;
                    ui.otpMessage.style.color = 'hsl(var(--destructive))';
                }
            }
        } catch (err) {
            if (ui.otpMessage) {
                ui.otpMessage.innerText = 'Error sending OTP.';
                ui.otpMessage.style.color = 'hsl(var(--destructive))';
            }
        }
    }

    if (ui.otpResendBtn) {
        ui.otpResendBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const userId = ui.otpUserIdInput.value;
            if (userId) {
                sendOtpRequest(userId);
            }
        });
    }

    if (ui.otpForm) {
        ui.otpForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const otp = ui.otpInput.value;
            const userId = ui.otpUserIdInput.value;

            if (otp.length < 6) {
                if (ui.otpError) ui.otpError.innerText = 'OTP must be 6 digits.';
                return;
            }
            if (ui.otpError) ui.otpError.innerText = '';

            try {
                const data = await studentApi.verifyOtp(userId, otp);
                if (data.success) {
                    showAlert('Student verified successfully!', 'success'); 
                    resetOtpEntryState();
                    setTimeout(() => location.reload(), 1500); 
                } else {
                    if (ui.otpError) ui.otpError.innerText = data.message;
                    if (data.message.includes('locked')) {
                        setTimeout(() => resetOtpEntryState(), 2000);
                    }
                }
            } catch (err) {
                if (ui.otpError) ui.otpError.innerText = 'An error occurred during verification.';
            }
        });
    }

    // =======================================================
    // --- Reset Password Modal Logic ---
    // =======================================================

    const resetResetPasswordState = () => {
        ui.closeResetPasswordModal();
        currentResetStudentId = null;
        currentResetStudentName = null;
    }

    if (ui.cancelResetPasswordBtn) {
        ui.cancelResetPasswordBtn.addEventListener('click', resetResetPasswordState);
    }

    if (ui.resetPasswordModal) {
        ui.resetPasswordModal.addEventListener('click', (e) => {
            if (e.target === ui.resetPasswordModal) resetResetPasswordState();
        });
    }

    if (ui.confirmResetPasswordBtn) {
        ui.confirmResetPasswordBtn.addEventListener('click', async () => {
            if (!currentResetStudentId) return;

            ui.confirmResetPasswordBtn.disabled = true;
            ui.confirmResetPasswordBtn.innerText = 'Resetting...';

            try {
                const data = await studentApi.resetPassword(currentResetStudentId);
                if (data.success) {
                    resetResetPasswordState();
                    if (ui.newPasswordText) ui.newPasswordText.value = data.new_password;
                    if (ui.showNewPasswordModal) ui.showNewPasswordModal.style.display = 'flex';
                    showAlert(data.message, 'success'); 
                } else {
                    showAlert(data.message || 'An error occurred.', 'danger'); 
                    resetResetPasswordState();
                }
            } catch (err) {
                showAlert('An unknown error occurred.', 'danger'); 
                resetResetPasswordState();
            } finally {
                ui.confirmResetPasswordBtn.disabled = false;
                ui.confirmResetPasswordBtn.innerText = 'Reset Password';
            }
        });
    }

    // --- 'Show New Password' Modal ---
    if (ui.closeNewPasswordModal) {
        ui.closeNewPasswordModal.addEventListener('click', ui.closeShowNewPasswordModal);
    }

    if (ui.showNewPasswordModal) {
        ui.showNewPasswordModal.addEventListener('click', (e) => {
            if (e.target === ui.showNewPasswordModal) {
                 ui.closeShowNewPasswordModal();
            }
        });
    }

} // End of init function