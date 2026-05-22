<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TourBookingApproved;
use App\Mail\TourBookingRejected;
use App\Models\FarmTourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TourBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmTourBooking::latest();

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        $bookings = $query->paginate(25)->withQueryString();
        return view('admin.tour-bookings.index', compact('bookings'));
    }

    public function edit(FarmTourBooking $tourBooking)
    {
        return view('admin.tour-bookings.edit', compact('tourBooking'));
    }

    public function update(Request $request, FarmTourBooking $tourBooking)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email',
            'phone'            => 'required|string|max:30',
            'package'          => 'required|in:individual,group,corporate',
            'persons'          => 'required|integer|min:1',
            'preferred_date'   => 'required|date',
            'confirmed_date'   => 'nullable|date',
            'notes'            => 'nullable|string',
            'payment_status'   => 'required|in:pending,paid,failed',
            'booking_status'   => 'required|in:pending,approved,rejected,cancelled',
            'admin_note'       => 'nullable|string|max:1000',
        ]);

        $tourBooking->update($data);

        return redirect()->route('admin.tour-bookings.index')
            ->with('success', 'Booking #' . $tourBooking->reference . ' updated.');
    }

    public function approve(Request $request, FarmTourBooking $tourBooking)
    {
        $request->validate([
            'admin_note'     => 'nullable|string|max:1000',
            'confirmed_date' => 'nullable|date',
        ]);

        $tourBooking->update([
            'booking_status' => 'approved',
            'admin_note'     => $request->admin_note,
            'confirmed_date' => $request->confirmed_date ?: $tourBooking->preferred_date,
        ]);

        try {
            Mail::to($tourBooking->email)->send(new TourBookingApproved($tourBooking));
        } catch (\Throwable $e) {
            logger()->error('Tour approval email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking approved and confirmation email sent to ' . $tourBooking->email);
    }

    public function reject(Request $request, FarmTourBooking $tourBooking)
    {
        $request->validate([
            'admin_note'       => 'required|string|max:1000',
            'alternative_date' => 'nullable|date|after:today',
        ]);

        $tourBooking->update([
            'booking_status'   => 'rejected',
            'admin_note'       => $request->admin_note,
            'alternative_date' => $request->alternative_date,
        ]);

        try {
            Mail::to($tourBooking->email)->send(new TourBookingRejected($tourBooking));
        } catch (\Throwable $e) {
            logger()->error('Tour rejection email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking rejected and notification sent to ' . $tourBooking->email);
    }

    public function destroy(FarmTourBooking $tourBooking)
    {
        $tourBooking->delete();
        return redirect()->route('admin.tour-bookings.index')->with('success', 'Booking deleted.');
    }
}
