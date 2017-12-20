<?php

namespace App\Listeners\PointDebited;

use App\Events\PointDebited;
use App\Events\StockAdded;
use App\Models\Transaction;

class AddStock
{
    /**
     * @param PointDebited $event
     */
    public function handle(PointDebited $event)
    {
        if ($event->user->getRepository()->havePack()) {
            $transaction = Transaction::addStocks(null, 9 * $event->number, 'From closing steps', $event->user);

            if ($transaction) {
                event(new StockAdded($transaction));
            }
        }
    }

}
