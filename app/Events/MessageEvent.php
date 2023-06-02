<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender_id;
    public $receiver_id;

    public $message;
    public $receiver_avatar;
    public $sender_avatar;


    /**
     * Create a new event instance.
     */
    public function __construct($sender_id, $receiver_id, $message, $sender_avatar,$receiver_avatar)
    {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->message = $message;
        $this->sender_avatar = $sender_avatar;
        $this->receiver_avatar = $receiver_avatar;
    }


    public function broadcastOn()
    {
        return ['chat'];
    }

    public function broadcastWith(): array
    {
        return [
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'message' => $this->message,
            'sender_avatar' => $this->sender_avatar,
            'receiver_avatar' => $this->receiver_avatar,

        ];
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
