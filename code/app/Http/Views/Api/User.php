<?php

namespace App\Http\Views\Api;

/**
 * @SWG\Definition(required={"_id, sponsorId, email"}, @SWG\Xml(name="User"))
 */
Class User extends BaseView
{
    /**
     * @SWG\Property(property="_id", type="string")
     */

    /**
     * @SWG\Property(property="email", type="string")
     */

    /**
     * @SWG\Property(property="username", type="string")
     */

    /**
     * @SWG\Property(property="sponsorId", type="string")
     */

    /**
     * @SWG\Property(property="firstPurchase", type="string")
     */

    public function get()
    {
        $user = $this->_model;

        if ($user->firstPurchase && ! is_string($user->firstPurchase)) {
            $user->firstPurchase = $user->firstPurchase->toDateTime()->format('d.m.Y H:i:s');
        } else {
            $user->firstPurchase = '';
        }
        if ($user->lastDateLogin && ! is_string($user->lastDateLogin)) {
            $user->lastDateLogin = $user->lastDateLogin->toDateTime()->format('d.m.Y H:i:s');
        } else {
            $user->lastDateLogin = '';
        }
        if ($user->created && ! is_string($user->created)) {
            $user->created = $user->created->toDateTime()->format('d.m.Y H:i:s');
        } else {
            $user->created = '';
        }
        if ($user->birthday && ! is_string($user->birthday)) {
            $user->birthday = $user->birthday->toDateTime()->format('d.m.Y H:i:s');
        } else {
            $user->birthday = '';
        }
        if ($user->expirationDateBS && ! is_string($user->expirationDateBS)) {
            $user->expirationDateBS = $user->expirationDateBS->toDateTime()->format('d.m.Y H:i:s');
        } else {
            $user->expirationDateBS = '';
        }
        $promoTravelDateComplete = $user->getAttribute('promotions.travel.dateComplete');
        if ($promoTravelDateComplete && ! is_string($promoTravelDateComplete)) {
            $user->setAttribute('promotions.travel.dateComplete', $promoTravelDateComplete->toDateTime()->format('d.m.Y H:i:s'));
        } else {
            $user->setAttribute('promotions.travel.dateComplete', '');
        }
        $dateBuyPack = $user->getAttribute('statistics.dateBuyPack');
        if ($dateBuyPack && ! is_string($dateBuyPack)) {
            $user->setAttribute('statistics.dateBuyPack', $dateBuyPack->toDateTime()->format('d.m.Y H:i:s'));
        } else {
            $user->setAttribute('statistics.dateBuyPack', '');
        }
        $user->setAttribute('nextRegistration._id', strval($user->getAttribute('nextRegistration._id')));

        $careerHistoryArray = [];
        if ($user->careerHistory) {
            foreach ($user->careerHistory as $careerHistory) {
                if (isset($careerHistory['career'])) {
                    $careerHistory['career']['_id'] = strval($careerHistory['career']['_id']);
                } else {
                    $careerHistory['_id'] = strval($careerHistory['_id']);
                }
                if (isset($careerHistory['date'])) {
                    if ($careerHistory['date'] && !is_string($careerHistory['date'])) {
                        $careerHistory['date'] = $careerHistory['date']->toDateTime()->format('d.m.Y H:i:s');
                    } else {
                        $careerHistory['date'] = '';
                    }
                }
                $careerHistoryArray[] = $careerHistory;
            }
            $user->careerHistory = $careerHistoryArray;
        }

        return $user;
    }

}