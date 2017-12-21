<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\Sale;
use App\Models\Pin;

class SaleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sale;
    public $pin;

    /**
     * Create a new event instance.
     *
     * @param Sale $sale
     * @param Pin|null $pin
     */
    public function __construct(Sale $sale, Pin $pin = null)
    {
        $this->sale = $sale;
        $this->pin = $pin;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('sale-created');
    }
}
