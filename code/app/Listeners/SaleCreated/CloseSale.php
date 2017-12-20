<?php

namespace App\Listeners\SaleCreated;

use App\Events\SaleCreated;
use App\Jobs\CalculateCountPartnersWithPurchases;
use App\Models\Sale;
use MongoDB\BSON\UTCDateTime;

class CloseSale
{
    /**
     * @param SaleCreated $event
     */
    public function handle(SaleCreated $event)
    {
        $event->sale->reduced = true;
        $event->sale->dateReduce = new UTCDateTime(time() * 1000);

        if ($event->sale->save()) {
            $sale = Sale::find($event->sale->_id);
            if ($sale) {
                $transactions = $sale->transactions->where('sale.type', '=', Sale::TYPE_CREATED);
                foreach ($transactions as $transaction) {
                    $transaction->sale()->associate($event->sale);
                    $transaction->save();
                }
                dispatch(new CalculateCountPartnersWithPurchases($sale->user, Sale::TYPE_CREATED));
            }
        }
    }

}
