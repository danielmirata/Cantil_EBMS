<div class="row">
    <!-- Profile Section -->
    <div class="col-md-4 text-center border-end mb-3">
        <div class="mb-3">
            @if ($resident->profile_picture)
                <img src="{{ route('profile.picture', ['filename' => $resident->profile_picture]) }}" 
                     alt="Profile Picture" 
                     class="img-thumbnail rounded-circle mb-2" 
                     style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                     style="width: 150px; height: 150px;">
                    <i class="fas fa-user fa-4x text-secondary"></i>
                </div>
            @endif
            <h4 class="mt-2">{{ $resident->first_name }} {{ $resident->last_name }}</h4>
            <p class="text-muted">{{ $resident->residency_status }}</p>
        </div>
    </div>
    <!-- Information Tabs -->
    <div class="col-md-8">
        <ul class="nav nav-tabs" id="residentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" 
                        data-bs-target="#personal" type="button" role="tab" 
                        aria-controls="personal" aria-selected="true">
                    <i class="fas fa-user me-1"></i> Personal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" 
                        data-bs-target="#contact" type="button" role="tab" 
                        aria-controls="contact" aria-selected="false">
                    <i class="fas fa-address-card me-1"></i> Contact & Address
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="family-tab" data-bs-toggle="tab" 
                        data-bs-target="#family" type="button" role="tab" 
                        aria-controls="family" aria-selected="false">
                    <i class="fas fa-users me-1"></i> Family
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="status-tab" data-bs-toggle="tab" 
                        data-bs-target="#status" type="button" role="tab" 
                        aria-controls="status" aria-selected="false">
                    <i class="fas fa-info-circle me-1"></i> Status
                </button>
            </li>
        </ul>
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="residentTabContent">
            <!-- Personal Information Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Full Name</span>
                            <p class="fw-bold mb-1">{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Date of Birth</span>
                            <p class="fw-bold mb-1">{{ \Carbon\Carbon::parse($resident->date_of_birth)->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Gender</span>
                            <p class="fw-bold mb-1">{{ $resident->gender }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Civil Status</span>
                            <p class="fw-bold mb-1">{{ $resident->civil_status }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Nationality</span>
                            <p class="fw-bold mb-1">{{ $resident->nationality }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Religion</span>
                            <p class="fw-bold mb-1">{{ $resident->religion }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Place of Birth</span>
                            <p class="fw-bold mb-1">{{ $resident->place_of_birth }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contact & Address Tab -->
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Email Address</span>
                            <p class="fw-bold mb-1">{{ $resident->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Contact Number</span>
                            <p class="fw-bold mb-1">{{ $resident->contact_number }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr class="text-muted my-2">
                        <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>Address</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">House Number</span>
                            <p class="fw-bold mb-1">{{ $resident->house_number }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Street</span>
                            <p class="fw-bold mb-1">{{ $resident->street }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Barangay</span>
                            <p class="fw-bold mb-1">{{ $resident->barangay }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Municipality</span>
                            <p class="fw-bold mb-1">{{ $resident->municipality }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Zip Code</span>
                            <p class="fw-bold mb-1">{{ $resident->zip }}</p>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        @php
                            try {
                                $household = $resident->household;
                                $mapLocation = $household ? \App\Models\MapLocation::where('household_id', $household->id)->first() : null;
                                $coordinates = $mapLocation ? json_decode($mapLocation->coordinates, true) : null;
                                
                                // For marker type, coordinates are [lat, lng]
                                // For polygon/rectangle, coordinates are [[lat, lng], [lat, lng], ...]
                                $hasValidCoordinates = false;
                                $lat = 9.280745008410356;
                                $lng = 123.27235221862794;
                                
                                if ($coordinates) {
                                    if ($mapLocation->type === 'marker' && is_array($coordinates) && count($coordinates) === 2) {
                                        $hasValidCoordinates = true;
                                        $lat = $coordinates[0];
                                        $lng = $coordinates[1];
                                    } elseif (($mapLocation->type === 'polygon' || $mapLocation->type === 'rectangle') && 
                                             is_array($coordinates) && count($coordinates) > 0) {
                                        // For polygons/rectangles, use the first point as the center
                                        $hasValidCoordinates = true;
                                        $lat = $coordinates[0][0];
                                        $lng = $coordinates[0][1];
                                    }
                                }
                            } catch (\Exception $e) {
                                \Log::error('Error getting household location', [
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);
                                $hasValidCoordinates = false;
                            }
                        @endphp
                        <a href="{{ route('map.view', [
                            'lat' => $lat,
                            'lng' => $lng,
                            'zoom' => 18
                        ]) }}" class="btn btn-primary">
                            <i class="fas fa-map-marked-alt me-2"></i>View Location
                        </a>
                    </div>
                </div>
            </div>
            <!-- Family Information Tab -->
            <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Father's Name</span>
                            <p class="fw-bold mb-1">{{ $resident->father_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Mother's Name</span>
                            <p class="fw-bold mb-1">{{ $resident->mother_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr class="text-muted my-2">
                        <h6 class="text-primary mb-3"><i class="fas fa-user-shield me-2"></i>Guardian Information</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Guardian's Name</span>
                            <p class="fw-bold mb-1">{{ $resident->guardian_name ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Guardian's Contact</span>
                            <p class="fw-bold mb-1">{{ $resident->guardian_contact ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <span class="text-muted fs-6">Relation to Guardian</span>
                            <p class="fw-bold mb-1">{{ $resident->guardian_relation ?: 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Status Information Tab -->
            <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-home me-2"></i>Residency Status
                                </h6>
                                <p class="card-text fw-bold mb-0">{{ $resident->residency_status }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-vote-yea me-2"></i>Voter Status
                                </h6>
                                <p class="card-text fw-bold mb-0">{{ $resident->voters }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-wheelchair me-2"></i>PWD Status
                                </h6>
                                <p class="card-text fw-bold mb-0">
                                    {{ $resident->pwd }}
                                    @if($resident->pwd == 'Yes')
                                        <span class="d-block text-muted small">Type: {{ $resident->pwd_type }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-user-friends me-2"></i>Single Parent Status
                                </h6>
                                <p class="card-text fw-bold mb-0">{{ $resident->single_parent }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">Resident Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationModal = document.getElementById('locationModal');
    if (locationModal) {
        locationModal.addEventListener('show.bs.modal', function () {
            // Get the address components
            const address = {
                houseNumber: '{{ $resident->house_number }}',
                street: '{{ $resident->street }}',
                barangay: '{{ $resident->barangay }}',
                municipality: '{{ $resident->municipality }}',
                zip: '{{ $resident->zip }}'
            };

            // Construct full address
            const fullAddress = `${address.houseNumber} ${address.street}, ${address.barangay}, ${address.municipality}, ${address.zip}`;

            // Initialize the map
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: fullAddress }, function(results, status) {
                if (status === 'OK') {
                    const map = new google.maps.Map(document.getElementById('map'), {
                        center: results[0].geometry.location,
                        zoom: 15
                    });

                    // Add marker
                    new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        title: '{{ $resident->first_name }} {{ $resident->last_name }}\'s Location'
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        });
    }
});
</script>
@endpush 