// public.zip/assets/js/tracking.js
// =======================================================
// Top-Right Corner la Alert kaatrathukku function
// (Common helper function)
// =======================================================
function showAlert(message, type = 'success') {
    const refreshButton = document.getElementById('map-refresh-btn');
    if (!refreshButton) return;
    
    const originalText = 'Refresh Map';
    const originalSvg = refreshButton.querySelector('svg:not(.spinner-svg)');
    let spinner = refreshButton.querySelector('.spinner-svg');

    // Button-oda original styles
    const originalBg = 'hsl(var(--secondary))';
    const originalColor = 'hsl(var(--secondary-foreground))';
    
    let newBg = originalBg;
    let newColor = originalColor;
    let revertTime = 3000; 

    // 1. Loading/Alert Logic
    if (type === 'loading') {
        // Loading state
        revertTime = 500000; // Long time, fetch success/fail aana thaan stop aagum
        message = 'Refreshing...';
        newBg = 'hsl(var(--primary))'; // Default primary blue for loading
        newColor = 'hsl(var(--primary-foreground))';
        if (originalSvg) originalSvg.style.display = 'none';
        
        // Spinner illana, create pannu
        if (!spinner) {
            spinner = document.createElement('svg');
            spinner.className = 'spinner-svg lucide lucide-loader-2';
            spinner.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            spinner.setAttribute('width', '16');
            spinner.setAttribute('height', '16');
            spinner.setAttribute('viewBox', '0 0 24 24');
            spinner.setAttribute('fill', 'none');
            spinner.setAttribute('stroke', 'currentColor');
            spinner.setAttribute('stroke-width', '2');
            spinner.setAttribute('stroke-linecap', 'round');
            spinner.setAttribute('stroke-linejoin', 'round');
            spinner.style.animation = 'spin 1s linear infinite';
            spinner.innerHTML = '<path d="M21 12a9 9 0 1 1-9-9c2.3 3.1 3.9 3.9 3.9 3.9"/>';
            refreshButton.prepend(spinner);
            
            // CSS animation-ku style tag-a add panrom (if not exists)
            if (!document.getElementById('animate-spin-style')) {
                const style = document.createElement('style');
                style.id = 'animate-spin-style';
                style.innerHTML = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }`;
                document.head.appendChild(style);
            }
        } else {
             spinner.style.display = 'inline-block';
        }
    
    } else if (type === 'danger') {
        // Error state (Red)
        newBg = 'hsl(var(--destructive))';
        newColor = 'hsl(var(--destructive-foreground))';
        if (spinner) spinner.style.display = 'none';
        if (originalSvg) originalSvg.style.display = 'inline-block';
    
    } else if (type === 'success') {
        // Success state (Green)
        newBg = 'hsl(var(--success))';
        newColor = 'hsl(var(--destructive-foreground))'; 
        if (spinner) spinner.style.display = 'none';
        if (originalSvg) originalSvg.style.display = 'inline-block';
    }
    
    // 2. Apply new style & message
    refreshButton.style.transition = 'all 0.3s ease';
    refreshButton.style.backgroundColor = newBg;
    refreshButton.querySelector('span').style.color = newColor;
    refreshButton.querySelector('span').textContent = message;

    // 3. Revert after set time (if not loading)
    if (type !== 'loading') {
        setTimeout(() => {
            // Reset color and message to original state (Cyan)
            refreshButton.style.backgroundColor = originalBg;
            refreshButton.querySelector('span').style.color = originalColor;
            refreshButton.querySelector('span').textContent = originalText;
            if (originalSvg) originalSvg.style.display = 'inline-block';
            if (spinner) spinner.style.display = 'none';
        }, revertTime);
    }
}

// =======================================================
// Main Map Tracking Logic (OSM + Leaflet)
// =======================================================

document.addEventListener('DOMContentLoaded', function() {
    
    const mapContainer = document.getElementById('map-container');
    if (!mapContainer) return;

    // --- 1. Map Initialization (Leaflet) ---
    // Default location: Chennai (13.0827, 80.2707)
    let map = L.map('map-container').setView([13.0827, 80.2707], 13);
    let busMarkers = {}; // Active markers-ah track panna oru object
    let markersLayer = L.layerGroup().addTo(map); // Markers-ah oru layer-la vekkalaam

    // OpenStreetMap Tiles-ah add panrom
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // PUTHU MAATRAM: Leaflet Default Icon thaan ippo namma use panrom
    const defaultIcon = new L.Icon.Default();

    // --- 2. Data Fetching Function (UPDATED) ---
    function fetchBusLocations() {
        
        // PUTHU: List containers-ah select pannurom
        const listContainer = document.getElementById('bus-list-content');
        const countBadge = document.getElementById('bus-count-badge');
        
        // Manual refresh panna mattum 'loading' alert kaattu
        if (!this.auto) {
            showAlert('Refreshing...', 'loading');
        }

        fetch('/ajax/admin/bus-locations')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                
                if (data.success && data.buses && data.buses.length > 0) {
                    
                    const newBusPlates = data.buses.map(b => b.plate);
                    let boundsArray = [];
                    let listHtml = ''; // PUTHU: List HTML kaga oru variable

                    // 2a. New/Update Markers & Build List
                    data.buses.forEach(bus => {
                        const lat = parseFloat(bus.current_lat);
                        const lon = parseFloat(bus.current_lon);
                        
                        if (lat === 0 && lon === 0) return;

                        const driverName = bus.driver_name ?? 'No Driver';
                        const popupContent = `
                            <strong>Bus: ${bus.bus_name}</strong> (${bus.plate})<br>
                            Driver: ${driverName}<br>
                            Location: ${lat.toFixed(5)}, ${lon.toFixed(5)}
                        `;

                        // Marker logic (ithu pazhasu)
                        if (bus.plate in busMarkers) {
                            busMarkers[bus.plate].setLatLng([lat, lon]);
                            busMarkers[bus.plate].setPopupContent(popupContent);
                        } else {
                            const marker = L.marker([lat, lon], {icon: defaultIcon}).addTo(markersLayer);
                            marker.bindPopup(popupContent);
                            busMarkers[bus.plate] = marker;
                        }
                        boundsArray.push([lat, lon]);
                        
                        // PUTHU: List HTML-ah build pannu
                        listHtml += `
                            <div class="bus-list-item" data-plate="${bus.plate}" data-lat="${lat}" data-lon="${lon}">
                                <div class="bus-item-header">
                                    <span>${bus.bus_name}</span>
                                    <span class="bus-item-plate">${bus.plate}</span>
                                </div>
                                <div class="bus-item-driver">
                                    Driver: ${driverName}
                                </div>
                            </div>
                        `;
                    });
                    
                    // PUTHU: List-ah update pannu
                    if(listContainer) listContainer.innerHTML = listHtml;
                    if(countBadge) countBadge.textContent = `${data.buses.length} Buses`;

                    // 2b. Remove Stale Markers
                    Object.keys(busMarkers).forEach(plate => {
                        if (!newBusPlates.includes(plate)) {
                            markersLayer.removeLayer(busMarkers[plate]);
                            delete busMarkers[plate];
                        }
                    });

                    // 2c. Map-ah fit pannu (First time load-la mattum)
                    if (this.auto !== true) { // 'auto' property-ah use pannurom
                         if (boundsArray.length > 0) {
                            map.fitBounds(boundsArray, {padding: [50, 50]});
                        }
                    }
                    
                    if (this.auto !== true) { 
                        showAlert(`Updated ${data.buses.length} buses.`, 'success');
                    }
                    
                } else if (data.buses && data.buses.length === 0) {
                    if (this.auto !== true) showAlert('No active buses.', 'warning');
                    markersLayer.clearLayers();
                    busMarkers = {};
                    if(listContainer) listContainer.innerHTML = '<div class="bus-list-empty"><span>No active buses found.</span></div>';
                    if(countBadge) countBadge.textContent = '0 Buses';
                }
            })
            .catch(error => {
                console.error('Map Data Fetch Error:', error);
                if (this.auto !== true) showAlert('Update failed!', 'danger');
                if(listContainer) listContainer.innerHTML = '<div class="bus-list-empty" style="color: hsl(var(--destructive));"><span>Data fetch failed.</span></div>';
            })
            .finally(() => {
                // Ithu auto-refresh nu mark pannu
                this.auto = true;
            });
    }

    // --- 3. Initial Load & Auto Refresh ---
    fetchBusLocations.call({ auto: false }); // First time load panna 'auto' false nu sollu
    setInterval(fetchBusLocations.bind({ auto: true }), 5000); // Auto refresh kaga 'auto' true nu sollu

    // Refresh button click event
    const refreshButton = document.getElementById('map-refresh-btn');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            fetchBusLocations.call({ auto: false }); // Manual refresh
        });
    }
    
    // =======================================================
    // --- PUTHU LOGIC: List Click Event Listener ---
    // =======================================================
    const listSidebar = document.getElementById('bus-list-sidebar');
    if (listSidebar) {
        listSidebar.addEventListener('click', function(e) {
            const item = e.target.closest('.bus-list-item');
            if (item) {
                const lat = parseFloat(item.dataset.lat);
                const lon = parseFloat(item.dataset.lon);
                const plate = item.dataset.plate;

                if (!lat || !lon || !plate) return;

                // 1. Map-ah antha location-ku fly pannu
                map.flyTo([lat, lon], 16); // 16 = Zoom level

                // 2. Antha marker-oda popup-ah open pannu
                if (busMarkers[plate]) {
                    busMarkers[plate].openPopup();
                }
            }
        });
    }
    
});