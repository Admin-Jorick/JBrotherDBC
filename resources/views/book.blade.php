<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>J Brothers DBC - Book Now</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/obspalo_designs/css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<link rel="icon" type="image/jpg" href="{{ asset('storage/JBlogo.jpg') }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<style>
.hour-dropdown { max-height: 80px; overflow-y: auto; font-size: 0.9rem; }
#gcash_div { display: none; }
</style>
</head>
<body class="bg-light">

@include('layouts.navbar')

<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="card shadow-lg p-4 w-100" style="max-width: 600px; border-radius: 15px;">
        <h2 class="text-center mb-4">Book Now</h2>

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="alert alert-danger" id="errorAlert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function(){
                const alertBox = document.getElementById('errorAlert');
                if(alertBox){
                    alertBox.style.transition = "opacity 0.5s ease";
                    alertBox.style.opacity = "0";
                    setTimeout(()=> alertBox.remove(), 500);
                }
            }, 5000); // 5 seconds
        });
        </script>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
        <div class="alert alert-success text-center" id="successAlert">{{ session('success') }}</div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Reset form
            const form = document.querySelector("form");
            if(form) form.reset();

            // Auto hide success message after 5 seconds
            setTimeout(function(){
                const alertBox = document.getElementById('successAlert');
                if(alertBox){
                    alertBox.style.transition = "opacity 0.5s ease";
                    alertBox.style.opacity = "0";
                    setTimeout(()=> alertBox.remove(), 500);
                }
            }, 5000); // 5 seconds
        });
        </script>
        @endif

        <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm">
            @csrf

            {{-- Full Name --}}
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required>
            </div>

            {{-- Contact Number --}}
            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" placeholder="09XXXXXXXXX" required>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
            </div>

            {{-- Event Name --}}
            <div class="mb-3">
                <label class="form-label">Event Name</label>
                <select name="event_name" id="event_name" class="form-select" required>
                    <option value="" disabled selected>Select Event</option>
                    <option value="Fiesta Performance">Fiesta Performance</option>
                    <option value="Parade / Street Performance">Parade / Street Performance</option>
                    <option value="School Event">School Event</option>
                    <option value="Private Event">Private Event</option>
                    <option value="Festival Performance">Festival Performance</option>
                    <option value="Others">Others</option>
                </select>
                <div class="mb-3" id="price_div" style="display:none;">
                    <label class="form-label">Event Price</label>
                    <input type="text" id="event_price_display" class="form-control" readonly>
                </div>
                <input type="hidden" name="event_price" id="event_price">
            </div>

            {{-- Other Event --}}
            <div class="mb-3" id="other_event_div" style="display: none;">
                <label class="form-label">Please specify</label>
                <input type="text" name="other_event_name" class="form-control" placeholder="Type your event">
            </div>

            {{-- Date & Time --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Event Date</label>
                    <input type="date" name="event_date" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Start Hour</label>
                    <select name="start_time" class="form-select hour-dropdown" required>
                        @for($i=0;$i<24;$i++)
                            <option value="{{ sprintf('%02d:00',$i) }}">{{ sprintf('%02d',$i) }}:00</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">End Hour</label>
                    <select name="end_time" class="form-select hour-dropdown" required>
                        @for($i=0;$i<24;$i++)
                            <option value="{{ sprintf('%02d:00',$i) }}">{{ sprintf('%02d',$i) }}:00</option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Venue --}}
            <div class="mb-3">
                <label class="form-label">Venue</label>
                <input type="text" name="venue" class="form-control" placeholder="Event venue/location" required>
            </div>

            {{-- Notes --}}
            <div class="mb-3">
                <label class="form-label">Additional Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Any extra details..."></textarea>
            </div>

            {{-- Downpayment --}}
            <div class="mb-3">
                <label class="form-label">Downpayment Amount (30%)</label>
                <input type="number" name="downpayment_amount" class="form-control" min="0" placeholder="₱0" required>
            </div>

            {{-- Payment --}}
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="" disabled selected>Select Payment Method</option>
                    <option value="GCash">GCash</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>

            {{-- GCash Fields --}}
            <div id="gcash_div">
                <div class="mb-3">
                    <label class="form-label">GCash Name</label>
                    <input type="text" name="gcash_name" class="form-control" placeholder="Name used in GCash">
                </div>
                <div class="mb-3">
                    <label class="form-label">GCash Number</label>
                    <input type="text" name="gcash_number" class="form-control" placeholder="09XXXXXXXXX">
                </div>
                <div class="mb-3">
                    <label class="form-label">GCash QR</label>
                    <img src="{{ asset('image/QR_Code.jpg') }}" class="img-fluid" style="max-width:200px;">
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload GCash Payment Receipt</label>
                    <input type="file" name="gcash_receipt" class="form-control" accept="image/*,application/pdf">
                </div>
            </div>

            {{-- Check Booking Modal --}}
            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#checkBookingModal">Check Booking</button>

            <div class="modal fade" id="checkBookingModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Double-check your booking details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group" id="modalDetails"></ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
                            <button type="button" class="btn btn-primary" id="confirmSubmitBtn">Submit Booking</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById('bookingForm');
    const eventSelect = document.getElementById('event_name');
    const priceDiv = document.getElementById('price_div');
    const priceDisplay = document.getElementById('event_price_display');
    const priceInput = document.getElementById('event_price');
    const downpaymentInput = form.querySelector('input[name="downpayment_amount"]');
    const paymentSelect = document.getElementById('payment_method');
    const gcashDiv = document.getElementById('gcash_div');
    const otherDiv = document.getElementById('other_event_div');
    const modalDetails = document.getElementById('modalDetails');
    const confirmBtn = document.getElementById('confirmSubmitBtn');

    // Event prices
    const prices = {
        "Fiesta Performance": 2500,
        "Parade / Street Performance": 3000,
        "School Event": 3000,
        "Private Event": 2500,
        "Festival Performance": 3500
    };

    // Event Name logic
    eventSelect.addEventListener('change', function(){
        if(this.value==='Others'){
            otherDiv.style.display='block';
            otherDiv.querySelector('input').required=true;
            priceDiv.style.display='block';
            priceDisplay.value='We will contact you for pricing';
            priceInput.value='';
            downpaymentInput.value='';
        } else {
            otherDiv.style.display='none';
            otherDiv.querySelector('input').required=false;
            if(prices[this.value]){
                priceDiv.style.display='block';
                priceDisplay.value='₱'+prices[this.value].toLocaleString();
                priceInput.value=prices[this.value];
                downpaymentInput.value=Math.round(prices[this.value]*0.3);
            } else {
                priceDiv.style.display='none';
                priceDisplay.value='';
                priceInput.value='';
                downpaymentInput.value='';
            }
        }
    });

    // Payment method toggle
    function updatePaymentFields() {
        if(paymentSelect.value === 'GCash'){
            gcashDiv.style.display='block';
            gcashDiv.querySelectorAll('input').forEach(i=>i.required=true);
        } else {
            gcashDiv.style.display='none';
            gcashDiv.querySelectorAll('input').forEach(i=>i.required=false);
        }
    }

    // on change
    paymentSelect.addEventListener('change', updatePaymentFields);

    // on page load
    updatePaymentFields();

    // Modal preview
    document.querySelector('[data-bs-target="#checkBookingModal"]').addEventListener('click', function(){
        modalDetails.innerHTML=`
            <li class="list-group-item"><strong>Full Name:</strong> ${form.full_name.value}</li>
            <li class="list-group-item"><strong>Contact Number:</strong> ${form.contact_number.value}</li>
            <li class="list-group-item"><strong>Email:</strong> ${form.email.value}</li>
            <li class="list-group-item"><strong>Event Name:</strong> ${form.event_name.value}</li>
            ${form.event_name.value==='Others'? `<li class="list-group-item"><strong>Other Event:</strong> ${form.other_event_name.value}</li>`:''}
            <li class="list-group-item"><strong>Event Date:</strong> ${form.event_date.value}</li>
            <li class="list-group-item"><strong>Start Time:</strong> ${form.start_time.value}</li>
            <li class="list-group-item"><strong>End Time:</strong> ${form.end_time.value}</li>
            <li class="list-group-item"><strong>Venue:</strong> ${form.venue.value}</li>
            <li class="list-group-item"><strong>Notes:</strong> ${form.notes.value}</li>
            <li class="list-group-item"><strong>Event Price:</strong> ${priceInput.value? '₱'+parseFloat(priceInput.value).toLocaleString():'₱0'}</li>
            <li class="list-group-item"><strong>Downpayment:</strong> ${downpaymentInput.value? '₱'+parseFloat(downpaymentInput.value).toLocaleString():'₱0'}</li>
            <li class="list-group-item"><strong>Payment Method:</strong> ${paymentSelect.value}</li>
            ${paymentSelect.value==='GCash'? `<li class="list-group-item"><strong>GCash Name:</strong> ${form.gcash_name.value}</li>
            <li class="list-group-item"><strong>GCash Number:</strong> ${form.gcash_number.value}</li>
            <li class="list-group-item"><strong>GCash Receipt:</strong> ${form.gcash_receipt.files[0]? form.gcash_receipt.files[0].name:'-'}</li>`:''}
        `;
    });

    // Date & time blocking logic
    const dateInput = form.querySelector('input[name="event_date"]');
    const startSelect = form.querySelector('select[name="start_time"]');
    const endSelect = form.querySelector('select[name="end_time"]');
    let blockedHours = [];

    function resetOptions(select){
        Array.from(select.options).forEach(o=>o.hidden=false);
        select.selectedIndex=0;
    }
    function applyBlocked(select){
        Array.from(select.options).forEach(o=>{
            const hour=parseInt(o.value.split(':')[0]);
            if(blockedHours.includes(hour)) o.hidden=true;
        });
    }

    function visibleOptionsCount(select){
        return Array.from(select.options).filter(o=>!o.hidden).length;
    }

    function updateEndSelect(){
        endSelect.disabled = visibleOptionsCount(endSelect)<3;
    }

    dateInput.addEventListener('change', function(){
        fetch(`/booked-times?date=${this.value}`)
        .then(res=>res.json())
        .then(data=>{
            blockedHours = data.map(t=>parseInt(t.split(':')[0]));
            resetOptions(startSelect);
            resetOptions(endSelect);
            applyBlocked(startSelect);
            applyBlocked(endSelect);
            updateEndSelect();
        });
    });

    startSelect.addEventListener('change', function(){
        const startHour = parseInt(this.value.split(':')[0]);
        resetOptions(endSelect);
        Array.from(endSelect.options).forEach(option=>{
            const endHour=parseInt(option.value.split(':')[0]);
            const overlapsBlocked = blockedHours.some(h=>h>=startHour && h<endHour);
            option.hidden = endHour<=startHour || overlapsBlocked;
        });
        endSelect.selectedIndex=0;
        updateEndSelect();
    });

    // 🔹 COMBINED CLICK HANDLER FOR CONFIRM BUTTON
    confirmBtn.addEventListener('click', () => {

        // GCash validation
        if(paymentSelect.value === 'GCash'){
            const gcashName = form.gcash_name.value.trim();
            const gcashNumber = form.gcash_number.value.trim();
            const gcashReceipt = form.gcash_receipt.files[0];

            if(!gcashName){
                alert("Please enter your GCash Name.");
                form.gcash_name.focus();
                return;
            }

            if(!gcashNumber){
                alert("Please enter your GCash Number.");
                form.gcash_number.focus();
                return;
            }

            if(!gcashReceipt){
                alert("Please upload your GCash Receipt.");
                return;
            }
        }

        // Submit form after validation
        form.submit();
    });

});
</script>
</body>

</html>