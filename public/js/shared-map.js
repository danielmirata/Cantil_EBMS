// Shared Map Configuration
const MAP_CONFIG = {
    defaultLat: 9.280745008410356,
    defaultLng: 123.27235221862794,
    defaultZoom: 17,
    purokColors: {
        'Malipayon': '#0d6efd',
        'Masinadyahon': '#198754',
        'Mabinuligon': '#dc3545',
        'Madasigon': '#ffc107',
        'Mabungahon': '#6f42c1',
        'Mabaskog': '#fd7e14',
        'Mabakas': '#20c997',
        'Mabakod': '#e83e8c'
    }
};

// Initialize Map
function initializeMap(containerId, lat = MAP_CONFIG.defaultLat, lng = MAP_CONFIG.defaultLng, zoom = MAP_CONFIG.defaultZoom) {
    const map = L.map(containerId).setView([lat, lng], zoom);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    return map;
}

// Create Purok Icon
function createPurokIcon(color) {
    return L.divIcon({
        className: 'purok-marker',
        html: 'P',
        iconSize: [35, 35],
        iconAnchor: [17.5, 17.5],
        popupAnchor: [0, -17.5],
        style: `border-color: ${color}; color: ${color};`
    });
}

// Create Purok Marker with Statistics
function createPurokMarker(map, lat, lng, purokName, color = null) {
    const markerColor = color || MAP_CONFIG.purokColors[purokName] || '#0d6efd';
    const marker = L.marker([lat, lng], { icon: createPurokIcon(markerColor) });
    
    // Fetch purok statistics
    fetch(`/api/purok-stats/${purokName}`)
        .then(response => response.json())
        .then(stats => {
            const popupContent = generatePurokPopupContent(purokName, stats);
            marker.bindPopup(popupContent);
        })
        .catch(error => {
            console.error('Error loading purok statistics:', error);
            marker.bindPopup(`<div class="purok-popup"><h5>Purok ${purokName}</h5><p>Error loading statistics</p></div>`);
        });

    marker.addTo(map);
    return marker;
}

// Generate Purok Popup Content
function generatePurokPopupContent(purokName, stats) {
    return `
        <div class="purok-popup">
            <h5>Purok ${purokName}</h5>
            <div class="purok-stats">
                <div class="stat-group">
                    <h6>Population Overview</h6>
                    <p><span class="label">Total Residents:</span><span class="value">${stats.total_residents}</span></p>
                    <p><span class="label">Total Families:</span><span class="value">${stats.total_families}</span></p>
                    <p><span class="label">Population Density:</span><span class="value">${(stats.total_residents / stats.area || 0).toFixed(2)}/km²</span></p>
                </div>

                <div class="stat-group">
                    <h6>Demographics</h6>
                    <p><span class="label">Children (0-17):</span><span class="value">${stats.age_groups.children}</span></p>
                    <p><span class="label">Adults (18-59):</span><span class="value">${stats.age_groups.adults}</span></p>
                    <p><span class="label">Senior Citizens:</span><span class="value">${stats.age_groups.senior}</span></p>
                </div>

                <div class="stat-group">
                    <h6>Gender Distribution</h6>
                    <p><span class="label">Male:</span><span class="value">${stats.gender.male}</span></p>
                    <p><span class="label">Female:</span><span class="value">${stats.gender.female}</span></p>
                </div>

                <div class="stat-group">
                    <h6>Special Groups</h6>
                    <p><span class="label">PWD:</span><span class="value">${stats.pwd}</span></p>
                    <p><span class="label">Single Parent:</span><span class="value">${stats.single_parent}</span></p>
                    <p><span class="label">Registered Voters:</span><span class="value">${stats.voters.registered}</span></p>
                </div>

                <div class="stat-group">
                    <h6>Civil Status</h6>
                    <p><span class="label">Single:</span><span class="value">${stats.civil_status.single}</span></p>
                    <p><span class="label">Married:</span><span class="value">${stats.civil_status.married}</span></p>
                    <p><span class="label">Widowed:</span><span class="value">${stats.civil_status.widowed}</span></p>
                    <p><span class="label">Divorced:</span><span class="value">${stats.civil_status.divorced}</span></p>
                </div>
            </div>
        </div>
    `;
}

