/**
 * @file
 * Handles all event listeners for the Drivers Management page.
 * Connects UI events (from driverUi.js) to API calls (from driverApi.js).
 *
 * @license GNU
 */

// Common utilities
import { appendCsrf, showAlert } from '../utils.js';

// API module
import * as driverApi from './driverApi.js';

// UI module
import * as ui from './driverUi.js';

/**
 * Initializes all event listeners for the page.
 */
export function init() {

    // --- State variables (Indha page-oda state inga irukku) ---
    let currentOtpUserId = null;
    let currentOtpUserEmail = null;
    let currentResetDriverId = null;
    let currentResetDriverName = null;
    let currentToggleSwitch = null; 
    let otpTimerInterval = null;

    // =======================================================
    // --- Add/Edit Driver Modal Functions ---
    // =======================================================

    // --- Open 'Add Driver' Modal ---
    if (ui.openAddDriverModal) {
        ui.openAddDriverModal.addEventListener('click', () => {
            ui.resetDriverModal();
            if (ui.addDriverModal) ui.addDriverModal.style.display = 'flex';
            ui.checkFormValidity();
        });
    }

    // --- Close 'Add Driver' Modal (Button/Cancel) ---
    if (ui.closeAddDriverModal) ui.closeAddDriverModal.addEventListener('click', ui.closeDriverModal);
    if (ui.cancelAddDriverModal) ui.cancelAddDriverModal.addEventListener('click', ui.closeDriverModal);

    // --- Close 'Add Driver' Modal (Overlay Click) ---
    if (ui.addDriverModal) {
        ui.addDriverModal.addEventListener('click', function(event) {
            if (event.target === ui.addDriverModal) {
                ui.closeDriverModal();
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
            ui.resetDriverModal();

            const id = editButton.getAttribute('data-id');
            const name = editButton.getAttribute('data-name');
            const email = editButton.getAttribute('data-email');
            const phone = editButton.getAttribute('data-phone');
            const photoPath = editButton.getAttribute('data-photo');

            if (ui.driverModalTitle) ui.driverModalTitle.innerText = 'Edit Driver';
            if (ui.driverModalSubmitBtn) ui.driverModalSubmitBtn.innerText = 'Update Driver';
            if (ui.driverForm) ui.driverForm.action = '/drivers/update';
            if (ui.driverIdInput) ui.driverIdInput.value = id;
            if (ui.nameInput) ui.nameInput.value = name;
            if (ui.emailInput) ui.emailInput.value = email;
            if (ui.phoneInput) ui.phoneInput.value = phone;

            if (ui.photoPreview) {
                 if (photoPath && photoPath !== '') {
                    ui.photoPreview.style.backgroundImage = `url('${photoPath}')`;
                    ui.photoPreview.style.borderStyle = 'solid';
                } else {
                    ui.photoPreview.style.backgroundImage = `url('https://api.dicebear.com/7.x/initials/svg?seed=${encodeURIComponent(name)}&backgroundColor=282c34&fontColor=86efac')`;
                    ui.photoPreview.style.borderStyle = 'solid';
                }
            }
            if (ui.addDriverModal) ui.addDriverModal.style.display = 'flex';
            if (ui.driverModalSubmitBtn) ui.driverModalSubmitBtn.disabled = false;
        }

        // --- 2. Delete Driver Button Click ---
        const deleteButton = event.target.closest('.js-delete-driver');
        if (deleteButton) {
            event.preventDefault();
            const deleteId = deleteButton.getAttribute('data-delete-id');
            const deleteUrl = deleteButton.getAttribute('data-delete-url');

            if (ui.confirmDeleteBtn) {
                ui.confirmDeleteBtn.setAttribute('data-id', deleteId); 
                ui.confirmDeleteBtn.setAttribute('data-url', deleteUrl);
            }
            if (ui.deleteDriverModal) {
                ui.deleteDriverModal.style.display = 'flex';
            }
        }

        // --- 3. Reset Password Button Click ---
        const resetPasswordButton = event.target.closest('.js-reset-password');
        if (resetPasswordButton) {
            event.preventDefault();
            currentResetDriverId = resetPasswordButton.getAttribute('data-id');
            currentResetDriverName = resetPasswordButton.getAttribute('data-name');

            if (ui.resetPasswordText) {
                ui.resetPasswordText.innerText = `Are you sure you want to reset the password for ${currentResetDriverName}?`;
            }
            if (ui.resetPasswordModal) {
                ui.resetPasswordModal.style.display = 'flex';
            }
        }

        // --- 4. Status Toggle Button Click ---
        const statusToggleButton = event.target.closest('.js-status-toggle');
        if (statusToggleButton) {
            event.preventDefault();
            currentToggleSwitch = statusToggleButton;
            const userName = statusToggleButton.getAttribute('data-user-name');
            const newStatusText = 'Not Verified'; 

            if (ui.statusConfirmText) ui.statusConfirmText.innerText = `Are you sure you want to set ${userName} as ${newStatusText}? This will disable their login.`;

            if(ui.statusModalIcon) {
                ui.statusModalIcon.style.backgroundColor = 'hsla(var(--warning), 0.15)';
                ui.statusModalIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--warning))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.46 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
            }
            if(ui.confirmStatusChangeBtn) {
                ui.confirmStatusChangeBtn.style.backgroundColor = 'hsl(var(--warning))';
                ui.confirmStatusChangeBtn.style.color = '#000';
            }
            if (ui.statusConfirmModal) ui.statusConfirmModal.style.display = 'flex';
        }

        // --- 5. Send OTP Button Click ---
        const otpButton = event.target.closest('.js-send-otp');
        if (otpButton) {
            event.preventDefault();
            currentOtpUserId = otpButton.getAttribute('data-user-id');
            currentOtpUserEmail = otpButton.getAttribute('data-user-email');

            if (ui.otpConfirmPrimaryText) {
                ui.otpConfirmPrimaryText.innerText = `Send verification OTP to ${currentOtpUserEmail}?`;
            }
            if (ui.otpConfirmModal) {
                ui.otpConfirmModal.style.display = 'flex';
            }
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
                const data = await driverApi.deleteDriver(deleteUrl, deleteId);

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
    if (ui.driverForm) {
        ui.driverForm.addEventListener('submit', async function(event) {
            event.preventDefault(); 

            const isNameValid = ui.validateName();
            const isEmailValid = ui.validateEmail();
            const isPhoneValid = ui.validatePhone();
            const isPhotoValid = ui.validatePhoto();

            if (!isNameValid || !isEmailValid || !isPhoneValid || !isPhotoValid) {
                showAlert('Please fix the errors in the form.', 'danger');
                return;
            }

            ui.driverModalSubmitBtn.disabled = true;
            ui.driverModalSubmitBtn.innerText = 'Submitting...';

            ui.clearAllDriverFormErrors();

            const formData = new FormData(ui.driverForm);
            appendCsrf(formData);
            const url = ui.driverForm.action;

            try {
                const data = await driverApi.submitDriverForm(url, formData);

                if (data.success) {
                    showAlert(data.message, 'success');
                    ui.closeDriverModal();
                    setTimeout(() => {
                        location.reload(); 
                    }, 1500);
                } 
            } catch (error) {
                console.error('Submit Error:', error);
                let errorMessage = 'An unknown error occurred. Please try again.';
                if (error && typeof error === 'object') {
                    errorMessage = error.message || errorMessage;
                    if (error.errors) {
                        if (error.errors.name) ui.showError(ui.nameInput, ui.nameError, error.errors.name[0]);
                        if (error.errors.email) ui.showError(ui.emailInput, ui.emailError, error.errors.email[0]);
                        if (error.errors.phone) ui.showError(ui.phoneInput, ui.phoneError, error.errors.phone[0]);
                        if (error.errors.photo) ui.showError(ui.uploadButton, ui.photoError, error.errors.photo[0]);
                    }
                }
                showAlert(errorMessage, 'danger');
            } finally {
                ui.driverModalSubmitBtn.disabled = false;
                const isEditMode = ui.driverIdInput && ui.driverIdInput.value !== '';
                ui.driverModalSubmitBtn.innerText = isEditMode ? 'Update Driver' : 'Submit Driver';
            }
        });
    }

    // =======================================================
    // --- Status Toggle Confirmation ---
    // =======================================================

    if (ui.confirmStatusChangeBtn) {
        ui.confirmStatusChangeBtn.addEventListener('click', async () => {
            if (currentToggleSwitch) {
                const driverId = currentToggleSwitch.getAttribute('data-user-id');
                const newStatus = 0; 

                try {
                    const data = await driverApi.updateDriverStatus(driverId, newStatus);

                    if (data.success) {
                        showAlert(data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert(data.message, 'danger');
                    }
                } catch (err) {
                    showAlert('An error occurred while updating status.', 'danger');
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
            const data = await driverApi.sendOtp(userId);

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
            clearInterval(otpTimerInterval);
            if (ui.otpTimer) ui.otpTimer.innerText = '';
            if (ui.otpResendBtn) ui.otpResendBtn.disabled = false;
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
                const data = await driverApi.verifyOtp(userId, otp);

                if (data.success) {
                    showAlert(data.message, 'success');
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
        currentResetDriverId = null;
        currentResetDriverName = null;
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
            if (!currentResetDriverId) return;

            ui.confirmResetPasswordBtn.disabled = true;
            ui.confirmResetPasswordBtn.innerText = 'Resetting...';

            try {
                const data = await driverApi.resetPassword(currentResetDriverId);

                if (data.success) {
                    resetResetPasswordState();
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
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