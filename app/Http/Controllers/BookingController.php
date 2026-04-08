<?php

namespace App\Http\Controllers;

use App\Mail\BookingApprovedMail;
use App\Mail\BookingCancelledMail;
use App\Mail\BookingDeclinedMail;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function create()
    {
        return view('book'); // book.blade.php
    }

    public function store(Request $request)
    {
        // 🔽 VALIDATION
        $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email',
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'venue' => 'required|string|max:255',
            'notes' => 'nullable|string',

            // PAYMENT
            'payment_method' => 'required|string|max:50',
            'event_price' => 'nullable|numeric',
            'downpayment_amount' => 'nullable|numeric',

            // CONDITIONAL GCash REQUIRED
            'gcash_name' => 'nullable|required_if:payment_method,GCash|string|max:255',
            'gcash_number' => 'nullable|required_if:payment_method,GCash|string|max:20',
            'gcash_receipt' => 'nullable|required_if:payment_method,GCash|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // 🔽 STATUS LOGIC BASED ON PAYMENT METHOD
        $status = 'pending';
        $gcashReceiptPath = null;

        if ($request->payment_method === 'GCash') {
            if ($request->hasFile('gcash_receipt')) {
                // Store receipt
                $gcashReceiptPath = $request->file('gcash_receipt')->store('gcash_receipts', 'public');
                $status = 'confirmed'; // AUTO CONFIRM for GCash with receipt
            }
        } elseif ($request->payment_method === 'Cash') {
            $status = 'pending'; // Cash always pending
        }

        // 🔽 CREATE BOOKING
        $booking = Booking::create([
            'full_name' => $request->full_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'event_name' => $request->event_name,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue' => $request->venue,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method,
            'event_price' => $request->event_price,
            'downpayment_amount' => $request->downpayment_amount,
            'gcash_name' => $request->gcash_name,
            'gcash_number' => $request->gcash_number,
            'gcash_receipt' => $gcashReceiptPath,
            'status' => $status,
        ]);

        // 🔽 SEND EMAIL IF AUTO-CONFIRMED
        if ($status === 'confirmed') {
            Mail::to($booking->email)->send(new BookingApprovedMail($booking));
        }

        // 🔽 SUCCESS MESSAGE
        $message = ($status === 'confirmed')
            ? 'Your booking has been successfully received and automatically confirmed. Admin will review the GCash payment shortly.'
            : 'Your booking has been received. Please wait for admin confirmation. We will contact you shortly.';

        return redirect()->route('booking.create')->with('success', $message);
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved';
        $booking->save();

        Mail::to($booking->email)->send(new BookingApprovedMail($booking));

        return redirect()->back()->with('success', 'Booking has been approved.');
    }

    public function decline(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->status === 'declined') {
            return back()->with('warning', 'This booking is already declined.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status' => 'declined',
        ]);

        Mail::to($booking->email)
            ->send(new BookingDeclinedMail($booking, $request->reason));

        return back()->with('success', 'Booking has been declined successfully.');
    }

    public function cancel(Booking $booking)
    {
        $booking->status = 'cancelled';
        $booking->save();

        Mail::to($booking->email)->send(new BookingCancelledMail($booking));

        return back()->with('success', 'Booking cancelled successfully.');
    }

    // Admin Bookings Page UPDATED: now includes 'approved' and 'confirmed'
    public function bookings()
    {
        $bookings = Booking::orderByRaw("FIELD(status, 'pending', 'confirmed', 'approved', 'declined', 'cancelled')")->get();

        return view('adminbookings', compact('bookings'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }

    // User Calendar Page
    public function calendar()
    {
        $bookings = \App\Models\Booking::whereIn('status', ['approved', 'confirmed', 'accepted'])->get();

        return view('calendar', compact('bookings'));
    }

    // Admin Calendar Page
    public function adminCalendar()
    {
        $bookings = \App\Models\Booking::all();
        return view('admincalendar', compact('bookings'));
    }

    public function getBookedTimes(Request $request)
    {
        $date = $request->date;

        $bookings = Booking::whereDate('event_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'approved'])
            ->get();

        $blockedHours = [];

        foreach ($bookings as $booking) {
            $startBlock = Carbon::parse($booking->start_time)->subHours(3);
            $endBlock = Carbon::parse($booking->end_time)->addHours(3);

            while ($startBlock < $endBlock) {
                $blockedHours[] = $startBlock->format('H:00');
                $startBlock->addHour();
            }
        }

        return response()->json(array_values(array_unique($blockedHours)));
    }
}
