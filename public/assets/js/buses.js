/**
 * Initializes all event listeners and logic for the Buses page.
 * Handles modal controls, form validation, photo preview, AJAX form submission, and delete operations.
 */
document.addEventListener('DOMContentLoaded', function() {
    
    const modal = document.getElementById('addBusModal');
    const openBtn = document.getElementById('openAddBusModal');
    const closeBtn = document.getElementById('closeAddBusModal');
    const cancelBtn = document.getElementById('cancelAddBusModal');

    const deleteModal = document.getElementById('deleteBusModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBusModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBusBtn');
    
    const busForm = document.getElementById('busForm');
    const busIdInput = document.getElementById('bus_id');
    const modalTitle = document.getElementById('busModalTitle');
    const modalSubmitBtn = document.getElementById('busModalSubmitBtn');

    const nameInput = document.getElementById('name');
    const plateInput = document.getElementById('plate');
    const capacityInput = document.getElementById('capacity');
    const driverInput = document.getElementById('driver_id'); 
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const uploadButton = document.getElementById('upload-button');

    const nameError = document.getElementById('nameError');
    const plateError = document.getElementById('plateError');
    const capacityError = document.getElementById('capacityError');
    const driverError = document.getElementById('driver_idError'); 
    const photoError = document.getElementById('photoError');
    
    const nameRegex = /.{3,}/; 
    const plateRegex = /^[A-Z]{2}[\s-]?[0-9]{1,2}[\s-]?[A-Z]{1,2}[\s-]?[0-9]{1,4}$/i; 
    const capacityRegex = /^[1-9][0-9]*$/; 
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    /**
     * Displays a validation error message for a specific input field.
     * @param {HTMLElement} input - The input element that failed validation.
     * @param {HTMLElement} errorElement - The <small> element to display the error message in.
     * @param {string} message - The error message to display.
     */
    function showError(input, errorElement, message) {
        if (input.classList) {
            input.classList.add('is-invalid');
        }
        errorElement.innerText = message; 

        setTimeout(() => {
            clearError(input, errorElement);
        }, 3000);
    }

    /**
     * Clears a validation error message from an input field.
     * @param {HTMLElement} input - The input element.
     * @param {HTMLElement} errorElement - The <small> element displaying the error.
     */
    function clearError(input, errorElement) {
        if (input.classList) {
            input.classList.remove('is-invalid');
        }
        errorElement.innerText = '';
    }

    /**
     * Clears all validation error messages from the form.
     */
    function clearAllErrors() {
        clearError(nameInput, nameError);
        clearError(plateInput, plateError);
        clearError(capacityInput, capacityError);
        clearError(driverInput, driverError); 
        clearError(uploadButton, photoError);
    }

    /**
     * Validates the bus name input field.
     * @returns {boolean} True if valid, false otherwise.
     */
    function validateName() {
        if (nameInput.value.trim() === '') {
            showError(nameInput, nameError, 'Bus name is required.');
            return false;
        } else if (!nameRegex.test(nameInput.value)) {
            showError(nameInput, nameError, 'Bus name must be at least 3 characters.');
            return false;
        }
        clearError(nameInput, nameError);
        return true;
    }

    /**
     * Validates the license plate input field.
     * @returns {boolean} True if valid, false otherwise.
     */
    function validatePlate() {
        if (plateInput.value.trim() === '') {
            showError(plateInput, plateError, 'Number plate is required.');
            return false;
        } else if (!plateRegex.test(plateInput.value)) {
            showError(plateInput, plateError, 'Enter a valid plate (e.g., TN-01-AB-1234).');
            return false;
        }
        clearError(plateInput, plateError);
        return true;
    }

    /**
     * Validates the bus capacity input field.
     * @returns {boolean} True if valid, false otherwise.
     */
    function validateCapacity() {
        if (capacityInput.value.trim() === '') {
            showError(capacityInput, capacityError, 'Capacity is required.');
            return false;
        } else if (!capacityRegex.test(capacityInput.value)) {
            showError(capacityInput, capacityError, 'Please enter a valid number of seats.');
            return false;
        }
        clearError(capacityInput, capacityError);
        return true;
    }

    /**
     * Validates the photo upload field.
     * @returns {boolean} True if valid, false otherwise.
     */
    function validatePhoto() {
        const file = photoInput.files[0];
        const isEditMode = busIdInput.value !== '';

        if (!file && !isEditMode) {
            showError(uploadButton, photoError, 'Bus image is required.');
            return false;
        }
        
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
     * Checks the validity of all form inputs and enables/disables the submit button.
     */
    function checkFormValidity() {
        clearError(nameInput, nameError);
        clearError(plateInput, plateError);
        clearError(capacityInput, capacityError);
        clearError(driverInput, driverError);
        clearError(uploadButton, photoError);

        const isNameValid = nameRegex.test(nameInput.value.trim());
        const isPlateValid = plateRegex.test(plateInput.value.trim());
        const isCapacityValid = capacityRegex.test(capacityInput.value.trim());
        
        const file = photoInput.files[0];
        const isEditMode = busIdInput.value !== '';
        let isPhotoValid = true;
        
        if (!file && !isEditMode) { 
            isPhotoValid = false;
        }
        if (file && file.size > MAX_FILE_SIZE) { 
            isPhotoValid = false;
        }

        if (isNameValid && isPlateValid && isCapacityValid && isPhotoValid) {
            modalSubmitBtn.disabled = false;
        } else {
            modalSubmitBtn.disabled = true;
        }
    }

    /**
     * Resets the bus modal form to its default state for adding a new bus.
     */
    const resetModal = () => {
        if (modalTitle) modalTitle.innerText = 'Add New Bus';
        if (modalSubmitBtn) modalSubmitBtn.innerText = 'Submit Bus';
        
        if (busForm) busForm.reset(); 
        if (busForm) busForm.action = '/buses/create';
        if (busIdInput) busIdInput.value = '';

        if (driverInput) driverInput.value = '';
        if (driverInput) driverInput.disabled = false;
        if (driverInput) driverInput.parentElement.querySelector('label').innerText = 'Assign Driver (Optional)';

        if (photoPreview) {
            photoPreview.style.backgroundImage = 'none';
            photoPreview.style.borderStyle = 'dashed'; 
        }

        clearAllErrors();
        if (modalSubmitBtn) modalSubmitBtn.disabled = true;
    };

    /**
     * Opens the modal in 'Add' mode.
     */
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            resetModal();
            if (modal) modal.style.display = 'flex';
            checkFormValidity(); 
        });
    }

    /**
     * Closes the main bus modal and resets its form.
     */
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

    /**
     * Handles all click events on the document, delegating to edit and delete buttons.
     */
    document.addEventListener('click', function(event) {
        
        const editButton = event.target.closest('.js-edit-bus');
        if (editButton) {
            event.preventDefault(); 
            resetModal(); 

            const id = editButton.getAttribute('data-id');
            const name = editButton.getAttribute('data-name');
            const plate = editButton.getAttribute('data-plate');
            const capacity = editButton.getAttribute('data-capacity');
            const driverId = editButton.getAttribute('data-driver-id'); 
            const photoPath = editButton.getAttribute('data-photo');

            if (modalTitle) modalTitle.innerText = 'Edit Bus';
            if (modalSubmitBtn) modalSubmitBtn.innerText = 'Update Bus';
            
            if (busForm) busForm.action = '/buses/update';
            if (busIdInput) busIdInput.value = id;

            if (nameInput) nameInput.value = name;
            if (plateInput) plateInput.value = plate;
            if (capacityInput) capacityInput.value = capacity;
            
            if (driverInput) {
                driverInput.value = driverId || ''; 
                driverInput.disabled = false;
                driverInput.parentElement.querySelector('label').innerText = 'Assign Driver (Optional)';
            }

            if (photoPreview) {
                 if (photoPath && photoPath !== '') {
                    photoPreview.style.backgroundImage = `url('${photoPath}')`;
                    photoPreview.style.borderStyle = 'solid';
                } else {
                    photoPreview.style.backgroundImage = `url('https://api.dicebear.com/7.x/shapes/svg?seed=${encodeURIComponent(plate)}&backgroundColor=282c34&shape1Color=86efac&shape2Color=e0e0e0')`;
                    photoPreview.style.borderStyle = 'solid';
                }
            }
            if (modal) modal.style.display = 'flex';
            
            if (modalSubmitBtn) modalSubmitBtn.disabled = false;
        }

        const deleteButton = event.target.closest('.js-delete-bus');
        if (deleteButton) {
            event.preventDefault();
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
    });

    /**
     * Attaches click listener to the 'Cancel Delete' button.
     */
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => {
            if (deleteModal) deleteModal.style.display = 'none';
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-id');
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-url');
        });
    }
    
    /**
     * Attaches click listener to the 'Confirm Delete' button for AJAX deletion.
     */
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            const deleteId = confirmDeleteBtn.getAttribute('data-id');
            const deleteUrl = confirmDeleteBtn.getAttribute('data-url'); 

            if (deleteId && deleteUrl) {
                
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2" style="animation: spin 1s linear infinite;">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span>Deleting...</span>
                `;
                
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
                    headers: buildAjaxHeaders()
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        if (deleteModal) deleteModal.style.display = 'none';
                        
                        setTimeout(() => {
                           location.reload();
                        }, 1500); 
                    
                    } else {
                        showAlert(data.message, 'danger');
                        if (deleteModal) deleteModal.style.display = 'none';
                    }

                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <span>Delete</span>
                    `;
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    showAlert('An unknown error occurred.', 'danger');
                    if (deleteModal) deleteModal.style.display = 'none';
                    
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <span>Delete</span>
                    `;
                });
            }
        });
    }
    
    /**
     * Attaches click listener to the delete modal overlay to close it.
     */
    if (deleteModal) {
        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                deleteModal.style.display = 'none';
                if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-id');
                if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-url');
            }
        });
    }

    /**
     * Attaches click listeners for photo upload buttons.
     */
    if (uploadButton) {
        uploadButton.addEventListener('click', () => photoInput.click());
    }
    if (photoPreview) {
        photoPreview.addEventListener('click', () => photoInput.click());
    }
    
    /**
     * Handles photo file selection, preview, and validation check.
     */
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

    /**
     * Attaches real-time validation listeners to form inputs.
     */
    if (nameInput) nameInput.addEventListener('input', checkFormValidity);
    if (plateInput) plateInput.addEventListener('input', checkFormValidity);
    if (capacityInput) capacityInput.addEventListener('input', checkFormValidity);


    /**
     * Handles the AJAX form submission for both creating and updating buses.
     */
    if (busForm) {
        busForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            
            const isNameValid = validateName();
            const isPlateValid = validatePlate();
            const isCapacityValid = validateCapacity();
            const isPhotoValid = validatePhoto();

            if (!isNameValid || !isPlateValid || !isCapacityValid || !isPhotoValid) {
                showAlert('Please fix the errors in the form.', 'danger');
                return;
            }
            
            modalSubmitBtn.disabled = true;
            modalSubmitBtn.innerText = 'Submitting...';
            
            clearAllErrors();
            
            const formData = new FormData(busForm);
            appendCsrf(formData);
            const url = busForm.action;

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
                        if (data.errors.name) {
                            showError(nameInput, nameError, data.errors.name);
                        }
                        if (data.errors.plate) {
                            showError(plateInput, plateError, data.errors.plate);
                        }
                        if (data.errors.capacity) {
                            showError(capacityInput, capacityError, data.errors.capacity);
                        }
                        if (data.errors.driver_id) { 
                            showError(driverInput, driverError, data.errors.driver_id);
                        }
                         if (data.errors.photo) {
                            showError(uploadButton, photoError, data.errors.photo);
                        }
                    }
                    modalSubmitBtn.disabled = false;
                    modalSubmitBtn.innerText = modalTitle.innerText.includes('Edit') ? 'Update Bus' : 'Submit Bus';
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                if (error && typeof error === 'object' && 'message' in error) {
                    showAlert(error.message || 'An error occurred. Please try again.', 'danger');
                    if (error.errors) {
                        if (error.errors.name) showError(nameInput, nameError, error.errors.name);
                        if (error.errors.plate) showError(plateInput, plateError, error.errors.plate);
                        if (error.errors.capacity) showError(capacityInput, capacityError, error.errors.capacity);
                        if (error.errors.driver_id) showError(driverInput, driverError, error.errors.driver_id);
                        if (error.errors.photo) showError(uploadButton, photoError, error.errors.photo);
                    }
                } else {
                    showAlert('An unknown error occurred. Please try again.', 'danger');
                }
                modalSubmitBtn.disabled = false;
                modalSubmitBtn.innerText = modalTitle.innerText.includes('Edit') ? 'Update Bus' : 'Submit Bus';
            });
        });
    }

});