<?php

namespace App\Listeners\SaleCreated;

use App\Events\SaleCreated;
use App\Events\StockAdded;
use App\Models\Transaction;

class AddStock
{
    /**
     * @param SaleCreated $event
     */
    public function handle(SaleCreated $event)
    {
        if ($event->sale->bonusStocks) {
            $transaction = Transaction::addStocks($event->sale);

            if ($transaction) {
                event(new StockAdded($transaction));
            }
        }
    }

}
