// =======================================================
// PUTHUSA: OTP Modal kaga elements
// =======================================================
// Modal 1: Confirmation ("Anuppattuma?")
const otpConfirmModal = document.getElementById('otpConfirmModal');
const otpConfirmPrimaryText = document.getElementById('otpConfirmPrimaryText');
const confirmOtpSendBtn = document.getElementById('confirmOtpSend');
const cancelOtpConfirmBtn = document.getElementById('cancelOtpConfirm');
let currentOtpUserId = null;
let currentOtpUserEmail = null;

// Modal 2: Entry ("Code-ah podunga")
const otpModal = document.getElementById('otpModal'); 
const otpForm = document.getElementById('otpForm');
const otpInput = document.getElementById('otp_code');
const otpError = document.getElementById('otpError');
const otpMessage = document.getElementById('otpMessage');
const otpResendBtn = document.getElementById('otpResendBtn');
const otpTimer = document.getElementById('otpTimer');
const closeOtpModal = document.getElementById('closeOtpModal');
const otpUserIdInput = document.getElementById('otp_user_id'); // Hidden input
let otpTimerInterval = null;

// =======================================================
// --- PUTHU MAATRAM: Reset Password Modal kaga elements ---
// =======================================================
const resetPasswordModal = document.getElementById('resetPasswordModal');
const resetPasswordText = document.getElementById('resetPasswordText');
const confirmResetPasswordBtn = document.getElementById('confirmResetPassword');
const cancelResetPasswordBtn = document.getElementById('cancelResetPassword');
let currentResetStudentId = null;
let currentResetStudentName = null;

// =======================================================
// --- PUTHU MAATRAM: Show New Password Modal elements ---
// =======================================================
const showNewPasswordModal = document.getElementById('showNewPasswordModal');
const newPasswordText = document.getElementById('newPasswordText');
const closeNewPasswordModal = document.getElementById('closeNewPasswordModal');
// --- MAATRAM MUDINJATHU ---


