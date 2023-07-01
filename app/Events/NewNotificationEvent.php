<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $notifiable_id;
    public $data;
    public $read_at;

    /**
     * Create a new event instance.
     */
    public function __construct($type, $notifiable_id, $data, $read_at)
    {
        $this->type=$type;
        $this->notifiable_id=$notifiable_id;
        $this->data=$data;
        $this->read_at=$read_at;
    }

    public function broadcastOn()
    {
        return ['notifications'];
    }

    public function broadcastWith(): array
    {
        return [
            'type' => $this->type,
            'notifiable_id' => $this->notifiable_id,
            'data' => $this->data,
            'read_at' => $this->read_at,

        ];
    }

    public function broadcastAs()
    {
        return 'notif';
    }
}
