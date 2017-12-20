<?php

namespace App\Listeners\PointDebited;

use App\Events\MoneyAdded;
use App\Events\PointDebited;
use App\Events\SaleCreated;
use App\Models\Sale;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\User;

class AddMoney
{
    /**
     * @param PointDebited $event
     */
    public function handle(PointDebited $event)
    {
        $mainUser = User::getMainUser();
        $compensationForClosingSteps = Settings::first()->compensationForClosingSteps * $event->number;
        $comment = 'Closing steps';

        $transaction = Transaction::addMoneys(null, $mainUser, $event->user, $compensationForClosingSteps, $comment);

        if ($transaction) {
            event(new MoneyAdded($transaction));
        }
    }

}
