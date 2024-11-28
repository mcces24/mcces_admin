<!-- ======= Header ======= -->
<style>
    .notifications-list {
    max-height: 800px;  /* Adjust this height as necessary */
    overflow-y: auto;   /* Enables vertical scrolling */
}

.notifications .dropdown-header,
.notifications .dropdown-footer {
    background-color: #fff;  /* Ensure header/footer has white background */
    padding: 10px;
}

.notifications .notification-item {
    padding: 10px;
    border-bottom: 1px solid #e0e0e0;  /* Optional: adds separation between notifications */
}
</style>
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="/assets/img/logo.png" alt="">
            <span class="d-none d-lg-block">MCCES | ADMIN</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword" class="form-control me-2" >
            <button type="submit" title="Search" class="btn btn-primary"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            {{-- <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon--> --}}

            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">{{ $alerts->first()->alert_count ?? 0 }}</span>
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        @php
                            $alert_count = $alerts->first()->alert_count ?? 0;
                            if ($alert_count > 0) {
                                echo "You have $alert_count new notifications";
                            } else {
                                echo "No new notifications";
                            }
                        @endphp
                        <a href="#" class="view-all-alerts"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
<div class="notifications-list">
        @foreach ($alerts as $alert)
            <li>
                <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
                @php
                    $icon = $alert->type == 'success' ? 'check-circle' : 'exclamation-circle';
                    $color = $alert->type == 'success' ? 'success' : 'warning';
                @endphp
                <i class="bi bi-{{ $icon }} text-{{ $color }}"></i>
                <a href="#" class="view-alerts" data-href="/logs/view/{{$alert->id}}" data-id="{{$alert->id}}">
                <div>
                    <h4 class="text-{{ $color }}">Login {{ $alert->type }} alert.</h4>
                    <p>{{ $alert->portal }} portal in {{ $alert->com_location }}</p>
                    <p>{{ $alert->created_at->diffForHumans() }}</p>
                </div>
                </a>
            </li>
        @endforeach
    </div>
                    
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="dropdown-footer">
                        <a href="/logs">Show all notifications</a>
                    </li>

                </ul><!-- End Notification Dropdown Items -->

            </li><!-- End Notification Nav -->

            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-chat-left-text"></i>
                    <span class="badge bg-success badge-number">{{ $messages->first()->message_count ?? 0 }}</span>
                </a><!-- End Messages Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                    <li class="dropdown-header">
                        @php
                            $message_count = $messages->first()->message_count ?? 0;
                            if ($message_count > 0) {
                                echo "You have $message_count new messages";
                            } else {
                                echo "No new messages";
                            }
                        @endphp
                        {{-- <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a> --}}
                    </li>

                    @foreach ($messages as $message)
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="message-item">
                            <a href="/users/{{ $message->id }}/edit">
                                <img src="{{ !empty($message->profile) ? asset($message->profile) : asset('assets/img/logo.png') }}" alt="" class="rounded-circle">
                                <div>
                                    <h4>{{ $message->name }}</h4>
                                    <p>{{ $message->username }} from {{ $message->role }} request forget password</p>
                                    {{-- <p>4 hrs. ago</p> --}}
                                </div>
                            </a>
                        </li>
                    @endforeach

                    {{-- <li class="dropdown-footer">
                        <a href="#">Show all messages</a>
                    </li> --}}

                </ul><!-- End Messages Dropdown Items -->

            </li><!-- End Messages Nav -->

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ !empty($profile_pic) ? asset($profile_pic) : asset('assets/img/logo.png') }}" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ $name }}!</span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ $name }}</h6>
                        <span>Admin</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- <li>
                        <a class="dropdown-item d-flex align-items-center" href="profile">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li> --}}
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="/profile">
                            <i class="bi bi-gear"></i>
                            <span>Account Settings</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- <li>
                        <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                            <i class="bi bi-question-circle"></i>
                            <span>Need Help?</span>
                        </a>
                    </li> --}}
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item d-flex align-items-center" href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.view-all-alerts').click(function() {
            $.ajax({
                url: '/logs/mark-all-read',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'  // Include CSRF token for security
                },
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();  // Reload the page upon success
                    }
                },
                error: function(xhr, status, error) {
                    // Log the error to the console
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);

                    // Optionally, display an alert or message to the user
                    alert("An error occurred while marking alerts as read. Please try again.");
                }
            });
        });        

        $('.view-alerts').click(function(event) {
            var href = $(this).data('href');
            var id = $(this).data('id');
            $.ajax({
                url: '/logs/mark-read/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'  // Include CSRF token for security
                },
                success: function(response) {
                    if (response.status == 'success') {
                        window.location.href = href;  // Redirect to the alert page upon success
                    }
                },
                error: function(xhr, status, error) {
                    // Log the error to the console
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.log("Response: " + xhr.responseText);

                    // Optionally, display an alert or message to the user
                    alert("An error occurred while marking alerts as read. Please try again.");
                }
            });
        });

    });
</script>