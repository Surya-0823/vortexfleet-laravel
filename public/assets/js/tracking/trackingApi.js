/**
 * @file
 * Handles all API requests for the Live Tracking page.
 * @license GNU
 */

// utils.js la irundhu common functions-a import panrom (oru folder veliya)
import { buildAjaxHeaders } from '../utils.js';

/**
 * Fetches the latest bus locations from the server.
 * @returns {Promise<Array>} - A promise that resolves to the array of bus data.
 */
export async function fetchBusLocationsApi() {
    const response = await fetch('/ajax/admin/bus-locations', {
        method: 'GET',
        headers: buildAjaxHeaders({ Accept: 'application/json' })
    });

    if (!response.ok) {
        console.error('Failed to fetch bus locations. Status:', response.status);
        // Error-a throw panrom, tracking.js la catch pannikalam
        throw new Error('Failed to fetch bus locations. Server returned status: ' + response.status);
    }

    const data = await response.json();

    if (data.success && data.buses) {
        return data.buses; // Bus data-va mattum return panrom
    } else if (!data.success) {
        // Server sonna error-a throw panrom
        throw new Error(data.message || 'Error loading bus data.');
    }

    return []; // Data illana, empty array return panrom
}