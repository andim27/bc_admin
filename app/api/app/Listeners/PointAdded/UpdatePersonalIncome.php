<?php

namespace App\Listeners\PointAdded;

use App\Events\PointAdded;

class UpdatePersonalIncome
{
    /**
     * @param PointAdded $event
     */
    public function handle(PointAdded $event)
    {
        $amount = $event->transaction->amount;

        $user = $event->transaction->userTo;

        if (isset($user->statistics['personalIncome'])) {
            $personalIncome = $user->statistics['personalIncome'] + $amount;
        } else {
            $personalIncome = $amount;
        }

        $user->setAttribute('statistics.personalIncome', $personalIncome);

        $user->save();
    }

}
