<?php

namespace App\Notifications;

use App\Models\Ad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdMatchingInterrestNotification extends Notification
{
    use Queueable;
    protected $ad;


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
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('A new ad has been added on the same category of one of your favorite ad.')
            ->line('Title: ' . $this->ad->title)
            ->action("Don't miss it", url('/ads/' . $this->ad->id))
            ->line('Thank you for using AnyPet!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ad_id' => $this->ad->id,
            'title' => $this->ad->title,
            'ad_url' => url('/ads/' . $this->ad->id),


        ];
    }
}
