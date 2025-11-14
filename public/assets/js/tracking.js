/**
 * @file
 * Main entry point for the Live Tracking page.
 *
 * This file initializes the UI, Events, Map, and API modules.
 *
 * UI Logic: './tracking/trackingUi.js'
 * Event Logic: './tracking/trackingEvents.js'
 * API Logic: './tracking/trackingApi.js'
 * Map Logic: './tracking/mapHandler.js'
 * Utilities: './utils.js'
 *
 * @license GNU
 */

import { showAlert } from './utils.js';
import * as trackingApi from './tracking/trackingApi.js';
import * as mapHandler from './tracking/mapHandler.js';
import * as ui from './tracking/trackingUi.js';
import * as events from './tracking/trackingEvents.js';

/**
 * Fetches bus locations and updates both map and UI list.
 * This is the main "controller" function.
 */
async function fetchBusLocations() {
    try {
        // 1. API-la irundhu data edu
        const buses = await trackingApi.fetchBusLocationsApi();

        // 2. Map-la markers-a update pannu
        mapHandler.updateMapMarkers(buses);

        // 3. Sidebar list-a update pannu
        ui.updateDriverList(buses);

    } catch (error) {
        console.error('Error fetching bus locations:', error);
        // Error-a kaattu
        showAlert(error.message, 'danger');
    }
}

/**
 * Main function to start the tracking page.
 */
function initializeTrackingApp() {
    // 1. UI elements-a cache pannu
    ui.init();

    // 2. Click listeners-a attach pannu
    events.init();

    // 3. Map-a initialize pannu. Map ready aanathum, data-va fetch pannu.
    mapHandler.initMap(fetchBusLocations);

    // 4. 10 second-ku oru thadava auto-refresh set panrom.
    setInterval(fetchBusLocations, 10000); 
}

// Start the app
initializeTrackingApp();