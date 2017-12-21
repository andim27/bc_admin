<?php

namespace App\Events;

use App\Models\Settings;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MentorBonusSetted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;
    public $user;
    public $settings;

    /**
     * @param Transaction $transaction
     * @param User $user
     * @param Settings $settings
     */
    public function __construct(Transaction $transaction, User $user, Settings $settings)
    {
        $this->transaction = $transaction;
        $this->user = $user;
        $this->settings = $settings;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('mentor-bonus-setted');
    }
}
