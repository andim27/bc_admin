<?php

namespace App\Listeners\StockAdded;

use App\Events\StockAdded;
use App\Models\Transaction;

class ReduceStock
{
    /**
     * @param StockAdded $event
     */
    public function handle(StockAdded $event)
    {
        Transaction::reduceStocks($event->transaction);
    }

}
