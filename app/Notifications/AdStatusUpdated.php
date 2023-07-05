<?php

namespace App\Notifications;

use App\Models\Ad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class AdStatusUpdated extends Notification
{
    use Queueable;

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
    {
        $statusMessage = '';
        switch ($this->ad->status) {
            case 0:
                $statusMessage = 'Waiting.';
                break;
            case 1:
                $statusMessage = 'Canceled.';
                break;
            case 2:
                $statusMessage = 'Approved.';
                break;

            default:
                $statusMessage = 'Your ad status has been updated.';
                break;
        }
        $newUrl = 'http://localhost:3000/advertise/' . $this->ad->id;

        return (new MailMessage)
            ->subject('Ad Status Updated')
            ->line($this->ad->user->firstname . ' your ad status has been updated.')
            ->line('Title: ' . $this->ad->title)
            ->line('New Status: ' . $statusMessage)
            ->action('View Ad', $newUrl)
            ->line('Thank you for using AnyPet!');


    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {        $newUrl = 'http://localhost:3000/advertise/' . $this->ad->id;

        return [
            'id' => Str::uuid(), // Generate a new UUID
            'title' => $this->ad->title,
            'status' => $this->ad->status,
            'url' => $newUrl,
        ];
    }
}
