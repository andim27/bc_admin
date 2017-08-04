<?php

namespace app\modules\business\controllers;

use app\models\PartsAccessories;
use app\models\Products;
use app\models\Repayment;
use app\models\RepaymentAmounts;
use app\models\Sales;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;

class OffsetsWithWarehousesController extends BaseController {
    
    public function actionOffsetsWithWarehouses()
    {
        $listAllCountry = Settings::getListCountry();
        $infoGoodsInProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['flWarehouse'] = '1';
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-01-01");
            $request['listPack']='all';
            $request['listWarehouse']='all';
            $request['listCountry']='all';
        }

        $from = strtotime($request['from']);
        $to = strtotime($request['to'] . ' 23:59:59');

        $listCountry = [];
        $info = [];

        /** buy for money */
        $model = StatusSales::find()->where(['buy_for_money'=>1])->all();
        if(!empty($model)){
            
            foreach ($model as $item) {
                $dateCreate = strtotime($item->sales->dateCreate->toDateTime()->format('Y-m-d'));
                $productSetId = (!empty($item->sales->product) ? $item->sales->product : '???');
                $countryCode = (!empty($infoUserWarehouseCountry[(string)$item->sales->warehouseId]['country']) ? $infoUserWarehouseCountry[(string)$item->sales->warehouseId]['country'] : 'none');
                $warehouseId = (!empty($infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id'] : 'none');

                $listCountry[$countryCode] = (!empty($listAllCountry[$countryCode]) ? $listAllCountry[$countryCode]: $countryCode );

                if ($dateCreate >= $from && $dateCreate <= $to && $item->sales->type != -1
                    && ($request['listPack']=='all' || $request['listPack']==$productSetId)
                    && (empty($request['listWarehouse']) || $request['listWarehouse']=='all' || $request['listWarehouse']==$warehouseId)
                    && (empty($request['listCountry']) || $request['listCountry']=='all' || $request['listCountry']==$countryCode)) {



                    $amountRepayment = RepaymentAmounts::CalculateRepaymentSet($warehouseId,$productSetId);

                    if(empty($info[$countryCode][$warehouseId][$productSetId])){
                        $info[$countryCode][$warehouseId][$productSetId] = [
                            'number_buy_cash'                   => 0,
                            'number_buy_prepayment'             => 0,
                            'amount_for_the_device'             => 0,
                            'amount_repayment_for_company'      => 0,
                            'amount_repayment_for_warehouse'    => 0,
                        ];
                    }


                    $info[$countryCode][$warehouseId][$productSetId]['number_buy_cash']++;
                    $info[$countryCode][$warehouseId][$productSetId]['amount_for_the_device'] += $item->sales->price;
                    $info[$countryCode][$warehouseId][$productSetId]['amount_repayment_for_company'] += $amountRepayment;

                }
            }
        }

        /** buy for prepayment */
        $model = StatusSales::find()
            ->where([
                'buy_for_money'=>[
                    '$ne' => 1
                ]
            ])
            ->andWhere([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime($from * 1000),
                    '$lt' => new UTCDateTime($to * 1000)
                ]
            ])
            ->all();
        if(!empty($model)){
            foreach ($model as $item) {

                $productSetId   = (!empty($item->sales->product) ? $item->sales->product : '???');

                if(!empty($item->setSales)  && $item->sales->type != -1  && ($request['listPack']=='all' || $request['listPack']==$productSetId)){

                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));
                        $countryCode    = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['country']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['country'] : 'none');
                        $warehouseId    = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');

                        $listCountry[$countryCode] = (!empty($listAllCountry[$countryCode]) ? $listAllCountry[$countryCode]: $countryCode );

                        if ($dateChange >= $from && $dateChange <= $to && $itemSet['status'] == 'status_sale_issued'
                            && (empty($request['listWarehouse']) || $request['listWarehouse']=='all' || $request['listWarehouse']==$warehouseId)
                            && (empty($request['listCountry']) || $request['listCountry']=='all' || $request['listCountry']==$countryCode)) {

                            $productId  = array_search($itemSet['title'],$infoGoodsInProduct);

                            $amountRepayment = RepaymentAmounts::CalculateRepaymentGoods($warehouseId,$productId);

                            if(empty($info[$countryCode][$warehouseId][$productSetId])){
                                $info[$countryCode][$warehouseId][$productSetId] = [
                                    'number_buy_cash'                   => 0,
                                    'number_buy_prepayment'             => 0,
                                    'amount_for_the_device'             => 0,
                                    'amount_repayment_for_company'      => 0,
                                    'amount_repayment_for_warehouse'    => 0,
                                ];
                            }


                            $info[$countryCode][$warehouseId][$productSetId]['number_buy_cash']++;
                            $info[$countryCode][$warehouseId][$productSetId]['amount_for_the_device'] += $item->sales->price;
                            $info[$countryCode][$warehouseId][$productSetId]['amount_repayment_for_warehouse'] += $amountRepayment;

                        }
                    }
                }

            }
        }

//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($info);
//        echo "</xmp>";
//        die();
        
        return $this->render('offsets-with-warehouses',[
            'language'          => Yii::$app->language,
            'request'           => $request,
            'info'              => $info,
            'listCountry'       => $listCountry,
        ]);
    }


    /**
     * list Repayment Amounts
     * @return string
     */
    public function actionRepaymentAmounts()
    {
        $model = RepaymentAmounts::find()->all();
        
        return $this->render('repayment-amounts',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup for add or edit Repayment Amounts
     * @param string $id
     * @return string
     */
    public function actionAddUpdateRepaymentAmounts($id='')
    {
        $infoProduct = [];

        $useWarehouse = [];
        $model = RepaymentAmounts::find()->all();
        if(!empty($model)){
            foreach ($model as $item) {
                $useWarehouse[] = (string)$item->warehouse_id;
            }
        }
        
        if(!empty($id)){
            $model = RepaymentAmounts::find()->where(['warehouse_id'=>new ObjectID($id)])->all();

            foreach ($model as $item) {
                $infoProduct[(string)$item->product_id] = $item->price;
            }
        }

        return $this->renderAjax('_add-update-repayment-amounts', [
            'language' => Yii::$app->language,
            'infoProduct' => $infoProduct,
            'id' => $id,
            'useWarehouse' => $useWarehouse,
        ]);
    }

    /**
     * save change for Repayment Amounts
     * @return \yii\web\Response
     */
    public function actionSaveRepaymentAmounts()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request['warehouse_id'])){

            RepaymentAmounts::deleteAll(['warehouse_id'=>new ObjectID($request['warehouse_id'])]);

            foreach ($request['product_id'] as $k=>$v){
                $model = new RepaymentAmounts();
                $model->warehouse_id = new ObjectID($request['warehouse_id']);
                $model->product_id = new ObjectID($v);
                $model->price = $request['price'][$k];

                if($model->save()){}


            }

            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );


        }

        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/repayment-amounts');
    }

    /**
     * remove Repayment Amounts
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRemoveRepaymentAmounts($id)
    {
        if(RepaymentAmounts::deleteAll(['warehouse_id'=>new ObjectID($id)])){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Удаление прошло успешно.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/repayment-amounts');
    }
    
    
    public function actionRepayment($id)
    {
        $model = Repayment::find()->all();

        return $this->render('repayment',[
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }
}