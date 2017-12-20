<?php

namespace App\Listeners\UserStepUpdated;

use App\Events\CareerUpdated;
use App\Events\UserStepUpdated;
use App\Models\Career;
use App\Models\Sale;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;

class UpdateCareer
{
    /**
     * @param UserStepUpdated $event
     */
    public function handle(UserStepUpdated $event)
    {
        $user = $event->user;

        if ($user->rank < 4) {
            $firstSale = $user->getRepository()->getFirstSale();

            if ($firstSale) {
                $packDate = $firstSale->dateCreate;
            }
        } else {
            if ($user->careerHistory && count($user->careerHistory) > 0) {
                $packDate = $user->careerHistory[count($user->careerHistory) - 1]['date'];
            }
        }

        if (isset($packDate) && $packDate) {
            $packDate = Carbon::createFromTimestamp($packDate->toDateTime()->getTimestamp());

            $career = Career::where('rank', '>', $user->rank)->orderBy('rank asc')->first();

            if ($career && $user->statistics['steps'] >= $career->steps) {
                $user->rank = $career->rank;

                $careerHistory = [
                    'career' => [
                        '_id' => new \MongoDB\BSON\ObjectID($career->_id),
                        'rank' => intval($career->rank),
                        'steps' => intval($career->steps),
                        'time' => intval($career->time),
                        'bonus' => $career->bonus,
                        '__v' => intval($career->__v)
                    ],
                    'date' => new UTCDateTime(time() * 1000)
                ];

                $user->push('careerHistory', $careerHistory);

                $dateInterval = \DateInterval::createFromDateString($career->time . ' days');

                if ($user->save() && $career->bonus > 0 && ($career->time == 0 || $packDate->add($dateInterval) >= Carbon::now())) {
                    event(new CareerUpdated($user, $career, $event->number));
                }
            }
        }
    }

}
