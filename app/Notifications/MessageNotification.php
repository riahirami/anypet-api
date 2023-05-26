<?php

namespace App\Notifications;

use App\Models\Messages;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MessageNotification extends Notification
{
    use Queueable;
    public $message;
    /**
     * Create a new notification instance.
     */
    public function __construct(Messages $message)
    {
        $this->message = $message ;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => Str::uuid(),
            'message' => $this->message->message,
            'sender' => $this->message->sender->firstname. " ".$this->message->sender->lastname ,
            'url' => url(':3000/users/conversation/' . $this->message->sender_id),
        ];
    }
}
