<?php

namespace App\Listeners\AutoBonusAdded;

use App\Events\AutoBonusAdded;
use App\Models\Transaction;

class ReduceAutoBonus
{
    /**
     * @param AutoBonusAdded $event
     */
    public function handle(AutoBonusAdded $event)
    {
        Transaction::reduceAutoBonus($event->transaction);
    }

}