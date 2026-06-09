<?php

namespace App\Notifications;

use App\Models\AssignmentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignmentGradedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public AssignmentSubmission $submission) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message'       => 'تم تصحيح واجبك: ' . $this->submission->assignment->title,
            'marks'         => $this->submission->marks_obtained,
            'total'         => $this->submission->assignment->total_marks,
            'feedback'      => $this->submission->teacher_feedback,
            'assignment_id' => $this->submission->assignment_id,
        ];
    }
}