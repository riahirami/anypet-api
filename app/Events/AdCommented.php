<?php

namespace App\Events;

use App\Models\Ad;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdCommented
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $comment;
    public $adOwner;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment, User $adOwner)
    {
        $this->comment = $comment;
        $this->adOwner = $adOwner;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
