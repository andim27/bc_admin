<?php

namespace App\Listeners\MentorBonusSetted;

use App\Events\MentorBonusSetted;
use App\Models\Transaction;

class ReduceMentorBonus
{
    /**
     * @param MentorBonusSetted $event
     */
    public function handle(MentorBonusSetted $event)
    {
        Transaction::reduceMentorBonus($event->transaction);
    }

}