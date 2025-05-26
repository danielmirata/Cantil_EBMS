@extends('layouts.captain_layout')

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
          </div>
          <div id="map"></div>
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
    fetch(`/captain/map-locations/purok-stats/${purokName}`)
      .then(response => response.json())
      .then(stats => {
        const popupContent = `
          <div class="purok-popup">
            <h5>Purok ${purokName}</h5>
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

  // Load existing purok markers
  fetch('/captain/map-locations')
    .then(response => response.json())
    .then(locations => {
      locations.forEach(location => {
        if (location.type === 'purok') {
          try {
            const coords = JSON.parse(location.coordinates);
            const purokName = location.title.replace('Purok ', '');
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
      fetch(`/captain/api/search?q=${encodeURIComponent(searchTerm)}`)
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
            fetch('/captain/map-locations/households')
              .then(response => response.json())
              .then(data => {
                const location = data.find(loc => loc.household_id === item.id);
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
      Object.values(purokMarkers).forEach(marker => {
        if (marker.getPopup() && marker.getPopup().getContent().includes(item.title || item.name)) {
          marker.openPopup();
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

  // Trigger a resize event after map initialization to ensure proper rendering
  setTimeout(() => {
    map.invalidateSize();
  }, 100);
</script>
@endsection
