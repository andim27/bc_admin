<?php

namespace App\Listeners\SaleCanceled;

use App\Events\SaleCanceled;
use App\Events\MoneyCanceled;
use App\Models\Transaction;

class CancelMoney
{
    /**
     * @param SaleCanceled $event
     */
    public function handle(SaleCanceled $event)
    {
        $transactions = $event->sale->transactions->where('type', '=', Transaction::TYPE_MONEY);

        foreach ($transactions as $transaction) {
            $resultTransaction = Transaction::cancelMoneys($event->sale, $transaction);

            if ($resultTransaction) {
                event(new MoneyCanceled($resultTransaction));
            }
        }
    }

}
