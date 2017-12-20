<?php

namespace App\Listeners\MoneyAdded;

use App\Events\MoneyAdded;
use App\Models\Transaction;

class ReduceMoney
{
    /**
     * @param MoneyAdded $event
     */
    public function handle(MoneyAdded $event)
    {
        Transaction::reduceMoneys($event->transaction);
    }

}
