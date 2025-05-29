<!-- Sidebar -->
<div class="sidebar">
    <div class="logo-container">
        <img src="{{ asset('img/cantil-e-logo.png') }}" alt="Barangay Logo" class="logo">
        
        <div class="brgy-name">Barangay Cantil-E</div>
        <div class="portal-label">Secretary Portal</div>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('secretary.dashboard') }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('schedules.index') }}">
                <i class="fas fa-calendar"></i> Schedule
            </a>
        </li>
        <li>
            <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#officialSubmenu">
                <i class="fas fa-user-tie"></i> Barangay Official
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="collapse submenu" id="officialSubmenu">
                <li>
                    <a href="{{ route('officials.create') }}">
                        <i class="fas fa-circle submenu-icon"></i> New Official
                    </a>
                </li>
                <li>
                    <a href="{{ route('officials.index') }}">
                        <i class="fas fa-circle submenu-icon"></i> List of Official
                    </a>
                </li>
                <li>
                    <a href="{{ route('officials.archived') }}">
                        <i class="fas fa-circle submenu-icon"></i> Official End Term
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#residenceSubmenu">
                <i class="fas fa-home"></i> Residence
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="collapse submenu" id="residenceSubmenu">
                <li>
                    <a href="{{ route('secretary.residence.new') }}">
                        <i class="fas fa-circle submenu-icon"></i> New Residence
                    </a>
                </li>
                <li>
                    <a href="{{ route('secretary.residence.all') }}">
                        <i class="fas fa-circle submenu-icon"></i> Resident
                    </a>
                </li>
                <li>
                    <a href="{{ route('secretary.residence.archived') }}">
                        <i class="fas fa-circle submenu-icon"></i> Archive Residence
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('secretary.documents.index') }}">
                <i class="fas fa-file-alt"></i> Documents
            </a>
        </li>
        <li>
            <a href="#" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#usersSubmenu">
                <i class="fas fa-users"></i> Users
                <i class="fas fa-chevron-down float-end"></i>
            </a>
            <ul class="collapse submenu" id="usersSubmenu">
                <li>
                    <a href="{{ route('secretary.user.resident') }}">
                        <i class="fas fa-circle submenu-icon"></i> Resident
                    </a>
                </li>
                <li>
                    <a href="{{ route('administrators.show') }}">
                        <i class="fas fa-circle submenu-icon"></i> Administrator
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('secretary.complaints.index') }}">
                <i class="fas fa-exclamation-circle"></i> Complaints
            </a>
        </li>
        <li>
            <a href="{{ route('secretary.blotter') }}">
                <i class="fas fa-file-signature"></i> Blotter Record
            </a>
        </li>
        <!--
        <li>
            <a href="#">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
        -->
        <li>
            <a href="{{ route('secretary.projects.index') }}">
                <i class="fas fa-project-diagram"></i> Barangay Projects
            </a>
        </li>
        <li>
            <a href="{{ route('secretary.inventory.index') }}">
                <i class="fas fa-boxes"></i> Inventory
            </a>
        </li>
        <li>
            <a href="{{ route('secretary.map') }}">
                <i class="fas fa-map-marked-alt"></i> Barangay Map
            </a>
        </li>
        <li>
            <a href="{{ route('secretary.activity-logs') }}">
                <i class="fas fa-history"></i> Record Logs
            </a>
        </li>
    </ul>
</div>

<!-- You'll also need CSS to style the submenu items to match your image -->
<style>
.sidebar {
    background-color: #8B0000; /* Dark red color as shown in image */
    color: white;
    min-height: 100vh;
}

.submenu {
    padding-left: 20px;
    list-style: none;
}

.submenu li a {
    color: white;
    text-decoration: none;
    padding: 8px 15px;
    display: block;
    font-size: 0.9rem;
}

.submenu-icon {
    color: #FF5252; /* Red circle for submenu items */
    font-size: 0.7rem;
    margin-right: 8px;
}

.sidebar-menu > li > a {
    padding: 12px 15px;
    display: block;
    color: white;
    text-decoration: none;
    font-weight: 500;
}

.sidebar-menu {
    padding-left: 0;
    list-style: none;
}

.sidebar-menu li {
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.fa-chevron-down {
    transition: transform 0.3s;
}

[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}
</style>

<!-- Add this script to handle submenu toggles -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add active class to current page link
    const currentLocation = window.location.href;
    const menuItems = document.querySelectorAll('.sidebar-menu a');
    
    menuItems.forEach(item => {
        if(item.href === currentLocation) {
            item.classList.add('active');
            
            // If it's a submenu item, open the parent menu
            const parent = item.closest('.submenu');
            if(parent) {
                parent.classList.add('show');
                const toggle = document.querySelector(`[data-bs-target="#${parent.id}"]`);
                if(toggle) toggle.setAttribute('aria-expanded', 'true');
            }
        }
    });
});
</script>