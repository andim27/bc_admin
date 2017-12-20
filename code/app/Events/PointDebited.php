<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PointDebited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $number;
    public $transactionLeft;
    public $transactionRight;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param $number
     * @param $transactionLeft
     * @param $transactionRight
     */
    public function __construct(User $user, $number, $transactionLeft, $transactionRight)
    {
        $this->user = $user;
        $this->number = $number;
        $this->transactionLeft = $transactionLeft;
        $this->transactionRight = $transactionRight;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('point-debited');
    }
}
