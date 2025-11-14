/**
 * @file
 * Main entry point for the Routes Management page.
 *
 * This file initializes the UI and Event modules.
 *
 * UI Logic: './routes/routeUi.js'
 * Event Logic: './routes/routeEvents.js'
 * API Logic: './routes/routeApi.js'
 * Utilities: './utils.js'
 *
 * @license GNU
 */

// UI module-a import panrom
import * as ui from './routes/routeUi.js';

// Event listener module-a import panrom
import * as events from './routes/routeEvents.js';

// Page load aanathum, UI-ayum Events-ayum initialize panrom
document.addEventListener('DOMContentLoaded', function() {
    // Mudhal-la UI elements-a cache panrom
    ui.init();

    // Adutha, antha UI elements-ku event listeners-a attach panrom
    events.init();
});