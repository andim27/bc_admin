<?php

namespace App\Events;

use App\Models\Career;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CareerUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $career;
    public $number;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Career $career
     * @param $number
     */
    public function __construct(User $user, Career $career, $number)
    {
        $this->user = $user;
        $this->career = $career;
        $this->number = $number;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('career-updated');
    }
}
