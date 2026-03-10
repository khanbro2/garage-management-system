<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReminderService;

class SendReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send due MOT and service reminders to customers';

    protected $reminderService;

    public function __construct(ReminderService $reminderService)
    {
        parent::__construct();
        $this->reminderService = $reminderService;
    }

    public function handle(): int
    {
        $this->info('Processing reminders...');
        
        $this->reminderService->processDueReminders();
        
        $this->info('Reminders processed successfully.');
        return Command::SUCCESS;
    }
}