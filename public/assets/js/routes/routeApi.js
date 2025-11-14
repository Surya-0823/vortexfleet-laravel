/**
 * @file
 * Handles all API requests for the Routes Management page.
 * @license GNU
 */

// utils.js la irundhu common functions-a import panrom (oru folder veliya)
import { buildAjaxHeaders, appendCsrf } from '../utils.js';

/**
 * Submits the route form (Add or Edit) via AJAX.
 * @param {string} url - The URL to submit to.
 * @param {FormData} formData - The form data to send.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function submitRouteForm(url, formData) {
    // CSRF token-a append panrom
    appendCsrf(formData);

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
 * Sends an AJAX request to delete a route.
 * @param {string} url - The delete URL.
 * @param {string} routeId - The ID of the route to delete.
 * @returns {Promise<object>} - The JSON response from the server.
 */
export async function deleteRoute(url, routeId) {
    const formData = new FormData();
    formData.append('id', routeId);
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