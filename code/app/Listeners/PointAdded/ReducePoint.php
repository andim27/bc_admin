<?php

namespace App\Listeners\PointAdded;

use App\Events\PointAdded;
use App\Models\Transaction;

class ReducePoint
{
    /**
     * @param PointAdded $event
     */
    public function handle(PointAdded $event)
    {
        Transaction::reducePoints($event->transaction);
    }

}
