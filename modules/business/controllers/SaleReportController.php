<?php

namespace app\modules\business\controllers;

use app\components\ArrayInfoHelper;
use app\components\GoodException;
use app\components\THelper;
use app\controllers\BaseController;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Repayment;
use app\models\RepaymentAmounts;
use app\models\Sales;
use app\models\PreUp;
use app\models\Users;
use app\models\SendingWaitingParcel;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use yii\base\Theme;
use yii\helpers\ArrayHelper;
use app\models\Pins;
use app\models\Transaction;
use app\models\api;
use app\models\RecoveryForRepaymentAmounts;


class SaleReportController extends BaseController
{


    public function actionMainStat()
    {
        //return 'MainStat';
        $statisticInfo = [];
        if(Users::checkRule('show_statistic','sidebar_main_stat') === true){
            $statisticInfo = $this->getStatisticInfo();
        }

        return $this->render('report-main-stat', [
            'user' => $this->user,
            'statisticInfo' => $statisticInfo,
        ]);
    }
    public function calcMoneyBalanceTopUp($queryDateFrom,$queryDateTo)
    {
        $res='?';
        $z = Transaction::find()
            ->select(['amount'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'forWhat' => [
                    '$regex' => 'Balance top'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');
        $h = Transaction::find()
            ->select(['amount'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'forWhat' => [
                    '$regex' => 'Денежный перевод'
                ],
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');
//        $g = (new \yii\mongodb\Query())
//                ->select(['amount'])
//                ->from('sales')
//                ->where([
//
//                ])
//                ->sum('amount');
        $g =Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere([
                'product' =>9001
            ])
            ->andWhere(['whenceSale'=>[
                '$regex' => 'adminka;kind'
            ]])
            ->sum('price');
        $o = (new \yii\mongodb\Query())
            ->select(['amount'])
            ->from('transactions')
            ->where([
                'forWhat' => 'Withdrawal',
                'reduced' => true,
                'confirmed' => 0,
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
            ])
            ->sum('amount');
        $b =Transaction::find()
            ->select(['amount'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'forWhat' => 'Purchase for a partner',
                'idTo' => [
                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                ],
                'type'=>1,
            ])
            ->sum('amount');
        $res =$z-$b-$o;
        return $res;
    }

    protected function getStatisticInfo(){

        $debag = [];

        $listProducts = Products::find()->where(['product'=>['$gt'=>999]])->all();
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
            '04'  =>  26765.11,
            '05'  =>  32410.16,
            '06'  =>  31633.04,
            '07'  =>  45791.55,
            '08'  =>  29052.08,
            '09'  =>  79577.72,
            '10'  =>  131664.98,
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
            'generalReceiptMoneyDetails'    => [],
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
            'receiptMoneyDetails'           => [],
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
            'refill_vipvip'                 => 0,
            'refill_wellness'               => 0,

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
            $date = strtotime('-1 month', strtotime($statisticInfo['request']['to']));
            $statisticInfo['request']['from'] = date('Y-m', $date);//--old definition
            //$statisticInfo['request']['from'] = date('Y-m', strtotime('2018-09'));//--start new accounting firm period
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
            ksort($statisticInfo['newRegistrationForMonth']);
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
                //'productType'=>['$nin'=>[0,4]],
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

                //if (!empty($typeProject[$listProductsType[$item['product']]])) {

                if (array_key_exists($item['product'],$listProductsType)) {
                    try {
                        $statisticInfo['generalReceiptMoney_' . $typeProject[$listProductsType[$item['product']]]] += $item['price'];
                    } catch (\Exception $e) {
                        $statisticInfo['generalReceiptMoney_VipCoin' ]= 0;
                        $statisticInfo['generalReceiptMoney_Wellness' ]= 0;
                        $statisticInfo['generalReceiptMoney_VipVip' ]= 0;

                    }



                }
                try {
                    // собираем информацию по товарам для товарооборота
                    if (empty($statisticInfo['tradeTurnover']['listProduct'][$item['product']])) {
                        $statisticInfo['tradeTurnover']['listProduct'][$item['product']] = [
                            'title' => $listProductsTitle[$item['product']],
                            'price' => 0,
                            'count' => 0,
                            'amount'=> 0
                        ];
                    }
                } catch (\Exception $e) { //---ERROR in $listProductsTitle
                    $statisticInfo['tradeTurnover']['listProduct'][$item['product']] = [
                        'title' => '??',
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
        //--b:vipcoin--
        $sum_vipcoin = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [1,9,10]
                ]
            ])
            ->andWhere([
                'productData.categories' => [
                    '$elemMatch' => ['$eq'=>'5b4c46469ed4eb002a683891']
                ]
            ])
            ->sum('price');
        $statisticInfo['generalReceiptMoneyDetails']['all']=$statisticInfo['generalReceiptMoney'];
        $statisticInfo['generalReceiptMoneyDetails']['vipcoin']=$sum_vipcoin;
        $statisticInfo['generalReceiptMoney']+=$sum_vipcoin;
        //--e:vipcoin--

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
            '$or' => [
                ['isDelete' => true],
                [
                    //'used'=>true,
                    'userId'=>new ObjectId('573a0d76965dd0fb16f60bfe')
                ]
            ]
        ])->all();


        /**
         * @todo Очень много запросов к апи, нужно переделать
         */
        /*        if(!empty($model)){

                    foreach ($model as $item) {
                        $infoPin = api\Pin::checkPin($item->pin);

                        if(!empty($infoPin->type) && !empty($typeProject[$infoPin->type])){
                            $statisticInfo['cancellationVoucher_'.$typeProject[$infoPin->type]] += $infoPin->price;
                            $statisticInfo['cancellationVoucher'] += $infoPin->price;
                        }

                    }
                }*/

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

        // приход живыми деньгами -отключен
        //$infoBuyForMoney = $this->getProductBuyForMoney($statisticInfo['request']['from'].'-01',$statisticInfo['request']['to'].'-'.$countDay);
        $infoBuyForMoney = 0;
        if(!empty($infoBuyForMoney)){
            foreach ($infoBuyForMoney as $k=>$item){
                $statisticInfo['receiptMoney'] += $item;
                $type = Products::findOne(['idInMarket'=>$k]);
                if(!empty($type->type) && !empty($typeProject[$type->type])){
                    $statisticInfo['receiptMoney_'.$typeProject[$type->type]] += $item;


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

        //----b:new calculation for income money---
        $loanRep = 0;
        $income  = 0;
        $loanRep = (new \yii\mongodb\Query())
            ->select(['amount'])
            ->from('loan_repayment')
            ->where([
                'date_create' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->sum('amount');

        $income_items = (new \yii\mongodb\Query())
            ->select(['amount','paymentType'])
            ->from('orders')
            ->where([
                'paymentDate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'paymentStatus' =>'paid',
                'status'        =>'confirmed',
                'paymentType'   =>['$ne'=>'balance']
            ])
            ->all();

        $statisticInfo['receiptMoneyDetails']['softpay'] = 0;
        $statisticInfo['receiptMoneyDetails']['paysera'] = 0;
        $statisticInfo['receiptMoneyDetails']['advcash'] = 0;
        $statisticInfo['receiptMoneyDetails']['invoice'] = 0;
        $statisticInfo['receiptMoneyDetails']['pb'] = 0;
        foreach ($income_items as $m_item) {
            $income+=$m_item['amount'];
            if (preg_match('/softpay/',$m_item['paymentType'])) {
                $statisticInfo['receiptMoneyDetails']['softpay']+=$m_item['amount'];
            }
            if (preg_match('/paysera/',$m_item['paymentType'])) {
                $statisticInfo['receiptMoneyDetails']['paysera']+=$m_item['amount'];
            }
            if (preg_match('/advcash/',$m_item['paymentType'])) {
                $statisticInfo['receiptMoneyDetails']['advcash']+=$m_item['amount'];
            }
//            if (preg_match('/invoice/',$m_item['paymentType'])) {
//                $statisticInfo['receiptMoneyDetails']['invoice']+=$m_item['amount'];
//            }
            if (preg_match('/pb/',$m_item['paymentType'])) {
                $statisticInfo['receiptMoneyDetails']['pb']+=$m_item['amount'];
            }
        }
        $loan=(new \yii\mongodb\Query())
            ->select(['amount'])
            ->from('pre_up')
            ->where([
                'created_at' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'kind'    =>'loan',
                'status'  =>'done'
            ])
            ->sum('amount');
        $statisticInfo['receiptMoney'] = $loanRep + $income;
        $statisticInfo['receiptMoneyDetails']['income'] = $income;
        $statisticInfo['receiptMoneyDetails']['loan'] = $loan;
        $statisticInfo['receiptMoneyDetails']['reloan'] = $loanRep;
        //---b:money from admin-----
        $statisticInfo['receiptMoneyDetails']['bank_a'] = 0;
        $statisticInfo['receiptMoneyDetails']['cash_a'] = 0;
        $statisticInfo['receiptMoneyDetails']['paysera_a'] = 0;
        $statisticInfo['receiptMoneyDetails']['advcash_a'] = 0;
        $statisticInfo['receiptMoneyDetails']['perevod_a'] = 0;
        $statisticInfo['receiptMoneyDetails']['advaction_a'] = 0;
        $statisticInfo['receiptMoneyDetails']['other_a']     = 0;
        $adm_items =  (new \yii\mongodb\Query())
            ->select(['dateCreate','price','whenceSale'])
            ->from('sales')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'type'=>[
                    '$ne'=>-1
                ],
                'product'=>9001,
                'username' =>[
                    '$ne'=>'main'
                ]
            ])
            ->all();
        foreach ($adm_items as $item ) {
            if (isset($item['whenceSale'])) {
                if (preg_match('/kind:bank/',$item['whenceSale'])) {
                    $statisticInfo['receiptMoneyDetails']['bank_a']+=$item['price'];
                }
                if (preg_match('/kind:cash/',$item['whenceSale'])) {
                    $statisticInfo['receiptMoneyDetails']['cash_a']+=$item['price'];
                }

                if (preg_match('/kind:paysera/',$item['whenceSale'])) {
                    $statisticInfo['receiptMoneyDetails']['paysera_a']+=$item['price'];
                }
                if (preg_match('/kind:advcash/',$item['whenceSale'])) {
                    $statisticInfo['receiptMoneyDetails']['advcash_a']+=$item['price'];
                }
                if (preg_match('/kind:perevod/',$item['whenceSale'])) {
                    $statisticInfo['receiptMoneyDetails']['perevod_a']+=$item['price'];
                }
                if (preg_match('/kind:advaction/',$item['whenceSale'])) {
                    $statisticInfo['receiptMoneyDetails']['advaction_a']+=$item['price'];
                }
                if (preg_match('/kind:other/',$item['whenceSale'])) {
                    $statisticInfo['receiptMoneyDetails']['other_a']+=$item['price'];
                }

            }
        }
        //---e:money from admin-----
        //----e:new calculation---
        //--b:calc right sum--
        $users = api\User::spilover('573b8a83507cba1c091c1b51', 200);//--test user
        $r_sum=0;
        foreach ($users as $user) {
            $r_sum+=$user->moneys;
        }
        //--e:calc right sum--

        $statisticInfo['onPersonalAccounts'] = (new \yii\mongodb\Query())
            ->select(['moneys'])
            ->from('users')
            ->where(['username' => ['$ne'=>'main'],
                    'isDelete' =>['$ne' =>[null,true]]
                    //'isDelete' =>['$ne' =>null]
                ]

            )
            ->sum('moneys');

        $statisticInfo['onPersonalAccounts']= abs($statisticInfo['onPersonalAccounts'])-$r_sum;

        $statisticInfo['orderedForWithdrawal'] = (new \yii\mongodb\Query())
            ->select(['amount'])
            ->from('transactions')
            ->where([
                'forWhat' => 'Withdrawal',
                'reduced' => true,
                'confirmed' => 0,
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
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
                'forWhat'=>['$regex'=>'Purchase for|Closing steps|Mentor bonus|For stocks|Executive bonus|Bonus per the achievement|Auto bonus|World bonus']
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
                'type'=>[1],
            ])
            //->andWhere(['forWhat' =>'Cancellation purchase for a partner'])
            ->andWhere(['forWhat' => ['$regex'=>'Cancellation purchase for a partner']])
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

        } else {
            if(empty($statisticInfo['tradeTurnover']['forUser'])) {
                for ($i = 0; $i <= 3; $i++) {
                    //$statisticInfo['tradeTurnover']['forUser'][$i]['sum'] = 0;
                    //$statisticInfo['tradeTurnover']['forUser'][$i]['fio'] = '??';
                }
            }
        }
        if(!empty($statisticInfo['tradeTurnover']['forUser'])){
            arsort($statisticInfo['tradeTurnover']['forUser']);
            $statisticInfo['tradeTurnover']['forUser'] = array_slice($statisticInfo['tradeTurnover']['forUser'],0,20);

        }
        //---b:best checks---
        $statisticInfo['tradeTurnover']['bestChecksUser']=[];

        if(!empty($statisticInfo['tradeTurnover']['forUser'])) {
            foreach ($statisticInfo['tradeTurnover']['forUser'] as $k => $item) {
                $infoUser = Users::findOne(['_id' => new \MongoDB\BSON\ObjectID($k)]);
                $statisticInfo['tradeTurnover']['bestChecksUser'][] = [
                    'sum' => $item,
                    'fio' => (!empty($infoUser->secondName) ? $infoUser->secondName : '') . ' ' . (!empty($infoUser->firstName) ? $infoUser->firstName : '')
                ];
            }
        }
        //---e:best checks---



        // выдача комиссионных
        $model = (new \yii\mongodb\Query())
            ->select(['amount','dateConfirm'])
            ->from('transactions')
            ->where([
                'forWhat' => 'Withdrawal',
                'reduced' => true,
                'confirmed' => 1,
                'dateConfirm' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->all();
        if(!empty($model)) {
            foreach ($model as $item) {
                $dateConfirm = $item['dateConfirm']->toDateTime()->format('Y-m');

                if (empty($statisticInfo['issuedCommissionMonth'][$dateConfirm])) {
                    $statisticInfo['issuedCommissionMonth'][$dateConfirm] = 0;
                }
                $statisticInfo['issuedCommissionMonth'][$dateConfirm] += $item['amount'];
                $statisticInfo['issuedCommission'] += $item['amount'];
            }
        }




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
//                'forWhat' => [
//                    '$regex' => 'Entering the money'
//                ],
                'forWhat' =>'Entering the money',
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
//                'forWhat' => [
//                    '$regex' => 'Entering the money \\(Rollback'
//                ],
                'forWhat' =>'Entering the money \\(Rollback',
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


        //--b:salesTurnover
        $statisticInfo['salesTurnover'] = (new \yii\mongodb\Query())
            ->select(['dateCreate','price'])
            ->from('sales')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'type'=>[
                    '$ne'=>-1
                ],
                'product'=>['$ne'=>'0'],
                'username' =>[
                    '$ne'=>'main'
                ]
            ])
            ->sum('price');
        //--e:salesTurnover
        //--b:p_pack--
        $p_set_arr     = Products::productIDWithSet();


        $statisticInfo['salesTurnoverDetails']['packs']  = Sales::find()
            ->select(['price'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->andWhere(['in','product',$p_set_arr])
            ->andWhere([
                'type' => ['$ne' => -1]
            ])
            ->sum('price');
        $statisticInfo['salesTurnoverDetails']['rest']=0;
        //--e:p_pack--
        //--b:salesPoints
        $statisticInfo['salesPoints'] = (new \yii\mongodb\Query())
            ->select(['productData.bonus.point.vip_investor_3'])
            ->from('sales')
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'type'=>[
                    '$ne'=>-1
                ],
                'product'=>['$ne'=>'0'],
                'username' =>[
                    '$ne'=>'main'
                ]
            ])
            ->sum('productData.bonus.point.vip_investor_3');
        //--e:salesPoints
        //---refill_vipvip
//        $statisticInfo['refill_vipvip'] = Transaction::find()
//            ->select(['amount'])
//            ->where([
//                'dateCreate' => [
//                    '$gte' => new UTCDatetime($queryDateFrom),
//                    '$lte' => new UTCDateTime($queryDateTo)
//                ],
//                'forWhat' => [
//                    '$regex' => 'Пополнение баланса VIPVIP'
//                ],
////                'idTo' => [
////                    '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
////                ],
//                'type'=>1,
//            ])
//            ->sum('amount');
        $statisticInfo['refill_vipvip'] = Sales::find()
            ->select(['amount'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'productName' => [
                    '$regex' => 'Пополнение баланса VIPVIP|пополнение баланса программы VIPVIP'
                ],
                //'product'=>[5001,5002,7003],
                'type'=>1,
            ])
            ->sum('price');
        //--refill_wellness --Пополнение баланса WebWellness|пополнение баланса программы WebWellness|WebWellness активность бизнес-места
        $statisticInfo['refill_wellness'] = Sales::find()
            ->select(['amount'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ],
                'product'=>[6001,6002,6003,6005,7001],
                'type'=>1,
            ])
            ->sum('price');
        return $statisticInfo;
    }

    public function actionStatDetails() {
        $result=['success'=>false,'details_html'=>''];
        try {
            $block_name =Yii::$app->request->post('block_name');
            $view_name='';
            $listProducts = Products::find()->where(['product'=>['$gt'=>999]])->all();
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
            $typeCat = [
                'WebWellness'      =>'5b4c46149ed4eb000b34b232',
                'VipVip'           =>'5b4c46359ed4eb0009049f62',
                'VipCoin'          =>'5b4c46469ed4eb002a683891',
                'Business_support' =>'5b60b9e58d6b9e00050dee14',
                'Balance_top_up'   =>'5b60bf3e8d6b9e000904477d'
            ];
            $listProductsType = ArrayInfoHelper::getArrayKeyValue($listProducts,'product','type');
            $listProductsTitle = ArrayInfoHelper::getArrayKeyValue($listProducts,'product','productName');
            $statisticInfo=[];


            $statisticInfo['request']['from']=$d_from=Yii::$app->request->post('d_from');
            $statisticInfo['request']['to']  =$d_to=Yii::$app->request->post('d_to');

            $infoDateTo = explode("-",$statisticInfo['request']['to']);
            $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);
            $queryDateFrom = strtotime($statisticInfo['request']['from'].'-01 00:00:00') * 1000;
            $queryDateTo   = strtotime($statisticInfo['request']['to'].'-'.$countDay.' 23:59:59') * 1000;
            //--------------------------------B:PARTNERS--------------------------------------------------
            if ($block_name =='partners') {
                $view_name ='_partners';
                $statisticInfo=['newRegistrationForMonth'=>[],'newRegistration'=>0,'ofThemPaidForMonth'=>[]];
                $statisticInfo['request']['from']=Yii::$app->request->post('d_from');
                $statisticInfo['request']['to']  =Yii::$app->request->post('d_to');



                //$countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);
                $queryDateFrom = strtotime($statisticInfo['request']['from'].'-01 00:00:00') * 1000;
                $queryDateTo   = strtotime($statisticInfo['request']['to'].'-'.$countDay.' 23:59:59') * 1000;

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
                $i = 0;
                for ($iDate=$statisticInfo['request']['from'];$iDate<=$statisticInfo['request']['to'];$iDate=date('Y-m',strtotime('+1 month', strtotime($iDate)))) {
                    if(empty($statisticInfo['newRegistrationForMonth'][$iDate])){
                        $statisticInfo['newRegistrationForMonth'][$iDate] = [$i,0];
                    }else{
                        $statisticInfo['newRegistrationForMonth'][$iDate] = [$i,$statisticInfo['newRegistrationForMonth'][$iDate]];
                    }
                    $statisticInfo['dateInterval'][] = [$i,$iDate];
                    $i++;
                }
                ksort($statisticInfo['newRegistrationForMonth']);
                ksort($statisticInfo['ofThemPaidForMonth']);

            }
            //--------------------------------B:PROJECTS--------------------------------------------------
            if (($block_name =='projects') ||($block_name =='turnover-details')) {
                $view_name = '_projects';
                if ($block_name =='turnover-details') {
                    $view_name = '_turnover_projects';
                }
                $statisticInfo = [
                    // общий приход
                    'generalReceiptMoney' => 0,
                    'generalReceiptMoneyMonth' => [],
                    //Приход по программе vipcoin
                    'generalReceiptMoney_VipCoin' => 0,
                    //Приход по программе Wellness
                    'generalReceiptMoney_Wellness' => 0,
                    //Приход по программе VipVip
                    'generalReceiptMoney_VipVip' => 0,

                    'generalReceiptMoney_BalanceTopUp'    => 0,
                    'generalReceiptMoney_BusinessSupport' => 0,
                    //Приход за деньги по программе vipcoin
                    'receiptMoney_VipCoin'          => 0,
                    'receiptMoney_VipCoin_cnt'      => 0,
                    //Приход за деньги по программе Wellness
                    'receiptMoney_Wellness'         => 0,
                    'receiptMoney_Wellness_cnt'     => 0,
                    //Приход за деньги по программе VipVip
                    'receiptMoney_VipVip'           => 0,
                    'receiptMoney_VipVip_cnt'       => 0,

                    'receiptMoney_Projects'         => 0,

                    'receiptMoney_BalanceTopUp'       => 0,
                    'receiptMoney_BalanceTopUp_f'     => 0,
                    'receiptMoney_BalanceTopUp_cnt'     => 0,
                    'receiptMoney_BalanceTopUp_f_cnt'   => 0,
                    'receiptMoney_BusinessSupport'      => 0,
                    'receiptMoney_BusinessSupport_cnt'  => 0,
                    'receiptMoney_Composite'      => 0,
                    'receiptMoney_Composite_cnt'  => 0,
                    'receiptMoney_cat_empty'      => 0,
                    'receiptMoney_cat_empty_cnt'  => 0,
                    'receiptMoney_Product_old'    => 0,
                    'receiptMoney_Product_old_cnt'=> 0,
                    'receiptMoney_Product_old_str'=> '',
                ];
                //--Balance top-up(f)-----------------------------------------------------
                $statisticInfo['receiptMoney_BalanceTopUp_f'] = self::calcMoneyBalanceTopUp($queryDateFrom,$queryDateTo);
                //$p_set_arr     = Products::productIDWithSet();
                $model = (new \yii\mongodb\Query())
                    ->select(['dateCreate', 'price', 'product','productData.categories','productName', 'username', 'project'])
                    ->from('sales')
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'type' => [
                            '$ne' => -1
                        ],
                        //'productType' => ['$nin' => [0, 4]],
                        //'product' => ['$gt' => 999],
                        //'product' =>['$in' => $p_set_arr],
                        'username' => [
                            '$ne' => 'main'
                        ]
                    ])
                    ->all();

                if (!empty($model)) {
                    foreach ($model as $item) {
                        if ($item['product'] > 999) {

                            $dateCreate = $item['dateCreate']->toDateTime()->format('Y-m');

                            if (empty($statisticInfo['generalReceiptMoneyMonth'][$dateCreate])) {
                                $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] = 0;
                            }
                            $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] += $item['price'];
                            //$statisticInfo['generalReceiptMoney'] += $item['price'];

                            //if (!empty($typeProject[$listProductsType[$item['product']]])) {

                            //----------b:Composite-------
                            if (!empty($item['productData']['categories'])) {
                                if ((count($item['productData']['categories']) > 1)) {
                                    $statisticInfo['receiptMoney_Composite'] += $item['price'];
                                    $statisticInfo['receiptMoney_Composite_cnt'] += 1;
                                } else {//--by one cat
                                    //--WebWellness-----------------------------------------------
                                    if (in_array($typeCat['WebWellness'], $item['productData']['categories'])) {
                                        $statisticInfo['receiptMoney_Wellness'] += $item['price'];
                                        $statisticInfo['receiptMoney_Wellness_cnt'] += 1;//$item['price'];
                                    }
                                    //--VIPVIP-----------------------------------------------------
                                    if (in_array($typeCat['VipVip'], $item['productData']['categories'])) {
                                        $statisticInfo['receiptMoney_VipVip'] += $item['price'];
                                        $statisticInfo['receiptMoney_VipVip_cnt'] += 1;//$item['price'];
                                    }
                                    //--VIPCoin-----------------------------------------------------
                                    if (in_array($typeCat['VipCoin'], $item['productData']['categories'])) {
                                        $statisticInfo['receiptMoney_VipCoin'] += $item['price'];
                                        $statisticInfo['receiptMoney_VipCoin_cnt'] += 1;//$item['price'];
                                    }
                                    //--Business support----------------------------------------------------
                                    if (in_array($typeCat['Business_support'], $item['productData']['categories'])) {
                                        $statisticInfo['receiptMoney_BusinessSupport'] += $item['price'];
                                        $statisticInfo['receiptMoney_BusinessSupport_cnt'] += 1;//$item['price'];
                                    }
                                    //--Balance_top_up------------------------------------------------------
                                    if (in_array($typeCat['Balance_top_up'], $item['productData']['categories'])) {
                                        $statisticInfo['receiptMoney_BalanceTopUp'] += $item['price'];
                                        $statisticInfo['receiptMoney_BalanceTopUp_cnt'] += 1;//$item['price'];
                                    }
                                }
                            } else {
                                $statisticInfo['receiptMoney_cat_empty'] += $item['price'];
                                $statisticInfo['receiptMoney_cat_empty_cnt'] += 1;//$item['price'];
                            }
                            //----------e:Composite-------


                            //-----from old stat methods---------------------------------------------
                            if (array_key_exists($item['product'], $listProductsType)) {
                                try {
                                    $statisticInfo['generalReceiptMoney_' . $typeProject[$listProductsType[$item['product']]]] += $item['price'];
                                } catch (\Exception $e) {
                                    $statisticInfo['generalReceiptMoney_VipCoin'] = 0;
                                    $statisticInfo['generalReceiptMoney_Wellness'] = 0;
                                    $statisticInfo['generalReceiptMoney_VipVip'] = 0;

                                }


                            }
                        } else {//--product old
                            $statisticInfo['receiptMoney_Product_old'] += $item['price'];
                            $statisticInfo['receiptMoney_Product_old_cnt'] += 1;
                            $statisticInfo['receiptMoney_Product_old_str'] .= strval($item['product']).'-'.$item['username'].';';

                        }
                    }//--foreach--
                }//---model--
                //var_dump($statisticInfo);die();
                $statisticInfo['receiptMoney_Projects'] = $statisticInfo['receiptMoney_Wellness'] + $statisticInfo['receiptMoney_VipVip'] + $statisticInfo['receiptMoney_VipCoin'] + $statisticInfo['receiptMoney_BusinessSupport'] + $statisticInfo['receiptMoney_BalanceTopUp'];
            }
            //--------------------------------B:TURNOVER--------------------------------------------------
            if ($block_name =='turnover') {
                $view_name ='_turnover';
                $statisticInfo = [
                    'tradeTurnover'=>[]
                ];
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
                        try {
                            // собираем информацию по товарам для товарооборота
                            if (empty($statisticInfo['tradeTurnover']['listProduct'][$item['product']])) {
                                $statisticInfo['tradeTurnover']['listProduct'][$item['product']] = [
                                    'title' => $listProductsTitle[$item['product']],
                                    'price' => 0,
                                    'count' => 0,
                                    'amount'=> 0
                                ];
                            }
                        } catch (\Exception $e) { //---ERROR in $listProductsTitle
                            $statisticInfo['tradeTurnover']['listProduct'][$item['product']] = [
                                'title' => '??',
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
            }
            //--------------------------------B:COMMISSION-details----------------------------------------
            if ($block_name =='commission-details') {
                $view_name = '_commission_details';
                // infoBonus
                // return "month1=".$statisticInfo['request']['from'].' month2='.$statisticInfo['request']['to'];
                //$listBonus = ['worldBonus','propertyBonus'];
                $listBonus = ['propertyBonus'];
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
                $statisticInfo['bonus']['worldBonus'] = Transaction::find()
                    ->select(['amount'])
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'forWhat' => [
                            '$regex' => 'World bonus'
                        ],
                        'reduced'=>true,
                        'idTo' => [
                            '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                        ],
                        'type'=>1,//11
                    ])
                    ->sum('amount');
                $statisticInfo['bonus']['autoBonus'] = Transaction::find()
                    ->select(['amount'])
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'forWhat' => [
                            '$regex' => 'Auto bonus'
                        ],
                        'idTo' => [
                            '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                        ],
                        'type'=>1,//4
                    ])
                    ->sum('amount');
                $statisticInfo['bonus']['executiveBonus'] = Transaction::find()
                    ->select(['amount'])
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'forWhat' => [
                            '$regex' => 'Executive bonus'
                        ],
                        'idTo' => [
                            '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                        ],
                        'type'=>1,//10
                    ])
                    ->sum('amount');

