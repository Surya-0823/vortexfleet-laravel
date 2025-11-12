// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    
    // Global variables for the map and markers
    let map;
    let busMarkers = {}; // Stores all bus markers { plate: marker }

    const driverListEl = document.getElementById('live-driver-list');
    const driverCountEl = document.getElementById('live-driver-count');
    const emptyStateEl = document.getElementById('live-driver-empty-state');

    /**
     * Creates a custom SVG bus icon for Leaflet.
     */
    function createBusIcon() {
        // Simple SVG bus icon
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
            className: 'custom-leaflet-bus-icon', // No extra CSS needed for this
            iconSize: [32, 32],
            iconAnchor: [16, 16], // Point of the icon which will correspond to marker's location
            popupAnchor: [0, -16] // Point from which the popup should open
        });
    }

    /**
     * Initialize the Leaflet map.
     */
    function initMap() {
        // Check if map element exists
        const mapElement = document.getElementById('map');
        if (!mapElement) {
            console.error('Map element #map not found!');
            return;
        }

        // 1. Initialize the map
        // Centered on a generic location (e.g., India)
        map = L.map('map').setView([20.5937, 78.9629], 5);

        // 2. Add the OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // 3. Fetch bus locations for the first time
        fetchBusLocations();

        // 4. Set an interval to refresh locations every 10 seconds
        setInterval(fetchBusLocations, 10000); // 10,000 ms = 10 seconds
    }

    /**
     * Fetch bus locations from the server.
     */
    async function fetchBusLocations() {
        try {
            // Namma TrackingController-la create panna AJAX route
            const response = await fetch('/ajax/admin/bus-locations', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                    // GET requests do not need CSRF
                }
            });

            if (!response.ok) {
                console.error('Failed to fetch bus locations. Status:', response.status);
                return;
            }

            const data = await response.json();

            if (data.success && data.buses) {
                // Send data to the marker update function
                updateMapMarkers(data.buses);
                updateDriverList(data.buses);
            }

        } catch (error) {
            console.error('Error fetching bus locations:', error);
        }
    }

    /**
     * Update map markers with new data.
     * @param {Array} buses - Array of bus objects from the API
     */
    function updateMapMarkers(buses) {
        const busIcon = createBusIcon();
        const activePlates = new Set(); // Keep track of buses in the new data

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
                // 1. If marker already exists, just move it
                busMarkers[plate].setLatLng(newPosition);
                busMarkers[plate].getPopup().setContent(popupContent);
            
            } else {
                // 2. If marker is new, create it
                const newMarker = L.marker(newPosition, { icon: busIcon })
                    .addTo(map)
                    .bindPopup(popupContent);
                
                // Store it in our tracking object
                busMarkers[plate] = newMarker;
            }
        });

        // 3. Remove old markers (for buses that are no longer active)
        for (const plate in busMarkers) {
            if (!activePlates.has(plate)) {
                // This bus is not in the new data, remove its marker
                map.removeLayer(busMarkers[plate]);
                delete busMarkers[plate];
            }
        }
        
        // 4. (Optional) Fit map to show all markers if it's the first load
        // This is complex, so we'll skip it for baby steps.
        // The map will just stay centered.
    }

    /**
     * Update the live driver list sidebar.
     * @param {Array} buses
     */
    function updateDriverList(buses) {
        if (!driverListEl) {
            return;
        }

        // Remove previous list items but keep the empty state element
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

            item.addEventListener('click', () => {
                focusOnBus(plate, current_lat, current_lon);
            });

            driverListEl.appendChild(item);
        });
    }

    function formatCoordinate(value) {
        if (value === null || value === undefined) {
            return '0.0000';
        }
        return Number.parseFloat(value).toFixed(4);
    }

    function focusOnBus(plate, lat, lon) {
        if (!map || !lat || !lon) {
            return;
        }
        map.setView([lat, lon], Math.max(map.getZoom(), 14), { animate: true });

        const marker = busMarkers[plate];
        if (marker) {
            marker.openPopup();
        }
    }

    // Start the map initialization process
    initMap();

});