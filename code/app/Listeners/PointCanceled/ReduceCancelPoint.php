<?php

namespace App\Listeners\PointCanceled;

use App\Events\PointCanceled;
use App\Models\Transaction;

class ReduceCancelPoint
{
    /**
     * @param PointCanceled $event
     */
    public function handle(PointCanceled $event)
    {
        Transaction::reduceCancelPoints($event->transaction);
    }

}
