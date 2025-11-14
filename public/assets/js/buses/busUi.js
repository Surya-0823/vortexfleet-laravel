/**
 * @file
 * Handles all UI logic for the Buses Management page.
 * Includes:
 * 1. DOM Element Caching
 * 2. Form Validation
 * 3. Modal Controls (Add/Edit, Delete)
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

// Add/Edit Modal
export let addBusModal, openAddBusModal, closeAddBusModal, cancelAddBusModal;
export let busForm, busIdInput, busModalTitle, busModalSubmitBtn;

// Delete Modal
export let deleteBusModal, cancelDeleteModal, confirmDeleteBtn;

// Form Inputs
export let nameInput, plateInput, capacityInput, driverInput;
export let photoInput, photoPreview, uploadButton;

// Error Fields
export let nameError, plateError, capacityError, driverError, photoError;

/**
 * Caches all required DOM elements into global variables.
 */
function cacheDOMElements() {
    addBusModal = document.getElementById('addBusModal');
    openAddBusModal = document.getElementById('openAddBusModal');
    closeAddBusModal = document.getElementById('closeAddBusModal');
    cancelAddBusModal = document.getElementById('cancelAddBusModal');

    deleteBusModal = document.getElementById('deleteBusModal');
    cancelDeleteModal = document.getElementById('cancelDeleteModal');
    confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    busForm = document.getElementById('busForm');
    busIdInput = document.getElementById('bus_id');
    busModalTitle = document.getElementById('busModalTitle');
    busModalSubmitBtn = document.getElementById('busModalSubmitBtn');

    nameInput = document.getElementById('name');
    plateInput = document.getElementById('plate');
    capacityInput = document.getElementById('capacity');
    driverInput = document.getElementById('driver_id'); 
    photoInput = document.getElementById('photo');
    photoPreview = document.getElementById('photo-preview');
    uploadButton = document.getElementById('upload-button');

    nameError = document.getElementById('nameError');
    plateError = document.getElementById('plateError');
    capacityError = document.getElementById('capacityError');
    driverError = document.getElementById('driver_idError'); 
    photoError = document.getElementById('photoError');
}

// --- Validation patterns (Regex) ---
const nameRegex = /.{3,}/; 
const plateRegex = /^[A-Z]{2}[\s-]?[0-9]{1,2}[\s-]?[A-Z]{1,2}[\s-]?[0-9]{1,4}$/i; 
const capacityRegex = /^[1-9][0-9]*$/; 
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
    setTimeout(() => {
        clearError(input, errorElement);
    }, 3000);
}

export function clearError(input, errorElement) {
    if (input && input.classList) {
        input.classList.remove('is-invalid');
    }
    if (errorElement) {
        errorElement.innerText = '';
    }
}

export function clearAllBusFormErrors() {
    clearError(nameInput, nameError);
    clearError(plateInput, plateError);
    clearError(capacityInput, capacityError);
    clearError(driverInput, driverError); 
    clearError(uploadButton, photoError);
}

// --- Individual Field Validation Functions ---

export function validateName() {
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

export function validatePlate() {
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

export function validateCapacity() {
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

export function validatePhoto() {
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

export function checkFormValidity() {
    clearAllBusFormErrors();

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
        busModalSubmitBtn.disabled = false;
    } else {
        busModalSubmitBtn.disabled = true;
    }
}

// =======================================================
// --- Modal Control Functions ---
// =======================================================

export const resetBusModal = () => {
    if (busModalTitle) busModalTitle.innerText = 'Add New Bus';
    if (busModalSubmitBtn) busModalSubmitBtn.innerText = 'Submit Bus';

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

    clearAllBusFormErrors();
    if (busModalSubmitBtn) busModalSubmitBtn.disabled = true;
};

export const closeBusModal = () => {
    if (addBusModal) addBusModal.style.display = 'none'; 
    resetBusModal(); 
}

export const closeDeleteModal = () => {
    if (deleteBusModal) deleteBusModal.style.display = 'none';
    if (confirmDeleteBtn) {
        confirmDeleteBtn.removeAttribute('data-id');
        confirmDeleteBtn.removeAttribute('data-url');
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
    if (plateInput) plateInput.addEventListener('input', checkFormValidity);
    if (capacityInput) capacityInput.addEventListener('input', checkFormValidity);
}

// =======================================================
// --- Init Function ---
// =======================================================

export function init() {
    cacheDOMElements();
    attachInternalUiListeners();
}