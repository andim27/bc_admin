<?php

namespace app\modules\business\controllers;

use app\models\api\Product;
use app\models\api\transactions\Charity;
use app\models\PartsAccessories;
use app\models\PercentForRepaymentAmounts;
use app\models\Products;
use app\models\RecoveryForRepaymentAmounts;
use app\models\Repayment;
use app\models\RepaymentAmounts;
use app\models\Sales;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Users;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;

class OffsetsWithWarehousesController extends BaseController
{

    /**
     * all info about percent and their border
     *
     * @param $object
     * @return string
     */
    public function actionPercentForRepayment($object)
    {
        $model = PercentForRepaymentAmounts::find()
            ->where([
                $object.'_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->all();

        return $this->render('percent-for-repayment',[
            'model' => $model,
            'object'=>$object,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup for add or update percent and border
     * @param string $id
     * @return string
     */
    public function actionAddUpdatePercentForRepayment($id='',$object)
    {
        $model = new PercentForRepaymentAmounts();

        if(!empty($id)){
            $model = $model::findOne(['_id'=> new ObjectID($id)]);
        }

        return $this->renderAjax('_add-update-percent-for-repayment', [
            'language' => Yii::$app->language,
            'model' => $model,
            'object' => $object,
            'id' => $id
        ]);
    }

    /**
     * save change for turnover boundary
     * @return \yii\web\Response
     */
    public function actionSavePercentForRepayment()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $object = $request['object'];

            $model = new PercentForRepaymentAmounts();

            if(!empty($request['_id'])){
                $model = $model::findOne(['_id'=>new ObjectID($request['_id'])]);
            }

            $turnover_boundary = [];
            if(!empty($request['percent'])){
                foreach ($request['percent'] as $k=>$item) {
                    $turnover_boundary[] = [
                        'turnover_boundary' => $request['turnover_boundary'][$k],
                        'percent' => $item
                    ];
                }

                ArrayHelper::multisort($turnover_boundary, ['turnover_boundary'], [SORT_ASC]);
            }

            $model->{$object.'_id'} = $request[$object.'_id'];
            if(!empty($request['dop_price_per_warehouse'])){
                $model->dop_price_per_warehouse = $request['dop_price_per_warehouse'];
            }
            $model->turnover_boundary = $turnover_boundary;

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );
            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/percent-for-repayment');

    }

    /**
     * set default value
     * @return \yii\web\Response
     */
    public function actionDefaultPercentForRepaymentRepresentative(){
        $list = Warehouse::getListHeadAdmin();

        foreach ($list as $k=>$item) {
            $model = new PercentForRepaymentAmounts();

            $model->representative_id = new ObjectID($k);
            $model->dop_price_per_warehouse = (int)5000;
            $model->turnover_boundary = [
                '0' => [
                    'turnover_boundary' => (int)0,
                    'percent' => (int)10
                ],
                '1' => [
                    'turnover_boundary' => (int)5000,
                    'percent' => (int)15
                ],
                '2' => [
                    'turnover_boundary' => (int)10000,
                    'percent' => (int)20
                ],
                '3' => [
                    'turnover_boundary' => (int)25000,
                    'percent' => (int)25
                ],
            ];

            if($model->save()){}
        }

        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/percent-for-repayment?object=representative');
    }
    /**
     * set default value
     * @return \yii\web\Response
     */
    public function actionDefaultPercentForRepaymentWarehouse(){
        $list = Warehouse::getArrayWarehouse();

        foreach ($list as $k=>$item) {
            $model = new PercentForRepaymentAmounts();

            $model->warehouse_id = new ObjectID($k);
            $model->turnover_boundary = [
                '0' => [
                    'turnover_boundary' => (int)0,
                    'percent' => (int)5
                ],
                '1' => [
                    'turnover_boundary' => (int)5000,
                    'percent' => (int)10
                ],
                '2' => [
                    'turnover_boundary' => (int)10000,
                    'percent' => (int)15
                ]
            ];

            if($model->save()){}
        }

        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/percent-for-repayment?object=warehouse');
    }


    /**
     * list recovery for repayment
     * @param $object
     * @param string $representative_id
     * @return string
     */
    public function actionRecoveryForRepayment($object,$representative_id='')
    {
        $model = RecoveryForRepaymentAmounts::find();


        if($object=='representative'){
            $model = $model->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ]);
        } else{
            $model = $model->where([
                'representative_id'=>new ObjectId($representative_id),
                'warehouse_id'=>[
                    '$nin' => [null]
                ]
            ]);
        }
        $model = $model->all();

        return $this->render('recovery-for-repayment',[
            'model' => $model,
            'object' => $object,
            'representative_id'=>$representative_id,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * add recovery
     * @param $object
     * @param string $representative_id
     * @return string
     */
    public function actionAddRecoveryForRepayment($object,$representative_id='')
    {
        $lastMonth = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));

        if($object == 'representative'){
            $model = RecoveryForRepaymentAmounts::find()
                ->where([
                    'month_recovery'=>$lastMonth,
                    'warehouse_id'=>[
                        '$in' => [null]
                    ]
                ])
                ->all();
        } else {
            $model = RecoveryForRepaymentAmounts::find()
                ->where([
                    'month_recovery'=>$lastMonth,
                    'representative_id'=>new ObjectId($representative_id),
                    'warehouse_id'=>[
                        '$nin' => [null]
                    ]
                ])
                ->all();
        }

        $error_message = '';
        if(!empty($model)){
            $error_message = 'Данные уже были внесены за период';
        }
        return $this->renderAjax('_add-recovery-for-repayment', [
            'language' => Yii::$app->language,
            'object' => $object,
            'representative_id' => $representative_id,
            'lastMonth' => $lastMonth,
            'error_message' => $error_message
        ]);
    }

    /**
     * save recovery
     * @return \yii\web\Response
     */
    public function actionSaveRecoveryForRepayment()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request['recovery_amount'])) {

            foreach ($request['recovery_amount'] as $k => $item) {
                $model = new RecoveryForRepaymentAmounts();

                $model->month_recovery = $request['month_recovery'];

                if ($request['object'] == 'representative') {
                    $model->representative_id = (!empty($request['representative'][$k]) ? new ObjectId($request['representative'][$k]) : '');

                    $forRedirect = '?object='.$request['object'];
                } else {
                    $model->representative_id = (!empty($request['representative_id']) ? new ObjectId($request['representative_id']) : '');
                    $model->warehouse_id = (!empty($request['warehouse'][$k]) ? new ObjectId($request['warehouse'][$k]) : '');

                    $forRedirect = '?object='.$request['object'].'&representative_id='.$request['representative_id'];
                }

                $model->recovery = (float)$item;
                $model->comment = (!empty($request['comment'][$k]) ? $request['comment'][$k] : '');

                if ($model->save()) {

                }
            }

            Yii::$app->session->setFlash('alert', [
                    'typeAlert' => 'success',
                    'message' => 'Сохранения применились.'
                ]
            );
        }


        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/recovery-for-repayment'.$forRedirect);

    }

    /**
     * list Repayment Amounts
     * @return string
     */
    public function actionRepaymentAmounts()
    {
        $model = RepaymentAmounts::find()
            ->where(['!=', 'warehouse_id', new ObjectId('5a056671dca7873e022be781')])
            ->andWhere(['!=', 'warehouse_id', new ObjectId('592426f6dca7872e64095b45')])
            ->all();

        return $this->render('repayment-amounts', [
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * list repayment for representative
     * @return string
     */
    public function actionListRepaymentRepresentative()
    {
        $info = [];

        $request = Yii::$app->request->post();

        if (empty($request)) {
            $request['date_repayment'] =  date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        }

        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date_repayment']])
            ->all();
        if(!empty($modelRepayment)){

            $repayment_paid = true;

            foreach ($modelRepayment as $item) {
                $info[(string)$item->representative_id] = [
                    'title' => $item->representative->username,
                    'amount_repayment' => $item->accrued,
                    'deduction' => $item->deduction
                ];
            }
        } else {

            $repayment_paid = false;

            // get repayment amount
            $modelRepaymentAmount = RepaymentAmounts::find()->all();
            if (!empty($modelRepaymentAmount)) {
                foreach ($modelRepaymentAmount as $item) {
                    if (empty($info[(string)$item->warehouse->headUser])) {
                        $info[(string)$item->warehouse->headUser] = [
                            'title' => $item->warehouse->infoHeadUser->username,
                            'amount_repayment' => 0,
                            'deduction' => 0
                        ];
                    }

                    if (!empty($item->prices_representative[$request['date_repayment']])) {
                        $info[(string)$item->warehouse->headUser]['amount_repayment'] += $item->prices_representative[$request['date_repayment']]['price'];
                    }
                }
            }

            // get deduction
            $modeDeduction = RecoveryForRepaymentAmounts::find()
                ->where([
                    'warehouse_id' => [
                        '$in' => [null]
                    ]
                ])
                ->andWhere(['month_recovery' => $request['date_repayment']])
                ->all();

            if (!empty($modeDeduction)) {
                foreach ($modeDeduction as $item) {
                    $info[(string)$item->representative_id]['deduction'] = $item->recovery;
                }
            } else {
                header('Content-Type: text/html; charset=utf-8');
                echo '<xmp>';
                print_r('Заполните удержаиня');
                echo '</xmp>';
                die();
            }
        }


        return $this->render('list-repayment-representative', [
            'language' => Yii::$app->language,
            'request' => $request,
            'info' => $info,
            'repayment_paid'=>$repayment_paid,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionMakeRepaymentRepresentative($dateRepayment)
    {
        //TODO:KAA check issue repayment for $dateRepayment
        if(Repayment::checkRepayment($dateRepayment,'representative')){
            header('Content-Type: text/html; charset=utf-8');
            echo '<xmp>';
            print_r('Выплаты была произведена!');
            echo '</xmp>';
            die();
        }


        // get repayment amount
        $modelRepaymentAmount = RepaymentAmounts::find()->all();
        if(!empty($modelRepaymentAmount)){
            foreach ($modelRepaymentAmount as $item) {
                if(empty($info[(string)$item->warehouse->headUser])){
                    $info[(string)$item->warehouse->headUser] = [
                        'title' => $item->warehouse->infoHeadUser->username,
                        'amount_repayment' => 0,
                        'deduction' => 0
                    ];
                }

                if(!empty($item->prices_representative[$dateRepayment])){
                    $info[(string)$item->warehouse->headUser]['amount_repayment'] += $item->prices_representative[$dateRepayment]['price'];
                }
            }
        }

        // get deduction
        $modeDeduction = RecoveryForRepaymentAmounts::find()
            ->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ])
            ->andWhere(['month_recovery'=>$dateRepayment])
            ->all();

        if(!empty($modeDeduction)){
            foreach ($modeDeduction as $item) {
                $info[(string)$item->representative_id]['deduction'] = $item->recovery;
            }
        }

        foreach ($info as $k=>$item){

            $repayment = $item['amount_repayment']-$item['deduction'];

            Charity::transferMoney('573a0d76965dd0fb16f60bfe',$k,$repayment,'repayment for representative');

            $model = new Repayment();

            $model->representative_id = new ObjectID($k);
            $model->accrued = $item['amount_repayment'];
            $model->deduction = $item['deduction'];
            $model->repayment = $repayment;
            $model->comment = 'repayment for representative';
            $model->date_for_repayment = $dateRepayment;
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if($model->save()){

            }
        }

        return $this->redirect('list-repayment-representative',301);
    }



    public function actionCalculationRepayment($look='')
    {

        $info = [];

        $listGoodsWithTitle = PartsAccessories::getListPartsAccessoriesForSaLe();

        $listGoodsWithPriceForPack = Products::getGoodsPriceForPack();

        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();

        $infoPercentForRepayment = [];
        $modelPercentForRepayment = PercentForRepaymentAmounts::find()
            ->where([
                'representative_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->all();
        if(!empty($modelPercentForRepayment)){
            foreach ($modelPercentForRepayment as $item) {
                $infoPercentForRepayment[(string)$item->representative_id] = [
                    'dop_price_per_warehouse' => $item->dop_price_per_warehouse,
                    'turnover_boundary' => $item->turnover_boundary
                ];
            }
        }

        $listRepresentativeForWarehouse = [];
        $infoWarehouse = Warehouse::find()->all();
        if (!empty($infoWarehouse)) {
            foreach ($infoWarehouse as $item) {

                // get list warehouse for representative
                $listRepresentativeForWarehouse[(string)$item->_id] = (!empty($item->headUser) ? (string)$item->headUser : '');

                // calculate count warehouses
                if (empty($info[(string)$item->headUser]['warehouses'][(string)$item->_id])) {
                    $info[(string)$item->headUser]['warehouses'][(string)$item->_id] = [
                        'packs' => 0,
                        'other_sale' => 0,
                        'listProducts' => [
                            '59620f49dca78761ae2d01c1' => 0,
                            '59620f57dca78747631d3c62' => 0,
                            '5975afe2dca78748ce5e7e02' => 0
                        ],
                        'numberProducts' => [
                            '59620f49dca78761ae2d01c1' => 0,
                            '59620f57dca78747631d3c62' => 0,
                            '5975afe2dca78748ce5e7e02' => 0
                        ]
                    ];
                }

                if(empty($info[(string)$item->headUser]['listProducts'])){
                    $info[(string)$item->headUser]['listProducts'] = [
                        '59620f49dca78761ae2d01c1' => 0,
                        '59620f57dca78747631d3c62' => 0,
                        '5975afe2dca78748ce5e7e02' => 0
                    ];
                    $info[(string)$item->headUser]['listOrderId'] = [];
                }
            }

            // calculate dop repayment
            foreach ($info as $kHeadAdmin => $item) {
                $countWarehouse = count($item['warehouses']);
                if ($countWarehouse > 5) {
                    $info[$kHeadAdmin]['dopGoodsTurnover'] = ($countWarehouse - 5) * $infoPercentForRepayment[$kHeadAdmin]['dop_price_per_warehouse'];
                } else {
                    $info[$kHeadAdmin]['dopGoodsTurnover'] = 0;
                }

                $info[$kHeadAdmin]['totalAmount'] = 0;
            }
        }

        $lastDate = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        $lastDate = explode('-', $lastDate);
        $countDay = cal_days_in_month(CAL_GREGORIAN, $lastDate['1'], $lastDate['0']);

        $calculationData = implode('-', $lastDate);
        $dateFrom = strtotime(implode($lastDate, '-') . '-01');
        $dateTo = strtotime(implode($lastDate, '-') . '-' . $countDay);


        // get info sale packs
        $model = StatusSales::find()
            ->where([
                'buy_for_money' => [
                    '$ne' => 1
                ]
            ])
            ->andWhere([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime($dateFrom * 1000),
                    '$lte' => new UTCDateTime($dateTo * 1000)
                ]
            ])
            ->all();

        if (!empty($model)) {
            foreach ($model as $item) {

                if (!empty($item->setSales) && $item->sales->type != -1) {
                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));

                        $warehouseId = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');
                        $representativeId = (!empty($listRepresentativeForWarehouse[$warehouseId]) ? $listRepresentativeForWarehouse[$warehouseId] : '');

                        if ($dateChange >= $dateFrom && $dateChange <= $dateTo && $itemSet['status'] == 'status_sale_issued' && !empty($representativeId)) {
                            if (!empty($info[$representativeId]['warehouses'][$warehouseId])) {

                                // check calculation only one for order
                                if(!in_array((string)$item->idSale,$info[$representativeId]['listOrderId'])){
                                    $info[$representativeId]['warehouses'][$warehouseId]['packs'] += $item->sales->price;
                                    $info[$representativeId]['totalAmount'] += $item->sales->price;
                                    $info[$representativeId]['listOrderId'][] = (string)$item->idSale;
                                }


                                $productID = array_search($itemSet['title'],$listGoodsWithTitle);
                                $info[$representativeId]['warehouses'][$warehouseId]['listProducts'][$productID] += $listGoodsWithPriceForPack[$item->sales->product][$productID];
                                $info[$representativeId]['warehouses'][$warehouseId]['numberProducts'][$productID]++;
                                $info[$representativeId]['listProducts'][$productID] += $listGoodsWithPriceForPack[$item->sales->product][$productID];

                            }
                        }
                    }
                }

            }
        }
        unset($model);

        // get info sale with out pack vipcoin and other refill
        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime($dateFrom * 1000),
                    '$lte' => new UTCDateTime($dateTo * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne' => -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [9, 10,3,4,7,8]
                ]
            ])
            ->all();

        if (!empty($model)) {
            foreach ($model as $item) {

                $country = $item->infoUser->country;
                $city = $item->infoUser->city;

                if(!empty($country) && !empty($city)){

                    $checkWarehouse = Warehouse::findOne(['country'=>$country,'cities'=>$city]);
                    if(!empty($checkWarehouse)){
                        $info[$listRepresentativeForWarehouse[(string)$checkWarehouse->_id]]['warehouses'][(string)$checkWarehouse->_id]['other_sale'] += $item->price;
                        $info[$listRepresentativeForWarehouse[(string)$checkWarehouse->_id]]['totalAmount'] += $item->price;
                    }
                }
            }
        }

        // calculation percent for representative
        if (!empty($info)) {
            foreach ($info as $k => $item) {
                foreach ($infoPercentForRepayment[$k]['turnover_boundary'] as $kPercent=>$itemPercent) {
                    if($itemPercent['turnover_boundary']<=$item['totalAmount']
                        && !empty($infoPercentForRepayment[$k]['turnover_boundary'][($kPercent+1)])
                        && $infoPercentForRepayment[$k]['turnover_boundary'][($kPercent+1)]['turnover_boundary']>$item['totalAmount']){

                        $percent_representative = $itemPercent['percent'];
                        break;
                    } elseif ($itemPercent['turnover_boundary']<=$item['totalAmount']
                        && empty($infoPercentForRepayment[$k]['turnover_boundary'][($kPercent+1)]['turnover_boundary'])){

                        $percent_representative = $itemPercent['percent'];
                        break;
                    }
                }
                $info[$k]['percent_representative'] = $percent_representative;


                foreach ($item['warehouses'] as $warehouseId => $warehouse) {

                    $turnoverWarehouse = $warehouse['packs'] + $warehouse['other_sale'];
                    $percent_warehouse = $this->calculationPercentWarehouse($warehouseId,$turnoverWarehouse);
                    $info[$k]['warehouses'][$warehouseId]['percent_warehouse'] = $percent_warehouse;

                    foreach ($warehouse['listProducts'] as $goodsId => $goodsPrice) {

                        $modelWarehouse = RepaymentAmounts::findOne([
                            'warehouse_id' => new ObjectId($warehouseId),
                            'product_id' => new ObjectId($goodsId),
                        ]);

                        if (empty($modelWarehouse)) {
                            $modelWarehouse = new RepaymentAmounts();
                            $modelWarehouse->warehouse_id = new ObjectId($warehouseId);
                            $modelWarehouse->product_id = new ObjectId($goodsId);
                            $modelWarehouse->prices_warehouse = [];
                            $modelWarehouse->prices_representative = [];
                        }

                        /* for representative */
                        $arrayPrices = $modelWarehouse->prices_representative;
                        $arrayPrices[$calculationData]['percent'] = $percent_representative;
                        $arrayPrices[$calculationData]['price'] = (float)round($goodsPrice / 100 * $percent_representative, 2);
                        $arrayPrices[$calculationData]['count'] = $warehouse['numberProducts'][$goodsId];
                        $arrayPrices[$calculationData]['goods_turnover'] = $item['totalAmount'];
                        $modelWarehouse->prices_representative = $arrayPrices;

                        /* for warehouse */
                        $arrayPrices = $modelWarehouse->prices_warehouse;
                        $arrayPrices[$calculationData]['percent'] = $percent_warehouse;
                        $arrayPrices[$calculationData]['price'] = (float)round($goodsPrice / 100 * $percent_warehouse, 2);
                        $arrayPrices[$calculationData]['count'] = $warehouse['numberProducts'][$goodsId];
                        $arrayPrices[$calculationData]['goods_turnover'] = $turnoverWarehouse;
                        $modelWarehouse->prices_warehouse = $arrayPrices;

                        if(empty($look)){
                            if($modelWarehouse->save()) {}
                        }
                    }


                }
            }
        }

        if($look==1){
            header('Content-Type: text/html; charset=utf-8');
            echo '<xmp>';
            print_r($info);
            echo '</xmp>';
            die();
        } else {
            return $this->redirect('repayment-amounts',301);
        }
    }

    /**
     * clear and update structure table
     * @throws \yii\mongodb\Exception
     */
    public function actionClearTableForRepayment()
    {
        // Remove field in table
        RepaymentAmounts::getCollection()->update(
            [],
            ['$unset' => ['price' => 1, 'price_representative' => 1]],
            ['multi' => true]
        );

        // Clear field in table
        RepaymentAmounts::getCollection()->update(
            [],
            ['$set' => ['prices_warehouse' => (array)[], 'prices_representative' => (array)[]]],
            ['multi' => true]
        );

        Repayment::getCollection()->remove();

        header('Content-Type: text/html; charset=utf-8');
        echo '<xmp>';
        print_r('all clear');
        echo '</xmp>';
        die();
    }



    /**
     * get amount repayment
     * @param $object
     * @param $id
     * @return int|mixed
     */
    protected function getDifferenceRepaymentNow($object, $id)
    {
        $repayment = 0;

        if ($object == 'representative') {
            $arrayWarehouse = Warehouse::getListHeadAdminWarehouseId($id);
            $directionRepayment = 'company';
        } else {
            $arrayWarehouse = [$id];
            $directionRepayment = 'representative';
        }


        $info = [
            'amount_repayment_for_' . $directionRepayment => 0,
            'amount_repayment_for_' . $object => 0,
        ];

        $infoGoodsInProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();

        /** buy for money */
        $model = StatusSales::find()->where(['buy_for_money' => 1])->all();
        if (!empty($model)) {

            foreach ($model as $item) {
                $productSetId = (!empty($item->sales->product) ? $item->sales->product : '???');

                $warehouseId = (!empty($infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id'] : 'none');

                if ($item->sales->type != -1 && in_array($warehouseId, $arrayWarehouse)) {
                    $amountRepayment = RepaymentAmounts::CalculateRepaymentSet($object, $warehouseId, $productSetId, $item->sales->dateCreate);
                    $info['amount_repayment_for_' . $directionRepayment] += $amountRepayment;
                }
            }
        }

        /** buy for prepayment */
        $model = StatusSales::find()
            ->where([
                'buy_for_money' => [
                    '$ne' => 1
                ]
            ])
            ->all();
        if (!empty($model)) {
            foreach ($model as $item) {

                if (!empty($item->setSales) && $item->sales->type != -1) {

                    foreach ($item->setSales as $itemSet) {
                        $warehouseId = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');

                        if ($itemSet['status'] == 'status_sale_issued' && in_array($warehouseId, $arrayWarehouse)) {
                            $productId = array_search($itemSet['title'], $infoGoodsInProduct);
                            $amountRepayment = RepaymentAmounts::CalculateRepaymentGoods($object, $warehouseId, $productId, $itemSet['dateChange']);
                            $info['amount_repayment_for_' . $object] += $amountRepayment;
                        }
                    }
                }
            }
        }

        $repaymentCompanyWarehouse = Repayment::getRepayment($object, $id, $directionRepayment . '_' . $object);
        $repaymentWarehouseCompany = Repayment::getRepayment($object, $id, $object . '_' . $directionRepayment);

        $repayment = $info['amount_repayment_for_' . $directionRepayment] - $repaymentWarehouseCompany - $info['amount_repayment_for_' . $object] + $repaymentCompanyWarehouse;

        return $repayment;
    }


    protected function calculationPercentWarehouse($warehpuseId,$turnoverWarehouse)
    {
        $percent = 0;

        $tablePercent = PercentForRepaymentAmounts::findOne(['warehouse_id'=>new ObjectID($warehpuseId)]);

        foreach ($tablePercent->turnover_boundary as $kPercent=>$itemPercent) {

            if($itemPercent['turnover_boundary']<=$turnoverWarehouse
                && !empty($tablePercent->turnover_boundary[($kPercent+1)])
                && $tablePercent->turnover_boundary[($kPercent+1)]['turnover_boundary']>$turnoverWarehouse){

                $percent = $itemPercent['percent'];
                break;
            } elseif ($itemPercent['turnover_boundary']<=$turnoverWarehouse
                && empty($tablePercent->turnover_boundary[($kPercent+1)]['turnover_boundary'])){

                $percent = $itemPercent['percent'];
                break;
            }
        }

        return $percent;
    }
}