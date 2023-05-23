<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class RoleChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($newRole)
    {
        $this->newRole = $newRole;
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
        $role = '';
        if ($this->newRole->id == 1)
            $role = "User";
        elseif ($this->newRole->id == 2)
            $role = "Admin";
        return (new MailMessage)
            ->line('Your role has been changed to ' . $role)
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
            'id' => Str::uuid(), // Generate a new UUID
            'role_id' => $this->newRole->id,
            'role' => $this->newRole->role,
        ];
    }
}
