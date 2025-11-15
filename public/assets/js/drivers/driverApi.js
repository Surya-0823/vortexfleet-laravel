/**
 * @file
 * Handles all API requests for the Drivers Management page.
 * @license GNU
 */

// utils.js la irundhu common functions-a import panrom
import { buildAjaxHeaders, appendCsrf } from '../utils.js';

/**
 * Submits the driver form (Add or Edit) via AJAX.
 * @param {string} url - The URL to submit to.
 * @param {FormData} formData - The form data to send.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function submitDriverForm(url, formData) {
    const response = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' }) 
    });

    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
        const text = await response.text();
        throw new Error(`Server returned non-JSON response: ${text.substring(0, 200)}`);
    }

    const data = await response.json();
    if (!response.ok) {
        throw data; // Throw server error data (e.g., validation errors)
    }
    return data; // { success: true, message: "..." }
}

/**
 * Sends an AJAX request to delete a driver.
 * @param {string} url - The delete URL.
 * @param {string} driverId - The ID of the driver to delete.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function deleteDriver(url, driverId) {
    const formData = new FormData();
    formData.append('id', driverId);
    appendCsrf(formData);

    const response = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    const data = await response.json();
    if (!response.ok) {
        throw data; // Throw server error data
    }
    return data; // { success: true, message: "..." }
}

/**
 * Sends an AJAX request to update a driver's verification status.
 * @param {string} driverId - The ID of the driver.
 * @param {number} newStatus - The new status (e.g., 0 for Not Verified).
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function updateDriverStatus(driverId, newStatus) {
    const formData = new FormData();
    formData.append('id', driverId);
    formData.append('is_verified', newStatus);
    appendCsrf(formData);

    const response = await fetch('/drivers/update-status', {
        method: 'POST', 
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}

/**
 * Sends an AJAX request to generate and send an OTP.
 * @param {string} userId - The ID of the driver.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function sendOtp(userId) {
    const formData = new FormData();
    formData.append('driver_id', userId);
    appendCsrf(formData);

    const response = await fetch('/drivers/send-otp', {
        method: 'POST', 
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}

/**
 * Sends an AJAX request to verify the entered OTP.
 * @param {string} userId - The ID of the driver.
 * @param {string} otpCode - The 6-digit OTP code.
 * @param {string|null} plainPassword - The new plain-text password (if available from reset)
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function verifyOtp(userId, otpCode, plainPassword) { // PUTHU MAATRAM: plainPassword add pannirukkom
    const formData = new FormData();
    formData.append('driver_id', userId);
    formData.append('otp_code', otpCode);
    
    // PUTHU MAATRAM: Password irunthaa, athaayum form data-la seru
    if (plainPassword) {
        formData.append('plain_password', plainPassword);
    }
    // END PUTHU MAATRAM
    
    appendCsrf(formData);

    const response = await fetch('/drivers/verify-otp', {
        method: 'POST',
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}

/**
 * Sends an AJAX request to reset a driver's password.
 * @param {string} driverId - The ID of the driver.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function resetPassword(driverId) {
    const formData = new FormData();
    formData.append('id', driverId);
    appendCsrf(formData);

    const response = await fetch('/drivers/reset-password', {
        method: 'POST',
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}