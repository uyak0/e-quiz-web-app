<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAssignmentAssigned extends Notification implements ShouldQueue
{
    use Queueable;
    public $title;
    public $description;
    public $id;
    public $classroom_id;

    /**
     * Create a new notification instance.
     */
    public function __construct(Assignment $assignment)
    {
        $this->title = $assignment->title;
        $this->description = $assignment->description;
        $this->id = $assignment->id;
        $this->classroom_id = $assignment->classroom_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line('You have been assigned a new assignment.')
                    ->line('Title: ' . $this->title)
                    ->line('Description: ' . $this->description)
            ->action(
                'View Assignment',
                'http://localhost:5173/student/' . auth()->user()->id .
                '/classroom/' . $this->classroom_id . '/assignment/' . $this->id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'classroom_id' => $this->classroom_id
        ]);
    }
}
