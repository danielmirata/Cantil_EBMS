@extends('layouts.o_map')

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

  /* Project Details Modal Styles */
  .custom-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    font-size: 1.1rem;
    padding: 0.5rem 1.5rem;
    transition: color 0.2s, border-bottom 0.2s;
  }
  .custom-tabs .nav-link.active {
    color: #600000;
    font-weight: bold;
    border-bottom: 3px solid #600000;
    background: none;
  }
  .custom-tabs .nav-link i {
    font-size: 1.1rem;
  }
  .custom-tabs .nav-link:focus {
    outline: none;
    box-shadow: none;
  }
  .info-item {
    margin-bottom: 1rem;
  }
  .info-item:last-child {
    margin-bottom: 0;
  }
  .badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
  }
  .modal-body {
    padding: 1.5rem;
  }
  .modal-header {
    border-radius: 10px 10px 0 0;
  }
  .modal-footer {
    border-radius: 0 0 10px 10px;
  }
  .text-primary {
    color: #0d6efd !important;
  }
  .card {
    border: none;
    border-radius: 10px;
  }
  .badge.bg-success {
    background-color: #198754 !important;
  }
  .badge.bg-primary {
    background-color: #0d6efd !important;
  }
  .badge.bg-info {
    background-color: #0dcaf0 !important;
  }
  .badge.bg-danger {
    background-color: #dc3545 !important;
  }
  .badge i {
    font-size: 0.9rem;
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

          <!-- Location Details Form Modal -->
          <div class="modal fade" id="locationFormModal" tabindex="-1" aria-labelledby="locationFormModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="locationFormModalLabel">Add Location Details</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="locationForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="mb-3">
                          <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="household" class="form-label">Link to Household (Optional)</label>
                      <select class="form-select" id="household" name="household_id">
                        <option value="">No Household</option>
                      </select>
                      <div class="form-text">Leave as "No Household" if this location is not associated with any household.</div>
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
                    <div class="mb-3">
                      <label for="project" class="form-label">Mark Project (Optional)</label>
                      <select class="form-select" id="project" name="project_id">
                        <option value="">No Project</option>
                      </select>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="saveLocationBtn">Save Location</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Project Details Modal -->
          <div class="modal fade" id="projectDetailsModal" tabindex="-1" aria-labelledby="projectDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="projectDetailsModalLabel">
                    <i class="fas fa-project-diagram me-2"></i>Project Information
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <!-- Tab Navigation -->
                  <ul class="nav nav-tabs custom-tabs mb-4" id="projectDetailsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="tab-info" data-bs-toggle="tab" data-bs-target="#tabInfoContent" type="button" role="tab" aria-controls="tabInfoContent" aria-selected="true">
                        <i class="fas fa-user me-1"></i> <span>Project Info</span>
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="tab-financial" data-bs-toggle="tab" data-bs-target="#tabFinancialContent" type="button" role="tab" aria-controls="tabFinancialContent" aria-selected="false">
                        <i class="fas fa-money-check-alt me-1"></i> <span>Financials</span>
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="tab-documents" data-bs-toggle="tab" data-bs-target="#tabDocumentsContent" type="button" role="tab" aria-controls="tabDocumentsContent" aria-selected="false">
                        <i class="fas fa-folder-open me-1"></i> <span>Documents & Notes</span>
                      </button>
                    </li>
                  </ul>
                  <div class="tab-content" id="projectDetailsTabsContent">
                    <!-- Project Info Tab -->
                    <div class="tab-pane fade show active" id="tabInfoContent" role="tabpanel" aria-labelledby="tab-info">
                      <div class="card shadow-sm mb-4">
                        <div class="card-body">
                          <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="card-title mb-0" id="viewProjectName"></h2>
                          </div>
                          <div class="mb-4">
                            <h5 class="text-muted mb-3 border-bottom pb-2">
                              <i class="fas fa-info-circle me-2"></i>Project Information
                            </h5>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong><i class="fas fa-map-marker-alt me-2 text-primary"></i>Location:</strong></p>
                                  <p class="text-muted ps-4" id="viewLocation"></p>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong><i class="fas fa-calendar-alt me-2 text-primary"></i>Timeline:</strong></p>
                                  <p class="text-muted ps-4" id="viewTimeline"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="mb-4">
                            <h5 class="text-muted mb-3 border-bottom pb-2">
                              <i class="fas fa-align-left me-2"></i>Description
                            </h5>
                            <p class="text-muted ps-4" id="viewDescription"></p>
                          </div>
                          <div class="mb-4">
                            <h5 class="text-muted mb-3 border-bottom pb-2">
                              <i class="fas fa-tasks me-2"></i>Status & Priority
                            </h5>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong>Status:</strong></p>
                                  <span class="badge" id="viewStatus"></span>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong>Priority:</strong></p>
                                  <p class="mb-0 ps-4" id="viewPriority"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Financials Tab -->
                    <div class="tab-pane fade" id="tabFinancialContent" role="tabpanel" aria-labelledby="tab-financial">
                      <div class="card shadow-sm mb-4">
                        <div class="card-body">
                          <div class="mb-4">
                            <h5 class="text-muted mb-3 border-bottom pb-2">
                              <i class="fas fa-money-bill-wave me-2"></i>Financial Information
                            </h5>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong><i class="fas fa-money-bill-wave me-2 text-primary"></i>Budget:</strong></p>
                                  <p class="text-muted ps-4" id="viewBudget"></p>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong><i class="fas fa-hand-holding-usd me-2 text-primary"></i>Funding Source:</strong></p>
                                  <p class="text-muted ps-4" id="viewFundingSource"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Documents & Notes Tab -->
                    <div class="tab-pane fade" id="tabDocumentsContent" role="tabpanel" aria-labelledby="tab-documents">
                      <div class="card shadow-sm mb-4">
                        <div class="card-body">
                          <div class="mb-4">
                            <h5 class="text-muted mb-3 border-bottom pb-2">
                              <i class="fas fa-file-alt me-2"></i>Project Documents
                            </h5>
                            <div id="viewDocuments" class="text-muted ps-4">
                              <!-- Documents will be populated here -->
                            </div>
                          </div>
                          <div class="mb-4">
                            <h5 class="text-muted mb-3 border-bottom pb-2">
                              <i class="fas fa-sticky-note me-2"></i>Additional Notes
                            </h5>
                            <p class="text-muted ps-4" id="viewNotes"></p>
                          </div>
                          <div class="mb-4">
                            <h5 class="text-muted mb-3 border-bottom pb-2">
                              <i class="fas fa-clock me-2"></i>Timestamps
                            </h5>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong><i class="fas fa-calendar-plus me-2 text-primary"></i>Created At:</strong></p>
                                  <p class="text-muted ps-4" id="viewCreatedAt"></p>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="info-item">
                                  <p class="mb-1"><strong><i class="fas fa-calendar-edit me-2 text-primary"></i>Updated At:</strong></p>
                                  <p class="text-muted ps-4" id="viewUpdatedAt"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                  </button>
                  <button type="button" class="btn btn-warning" id="editFromViewBtn">
                    <i class="fas fa-edit me-1"></i>Edit Project
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
  function createLayer(type, coords, color, location = null) {
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
      if (layer && location) {
        let popupContent = `
          <div class="location-popup">
            <h5>${location.title}</h5>
            ${location.description ? `<p>${location.description}</p>` : ''}`;

        // Add household details if available
        if (location.household_id) {
          popupContent += `
            <div class="household-info">
              <p><strong>Household:</strong> ${location.household ? `${location.household.house_number}, ${location.household.street}` : 'Loading...'}</p>
              <button class="btn btn-primary btn-sm" onclick="viewHouseholdMembers(${location.household_id})">
                <i class="fas fa-users"></i> View Household Members
              </button>
            </div>`;
        }

        // Add project information if available
        if (location.project) {
          const project = location.project;
          const statusClass = {
            'Completed': 'success',
            'Ongoing': 'primary',
            'Planning': 'info',
            'On Hold': 'danger'
          }[project.status] || 'secondary';

          popupContent += `
            <div class="project-info mt-2">
              <h6 class="mb-2">Project Information</h6>
              <p><strong>Project:</strong> ${project.project_name}</p>
              <p><strong>Status:</strong> <span class="badge bg-${statusClass}">${project.status}</span></p>
              <p><strong>Timeline:</strong> ${formatDate(project.start_date)} - ${formatDate(project.end_date)}</p>
              <button class="btn btn-info btn-sm" onclick="showProjectDetails(${project.id})">
                <i class='fas fa-project-diagram'></i> View Full Details
              </button>
            </div>`;
        }

        popupContent += `
            <button class="btn btn-danger btn-sm" onclick="deleteLocation(${location.id})">Delete</button>
          </div>`;

        layer.bindPopup(popupContent);
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
          // Fetch project details if project_id exists
          if (location.project_id) {
            fetch(`/api/projects/${location.project_id}`)
              .then(response => response.json())
              .then(projectData => {
                location.project = projectData.project || projectData;
                const layer = createLayer(location.type, coords, location.color, location);
                if (layer) {
                  drawnItems.addLayer(layer);
                }
              })
              .catch(error => {
                console.error('Error loading project:', error);
                const layer = createLayer(location.type, coords, location.color, location);
                if (layer) {
                  drawnItems.addLayer(layer);
                }
              });
          } else {
            const layer = createLayer(location.type, coords, location.color, location);
            if (layer) {
              drawnItems.addLayer(layer);
            }
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
    const modal = new bootstrap.Modal(document.getElementById('locationFormModal'));
    document.getElementById('locationFormModalLabel').textContent = `Add ${type.charAt(0).toUpperCase() + type.slice(1)} Details`;
    
    // Load projects for the dropdown
    fetch('/api/projects')
      .then(response => response.json())
      .then(projects => {
        const projectSelect = document.getElementById('project');
        projectSelect.innerHTML = '<option value="">No Project</option>';
        
        // Check if projects is an array or has a projects property
        const projectList = Array.isArray(projects) ? projects : (projects.projects || []);
        
        projectList.forEach(project => {
          const option = document.createElement('option');
          option.value = project.id;
          option.textContent = `${project.project_name} (${project.status})`;
          projectSelect.appendChild(option);
        });
      })
      .catch(error => {
        console.error('Error loading projects:', error);
        const projectSelect = document.getElementById('project');
        projectSelect.innerHTML = '<option value="">Error loading projects</option>';
      });

    document.getElementById('locationForm').onsubmit = function(e) {
      e.preventDefault();
      saveLocation(type, coordinates);
    };
    modal.show();
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

  // Update the saveLocation function
  function saveLocation(type, coordinates) {
    const form = document.getElementById('locationForm');
    const data = {
      type: type,
      coordinates: JSON.stringify(coordinates),
      title: form.querySelector('#title').value,
      description: form.querySelector('#description').value || null,
      household_id: form.querySelector('#household').value || null,
      color: form.querySelector('#color').value,
      project_id: form.querySelector('#project').value || null
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

        // If project_id exists, fetch project details
        if (data.project_id) {
          fetch(`/api/projects/${data.project_id}`)
            .then(response => response.json())
            .then(projectData => {
              location.project = projectData.project || projectData;
              let popupContent = createPopupContent(location, data);
              currentLayer.bindPopup(popupContent);
              drawnItems.addLayer(currentLayer);
            })
            .catch(error => {
              console.error('Error loading project:', error);
              let popupContent = createPopupContent(location, data);
              currentLayer.bindPopup(popupContent);
              drawnItems.addLayer(currentLayer);
            });
        } else {
          let popupContent = createPopupContent(location, data);
          currentLayer.bindPopup(popupContent);
          drawnItems.addLayer(currentLayer);
        }
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

  // Helper function to create popup content
  function createPopupContent(location, data) {
    let popupContent = `
      <div class="location-popup">
        <h5>${location.title}</h5>
        ${data.description ? `<p>${data.description}</p>` : ''}`;

    // Add household details if available
    if (data.household_id) {
      const householdSelect = document.getElementById('household');
      const selectedOption = householdSelect.selectedOptions[0];
      popupContent += `
        <div class="household-info">
          <p><strong>Household:</strong> ${selectedOption.text}</p>
          <button class="btn btn-primary btn-sm" onclick="viewHouseholdMembers(${data.household_id})">
            <i class="fas fa-users"></i> View Household Members
          </button>
        </div>`;
    }

    // Add project information if available
    if (location.project) {
      const project = location.project;
      const statusClass = {
        'Completed': 'success',
        'Ongoing': 'primary',
        'Planning': 'info',
        'On Hold': 'danger'
      }[project.status] || 'secondary';

      popupContent += `
        <div class="project-info mt-2">
          <h6 class="mb-2">Project Information</h6>
          <p><strong>Project:</strong> ${project.project_name}</p>
          <p><strong>Status:</strong> <span class="badge bg-${statusClass}">${project.status}</span></p>
          <p><strong>Timeline:</strong> ${formatDate(project.start_date)} - ${formatDate(project.end_date)}</p>
          <button class="btn btn-info btn-sm" onclick="showProjectDetails(${project.id})">
            <i class='fas fa-project-diagram'></i> View Full Details
          </button>
        </div>`;
    }

    popupContent += `
        <button class="btn btn-danger btn-sm" onclick="deleteLocation(${location.id})">Delete</button>
      </div>`;

    return popupContent;
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
    const modal = bootstrap.Modal.getInstance(document.getElementById('locationFormModal'));
    if (modal) {
      modal.hide();
    }
    document.getElementById('locationForm').reset();
    
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

  // Add event listener for save button
  document.getElementById('saveLocationBtn').addEventListener('click', function() {
    if (currentLayer && currentType) {
      saveLocation(currentType, getCoordinates(currentLayer, currentType));
    }
  });

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

  // Add showProjectDetails function
  function showProjectDetails(projectId) {
    console.log('Showing project details for ID:', projectId);
    
    fetch(`/api/projects/${projectId}`)
      .then(response => response.json())
      .then(response => {
        console.log('Project data received:', response);
        
        const project = response.project || response;
        if (!project) {
          console.error('No project data received');
          alert('Error: No project data received');
          return;
        }
        
        // Update modal content
        $('#viewProjectName').text(project.project_name || '');
        $('#viewLocation').text(project.location || 'Not specified');
        $('#viewTimeline').text(`${formatDate(project.start_date)} - ${formatDate(project.end_date)}`);
        $('#viewDescription').text(project.description || '');
        $('#viewBudget').text(`₱${formatNumber(project.budget)}`);
        $('#viewFundingSource').text(project.funding_source || 'Not specified');
        $('#viewPriority').text(project.priority || 'Not specified');
        $('#viewNotes').text(project.notes || 'No additional notes');
        $('#viewCreatedAt').text(formatDate(project.created_at));
        $('#viewUpdatedAt').text(formatDate(project.updated_at));
        
        // Handle documents
        const documentsContainer = $('#viewDocuments');
        documentsContainer.empty();
        if (project.documents && project.documents.length > 0) {
          const documentsList = $('<ul class="list-unstyled"></ul>');
          project.documents.forEach(doc => {
            let fileName = doc.path || doc.name || doc;
            fileName = fileName.replace(/^project-documents[\\/]/, '');
            const docUrl = `/storage/project-documents/${fileName}`;
            const docName = doc.name || doc.original_name || doc.path || doc;
            documentsList.append(`
              <li class="mb-2">
                <i class="fas fa-file me-2 text-primary"></i>
                <a href="${docUrl}" target="_blank" class="text-primary text-decoration-underline">${docName}</a>
              </li>
            `);
          });
          documentsContainer.append(documentsList);
        } else {
          documentsContainer.html('<p class="text-muted"><i class="fas fa-info-circle me-2"></i>No documents attached</p>');
        }
        
        // Update status badge
        const statusClass = {
          'Completed': 'success',
          'Ongoing': 'primary',
          'Planning': 'info',
          'On Hold': 'danger'
        }[project.status] || 'secondary';
        
        const statusIcon = {
          'Completed': 'check-circle',
          'Ongoing': 'spinner fa-spin',
          'Planning': 'clipboard-list',
          'On Hold': 'pause-circle'
        }[project.status] || 'question-circle';
        
        $('#viewStatus').html(`
          <span class="badge bg-${statusClass} text-white">
            <i class="fas fa-${statusIcon} me-1"></i>
            ${project.status}
          </span>
        `);
        
        // Set edit button action
        $('#editFromViewBtn').off('click').on('click', function() {
          $('#projectDetailsModal').modal('hide');
          editProject(projectId);
        });
        
        // Show modal
        const viewModal = new bootstrap.Modal(document.getElementById('projectDetailsModal'));
        viewModal.show();
      })
      .catch(error => {
        console.error('Error fetching project details:', error);
        alert('Error loading project details. Please try again.');
      });
  }

  // Helper function to format dates
  function formatDate(dateString) {
    if (!dateString) return 'Not specified';
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  }

  // Helper function to format numbers
  function formatNumber(number) {
    if (!number) return '0.00';
    return new Intl.NumberFormat('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(number);
  }

  // Initialize tooltips and other Bootstrap components
  $(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Initialize tabs
    $('.nav-tabs a').on('click', function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // Show first tab by default
    $('.nav-tabs li:first-child a').tab('show');

    // Handle modal events
    $('#projectDetailsModal').on('shown.bs.modal', function() {
      // Refresh tooltips when modal is shown
      $('[data-toggle="tooltip"]').tooltip();
    });

    $('#projectDetailsModal').on('hidden.bs.modal', function() {
      // Clean up when modal is hidden
      $('[data-toggle="tooltip"]').tooltip('dispose');
    });
  });
</script>
@endsection