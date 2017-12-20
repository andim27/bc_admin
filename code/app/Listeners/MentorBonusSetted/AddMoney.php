<?php

namespace App\Listeners\MentorBonusSetted;

use App\Events\MentorBonusSetted;
use App\Events\MoneyAdded;
use App\Models\Transaction;
use App\Models\User;

class AddMoney
{
    /**
     * @param MentorBonusSetted $event
     */
    public function handle(MentorBonusSetted $event)
    {
        $mainUser = User::getMainUser();
        $user = $event->transaction->userTo;

        $comment = 'Mentor bonus for ' . $event->user->username;

        $transaction = Transaction::addMoneys(null, $mainUser, $user, $event->transaction->amount, $comment);

        if ($transaction) {
            event(new MoneyAdded($transaction));
        }
    }

}
