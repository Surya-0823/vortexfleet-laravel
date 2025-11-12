document.addEventListener('DOMContentLoaded', function() {
    
    // --- Modal Controls ---
    const modal = document.getElementById('addBusModal');
    const openBtn = document.getElementById('openAddBusModal');
    const closeBtn = document.getElementById('closeAddBusModal');
    const cancelBtn = document.getElementById('cancelAddBusModal');

    // --- Delete Modal ---
    const deleteModal = document.getElementById('deleteBusModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBusModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBusBtn');
    
    // =======================================================
    // Validation kaga namma select panra elements
    // =======================================================
    const busForm = document.getElementById('busForm');
    const busIdInput = document.getElementById('bus_id');
    const modalTitle = document.getElementById('busModalTitle');
    const modalSubmitBtn = document.getElementById('busModalSubmitBtn');

    // Form inputs
    const nameInput = document.getElementById('name');
    const plateInput = document.getElementById('plate');
    const capacityInput = document.getElementById('capacity');
    const driverInput = document.getElementById('driver'); // <-- PUTHU INPUT
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const uploadButton = document.getElementById('upload-button');

    // Error message-ah kaatra <small> tags
    const nameError = document.getElementById('nameError');
    const plateError = document.getElementById('plateError');
    const capacityError = document.getElementById('capacityError');
    const driverError = document.getElementById('driverError'); // <-- PUTHU ERROR ELEMENT
    const photoError = document.getElementById('photoError');
    
    // Validation patterns (Regex)
    const nameRegex = /.{3,}/; // Minimum 3 characters
    const plateRegex = /^[A-Z]{2}[\s-]?[0-9]{1,2}[\s-]?[A-Z]{1,2}[\s-]?[0-9]{1,4}$/i; // TN-01-AB-1234
    const capacityRegex = /^[1-9][0-9]*$/; // 1-99+
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

    // =======================================================
    // Validation Helper Functions
    // =======================================================

    /** Input field-la error kaatrum */
    function showError(input, errorElement, message) {
        if (input.classList) {
            input.classList.add('is-invalid');
        }
        errorElement.innerText = message; 

        // 3 second-la antha error-ah clear pannidrom
        setTimeout(() => {
            clearError(input, errorElement);
        }, 3000);
    }

    /** Input field-la irunthu error-ah neekkum */
    function clearError(input, errorElement) {
        if (input.classList) {
            input.classList.remove('is-invalid');
        }
        errorElement.innerText = '';
    }

    // Ella client/server error-ayum clear panna oru function
    function clearAllErrors() {
        clearError(nameInput, nameError);
        clearError(plateInput, plateError);
        clearError(capacityInput, capacityError);
        clearError(driverInput, driverError); // <-- PUTHU ERROR CLEAR
        clearError(uploadButton, photoError);
    }

    // --- Ovvoru input-kum thani validation function ---

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
     * Ellame valid-ah-nu check panni, submit button-ah enable/disable pannum
     */
    function checkFormValidity() {
        // Clear panrathu kaga itha call panrom, error-ah kaatarthukku illa
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
        
        if (!file && !isEditMode) { // Add mode-la file illa
            isPhotoValid = false;
        }
        if (file && file.size > MAX_FILE_SIZE) { // File irukku, aana size perusu
            isPhotoValid = false;
        }

        // Ellame valid-ah iruntha mattum button enable aagum
        if (isNameValid && isPlateValid && isCapacityValid && isPhotoValid) {
            modalSubmitBtn.disabled = false;
        } else {
            modalSubmitBtn.disabled = true;
        }
    }

    // --- Modal Reset Function ---
    const resetModal = () => {
        if (modalTitle) modalTitle.innerText = 'Add New Bus';
        if (modalSubmitBtn) modalSubmitBtn.innerText = 'Submit Bus';
        
        if (busForm) busForm.reset(); 
        if (busForm) busForm.action = '/buses/create';
        if (busIdInput) busIdInput.value = '';

        // PUTHU MAATRAM: Driver dropdown reset panrom
        if (driverInput) driverInput.value = '';
        if (driverInput) driverInput.disabled = true;
        if (driverInput) driverInput.parentElement.querySelector('label').innerText = 'Assigned Driver (Edit Only)';

        if (photoPreview) {
            photoPreview.style.backgroundImage = 'none';
            photoPreview.style.borderStyle = 'dashed'; 
        }

        clearAllErrors();
        if (modalSubmitBtn) modalSubmitBtn.disabled = true;
    };


    // "Add Bus" button click panna
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            resetModal();
            if (modal) modal.style.display = 'flex';
            checkFormValidity(); // Photo required-ah nu check pannum
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

    // --- "Edit" and "Delete" logic ---
    document.addEventListener('click', function(event) {
        
        // --- Edit Button Check ---
        const editButton = event.target.closest('.js-edit-bus');
        if (editButton) {
            event.preventDefault(); 
            resetModal(); 

            const id = editButton.getAttribute('data-id');
            const name = editButton.getAttribute('data-name');
            const plate = editButton.getAttribute('data-plate');
            const capacity = editButton.getAttribute('data-capacity');
            const driverId = editButton.getAttribute('data-driver-id'); // <-- PUTHU ATTRIBUTE
            const photoPath = editButton.getAttribute('data-photo');

            if (modalTitle) modalTitle.innerText = 'Edit Bus';
            if (modalSubmitBtn) modalSubmitBtn.innerText = 'Update Bus';
            
            if (busForm) busForm.action = '/buses/update';
            if (busIdInput) busIdInput.value = id;

            if (nameInput) nameInput.value = name;
            if (plateInput) plateInput.value = plate;
            if (capacityInput) capacityInput.value = capacity;
            
            // PUTHU MAATRAM: Driver dropdown update panrom
            if (driverInput) {
                driverInput.value = driverId || ''; // Current Driver ID select aagum
                driverInput.disabled = false;
                driverInput.parentElement.querySelector('label').innerText = 'Assigned Driver';
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

        // --- Delete Button Check ---
        const deleteButton = event.target.closest('.js-delete-bus');
        if (deleteButton) {
            event.preventDefault();
            // MAATRAM: data-delete-id-ah edukkrom
            const deleteId = deleteButton.getAttribute('data-delete-id');
            const deleteUrl = deleteButton.getAttribute('data-delete-url');

            if (confirmDeleteBtn) {
                confirmDeleteBtn.setAttribute('data-id', deleteId); // ID-ah set panrom
                confirmDeleteBtn.setAttribute('data-url', deleteUrl); // URL-ah set panrom
            }
            if (deleteModal) {
                deleteModal.style.display = 'flex';
            }
        }
    });

    // --- Delete Modal Listeners ---
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => {
            if (deleteModal) deleteModal.style.display = 'none';
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-id');
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-url');
        });
    }
    
    // =======================================================
    // ITHU THAAN ANTHA PUTHU DELETE FIX (AJAX/FETCH)
    // =======================================================
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            const deleteId = confirmDeleteBtn.getAttribute('data-id');
            const deleteUrl = confirmDeleteBtn.getAttribute('data-url'); // POST URL

            if (deleteId && deleteUrl) {
                
                // Button-ah disable panni, loading spinner kaatrom
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2" style="animation: spin 1s linear infinite;">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <span>Deleting...</span>
                `;
                
                // Style tag add panrom (CSS la animation illana)
                if (!document.getElementById('animate-spin-style')) {
                    const style = document.createElement('style');
                    style.id = 'animate-spin-style';
                    style.innerHTML = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
                    document.head.appendChild(style);
                }

                 // MAATRAM: POST request use panrom
                const formData = new FormData();
                formData.append('id', deleteId);
                formData.append('csrf_token', getCsrfToken()); // CSRF Token Add panrom


                // Fetch use panni AJAX call panrom
                fetch(deleteUrl, {
                    method: 'POST', // MAATRAM: POST method
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    // Response vanthathum, correct-ana alert kaatrom
                    if (data.success) {
                        showAlert(data.message, 'success');
                        if (deleteModal) deleteModal.style.display = 'none';
                        
                        // Page-ah reload panrom
                        setTimeout(() => {
                           location.reload();
                        }, 1500); 
                    
                    } else {
                        // Error message (e.g., "Cannot delete bus. It is assigned to route...")
                        showAlert(data.message, 'danger');
                        if (deleteModal) deleteModal.style.display = 'none';
                    }

                    // Button-ah pazhaya nilaikku kondu varom
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
                    
                    // Error aanaalum, Button-ah pazhaya nilaikku kondu varom
                    confirmDeleteBtn.disabled = false;
                    confirmDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <span>Delete</span>
                    `;
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

    // =======================================================
    // Real-time validation (User type panna panna)
    // =======================================================
    if (nameInput) nameInput.addEventListener('input', checkFormValidity);
    if (plateInput) plateInput.addEventListener('input', checkFormValidity);
    if (capacityInput) capacityInput.addEventListener('input', checkFormValidity);


    // =======================================================
    // Form Submit pannum pothu AJAX use panrom
    // =======================================================
    if (busForm) {
        busForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            
            // Final validation check (ithu thaan error-ah kaattum)
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
            
            // *** PUTHU MAATRAM: CSRF Token-ah add panrom ***
            formData.append('csrf_token', getCsrfToken());
            // *** MAATRAM MUDINJATHU ***

            const url = busForm.action;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
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
                        if (data.errors.driver) { // <-- PUTHU SERVER ERROR DISPLAY
                            showError(driverInput, driverError, data.errors.driver);
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
                // If error is an object with success/message, it's a server error response
                if (error && typeof error === 'object' && 'message' in error) {
                    showAlert(error.message || 'An error occurred. Please try again.', 'danger');
                    if (error.errors) {
                        if (error.errors.name) showError(nameInput, nameError, error.errors.name);
                        if (error.errors.plate) showError(plateInput, plateError, error.errors.plate);
                        if (error.errors.capacity) showError(capacityInput, capacityError, error.errors.capacity);
                        if (error.errors.driver) showError(driverInput, driverError, error.errors.driver);
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