@extends('layouts.app')

@section('title', 'Barangay Map')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
<style>
  #map {
    height: 75vh;
    width: 100%;
    border-radius: 4px;
  }
  .card {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
  }
  .btn-toolbar {
    margin-bottom: 1rem;
  }
  .btn-toolbar .btn {
    margin-right: 0.5rem;
  }
  #locationForm {
    display: none;
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 4px;
  }
  .location-popup {
    min-width: 300px;
  }
  .purok-marker {
    background-color: #fff;
    border: 2px solid #0d6efd;
    border-radius: 50%;
    text-align: center;
    font-weight: bold;
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
  }
  .purok-marker:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
  }
  .purok-popup {
    min-width: 300px;
  }
  .purok-popup h5 {
    color: #0d6efd;
    margin-bottom: 15px;
    border-bottom: 2px solid #0d6efd;
    padding-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .purok-popup .edit-btn {
    font-size: 0.8em;
    padding: 2px 8px;
  }
  .purok-stats {
    margin-top: 10px;
  }
  .purok-stats .stat-group {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
  }
  .purok-stats .stat-group:last-child {
    border-bottom: none;
  }
  .purok-stats .stat-group h6 {
    color: #6c757d;
    margin-bottom: 8px;
    font-size: 0.9em;
    text-transform: uppercase;
  }
  .purok-stats p {
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    font-size: 0.9em;
  }
  .purok-stats .label {
    font-weight: 500;
    color: #6c757d;
  }
  .purok-stats .value {
    color: #0d6efd;
  }
  .color-picker {
    display: flex;
    gap: 8px;
    margin-top: 15px;
    flex-wrap: wrap;
  }
  .color-option {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: all 0.2s ease;
  }
  .color-option:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
  }
  .color-option.active {
    border: 2px solid #000;
    transform: scale(1.1);
  }
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Interactive Barangay Map</h3>
        </div>
        <div class="card-body">
          <div class="btn-toolbar">
            <div class="input-group me-2" style="max-width: 300px;">
              <input type="text" id="searchInput" class="form-control" placeholder="Search anything...">
              <button class="btn btn-outline-secondary" type="button" id="searchButton">
                <i class="fas fa-search"></i>
              </button>
            </div>
            <div id="searchResults" class="position-absolute bg-white shadow-sm rounded" style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto; width: 300px;">
            </div>
            <button id="draw-rectangle" class="btn btn-outline-primary btn-sm">
              <i class="fas fa-square"></i> Draw Rectangle
            </button>
            <button id="draw-polygon" class="btn btn-outline-success btn-sm">
              <i class="fas fa-draw-polygon"></i> Draw Polygon
            </button>
            <button id="draw-marker" class="btn btn-outline-info btn-sm">
              <i class="fas fa-map-marker-alt"></i> Place Marker
            </button>
          </div>
          <div id="map"></div>

          <!-- Location Details Form -->
          <form id="locationForm" class="needs-validation" novalidate>
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="title" name="title" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="household" class="form-label">Link to Household (Optional)</label>
                  <select class="form-select" id="household" name="household_id">
                    <option value="">No Household</option>
                  </select>
                  <div class="form-text">Leave as "No Household" if this location is not associated with any household.</div>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description (Optional)</label>
              <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter any additional details about this location"></textarea>
            </div>
            <div class="mb-3">
              <label for="color" class="form-label">Color (Optional)</label>
              <input type="color" class="form-control form-control-color" id="color" name="color" value="#ff0000">
              <div class="form-text">Choose a color for the shape or marker.</div>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Save Location</button>
              <button type="button" class="btn btn-secondary" id="cancelForm">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<script>
  // Get coordinates from URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const lat = urlParams.get('lat') || 9.280745008410356;
  const lng = urlParams.get('lng') || 123.27235221862794;
  const zoom = urlParams.get('zoom') || 17;

  // Initialize map with OpenStreetMap tiles
  const map = L.map('map').setView([lat, lng], zoom);
  let currentLayer = null;
  let currentType = null;
  let isPlacingMarker = false;
  let purokMarkers = {};

  // Add OpenStreetMap tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19,
  }).addTo(map);

  // Predefined colors for puroks
  const purokColors = {
    'Malipayon': '#0d6efd',
    'Masinadyahon': '#198754',
    'Mabinuligon': '#dc3545',
    'Madasigon': '#ffc107',
    'Mabungahon': '#6f42c1',
    'Mabaskog': '#fd7e14',
    'Mabakas': '#20c997',
    'Mabakod': '#e83e8c'
  };

  // Custom Purok Icon with color
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

  // Function to create a purok marker
  function createPurokMarker(lat, lng, purokName, color = null) {
    const markerColor = color || purokColors[purokName] || '#0d6efd';
    const marker = L.marker([lat, lng], { icon: createPurokIcon(markerColor) });
    
    // Fetch purok statistics
    fetch(`/map-locations/purok-stats/${purokName}`)
      .then(response => response.json())
      .then(stats => {
        const popupContent = `
          <div class="purok-popup">
            <h5>
              Purok ${purokName}
              <button class="btn btn-sm btn-outline-primary edit-btn" onclick="editPurokMarker('${purokName}', ${lat}, ${lng}, '${markerColor}')">
                <i class="fas fa-edit"></i> Edit
              </button>
            </h5>
            <div class="purok-stats">
              <div class="stat-group">
                <h6>Population Overview</h6>
                <p>
                  <span class="label">Total Residents:</span>
                  <span class="value">${stats.total_residents}</span>
                </p>
                <p>
                  <span class="label">Total Families:</span>
                  <span class="value">${stats.total_families}</span>
                </p>
                <p>
                  <span class="label">Population Density:</span>
                  <span class="value">${(stats.total_residents / stats.area || 0).toFixed(2)}/km²</span>
                </p>
              </div>

              <div class="stat-group">
                <h6>Demographics</h6>
                <p>
                  <span class="label">Children (0-17):</span>
                  <span class="value">${stats.age_groups.children}</span>
                </p>
                <p>
                  <span class="label">Adults (18-59):</span>
                  <span class="value">${stats.age_groups.adults}</span>
                </p>
                <p>
                  <span class="label">Senior Citizens:</span>
                  <span class="value">${stats.age_groups.senior}</span>
                </p>
              </div>

              <div class="stat-group">
                <h6>Gender Distribution</h6>
                <p>
                  <span class="label">Male:</span>
                  <span class="value">${stats.gender.male}</span>
                </p>
                <p>
                  <span class="label">Female:</span>
                  <span class="value">${stats.gender.female}</span>
                </p>
              </div>

              <div class="stat-group">
                <h6>Special Groups</h6>
                <p>
                  <span class="label">PWD:</span>
                  <span class="value">${stats.pwd}</span>
                </p>
                <p>
                  <span class="label">Single Parent:</span>
                  <span class="value">${stats.single_parent}</span>
                </p>
                <p>
                  <span class="label">Registered Voters:</span>
                  <span class="value">${stats.voters.registered}</span>
                </p>
              </div>

              <div class="stat-group">
                <h6>Civil Status</h6>
                <p>
                  <span class="label">Single:</span>
                  <span class="value">${stats.civil_status.single}</span>
                </p>
                <p>
                  <span class="label">Married:</span>
                  <span class="value">${stats.civil_status.married}</span>
                </p>
                <p>
                  <span class="label">Widowed:</span>
                  <span class="value">${stats.civil_status.widowed}</span>
                </p>
                <p>
                  <span class="label">Divorced:</span>
                  <span class="value">${stats.civil_status.divorced}</span>
                </p>
              </div>
            </div>
          </div>
        `;
        marker.bindPopup(popupContent);
      })
      .catch(error => {
        console.error('Error loading purok statistics:', error);
        marker.bindPopup(`<div class="purok-popup"><h5>Purok ${purokName}</h5><p>Error loading statistics</p></div>`);
      });

    return marker;
  }

  // Function to edit purok marker
  function editPurokMarker(purokName, lat, lng, currentColor) {
    const newLat = prompt('Enter new latitude:', lat);
    if (newLat === null) return;
    
    const newLng = prompt('Enter new longitude:', lng);
    if (newLng === null) return;

    const colorOptions = Object.entries(purokColors).map(([name, color]) => 
      `<div class="color-option ${color === currentColor ? 'active' : ''}" 
           style="background-color: ${color}" 
           onclick="updatePurokMarker('${purokName}', ${newLat}, ${newLng}, '${color}')"
           title="${name}"></div>`
    ).join('');

    const colorPicker = document.createElement('div');
    colorPicker.className = 'color-picker';
    colorPicker.innerHTML = colorOptions;
    
    const popup = document.createElement('div');
    popup.innerHTML = `
      <div class="purok-popup">
        <h5>Choose Color for Purok ${purokName}</h5>
        <div class="color-picker">
          ${colorOptions}
        </div>
      </div>
    `;

    L.popup()
      .setLatLng([newLat, newLng])
      .setContent(popup)
      .openOn(map);
  }

  // Function to update purok marker
  function updatePurokMarker(purokName, lat, lng, color) {
    // Remove existing marker
    if (purokMarkers[purokName]) {
      map.removeLayer(purokMarkers[purokName]);
    }

    // Create and add new marker
    const marker = createPurokMarker(lat, lng, purokName, color);
    marker.addTo(map);
    purokMarkers[purokName] = marker;

    // Update in database
    const data = {
      type: 'purok',
      coordinates: JSON.stringify([lat, lng]),
      title: `Purok ${purokName}`,
      description: `Purok ${purokName} Marker`,
      color: color
    };

    // Check if marker already exists
    fetch('/map-locations')
      .then(response => response.json())
      .then(locations => {
        const existingLocation = locations.find(loc => 
          loc.type === 'purok' && loc.title === `Purok ${purokName}`
        );

        const url = existingLocation 
          ? `/map-locations/${existingLocation.id}`
          : '/map-locations';
        
        const method = existingLocation ? 'PUT' : 'POST';

        fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(data)
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(location => {
          console.log('Purok marker saved:', location);
          map.closePopup();
          
          // Show success message
          const toast = document.createElement('div');
          toast.className = 'toast show position-fixed bottom-0 end-0 m-3';
          toast.style.backgroundColor = '#28a745';
          toast.style.color = 'white';
          toast.style.padding = '10px 20px';
          toast.style.borderRadius = '4px';
          toast.style.zIndex = '1000';
          toast.innerHTML = `Purok ${purokName} marker saved successfully!`;
          document.body.appendChild(toast);
          
          // Remove toast after 3 seconds
          setTimeout(() => {
            toast.remove();
          }, 3000);
        })
        .catch(error => {
          console.error('Error saving purok marker:', error);
          alert('Error saving purok marker. Please try again.');
        });
      })
      .catch(error => {
        console.error('Error checking existing markers:', error);
        alert('Error checking existing markers. Please try again.');
      });
  }

  // Function to handle purok marker placement
  function handlePurokMarkerPlacement(e) {
    if (!isPlacingMarker) return;

    const purokName = prompt('Enter Purok Name:');
    if (!purokName) {
      isPlacingMarker = false;
      return;
    }

    // Show color picker
    const colorOptions = Object.entries(purokColors).map(([name, color]) => 
      `<div class="color-option" 
           style="background-color: ${color}" 
           onclick="updatePurokMarker('${purokName}', ${e.latlng.lat}, ${e.latlng.lng}, '${color}')"
           title="${name}"></div>`
    ).join('');

    const popup = document.createElement('div');
    popup.innerHTML = `
      <div class="purok-popup">
        <h5>Choose Color for Purok ${purokName}</h5>
        <div class="color-picker">
          ${colorOptions}
        </div>
      </div>
    `;

    L.popup()
      .setLatLng(e.latlng)
      .setContent(popup)
      .openOn(map);

    isPlacingMarker = false;
    map.getContainer().classList.remove('marker-placement-mode');
    document.getElementById('draw-marker').classList.remove('active');
  }

  // Update the draw-marker button click handler for purok markers
  document.getElementById('draw-marker').addEventListener('click', function() {
    if (isPlacingMarker) {
      // Cancel marker placement
      isPlacingMarker = false;
      map.off('click', handlePurokMarkerPlacement);
      map.getContainer().classList.remove('marker-placement-mode');
      this.classList.remove('active');
    } else {
      // Start marker placement
      isPlacingMarker = true;
      map.getContainer().classList.add('marker-placement-mode');
      this.classList.add('active');
      map.once('click', handlePurokMarkerPlacement);
    }
  });

  // Add CSS for marker placement mode
  const style = document.createElement('style');
  style.textContent = `
    .marker-placement-mode {
      cursor: crosshair !important;
    }
    .marker-placement-mode .leaflet-container {
      cursor: crosshair !important;
    }
    .purok-marker {
      transition: all 0.3s ease;
    }
    .purok-marker:hover {
      transform: scale(1.1);
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    .color-picker {
      display: flex;
      gap: 8px;
      margin-top: 15px;
      flex-wrap: wrap;
    }
    .color-option {
      width: 25px;
      height: 25px;
      border-radius: 50%;
      cursor: pointer;
      border: 2px solid #fff;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
      transition: all 0.2s ease;
    }
    .color-option:hover {
      transform: scale(1.1);
      box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    .color-option.active {
      border: 2px solid #000;
      transform: scale(1.1);
    }
  `;
  document.head.appendChild(style);

  // Load existing purok markers
  fetch('/map-locations')
    .then(response => response.json())
    .then(locations => {
      locations.forEach(location => {
        if (location.type === 'purok') {
          try {
            const coords = JSON.parse(location.coordinates);
            const purokName = location.title.replace('Purok ', '');
            // Always use backend color if available, fallback to predefined
            const markerColor = location.color || purokColors[purokName] || '#0d6efd';
            if (!purokMarkers[purokName]) {
              const marker = createPurokMarker(coords[0], coords[1], purokName, markerColor);
              marker.addTo(map);
              purokMarkers[purokName] = marker;
            }
          } catch (error) {
            console.error('Error loading purok marker:', error);
          }
        }
      });
    })
    .catch(error => console.error('Error fetching purok markers:', error));

  // NOTE: If color is still not working, ensure your backend saves and returns the color field for purok markers in /map-locations endpoints.

  // Leaflet Draw Feature Group
  const drawnItems = new L.FeatureGroup();
  map.addLayer(drawnItems);

  const drawControl = new L.Control.Draw({
    edit: {
      featureGroup: drawnItems,
      remove: true
    },
    draw: {
      polygon: true,
      rectangle: true,
      marker: true,
      circle: false,
      circlemarker: false,
      polyline: false
    }
  });

  map.addControl(drawControl);

  // Function to convert coordinates to the correct format
  function getCoordinates(layer, type) {
    if (type === 'marker') {
      const latLng = layer.getLatLng();
      return [latLng.lat, latLng.lng];
    } else if (type === 'rectangle') {
      const bounds = layer.getBounds();
      return [
        [bounds.getNorth(), bounds.getWest()],
        [bounds.getNorth(), bounds.getEast()],
        [bounds.getSouth(), bounds.getEast()],
        [bounds.getSouth(), bounds.getWest()]
      ];
    } else if (type === 'polygon') {
      return layer.getLatLngs()[0].map(latLng => [latLng.lat, latLng.lng]);
    }
  }

  // Function to create layer from saved coordinates
  function createLayer(type, coords, color) {
    let layer;
    try {
      if (type === 'marker') {
        layer = L.marker(coords);
      } else if (type === 'rectangle' || type === 'polygon') {
        layer = type === 'rectangle' ? L.polygon(coords) : L.polygon(coords);
        if (color) {
          layer.setStyle({ color: color });
        }
      }
      return layer;
    } catch (error) {
      console.error('Error creating layer:', error);
      return null;
    }
  }

  // Load existing locations
  fetch('/map-locations')
    .then(response => response.json())
    .then(locations => {
      locations.forEach(location => {
        try {
          const coords = JSON.parse(location.coordinates);
          const layer = createLayer(location.type, coords, location.color);
          
          if (layer) {
            const popupContent = `
              <div class="location-popup">
                <h5>${location.title}</h5>
                ${location.description ? `<p>${location.description}</p>` : ''}
                ${location.household ? `
                  <p><strong>Household:</strong> ${location.household.name} - ${location.household.house_number}, ${location.household.street}</p>
                  <button class="btn btn-info btn-sm" onclick="viewHouseholdMembers(${location.household.id})">
                    <i class="fas fa-users me-1"></i> View Members
                  </button>
                ` : ''}
                <button class="btn btn-danger btn-sm" onclick="deleteLocation(${location.id})">Delete</button>
              </div>
            `;
            
            layer.bindPopup(popupContent);
            drawnItems.addLayer(layer);
          }
        } catch (error) {
          console.error('Error loading location:', error);
        }
      });
    })
    .catch(error => console.error('Error fetching locations:', error));

  // Load households for select dropdown
  let households = []; // Store households data

  // Load households for search
  fetch('/map-locations/households')
    .then(response => response.json())
    .then(data => {
      households = data;
      const select = document.getElementById('household');
      households.forEach(household => {
        const option = document.createElement('option');
        option.value = household.id;
        option.textContent = `${household.name} - ${household.house_number}, ${household.street}`;
        select.appendChild(option);
      });
    });

  // Global search functionality
  let searchTimeout;
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');

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
          displaySearchResults(data);
        })
        .catch(error => {
          console.error('Error searching:', error);
        });
    }, 300);
  });

  function displaySearchResults(results) {
    searchResults.innerHTML = '';
    
    if (results.length === 0) {
      searchResults.innerHTML = '<div class="p-2 text-muted">No results found</div>';
      searchResults.style.display = 'block';
      return;
    }

    const groupedResults = groupResultsByType(results);
    
    Object.entries(groupedResults).forEach(([type, items]) => {
      const typeHeader = document.createElement('div');
      typeHeader.className = 'p-2 bg-light border-bottom';
      typeHeader.textContent = type;
      searchResults.appendChild(typeHeader);

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
            navigateToLocation(item);
          } else if (item.type === 'Household') {
            // If it's a household without coordinates, try to find its location
            fetch('/map-locations')
              .then(response => response.json())
              .then(locations => {
                const location = locations.find(loc => loc.household_id === item.id);
                if (location) {
                  item.coordinates = location.coordinates;
                  item.color = location.color;
                  navigateToLocation(item);
                } else {
                  alert('No map location found for this household.');
                }
              })
              .catch(error => {
                console.error('Error finding location:', error);
                alert('Error finding location. Please try again.');
              });
          }
          searchResults.style.display = 'none';
          searchInput.value = item.title || item.name;
        });
        
        searchResults.appendChild(resultItem);
      });
    });

    searchResults.style.display = 'block';
  }

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

  function navigateToLocation(item) {
    if (item.coordinates) {
      const coords = JSON.parse(item.coordinates);
      if (Array.isArray(coords[0])) {
        map.setView(coords[0], 18);
      } else {
        map.setView(coords, 18);
      }

      // Highlight the location
      drawnItems.eachLayer(layer => {
        if (layer.getPopup() && layer.getPopup().getContent().includes(item.title || item.name)) {
          layer.openPopup();
          if (layer.setStyle) {
            layer.setStyle({ color: '#ff0000', weight: 3 });
            setTimeout(() => {
              layer.setStyle({ color: item.color || '#3388ff' });
            }, 2000);
          }
        }
      });
    }
  }

  // Close search results when clicking outside
  document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
      searchResults.style.display = 'none';
    }
  });

  // Handle drawing events
  map.on(L.Draw.Event.CREATED, function (e) {
    currentLayer = e.layer;
    currentType = e.layerType;
    showLocationForm(e.layerType, getCoordinates(currentLayer, e.layerType));
  });

  function showLocationForm(type, coordinates) {
    document.getElementById('locationForm').style.display = 'block';
    document.getElementById('locationForm').onsubmit = function(e) {
      e.preventDefault();
      saveLocation(type, coordinates);
    };
  }

  // Drawing button handlers
  let currentDrawControl = null;

  function startDrawing(type) {
    if (currentDrawControl) {
      currentDrawControl.disable();
    }

    let drawer;
    switch(type) {
      case 'rectangle':
        drawer = new L.Draw.Rectangle(map, drawControl.options.draw.rectangle);
        break;
      case 'polygon':
        drawer = new L.Draw.Polygon(map, drawControl.options.draw.polygon);
        break;
    }

    currentDrawControl = drawer;
    drawer.enable();
  }

  function handleMapClick(e) {
    if (!isPlacingMarker) return;

    const marker = L.marker(e.latlng);
    currentLayer = marker;
    currentType = 'marker';
    
    // Remove any existing temporary marker
    if (window.tempMarker) {
      map.removeLayer(window.tempMarker);
    }
    
    // Add temporary marker
    window.tempMarker = marker;
    marker.addTo(map);
    
    // Show the location form
    showLocationForm('marker', [e.latlng.lat, e.latlng.lng]);
    
    // Reset marker placement mode
    isPlacingMarker = false;
  }

  document.getElementById('draw-rectangle').addEventListener('click', () => startDrawing('rectangle'));
  document.getElementById('draw-polygon').addEventListener('click', () => startDrawing('polygon'));
  document.getElementById('draw-marker').addEventListener('click', () => startDrawing('marker'));

  // Update the saveLocation function to handle markers better
  function saveLocation(type, coordinates) {
    const form = document.getElementById('locationForm');
    const data = {
      type: type,
      coordinates: JSON.stringify(coordinates),
      title: form.querySelector('#title').value,
      description: form.querySelector('#description').value || null,
      household_id: form.querySelector('#household').value || null,
      color: form.querySelector('#color').value
    };

    fetch('/map-locations', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(location => {
      if (currentLayer) {
        // Remove temporary marker if it exists
        if (window.tempMarker) {
          map.removeLayer(window.tempMarker);
          window.tempMarker = null;
        }
        
        const popupContent = `
          <div class="location-popup">
            <h5>${location.title}</h5>
            ${data.description ? `<p>${data.description}</p>` : ''}
            ${data.household_id ? `<p><strong>Household:</strong> ${document.getElementById('household').selectedOptions[0].text}</p>` : ''}
            <button class="btn btn-danger btn-sm" onclick="deleteLocation(${location.id})">Delete</button>
          </div>
        `;
        
        currentLayer.bindPopup(popupContent);
        drawnItems.addLayer(currentLayer);
      }
      
      hideLocationForm();
      map.getContainer().classList.remove('marker-placement-mode');
      document.getElementById('draw-marker').classList.remove('active');
    })
    .catch(error => {
      console.error('Error saving location:', error);
      alert('Error saving location. Please try again.');
    });
  }

  function deleteLocation(id) {
    if (confirm('Are you sure you want to delete this location?')) {
      fetch(`/map-locations/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(() => {
        location.reload();
      })
      .catch(error => {
        console.error('Error deleting location:', error);
        alert('Error deleting location. Please try again.');
      });
    }
  }

  function hideLocationForm() {
    const form = document.getElementById('locationForm');
    form.style.display = 'none';
    form.reset();
    
    if (window.tempMarker) {
      map.removeLayer(window.tempMarker);
      window.tempMarker = null;
    }
    
    currentLayer = null;
    currentType = null;
    isPlacingMarker = false;
    map.getContainer().classList.remove('marker-placement-mode');
    document.getElementById('draw-marker').classList.remove('active');
  }

  document.getElementById('cancelForm').onclick = hideLocationForm;

  // Trigger a resize event after map initialization to ensure proper rendering
  setTimeout(() => {
    map.invalidateSize();
  }, 100);

  // Function to view household members
  function viewHouseholdMembers(householdId) {
    fetch(`/households/${householdId}/members`)
      .then(response => response.json())
      .then(members => {
        const modalHtml = `
          <div class="modal fade" id="householdMembersModal" tabindex="-1" aria-labelledby="householdMembersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="householdMembersModalLabel">Household Members</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Age</th>
                          <th>Gender</th>
                          <th>Civil Status</th>
                          <th>Contact</th>
                        </tr>
                      </thead>
                      <tbody>
                        ${members.map(member => `
                          <tr>
                            <td>${member.first_name} ${member.middle_name} ${member.last_name} ${member.suffix || ''}</td>
                            <td>${calculateAge(member.date_of_birth)}</td>
                            <td>${member.gender}</td>
                            <td>${member.civil_status}</td>
                            <td>${member.contact_number || 'N/A'}</td>
                          </tr>
                        `).join('')}
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('householdMembersModal');
        if (existingModal) {
          existingModal.remove();
        }

        // Add new modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('householdMembersModal'));
        modal.show();
      })
      .catch(error => {
        console.error('Error fetching household members:', error);
        alert('Error loading household members. Please try again.');
      });
  }

  // Helper function to calculate age
  function calculateAge(dateOfBirth) {
    const birthDate = new Date(dateOfBirth);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    
    return age;
  }
</script>
@endsection
