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

        $debag = [];

        $listProducts = Products::find()->all();
        $listProductsTitle = ArrayInfoHelper::getArrayKeyValue($listProducts,'product','productName');
        $listProductsType = ArrayInfoHelper::getArrayKeyValue($listProducts,'product','type');

        $typeProject = [
            '1'     => 'VipVip',
            '2'     => 'BusinessSupport',
            '3'     => 'VipVip',
            '4'     => 'BalanceTopUp',
            '5'     => 'Wellness',
            '7'     => 'Wellness',
            '8'     => 'Wellness',
            '9'     => 'VipCoin',
            '10'    => 'VipCoin',
        ];

        $liveMoney = [
            '05'  =>  5774.62,
            '06'  =>  2000,
            '07'  =>  29423.08,
            '08'  =>  4957.63,
            '09'  =>  69955.77,
            '10'  =>  85032.24,
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
            //удаленные пользователи
            'removeUsers'                   => 0,

            // общий приход
            'generalReceiptMoney'           => 0,
            'generalReceiptMoneyMonth'      => [],
            //Приход по программе vipcoin
            'generalReceiptMoney_VipCoin'        => 0,
            //Приход по программе Wellness
            'generalReceiptMoney_Wellness'     => 0,
            //Приход по программе VipVip
            'generalReceiptMoney_VipVip'   => 0,

            'generalReceiptMoney_BalanceTopUp'   => 0,
            'generalReceiptMoney_BusinessSupport'   => 0,

            // приход за ваучеры
            'receiptVoucher'                => 0,
            //Приход ваучерами по программе vipcoin
            'receiptVoucher_VipCoin'        => 0,
            //Приход ваучерами по программе Wellness
            'receiptVoucher_Wellness'       => 0,
            //Приход ваучерами по программе VipVip
            'receiptVoucher_VipVip'         => 0,

            'receiptVoucher_BalanceTopUp'         => 0,
            'receiptVoucher_BusinessSupport'         => 0,

            // приход за деньги
            'receiptMoney'                  => 0,
            //Приход за деньги по программе vipcoin
            'receiptMoney_VipCoin'          => 0,
            //Приход за деньги по программе Wellness
            'receiptMoney_Wellness'         => 0,
            //Приход за деньги по программе VipVip
            'receiptMoney_VipVip'           => 0,

            'receiptMoney_BalanceTopUp'           => 0,
            'receiptMoney_BusinessSupport'           => 0,


            // отмена за ваучеры
            'cancellationVoucher'                => 0,
            //отмена ваучерами по программе vipcoin
            'cancellationVoucher_VipCoin'        => 0,
            //отмена ваучерами по программе Wellness
            'cancellationVoucher_Wellness'       => 0,
            //отмена ваучерами по программе VipVip
            'cancellationVoucher_VipVip'         => 0,

            'cancellationVoucher_BalanceTopUp'         => 0,
            'cancellationVoucher_BusinessSupport'         => 0,

            // на лицевых считах
            'onPersonalAccounts'            => 0,
            //пополнение
            'refill'                        => 0,

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


        $statisticInfo['removeUsers'] = (new \yii\mongodb\Query())
             ->select(['deletedUsers.number'])
             ->from('statistics')
             ->where([
                 'deletedUsers.date' => [
                     '$gte' => new UTCDatetime($queryDateFrom),
                     '$lte' => new UTCDateTime($queryDateTo)
                 ]
             ])
             ->sum('deletedUsers.number');

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

                    //debag
//                    if($typeProject[$listProductsType[$item['product']]]=='VipCoin'){
//                        if(empty($debag['generalReceiptMoney_VipCoin_pack'][$item['product']])){
//                            $debag['generalReceiptMoney_VipCoin_pack'][$item['product']] = 0;
//                        }
//                        $debag['generalReceiptMoney_VipCoin_pack'][$item['product']]++;
//
//                        if($item['product'] == '42'){
//                            $debag['pack_42_date_buy'][] = $item['dateCreate']->toDateTime()->format('Y-m-d H:i:s');
//                        }
//                    }

                }

                // собираем информацию по товарам для товарооборота
                if (empty($statisticInfo['tradeTurnover']['listProduct'][$item['product']])) {
                    $statisticInfo['tradeTurnover']['listProduct'][$item['product']] = [
                        'title' => $listProductsTitle[$item['product']],
                        'price' => 0,
                        'count' => 0,
                        'amount'=> 0
                    ];
                }
                $statisticInfo['tradeTurnover']['listProduct'][$item['product']]['price'] = $item['price'];
                $statisticInfo['tradeTurnover']['listProduct'][$item['product']]['count']++;
                $statisticInfo['tradeTurnover']['listProduct'][$item['product']]['amount'] += $item['price'];

            }
        }
        unset($model);


        $arrayQuery=[];
        foreach ($listProducts as $listProduct) {
            if(!empty($typeProject[$listProduct->type])){
                $arrayQuery[$typeProject[$listProduct->type]][] = 'Creating pin for product '.$listProduct->productName;
            }
        }

        //debag
