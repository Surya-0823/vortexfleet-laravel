/**
 * @file
 * Handles all UI logic for the Live Tracking page sidebar.
 * Includes DOM caching and list rendering.
 * @license GNU
 */

// =======================================================
// --- DOM Elements Cache ---
// =======================================================
export let driverListEl, driverCountEl, emptyStateEl;

/**
 * Caches all required DOM elements into global variables.
 */
function cacheDOMElements() {
    driverListEl = document.getElementById('live-driver-list');
    driverCountEl = document.getElementById('live-driver-count');
    emptyStateEl = document.getElementById('live-driver-empty-state');
}

function formatCoordinate(value) {
    if (value === null || value === undefined) {
        return '0.0000';
    }
    return Number.parseFloat(value).toFixed(4);
}

/**
 * Update the live driver list sidebar.
 * @param {Array} buses
 */
export function updateDriverList(buses) {
    if (!driverListEl) {
        return;
    }

    // Remove previous list items
    driverListEl
        .querySelectorAll('.tracking-sidebar__item')
        .forEach((node) => node.remove());

    if (driverCountEl) {
        driverCountEl.textContent = buses.length;
    }

    if (!buses.length) {
        if (emptyStateEl) {
            emptyStateEl.style.display = 'flex';
        }
        return;
    }

    if (emptyStateEl) {
        emptyStateEl.style.display = 'none';
    }

    buses.forEach((bus) => {
        const { plate, current_lat, current_lon, bus_name, driver_name } = bus;
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'tracking-sidebar__item';

        // PUTHU MAATRAM: Click event-kaga data-attributes set panrom
        item.setAttribute('data-plate', plate);
        item.setAttribute('data-lat', current_lat);
        item.setAttribute('data-lon', current_lon);

        item.innerHTML = `
            <div class="tracking-sidebar__item-title">
                <span>${bus_name || 'Unnamed Bus'}</span>
                <span class="bus-item-plate">${plate}</span>
            </div>
            <div class="tracking-sidebar__item-meta">
                Driver: ${driver_name || 'Not assigned'}
            </div>
            <div class="tracking-sidebar__item-location">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation">
                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                </svg>
                ${formatCoordinate(current_lat)}, ${formatCoordinate(current_lon)}
            </div>
        `;

        // Event listener inga add pannala

        driverListEl.appendChild(item);
    });
}

/**
 * Initializes the UI module.
 */
export function init() {
    cacheDOMElements();
}