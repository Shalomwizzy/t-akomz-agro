<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BroadcastMail;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailBroadcastController extends Controller
{
    // ─── Workers / Staff ─────────────────────────────────────────────────────

    public function workersIndex()
    {
        $workers = Worker::where('status', 'active')->orderBy('full_name')->get();
        $staff   = User::whereHas('roles', fn($q) => $q->whereIn('name', ['ADMIN', 'SUPER_ADMIN', 'MANAGER', 'SALES']))->orderBy('name')->get();

        return view('admin.mail.workers', compact('workers', 'staff'));
    }

    public function sendToWorkers(Request $request)
    {
        $data = $request->validate([
            'recipient_type' => ['required', 'in:all_workers,all_staff,specific_worker,specific_staff'],
            'worker_id'      => ['required_if:recipient_type,specific_worker', 'nullable', 'exists:workers,id'],
            'staff_id'       => ['required_if:recipient_type,specific_staff', 'nullable', 'exists:users,id'],
            'subject'        => ['required', 'string', 'min:3', 'max:255'],
            'body'           => ['required', 'string', 'min:10'],
        ]);

        $subject = $data['subject'];
        $body    = $data['body'];
        $sender  = auth()->user()->name;
        $sent    = 0;
        $failed  = 0;

        $recipients = collect();

        switch ($data['recipient_type']) {
            case 'all_workers':
                $recipients = Worker::where('status', 'active')
                    ->whereNotNull('email')
                    ->where('email', '!=', '')
                    ->get()
                    ->map(fn($w) => ['email' => $w->email, 'name' => $w->full_name]);
                break;

            case 'all_staff':
                $recipients = User::whereHas('roles', fn($q) => $q->whereIn('name', ['ADMIN', 'SUPER_ADMIN', 'MANAGER', 'SALES']))
                    ->get()
                    ->map(fn($u) => ['email' => $u->email, 'name' => $u->name]);
                break;

            case 'specific_worker':
                $worker = Worker::findOrFail($data['worker_id']);
                if ($worker->email) {
                    $recipients = collect([['email' => $worker->email, 'name' => $worker->full_name]]);
                }
                break;

            case 'specific_staff':
                $user = User::findOrFail($data['staff_id']);
                $recipients = collect([['email' => $user->email, 'name' => $user->name]]);
                break;
        }

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient['email'], $recipient['name'])
                    ->send(new BroadcastMail($subject, $body, $sender));
                $sent++;
            } catch (\Throwable) {
                $failed++;
            }
        }

        $msg = "Mail sent to {$sent} recipient(s).";
        if ($failed > 0) {
            $msg .= " {$failed} failed (check mail config).";
        }
        if ($sent === 0 && $failed === 0) {
            $msg = 'No recipients found with a valid email address.';
        }

        return back()->with('success', $msg);
    }

    // ─── Users / Customers ────────────────────────────────────────────────────

    private function customerQuery()
    {
        return User::where(function ($q) {
            $q->whereHas('roles', fn($r) => $r->where('name', 'CUSTOMER'))
              ->orWhereDoesntHave('roles');
        });
    }

    public function usersIndex(Request $request)
    {
        $users = $this->customerQuery()->orderBy('name', 'asc')->get();

        return view('admin.mail.users', compact('users'));
    }

    public function sendToUsers(Request $request)
    {
        $data = $request->validate([
            'recipient_type' => ['required', 'in:all_customers,selected'],
            'user_ids'       => ['required_if:recipient_type,selected', 'nullable', 'array'],
            'user_ids.*'     => ['exists:users,id'],
            'subject'        => ['required', 'string', 'min:3', 'max:255'],
            'body'           => ['required', 'string', 'min:10'],
        ]);

        $subject = $data['subject'];
        $body    = $data['body'];
        $sender  = auth()->user()->name;
        $sent    = 0;
        $failed  = 0;

        if ($data['recipient_type'] === 'all_customers') {
            $users = $this->customerQuery()->get();
        } else {
            $users = User::whereIn('id', $data['user_ids'] ?? [])->get();
        }

        foreach ($users as $user) {
            try {
                Mail::to($user->email, $user->name)
                    ->send(new BroadcastMail($subject, $body, $sender));
                $sent++;
            } catch (\Throwable) {
                $failed++;
            }
        }

        $msg = "Mail sent to {$sent} customer(s).";
        if ($failed > 0) {
            $msg .= " {$failed} failed (check mail config).";
        }
        if ($sent === 0 && $failed === 0) {
            $msg = 'No recipients found.';
        }

        return back()->with('success', $msg);
    }
}
