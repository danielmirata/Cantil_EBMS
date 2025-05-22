<!-- Sidebar -->
 <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <h3>CANTIL E-System</h3>
                <p>Admin Panel</p>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Account Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.role') }}">
                        <i class="fas fa-user-tag"></i>
                        <span>Role Management</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.residence.archived') ? 'active' : '' }}">
                    <a href="{{ route('admin.residence.archived') }}">
                        <i class="fas fa-archive"></i>
                        <span>Resident Archive</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.officials.archived') ? 'active' : '' }}">
                    <a href="{{ route('admin.officials.archived') }}">
                        <i class="fas fa-folder-open"></i>
                        <span>Official Archive</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('secretary.activity-logs') }}">
                        <i class="fas fa-history"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('secretary.map') }}">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Map View</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('secretary.map') }}">
                        <i class="fas fa-database"></i>
                        <span>Backup/Restore</span>
                    </a>
                </li>
            </ul>
        </nav>