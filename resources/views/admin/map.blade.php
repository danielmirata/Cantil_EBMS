@extends('layouts.admin_layout')

@section('title', 'Admin Barangay Map')

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
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Admin Interactive Barangay Map</h3>
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
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description (Optional)</label>
              <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="color" class="form-label">Color (Optional)</label>
              <input type="color" class="form-control form-control-color" id="color" name="color" value="#ff0000">
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
  // Initialize map
  const map = L.map('map').setView([9.280745008410356, 123.27235221862794], 17);
  let currentLayer = null;
  let currentType = null;
  let isPlacingMarker = false;

  // Add OpenStreetMap tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19,
  }).addTo(map);

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

  // Load existing locations
  fetch('/admin/map-locations')
    .then(response => response.json())
    .then(locations => {
      locations.forEach(location => {
        try {
          const coords = JSON.parse(location.coordinates);
          let layer;
          
          if (location.type === 'marker') {
            layer = L.marker(coords);
          } else if (location.type === 'rectangle' || location.type === 'polygon') {
            layer = L.polygon(coords);
            if (location.color) {
              layer.setStyle({ color: location.color });
            }
          }
          
          if (layer) {
            const popupContent = `
              <div class="location-popup">
                <h5>${location.title}</h5>
                ${location.description ? `<p>${location.description}</p>` : ''}
                ${location.household ? `
                  <p><strong>Household:</strong> ${location.household.name} - ${location.household.house_number}, ${location.household.street}</p>
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
  fetch('/admin/map-locations/households')
    .then(response => response.json())
    .then(households => {
      const select = document.getElementById('household');
      households.forEach(household => {
        const option = document.createElement('option');
        option.value = household.id;
        option.textContent = `${household.name} - ${household.house_number}, ${household.street}`;
        select.appendChild(option);
      });
    });

  // Handle drawing events
  map.on(L.Draw.Event.CREATED, function (e) {
    currentLayer = e.layer;
    currentType = e.layerType;
    showLocationForm(e.layerType, getCoordinates(currentLayer, e.layerType));
  });

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

  function showLocationForm(type, coordinates) {
    document.getElementById('locationForm').style.display = 'block';
    document.getElementById('locationForm').onsubmit = function(e) {
      e.preventDefault();
      saveLocation(type, coordinates);
    };
  }

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

    fetch('/admin/map-locations', {
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
    })
    .catch(error => {
      console.error('Error saving location:', error);
      alert('Error saving location. Please try again.');
    });
  }

  function deleteLocation(id) {
    if (confirm('Are you sure you want to delete this location?')) {
      fetch(`/admin/map-locations/${id}`, {
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
    currentLayer = null;
    currentType = null;
  }

  document.getElementById('cancelForm').onclick = hideLocationForm;

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
      fetch(`/admin/map-locations/search?q=${encodeURIComponent(searchTerm)}`)
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

    results.forEach(result => {
      const resultItem = document.createElement('div');
      resultItem.className = 'p-2 border-bottom search-result-item';
      resultItem.style.cursor = 'pointer';
      
      resultItem.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <strong>${result.title || result.name}</strong>
            <div class="small text-muted">${result.description || ''}</div>
          </div>
          <small class="text-muted">${result.type}</small>
        </div>
      `;
      
      resultItem.addEventListener('click', () => {
        if (result.coordinates) {
          navigateToLocation(result);
        }
        searchResults.style.display = 'none';
        searchInput.value = result.title || result.name;
      });
      
      searchResults.appendChild(resultItem);
    });

    searchResults.style.display = 'block';
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

  // Trigger a resize event after map initialization
  setTimeout(() => {
    map.invalidateSize();
  }, 100);
</script>
@endsection
