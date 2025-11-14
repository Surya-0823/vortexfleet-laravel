/**
 * @file
 * Handles all event listeners for the Live Tracking page.
 * Connects UI events (sidebar clicks) to map actions.
 * @license GNU
 */

import * as ui from './trackingUi.js';
import * as mapHandler from '../tracking/mapHandler.js'; // Oru folder veliya

/**
 * Attaches event listeners for the tracking page.
 * Uses event delegation for the sidebar list.
 */
function attachEventListeners() {
    if (ui.driverListEl) {
        ui.driverListEl.addEventListener('click', (event) => {
            // Click panna button-a kandupidi
            const item = event.target.closest('.tracking-sidebar__item');
            if (!item) return; // Button mela click pannalana, edhuvum pannadha

            // Button-la irundhu data-va edu
            const { plate, lat, lon } = item.dataset; // data-plate, data-lat, data-lon

            // Map-a focus pannu
            mapHandler.focusOnBus(plate, lat, lon);
        });
    }
}

/**
 * Initializes the event listeners.
 */
export function init() {
    attachEventListeners();
}