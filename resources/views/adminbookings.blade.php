<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Bookings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/obspalo_designs/css/style.css" rel="stylesheet">
<link rel="icon" type="image/jpg" href="{{ asset('storage/JBlogo.jpg') }}">
<style>
body { color: #000; }

.booking-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-badge {
    padding: 3px 8px;
    border-radius: 12px;
    color: #fff;
    font-size: 0.85rem;
    font-weight: bold;
}
.status-pending { background-color: #ffc107; }
.status-accepted { background-color: #28a745; }
.status-cancelled { background-color: #dc3545; }
.status-declined { background-color: #343a40; }

.btn-view, .btn-action {
    font-size: 0.85rem;
    padding: 4px 10px;
}

.receipt-img { max-width: 100%; border-radius: 8px; margin-top: 10px; }

.modal-body, .modal-title { color: #000000; }

.lightbox {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
}

.lightbox img {
    display: block;
    margin: auto;
    max-width: 90%;
    max-height: 80%;
    border-radius: 10px;
    animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
    from {transform: scale(0.7); opacity: 0;}
    to {transform: scale(1); opacity: 1;}
}

.lightbox:target {
    display: block;
}

</style>
</head>
<body>
@include('layouts.navbar_admin')

<div class="container mt-4">
    <h1 class="mb-4 text-center text-white">Bookings</h1>

    @forelse($bookings as $booking)
    <div class="booking-card" id="bookingCard{{ $booking->id }}">
        <div>
            <!-- ✅ FIXED NUMBERING -->
            <strong>#{{ $loop->iteration }}</strong> - {{ $booking->full_name }}
        </div>
        <div class="d-flex align-items-center gap-2">
            @php
                $status = strtolower($booking->status);
            @endphp

            @if($status == 'pending')
                <span class="status-badge status-pending">Pending</span>

            @elseif(in_array($status, ['approved', 'confirmed', 'accepted']))
                <span class="status-badge" style="background-color:#28a745;">
                    Booked
                </span>

            @elseif($status == 'cancelled')
                <span class="status-badge status-cancelled">Cancelled</span>

            @elseif($status == 'declined')
                <span class="status-badge status-declined">Declined</span>

            @else
                <span class="status-badge" style="background-color:gray;">
                    {{ ucfirst($booking->status) }}
                </span>
            @endif
            <button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#viewBooking{{ $booking->id }}">
                View Booking
            </button>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="viewBooking{{ $booking->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- ✅ FIXED NUMBERING -->
                    <h5 class="modal-title">Booking #{{ $loop->iteration }} - {{ $booking->full_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Contact:</strong> {{ $booking->contact_number }}</p>
                    <p><strong>Email:</strong> {{ $booking->email ?? 'N/A' }}</p>
                    <p><strong>Event Name:</strong> {{ $booking->event_name }}</p>
                    <p><strong>Event Date:</strong> {{ $booking->event_date }}</p>
                    <p><strong>Start Time:</strong> {{ $booking->start_time }}</p>
                    <p><strong>End Time:</strong> {{ $booking->end_time }}</p>
                    <p><strong>Venue:</strong> {{ $booking->venue }}</p>
                    <p><strong>Payment Method:</strong> {{ $booking->payment_method ?? 'N/A' }}</p>
                    <p><strong>GCash Name:</strong> {{ $booking->gcash_name ?? 'N/A' }}</p>
                    <p><strong>GCash Number:</strong> {{ $booking->gcash_number ?? 'N/A' }}</p>
                    @if($booking->gcash_receipt)
                        <p><strong>Receipt:</strong></p>
                        <a href="#viewReceipt{{ $booking->id }}">
                            <img src="{{ asset('storage/'.$booking->gcash_receipt) }}" 
                                class="receipt-img" 
                                style="cursor:pointer;">
                        </a>

                        <div id="viewReceipt{{ $booking->id }}" class="lightbox">
                            <a href="#">
                                <img src="{{ asset('storage/'.$booking->gcash_receipt) }}">
                            </a>
                        </div>
                    @endif
                    <p><strong>Notes:</strong> {{ $booking->notes ?? 'N/A' }}</p>
                    <hr>
                    <p><strong>Payment Breakdown</strong></p>

                    <p><strong>Event Price:</strong> ₱{{ number_format($booking->event_price) }}</p>

                    <p><strong>Downpayment (30%):</strong> ₱{{ number_format($booking->downpayment_amount) }}</p>
                </div>
                <div class="modal-footer">

                    {{-- Accept --}}
                    @if($booking->status == 'pending' && $booking->payment_method == 'Cash')
                    <form action="{{ route('bookings.approve', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-action">Accept</button>
                    </form>
                    @endif

                    {{-- Decline --}}
                    @if($booking->status != 'cancelled' && $booking->status != 'declined')
                    <button class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#declineModal{{ $booking->id }}">
                        Decline
                    </button>
                    @endif

                    <!-- Delete Button -->
                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="deleteForm ms-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-action">Delete</button>
                    </form>

                    <button class="btn btn-secondary btn-action" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DECLINE MODAL -->
    <div class="modal fade" id="declineModal{{ $booking->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('bookings.decline', $booking->id) }}" method="POST" class="declineForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Decline Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to decline and remove this booking?</p>
                        <textarea class="form-control mt-2" name="reason" required placeholder="Reason for declining"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-action">Decline</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @empty
    <div class="alert alert-info text-center">No bookings found.</div>
    @endforelse
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('adminBookNowBtn');
    const formDiv = document.getElementById('adminBookNowForm');
    const closeBtn = document.getElementById('closeAdminForm');

    toggleBtn.addEventListener('click', () => {
        formDiv.style.display = formDiv.style.display === 'none' ? 'block' : 'none';
    });

    closeBtn.addEventListener('click', () => {
        formDiv.style.display = 'none';
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Decline = delete + remove UI
    document.querySelectorAll('.declineForm').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const modal = this.closest('.modal');
            const bookingCard = modal.id.replace('declineModal','bookingCard');

            fetch(this.action, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: new FormData(this)
            }).then(res => {
                if(res.ok) {
                    document.getElementById(bookingCard)?.remove();

                    const modalEl = bootstrap.Modal.getInstance(modal);
                    modalEl?.hide();
                }
            });
        });
    });

    // Delete = remove booking from DB + UI
    document.querySelectorAll('.deleteForm').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const modal = this.closest('.modal');
            const bookingCard = modal.id.replace('viewBooking','bookingCard');

            fetch(this.action, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: new FormData(this)
            }).then(res => {
                if(res.ok) {
                    document.getElementById(bookingCard)?.remove();

                    const modalEl = bootstrap.Modal.getInstance(modal);
                    modalEl?.hide();
                }
            });
        });
    });

});

