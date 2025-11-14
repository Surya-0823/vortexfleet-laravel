/**
 * @file
 * Handles all UI logic for the Routes Management page.
 * Includes:
 * 1. DOM Element Caching
 * 2. Form Validation
 * 3. Modal Controls (Add/Edit, Delete)
 *
 * @license GNU
 */

// ../utils.js la irundhu (oru folder veliya) import panrom
import { showAlert } from '../utils.js';

// =======================================================
// --- DOM Elements Cache ---
// =======================================================
// Ella DOM element variables-ayum inga export panrom

// Add/Edit Modal
export let modal, routeForm, routeIdInput, openBtn, closeBtn, cancelBtn;
export let modalTitle, modalSubmitBtn, startInput, endInput, busInput;

// Delete Modal
export let deleteModal, cancelDeleteBtn, confirmDeleteBtn;

// Error Fields
export let startError, endError, busError;

/**
 * Caches all required DOM elements into global variables.
 */
function cacheDOMElements() {
    // --- Modal Controls (Add/Edit) ---
    modal = document.getElementById('addRouteModal');
    routeForm = document.getElementById('routeForm');
    routeIdInput = document.getElementById('route_id');
    openBtn = document.getElementById('openAddRouteModal');
    closeBtn = document.getElementById('closeAddRouteModal');
    cancelBtn = document.getElementById('cancelAddRouteModal');

    // --- Modal Form Elements ---
    modalTitle = document.getElementById('routeModalTitle');
    modalSubmitBtn = document.getElementById('routeModalSubmitBtn');
    startInput = document.getElementById('start');
    endInput = document.getElementById('end');
    busInput = document.getElementById('bus_plate'); 
    
    // --- Delete Modal Elements ---
    deleteModal = document.getElementById('deleteRouteModal');
    cancelDeleteBtn = document.getElementById('cancelDeleteModal');
    confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // --- Validation elements ---
    startError = document.getElementById('startError');
    endError = document.getElementById('endError');
    busError = document.getElementById('bus_plateError');
}

// --- Validation patterns (Regex) ---
const pointRegex = /.{2,}/; // Minimum 2 characters

// =======================================================
// --- Validation Helper Functions ---
// =======================================================

export function showError(input, errorElement, message) {
    if (input && input.classList) {
        input.classList.add('is-invalid');
    }
    if (errorElement) {
        errorElement.innerText = message;
        setTimeout(() => clearError(input, errorElement), 3000);
    }
}

export function clearError(input, errorElement) {
    if (input && input.classList) {
        input.classList.remove('is-invalid');
    }
    if (errorElement) {
        errorElement.innerText = '';
    }
}

export function clearAllRouteFormErrors() {
    clearError(startInput, startError);
    clearError(endInput, endError);
    clearError(busInput, busError);
}

// --- Individual Field Validation Functions ---

export function validateStart() {
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

export function validateEnd() {
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

export function validateBus() {
    if (!busInput || busInput.value.trim() === '') {
        showError(busInput, busError, 'Please select a bus.');
        return false;
    }
    clearError(busInput, busError);
    return true;
}

export function checkFormValidity() {
    clearAllRouteFormErrors();
    const isStartValid = pointRegex.test(startInput.value.trim());
    const isEndValid = pointRegex.test(endInput.value.trim());
    const isBusValid = busInput && busInput.value.trim() !== '';

    if (isStartValid && isEndValid && isBusValid) {
        modalSubmitBtn.disabled = false;
    } else {
        modalSubmitBtn.disabled = true;
    }
}

// =======================================================
// --- Modal Control Functions ---
// =======================================================

export const resetRouteModal = () => {
    if (modalTitle) modalTitle.innerText = 'Add New Route';
    if (modalSubmitBtn) modalSubmitBtn.innerText = 'Submit Route';
    
    if (routeForm) routeForm.reset();
    if (routeForm) routeForm.action = '/routes/create';
    if (routeIdInput) routeIdInput.value = '';

    clearAllRouteFormErrors();
    if (modalSubmitBtn) modalSubmitBtn.disabled = true;
};

export const closeRouteModal = () => {
    if (modal) modal.style.display = 'none'; 
    resetRouteModal(); 
}

export const closeDeleteModal = () => {
    if (deleteModal) deleteModal.style.display = 'none';
    if (confirmDeleteBtn) {
        confirmDeleteBtn.removeAttribute('data-id');
        confirmDeleteBtn.removeAttribute('data-url');
    }
};

// =======================================================
// --- UI Event Listeners (Internal to UI) ---
// =======================================================

function attachInternalUiListeners() {
    // Real-time validation
    if (startInput) startInput.addEventListener('input', checkFormValidity);
    if (endInput) endInput.addEventListener('input', checkFormValidity);
    if (busInput) busInput.addEventListener('change', checkFormValidity);
}

// =======================================================
// --- Init Function ---
// =======================================================

export function init() {
    cacheDOMElements();
    attachInternalUiListeners();
}