<?php

namespace App\Listeners\SaleCanceled;

use App\Events\SaleCanceled;

class UpdatePersonalIncome
{
    /**
     * @param SaleCanceled $event
     */
    public function handle(SaleCanceled $event)
    {
        $amount = $event->sale->price;

        $user = $event->sale->user;

        if (isset($user->statistics['personalIncome'])) {
            $personalIncome = $user->statistics['personalIncome'] - $amount;
        } else {
            $personalIncome = 0;
        }

        $user->setAttribute('statistics.personalIncome', $personalIncome);

        $user->save();
    }

}
