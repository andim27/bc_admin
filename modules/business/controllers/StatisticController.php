<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api;
use Yii;

class StatisticController extends BaseController
{
    public function actionIndex()
    {
        $data = [
            'user_id' => $this->user->id,
            'in_busines' => $this->user->firstPurchase > 0 ? date_diff(date_create(gmdate('d.m.Y', $this->user->firstPurchase)), date_create())->days : 0,
            'registrations' => $this->user->rightSideNumberUsers + $this->user->leftSideNumberUsers,
            'partners' => $this->user->statistics->partnersWithPurchases,
            'self_recommendations' => $this->user->statistics->personalPartners,
            'self_partners' => $this->user->statistics->personalPartnersWithPurchases,
            'total_earned' => $this->user->statistics->structIncome + $this->user->statistics->personalIncome
        ];

        return $this->render('index', [
            'data' => $data,
            'registrationsStatisticsPerMoths' => api\graph\RegistrationsStatistics::get($this->user->accountId),
            'incomeStatisticsPerMoths' => api\graph\IncomeStatistics::get($this->user->id),
            'checksStatisticsPerMoths' => api\graph\ChecksStatistics::get($this->user->id),
            'user' => json_encode($this->user)
        ]);
    }

    public function actionPersonalPartners()
    {
        $users = api\User::personalPartners($this->user->id);

        $result = [];

        foreach ($users as $key => $user) {
            $addresArray = [];
            if ($user->countryCode) {
                $addresArray[$key] = $user->countryCode;
            }
            if ($user->city) {
                $addresArray[$key] = $user->city;
            }
            if ($user->address) {
                $addresArray[$key] = $user->address;
            }

            $result[] = [
                'address' => implode(',', $addresArray),
                'lat' => $user->settings->onMapX,
                'lng' => $user->settings->onMapY,
                'accountId' => $user->accountId
            ];
        }

        return json_encode($result);
    }

}