<?php

namespace App\Listeners\MoneyCanceled;

use App\Events\MoneyCanceled;
use App\Models\Transaction;

class ReduceCancelMoney
{
    /**
     * @param MoneyCanceled $event
     */
    public function handle(MoneyCanceled $event)
    {
        Transaction::reduceCancelMoneys($event->transaction);
    }

}
