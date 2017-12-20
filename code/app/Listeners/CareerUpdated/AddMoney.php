<?php

namespace App\Listeners\CareerUpdated;

use App\Events\CareerUpdated;
use App\Events\MoneyAdded;
use App\Models\Transaction;
use App\Models\User;

class AddMoney
{
    /**
     * @param CareerUpdated $event
     */
    public function handle(CareerUpdated $event)
    {
        $mainUser = User::getMainUser();

        $comment = 'Bonus per the achievement of career';

        $transaction = Transaction::addMoneys(null, $mainUser, $event->user, $event->career->bonus, $comment);

        if ($transaction) {
            event(new MoneyAdded($transaction));
        }
    }

}
