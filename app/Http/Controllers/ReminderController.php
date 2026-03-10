<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Services\ReminderService;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    protected $reminderService;

    public function __construct(ReminderService $reminderService)
    {
        $this->middleware(['auth', 'ensure.garage.access']);
        $this->reminderService = $reminderService;
    }

    public function index()
    {
        $user = auth()->user();
        
        // Super Admin should not access reminders - redirect to admin dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Reminders are only available for garage accounts.');
        }

        $garageId = $user->garage_id;

        if (!$garageId) {
            return redirect()->route('dashboard')
                ->with('error', 'No garage assigned.');
        }

        $upcomingReminders = Reminder::where('garage_id', $garageId)
            ->with(['vehicle', 'customer'])
            ->whereIn('status', ['pending', 'sent'])
            ->whereDate('due_date', '>=', now())
            ->orderBy('due_date')
            ->paginate(20);

        $stats = $this->reminderService->getReminderStats($garageId);

        return view('reminders.index', compact('upcomingReminders', 'stats'));
    }

    public function history()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Reminders are only available for garage accounts.');
        }

        $garageId = $user->garage_id;

        if (!$garageId) {
            return redirect()->route('dashboard')
                ->with('error', 'No garage assigned.');
        }

        $reminderHistory = Reminder::where('garage_id', $garageId)
            ->with(['vehicle', 'customer'])
            ->where(function ($q) {
                $q->where('status', 'sent')
                  ->orWhere('status', 'failed');
            })
            ->orderBy('sent_at', 'desc')
            ->paginate(20);

        return view('reminders.history', compact('reminderHistory'));
    }

    public function sendNow(Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $success = $this->reminderService->sendImmediateReminder($reminder);

        if ($success) {
            return back()->with('success', 'Reminder sent successfully.');
        }

        return back()->with('error', 'Failed to send reminder.');
    }

    public function cancel(Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $reminder->update(['status' => 'cancelled']);

        return back()->with('success', 'Reminder cancelled.');
    }
}