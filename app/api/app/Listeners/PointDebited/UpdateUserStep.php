<?php

namespace App\Listeners\PointDebited;

use App\Events\PointDebited;
use App\Events\UserStepUpdated;

class UpdateUserStep
{
    /**
     * @param PointDebited $event
     */
    public function handle(PointDebited $event)
    {
        $event->user->setAttribute('statistics.steps', $event->user->statistics['steps'] + $event->number);

        if ($event->user->save()) {
            event(new UserStepUpdated($event->user, $event->number));
        }
    }

}
