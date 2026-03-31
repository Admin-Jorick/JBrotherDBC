<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingDeclinedMail;
use App\Mail\BookingCancelledMail;

class AdminController extends Controller
{
    // Store new post
    public function storePost(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->storeAs('public', $fileName);
            $data['image'] = 'storage/' . $fileName;
        }

        Post::create($data);

        return back()->with('success', 'Post created successfully!');
    }

    // Show admin calendar
    public function showCalendar()
    {
        return view('admincalendar');
    }

    // Approve booking
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'approved';
        $booking->save();

        // Send email notification
        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingApprovedMail($booking));
        }

        return back()->with('success', 'Booking has been approved.');
    }

    // Decline booking
    public function decline(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Send decline email
        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingDeclinedMail($booking, $request->reason));
        }

        // Delete booking from database
        $booking->delete();

        return back()->with('success', 'Booking has been declined and removed.');
    }

    // Cancel booking (optional)
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'cancelled';
        $booking->save();

        // Send email notification
        if ($booking->email) {
            Mail::to($booking->email)->send(new BookingCancelledMail($booking));
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    // Admin bookings page
    public function bookings()
    {
        $bookings = Booking::orderByRaw("FIELD(status, 'pending', 'confirmed', 'approved', 'declined', 'cancelled')")->get();
        return view('adminbookings', compact('bookings'));
    }

    // Delete booking manually (if needed)
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }

    // Admin calendar data
    public function adminCalendar()
    {
        $bookings = Booking::whereIn('status', ['confirmed', 'approved', 'accepted'])->get();
        return view('admincalendar', compact('bookings'));
    }

    public function adminStoreBooking(Request $request)
    {
        // Validate fields
        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'event_name' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'venue' => 'required|string',
            'downpayment_amount' => 'required|numeric|min:0',
        ]);

        // Save booking
        $booking = new \App\Models\Booking();
        $booking->full_name = $request->full_name;
        $booking->contact_number = $request->contact_number;
        $booking->email = $request->email;
        $booking->event_name = $request->event_name;
        $booking->event_date = $request->event_date;
        $booking->start_time = $request->start_time;
        $booking->end_time = $request->end_time;
        $booking->venue = $request->venue;
        $booking->downpayment_amount = $request->downpayment_amount;
        $booking->status = 'accepted'; // auto accept
        $booking->payment_method = 'Cash'; // admin booking default Cash
        $booking->event_price = $request->event_price;
        $booking->downpayment_amount = $request->downpayment_amount;
        $booking->save();

        return redirect()->back()->with('success', 'Booking successfully added by admin.');
    }
}