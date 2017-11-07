<?php

namespace app\modules\business\controllers;

use app\components\ArrayInfoHelper;
use app\controllers\BaseController;
use app\models\Pins;
use app\models\Products;
use app\models\Sales;
use app\models\Transaction;
use app\models\Users;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\api;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        $statisticInfo = [];
        if(Users::checkRule('show_statistic','sidebar_home') === true){
            $statisticInfo = $this->getStatisticInfo();
        }

        return $this->render('index', [
            'user' => $this->user,
            'statisticInfo' => $statisticInfo,
        ]);
    }


    protected function getStatisticInfo(){

        $listProducts = Products::find()->all();
        $listProductsTitle = ArrayInfoHelper::getArrayKeyValue($listProducts,'product','productName');
        $listProductsType = ArrayInfoHelper::getArrayKeyValue($listProducts,'product','type');

        $typeProject = [
            '1'     => 'VipVip',
            '3'     => 'VipVip',
            '5'     => 'Wellness',
            '7'     => 'Wellness',
            '8'     => 'Wellness',
            '9'     => 'VipCoin',
            '10'    => 'VipCoin',
        ];

        $statisticInfo = [
            'request'                       =>[],
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
            //Приход по программе vipcoin
            'generalReceiptMoney_VipCoin'        => 0,
            //Приход по программе Wellness
            'generalReceiptMoney_Wellness'     => 0,
            //Приход по программе VipVip
            'generalReceiptMoney_VipVip'   => 0,

            // приход за ваучеры
            'receiptVoucher'                => 0,
            //Приход ваучерами по программе vipcoin
            'receiptVoucher_VipCoin'        => 0,
            //Приход ваучерами по программе Wellness
            'receiptVoucher_Wellness'       => 0,
            //Приход ваучерами по программе VipVip
            'receiptVoucher_VipVip'         => 0,

            // отмена за ваучеры
            'cancellationVoucher'                => 0,
            //отмена ваучерами по программе vipcoin
            'cancellationVoucher_VipCoin'        => 0,
            //отмена ваучерами по программе Wellness
            'cancellationVoucher_Wellness'       => 0,
            //отмена ваучерами по программе VipVip
            'cancellationVoucher_VipVip'         => 0,

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
            $statisticInfo['request']['to'] = date("Y-m");
            $date = strtotime('-3 month', strtotime($statisticInfo['request']['to']));
            $statisticInfo['request']['from'] = date('Y-m', $date);
        } else {
            $statisticInfo['request']['from'] = $request['from'];
            $statisticInfo['request']['to'] = $request['to'];
        }

        $infoDateTo = explode("-",$statisticInfo['request']['to']);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

        $queryDateFrom = strtotime($statisticInfo['request']['from'].'-01 00:00:00') * 1000;
        $queryDateTo = strtotime($statisticInfo['request']['to'].'-'.$countDay.' 23:59:59') * 1000;

        // зарегистрировалось за выбранный период
        $model = (new \yii\mongodb\Query())
            ->select(['created'])
            ->from('users')
            ->where([
                'created' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->all();
        if(!empty($model)) {
            foreach ($model as $item) {
                $dateRegitration = $item['created']->toDateTime()->format('Y-m');

                if (empty($statisticInfo['newRegistrationForMonth'][$dateRegitration])) {
                    $statisticInfo['newRegistrationForMonth'][$dateRegitration] = 0;
                }
                $statisticInfo['newRegistrationForMonth'][$dateRegitration]++;
                $statisticInfo['newRegistration']++;
            }
        }
        unset($model);

        // первая покупка за выбранный период
        $model = (new \yii\mongodb\Query())
            ->select(['firstPurchase'])
            ->from('users')
            ->where([
                'firstPurchase' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->all();
        if(!empty($model)) {
            foreach ($model as $item) {
                $dateOfPaid = $item['firstPurchase']->toDateTime()->format('Y-m');

                if ($statisticInfo['request']['from'] <= $dateOfPaid && $dateOfPaid <= $statisticInfo['request']['to']) {
                    if (empty($statisticInfo['ofThemPaidForMonth'][$dateOfPaid])) {
                        $statisticInfo['ofThemPaidForMonth'][$dateOfPaid] = 0;
                    }
                    $statisticInfo['ofThemPaidForMonth'][$dateOfPaid]++;
                    $statisticInfo['ofThemPaid']++;
                }
            }
        }
        unset($model);

        $model = (new \yii\mongodb\Query())
            ->select(['dateCreate','price','product','username','project'])
            ->from('sales')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'type'=>[
                    '$ne'=>-1
                ],
                'productType'=>['$nin'=>[0,4]],
                'product'=>['$ne'=>'0'],

                //'product'=>['$nin'=>['0','6','7','8','9','10','11','12','13','14','28','29','30','31','32','33','34']],

                'username' =>[
                    '$ne'=>'main'
                ]
            ])
            ->all();

        if(!empty($model)) {
            foreach ($model as $item) {
                $dateCreate = $item['dateCreate']->toDateTime()->format('Y-m');

                if (empty($statisticInfo['generalReceiptMoneyMonth'][$dateCreate])) {
                    $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] = 0;
                }
                $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] += $item['price'];
                $statisticInfo['generalReceiptMoney'] += $item['price'];

                if (!empty($typeProject[$listProductsType[$item['product']]])) {
                    $statisticInfo['generalReceiptMoney_' . $typeProject[$listProductsType[$item['product']]]] += $item['price'];
                }

                // собираем информацию по товарам для товарооборота
                if (empty($statisticInfo['tradeTurnover']['listProduct'][$item['product']])) {
                    $statisticInfo['tradeTurnover']['listProduct'][$item['product']] = [
                        'title' => $listProductsTitle[$item['product']],
                        'price' => 0,
                        'count' => 0
                    ];
                }
                $statisticInfo['tradeTurnover']['listProduct'][$item['product']]['price'] = $item['price'];
                $statisticInfo['tradeTurnover']['listProduct'][$item['product']]['count']++;

            }
        }
        unset($model);

        $arrayQuery=[];
        foreach ($listProducts as $listProduct) {
            if(!empty($typeProject[$listProduct->type])){
                $arrayQuery[$typeProject[$listProduct->type]][] = 'Creating pin for product '.$listProduct->productName;
            }
        }

        // приходы по pin
        foreach ($arrayQuery as $k=>$item){
            $statisticInfo['receiptVoucher_'.$k] = Transaction::find()
                ->select(['amount'])
                ->where([
                    'dateCreate' => [
                        '$gte' => new UTCDatetime($queryDateFrom),
                        '$lte' => new UTCDateTime($queryDateTo)
                    ]
                ])
                ->andWhere(['IN','forWhat',$item])
                ->sum('amount');

            $statisticInfo['receiptVoucher'] += $statisticInfo['receiptVoucher_'.$k];
        }

        // отмены по pin
        $model = Pins::find()->where([
            'dateCreate' => [
                '$gte' => new UTCDatetime($queryDateFrom),
                '$lte' => new UTCDateTime($queryDateTo)
            ],
            'isDelete' => true
        ])->all();
        if(!empty($model)){
            foreach ($model as $item) {
                $infoPin = api\Pin::checkPin($item->pin);

                if(!empty($typeProject[$infoPin->type])){
                    $statisticInfo['cancellationVoucher_'.$typeProject[$infoPin->type]] += $infoPin->price;
                    $statisticInfo['cancellationVoucher'] += $infoPin->price;
                }

            }
        }

        $statisticInfo['onPersonalAccounts'] = (new \yii\mongodb\Query())
            ->select(['firstPurchase'])
            ->from('users')
            ->where(['username' => ['$ne'=>'main']])
            ->sum('moneys');

        $statisticInfo['orderedForWithdrawal'] = (new \yii\mongodb\Query())
            ->select(['amount'])
            ->from('transactions')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'forWhat'=> [
                    '$regex' => 'Withdrawal',
                ],
                'reduced' => ['$ne'=>false],
                'confirmed' => 0
            ])
            ->sum('amount');



        $model = (new \yii\mongodb\Query())
            ->select(['amount','dateCreate', 'forWhat', 'idTo'])
            ->from('transactions')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->andWhere([
                '$or' =>[
                    [
                        'forWhat' => [
                            '$regex' => 'Purchase for'
                        ]
                    ],
                    [
                        'forWhat' => [
                            '$regex' => 'Closing steps'
                        ]
                    ],
                    [
                        'forWhat' => [
                            '$regex' => 'Mentor bonus'
                        ]
                    ],
                    [
                        'forWhat' => [
                            '$regex' => 'For stocks'
                        ]
                    ]
                ]
            ])
            ->all();


        if(!empty($model)) {
            foreach ($model as $item) {
                $dateCreate = $item['dateCreate']->toDateTime()->format('Y-m');

                if (empty($statisticInfo['feesCommissionMonth'][$dateCreate])) {
                    $statisticInfo['feesCommissionMonth'][$dateCreate] = 0;
                }
                $statisticInfo['feesCommissionMonth'][$dateCreate] += $item['amount'];
                $statisticInfo['feesCommission'] += $item['amount'];

                // собираем информацию для формирования максимального чека
                if(empty($statisticInfo['tradeTurnover']['forUser'][(string)$item['idTo']])){
                    $statisticInfo['tradeTurnover']['forUser'][(string)$item['idTo']] = 0;
                }
                $statisticInfo['tradeTurnover']['forUser'][(string)$item['idTo']] += $item['amount'];



            }

        }


        // отмены транзакций
        $model = (new \yii\mongodb\Query())
            ->select(['amount','dateCreate', 'forWhat', 'idTo','idFrom'])
            ->from('transactions')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'idTo' => [
                    '$ne' => new ObjectID('000000000000000000000001')
                ],
                'type'=>1,
            ])
            ->andWhere([
                '$or' =>[
                    [
                        'forWhat' => [
                            '$regex' => 'Cancellation purchase for a partner'
                        ]
                    ]
                ]
            ])
            ->all();


        if(!empty($model)) {
            foreach ($model as $item) {
                $dateCreate = $item['dateCreate']->toDateTime()->format('Y-m');

                if (empty($statisticInfo['feesCommissionMonth'][$dateCreate])) {
                    $statisticInfo['feesCommissionMonth'][$dateCreate] = 0;
                }
                $statisticInfo['feesCommissionMonth'][$dateCreate] -= $item['amount'];
                $statisticInfo['feesCommission'] -= $item['amount'];

                // собираем информацию для формирования максимального чека
                if(empty($statisticInfo['tradeTurnover']['forUser'][(string)$item['idFrom']])){
                    $statisticInfo['tradeTurnover']['forUser'][(string)$item['idFrom']] = 0;
                }
                $statisticInfo['tradeTurnover']['forUser'][(string)$item['idFrom']] -= $item['amount'];

            }
        }

        if(!empty($statisticInfo['tradeTurnover']['forUser'])){
            arsort($statisticInfo['tradeTurnover']['forUser']);
            $statisticInfo['tradeTurnover']['forUser'] = array_slice($statisticInfo['tradeTurnover']['forUser'],0,10);

        }


        $model = (new \yii\mongodb\Query())
            ->select(['amount','dateCreate'])
            ->from('transactions')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'forWhat'=> [
                    '$regex' => 'Withdrawal',
                ],
                'reduced' => ['$ne'=>false],
                'confirmed'=>1
            ])
            ->all();
        if(!empty($model)) {
            foreach ($model as $item) {
                $dateCreate = $item['dateCreate']->toDateTime()->format('Y-m');

                if (empty($statisticInfo['issuedCommissionMonth'][$dateCreate])) {
                    $statisticInfo['issuedCommissionMonth'][$dateCreate] = 0;
                }
                $statisticInfo['issuedCommissionMonth'][$dateCreate] += $item['amount'];
                $statisticInfo['issuedCommission'] += $item['amount'];
            }
        }

        $i = 0;
        for ($iDate=$statisticInfo['request']['from'];$iDate<=$statisticInfo['request']['to'];$iDate=date('Y-m',strtotime('+1 month', strtotime($iDate)))) {
            if(empty($statisticInfo['newRegistrationForMonth'][$iDate])){
                $statisticInfo['newRegistrationForMonth'][$iDate] = [$i,0];
            }else{
                $statisticInfo['newRegistrationForMonth'][$iDate] = [$i,$statisticInfo['newRegistrationForMonth'][$iDate]];
            }

            if(empty($statisticInfo['ofThemPaidForMonth'][$iDate])){
                $statisticInfo['ofThemPaidForMonth'][$iDate] = [$i,0];
            }else{
                $statisticInfo['ofThemPaidForMonth'][$iDate] = [$i,$statisticInfo['ofThemPaidForMonth'][$iDate]];
            }

            if(empty($statisticInfo['generalReceiptMoneyMonth'][$iDate])){
                $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i,0];
            }else{
                $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i,round($statisticInfo['generalReceiptMoneyMonth'][$iDate])];
            }

            if(empty($statisticInfo['issuedCommissionMonth'][$iDate])){
                $statisticInfo['issuedCommissionMonth'][$iDate] = [$i,0];
            }else{
                $statisticInfo['issuedCommissionMonth'][$iDate] = [$i,round($statisticInfo['issuedCommissionMonth'][$iDate])];
            }

            $statisticInfo['dateInterval'][] = [$i,$iDate];

            $i++;
        }

        ksort($statisticInfo['newRegistrationForMonth']);
        ksort($statisticInfo['ofThemPaidForMonth']);
        ksort($statisticInfo['generalReceiptMoneyMonth']);
        ksort($statisticInfo['issuedCommissionMonth']);

//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($statisticInfo);
//        echo "</xmp>";
//        die();

        return $statisticInfo;
    }

}