//        foreach ($arrayQuery['VipCoin'] as $k=>$item){
//            $info = Transaction::find()
//                ->select(['amount','dateCreate','idFrom'])
//                ->where([
//                    'dateCreate' => [
//                        '$gte' => new UTCDatetime($queryDateFrom),
//                        '$lte' => new UTCDateTime($queryDateTo)
//                    ]
//                ])
//                ->andWhere(['IN','forWhat',$item])
//                ->all();
//
//
//            $debag['create_pin_vipcoin'][] = $item.'-'.count($info);
//
//            foreach($info as $itemI){
//
//                $infoUser = Users::findOne(['_id'=>$itemI['idFrom']]);
//
//                $debag['date_create_pin_vipcoin'][$item][$itemI['dateCreate']->toDateTime()->format('Y-m-d H:i:s')] = $infoUser->username;
//            }
//        }



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



        //debag
//        $debag['pinsSum']=0;
//        $model = Pins::find()->where([
//            'dateCreate' => [
//                '$gte' => new UTCDatetime($queryDateFrom),
//                '$lte' => new UTCDateTime($queryDateTo)
//            ],
//            'userId'=>['$ne' =>new ObjectId('573a0d76965dd0fb16f60bfe')],
//            //'isDelete' => false
//        ])->all();
//        if(!empty($model)){
//            foreach ($model as $item) {
//                $infoPin = api\Pin::checkPin($item->pin);
//
//                if(!empty($infoPin->product) && in_array($infoPin->product,[40,41,42,43])){
//
//                    if(empty($debag['pins'][$infoPin->product])){
//                        $debag['pins'][$infoPin->product] = 0;
//                    }
//
//
//
//                    $debag['pins'][$infoPin->product]++;
//
//                    $debag['pinsSum']+= $infoPin->price;
//                }
//
//            }
//        }



        // отмены по pin
        $model = Pins::find()->where([
            'dateCreate' => [
                '$gte' => new UTCDatetime($queryDateFrom),
                '$lte' => new UTCDateTime($queryDateTo)
            ],
            '$or' => [
                ['isDelete' => true],
                [
                    //'used'=>true,
                    'userId'=>new ObjectId('573a0d76965dd0fb16f60bfe')
                ]
            ]
        ])->all();

        if(!empty($model)){
            foreach ($model as $item) {
                $infoPin = api\Pin::checkPin($item->pin);

                if(!empty($infoPin->type) && !empty($typeProject[$infoPin->type])){
                    $statisticInfo['cancellationVoucher_'.$typeProject[$infoPin->type]] += $infoPin->price;
                    $statisticInfo['cancellationVoucher'] += $infoPin->price;
                }

            }
        }

        // проверка что б приход деньгами не был отрицательным,
        // возникает это при условии что пин коды
        // не активированы но сформированны
//        $listTypeProject = ['VipVip','Wellness','VipCoin'];
//        foreach ($listTypeProject as $item){
//            if($statisticInfo['generalReceiptMoney_'.$item] < ($statisticInfo['receiptVoucher_'.$item]+$statisticInfo['cancellationVoucher_'.$item])){
//                $statisticInfo['receiptVoucher_'.$item] = $statisticInfo['generalReceiptMoney_'.$item];
//                $statisticInfo['cancellationVoucher_'.$item] = 0;
//            }
//        }
        
        // приход живыми деньгами
        $infoBuyForMoney = $this->getProductBuyForMoney($statisticInfo['request']['from'].'-01',$statisticInfo['request']['to'].'-'.$countDay);
        if(!empty($infoBuyForMoney)){
            foreach ($infoBuyForMoney as $k=>$item){
                $statisticInfo['receiptMoney'] += $item;
                $type = Products::findOne(['idInMarket'=>$k]);
                if(!empty($type->type) && !empty($typeProject[$type->type])){
                    $statisticInfo['receiptMoney_'.$typeProject[$type->type]] += $item;

                    //debag
//                    if($typeProject[$type->type] == 'VipCoin'){
//                        $debag['live_money'][] = $k;
//                    }
                }
            }
        }
        $i = 0;
        for ($iDate=$statisticInfo['request']['from'];$iDate<=$statisticInfo['request']['to'];$iDate=date('Y-m',strtotime('+1 month', strtotime($iDate)))) {
            $month = date('m',strtotime($iDate));
            if(!empty($liveMoney[$month])){
                $statisticInfo['receiptMoney'] += $liveMoney[$month];
                $statisticInfo['receiptMoney_BalanceTopUp'] += $liveMoney[$month];
            }

            $i++;
        }