// Add Project Markers
function addProjectMarkers(map, projects) {
    projects.forEach(project => {
        if (project.coordinates) {
            const coords = JSON.parse(project.coordinates);
            const marker = L.marker(coords)
                .bindPopup(`
                    <div class="project-popup">
                        <h5>${project.project_name}</h5>
                        <p><strong>Status:</strong> ${project.status}</p>
                        <p><strong>Progress:</strong> ${project.progress}%</p>
                        <button class="btn btn-sm btn-primary" onclick="viewProjectDetails(${project.id})">
                            View Details
                        </button>
                    </div>
                `);
            marker.addTo(map);
        }
    });
}

// Search Functionality
function initializeSearch(searchInputId, searchResultsId, map) {
    const searchInput = document.getElementById(searchInputId);
    const searchResults = document.getElementById(searchResultsId);
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value.trim();
        
        if (searchTerm.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/api/search?q=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data, searchResults, map);
                })
                .catch(error => {
                    console.error('Error searching:', error);
                });
        }, 300);
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
}

// Display Search Results
function displaySearchResults(results, container, map) {
    container.innerHTML = '';
    
    if (results.length === 0) {
        container.innerHTML = '<div class="p-2 text-muted">No results found</div>';
        container.style.display = 'block';
        return;
    }

    const groupedResults = groupResultsByType(results);
    
    Object.entries(groupedResults).forEach(([type, items]) => {
        const typeHeader = document.createElement('div');
        typeHeader.className = 'p-2 bg-light border-bottom';
        typeHeader.textContent = type;
        container.appendChild(typeHeader);

        items.forEach(item => {
            const resultItem = document.createElement('div');
            resultItem.className = 'p-2 border-bottom search-result-item';
            resultItem.style.cursor = 'pointer';
            
            let description = item.description || '';
            if (item.household) {
                description = `${item.household.house_number}, ${item.household.street}`;
            }
            
            resultItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.title || item.name}</strong>
                        <div class="small text-muted">${description}</div>
                    </div>
                    <small class="text-muted">${item.type}</small>
                </div>
            `;
            
            resultItem.addEventListener('click', () => {
                if (item.coordinates) {
                    navigateToLocation(map, item);
                } else if (item.type === 'Household') {
                    fetch('/api/household-location/' + item.id)
                        .then(response => response.json())
                        .then(data => {
                            if (data.coordinates) {
                                item.coordinates = data.coordinates;
                                navigateToLocation(map, item);
                            } else {
                                alert('No map location found for this household.');
                            }
                        })
                        .catch(error => {
                            console.error('Error finding location:', error);
                            alert('Error finding location. Please try again.');
                        });
                }
                container.style.display = 'none';
                searchInput.value = item.title || item.name;
            });
            
            container.appendChild(resultItem);
        });
    });

    container.style.display = 'block';
}

// Group Results by Type
function groupResultsByType(results) {
    return results.reduce((acc, item) => {
        const type = item.type || 'Other';
        if (!acc[type]) {
            acc[type] = [];
        }
        acc[type].push(item);
        return acc;
    }, {});
}

// Navigate to Location
function navigateToLocation(map, item) {
    if (item.coordinates) {
        const coords = JSON.parse(item.coordinates);
        if (Array.isArray(coords[0])) {
            map.setView(coords[0], 18);
        } else {
            map.setView(coords, 18);
        }
    }
}

// Export functions
window.MapUtils = {
    initializeMap,
    createPurokMarker,
    addProjectMarkers,
    initializeSearch,
    navigateToLocation
}; 