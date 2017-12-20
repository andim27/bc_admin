<?php

namespace App\Listeners\StockCanceled;

use App\Events\StockCanceled;
use App\Models\Transaction;

class ReduceCancelStock
{
    /**
     * @param StockCanceled $event
     */
    public function handle(StockCanceled $event)
    {
        Transaction::reduceCancelStocks($event->transaction);
    }

}
