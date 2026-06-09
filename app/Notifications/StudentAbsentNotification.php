<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentAbsentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User   $student,
        public string $date
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message'    => 'تغيّب ' . $this->student->name . ' عن المدرسة اليوم ' . $this->date,
            'student_id' => $this->student->id,
            'date'       => $this->date,
        ];
    }
}