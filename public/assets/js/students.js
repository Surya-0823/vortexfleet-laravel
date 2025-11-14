/**
 * @file
 * Main entry point for the Students Management page.
 *
 * This file initializes the UI and Event modules.
 *
 * UI Logic: './students/studentUi.js'
 * Event Logic: './students/studentEvents.js'
 * API Logic: './students/studentApi.js'
 * Utilities: './utils.js'
 *
 * @license GNU
 */

// UI module-a import panrom
import * as ui from './students/studentUi.js';

// Event listener module-a import panrom
import * as events from './students/studentEvents.js';

// Page load aanathum, UI-ayum Events-ayum initialize panrom
document.addEventListener('DOMContentLoaded', function() {
    // Mudhal-la UI elements-a cache panrom
    ui.init();

    // Adutha, antha UI elements-ku event listeners-a attach panrom
    events.init();
});