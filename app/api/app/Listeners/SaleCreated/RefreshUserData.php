<?php

namespace App\Listeners\SaleCreated;

use App\Events\SaleCreated;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;

class RefreshUserData
{
    /**
     * @param SaleCreated $event
     */
    public function handle(SaleCreated $event)
    {
        $product = Product::where('product', '=', $event->sale->product)->first();

        if ($product && $product->expirationPeriod && isset($product->expirationPeriod['value']) && isset($product->expirationPeriod['format'])) {
            $user = $event->sale->user;
            $date = Carbon::now();

            $dateInterval = \DateInterval::createFromDateString($product->expirationPeriod['value'] . ' ' . $product->expirationPeriod['format']);

            if ($user->bs) {
                $userExpirationDate = Carbon::createFromTimestamp($user->expirationDateBS->toDateTime()->getTimestamp());

                if ($product->type == Sale::PRODUCT_TYPE_SUPPORT) {
                    $newExpirationDate = $userExpirationDate->add($dateInterval);
                } else {
                    $newExpirationDate = $date->add($dateInterval);
                    if ($newExpirationDate <= $userExpirationDate) {
                        unset($newExpirationDate);
                    }
                }
            } else {
                $newExpirationDate = $date->add($dateInterval);
            }

            if (isset($newExpirationDate)) {
                $user->bs = true;
                $user->expirationDateBS = new UTCDateTime($newExpirationDate->timestamp * 1000);

                $user->save();
            }
        }

        if ($product->product > $user->statistics['pack']) {
            if ($product->product <= 3) {
                $user->setAttribute('statistics.pack', $product->product);
            } else {
                switch($product->product) {
                    case 19:
                    case 35:
                    case 40:
                        $user->setAttribute('statistics.pack', 1);
                    break;
                    case 15:
                    case 20:
                    case 23:
                    case 25:
                    case 41:
                        $user->setAttribute('statistics.pack', 2);
                    break;
                    case 16:
                    case 17:
                    case 21:
                    case 22:
                    case 26:
                    case 27:
                    case 36:
                    case 37:
                    case 38:
                    case 39:
                    case 42:
                    case 43:
                    case 44:
                    case 45:
                    case 46:
                    case 47:
                    case 48:
                        $user->setAttribute('statistics.pack', 3);
                    break;
                }
            }

            $user->setAttribute('statistics.dateBuyPack', new UTCDateTime(time() * 1000));

            $user->save();
        }
    }

}
