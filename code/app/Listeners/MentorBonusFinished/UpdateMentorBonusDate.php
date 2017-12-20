<?php

namespace App\Listeners\MentorBonusFinished;

use App\Events\MentorBonusFinished;
use MongoDB\BSON\UTCDateTime;

class UpdateMentorBonusDate
{
    /**
     * @param MentorBonusFinished $event
     */
    public function handle(MentorBonusFinished $event)
    {
        $event->settings->mentorBonusDate = new UTCDateTime(time() * 1000);

        $event->settings->save();
    }

}