<?php

namespace App\Listeners\PointDebited;

use App\Events\PointDebited;
use App\Models\Settings;

class UpdateStructureIncome
{
    /**
     * @param PointDebited $event
     */
    public function handle(PointDebited $event)
    {
        $amount = Settings::first()->compensationForClosingSteps * $event->number;

        $user = $event->user;

        $structureIncome = $user->statistics['structIncome'] + $amount;

        $user->setAttribute('statistics.structIncome', $structureIncome);

        $user->save();
    }

}
