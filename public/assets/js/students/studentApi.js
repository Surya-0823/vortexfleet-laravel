/**
 * @file
 * Handles all API requests for the Students Management page.
 * @license GNU
 */

// utils.js la irundhu common functions-a import panrom (oru folder veliya)
import { buildAjaxHeaders, appendCsrf } from '../utils.js';

/**
 * Submits the student form (Add or Edit) via AJAX.
 * @param {string} url - The URL to submit to.
 * @param {FormData} formData - The form data to send.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function submitStudentForm(url, formData) {
    // CSRF token-a append panradha inga pannikalam (already form-la irundhaalum, safe-kaga)
    if (!formData.has('_token')) {
        appendCsrf(formData);
    }

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
        throw data; // Server error data-va (e.g., validation errors) throw panrom
    }
    return data;
}

/**
 * Sends an AJAX request to delete a student.
 * @param {string} url - The delete URL.
 * @param {string} studentId - The ID of the student to delete.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function deleteStudent(url, studentId) {
    const formData = new FormData();
    formData.append('id', studentId);
    appendCsrf(formData);

    const response = await fetch(url, {
        method: 'POST',
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    const data = await response.json();
    if (!response.ok) {
        throw data;
    }
    return data;
}

/**
 * Sends an AJAX request to update a student's verification status.
 * @param {string} studentId - The ID of the student.
 * @param {number} newStatus - The new status (e.g., 0 for Not Verified).
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function updateStudentStatus(studentId, newStatus) {
    const formData = new FormData();
    formData.append('id', studentId);
    formData.append('is_verified', newStatus);
    appendCsrf(formData);

    const response = await fetch('/students/update-status', {
        method: 'POST', 
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}

/**
 * Sends an AJAX request to generate and send an OTP.
 * @param {string} userId - The ID of the student.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function sendOtp(userId) {
    const formData = new FormData();
    formData.append('student_id', userId);
    appendCsrf(formData);

    const response = await fetch('/students/send-otp', {
        method: 'POST', 
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}

/**
 * Sends an AJAX request to verify the entered OTP.
 * @param {string} userId - The ID of the student.
 * @param {string} otpCode - The 6-digit OTP code.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function verifyOtp(userId, otpCode) {
    const formData = new FormData();
    formData.append('student_id', userId);
    formData.append('otp_code', otpCode);
    appendCsrf(formData);

    const response = await fetch('/students/verify-otp', {
        method: 'POST',
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}

/**
 * Sends an AJAX request to reset a student's password.
 * @param {string} studentId - The ID of the student.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function resetPassword(studentId) {
    const formData = new FormData();
    formData.append('id', studentId);
    appendCsrf(formData);

    const response = await fetch('/students/reset-password', {
        method: 'POST',
        body: formData,
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    return response.json();
}