</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const prices = {
            "Fiesta Performance": 2500,
            "Parade / Street Performance": 3000,
            "School Event": 3000,
            "Private Event": 2500,
            "Festival Performance": 3500
        };

        const eventSelect = document.getElementById('event_name');
        const priceDiv = document.getElementById('price_div');
        const priceDisplay = document.getElementById('event_price_display');
        const priceInput = document.getElementById('event_price');
        const downpaymentInput = document.querySelector('input[name="downpayment_amount"]');

        eventSelect.addEventListener('change', function () {
            const value = this.value;

            if (prices[value]) {
                const price = prices[value];

                // show price
                priceDiv.style.display = 'block';
                priceDisplay.value = "₱" + price.toLocaleString();
                priceInput.value = price;

                // auto downpayment (30%)
                downpaymentInput.value = Math.round(price * 0.3);

            } else {
                priceDiv.style.display = 'none';
                priceDisplay.value = '';
                priceInput.value = '';
                downpaymentInput.value = '';
            }
        });

    });
</script>

    <!-- Floating Book Now Button -->
    <button id="adminBookNowBtn" class="btn btn-primary position-fixed" 
            style="bottom:30px; right:30px; z-index:1050;">
        Book Now
    </button>

    <!-- Floating Admin Booking Form -->
    <div id="adminBookNowForm" class="card p-4 shadow-lg position-fixed" 
        style="bottom:80px; right:30px; width:350px; max-width:90%; display:none; 
                background-color: rgba(255,255,255,0.95); z-index:1050; border-radius:12px;">
        <h5 class="text-center mb-3">Book Now (Admin)</h5>
        <form action="{{ route('bookings.adminStore') }}" method="POST" id="adminBookingForm">
            @csrf
            <input type="hidden" name="status" value="accepted">

            <div class="mb-2">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Contact Number</label>
                <input type="text" name="contact_number" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Event Name</label>
                <select name="event_name" id="event_name" class="form-select" required>
                    <option value="" disabled selected>Select Event</option>
                    <option value="Fiesta Performance">Fiesta Performance</option>
                    <option value="Parade / Street Performance">Parade / Street Performance</option>
                    <option value="School Event">School Event</option>
                    <option value="Private Event">Private Event</option>
                    <option value="Festival Performance">Festival Performance</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <!-- PRICE DISPLAY -->
            <div class="mb-2" id="price_div" style="display:none;">
                <label>Event Price</label>
                <input type="text" id="event_price_display" class="form-control" readonly>
            </div>

            <!-- OPTIONAL: para masave sa DB -->
            <input type="hidden" name="event_price" id="event_price">

            <div class="mb-2">
                <label>Event Date</label>
                <input type="date" name="event_date" class="form-control" required>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <label>Start Hour</label>
                    <select name="start_time" class="form-select" required>
                        @for($i=0;$i<24;$i++)
                            <option value="{{ sprintf('%02d:00',$i) }}">{{ sprintf('%02d',$i) }}:00</option>
                        @endfor
                    </select>
                </div>
                <div class="col">
                    <label>End Hour</label>
                    <select name="end_time" class="form-select" required>
                        @for($i=0;$i<24;$i++)
                            <option value="{{ sprintf('%02d:00',$i) }}">{{ sprintf('%02d',$i) }}:00</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <label>Venue</label>
                <input type="text" name="venue" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Downpayment Amount (30%)</label>
                <input type="number" name="downpayment_amount" class="form-control" readonly required>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="button" id="closeAdminForm" class="btn btn-secondary btn-sm">Cancel</button>
                <button type="submit" class="btn btn-success btn-sm">Book Now</button>
            </div>
        </form>
    </div>
</body>

</html>