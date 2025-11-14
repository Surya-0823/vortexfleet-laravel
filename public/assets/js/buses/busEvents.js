/**
 * @file
 * Handles all event listeners for the Buses Management page.
 * Connects UI events (from busUi.js) to API calls (from busApi.js).
 *
 * @license GNU
 */

// Common utilities
import { showAlert } from '../utils.js';

// API module
import * as busApi from './busApi.js';

// UI module
import * as ui from './busUi.js';

/**
 * Initializes all event listeners for the page.
 */
export function init() {

    // =======================================================
    // --- Add/Edit Bus Modal Functions ---
    // =======================================================

    if (ui.openAddBusModal) {
        ui.openAddBusModal.addEventListener('click', () => {
            ui.resetBusModal();
            if (ui.addBusModal) ui.addBusModal.style.display = 'flex';
            ui.checkFormValidity(); 
        });
    }

    if (ui.closeAddBusModal) ui.closeAddBusModal.addEventListener('click', ui.closeBusModal);
    if (ui.cancelAddBusModal) ui.cancelAddBusModal.addEventListener('click', ui.closeBusModal);

    if (ui.addBusModal) {
        ui.addBusModal.addEventListener('click', function(event) {
            if (event.target === ui.addBusModal) {
                ui.closeBusModal();
            }
        });
    }

    // =======================================================
    // --- Global Click Event Delegation ---
    // =======================================================
    document.addEventListener('click', function(event) {

        // --- 1. Edit Bus Button Click ---
        const editButton = event.target.closest('.js-edit-bus');
        if (editButton) {
            event.preventDefault(); 
            ui.resetBusModal(); 

            const id = editButton.getAttribute('data-id');
            const name = editButton.getAttribute('data-name');
            const plate = editButton.getAttribute('data-plate');
            const capacity = editButton.getAttribute('data-capacity');
            const driverId = editButton.getAttribute('data-driver-id'); 
            const photoPath = editButton.getAttribute('data-photo');

            if (ui.busModalTitle) ui.busModalTitle.innerText = 'Edit Bus';
            if (ui.busModalSubmitBtn) ui.busModalSubmitBtn.innerText = 'Update Bus';

            if (ui.busForm) ui.busForm.action = '/buses/update';
            if (ui.busIdInput) ui.busIdInput.value = id;

            if (ui.nameInput) ui.nameInput.value = name;
            if (ui.plateInput) ui.plateInput.value = plate;
            if (ui.capacityInput) ui.capacityInput.value = capacity;

            if (ui.driverInput) {
                ui.driverInput.value = driverId || ''; 
                ui.driverInput.disabled = false;
                ui.driverInput.parentElement.querySelector('label').innerText = 'Assign Driver (Optional)';
            }

            if (ui.photoPreview) {
                 if (photoPath && photoPath !== '') {
                    ui.photoPreview.style.backgroundImage = `url('${photoPath}')`;
                    ui.photoPreview.style.borderStyle = 'solid';
                } else {
                    ui.photoPreview.style.backgroundImage = `url('https://api.dicebear.com/7.x/shapes/svg?seed=${encodeURIComponent(plate)}&backgroundColor=282c34&shape1Color=86efac&shape2Color=e0e0e0')`;
                    ui.photoPreview.style.borderStyle = 'solid';
                }
            }
            if (ui.addBusModal) ui.addBusModal.style.display = 'flex';
            if (ui.busModalSubmitBtn) ui.busModalSubmitBtn.disabled = false;
        }

        // --- 2. Delete Bus Button Click ---
        const deleteButton = event.target.closest('.js-delete-bus');
        if (deleteButton) {
            event.preventDefault();
            const deleteId = deleteButton.getAttribute('data-delete-id');
            const deleteUrl = deleteButton.getAttribute('data-delete-url');

            if (ui.confirmDeleteBtn) {
                ui.confirmDeleteBtn.setAttribute('data-id', deleteId); 
                ui.confirmDeleteBtn.setAttribute('data-url', deleteUrl); 
            }
            if (ui.deleteBusModal) {
                ui.deleteBusModal.style.display = 'flex';
            }
        }
    });

    // =======================================================
    // --- Delete Modal Logic (AJAX) ---
    // =======================================================

    if (ui.cancelDeleteModal) {
        ui.cancelDeleteModal.addEventListener('click', ui.closeDeleteModal);
    }

    if (ui.deleteBusModal) {
        ui.deleteBusModal.addEventListener('click', (e) => {
            if (e.target === ui.deleteBusModal) ui.closeDeleteModal();
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
                <span>Deleting...</span>
            `;

            if (!document.getElementById('animate-spin-style')) {
                const style = document.createElement('style');
                style.id = 'animate-spin-style';
                style.innerHTML = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
                document.head.appendChild(style);
            }

            try {
                const data = await busApi.deleteBus(deleteUrl, deleteId);

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
                showAlert('An unknown error occurred.', 'danger');
                ui.closeDeleteModal();
            } finally {
                ui.confirmDeleteBtn.disabled = false;
                ui.confirmDeleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    <span>Delete</span>
                `;
            }
        });
    }

    // =======================================================
    // --- Form Submit (Add/Edit) AJAX Logic ---
    // =======================================================
    if (ui.busForm) {
        ui.busForm.addEventListener('submit', async function(event) {
            event.preventDefault(); 

            const isNameValid = ui.validateName();
            const isPlateValid = ui.validatePlate();
            const isCapacityValid = ui.validateCapacity();
            const isPhotoValid = ui.validatePhoto();

            if (!isNameValid || !isPlateValid || !isCapacityValid || !isPhotoValid) {
                showAlert('Please fix the errors in the form.', 'danger'); 
                return;
            }

            ui.busModalSubmitBtn.disabled = true;
            ui.busModalSubmitBtn.innerText = 'Submitting...';

            ui.clearAllBusFormErrors();

            const formData = new FormData(ui.busForm);
            const url = ui.busForm.action;

            try {
                const data = await busApi.submitBusForm(url, formData);

                if (data.success) {
                    showAlert(data.message, 'success'); 
                    ui.closeBusModal();
                    setTimeout(() => {
                        location.reload(); 
                    }, 1500);
                } 
            } catch (error) {
                console.error('Fetch Error:', error);
                if (error && typeof error === 'object' && 'message' in error) {
                    showAlert(error.message || 'An error occurred. Please try again.', 'danger'); 
                    if (error.errors) {
                        if (error.errors.name) ui.showError(ui.nameInput, ui.nameError, error.errors.name);
                        if (error.errors.plate) ui.showError(ui.plateInput, ui.plateError, error.errors.plate);
                        if (error.errors.capacity) ui.showError(ui.capacityInput, ui.capacityError, error.errors.capacity);
                        if (error.errors.driver_id) ui.showError(ui.driverInput, ui.driverError, error.errors.driver_id);
                        if (error.errors.photo) ui.showError(ui.uploadButton, ui.photoError, error.errors.photo);
                    }
                } else {
                    showAlert('An unknown error occurred. Please try again.', 'danger'); 
                }
            } finally {
                ui.busModalSubmitBtn.disabled = false;
                const isEditMode = ui.busIdInput && ui.busIdInput.value !== '';
                ui.busModalSubmitBtn.innerText = isEditMode ? 'Update Bus' : 'Submit Bus';
            }
        });
    }

} // End of init function