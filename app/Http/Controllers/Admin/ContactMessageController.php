<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * List all contact messages with optional unread filter.
     */
    public function index(): View
    {
        $query = ContactMessage::query()->orderByDesc('created_at');

        if (request('status') === 'unread') {
            $query->unread();
        }

        $messages    = $query->paginate(25)->withQueryString();
        $unreadCount = ContactMessage::unread()->count();
        $totalCount  = ContactMessage::count();

        return view('admin.contact.index', compact('messages', 'unreadCount', 'totalCount'));
    }

    /**
     * Show a single contact message and mark it read on first view.
     */
    public function show(ContactMessage $message): View
    {
        if (! $message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('admin.contact.show', compact('message'));
    }

    /**
     * Explicitly mark a message as read (via POST from index).
     */
    public function markRead(ContactMessage $message): RedirectResponse
    {
        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Message marked as read.');
    }

    /**
     * Delete a contact message.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
