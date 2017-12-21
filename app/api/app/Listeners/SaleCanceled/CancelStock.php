<?php

namespace App\Listeners\SaleCanceled;

use App\Events\SaleCanceled;
use App\Events\StockCanceled;
use App\Models\Transaction;

class CancelStock
{
    /**
     * @param SaleCanceled $event
     */
    public function handle(SaleCanceled $event)
    {
        if ($event->sale->bonusStocks) {
            $transaction = Transaction::cancelStocks($event->sale);

            if ($transaction) {
                event(new StockCanceled($transaction));
            }
        }
    }

}
