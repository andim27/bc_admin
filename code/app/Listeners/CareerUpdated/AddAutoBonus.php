<?php

namespace App\Listeners\CareerUpdated;

use App\Events\AutoBonusAdded;
use App\Events\CareerUpdated;
use App\Models\Transaction;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectID;
use App\Models\User;
use Carbon\Carbon;

class AddAutoBonus
{
    /**
     * @param CareerUpdated $event
     */
    public function handle(CareerUpdated $event)
    {
        $user = $event->user;

        if ($user->getRepository()->havePack()) {
            $mainUser = User::getMainUser();

            $nowDate = Carbon::now();
            $dateFrom = Carbon::create($nowDate->year, $nowDate->month, 1, 0, 0, 0);
            $tmpDate = clone $dateFrom;
            $dateInterval = \DateInterval::createFromDateString('1 month');
            $dateTo = $tmpDate->add($dateInterval);

            $transactions = Transaction::where('idTo', '=', new ObjectID($user->_id))
                ->where('type', '=', Transaction::TYPE_AUTO_BONUS)
                ->where('reduced', '=', true)
                ->where('dateCreate', '>=', new UTCDateTime($dateFrom->timestamp * 1000))
                ->where('dateCreate', '<=', new UTCDateTime($dateTo->timestamp * 1000));

            $total = $transactions->sum('amount');

            $steps = $event->number;

            $autoBonus = 0;

            switch ($user->rank) {
                case 0:
                case 1:
                case 2:
                case 3:
                    $tmpAutoBonus = (10 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 100 ? $tmpAutoBonus : 100 - $total;
                    break;
                case 4:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 150 ? $tmpAutoBonus : 150 - $total;
                    break;
                case 5:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 200 ? $tmpAutoBonus : 200 - $total;
                    break;
                case 6:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 250 ? $tmpAutoBonus : 250 - $total;
                    break;
                case 7:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 300 ? $tmpAutoBonus : 300 - $total;
                    break;
                case 8:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 500 ? $tmpAutoBonus : 500 - $total;
                    break;
                case 9:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 700 ? $tmpAutoBonus : 700 - $total;
                    break;
                case 10:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 1000 ? $tmpAutoBonus : 1000 - $total;
                    break;
                case 11:
                case 12:
                case 13:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 1500 ? $tmpAutoBonus : 1500 - $total;
                    break;
                case 14:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 2000 ? $tmpAutoBonus : 2000 - $total;
                    break;
                case 15:
                    $tmpAutoBonus = (20 * $steps);
                    $totalAutoBonus = $total + $tmpAutoBonus;
                    $autoBonus = $totalAutoBonus <= 3000 ? $tmpAutoBonus : 3000 - $total;
                    break;
            }

            if ($autoBonus > 0) {
                $transaction = Transaction::addAutoBonus($mainUser, $user, $autoBonus, 'Auto bonus');

                if ($transaction) {
                    event(new AutoBonusAdded($transaction));
                }
            }
        }
    }

}