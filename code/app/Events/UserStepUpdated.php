<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserStepUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $number;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param $number
     */
    public function __construct(User $user, $number)
    {
        $this->user = $user;
        $this->number = $number;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user-step-updated');
    }
}
