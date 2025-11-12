document.addEventListener('DOMContentLoaded', function() {
    
    // --- Modal Controls (Add/Edit) ---
    const modal = document.getElementById('addRouteModal');
    const routeForm = document.getElementById('routeForm');
    const routeIdInput = document.getElementById('route_id');
    const openBtn = document.getElementById('openAddRouteModal');
    const closeBtn = document.getElementById('closeAddRouteModal');
    const cancelBtn = document.getElementById('cancelAddRouteModal');

    // --- Modal Form Elements ---
    const modalTitle = document.getElementById('routeModalTitle');
    const modalSubmitBtn = document.getElementById('routeModalSubmitBtn');
    const startInput = document.getElementById('start');
    const endInput = document.getElementById('end');
    const busInput = document.getElementById('bus'); // Ithu ippo <select>
    
    // --- Delete Modal Elements ---
    const deleteModal = document.getElementById('deleteRouteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // =======================================================
    // Validation kaga namma select panra elements
    // =======================================================
    const startError = document.getElementById('startError');
    const endError = document.getElementById('endError');
    const busError = document.getElementById('busError');
    
    // Validation patterns (Regex)
    const pointRegex = /.{2,}/; // Minimum 2 characters
    // Bus plate regex theva illa, namma dropdown use panrom
    // const plateRegex = /^[A-Z]{2}[\s-]?[0-9]{1,2}[\s-]?[A-Z]{1,2}[\s-]?[0-9]{1,4}$/i; 

    // =======================================================
    // Validation Helper Functions
    // =======================================================

    function showError(input, errorElement, message) {
        if (input.classList) {
            input.classList.add('is-invalid');
        }
        errorElement.innerText = message;
        setTimeout(() => clearError(input, errorElement), 3000);
    }

    function clearError(input, errorElement) {
        if (input.classList) {
            input.classList.remove('is-invalid');
        }
        errorElement.innerText = '';
    }

    function clearAllErrors() {
        clearError(startInput, startError);
        clearError(endInput, endError);
        clearError(busInput, busError);
    }

    // --- Ovvoru input-kum thani validation function ---

    function validateStart() {
        if (startInput.value.trim() === '') {
            showError(startInput, startError, 'Start point is required.');
            return false;
        } else if (!pointRegex.test(startInput.value)) {
            showError(startInput, startError, 'Start point must be at least 2 characters.');
            return false;
        }
        clearError(startInput, startError);
        return true;
    }

    function validateEnd() {
        if (endInput.value.trim() === '') {
            showError(endInput, endError, 'End point is required.');
            return false;
        } else if (!pointRegex.test(endInput.value)) {
            showError(endInput, endError, 'End point must be at least 2 characters.');
            return false;
        }
        clearError(endInput, endError);
        return true;
    }

    // =======================================================
    // PUTHU MAATRAM: Bus validation function (Dropdown kaga)
    // =======================================================
    function validateBus() {
        if (busInput.value.trim() === '') {
            // Message ah maathirukkom
            showError(busInput, busError, 'Please select a bus.');
            return false;
        }
        // Regex check ah remove pannitom
        clearError(busInput, busError);
        return true;
    }

    // =======================================================
    // PUTHU MAATRAM: checkFormValidity (Dropdown kaga)
    // =======================================================
    function checkFormValidity() {
        clearAllErrors();
        const isStartValid = pointRegex.test(startInput.value.trim());
        const isEndValid = pointRegex.test(endInput.value.trim());
        // Regex-ku pathila, selection irukkaanu mattum check panrom
        const isBusValid = busInput.value.trim() !== '';

        if (isStartValid && isEndValid && isBusValid) {
            modalSubmitBtn.disabled = false;
        } else {
            modalSubmitBtn.disabled = true;
        }
    }


    // --- Function: Modal-a default "Add" state-ku reset panrathu ---
    const resetModal = () => {
        if (modalTitle) modalTitle.innerText = 'Add New Route';
        if (modalSubmitBtn) modalSubmitBtn.innerText = 'Submit Route';
        
        if (routeForm) routeForm.reset();
        if (routeForm) routeForm.action = '/routes/create';
        if (routeIdInput) routeIdInput.value = '';

        clearAllErrors();
        if (modalSubmitBtn) modalSubmitBtn.disabled = true;
    };

    // "Add Route" button click panna
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            resetModal();
            if (modal) modal.style.display = 'flex';
            checkFormValidity();
        });
    }

    // Function: Modal-a moodurathu
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

    // --- LOGIC: "Edit" and "Delete" button clicks ---
    document.addEventListener('click', function(event) {
        
        // --- Edit Button Check ---
        const editButton = event.target.closest('.js-edit-route');
        if (editButton) {
            event.preventDefault(); 
            resetModal();
            
            const id = editButton.getAttribute('data-id');
            const start = editButton.getAttribute('data-start');
            const end = editButton.getAttribute('data-end');
            const bus = editButton.getAttribute('data-bus'); // Ithu bus plate number
            
            if (modalTitle) modalTitle.innerText = 'Edit Route';
            if (modalSubmitBtn) modalSubmitBtn.innerText = 'Update Route';
            
            if (routeForm) routeForm.action = '/routes/update';
            if (routeIdInput) routeIdInput.value = id;

            if (startInput) startInput.value = start;
            if (endInput) endInput.value = end;
            if (busInput) busInput.value = bus; // Ithu correct ah dropdown ah select pannidum

            if (modal) modal.style.display = 'flex';
            if (modalSubmitBtn) modalSubmitBtn.disabled = false; // Edit pannum pothu button enable la irukkanum
        }

        // --- Delete Button Check ---
        const deleteButton = event.target.closest('.js-delete-route');
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
    });

    // --- Delete Modal Button Listeners ---
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => {
            if (deleteModal) deleteModal.style.display = 'none';
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-id');
            if (confirmDeleteBtn) confirmDeleteBtn.removeAttribute('data-url');
        });
    }

    // =======================================================
    // PUTHU DELETE LOGIC (AJAX/FETCH) INGA THAAN
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
                        // Error message (e.g., "Cannot delete route. It is assigned to 5 students...")
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
    
    // =======================================================
    // PUTHU MAATRAM: Real-time validation (Dropdown kaga)
    // =======================================================
    if (startInput) startInput.addEventListener('input', checkFormValidity);
    if (endInput) endInput.addEventListener('input', checkFormValidity);
    if (busInput) busInput.addEventListener('change', checkFormValidity); // 'input' ah 'change' ah maathrom


    // =======================================================
    // PUTHU MAATRAM: Form Submit pannum pothu AJAX use panrom
    // =======================================================
    if (routeForm) {
        routeForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            
            // Final validation check
            const isStartValid = validateStart();
            const isEndValid = validateEnd();
            const isBusValid = validateBus(); // Itha maathirukkom

            if (!isStartValid || !isEndValid || !isBusValid) {
                showAlert('Please fix the errors in the form.', 'danger');
                return;
            }
            
            modalSubmitBtn.disabled = true;
            modalSubmitBtn.innerText = 'Submitting...';
            
            clearAllErrors();
            
            const formData = new FormData(routeForm);
            
            // *** PUTHU MAATRAM: CSRF Token-ah add panrom ***
            formData.append('csrf_token', getCsrfToken());
            // *** MAATRAM MUDINJATHU ***

            const url = routeForm.action;

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
                        if (data.errors.start) {
                            showError(startInput, startError, data.errors.start);
                        }
                        if (data.errors.end) {
                            showError(endInput, endError, data.errors.end);
                        }
                         if (data.errors.bus) {
                            showError(busInput, busError, data.errors.bus);
                        }
                    }
                    modalSubmitBtn.disabled = false;
                    modalSubmitBtn.innerText = modalTitle.innerText.includes('Edit') ? 'Update Route' : 'Submit Route';
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                // If error is an object with success/message, it's a server error response
                if (error && typeof error === 'object' && 'message' in error) {
                    showAlert(error.message || 'An error occurred. Please try again.', 'danger');
                    if (error.errors) {
                        if (error.errors.start) showError(startInput, startError, error.errors.start);
                        if (error.errors.end) showError(endInput, endError, error.errors.end);
                        if (error.errors.bus) showError(busInput, busError, error.errors.bus);
                    }
                } else {
                    showAlert('An unknown error occurred. Please try again.', 'danger');
                }
                modalSubmitBtn.disabled = false;
                modalSubmitBtn.innerText = modalTitle.innerText.includes('Edit') ? 'Update Route' : 'Submit Route';
            });
        });
    }
});