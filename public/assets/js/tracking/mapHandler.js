/**
 * @file
 * Handles all Leaflet map logic for the Live Tracking page.
 * Includes map initialization, icon creation, and marker management.
 * @license GNU
 */

// Module-level variables (private to this file)
let map;
let busMarkers = {}; // Stores all bus markers { plate: marker }

/**
 * Creates a custom SVG bus icon for Leaflet.
 * (Internal helper function, no need to export)
 */
function createBusIcon() {
    const svgIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             style="background-color: #2563eb; border-radius: 50%; padding: 4px; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.5);">
            <path d="M8 6v6"></path><path d="M15 6v6"></path><path d="M2 12h19.6"></path>
            <path d="M18 18h3s-1-1.5-1.5-2.5S19 14 19 14"></path><path d="M6 18H3s1-1.5 1.5-2.5S5 14 5 14"></path>
            <rect width="20" height="10" x="2" y="8" rx="2"></rect>
        </svg>
    `;

    return L.divIcon({
        html: svgIcon,
        className: 'custom-leaflet-bus-icon',
        iconSize: [32, 32],
        iconAnchor: [16, 16],
        popupAnchor: [0, -16]
    });
}

/**
 * Initialize the Leaflet map.
 * @param {Function} onMapReady - Callback function to run after map is ready.
 */
export function initMap(onMapReady) {
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('Map element #map not found!');
        return;
    }

    // 1. Initialize the map
    map = L.map('map').setView([20.5937, 78.9629], 5);

    // 2. Add the OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // 3. Map ready aanathum, callback-a run panrom (e.g., fetchBusLocations)
    if (onMapReady) {
        onMapReady();
    }
}

/**
 * Update map markers with new data.
 * @param {Array} buses - Array of bus objects from the API
 */
export function updateMapMarkers(buses) {
    if (!map) return; // Map innum initialize aagala

    const busIcon = createBusIcon();
    const activePlates = new Set(); 

    buses.forEach(bus => {
        const { plate, current_lat, current_lon, bus_name, driver_name } = bus;
        activePlates.add(plate);

        const newPosition = [current_lat, current_lon];
        const popupContent = `
            <b>${bus_name || 'Unknown Bus'}</b><br>
            Plate: ${plate}<br>
            Driver: ${driver_name || 'N/A'}
        `;

        if (busMarkers[plate]) {
            busMarkers[plate].setLatLng(newPosition);
            busMarkers[plate].getPopup().setContent(popupContent);
        } else {
            const newMarker = L.marker(newPosition, { icon: busIcon })
                .addTo(map)
                .bindPopup(popupContent);
            busMarkers[plate] = newMarker;
        }
    });

    for (const plate in busMarkers) {
        if (!activePlates.has(plate)) {
            map.removeLayer(busMarkers[plate]);
            delete busMarkers[plate];
        }
    }
}

/**
 * Focuses the map on a specific bus marker.
 * @param {string} plate
 * @param {number} lat
 * @param {number} lon
 */
export function focusOnBus(plate, lat, lon) {
    if (!map || !lat || !lon) {
        return;
    }
    map.setView([lat, lon], Math.max(map.getZoom(), 14), { animate: true });

    const marker = busMarkers[plate];
    if (marker) {
        marker.openPopup();
    }
}