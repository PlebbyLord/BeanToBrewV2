<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $scheduleType;

    /**
     * Create a new message instance.
     *
     * @param string $scheduleType
     */
    public function __construct($scheduleType)
    {
        $this->scheduleType = $scheduleType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('beantobrew24@gmail.com', 'Bean to Brew')
                    ->markdown('emails.schedule_notification')
                    ->subject('Schedule Notification: ' . $this->scheduleType);
    }
}
