<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\Sales;
use app\models\Transaction;
use app\models\Users;
use MongoDB\BSON\UTCDatetime;
use Yii;
use yii\helpers\Url;
use app\models\api;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        $statisticInfo = [
            // интервалы выборки
            'dateInterval'                  =>[],
            // инф. о новых зарегестровавшихся
            'newRegistration'               => 0,
            'newRegistrationForMonth'       => [],
            // инф. об оплаченных из зарегестрировавшихся
            'ofThemPaid'                    => 0,
            'ofThemPaidForMonth'            => [],
            // общий приход
            'generalReceiptMoney'           => 0,
            'generalReceiptMoneyMonth'      => [],
            // приход за ваучеры
            'receiptVoucher'                => 0,

            // на лицевых считах
            'onPersonalAccounts'            => 0,

            // заказано на вывод
            'orderedForWithdrawal'          => 0,
            'orderedForWithdrawalMonth'     => [],

            // начисленно коммисионных
            'feesCommission'                => 0,
            'feesCommissionMonth'           => [],

            // выданно коммисионных
            'issuedCommission'                => 0,
            'issuedCommissionMonth'           => [],
        ];

        $request = Yii::$app->request->post();
        if(empty($request)){
            $request['to'] = date("Y-m");
            $date = strtotime('-3 month', strtotime($request['to']));
            $request['from'] = date('Y-m', $date);
        }