document.addEventListener('DOMContentLoaded', function() {
    
    // --- Modal Controls (Add/Edit) ---
    const modal = document.getElementById('addStudentModal');
    const studentForm = document.getElementById('studentForm');
    const studentIdInput = document.getElementById('student_id');
    const openBtn = document.getElementById('openAddStudentModal');
    const closeBtn = document.getElementById('closeAddStudentModal');
    const cancelBtn = document.getElementById('cancelAddStudentModal');

    // --- Modal Form Elements ---
    const modalTitle = document.getElementById('studentModalTitle');
    const modalSubmitBtn = document.getElementById('studentModalSubmitBtn');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const gradeInput = document.getElementById('grade'); 
    const routeInput = document.getElementById('route_name'); 
    const appUsernameInput = document.getElementById('app_username');
    const appPasswordInput = document.getElementById('app_password');
    
    // --- Photo Upload Elements ---
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const uploadButton = document.getElementById('upload-button');

    // --- Delete Modal Elements ---
    const deleteModal = document.getElementById('deleteStudentModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // --- Status Confirm Modal Elements ---
    const statusModal = document.getElementById('statusConfirmModal');
    const statusConfirmText = document.getElementById('statusConfirmText');
    const confirmStatusChangeBtn = document.getElementById('confirmStatusChange');
    const cancelStatusChangeBtn = document.getElementById('cancelStatusChange');
    let currentToggleSwitch = null; 

    // --- Validation elements ---
    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    const phoneError = document.getElementById('phoneError');
    const gradeError = document.getElementById('gradeError');
    const routeError = document.getElementById('route_nameError');
    const photoError = document.getElementById('photoError');
    const appUsernameError = document.getElementById('app_usernameError');
    const appPasswordError = document.getElementById('app_passwordError');
    
    // Validation patterns (Regex)
    const nameRegex = /^[a-zA-Z\s\.]{3,}$/; // Min 3 chars
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Standard email
    const phoneRegex = /^\+?[0-9\s-]{10,15}$/; // 10-15 digits
    const textRegex = /.{2,}/; // Min 2 chars
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    // --- Validation Helper Functions ---

    function showError(input, errorElement, message) {
        if (input && input.classList) { // Null check pannurom
            input.classList.add('is-invalid');
        }
        if (errorElement) { // Null check pannurom
            errorElement.innerText = message;
        }
        setTimeout(() => clearError(input, errorElement), 3000);
    }

    function clearError(input, errorElement) {
        if (input && input.classList) { // Null check pannurom
            input.classList.remove('is-invalid');
        }
        if (errorElement) { // Null check pannurom
            errorElement.innerText = '';
        }
    }

    function clearAllErrors() {
        clearError(nameInput, nameError);
        clearError(emailInput, emailError);
        clearError(phoneInput, phoneError);
        clearError(gradeInput, gradeError);
        clearError(routeInput, routeError);
        clearError(uploadButton, photoError);
        clearError(appUsernameInput, appUsernameError);
        clearError(appPasswordInput, appPasswordError);
    }

    // --- Ovvoru input-kum thani validation function ---

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
    
    function validateGrade() {
        if (!gradeInput) return true;
        if (gradeInput.value.trim() === '') {
            showError(gradeInput, gradeError, 'Grade is required.');
            return false;
        }
        clearError(gradeInput, gradeError);
        return true;
    }
    
    function validateRoute() {
        if (!routeInput) return false;
        if (routeInput.value.trim() === '') {
            showError(routeInput, routeError, 'Please select a bus route.');
            return false;
        }
        clearError(routeInput, routeError);
        return true;
    }

    function generateDefaultUsername(emailValue) {
        const value = (emailValue || '').trim();
        return value ? value.toLowerCase() : '';
    }

    function generateDefaultPassword(nameValue, phoneValue) {
        const sanitizedName = (nameValue || '').trim().toLowerCase().replace(/\s+/g, '');
        const digits = String(phoneValue || '').replace(/\D/g, '');
        if (!sanitizedName || digits.length < 4) {
            return '';
        }
        return `${sanitizedName}@${digits.slice(-4)}`;
    }

    function ensureAppCredentialDefaults(options = {}) {
        const { force = false } = options;
        const emailValue = emailInput ? emailInput.value : '';
        const nameValue = nameInput ? nameInput.value : '';
        const phoneValue = phoneInput ? phoneInput.value : '';

        if (appUsernameInput) {
            const shouldUpdateUsername = force || !appUsernameInput.value.trim() || appUsernameInput.dataset.userEdited !== 'true';
            if (shouldUpdateUsername) {
                const generatedUsername = generateDefaultUsername(emailValue);
                if (generatedUsername) {
                    appUsernameInput.value = generatedUsername;
                    delete appUsernameInput.dataset.userEdited;
                } else if (force) {
                    appUsernameInput.value = '';
                }
            }
        }

        if (appPasswordInput) {
            const shouldUpdatePassword = force || !appPasswordInput.value.trim() || appPasswordInput.dataset.userEdited !== 'true';
            if (shouldUpdatePassword) {
                const generatedPassword = generateDefaultPassword(nameValue, phoneValue);
                if (generatedPassword) {
                    appPasswordInput.value = generatedPassword;
                    delete appPasswordInput.dataset.userEdited;
                } else if (force) {
                    appPasswordInput.value = '';
                }
            }
        }
    }

    function validatePhoto() {
        if (!photoInput) return true; // Photo input illana, valid nu sollidalam
        const file = photoInput.files[0];
        // Photo optional
        if (file) {
            if (file.size > MAX_FILE_SIZE) {
                showError(uploadButton, photoError, 'File is too large (Max 2MB).');
                return false;
            }
        }
        clearError(uploadButton, photoError);
        return true;
    }

    function validateAppUsername() {
        if (!appUsernameInput) return true;
        if (appUsernameInput.value.trim() === '') {
            showError(appUsernameInput, appUsernameError, 'App username is required.');
            return false;
        }
        clearError(appUsernameInput, appUsernameError);
        return true;
    }

    function validateAppPassword() {
        if (!appPasswordInput) return true;
        if (appPasswordInput.value.trim().length < 6) {
            showError(appPasswordInput, appPasswordError, 'App password must be at least 6 characters.');
            return false;
        }
        clearError(appPasswordInput, appPasswordError);
        return true;
    }

    function checkFormValidity() {
        if (!nameInput) return; // Add/Edit modal illatha page-la run aana
        
        // Clear panrathu kaga itha call panrom
        clearError(nameInput, nameError);
        clearError(emailInput, emailError);
        clearError(phoneInput, phoneError);
        clearError(gradeInput, gradeError);
        clearError(routeInput, routeError);
        clearError(uploadButton, photoError);
        clearError(appUsernameInput, appUsernameError);
        clearError(appPasswordInput, appPasswordError);

        ensureAppCredentialDefaults();

        const isNameValid = nameRegex.test(nameInput.value.trim());
        const isEmailValid = emailRegex.test(emailInput.value.trim());
        const isPhoneValid = phoneRegex.test(phoneInput.value.trim());
        const isGradeValid = gradeInput ? gradeInput.value.trim() !== '' : true;
        const isRouteValid = routeInput ? routeInput.value.trim() !== '' : true;
        const isPhotoValid = validatePhoto(); // Size mattum check pannum
        const isAppUsernameValid = appUsernameInput ? appUsernameInput.value.trim() !== '' : true;
        const isAppPasswordValid = appPasswordInput ? appPasswordInput.value.trim().length >= 6 : true;

        if (isNameValid && isEmailValid && isPhoneValid && isGradeValid && isRouteValid && isPhotoValid && isAppUsernameValid && isAppPasswordValid) {
            if (modalSubmitBtn) modalSubmitBtn.disabled = false;
        } else {
            if (modalSubmitBtn) modalSubmitBtn.disabled = true;
        }
    }


    // --- Modal Reset Function ---
    const resetModal = () => {
        if (modalTitle) modalTitle.innerText = 'Add New Student';
        if (modalSubmitBtn) modalSubmitBtn.innerText = 'Submit Student';
        
        if (studentForm) studentForm.reset();
        if (studentForm) studentForm.action = '/students/create';
        if (studentIdInput) studentIdInput.value = '';

        if (appUsernameInput) {
            delete appUsernameInput.dataset.userEdited;
            appUsernameInput.value = '';
        }
        if (appPasswordInput) {
            delete appPasswordInput.dataset.userEdited;
            appPasswordInput.value = '';
        }

        if (photoPreview) {
            photoPreview.style.backgroundImage = 'none';
            photoPreview.style.borderStyle = 'dashed'; 
        }

        clearAllErrors();
        if (modalSubmitBtn) modalSubmitBtn.disabled = true;
    };

    // "Add Student" button click panna
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            resetModal();
            if (modal) modal.style.display = 'flex';
            checkFormValidity();
        });
    }

    // Modal-a moodurathu
    const closeModal = () => {
        if (modal) modal.style.display = 'none'; 
        resetModal(); 
    }

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    }

    // =======================================================
    // MAATRAM: 'click' event listener
    // =======================================================
    document.addEventListener('click', function(event) {
        
        // --- 1. PUTHU PASSWORD TOGGLE (REMOVED) ---
        
        // --- 2. ADMIN-ODA STATUS TOGGLE (Verified -> Pending) ---
        const statusToggleButton = event.target.closest('.js-status-toggle');
        if (statusToggleButton) {
            event.preventDefault();
            currentToggleSwitch = statusToggleButton; // Button-ah save panrom
            const userName = statusToggleButton.getAttribute('data-user-name');
            
            if (statusConfirmText) statusConfirmText.innerText = `Are you sure you want to set ${userName} to 'Pending'? This will disable their login.`;
            
            if (statusModal) {
                statusModal.style.display = 'flex';
            }
        }
        
        // --- 3. PUTHU OTP BUTTON CLICK (Pending -> Verified) ---
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

        // --- 4. Edit Button Check ---
        const editButton = event.target.closest('.js-edit-student');
        if (editButton) {
            event.preventDefault(); 
            resetModal();
            
            const id = editButton.getAttribute('data-id');
            const name = editButton.getAttribute('data-name');
            const email = editButton.getAttribute('data-email');
            const phone = editButton.getAttribute('data-phone');
            const grade = editButton.getAttribute('data-grade'); 
            const route = editButton.getAttribute('data-route-name'); 
            const photoPath = editButton.getAttribute('data-photo');
            
            if (modalTitle) modalTitle.innerText = 'Edit Student';
            if (modalSubmitBtn) modalSubmitBtn.innerText = 'Update Student';
            
            if (studentForm) studentForm.action = '/students/update';
            if (studentIdInput) studentIdInput.value = id;
            
            if (nameInput) nameInput.value = name;
            if (emailInput) emailInput.value = email;
            if (phoneInput) phoneInput.value = phone;
            if (gradeInput) gradeInput.value = grade; 
            if (routeInput) routeInput.value = route; 

            if (photoPreview) {
                 if (photoPath && photoPath !== '') {
                    photoPreview.style.backgroundImage = `url('${photoPath}')`;
                    photoPreview.style.borderStyle = 'solid';
                } else {
                    photoPreview.style.backgroundImage = `url('https://api.dicebear.com/7.x/initials/svg?seed=${encodeURIComponent(name)}&backgroundColor=282c34&fontColor=86efac')`;
                    photoPreview.style.borderStyle = 'solid';
                }
            }
            if (modal) modal.style.display = 'flex';
            if (modalSubmitBtn) modalSubmitBtn.disabled = false;
        }

        // --- 5. Delete Button Check ---
        const deleteButton = event.target.closest('.js-delete-student');
        if (deleteButton) {
            event.preventDefault();
            // MAATRAM: data-delete-id-ah edukkrom
            const deleteId = deleteButton.getAttribute('data-delete-id');
            const deleteUrl = deleteButton.getAttribute('data-delete-url');

            if (confirmDeleteBtn) {
                confirmDeleteBtn.setAttribute('data-id', deleteId);
                confirmDeleteBtn.setAttribute('data-url', deleteUrl);
            }
            if (deleteModal) {
                deleteModal.style.display = 'flex';
            }
        }
        
        // =======================================================
        // --- PUTHU MAATRAM INGA: Reset Password Button Click ---
        // =======================================================
        const resetPasswordButton = event.target.closest('.js-reset-password');
        if (resetPasswordButton) {
            event.preventDefault();
            currentResetStudentId = resetPasswordButton.getAttribute('data-id');
            currentResetStudentName = resetPasswordButton.getAttribute('data-name');
            
            if (resetPasswordText) {
                resetPasswordText.innerText = `Are you sure you want to reset the password for ${currentResetStudentName}?`;
            }
            if (resetPasswordModal) {
                resetPasswordModal.style.display = 'flex';
            }
        }
        // --- MAATRAM MUDINJATHU ---
    });

    // --- Delete Modal Button Listeners ---
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => {
            if (deleteModal) deleteModal.style.display = 'none';
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-id');
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-url');
        });
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            const deleteId = confirmDeleteBtn.getAttribute('data-id');
            const deleteUrl = confirmDeleteBtn.getAttribute('data-url'); // POST URL

            if (deleteId && deleteUrl) {
                
                // PUTHU MAATRAM: Button-ah loading state-ku maathrom (Consistency fix)
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2" style="animation: spin 1s linear infinite;">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span>Deleting...</span>`;
                
                // CSS animation check
                if (!document.getElementById('animate-spin-style')) {
                    const style = document.createElement('style');
                    style.id = 'animate-spin-style';
                    style.innerHTML = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
                    document.head.appendChild(style);
                }

                // MAATRAM: POST request use panrom
                const formData = new FormData();
                formData.append('id', deleteId);
                appendCsrf(formData);

                // PUTHU MAATRAM: Fetch use panni AJAX call panrom (Consistency fix)
                fetch(deleteUrl, {
                    method: 'POST', // MAATRAM: POST method
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
                        if (deleteModal) deleteModal.style.display = 'none';
                        setTimeout(() => { location.reload(); }, 1500); 
                    } else {
                        // Error message (e.g., "Cannot delete student. They are assigned to route...")
                        showAlert(data.message, 'danger');
                        if (deleteModal) deleteModal.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    showAlert((error.message || 'An unknown error occurred.') + ' Please try again.', 'danger');
                    if (deleteModal) deleteModal.style.display = 'none';
                })
                .finally(() => {
                    // Button-ah pazhaya nilaikku kondu varom
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <span>Delete</span>`;
                    if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-id');
                    if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-url');
                });
            }
        });
    }

    if (deleteModal) {
        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                deleteModal.style.display = 'none';
                if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-id');
                if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-url');
            }
        });
    }

    // --- Photo Upload Logic ---
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
    
    [nameInput, emailInput, phoneInput].forEach(input => {
        if (input) {
            input.addEventListener('input', () => ensureAppCredentialDefaults());
            input.addEventListener('blur', () => ensureAppCredentialDefaults());
        }
    });

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

    // --- Real-time validation ---
    if (nameInput) nameInput.addEventListener('input', checkFormValidity);
    if (emailInput) emailInput.addEventListener('input', checkFormValidity);
    if (phoneInput) phoneInput.addEventListener('input', checkFormValidity);
    if (gradeInput) gradeInput.addEventListener('input', checkFormValidity);
    if (routeInput) routeInput.addEventListener('change', checkFormValidity);

    
    // --- Form Submit AJAX logic ('Add Student' / 'Edit Student') ---
    if (studentForm) {
        studentForm.addEventListener('submit', function(event) {
            event.preventDefault(); 

            ensureAppCredentialDefaults({ force: true });
            
            // Final validation check
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPhoneValid = validatePhone();
            const isGradeValid = validateGrade();
            const isRouteValid = validateRoute();
            const isPhotoValid = validatePhoto();
            const isAppUsernameValid = validateAppUsername();
            const isAppPasswordValid = validateAppPassword();

            if (!isNameValid || !isEmailValid || !isPhoneValid || !isGradeValid || !isRouteValid || !isPhotoValid || !isAppUsernameValid || !isAppPasswordValid) {
                showAlert('Please fix the errors in the form.', 'danger');
                return;
            }
            
            modalSubmitBtn.disabled = true;
            modalSubmitBtn.innerText = 'Submitting...';
            
            clearAllErrors();
            
            const formData = new FormData(studentForm);
            appendCsrf(formData);

            const url = studentForm.action;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: buildAjaxHeaders()
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    throw new Error(`Server returned non-JSON response: ${text.substring(0, 200)}`);
                }
                const data = await response.json();
                if (!response.ok) {
                    return Promise.reject(data);
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    closeModal();
                    setTimeout(() => {
                        location.reload(); 
                    }, 1500);

                } else {
                    showAlert(data.message, 'danger');
                    if (data.errors) {
                        if (data.errors.name) showError(nameInput, nameError, data.errors.name);
                        if (data.errors.email) showError(emailInput, emailError, data.errors.email);
                        if (data.errors.phone) showError(phoneInput, phoneError, data.errors.phone);
                        if (data.errors.grade) showError(gradeInput, gradeError, data.errors.grade);
                        if (data.errors.route_name || data.errors.route) {
                            showError(routeInput, routeError, data.errors.route_name || data.errors.route);
                        }
                        if (data.errors.photo) showError(uploadButton, photoError, data.errors.photo);
                        if (data.errors.app_username) showError(appUsernameInput, appUsernameError, data.errors.app_username);
                        if (data.errors.app_password) showError(appPasswordInput, appPasswordError, data.errors.app_password);
                    }
                    modalSubmitBtn.disabled = false;
                    modalSubmitBtn.innerText = modalTitle.innerText.includes('Edit') ? 'Update Student' : 'Submit Student';
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                // If error is an object with success/message, it's a server error response
                if (error && typeof error === 'object' && 'message' in error) {
                    showAlert(error.message || 'An error occurred. Please try again.', 'danger');
                    if (error.errors) {
                        if (error.errors.name) showError(nameInput, nameError, error.errors.name);
                        if (error.errors.email) showError(emailInput, emailError, error.errors.email);
                        if (error.errors.phone) showError(phoneInput, phoneError, error.errors.phone);
                        if (error.errors.grade) showError(gradeInput, gradeError, error.errors.grade);
                        if (error.errors.route_name || error.errors.route) {
                            showError(routeInput, routeError, error.errors.route_name || error.errors.route);
                        }
                        if (error.errors.photo) showError(uploadButton, photoError, error.errors.photo);
                        if (error.errors.app_username) showError(appUsernameInput, appUsernameError, error.errors.app_username);
                        if (error.errors.app_password) showError(appPasswordInput, appPasswordError, error.errors.app_password);
                    }
                } else {
                    showAlert('An unknown error occurred. Please try again.', 'danger');
                }
                modalSubmitBtn.disabled = false;
                modalSubmitBtn.innerText = modalTitle.innerText.includes('Edit') ? 'Update Student' : 'Submit Student';
            });
        });
    }

    
    // --- Status Toggle Confirmation (Admin) ---
    // Itha thirumba uncomment panrom
    const closeStatusModal = () => {
        if (statusModal) statusModal.style.display = 'none';
        currentToggleSwitch = null; 
    };

    if (confirmStatusChangeBtn) {
        confirmStatusChangeBtn.addEventListener('click', () => {
            if (currentToggleSwitch) {
                const studentId = currentToggleSwitch.getAttribute('data-user-id');
                
                // REAL FETCH CALL to update status
                const formData = new FormData();
                formData.append('id', studentId);
                formData.append('is_verified', '0');
                appendCsrf(formData);

                fetch('/students/update-status', { 
                    method: 'POST', 
                    body: formData,
                    headers: buildAjaxHeaders()
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        setTimeout(() => location.reload(), 1500); // Reload panna puthu RED button varum
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(err => showAlert('An error occurred.', 'danger'));
                
                closeStatusModal();
            }
        });
    }

    if (cancelStatusChangeBtn) {
        cancelStatusChangeBtn.addEventListener('click', closeStatusModal);
    }

    if (statusModal) {
        statusModal.addEventListener('click', function(event) {
            if (event.target === statusModal) {
                closeStatusModal();
            }
        });
    }

    // =======================================================
    // PUTHUSA: OTP MODAL (Confirmation + Entry) FUNCTIONS
    // =======================================================

    // --- OTP Confirm Modal (Modal 1) Listeners ---
    if (cancelOtpConfirmBtn) {
        cancelOtpConfirmBtn.addEventListener('click', () => {
            if (otpConfirmModal) otpConfirmModal.style.display = 'none';
            currentOtpUserId = null;
            currentOtpUserEmail = null;
        });
    }

    if (confirmOtpSendBtn) {
        confirmOtpSendBtn.addEventListener('click', () => {
            if (otpConfirmModal) otpConfirmModal.style.display = 'none';
            
            if (currentOtpUserId) {
                if (otpUserIdInput) otpUserIdInput.value = currentOtpUserId;
                showOtpModal(); // Ithu thaan antha OTP entry modal-ah kaattum
                sendOtpRequest(currentOtpUserId); // Ithu thaan OTP anuppum
            }
            currentOtpUserId = null;
            currentOtpUserEmail = null;
        });
    }
    
    if (otpConfirmModal) {
        otpConfirmModal.addEventListener('click', function(event) {
            if (event.target === otpConfirmModal) {
                if (otpConfirmModal) otpConfirmModal.style.display = 'none';
                currentOtpUserId = null;
                currentOtpUserEmail = null;
            }
        });
    }


    // --- OTP Entry Modal (Modal 2) Functions ---
    function showOtpModal() {
        if (otpModal) otpModal.style.display = 'flex';
    }
    
    function closeOtpModalFunc() {
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
    
    if (closeOtpModal) closeOtpModal.addEventListener('click', closeOtpModalFunc);
    if (otpModal) otpModal.addEventListener('click', (e) => {
        if (e.target === otpModal) closeOtpModalFunc();
    });

    // 1. Send OTP Request (AJAX)
    // Itha thirumba uncomment panrom
    function sendOtpRequest(userId) {
        const formData = new FormData();
        formData.append('student_id', userId);
        appendCsrf(formData);
        
        startOtpTimer(60); // Timer 60s kaga start panrom
        if (otpMessage) {
             otpMessage.innerText = 'Sending OTP...';
             otpMessage.style.color = 'hsl(var(--muted-foreground))';
        }
        if (otpError) otpError.innerText = ''; // Pazhaya error-ah clear pannu

        fetch('/students/send-otp', { 
            method: 'POST', 
            body: formData,
            headers: buildAjaxHeaders()
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (otpMessage) {
                    otpMessage.innerText = data.message;
                    otpMessage.style.color = 'hsl(var(--success))';
                }
            } else {
                // Timer stop pannu
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
        });
    }
    
    // 2. Resend OTP
    if (otpResendBtn) {
        otpResendBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const userId = otpUserIdInput.value;
            if (userId) {
                sendOtpRequest(userId);
            }
        });
    }
    
    // 3. OTP Timer
    function startOtpTimer(duration) {
        let timer = duration;
        if (otpResendBtn) otpResendBtn.disabled = true;
        
        clearInterval(otpTimerInterval); // Pazhaya timer-ah clear pannu
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

    // 4. Verify OTP (Form Submit)
    // Itha thirumba uncomment panrom
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
            formData.append('student_id', userId);
            formData.append('otp_code', otp);
            appendCsrf(formData);

            fetch('/students/verify-otp', {
                method: 'POST',
                body: formData,
                headers: buildAjaxHeaders()
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showAlert('Student verified successfully!', 'success');
                    closeOtpModalFunc();
                    setTimeout(() => location.reload(), 1500); // Page reload panna, puthu GREEN button varum
                } else {
                    if (otpError) otpError.innerText = data.message;
                    // Lock aayiduchu-na, modal-ah close pannidalam
                    if (data.message.includes('locked')) {
                        setTimeout(() => closeOtpModalFunc(), 2000);
                    }
                }
            })
            .catch(err => {
                if (otpError) otpError.innerText = 'An error occurred during verification.';
            });
        });
    }
    
    
    // =======================================================
    // --- PUTHU MAATRAM INGA: Reset Password Modal Logic ---
    // =======================================================
    
    // Modal-ah close pannum function
    const closeResetPasswordModal = () => {
        if (resetPasswordModal) resetPasswordModal.style.display = 'none';
        currentResetStudentId = null;
        currentResetStudentName = null;
    };
    
    // "Cancel" button click panna
    if (cancelResetPasswordBtn) {
        cancelResetPasswordBtn.addEventListener('click', closeResetPasswordModal);
    }
    
    // Velila click panna
    if (resetPasswordModal) {
        resetPasswordModal.addEventListener('click', (e) => {
            if (e.target === resetPasswordModal) closeResetPasswordModal();
        });
    }

    // "Confirm Reset" button click panna
    if (confirmResetPasswordBtn) {
        confirmResetPasswordBtn.addEventListener('click', () => {
            if (!currentResetStudentId) return;
            
            // Button-ah loading state-ku maathurom
            confirmResetPasswordBtn.disabled = true;
            confirmResetPasswordBtn.innerText = 'Resetting...';

            const formData = new FormData();
            formData.append('id', currentResetStudentId);
            appendCsrf(formData);

            fetch('/students/reset-password', { // Namma puthu route
                method: 'POST',
                body: formData,
                headers: buildAjaxHeaders({ Accept: 'application/json' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // --- ITHU THAAN ANTHA PUTHU LOGIC ---
                    // 1. Pazhaya alert-ah remove panrom
                    // alert(`Password for ${currentResetStudentName} has been reset.\n\nNew Password: ${data.new_password}\n\nPlease share this password with the student.`);
                    
                    // 2. Confirmation modal-ah close panrom
                    closeResetPasswordModal();
                    
                    // 3. Puthu password-ah input-la set panrom
                    if (newPasswordText) {
                        newPasswordText.value = data.new_password;
                    }
                    
                    // 4. Puthu modal-ah kaatrom
                    if (showNewPasswordModal) {
                        showNewPasswordModal.style.display = 'flex';
                    }
                    
                    // 5. Global alert-um kaatrom
                    showAlert(data.message, 'success');

                } else {
                    // Error aana, error alert kaatrom
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
    
    // --- PUTHU MAATRAM: Puthu password modal-ah close panrathu ---
    if (closeNewPasswordModal) {
        closeNewPasswordModal.addEventListener('click', () => {
            if (showNewPasswordModal) {
                showNewPasswordModal.style.display = 'none';
            }
            if (newPasswordText) {
                newPasswordText.value = ''; // Input-ah clear pannidrom
            }
        });
    }
    
    if (showNewPasswordModal) {
        showNewPasswordModal.addEventListener('click', (e) => {
            if (e.target === showNewPasswordModal) {
                 if (showNewPasswordModal) {
                    showNewPasswordModal.style.display = 'none';
                }
                if (newPasswordText) {
                    newPasswordText.value = ''; // Input-ah clear pannidrom
                }
            }
        });
    }
    // --- MAATRAM MUDINJATHU ---
    
});