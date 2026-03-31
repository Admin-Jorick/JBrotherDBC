<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/admin') }}">Admin</a>
                </li>

                {{-- Calendar link --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/admin/calendar') }}">Calendar</a>
                </li>

                {{-- Bookings link --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.bookings') }}">Bookings</a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger ms-2">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