//
//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($debag);
//        echo "</xmp>";
//        die();

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
                    ],
                    [
                        'forWhat' => [
                            '$regex' => 'Executive bonus'
                        ]
                    ],
                    [
                        'forWhat' => [
                            '$regex' => 'Bonus per the achievement'
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
            $statisticInfo['tradeTurnover']['forUser'] = array_slice($statisticInfo['tradeTurnover']['forUser'],0,20);

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



        // infoBonus
        $listBonus = ['worldBonus','autoBonus','propertyBonus'];
        foreach ($listBonus as $itemBonus) {
            $statisticInfo['bonus'][$itemBonus] = (new \yii\mongodb\Query())
                ->select(['statistics.'.$itemBonus])
                ->from('users')
                ->where([
                    'username' => [
                        '$nin' => ['main','datest1','danilchenkoalex']
                    ]
                ])
                ->sum('statistics.'.$itemBonus);
        }

        $statisticInfo['bonus']['executiveBonus'] = Transaction::find()
            ->select(['amount'])
            ->where([
                'forWhat' => [
                    '$regex' => 'Executive bonus'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');

        $statisticInfo['bonus']['careerBonus'] = Transaction::find()
            ->select(['amount'])
            ->where([
                'forWhat' => [
                    '$regex' => 'Bonus per the achievement'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');

        $statisticInfo['bonus']['mentorBonus'] = Transaction::find()
            ->select(['amount'])
            ->where([
                'forWhat' => [
                    '$regex' => 'Mentor bonus'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');

        $statisticInfo['bonus']['equityBonus'] = Transaction::find()
            ->select(['amount'])
            ->where([
                'forWhat' => [
                    '$regex' => 'For stocks'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');

        $statisticInfo['bonus']['teamBonus'] = Transaction::find()
            ->select(['amount'])
            ->where([
                'forWhat' => [
                    '$regex' => 'Closing steps'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');

        $connectingBonusAdd = Transaction::find()
            ->select(['amount'])
            ->where([
                'forWhat' => [
                    '$regex' => 'Purchase for a partner'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');

        $connectingBonusCancellation = Transaction::find()
            ->select(['amount'])
            ->where([
                'forWhat' => [
                    '$regex' => 'Cancellation purchase for a partner'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('000000000000000000000001')
                ],
                'type'=>1,
            ])
            ->sum('amount');
        $statisticInfo['bonus']['connectingBonus'] = $connectingBonusAdd - $connectingBonusCancellation;


//        $entering_money = Transaction::find()
//            ->select(['amount','idTo','dateCreate'])
//            ->where([
//                'forWhat' => [
//                    '$regex' => 'Entering the money'
//                ],
//                'idTo' => [
//                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
//                ],
//                'type'=>1,
//            ])->orderBy(['amount'=>SORT_DESC])->all();
//        $xz='';
//        foreach ($entering_money as $k=>$item) {
//            $xz .= '<tr><td>'.$item->dateCreate->toDateTime()->format('Y-m-d H:i:s').'<td>'.Users::findOne(['_id'=>$item->idTo])->username. '<td>' . $item->amount;
//            if($k==50)break;
//        }
//
//        $xz = '<table>'.$xz.'</table>';
//        echo $xz;
//        die();

        $entering_money = Transaction::find()
            ->select(['amount'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'forWhat' => [
                    '$regex' => 'Entering the money'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');

        $entering_money_caneletion = Transaction::find()
            ->select(['amount'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'forWhat' => [
                    '$regex' => 'Entering the money \\(Rollback'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1
            ])
            ->sum('amount');

        $statisticInfo['refill'] = $entering_money - $entering_money_caneletion;



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
            
            if(empty($statisticInfo['feesCommissionMonth'][$iDate])){
                $statisticInfo['feesCommissionMonth'][$iDate] = [$i,0];
            }else{
                $statisticInfo['feesCommissionMonth'][$iDate] = [$i,round($statisticInfo['feesCommissionMonth'][$iDate])];
            }

            $statisticInfo['dateInterval'][] = [$i,$iDate];

            $i++;
        }

        ksort($statisticInfo['newRegistrationForMonth']);
        ksort($statisticInfo['ofThemPaidForMonth']);
        ksort($statisticInfo['generalReceiptMoneyMonth']);
        ksort($statisticInfo['issuedCommissionMonth']);
        ksort($statisticInfo['feesCommissionMonth']);

//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($statisticInfo);
//        echo "</xmp>";
//        die();

        return $statisticInfo;
    }

    protected function getProductBuyForMoney($date_from,$date_to)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL,"http://vipsite.biz/admin/statistic.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(['date_from' => $date_from,'date_to'=>$date_to]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return json_decode($server_output,TRUE);
    }
    
}