//        $infoDateTo = explode("-",$request['to']);
//        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);
//
//        $queryDateTo = strtotime($request['to'].'-'.$countDay.' 23:59:59') * 1000;
//        $queryDateFrom = strtotime($request['from'].'-01 00:00:00') * 1000;
//
//        $model = Users::find()->select(['created','firstPurchase'])->where([
//            'created' => [
//                '$gte' => new UTCDatetime($queryDateFrom),
//                '$lte' => new UTCDateTime($queryDateTo)
//            ]
//        ])->all();
//        if(!empty($model)) {
//            foreach ($model as $item) {
//                $dateRegitration = $item->created->toDateTime()->format('Y-m');
//                $dateOfPaid = $item->firstPurchase->toDateTime()->format('Y-m');
//
//                if (empty($statisticInfo['newRegistrationForMonth'][$dateRegitration])) {
//                    $statisticInfo['newRegistrationForMonth'][$dateRegitration] = 0;
//                }
//                $statisticInfo['newRegistrationForMonth'][$dateRegitration]++;
//                $statisticInfo['newRegistration']++;
//
//
//                if ($request['from'] <= $dateOfPaid && $dateOfPaid <= $request['to']) {
//                    if (empty($statisticInfo['ofThemPaidForMonth'][$dateOfPaid])) {
//                        $statisticInfo['ofThemPaidForMonth'][$dateOfPaid] = 0;
//                    }
//                    $statisticInfo['ofThemPaidForMonth'][$dateOfPaid]++;
//                    $statisticInfo['ofThemPaid']++;
//                }
//            }
//        }
//
//        $model = Sales::find()->select(['dateCreate','price'])->where([
//            'dateCreate' => [
//                '$gte' => new UTCDatetime($queryDateFrom),
//                '$lte' => new UTCDateTime($queryDateTo)
//            ],
//            'type'=>[
//                '$ne'=>-1
//            ]
//        ])->all();
//        if(!empty($model)) {
//            foreach ($model as $item) {
//                $dateCreate = $item->dateCreate->toDateTime()->format('Y-m');
//
//                if (empty($statisticInfo['generalReceiptMoneyMonth'][$dateCreate])) {
//                    $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] = 0;
//                }
//                $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] += $item->price;
//                $statisticInfo['generalReceiptMoney'] += $item->price;
//            }
//        }
//
//        $statisticInfo['receiptVoucher'] = Transaction::find()
//            ->select(['amount'])
//            ->where([
//                'dateCreate' => [
//                    '$gte' => new UTCDatetime($queryDateFrom),
//                    '$lte' => new UTCDateTime($queryDateTo)
//                ],
//                'forWhat' => [
//                    '$regex' => 'Creating voucher for product',
//                    '$options' => 'i'
//                ],
//            ])
//            ->sum('amount');
//
//        $statisticInfo['onPersonalAccounts'] = Users::find()->where(['username' => ['$ne'=>'main']])->sum('moneys');
//
//        $model = Transaction::find()
//            ->select(['amount','dateCreate'])
//            ->where([
//                'dateCreate' => [
//                    '$gte' => new UTCDatetime($queryDateFrom),
//                    '$lte' => new UTCDateTime($queryDateTo)
//                ],
//                'forWhat'=> [
//                    '$regex' => 'Withdrawal',
//                ],
//                'reduced' => ['$ne'=>false]
//            ])
//            ->all();
//        if(!empty($model)) {
//            foreach ($model as $item) {
//                $dateCreate = $item->dateCreate->toDateTime()->format('Y-m');
//
//                if (empty($statisticInfo['orderedForWithdrawalMonth'][$dateCreate])) {
//                    $statisticInfo['orderedForWithdrawalMonth'][$dateCreate] = 0;
//                }
//                $statisticInfo['orderedForWithdrawalMonth'][$dateCreate] += $item->amount;
//                $statisticInfo['orderedForWithdrawal'] += $item->amount;
//            }
//        }
//
//
//        $model = Transaction::find()
//            ->select(['amount','dateCreate'])
//            ->where([
//                'dateCreate' => [
//                    '$gte' => new UTCDatetime($queryDateFrom),
//                    '$lte' => new UTCDateTime($queryDateTo)
//                ],
//                'forWhat' => [
//                    '$regex' => 'purchase for',
//                    '$options' => 'i'
//                ],
//            ])
//            ->all();
//        if(!empty($model)) {
//            foreach ($model as $item) {
//                $dateCreate = $item->dateCreate->toDateTime()->format('Y-m');
//
//                if (empty($statisticInfo['feesCommissionMonth'][$dateCreate])) {
//                    $statisticInfo['feesCommissionMonth'][$dateCreate] = 0;
//                }
//                $statisticInfo['feesCommissionMonth'][$dateCreate] += $item->amount;
//                $statisticInfo['feesCommission'] += $item->amount;
//            }
//        }
//
//        $model = Transaction::find()
//            ->select(['amount','dateCreate'])
//            ->where([
//                'dateCreate' => [
//                    '$gte' => new UTCDatetime($queryDateFrom),
//                    '$lte' => new UTCDateTime($queryDateTo)
//                ],
//                'forWhat'=> [
//                    '$regex' => 'Withdrawal',
//                ],
//                'reduced' => ['$ne'=>false],
//                'confirmed'=>1
//            ])
//            ->all();
//        if(!empty($model)) {
//            foreach ($model as $item) {
//                $dateCreate = $item->dateCreate->toDateTime()->format('Y-m');
//
//                if (empty($statisticInfo['issuedCommissionMonth'][$dateCreate])) {
//                    $statisticInfo['issuedCommissionMonth'][$dateCreate] = 0;
//                }
//                $statisticInfo['issuedCommissionMonth'][$dateCreate] += $item->amount;
//                $statisticInfo['issuedCommission'] += $item->amount;
//            }
//        }
//
//
//        $i = 0;
//        for ($iDate=$request['from'];$iDate<=$request['to'];$iDate=date('Y-m',strtotime('+1 month', strtotime($iDate)))) {
//            if(empty($statisticInfo['newRegistrationForMonth'][$iDate])){
//                $statisticInfo['newRegistrationForMonth'][$iDate] = [$i,0];
//            }else{
//                $statisticInfo['newRegistrationForMonth'][$iDate] = [$i,$statisticInfo['newRegistrationForMonth'][$iDate]];
//            }
//
//            if(empty($statisticInfo['ofThemPaidForMonth'][$iDate])){
//                $statisticInfo['ofThemPaidForMonth'][$iDate] = [$i,0];
//            }else{
//                $statisticInfo['ofThemPaidForMonth'][$iDate] = [$i,$statisticInfo['ofThemPaidForMonth'][$iDate]];
//            }
//
//            if(empty($statisticInfo['generalReceiptMoneyMonth'][$iDate])){
//                $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i,0];
//            }else{
//                $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i,round($statisticInfo['generalReceiptMoneyMonth'][$iDate])];
//            }
//
//            if(empty($statisticInfo['feesCommissionMonth'][$iDate])){
//                $statisticInfo['feesCommissionMonth'][$iDate] = [$i,0];
//            }else{
//                $statisticInfo['feesCommissionMonth'][$iDate] = [$i,round($statisticInfo['feesCommissionMonth'][$iDate])];
//            }
//
//            $statisticInfo['dateInterval'][] = [$i,$iDate];
//
//            $i++;
//        }
//
//        ksort($statisticInfo['newRegistrationForMonth']);
//        ksort($statisticInfo['ofThemPaidForMonth']);
//        ksort($statisticInfo['generalReceiptMoneyMonth']);
//        ksort($statisticInfo['feesCommissionMonth']);



       return $this->render('index', [
            'user' => $this->user,
            'statisticInfo' => $statisticInfo,
            'request' => $request
//            'registrationsStatisticsPerMoths' => api\graph\RegistrationsStatistics::get($this->user->accountId)
        ]);
    }

}
