<!-- ======= Sidebar ======= -->
@php
    function isActive($route) {
        $currentRoute = Route::currentRouteName();

        if (str_contains($currentRoute, $route)) {
            return '';
        } else {
            return 'collapsed';
        }
    }
@endphp
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link @php echo isActive('home') @endphp" href="/home">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link @php echo isActive('users') @endphp" href="/users">
                <i class="bi bi-people-fill"></i>
                <span>Portal Users</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @php echo isActive('members') @endphp" href="/members">
                <i class="bi bi-person-fill-add"></i>
                <span>Members </span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @php echo isActive('logs') @endphp" href="/logs">
                <i class="bi bi-clock-history"></i>
                <span>Log</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @php echo isActive('database') @endphp" href="/database">
                <i class="bi bi-database-fill-up"></i>
                <span>Database</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @php echo isActive('profile') @endphp" href="/profile">
                <i class="bi bi-gear-fill"></i>
                <span>Account Settings</span>
            </a>
        </li>

        {{-- <li class="nav-heading">Pages</li> --}}

    </ul>

</aside><!-- End Sidebar-->