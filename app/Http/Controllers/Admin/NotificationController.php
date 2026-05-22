<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotificationLog;
use App\Services\OneSignalService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private OneSignalService $onesignal) {}

    public function index()
    {
        $logs = PushNotificationLog::latest()->paginate(30);
        return view('admin.notifications.index', compact('logs'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:300'],
            'url'     => ['nullable', 'url', 'max:500'],
            'segment' => ['required', 'string', 'in:all,subscribers,customers'],
        ]);

        $segment = match ($data['segment']) {
            'subscribers' => 'Opted In Users',
            'customers'   => 'Total Subscriptions',
            default       => 'Total Subscriptions',
        };

        $success = $this->onesignal->sendToSegment(
            $segment,
            $data['title'],
            $data['message'],
            $data['url'] ?? null
        );

        PushNotificationLog::create([
            'title'      => $data['title'],
            'message'    => $data['message'],
            'url'        => $data['url'] ?? null,
            'segment'    => $data['segment'],
            'sent_by'    => auth()->id(),
            'success'    => $success,
        ]);

        if ($success) {
            return back()->with('success', 'Push notification sent successfully.');
        }

        return back()->with('error', 'Failed to send notification. Check that your OneSignal REST API Key is set in .env (ONESIGNAL_REST_API_KEY).');
    }
}
