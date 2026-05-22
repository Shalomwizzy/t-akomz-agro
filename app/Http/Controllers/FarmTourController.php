<?php

namespace App\Http\Controllers;

use App\Mail\TourBookingAdminAlert;
use App\Mail\TourBookingConfirmed;
use App\Models\FarmTourBooking;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FarmTourController extends Controller
{
    public function index()
    {
        return view('pages.farm-tour');
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email'],
            'phone'          => ['required', 'string', 'max:20'],
            'preferred_date' => ['required', 'date', 'after:+2 days'],
            'group_size'     => ['required', 'integer', 'min:1', 'max:500'],
            'package'        => ['required', 'in:individual,group,corporate'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $packages = FarmTourBooking::packages();
        $pkg      = $packages[$validated['package']];

        if ($validated['package'] === 'corporate') {
            return redirect()->route('contact')->with('info', 'For corporate/school tours please contact us directly so we can tailor a programme.');
        }

        $amount    = $pkg['price'] * (int) $validated['group_size'];
        $reference = 'TOUR-' . strtoupper(Str::random(10));

        $booking = FarmTourBooking::create([
            'reference'      => $reference,
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'preferred_date' => $validated['preferred_date'],
            'group_size'     => $validated['group_size'],
            'package'        => $validated['package'],
            'amount'         => $amount,
            'persons'        => (int) $validated['group_size'],
            'notes'          => $validated['notes'] ?? null,
            'payment_status' => 'pending',
        ]);

        // Initialize Paystack payment
        $secretKey = trim((string) (SiteSetting::get('paystack_secret_key') ?: config('services.paystack.secret_key')));

        $response = \Illuminate\Support\Facades\Http::withToken($secretKey)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email'        => $booking->email,
                'amount'       => $amount * 100,
                'reference'    => $reference,
                'callback_url' => route('farm-tour.callback'),
                'metadata'     => [
                    'booking_id'  => $booking->id,
                    'package'     => $pkg['label'],
                    'persons'     => $booking->persons,
                    'tour_date'   => $booking->preferred_date->format('d/m/Y'),
                    'custom_fields' => [
                        ['display_name' => 'Package',  'variable_name' => 'package',  'value' => $pkg['label']],
                        ['display_name' => 'Persons',  'variable_name' => 'persons',  'value' => $booking->persons],
                        ['display_name' => 'Tour Date','variable_name' => 'tour_date','value' => $booking->preferred_date->format('d/m/Y')],
                    ],
                ],
            ]);

        if ($response->successful() && $response->json('status')) {
            return redirect($response->json('data.authorization_url'));
        }

        $booking->delete();
        return back()->with('error', 'Could not initialize payment. Please try again.')->withInput();
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference') ?? $request->query('trxref');
        if (!$reference) {
            return redirect()->route('farm-tour')->with('error', 'Invalid payment reference.');
        }

        $booking = FarmTourBooking::where('reference', $reference)->first();
        if (!$booking) {
            return redirect()->route('farm-tour')->with('error', 'Booking not found.');
        }

        $secretKey = trim((string) (SiteSetting::get('paystack_secret_key') ?: config('services.paystack.secret_key')));
        $verify    = \Illuminate\Support\Facades\Http::withToken($secretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($verify->successful() && $verify->json('data.status') === 'success') {
            $booking->update([
                'payment_status' => 'paid',
                'paystack_ref'   => $verify->json('data.id'),
            ]);

            // Send confirmation email to guest
            try {
                Mail::to($booking->email)->send(new TourBookingConfirmed($booking));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Tour booking email failed', ['error' => $e->getMessage()]);
            }

            // Alert admin
            try {
                $adminEmail = SiteSetting::adminEmail();
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new TourBookingAdminAlert($booking));
                }
            } catch (\Throwable) {}

            return redirect()->route('farm-tour.success', ['ref' => $reference]);
        }

        $booking->update(['payment_status' => 'failed']);
        return redirect()->route('farm-tour')->with('error', 'Payment was not completed. Please try again.');
    }

    public function success(Request $request)
    {
        $booking = FarmTourBooking::where('reference', $request->query('ref'))->where('payment_status', 'paid')->first();
        if (!$booking) {
            return redirect()->route('farm-tour');
        }
        return view('pages.farm-tour-success', compact('booking'));
    }
}
