<?php

namespace App\Listeners\SaleCreated;

use App\Events\PointAdded;
use App\Events\SaleCreated;
use App\Models\Transaction;

class AddPoint
{
    /**
     * @param SaleCreated $event
     */
    public function handle(SaleCreated $event)
    {
        if ($event->sale->bonusPoints > 0) {
            $users = $event->sale->user->getRepository()->getUsersToMain();
            $previewUser = $event->sale->user;
            foreach ($users as $user) {
                if (! $user->bs) {
                    /**
                     * @todo send mail about lose points
                     */
                } else {
                    $transaction = Transaction::addPoints($event->sale, $user, $previewUser->side);

                    if ($transaction) {
                        event(new PointAdded($transaction));
                    }
                }
                $previewUser = $user;
            }
        }
    }

}
