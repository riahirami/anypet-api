<?php

namespace App\Notifications;

use App\Models\Ad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class AdCommented extends Notification implements ShouldQueue
{
    use Queueable;

    public $ad;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {        $newUrl = 'http://localhost:3000/advertise/' . $this->ad->id;

        return (new MailMessage)
            ->line('Your ad "' . $this->ad->title . '" has been commented by another user.')
            ->action('View Ad', $newUrl)
            ->line('Thank you for using AnyPet!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $newUrl = 'http://localhost:3000/advertise/' . $this->ad->id;

        return [
            'id' => Str::uuid(),
            'title' => $this->ad->title,
            'status' => $this->ad->status,
            'url' => $newUrl,
        ];
    }
}
