/**
 * @file
 * Handles all event listeners for the Routes Management page.
 * Connects UI events (from routeUi.js) to API calls (from routeApi.js).
 *
 * @license GNU
 */

// Common utilities
import { showAlert } from '../utils.js';

// API module
import * as routeApi from './routeApi.js';

// UI module
import * as ui from './routeUi.js';

/**
 * Initializes all event listeners for the page.
 */
export function init() {

    // =======================================================
    // --- Add/Edit Route Modal Functions ---
    // =======================================================

    if (ui.openBtn) {
        ui.openBtn.addEventListener('click', () => {
            ui.resetRouteModal();
            if (ui.modal) ui.modal.style.display = 'flex';
            ui.checkFormValidity();
        });
    }

    if (ui.closeBtn) ui.closeBtn.addEventListener('click', ui.closeRouteModal);
    if (ui.cancelBtn) ui.cancelBtn.addEventListener('click', ui.closeRouteModal);

    if (ui.modal) {
        ui.modal.addEventListener('click', function(event) {
            if (event.target === ui.modal) {
                ui.closeRouteModal();
            }
        });
    }

    // =======================================================
    // --- Global Click Event Delegation ---
    // =======================================================
    document.addEventListener('click', function(event) {

        // --- 1. Edit Button Check ---
        const editButton = event.target.closest('.js-edit-route');
        if (editButton) {
            event.preventDefault(); 
            ui.resetRouteModal();

            const id = editButton.getAttribute('data-id');
            const start = editButton.getAttribute('data-start');
            const end = editButton.getAttribute('data-end');
            const bus = editButton.getAttribute('data-bus-plate'); 

            if (ui.modalTitle) ui.modalTitle.innerText = 'Edit Route';
            if (ui.modalSubmitBtn) ui.modalSubmitBtn.innerText = 'Update Route';

            if (ui.routeForm) ui.routeForm.action = '/routes/update';
            if (ui.routeIdInput) ui.routeIdInput.value = id;

            if (ui.startInput) ui.startInput.value = start;
            if (ui.endInput) ui.endInput.value = end;
            if (ui.busInput) ui.busInput.value = bus; 

            if (ui.modal) ui.modal.style.display = 'flex';
            if (ui.modalSubmitBtn) ui.modalSubmitBtn.disabled = false; 
        }

        // --- 2. Delete Button Check ---
        const deleteButton = event.target.closest('.js-delete-route');
        if (deleteButton) {
            event.preventDefault();
            const deleteId = deleteButton.getAttribute('data-delete-id');
            const deleteUrl = deleteButton.getAttribute('data-delete-url');

            if (ui.confirmDeleteBtn) {
                ui.confirmDeleteBtn.setAttribute('data-id', deleteId);
                ui.confirmDeleteBtn.setAttribute('data-url', deleteUrl);
            }
            if (ui.deleteModal) {
                ui.deleteModal.style.display = 'flex';
            }
        }
    });

    // =======================================================
    // --- Delete Modal Logic (AJAX) ---
    // =======================================================

    if (ui.cancelDeleteBtn) {
        ui.cancelDeleteBtn.addEventListener('click', ui.closeDeleteModal);
    }

    if (ui.deleteModal) {
        ui.deleteModal.addEventListener('click', (e) => {
            if (e.target === ui.deleteModal) ui.closeDeleteModal();
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
                const data = await routeApi.deleteRoute(deleteUrl, deleteId);

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
    if (ui.routeForm) {
        ui.routeForm.addEventListener('submit', async function(event) {
            event.preventDefault(); 

            const isStartValid = ui.validateStart();
            const isEndValid = ui.validateEnd();
            const isBusValid = ui.validateBus(); 

            if (!isStartValid || !isEndValid || !isBusValid) {
                showAlert('Please fix the errors in the form.', 'danger'); 
                return;
            }

            ui.modalSubmitBtn.disabled = true;
            ui.modalSubmitBtn.innerText = 'Submitting...';

            ui.clearAllRouteFormErrors();

            const formData = new FormData(ui.routeForm);
            const url = ui.routeForm.action;

            try {
                const data = await routeApi.submitRouteForm(url, formData);

                if (data.success) {
                    showAlert(data.message, 'success'); 
                    ui.closeRouteModal();
                    setTimeout(() => {
                        location.reload(); 
                    }, 1500);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                if (error && typeof error === 'object' && 'message' in error) {
                    showAlert(error.message || 'An error occurred. Please try again.', 'danger'); 
                    if (error.errors) {
                        if (error.errors.start) ui.showError(ui.startInput, ui.startError, error.errors.start);
                        if (error.errors.end) ui.showError(ui.endInput, ui.endError, error.errors.end);
                        if (error.errors.bus_plate) ui.showError(ui.busInput, ui.busError, error.errors.bus_plate);
                    }
                } else {
                    showAlert('An unknown error occurred. Please try again.', 'danger'); 
                }
            } finally {
                ui.modalSubmitBtn.disabled = false;
                const isEditMode = ui.routeIdInput && ui.routeIdInput.value !== '';
                ui.modalSubmitBtn.innerText = isEditMode ? 'Update Route' : 'Submit Route';
            }
        });
    }

} // End of init function