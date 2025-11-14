/**
 * @file
 * Main entry point for the Buses Management page.
 *
 * This file initializes the UI and Event modules.
 *
 * UI Logic: './buses/busUi.js'
 * Event Logic: './buses/busEvents.js'
 * API Logic: './buses/busApi.js'
 * Utilities: './utils.js'
 *
 * @license GNU
 */

// UI module-a import panrom
import * as ui from './buses/busUi.js';

// Event listener module-a import panrom
import * as events from './buses/busEvents.js';

// Page load aanathum, UI-ayum Events-ayum initialize panrom
document.addEventListener('DOMContentLoaded', function() {
    // Mudhal-la UI elements-a cache panrom
    ui.init();

    // Adutha, antha UI elements-ku event listeners-a attach panrom
    events.init();
});