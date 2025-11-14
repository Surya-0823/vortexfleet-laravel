/**
 * @file
 * Main entry point for the Drivers Management page.
 *
 * This file initializes the UI and Event modules.
 *
 * UI Logic: './drivers/driverUi.js'
 * Event Logic: './drivers/driverEvents.js'
 * API Logic: './drivers/driverApi.js'
 * Utilities: './utils.js'
 *
 * @license GNU
 */

// UI module-a import panrom
import * as ui from './drivers/driverUi.js';

// Event listener module-a import panrom
import * as events from './drivers/driverEvents.js';

// Page load aanathum, UI-ayum Events-ayum initialize panrom
document.addEventListener('DOMContentLoaded', function() {
    // Mudhal-la UI elements-a cache panrom
    ui.init();

    // Adutha, antha UI elements-ku event listeners-a attach panrom
    events.init();
});