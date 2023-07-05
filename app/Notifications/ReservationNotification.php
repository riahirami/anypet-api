<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ReservationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;

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
    public function toMail($notifiable)
    {        $newUrl = 'http://localhost:3000/advertise/' . $this->reservation->advertisement->id;

        return (new MailMessage)
            ->line('You get an reservation request for the advertisement "' . $this->reservation->advertisement->title . '" from user '. $this->reservation->sender->firstname )
            ->action('View Reservation', $newUrl)
            ->line('Thank you for using Anypet !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {   $newUrl = 'http://localhost:3000/advertise/' . $this->reservation->advertisement->id;

        return [
            'id' => Str::uuid()->toString(), // Generate a new UUID
            'ad' => $this->reservation->advertisement->title,
            'receiver' => $this->reservation->receiver,
            'sender' => $this->reservation->sender,
            'status' => $this->reservation->status,
            'url' => $newUrl
        ];
    }
}
