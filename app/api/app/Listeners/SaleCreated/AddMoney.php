<?php

namespace App\Listeners\SaleCreated;

use App\Events\MoneyAdded;
use App\Events\SaleCreated;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\User;

class AddMoney
{
    /**
     * @param SaleCreated $event
     */
    public function handle(SaleCreated $event)
    {
        $mainUser = User::getMainUser();
        $companyUser = User::getCompanyUser();

        $comment = 'Purchase for a partner ' . $event->sale->user->username;

        /**
         * Записываем приход средств за покупку, на счет основного пользователя.
         */
        if ($event->sale->productType !== Sale::PRODUCT_TYPE_BALANCE) {
            $transaction = Transaction::addMoneys($event->sale, $companyUser, $mainUser, $event->sale->price, $comment);

            if ($transaction) {
                event(new MoneyAdded($transaction));
            }
        }

        /**
         * Выплачиваем бонус за счет средств основного пользователя.
         */
        $user = $event->sale->user;
        $sponsor = $event->sale->user->sponsor;

        switch($event->sale->productType) {
            case Sale::PRODUCT_TYPE_VIPVIP:
                if ($sponsor && $sponsor->bs && $event->sale->bonusMoney > 0) {
                    if ($event->sale->product == 35) {
                        $bonusMoney = $event->sale->bonusMoney;
                    } else {
                        switch($sponsor->statistics['pack']) {
                            case 3:
                                $bonusMoney = $event->sale->bonusMoney;
                                break;
                            case 2:
                                if ($event->sale->bonusMoney > 100) {
                                    $bonusMoney = 100;
                                } else {
                                    $bonusMoney = $event->sale->bonusMoney;
                                }
                                break;
                            case 1:
                                if ($event->sale->bonusMoney > 30) {
                                    $bonusMoney = 30;
                                } else {
                                    $bonusMoney = $event->sale->bonusMoney;
                                }
                                break;
                        }
                    }

                    $comment = 'Purchase for a partner ' . $event->sale->user->username;

                    $transaction = Transaction::addMoneys($event->sale, $mainUser, $sponsor, $bonusMoney, $comment);

                    if ($transaction) {
                        event(new MoneyAdded($transaction));
                    }
                }
            break;
            case Sale::PRODUCT_TYPE_WELLNESS:
                if ($sponsor && $sponsor->bs && $event->sale->bonusMoney > 0) {
                    if ($event->sale->product == 35) {
                        $bonusMoney = $event->sale->bonusMoney;
                    } else {
                        switch($sponsor->statistics['pack']) {
                            case 3:
                                $bonusMoney = $event->sale->bonusMoney;
                            break;
                            case 2:
                                if ($event->sale->bonusMoney > 100) {
                                    $bonusMoney = 100;
                                } else {
                                    $bonusMoney = $event->sale->bonusMoney;
                                }
                            break;
                            case 1:
                                if ($event->sale->bonusMoney > 50) {
                                    $bonusMoney = 50;
                                } else {
                                    $bonusMoney = $event->sale->bonusMoney;
                                }
                            break;
                        }
                    }

                    $comment = 'Purchase for a partner ' . $event->sale->user->username;

                    $transaction = Transaction::addMoneys($event->sale, $mainUser, $sponsor, $bonusMoney, $comment);

                    if ($transaction) {
                        event(new MoneyAdded($transaction));
                    }
                }
            break;
            case Sale::PRODUCT_TYPE_BALANCE_VIPVIP:
                if ($user->bs && $event->sale->bonusMoney > 0) {
                    $comment = 'Purchase for a partner ' . $event->sale->user->username;

                    $transaction = Transaction::addMoneys($event->sale, $mainUser, $user, $event->sale->bonusMoney, $comment);

                    if ($transaction) {
                        event(new MoneyAdded($transaction));
                    }
                }
            break;
            case Sale::PRODUCT_TYPE_BALANCE_WELLNESS:
            case Sale::PRODUCT_TYPE_BALANCE_TOP_UP:
                if ($sponsor && $sponsor->bs && $event->sale->bonusMoney > 0) {
                    $comment = 'Purchase for a partner ' . $event->sale->user->username;

                    $transaction = Transaction::addMoneys($event->sale, $mainUser, $sponsor, $event->sale->bonusMoney, $comment);

                    if ($transaction) {
                        event(new MoneyAdded($transaction));
                    }
                }
            break;
            case Sale::PRODUCT_TYPE_BALANCE:
                $comment = 'Entering the money';

                $transaction = Transaction::addMoneys($event->sale, $companyUser, $user, $event->sale->price, $comment);

                if ($transaction) {
                    event(new MoneyAdded($transaction));
                }
            break;
            case Sale::PRODUCT_TYPE_VIPCOIN:
            case Sale::PRODUCT_TYPE_VIPCOIN_UPGRADE:
                if ($sponsor && $sponsor->bs && $event->sale->bonusMoney > 0) {
                    $comment = 'Purchase for a partner ' . $event->sale->user->username;

                    if ($sponsor->getRepository()->hasInvestorPack()) {
                        $bonusMoney = $event->sale->bonusMoney;
                    } else {
                        switch($event->sale->product) {
                            case 40:
                                switch ($sponsor->statistics['pack']) {
                                    case 3:
                                    case 2:
                                    case 1:
                                        $bonusMoney = 25;
                                    break;
                                }
                            break;
                            case 41:
                                switch ($sponsor->statistics['pack']) {
                                    case 3:
                                    case 2:
                                        $bonusMoney = 60;
                                    break;
                                    case 1:
                                        $bonusMoney = 25;
                                    break;
                                }
                            break;
                            case 42:
                                switch ($sponsor->statistics['pack']) {
                                    case 3:
                                        $bonusMoney = 180;
                                    break;
                                    case 2:
                                        $bonusMoney = 60;
                                    break;
                                    case 1:
                                        $bonusMoney = 25;
                                    break;
                                }
                            break;
                            case 43:
                            case 44:
                            case 45:
                            case 46:
                            case 47:
                            case 48:
                                switch ($sponsor->statistics['pack']) {
                                    case 3:
                                    case 2:
                                    case 1:
                                        $bonusMoney = 180;
                                    break;
                                }
                            break;
                        }
                    }

                    $transaction = Transaction::addMoneys($event->sale, $mainUser, $sponsor, $bonusMoney, $comment);

                    if ($transaction) {
                        event(new MoneyAdded($transaction));
                    }
                }
            break;
        }
    }

}
