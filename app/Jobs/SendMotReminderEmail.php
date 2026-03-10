<?php

namespace App\Jobs;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMotReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $reminder;
    public $tries = 3;
    public $backoff = [60, 300, 600];

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function handle(): void
    {
        $customer = $this->reminder->customer;
        $vehicle = $this->reminder->vehicle;
        $garage = $this->reminder->garage;

        if ($customer->email) {
            // Send email logic here
            // Mail::to($customer->email)->send(new MotReminderMail($customer, $vehicle, $garage, $this->reminder->due_date));
        }
    }

    public function failed(\Throwable $exception): void
    {
        $this->reminder->markAsFailed($exception->getMessage());
    }
}