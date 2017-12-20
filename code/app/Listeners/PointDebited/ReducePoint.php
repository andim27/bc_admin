<?php

namespace App\Listeners\PointDebited;

use App\Events\PointDebited;
use App\Models\Transaction;

class ReducePoint
{
    /**
     * @param PointDebited $event
     */
    public function handle(PointDebited $event)
    {
        Transaction::reducePoints($event->transactionLeft);
        Transaction::reducePoints($event->transactionRight);
    }

}
