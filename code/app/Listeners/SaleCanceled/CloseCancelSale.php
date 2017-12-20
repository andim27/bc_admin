<?php

namespace App\Listeners\SaleCanceled;

use App\Events\SaleCanceled;
use App\Events\CancelSaleClosed;
use App\Jobs\CalculateCountPartnersWithPurchases;
use App\Models\Sale;
use MongoDB\BSON\UTCDateTime;

class CloseCancelSale
{
    /**
     * @param SaleCanceled $event
     */
    public function handle(SaleCanceled $event)
    {
        $event->sale->reduced = true;
        $event->sale->dateReduce = new UTCDateTime(time() * 1000);

        if ($event->sale->save()) {
            $sale = Sale::find($event->sale->_id);
            if ($sale) {
                $transactions = $sale->transactions->where('sale.type', '=', Sale::TYPE_CANCELED);
                foreach ($transactions as $transaction) {
                    $transaction->sale()->associate($event->sale);
                    $transaction->save();
                }
                dispatch(new CalculateCountPartnersWithPurchases($sale->user, Sale::TYPE_CANCELED));
            }
        }
    }

}
