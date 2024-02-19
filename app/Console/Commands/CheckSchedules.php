<?php

namespace App\Console\Commands;

use App\Mail\ScheduleNotificationMail;
use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check schedules and send email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Log the current date
        Log::info('Checking schedules for ' . Carbon::now()->toDateString());

        // Get current date
        $currentDate = Carbon::now()->toDateString();

        // Retrieve schedules where Date_Set is equal to the current date
        $schedules = Schedule::whereDate('Date_Set', $currentDate)->get();

        $email = 'beantobrew24@gmail.com';

        // Log the number of schedules found for today
        Log::info('Found ' . $schedules->count() . ' schedules for today.');

        foreach ($schedules as $schedule) {
            // Log each schedule being processed
            Log::info('Processing schedule with ID ' . $schedule->id);

            // Determine notification content based on Schedule_Type
            $scheduleType = '';

            switch ($schedule->Schedule_Type) {
                case 'Planting':
                    $scheduleType = 'Planting';
                    break;
                case 'Harvesting':
                    $scheduleType = 'Harvesting';
                    break;
                case 'Pruning':
                    $scheduleType = 'Pruning';
                    break;
                case 'Pesticide Spraying':
                    $scheduleType = 'Pesticide Spraying';
                    break;
                case 'Drying':
                    $scheduleType = 'Drying';
                    break;
                case 'Fermenting':
                    $scheduleType = 'Fermenting';
                    break;
                case 'Grinding':
                    $scheduleType = 'Grinding';
                    break;
                case 'Hulling':
                    $scheduleType = 'Hulling';
                    break;
                case 'Packaging':
                    $scheduleType = 'Packaging';
                    break;
                case 'Pulping':
                    $scheduleType = 'Pulping';
                    break;
                case 'Roasting':
                    $scheduleType = 'Roasting';
                    break;
                case 'Sorting':
                    $scheduleType = 'Sorting';
                    break;
                default:
                    $scheduleType = 'Unknown';
                    break;
            }
            
            // Log the schedule type being processed
            Log::info('Sending email notification for ' . $scheduleType . ' schedule.');

            // Send email notification
            $this->sendEmailNotification($email, $scheduleType);
        }
    }

    /**
     * Send an email notification using a Mailable.
     *
     * @param string $email
     * @param string $scheduleType
     * @return void
     */
    protected function sendEmailNotification($email, $scheduleType)
    {
        // Send email notification
        Mail::to($email)
            ->send(new ScheduleNotificationMail($scheduleType));
    }
}
