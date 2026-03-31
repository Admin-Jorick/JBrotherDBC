<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Calendar - JBrothers</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('obspalo_designs/css/style.css') }}" rel="stylesheet">
<link rel="icon" type="image/jpg" href="{{ asset('storage/JBlogo.jpg') }}">

{{-- FullCalendar CSS --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<style>
    #calendar {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .booking-list {
        max-height: 500px;
        overflow-y: auto;
    }
    .fc-toolbar-title {
        color: #212529; 
        font-weight: 600; 
        font-size: 1.5rem;
    }
</style>
</head>
<body class="bg-light">
    {{-- Navbar --}}
    @include('layouts.navbar_admin')

    <div class="container mt-4">
        <h1 class="text-center mb-3 text-white">Admin Calendar</h1>
        <p class="text-center text-white">Overview of all bookings and schedules</p>

        {{-- Calendar --}}
        <div id="calendar"></div>

        <hr class="my-5">

        {{-- Bookings Card List --}}
        <h3 class="mb-3 text-center text-white">All Bookings</h3>

        <div class="booking-list">
            <div class="row g-3">
                @forelse($bookings as $booking)
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $booking->event_name }}</h5>

                                <p class="card-text mb-3">
                                    <strong>Booked By:</strong> {{ $booking->full_name }} <br>
                                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }} <br>
                                    <strong>Time:</strong> {{ $booking->start_time }} - {{ $booking->end_time }} <br>
                                    <strong>Venue:</strong> {{ $booking->venue }}
                                </p>

                                {{-- Status Badge --}}
                                <div class="mt-auto">
                                    @php
                                        $status = strtolower($booking->status);
                                    @endphp

                                    @if($status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>

                                    @elseif(in_array($status, ['approved', 'confirmed', 'accepted']))
                                        <span class="badge bg-success">Booked</span>

                                    @elseif($status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>

                                    @elseif($status == 'declined')
                                        <span class="badge bg-secondary">Declined</span>

                                    @else
                                        <span class="badge bg-dark">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">No bookings yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: '' // hide week/day buttons
                },
                events: [
                    @foreach($bookings as $booking)
                        {
                            title: "{{ $booking->event_name }} ({{ $booking->status }})",
                            start: "{{ $booking->event_date }}T{{ $booking->start_time }}",
                            end: "{{ $booking->event_date }}T{{ $booking->end_time }}",
                            backgroundColor: 
                                @if($booking->status == 'pending')
                                    'orange'
                                @elseif($booking->status == 'approved' || $booking->status == 'confirmed')
                                    'green'
                                @elseif($booking->status == 'cancelled')
                                    'red'
                                @else
                                    'gray'
                                @endif,
                            borderColor: 'transparent'
                        },
                    @endforeach
                ]
            });
            calendar.render();
        });
    </script>

</body>

</html>