<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\Reminder;
use App\Jobs\SendMotReminderEmail;
use App\Jobs\SendServiceReminderEmail;
use Carbon\Carbon;

class ReminderService
{
    public function createRemindersForVehicle(Vehicle $vehicle): void
    {
        $this->createMotReminders($vehicle);
        $this->createServiceReminders($vehicle);
    }

    public function createMotReminders(Vehicle $vehicle): void
    {
        if (!$vehicle->mot_expiry) return;

        $expiryDate = Carbon::parse($vehicle->mot_expiry);
        
        if ($expiryDate->isPast()) return;

        $vehicle->reminders()
            ->where('type', 'mot_expiry')
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        $this->createReminder($vehicle, 'mot_expiry', $expiryDate, 30);
        $this->createReminder($vehicle, 'mot_expiry', $expiryDate, 7);
    }

    public function createServiceReminders(Vehicle $vehicle): void
    {
        if (!$vehicle->service_due) return;

        $dueDate = Carbon::parse($vehicle->service_due);
        
        if ($dueDate->isPast()) return;

        $vehicle->reminders()
            ->where('type', 'service_due')
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        $this->createReminder($vehicle, 'service_due', $dueDate, 30);
        $this->createReminder($vehicle, 'service_due', $dueDate, 7);
    }

    protected function createReminder(Vehicle $vehicle, string $type, Carbon $dueDate, int $daysBefore): void
    {
        $reminderDate = $dueDate->copy()->subDays($daysBefore);
        
        if ($reminderDate->isPast()) return;

        $notificationMethod = $this->determineNotificationMethod($vehicle->garage);

        Reminder::create([
            'garage_id' => $vehicle->garage_id,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $vehicle->customer_id,
            'type' => $type,
            'due_date' => $dueDate,
            'status' => 'pending',
            'notification_method' => $notificationMethod,
            'days_before' => $daysBefore,
        ]);
    }

    protected function determineNotificationMethod($garage): string
    {
        if ($garage->hasSmsReminders()) {
            return 'both';
        }
        return 'email';
    }

    public function processDueReminders(): void
    {
        $reminders = Reminder::dueForSending()
            ->with(['vehicle.customer', 'garage'])
            ->get();

        foreach ($reminders as $reminder) {
            $this->sendReminder($reminder);
        }
    }

    protected function sendReminder(Reminder $reminder): void
    {
        try {
            if ($reminder->type === 'mot_expiry') {
                dispatch(new SendMotReminderEmail($reminder));
            } else {
                dispatch(new SendServiceReminderEmail($reminder));
            }

            $reminder->markAsSent();
        } catch (\Exception $e) {
            $reminder->markAsFailed($e->getMessage());
            report($e);
        }
    }

    public function sendImmediateReminder(Reminder $reminder): bool
    {
        try {
            if ($reminder->type === 'mot_expiry') {
                dispatch_sync(new SendMotReminderEmail($reminder));
            } else {
                dispatch_sync(new SendServiceReminderEmail($reminder));
            }

            $reminder->markAsSent();
            return true;
        } catch (\Exception $e) {
            $reminder->markAsFailed($e->getMessage());
            report($e);
            return false;
        }
    }

    public function getReminderStats(int $garageId): array
    {
        return [
            'pending' => Reminder::where('garage_id', $garageId)->where('status', 'pending')->count(),
            'sent_today' => Reminder::where('garage_id', $garageId)
                ->where('status', 'sent')
                ->whereDate('sent_at', Carbon::today())
                ->count(),
            'failed' => Reminder::where('garage_id', $garageId)->where('status', 'failed')->count(),
            'mot_expiring_soon' => Reminder::where('garage_id', $garageId)
                ->where('type', 'mot_expiry')
                ->where('status', 'pending')
                ->whereDate('due_date', '<=', Carbon::now()->addDays(7))
                ->count(),
        ];
    }

    public function cancelVehicleReminders(Vehicle $vehicle): void
    {
        $vehicle->reminders()
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);
    }

    public function rescheduleReminders(Vehicle $vehicle): void
    {
        $this->cancelVehicleReminders($vehicle);
        $this->createRemindersForVehicle($vehicle);
    }
}