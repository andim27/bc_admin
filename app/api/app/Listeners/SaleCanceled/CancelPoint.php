<?php

namespace App\Listeners\SaleCanceled;

use App\Events\SaleCanceled;
use App\Events\PointCanceled;
use App\Models\Transaction;

class CancelPoint
{
    /**
     * @param SaleCanceled $event
     */
    public function handle(SaleCanceled $event)
    {
        $transactions = $event->sale->transactions->where('type', '=', Transaction::TYPE_POINT);

        foreach ($transactions as $transaction) {
            $resultTransaction = Transaction::cancelPoints($event->sale, $transaction);

            if ($resultTransaction) {
                event(new PointCanceled($resultTransaction));
            }
        }
    }

}