                $statisticInfo['bonus']['careerBonus'] = Transaction::find()
                    ->select(['amount'])
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'forWhat' => [
                            '$regex' => 'Bonus per the achievement'
                        ],
                        'idTo' => [
                            '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                        ],
                        'type'=>1,//12
                    ])
                    ->sum('amount');

                $statisticInfo['bonus']['mentorBonus'] = Transaction::find()
                    ->select(['amount'])
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'forWhat' => [
                            '$regex' => 'Mentor bonus'
                        ],
                        'idTo' => [
                            '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                        ],
                        'reduced'=>true,
                        'type'=>9,
                    ])
                    ->sum('amount');


                $statisticInfo['bonus']['equityBonus'] = Transaction::find()
                    ->select(['amount'])
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
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
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        //'forWhat' => [
                        //    '$regex' => 'Closing steps'
                        //],
                        'forWhat'=>'Closing steps',
                        'idTo' => [
                            '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                        ],
                        'reduced'=>true,
                        'type'=>1,
                    ])
                    ->sum('amount');

                $connectingBonusAdd = Transaction::find()
                    ->select(['amount'])
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
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
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'forWhat'=>'Cancellation purchase for a partner',
                        //'idTo' => [
                        //    '$ne' => new ObjectID('000000000000000000000001')
                        //],
                        'type'=>1,
                    ])
                    ->sum('amount');

                $statisticInfo['bonus']['connectingBonus'] = $connectingBonusAdd - $connectingBonusCancellation;
                $repayment_month = $statisticInfo['request']['from'];
                $month_interval  = date_diff(date_create($statisticInfo['request']['from'].'-01'), date_create($statisticInfo['request']['to'].'-01'));
                $month_interval  =(int) $month_interval->format('%m');
                $y_from = explode("-", $statisticInfo['request']['from'])[0];
                $y_to   = explode("-", $statisticInfo['request']['to'])[0];
                $y_from_m = explode("-", $statisticInfo['request']['from'])[1];
                $y_to_m   = explode("-", $statisticInfo['request']['to'])[1];
                if  ($y_from == $y_to) {
                    if ($month_interval >=1) {
                        $z=0;
                        if ( (int)$y_from_m >9) {
                            $z='';
                        }
                        $next_y_m = $y_from.'-'.$z.($y_from_m+1);
                        $repayment_month =[$statisticInfo['request']['from'],$next_y_m];
                    }

                } else {
                    $repayment_month = $statisticInfo['request']['from'];
                }
                $repayment_sum = RecoveryForRepaymentAmounts::find()
                    ->where([
                        'month_recovery'=>$repayment_month,
                        'warehouse_id'=>[
                            '$nin' => [null]
                        ]
                    ])
                    ->sum('recovery');
                $statisticInfo['bonus']['representative'] = $repayment_sum;

            }
            //--------------------------------B:COMMISSION GRAPH--------------------------------------------------
            if ($block_name =='commission-graph') {
                $view_name = '_commission_graph';
                $statisticInfo = [
                    'generalReceiptMoneyMonth' => [],
                    'feesCommissionMonth' => [],
                    'dateInterval' => []
                ];
                $model = (new \yii\mongodb\Query())
                    ->select(['amount', 'dateCreate', 'forWhat', 'idTo'])
                    ->from('transactions')
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'idTo' => [
                            '$ne' => new ObjectID('573a0d76965dd0fb16f60bfe')
                        ],
                        'type' => 1,
                        'forWhat' => [//Cancellation purchase for a partner
                            '$regex' => 'Purchase for|Closing steps|Mentor bonus|For stocks|Executive bonus|Bonus per the achievement|Auto bonus'
                        ]
                    ])
                    ->all();
                if (!empty($model)) {
                    foreach ($model as $item) {
                        $dateCreate = $item['dateCreate']->toDateTime()->format('Y-m');
                        if (empty($statisticInfo['generalReceiptMoneyMonth'][$dateCreate])) {
                            $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] = 0;
                        }
                        $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] += $item['amount'];

                        if (empty($statisticInfo['feesCommissionMonth'][$dateCreate])) {
                            $statisticInfo['feesCommissionMonth'][$dateCreate] = 0;
                        }
                        if (preg_match('/Cancellation purchase/',$item['forWhat'] ) ) {
                            $statisticInfo['feesCommissionMonth'][$dateCreate] -= abs($item['amount']);
                        } else {
                            $statisticInfo['feesCommissionMonth'][$dateCreate] += $item['amount'];
                        }

                        //$statisticInfo['feesCommission'] += $item['amount'];
                    }
                }
                $i = 0;
                for ($iDate = $d_from; $iDate <= $d_to; $iDate = date('Y-m', strtotime('+1 month', strtotime($iDate)))) {
                    if(empty($statisticInfo['generalReceiptMoneyMonth'][$iDate])){
                        $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i,0];
                    }else{
                        $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i,round($statisticInfo['generalReceiptMoneyMonth'][$iDate])];
                    }
                    if(empty($statisticInfo['feesCommissionMonth'][$iDate])){
                        $statisticInfo['feesCommissionMonth'][$iDate] = [$i,0];
                    }else{
                        $statisticInfo['feesCommissionMonth'][$iDate] = [$i,round($statisticInfo['feesCommissionMonth'][$iDate])];
                    }

                    $statisticInfo['dateInterval'][] = [$i,$iDate];
                    $i++;
                }
            }
            //--------------------------------B:TURNOVER GRAPH--------------------------------------------------
            if ($block_name =='turnover-graph') {
                $view_name = '_turnover_graph';
                $statisticInfo = [
                    'generalReceiptMoneyMonth' => [],
                    'dateInterval'=>[]
                ];
                $model = (new \yii\mongodb\Query())
                    ->select(['dateCreate', 'price', 'product', 'username', 'project'])
                    ->from('sales')
                    ->where([
                        'dateCreate' => [
                            '$gte' => new UTCDatetime($queryDateFrom),
                            '$lte' => new UTCDateTime($queryDateTo)
                        ],
                        'type' => [
                            '$ne' => -1
                        ],
                        'product' => ['$ne' => '0'],
                        'username' => [
                            '$ne' => 'main'
                        ]
                    ])
                    ->all();

                if (!empty($model)) {
                    foreach ($model as $item) {
                        $dateCreate = $item['dateCreate']->toDateTime()->format('Y-m');

                        if (empty($statisticInfo['generalReceiptMoneyMonth'][$dateCreate])) {
                            $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] = 0;
                        }
                        $statisticInfo['generalReceiptMoneyMonth'][$dateCreate] += $item['price'];
                        //$statisticInfo['generalReceiptMoney'] += $item['price'];
                    }
                }
                //-------dates----------
                $i = 0;
                for ($iDate = $d_from; $iDate <= $d_to; $iDate = date('Y-m', strtotime('+1 month', strtotime($iDate)))) {
                    if (empty($statisticInfo['generalReceiptMoneyMonth'][$iDate])) {
                        $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i, 0];
                    } else {
                        $statisticInfo['generalReceiptMoneyMonth'][$iDate] = [$i, round($statisticInfo['generalReceiptMoneyMonth'][$iDate])];
                    }
                    $statisticInfo['dateInterval'][] = [$i,$iDate];
                    $i++;
                }
                ksort($statisticInfo['generalReceiptMoneyMonth']);
            }
            //--------------------------------E:TURNOVER GRAPH--------------------------------------------------

            //--------------------------------B:CHECKS--------------------------------------------------
            if ($block_name =='checks') {
                $view_name ='_checks';
                $statisticInfo = [
                    'tradeTurnover'=>[]
                ];
                //-----------b:transaction +
                //'forWhat' =>['$in'=>['/Purchase for/','/Closing steps/','/Mentor bonus/','/For stocks/','/Executive bonus/','/Bonus per the achievement/']]
                //'forWhat' =>['$in'=>['Purchase for','Closing steps','Mentor bonus','For stocks','Executive bonus','Bonus per the achievement']]
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
                        //wrong sum - why??? //'forWhat' =>['$in'=>['Purchase for','Closing steps','Mentor bonus','For stocks','Executive bonus','Bonus per the achievement']]
                        'forWhat'=>['$regex'=>'Purchase for|Closing steps|Mentor bonus|For stocks|Executive bonus|Bonus per the achievement']

                    ])
                    ->all();
                if(!empty($model)) {
                    foreach ($model as $item) {
                        // собираем информацию для формирования максимального чека
                        if(empty($statisticInfo['tradeTurnover']['forUser'][(string)$item['idTo']])){
                            $statisticInfo['tradeTurnover']['forUser'][(string)$item['idTo']] = 0;
                        }
                        $statisticInfo['tradeTurnover']['forUser'][(string)$item['idTo']] += $item['amount'];
                    }
                }
                //-----------e:transaction +

                //-----------b:transaction (-)
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
                                'forWhat' => 'Cancellation purchase for a partner'

                            ]
                        ]
                    ])
                    ->all();
                if(!empty($model)) {
                    foreach ($model as $item) {
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
                //-----------e:transaction (-)
            }
            if (!Empty($view_name)) {
                $result=['success'=>true,'details_html'=>$this->renderPartial($view_name, [
                    'user' => $this->user,
                    'statisticInfo' => $statisticInfo,
                ])];

            }
        } catch (\Exception $e) {
            $result =['success'=>false,'details_html'=>'Error!'.$e->getMessage().' line:'.$e->getLine()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }

    /**
     * report not issued goods for client
     * @return string
     */
    public function actionInfoWaitSaleByUser()
    {

        $listCountry = [];

        $infoWarehouse = Warehouse::getInfoWarehouse();

        $allListCountry = Settings::getListCountry();

        $request =  Yii::$app->request->post();

        if(empty($request['countryReport'])) {
            if(Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)===false){
                $request['countryReport'] = $infoWarehouse->country;
            } else {
                $request['countryReport'] = 'all';
            }
        }

        $request['goodsReport'] = (empty($request['goodsReport']) ? 'all' : $request['goodsReport']);

        $infoSale = $infoGoods = [];

        if (Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)) {
            $listCountry['all'] = 'Все страны';
        }

        $listCountry[$request['countryReport']] = ($request['countryReport']=='all' ? 'Все страны' : $allListCountry[$request['countryReport']]);

        $model = StatusSales::find()
            ->where(['IN','setSales.status',['status_sale_new','status_sale_delivered',
                'status_sale_repairs_under_warranty',
                'status_sale_repair_without_warranty',]
            ])
            ->all();
        if(!empty($model)){
            $listIdSale = ArrayHelper::getColumn($model,'idSale');

            $model = Sales::find()
                ->where(['IN','_id',$listIdSale])
                ->andWhere([
                    'type' => [
                        '$ne'   =>  -1
                    ]
                ])
                ->andWhere(['in','product',Products::productIDWithSet()])
                ->all();



            if(!empty($model)){
                /** @var \app\models\Sales $item */
                foreach ($model as $item) {
                    $tempInfoUser = [];
                    if(!empty($item->statusSale) && ($request['countryReport'] == 'all' || $request['countryReport'] == $item->infoUser->country)){

                        $tempInfoUser['_id'] = (string)$item->_id;

                        $tempInfoUser['name'] = $item->infoUser->secondName . ' ' . $item->infoUser->firstName . '('. $item->infoUser->username.')';
                        $tempInfoUser['country'] = $item->infoUser->country;
                        $tempInfoUser['city'] = $item->infoUser->city;
                        $tempInfoUser['address'] = $item->infoUser->address;


                        $tempInfoUser['phone']= [];
                        if(!empty($item->infoUser->phoneNumber)){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],[$item->infoUser->phoneNumber]);
                        }
                        if(!empty($item->infoUser->phoneNumber2)){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],[$item->infoUser->phoneNumber2]);
                        }
                        if(!empty($item->infoUser->settings['phoneViber'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['Viber' => $item->infoUser->settings['phoneViber']]);
                        }
                        if(!empty($item->infoUser->settings['phoneFB'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['FB' => $item->infoUser->settings['phoneFB']]);
                        }
                        if(!empty($item->infoUser->settings['phoneTelegram'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['Telegram' => $item->infoUser->settings['phoneTelegram']]);
                        }
                        if(!empty($item->infoUser->settings['phoneWhatsApp'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['WhatsApp' => $item->infoUser->settings['phoneWhatsApp']]);
                        }

                        $tempInfoUser['date_create'] = $item->dateCreate->toDateTime()->format('Y-m-d H:i:s');
                        $tempInfoUser['type'] = $item->type;

                        /** @var StatusSales $itemSet */
                        foreach($item->statusSale->set as $k=>$itemSet){


                            if($request['goodsReport'] == 'all' || $request['goodsReport'] == $itemSet->title) {

                                $tempInfoGoods = [];

                                $tempInfoGoods['countryWarehouse'] = 'none';
                                $tempInfoGoods['nameWarehouse'] = '';

                                if(!empty($itemSet->idUserChange)){
                                    $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                    if(!empty($infoWarehouse->country)){
                                        $tempInfoGoods['countryWarehouse'] = $infoWarehouse->country;
                                        $tempInfoGoods['nameWarehouse'] = $infoWarehouse->title;

                                        if(empty($listCountry[$infoWarehouse->country])){
                                            $listCountry[$infoWarehouse->country] = $allListCountry[$infoWarehouse->country];
                                        }
                                    }
                                }

                                $tempInfoGoods['key'] = $k;
                                $tempInfoGoods['goods'] = $itemSet->title;
                                $tempInfoGoods['status'] = $itemSet->status;

                                $infoGoods[$itemSet->title] = (!empty($infoGoods[$itemSet->title]) ? ($infoGoods[$itemSet->title] +1) : '1');

                                if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                    $infoSale[] = ArrayHelper::merge($tempInfoUser, $tempInfoGoods);
                                }
                            }
                        }

                    }
                }
            }
        }


        return $this->render('info-wait-sale-by-user',[
            'language' => Yii::$app->language,
            'request' => $request,
            'infoSale' => $infoSale,
            'listCountry' => $listCountry,
            'infoGoods' => $infoGoods,


            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionChangeWarehouse()
    {
        $request = Yii::$app->request->get();

        $modelInfoSale = Sales::findOne(['_id'=>new ObjectID($request['_id'])]);

        $info = $modelInfoSale->statusSale->setSales[$request['k']];

        $info = [
            'idSale'            => $request['_id'],
            'key'               => $request['k'],
            'idUserWarehpouse'  => (string)$info['idUserChange']
        ];


        return $this->renderAjax('_change-warehouse', [
            'language' => Yii::$app->language,
            'info' => $info,
        ]);
    }

    public function actionSaveChangeWarehouse(){
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        $modelInfoSale = Sales::findOne(['_id'=>new ObjectID($request['idSale'])]);

        $info = $modelInfoSale->statusSale;

        $temp = $info->setSales;
        $temp[$request['key']]['idUserChange'] = new ObjectID($request['warehouse_id']);

        $info->setSales = $temp;

        if($info->save()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }


        return $this->redirect('/' . Yii::$app->language .'/business/sale-report/info-wait-sale-by-user');
    }

    public function actionInfoSaleForCountry()
    {
        $listGoodsWithKey = Products::getListGoodsWithKey();

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-m-d", strtotime( $request['to']." -6 months"));
        }

        if(empty($request['send_kh'])){
            $request['send_kh'] = 0;
        }

        if(empty($request['flGoods'])){
            if(empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';

        } else {
            $request['listGoods'] = '';
        }

        $infoSale = [];


        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();


        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($request['listPack'])){

                    if($item->product == $request['listPack'] || $request['listPack'] == 'all') {

                        $country = 'none';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if (empty($infoSale[$country][$item->productName])) {
                            $infoSale[$country][$item->productName] = [
                                'all'       => 0,
                                'issued'    => 0,
                                'in_stock'  => 0,
                                'wait'      => 0,
                                'repair'    => 0,
                                'send'      => 0,
                            ];
                        }

                        if ($flIssuedPack == '1') {
                            $infoSale[$country][$item->productName]['issued']++;
                        } else {
                            $infoSale[$country][$item->productName]['wait']++;
                        }

                        if($flRepairsPack == '1'){
                            $infoSale[$country][$item->productName]['repair']++;
                        }

                        $infoSale[$country][$item->productName]['all']++;
                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            $country = 'none';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }


                            if(empty($infoSale[$country][$itemSet->title])){
                                $infoSale[$country][$itemSet->title] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            $infoSale[$country][$itemSet->title]['all']++;

                            if(in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                                $infoSale[$country][$itemSet->title]['issued']++;
                            } else {
                                $infoSale[$country][$itemSet->title]['wait']++;
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $infoSale[$country][$itemSet->title]['repair']++;
                            }
                        }
                    }
                }

            }
        }

        if(!empty($request['listGoods'])) {

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            if($request['send_kh']==1){
                $modelSending = $modelSending->andWhere(['from_where_send'=>'5a056671dca7873e022be781'])->all();
            } else {
                $modelSending = $modelSending->all();
            }

            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }

                    if (!empty($item->part_parcel)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) &&
                                ($listGoodsWithKey[$itemParcel['goods_id']] == $request['listGoods'] || $request['listGoods'] == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];

                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $request['listGoods'] || $request['listGoods'] == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }


                        if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                            $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                'all' => 0,
                                'issued' => 0,
                                'in_stock' => 0,
                                'wait' => 0,
                                'repair' => 0,
                                'send' => 0,
                            ];
                        }

                        $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                    }

                }
            }

        }


        return $this->render('info-sale-for-country',[
            'language'      =>   Yii::$app->language,
            'infoSale'      =>   $infoSale,
            'request'       =>   $request
        ]);
    }

    public function actionExportInfoSaleForCountry($from,$to,$flGoods,$listPack,$listGoods)
    {
        $listGoodsWithKey = Products::getListGoodsWithKey();
        $listCountry = Settings::getListCountry();

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($from . '00:00:01') * 1000),
                    '$lte' => new UTCDateTime(strtotime($to . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();


        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($listPack)){

                    if($item->product == $listPack || $listPack == 'all') {

                        $country = 'none';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if (empty($infoSale[$country][$item->productName])) {
                            $infoSale[$country][$item->productName] = [
                                'all'       => 0,
                                'issued'    => 0,
                                'in_stock'  => 0,
                                'wait'      => 0,
                                'repair'    => 0,
                                'send'      => 0,
                            ];
                        }

                        if ($flIssuedPack == '1') {
                            $infoSale[$country][$item->productName]['issued']++;
                        } else {
                            $infoSale[$country][$item->productName]['wait']++;
                        }

                        if($flRepairsPack == '1'){
                            $infoSale[$country][$item->productName]['repair']++;
                        }

                        $infoSale[$country][$item->productName]['all']++;
                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $listGoods || $listGoods == 'all'){

                            $country = 'none';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }


                            if(empty($infoSale[$country][$itemSet->title])){
                                $infoSale[$country][$itemSet->title] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            $infoSale[$country][$itemSet->title]['all']++;

                            if(in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                                $infoSale[$country][$itemSet->title]['issued']++;
                            } else {
                                $infoSale[$country][$itemSet->title]['wait']++;
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $infoSale[$country][$itemSet->title]['repair']++;
                            }
                        }
                    }
                }

            }
        }

        if(!empty($listGoods)) {

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            $modelSending = $modelSending->all();

            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }

                    if (!empty($item->part_parcel)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) &&
                                ($listGoodsWithKey[$itemParcel['goods_id']] == $listGoods || $listGoods == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];

                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $listGoods  || $listGoods == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }


                        if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                            $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                'all' => 0,
                                'issued' => 0,
                                'in_stock' => 0,
                                'wait' => 0,
                                'repair' => 0,
                                'send' => 0,
                            ];
                        }

                        $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                    }

                }
            }
        }

        if($flGoods == 1){
            $infoExport = [];
            foreach ($infoSale as $kCountry=>$itemCountry) {
                foreach ($itemCountry as $k=>$item) {
                    $infoExport[] = [
                        'country'       => !empty($listCountry[$kCountry]) ? $listCountry[$kCountry] : 'none',
                        'product_name'  => $k,
                        'all'           => $item['all'],
                        'issued'        => $item['issued'],
                        'margin'        => ($item['issued'] + $item['send'] + $item['in_stock'] - $item['all']),
                        'repair'        => $item['repair']
                    ];
                }

            }


            \moonland\phpexcel\Excel::export([
                'models' => $infoExport,
                'fileName' => 'export_'.$from.'-'.$to,
                'columns' => [
                    'country',
                    'product_name',
                    'all',
                    'issued',
                    'margin',
                    'repair'
                ],
                'headers' => [
                    'country' => THelper::t('country'),
                    'product_name' => THelper::t('business_product'),
                    'all' => THelper::t('number_all_ordering'),
                    'issued' => THelper::t('number_issue'),
                    'margin' =>THelper::t('number_difference'),
                    'repair' => THelper::t('number_repair')
                ]
            ]);
        } else {$infoExport = [];

            foreach ($infoSale as $kCountry=>$itemCountry) {
                foreach ($itemCountry as $k=>$item) {
                    $infoExport[] = [
                        'country'       => !empty($listCountry[$kCountry]) ? $listCountry[$kCountry] : 'none',
                        'product_name'  => $k,
                        'all'           => $item['all'],
                        'issued'        => $item['issued'],
                        'in_stock'      => $item['in_stock'],
                        'send'          => $item['send'],
                        'margin'        => ($item['issued'] + $item['send'] + $item['in_stock'] - $item['all']),
                        'repair'        => $item['repair']
                    ];
                }

            }


            \moonland\phpexcel\Excel::export([
                'models' => $infoExport,
                'fileName' => 'export_'.$from.'-'.$to,
                'columns' => [
                    'country',
                    'product_name',
                    'all',
                    'issued',
                    'in_stock',
                    'send',
                    'margin',
                    'repair'
                ],
                'headers' => [
                    'country' => THelper::t('country'),
                    'product_name' => THelper::t('goods'),
                    'all' => THelper::t('number_all_ordering'),
                    'issued' => THelper::t('number_issue'),
                    'in_stock' => THelper::t('number_in_stock'),
                     'send' => THelper::t('number_send'),
                    'margin' =>THelper::t('number_difference'),
                    'repair' => THelper::t('number_repair')
                ]
            ]);
        }

        die();
    }

    public function actionInfoSaleForCountryWarehouse()
    {
        $listCountry = Settings::getListCountry();
        $listGoodsWithKey = Products::getListGoodsWithKey();

        $request =  Yii::$app->request->post();

        $listCountryWarehouse = [];

        if(empty($request['listCountry'])){
            $request['listCountry'] = 'all';
        }

        if(empty($request['send_kh'])){
            $request['send_kh'] = 0;
        }

        if(empty($request['flGoods'])){
            if(empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';

        } else {
            $request['listGoods'] = '';
        }

        $infoSale = [];

        $dateTo = date("Y-m-d");
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -6 months"));;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($dateFrom) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();




        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($request['listPack'])){

                    if($item->product == $request['listPack'] || $request['listPack'] == 'all') {

                        $country = 'none';
                        $warehouse = '';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if($request['listCountry'] == 'all' || $request['listCountry'] == $country){
                            if (empty($infoSale[$country][$warehouse][$item->productName])) {
                                $infoSale[$country][$warehouse][$item->productName] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            if ($flIssuedPack == '1') {
                                $infoSale[$country][$warehouse][$item->productName]['issued']++;
                            } else {
                                $infoSale[$country][$warehouse][$item->productName]['wait']++;
                            }

                            if($flRepairsPack == '1'){
                                $infoSale[$country][$warehouse][$item->productName]['repair']++;
                            }

                            $infoSale[$country][$warehouse][$item->productName]['all']++;
                        }

                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            $country = 'none';
                            $warehouse = '';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if($request['listCountry'] == $country || $request['listCountry'] == 'all') {
                                if (empty($infoSale[$country][$warehouse][$itemSet->title])) {
                                    $infoSale[$country][$warehouse][$itemSet->title] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$country][$warehouse][$itemSet->title]['all']++;

                                if (in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['issued']++;
                                } else {
                                    $infoSale[$country][$warehouse][$itemSet->title]['wait']++;
                                }

                                if (in_array($itemSet->status, ['status_sale_repairs_under_warranty', 'status_sale_repair_without_warranty'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['repair']++;
                                }

                            }
                        }
                    }
                }

            }
        }


        if(!empty($request['listGoods'])) {

            $selectWarehouseKh = [];
            if($request['send_kh']==1){
                $selectWarehouseKh = ['from_where_send'=>new ObjectID('5a056671dca7873e022be781')];
            }

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            if($request['send_kh']==1){
                $modelSending = $modelSending->andWhere(['from_where_send'=>'5a056671dca7873e022be781'])->all();
            } else {
                $modelSending = $modelSending->all();
            }


            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }
                    if (empty($item->infoWarehouse->title)) {
                        $item->infoWarehouse->title = 'none';
                    }

                    if (!empty($item->part_parcel) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) && ($listGoodsWithKey[$itemParcel['goods_id']] == $request['listGoods'] || $request['listGoods'] == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all' => 0,
                                        'issued' => 0,
                                        'in_stock' => 0,
                                        'wait' => 0,
                                        'repair' => 0,
                                        'send' => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];
                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $request['listGoods'] || $request['listGoods'] == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }
                        if (empty($item->infoWarehouse->title)) {
                            $item->infoWarehouse->title = 'none';
                        }

                        if (!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {

                            if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                    'all' => 0,
                                    'issued' => 0,
                                    'in_stock' => 0,
                                    'wait' => 0,
                                    'repair' => 0,
                                    'send' => 0,
                                ];
                            }

                            $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                        }
                    }
                }
            }

        }

        asort($listCountryWarehouse);

        return $this->render('info-sale-for-country-warehouse',[
            'language'              =>   Yii::$app->language,
            'infoSale'              =>   $infoSale,
            'listCountryWarehouse'  =>   $listCountryWarehouse,
            'request'               =>   $request
        ]);
    }

    public function actionInfoSaleForCountryWarehouseExcel()
    {
        $listCountry = Settings::getListCountry();
        $listGoodsWithKey = Products::getListGoodsWithKey();

        $request =  Yii::$app->request->post();

        $listCountryWarehouse = [];

        if(empty($request['listCountry'])){
            $request['listCountry'] = 'all';
        }

        if(empty($request['send_kh'])){
            $request['send_kh'] = 0;
        }

        if(empty($request['flGoods'])){
            if(empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';

        } else {
            $request['listGoods'] = '';
        }

        $infoSale = [];

        $dateTo = date("Y-m-d");
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -6 months"));;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($dateFrom) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();




        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($request['listPack'])){

                    if($item->product == $request['listPack'] || $request['listPack'] == 'all') {

                        $country = 'none';
                        $warehouse = '';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if($request['listCountry'] == 'all' || $request['listCountry'] == $country){
                            if (empty($infoSale[$country][$warehouse][$item->productName])) {
                                $infoSale[$country][$warehouse][$item->productName] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            if ($flIssuedPack == '1') {
                                $infoSale[$country][$warehouse][$item->productName]['issued']++;
                            } else {
                                $infoSale[$country][$warehouse][$item->productName]['wait']++;
                            }

                            if($flRepairsPack == '1'){
                                $infoSale[$country][$warehouse][$item->productName]['repair']++;
                            }

                            $infoSale[$country][$warehouse][$item->productName]['all']++;
                        }

                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            $country = 'none';
                            $warehouse = '';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if($request['listCountry'] == $country || $request['listCountry'] == 'all') {
                                if (empty($infoSale[$country][$warehouse][$itemSet->title])) {
                                    $infoSale[$country][$warehouse][$itemSet->title] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$country][$warehouse][$itemSet->title]['all']++;

                                if (in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['issued']++;
                                } else {
                                    $infoSale[$country][$warehouse][$itemSet->title]['wait']++;
                                }

                                if (in_array($itemSet->status, ['status_sale_repairs_under_warranty', 'status_sale_repair_without_warranty'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['repair']++;
                                }

                            }
                        }
                    }
                }

            }
        }


        if(!empty($request['listGoods'])) {

            $selectWarehouseKh = [];
            if($request['send_kh']==1){
                $selectWarehouseKh = ['from_where_send'=>new ObjectID('5a056671dca7873e022be781')];
            }

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            if($request['send_kh']==1){
                $modelSending = $modelSending->andWhere(['from_where_send'=>'5a056671dca7873e022be781'])->all();
            } else {
                $modelSending = $modelSending->all();
            }


            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }
                    if (empty($item->infoWarehouse->title)) {
                        $item->infoWarehouse->title = 'none';
                    }

                    if (!empty($item->part_parcel) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) && ($listGoodsWithKey[$itemParcel['goods_id']] == $request['listGoods'] || $request['listGoods'] == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all' => 0,
                                        'issued' => 0,
                                        'in_stock' => 0,
                                        'wait' => 0,
                                        'repair' => 0,
                                        'send' => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];
                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $request['listGoods'] || $request['listGoods'] == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }
                        if (empty($item->infoWarehouse->title)) {
                            $item->infoWarehouse->title = 'none';
                        }

                        if (!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {

                            if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                    'all' => 0,
                                    'issued' => 0,
                                    'in_stock' => 0,
                                    'wait' => 0,
                                    'repair' => 0,
                                    'send' => 0,
                                ];
                            }

                            $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                        }
                    }
                }
            }

        }

        asort($listCountryWarehouse);

        $amount = [
            'ordering'  => 0,
            'issued'    => 0,
            'in_stock'  => 0,
            'send'      => 0,
            'repair'    => 0,
            'margin'    => 0,
        ];

        $infoExport = [];
        if(!empty($infoSale)){
            foreach ($infoSale as $k=>$itemWarehouse) {
                foreach($itemWarehouse as $kWarehouse=>$itemGoods) {
                    foreach($itemGoods as $kGoods=>$item) {
                        $margin = $item['issued'] + $item['send'] + $item['in_stock'] - $item['all'];
                        $amount['ordering'] += $item['all'];
                        $amount['issued'] += $item['issued'];
                        $amount['in_stock'] += $item['in_stock'];
                        $amount['send'] += $item['send'];
                        $amount['repair'] += $item['repair'];
                        $amount['margin'] += $margin;

                        $infoExport[] = [
                            'country'           =>  (!empty($listCountry[$k]) ? $listCountry[$k] : 'none'),
                            'warehouse'         =>  $kWarehouse,
                            'title'             =>  $kGoods,
                            'order'             =>  $item['all'],
                            'issued'            =>  $item['issued'],

                            'inStock'           =>  (!empty($request['listGoods']) ? $item['in_stock'] : '-'),
                            'send'              =>  (!empty($request['listGoods']) ? $item['send'] : '-'),

                            'different'         =>  $margin,
                            'inRepair'          =>  $item['repair'],
                        ];
                    }
                }

            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'country',
                'warehouse',
                'title',
                'order',
                'issued',
                'inStock',
                'send',
                'different',
                'inRepair'
            ],
            'headers' => [
                'country'           =>  'Страна',
                'warehouse'         =>  'Склад',
                'title'             =>  (!empty($request['listGoods']) ? 'Название товара' : 'Название пака'),
                'order'             =>  'Заказано',
                'issued'            =>  'Выданно',
                'inStock'           =>  'В наличии',
                'send'              =>  'Отправленно',
                'different'         =>  'Разница',
                'inRepair'          =>  'На ремонте',
            ],
        ]);

        die();

    }

    public function actionReportProjectVipcoin(){
        $infoWarehouse = Warehouse::getInfoWarehouse();

        $allListCountry = Settings::getListCountry();
        $totatPrice = 0;
        $infoSale = [];
        $listCountry = [];
        $listCity = [];

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to']=date("Y-m-d");
            $date = strtotime('-3 month', strtotime($request['to']));
            $request['from'] = date('Y-m-d', $date);
        }

        $listAvailableCities = [];
        if(Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)===false){
            $request['countryReport'] = $infoWarehouse->country;

            if(!empty($infoWarehouse->cities) && empty($request['cityReport'])){
                $listAvailableCities = $infoWarehouse->cities;
                $listCity = ArrayInfoHelper::getArrayEqualKeyValue($listAvailableCities);
            }
        }

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [1,9,10]
                ]
            ])
            ->andWhere([
                'productData.categories' => [
                    '$elemMatch' => ['$eq'=>'5b4c46469ed4eb002a683891']
                ]
            ])
            ->all();
        if(!empty($model)){
            foreach ($model as $item) {
                if (!empty($item->infoUser->country)) {
                    $country = mb_strtolower($item->infoUser->country);
                    $listCountry[$country] = $allListCountry[$country];

                    if (empty($request['countryReport']) || ($request['countryReport'] == $country)) {
                        $city = (!empty($item->infoUser->city) ? $item->infoUser->city : 'None');

                        if (empty($listAvailableCities)) {
                            $listCity[$city] = $city;
                        }

                        if ((empty($request['cityReport']) && empty($listAvailableCities))
                            || (empty($request['cityReport']) && !empty($listAvailableCities) && in_array($city, $listAvailableCities))
                            || (!empty($request['cityReport']) && in_array($city, $request['cityReport']))
                        ) {
                            $infoSale[] = [
                                'dateCreate' => $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                                'userCountry' => $allListCountry[$country],
                                'userCity' => $city,
                                'userAddress' => $item->infoUser->address,
                                'userName' => $item->infoUser->secondName . ' ' . $item->infoUser->firstName,
                                'userPhone' => $item->infoUser->phoneNumber . ' / ' . $item->infoUser->phoneNumber2,
                                'productName' => $item->productName,
                                'productPrice' => $item->price
                            ];

                            $totatPrice += $item->price;
                        }
                    }


                }

            asort($listCountry);
            asort($listCity);
            }
        }


        return $this->render('report-project-vipcoin',[
                'language' => Yii::$app->language,
                'request' => $request,
                'totatPrice' => $totatPrice,
                'infoSale' => $infoSale,
                'listCountry' => $listCountry,
                'listCity' => $listCity
            ]
        );
    }

    public function actionReportProjectVipcoinExcel()
    {
        $infoWarehouse = Warehouse::getInfoWarehouse();

        $allListCountry = Settings::getListCountry();

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to']=date("Y-m-d");
            $date = strtotime('-3 month', strtotime($request['to']));
            $request['from'] = date('Y-m-d', $date);
        }

        if(Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)===false){
            $request['countryReport'] = $infoWarehouse->country;

            if(!empty($infoWarehouse->cities) && empty($request['cityReport'])){
                $listAvailableCities = $infoWarehouse->cities;
                $listCity = ArrayInfoHelper::getArrayEqualKeyValue($listAvailableCities);
            }
        }

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [1,9,10]
                ]
            ])
            ->andWhere([
                'productData.categories' => [
                    '$elemMatch' => ['$eq'=>'5b4c46469ed4eb002a683891']
                ]
            ])
            ->all();

        $infoExport = [];
        if(!empty($model)) {
            foreach ($model as $item) {
                if (!empty($item->infoUser->country)) {
                    $country = mb_strtolower($item->infoUser->country);

                    $listCountry[$country] = $allListCountry[$country];

                    if (empty($request['countryReport']) || ($request['countryReport'] == $country)) {
                        $city = (!empty($item->infoUser->city) ? $item->infoUser->city : 'None');

                        if (empty($listAvailableCities)) {
                            $listCity[$city] = $city;
                        }

                        if ((empty($request['cityReport']) && empty($listAvailableCities))
                            || (empty($request['cityReport']) && !empty($listAvailableCities) && in_array($city, $listAvailableCities))
                            || (!empty($request['cityReport']) && in_array($city, $request['cityReport']))
                        ) {
                            $infoExport[] = [
                                'date_create' => $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                                'country' => $allListCountry[$country],
                                'city' => $city,
                                'address' => $item->infoUser->address,
                                'full_name' => $item->infoUser->secondName . ' ' . $item->infoUser->firstName,
                                'phone' => $item->infoUser->phoneNumber . ' / ' . $item->infoUser->phoneNumber2,
                                'goods' => $item->productName,
                                'price' => $item->price
                            ];

                        }
                    }


                }
            }
        }
        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'date_create',
                'country',
                'city',
                'address',
                'full_name',
                'phone',
                'goods',
                'price'
            ],
            'headers' => [
                'date_create'   =>  THelper::t('date_create'),
                'country'       =>  THelper::t('country'),
                'city'          =>  THelper::t('city'),
                'address'       =>  THelper::t('address'),
                'full_name'     =>  THelper::t('full_name'),
                'phone'         =>  THelper::t('phone'),
                'goods'         =>  THelper::t('goods'),
                'price'         =>  THelper::t('price')
            ],
        ]);

        die();
    }

    /**
     * make report for charges representative
     * @return string
     * @throws GoodException
     */
    public function actionReportChargesRepresentative()
    {
        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['date'] = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        }

        $report = [];

        $listRepresentative = Warehouse::getListHeadAdmin();
        foreach ($listRepresentative as $k=>$item) {
            $report[$k] = [
                'title'         => $item,
                'percent'       => 0,
                'goods_turnover'=> 0,
                'issued_for_amount'=> 0,
                'accrued'       => 0,
                'deduction'     => 0,
                'repayment'     => 0,
                'goods'         => []
            ];
        }

        $modelRepaymentAmounts = RepaymentAmounts::find()->all();
        /** @var RepaymentAmounts $item */
        foreach ($modelRepaymentAmounts as $item){
            if(!empty($item->prices_representative[$request['date']])){
                $representativeId = (string)$item->warehouse->headUser;

                if(empty($report[$representativeId]['goods'][(string)$item->product_id])){
                    $report[$representativeId]['percent'] = $item->prices_representative[$request['date']]['percent'];
                    $report[$representativeId]['goods_turnover'] = $item->prices_representative[$request['date']]['goods_turnover'];
                    $report[$representativeId]['goods'][(string)$item->product_id] = [
                        'title' => $item->product->title,
                        'price' => 0,
                        'count' => 0
                    ];
                }

                $report[$representativeId]['goods'][(string)$item->product_id]['price'] += $item->prices_representative[$request['date']]['price'];
                $report[$representativeId]['goods'][(string)$item->product_id]['count'] += $item->prices_representative[$request['date']]['count'];

                @$report[$representativeId]['issued_for_amount'] += round(($item->prices_representative[$request['date']]['price']/$report[$representativeId]['percent']*100),2);

            }
//            else {
//                throw new GoodException('Отчет не возможно сфомировать','Нет выплат по данной дате');
//            }
        }

        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date']])
            ->all();
        if(!empty($modelRepayment)){
            /** @var Repayment $item */
            foreach ($modelRepayment as $item) {
                $representativeId = (string)$item->representative_id;

                if(isset($report[$representativeId])){
                    $report[$representativeId]['accrued'] += $item->accrued;
                    $report[$representativeId]['deduction'] += $item->deduction;
                    $report[$representativeId]['repayment'] += $item->repayment;
                }
            }
        }

        return $this->render('report-charges-representative',[
                'language' => Yii::$app->language,
                'request' => $request,
                'report' => $report
            ]
        );
    }

    /**
     * make report for charges representative
     * @return string
     * @throws GoodException
     */
    public function actionReportChargesWarehouse()
    {
        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['representative'] = '';
            $request['date'] = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        }

        $report = [];

        $listWarehouse = Warehouse::getArrayWarehouse();

        foreach ($listWarehouse as $k=>$item) {
            $report[$k] = [
                'title'         => $item,
                'representative_id'=>'',
                'percent'       => 0,
                'goods_turnover'=> 0,
                'issued_for_amount'=> 0,
                'accrued'       => 0,
                'deduction'     => 0,
                'repayment'     => 0,
                'goods'         => []
            ];
        }

        $modelRepaymentAmounts = RepaymentAmounts::find()->all();
        /** @var RepaymentAmounts $item */
        foreach ($modelRepaymentAmounts as $item){
            if(!empty($item->prices_warehouse[$request['date']])){
                $representativeId = (string)$item->warehouse->headUser;
                $warehouse_id = (string)$item->warehouse_id;

                if(empty($report[$warehouse_id]['goods'][(string)$item->product_id])){
                    $report[$warehouse_id]['representative_id'] = $representativeId;
                    $report[$warehouse_id]['percent'] = $item->prices_warehouse[$request['date']]['percent'];
                    $report[$warehouse_id]['goods_turnover'] = $item->prices_warehouse[$request['date']]['goods_turnover'];
                    $report[$warehouse_id]['goods'][(string)$item->product_id] = [
                        'title' => $item->product->title,
                        'price' => 0,
                        'count' => 0
                    ];
                }

                $report[$warehouse_id]['goods'][(string)$item->product_id]['price'] += $item->prices_warehouse[$request['date']]['price'];
                $report[$warehouse_id]['goods'][(string)$item->product_id]['count'] += $item->prices_warehouse[$request['date']]['count'];

                if($report[$warehouse_id]['percent']>0){
                    $report[$warehouse_id]['issued_for_amount'] += round(($item->prices_warehouse[$request['date']]['price']/$report[$warehouse_id]['percent']*100),2);
                }
            }
//            else {
//                throw new GoodException('Отчет не возможно сфомировать','Нет выплат по данной дате');
//            }
        }

        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date']])
            ->all();
        if(!empty($modelRepayment)){
            /** @var Repayment $item */
            foreach ($modelRepayment as $item) {
                $warehouseId = (string)$item->warehouse_id;

                if(isset($report[$warehouseId])){
                    $report[$warehouseId]['accrued'] += $item->accrued;
                    $report[$warehouseId]['deduction'] += $item->deduction;
                    $report[$warehouseId]['repayment'] += $item->repayment;
                }
            }
        }

        foreach ($report as $k=>$item) {
            if(!empty($request['representative']) && $request['representative']!=$item['representative_id']){
                unset($report[$k]);
            }
        }


        return $this->render('report-charges-warehouse',[
                'language' => Yii::$app->language,
                'request' => $request,
                'report' => $report
            ]
        );
    }
    //------------b:--Report balance up
    public function actionReportBalanceUp()
    {
        $request =  Yii::$app->request->post();
        $p_key   =  Yii::$app->request->get('d');
        if(empty($request)){
            $request['to']   = date("Y-m-d");
            $request['from'] = date("Y-m-d", strtotime( $request['to']." -1 months"));
        }
        $dateTo   = $request['to'];
        $dateFrom = $request['from'];

        $infoSale = PreUp::find()
            ->where([
                'created_at' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
//            ->andWhere([
//                'type' => [
//                    '$ne'   =>  -1
//                ]
//            ])
            ->andWhere([
                'product' => 9001
            ])
            ->orderBy(['created_at' => SORT_DESC]) //SORT_ASC//SORT_DESC//
            ->all();
        foreach ($infoSale as $info_item) {
            $info_user = @Users::find()->where(['_id'=>new ObjectID($info_item['author_id'])])->one();
            $info_item['author_name']  = @$info_user->username;
        }
        if ($p_key !=(date('d')+1)) {
            $p_key = null;
        }
        return $this->render('report-balance-up',[
                'language' => Yii::$app->language,
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo,
                'report'   => $dateFrom,
                'infoSale' => $infoSale,
                'p_key'    => $p_key
            ]
        );
    }
    //------------e:--Report balance up

}