<?php

namespace App\Notifications;

use App\Models\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewQuizAssigned extends Notification
{
    use Queueable;
    public $id;
    public $title;
    public $due_date;
    public $classroom_id;

    /**
     * Create a new notification instance.
     */
    public function __construct(Quiz $quiz)
    {
        $this->id = $quiz->id;
        $this->title = $quiz->title;
        $this->due_date = $quiz->due_date;
        $this->classroom_id = $quiz->classroom_id;
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
                    ->line('You have been assigned a new quiz.')
                    ->line('Title: ' . $this->title)
                    ->line('Due Date: ' . $this->due_date)
            ->action('View Quiz',
                'http://localhost:5173/student/' . auth()->user()->id .
                '/classroom/' . $this->classroom_id . '/quiz/' . $this->id);
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
            'due_date' => $this->due_date,
            'classroom_id' => $this->classroom_id
        ]);
    }
}
