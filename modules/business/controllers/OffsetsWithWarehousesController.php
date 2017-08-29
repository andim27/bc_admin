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
        $myWarehouseId = Warehouse::getIdMyWarehouse();

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['flWarehouse'] = '1';
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-01-01");
            $request['listCountry']='';
            $request['listWarehouse'] = '';
        }



        if($myWarehouseId != '592426f6dca7872e64095b45'){
            $request['listWarehouse']=$myWarehouseId;
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
                    && (empty($request['listWarehouse']) || $request['listWarehouse']==$warehouseId)
                    && (empty($request['listCountry']) || $request['listCountry']==$countryCode)) {



                    $amountRepayment = RepaymentAmounts::CalculateRepaymentSet('warehouse',$warehouseId,$productSetId);

                    if(empty($info[$countryCode][$warehouseId])){
                        //TODO:KAA  персонализировать откуда идут зачисления
                        $repaymentCompanyWarehouse = Repayment::getRepayment('warehouse',$warehouseId,'company_warehouse',$request['from'],$request['to']);
                        $repaymentWarehouseCompany = Repayment::getRepayment('warehouse',$warehouseId,'warehouse_company',$request['from'],$request['to']);

                        $info[$countryCode][$warehouseId] = [
                            'number_buy_cash'                   => 0,
                            'number_buy_prepayment'             => 0,
                            'amount_for_the_device'             => 0,
                            'amount_repayment_for_company'      => 0,
                            'amount_repayment_for_warehouse'    => 0,
                            'repayment'                         => ($repaymentCompanyWarehouse-$repaymentWarehouseCompany),
                        ];
                    }


                    $info[$countryCode][$warehouseId]['number_buy_cash']++;
                    $info[$countryCode][$warehouseId]['amount_for_the_device'] += $item->sales->price;
                    $info[$countryCode][$warehouseId]['amount_repayment_for_company'] += $amountRepayment;

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
                if(!empty($item->setSales)  && $item->sales->type != -1){
                    $countTemp = [];

                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));
                        $countryCode    = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['country']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['country'] : 'none');
                        $warehouseId    = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');

                        $listCountry[$countryCode] = (!empty($listAllCountry[$countryCode]) ? $listAllCountry[$countryCode]: $countryCode );

                        if ($dateChange >= $from && $dateChange <= $to && $itemSet['status'] == 'status_sale_issued'
                            && (empty($request['listWarehouse']) || $request['listWarehouse']==$warehouseId)
                            && (empty($request['listCountry']) || $request['listCountry']==$countryCode)) {

                            $productId  = array_search($itemSet['title'],$infoGoodsInProduct);

                            $amountRepayment = RepaymentAmounts::CalculateRepaymentGoods('warehouse',$warehouseId,$productId);

                            if(empty($info[$countryCode][$warehouseId])){
                                //TODO:KAA  персонализировать откуда идут зачисления
                                $repaymentCompanyWarehouse = Repayment::getRepayment('warehouse',$warehouseId,'company_warehouse',$request['from'],$request['to']);
                                $repaymentWarehouseCompany = Repayment::getRepayment('warehouse',$warehouseId,'warehouse_company',$request['from'],$request['to']);

                                $info[$countryCode][$warehouseId] = [
                                    'number_buy_cash'                   => 0,
                                    'number_buy_prepayment'             => 0,
                                    'amount_for_the_device'             => 0,
                                    'amount_repayment_for_company'      => 0,
                                    'amount_repayment_for_warehouse'    => 0,
                                    'repayment'                         => ($repaymentCompanyWarehouse-$repaymentWarehouseCompany),
                                ];
                            }

                            $countTemp[$countryCode][$warehouseId] = 1;

                            $info[$countryCode][$warehouseId]['amount_for_the_device'] += $item->sales->price;
                            $info[$countryCode][$warehouseId]['amount_repayment_for_warehouse'] += $amountRepayment;

                        }
                    }

                    if(!empty($countTemp)){
                        foreach ($countTemp as $kTemp=>$itemWTemp){
                            foreach ($itemWTemp as $kWTemp=>$itemTemp){
                                $info[$kTemp][$kWTemp]['number_buy_prepayment'] += $itemTemp;
                            }
                        }
                    }

                }

            }
        }

        return $this->render('offsets-with-warehouses',[
            'language'          => Yii::$app->language,
            'request'           => $request,
            'info'              => $info,
            'listCountry'       => $listCountry,
        ]);
    }

    public function actionOffsetsWithGoods()
    {
        $infoGoodsInProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();

        $request =  Yii::$app->request->post();

        $from = strtotime($request['from']);
        $to = strtotime($request['to'] . ' 23:59:59');

        $info = [];

        if($request['object'] == 'warehouse'){
            $arrayWarehouse = [$request['id']];
            
        } else {
            $arrayWarehouse = Warehouse::getListHeadAdminWarehouseId($request['id']);
        }

        /** buy for money */
        $model = StatusSales::find()->where(['buy_for_money'=>1])->all();
        if(!empty($model)){

            foreach ($model as $item) {
                $dateCreate = strtotime($item->sales->dateCreate->toDateTime()->format('Y-m-d'));
                $productSetId = (!empty($item->sales->product) ? $item->sales->product : '???');
                $warehouseId = (!empty($infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id'] : 'none');

                if ($dateCreate >= $from && $dateCreate <= $to && $item->sales->type != -1
                    && in_array($warehouseId,$arrayWarehouse)) {

                    $amountRepayment = RepaymentAmounts::CalculateRepaymentSet($request['object'],$warehouseId,$productSetId);
                    
                    // info item pack
                    if(empty($info[$productSetId])){
                        $info[$productSetId] = [
                            'number_buy_cash'                   => 0,
                            'number_buy_prepayment'             => 0,
                            'amount_for_the_device'             => 0,
                            'amount_repayment_for_company'      => 0,
                            'amount_repayment_for_warehouse'    => 0,
                        ];
                    }
                    $info[$productSetId]['number_buy_cash']++;
                    $info[$productSetId]['amount_for_the_device'] += $item->sales->price;
                    $info[$productSetId]['amount_repayment_for_company'] += $amountRepayment;

                    // info item pack
                    foreach ($item->setSales as $itemSet) {
                        $productId  = array_search($itemSet['title'],$infoGoodsInProduct);
                        if(empty($info[$productSetId]['set'][$productId])){
                            $info[$productSetId]['set'][$productId] = [
                                'number_buy_cash'                   => 0,
                                'number_buy_prepayment'             => 0,
                                'amount_for_the_device'             => 0,
                                'amount_repayment_for_company'      => '-',
                                'amount_repayment_for_warehouse'    => 0,
                            ];
                        }
                        $info[$productSetId]['set'][$productId]['amount_for_the_device'] += $item->sales->price;
                        $info[$productSetId]['set'][$productId]['number_buy_cash']++;
                    }


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

                if(!empty($item->setSales)  && $item->sales->type != -1){
                    $countTemp=0;
                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));
                        $warehouseId    = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');

                        if ($dateChange >= $from && $dateChange <= $to && $itemSet['status'] == 'status_sale_issued'
                            && in_array($warehouseId,$arrayWarehouse)) {

                            $productId  = array_search($itemSet['title'],$infoGoodsInProduct);

                            $amountRepayment = RepaymentAmounts::CalculateRepaymentGoods($request['object'],$warehouseId,$productId);
                            
                            // info pack
                            if(empty($info[$productSetId])){
                                $info[$productSetId] = [
                                    'number_buy_cash'                   => 0,
                                    'number_buy_prepayment'             => 0,
                                    'amount_for_the_device'             => 0,
                                    'amount_repayment_for_company'      => 0,
                                    'amount_repayment_for_warehouse'    => 0,
                                ];
                            }

                            $countTemp=1;
                            $info[$productSetId]['amount_for_the_device'] += $item->sales->price;
                            $info[$productSetId]['amount_repayment_for_warehouse'] += $amountRepayment;
                            
                            
                            // info item pack
                            if(empty($info[$productSetId]['set'][$productId])){
                                $info[$productSetId]['set'][$productId] = [
                                    'number_buy_cash'                   => 0,
                                    'number_buy_prepayment'             => 0,
                                    'amount_for_the_device'             => 0,
                                    'amount_repayment_for_company'      => '-',
                                    'amount_repayment_for_warehouse'    => 0,
                                ];
                            }
                            $info[$productSetId]['set'][$productId]['amount_for_the_device'] += $item->sales->price;
                            $info[$productSetId]['set'][$productId]['amount_repayment_for_warehouse'] += $amountRepayment;
                            $info[$productSetId]['set'][$productId]['number_buy_prepayment']++;

                        }
                    }

                    if($countTemp==1){
                        $info[$productSetId]['number_buy_prepayment'] += $countTemp;
                    }
                }

            }
        }


        return $this->renderPartial('_offsets-with-goods',[
            'info' => $info,
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
                $infoProduct[(string)$item->product_id]['price'] = $item->price;
                $infoProduct[(string)$item->product_id]['price_representative'] = $item->price_representative;
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
                $model->price = (float)$request['price'][$k];
                $model->price_representative = (float)$request['price_representative'][$k];

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
    
    
    public function actionRepayment($object,$id)
    {
        if($object == 'representative'){
            $field = 'representative_id';
        } else {
            $field = 'warehouse_id';
        }

        $model = Repayment::find()
            ->where([$field=>new ObjectID($id)])
            ->all();

        $differenceRepaymentNow = $this->getDifferenceRepaymentNow($object,$id);

        return $this->render('repayment',[
            'id'                            => $id,
            'object'                        => $object,
            'field'                         => $field,
            'model'                         => $model,
            'differenceRepaymentNow'        => $differenceRepaymentNow,
            'alert'                         => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionAddRepayment($object,$id)
    {
        return $this->renderAjax('_add-repayment', [
            'language'      => Yii::$app->language,
            'object'  => $object,
            'id'  => $id
        ]);
    }

    public function  actionSaveRepayment()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){

            $model = new Repayment();

            $model->repayment = (float)$request['price'];
            $model->type_repayment = $request['type_repayment'];
            $model->method_repayment = $request['method_repayment'];
            $model->date_create = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            if(!empty($request['representative_id'])){
                $model->difference_repayment = (int)$this->getDifferenceRepaymentNow('representative',$request['representative_id']);
                $model->warehouse_id = '';
                $model->representative_id = new ObjectID($request['representative_id']);
                $object = 'representative';
                $id = $request['representative_id'];
            } else {
                $model->difference_repayment = (int)$this->getDifferenceRepaymentNow('warehouse',$request['warehouse_id']);
                $model->warehouse_id = new ObjectID($request['warehouse_id']);
                $model->representative_id = '';
                $object = 'warehouse';
                $id = $request['warehouse_id'];
            }

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );

                return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/repayment?object='.$object.'&id=' . $id);
            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/offsets-with-warehouses/repayment-amounts');
    }

    protected function getDifferenceRepaymentNow($object,$id)
    {
        $repayment = 0;

        $info = [
            'amount_repayment_for_company'      => 0,
            'amount_repayment_for_warehouse'    => 0,
        ];

        $infoGoodsInProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();

        if($object=='representative'){
            $arrayWarehouse = Warehouse::getListHeadAdminWarehouseId($id);
        } else {
            $arrayWarehouse = [$id];
        }

        /** buy for money */
        $model = StatusSales::find()->where(['buy_for_money'=>1])->all();
        if(!empty($model)){

            foreach ($model as $item) {
                $productSetId = (!empty($item->sales->product) ? $item->sales->product : '???');

                $warehouseId = (!empty($infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id'] : 'none');

                if($item->sales->type != -1 && in_array($warehouseId,$arrayWarehouse)) {
                    $amountRepayment = RepaymentAmounts::CalculateRepaymentSet($object,$warehouseId,$productSetId);
                    $info['amount_repayment_for_company'] += $amountRepayment;
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
            ->all();
        if(!empty($model)){
            foreach ($model as $item) {

                if(!empty($item->setSales)  && $item->sales->type != -1){

                    foreach ($item->setSales as $itemSet) {
                        $warehouseId    = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');

                        if ($itemSet['status'] == 'status_sale_issued' && in_array($warehouseId,$arrayWarehouse)) {
                            $productId  = array_search($itemSet['title'],$infoGoodsInProduct);
                            $amountRepayment = RepaymentAmounts::CalculateRepaymentGoods($object,$warehouseId,$productId);
                            $info['amount_repayment_for_warehouse'] += $amountRepayment;
                        }
                    }
                }
            }

            $repaymentCompanyWarehouse = Repayment::getRepayment($object,$id,'company_'.$object);
            $repaymentWarehouseCompany = Repayment::getRepayment($object,$id,$object.'_company');

            $repayment = $info['amount_repayment_for_company']-$repaymentWarehouseCompany-$info['amount_repayment_for_warehouse']+$repaymentCompanyWarehouse;
        }

        return $repayment;
    }





    public function actionOffsetsWithRepresentative()
    {
        $infoUserWarehouseCountry = Warehouse::getArrayAdminWithWarehouseCountry();
        $infoGoodsInProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
        $listRepresentativeForWarehouse = [];
        $infoWarehouse = Warehouse::find()->all();
        if(!empty($infoWarehouse)){
            foreach ($infoWarehouse as $item){
                $listRepresentativeForWarehouse[(string)$item->_id] = (!empty($item->headUser) ? (string)$item->headUser : '');
            }
        }

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-01-01");
            $request['listRepresentative']='';
        }


        $from = strtotime($request['from']);
        $to = strtotime($request['to'] . ' 23:59:59');

        $info = [];

        /** buy for money */
        $model = StatusSales::find()->where(['buy_for_money'=>1])->all();
        if(!empty($model)){
            foreach ($model as $item) {
                $dateCreate = strtotime($item->sales->dateCreate->toDateTime()->format('Y-m-d'));
                $productSetId = (!empty($item->sales->product) ? $item->sales->product : '???');

                $warehouseId = (!empty($infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$item->sales->warehouseId]['warehouse_id'] : 'none');
                $representativeId = (!empty($listRepresentativeForWarehouse[$warehouseId]) ? $listRepresentativeForWarehouse[$warehouseId] : '');

                if ($dateCreate >= $from && $dateCreate <= $to && $item->sales->type != -1
                    && !empty($representativeId) && (empty($request['listRepresentative']) || $request['listRepresentative']==$representativeId)) {

                    $amountRepayment = RepaymentAmounts::CalculateRepaymentSet('representative',$warehouseId,$productSetId);

                    if(empty($info[$representativeId])){
                        //TODO:KAA  персонализировать откуда идут зачисления
                        $repaymentCompanyWarehouse = Repayment::getRepayment('representative',$representativeId,'company_representative',$request['from'],$request['to']);
                        $repaymentWarehouseCompany = Repayment::getRepayment('representative',$representativeId,'representative_company',$request['from'],$request['to']);

                        $info[$representativeId] = [
                            'number_buy_cash'                   => 0,
                            'number_buy_prepayment'             => 0,
                            'amount_for_the_device'             => 0,
                            'amount_repayment_for_company'      => 0,
                            'amount_repayment_for_warehouse'    => 0,
                            'repayment'                         => ($repaymentCompanyWarehouse-$repaymentWarehouseCompany),
                        ];
                    }


                    $info[$representativeId]['number_buy_cash']++;
                    $info[$representativeId]['amount_for_the_device'] += $item->sales->price;
                    $info[$representativeId]['amount_repayment_for_company'] += $amountRepayment;

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
                if(!empty($item->setSales)  && $item->sales->type != -1){
                    $countTemp = [];

                    foreach ($item->setSales as $itemSet) {
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));

                        $warehouseId    = (!empty($infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id']) ? $infoUserWarehouseCountry[(string)$itemSet['idUserChange']]['warehouse_id'] : 'none');
                        $representativeId = (!empty($listRepresentativeForWarehouse[$warehouseId]) ? $listRepresentativeForWarehouse[$warehouseId] : '');

                        if ($dateChange >= $from && $dateChange <= $to && $itemSet['status'] == 'status_sale_issued'
                            && !empty($representativeId)  && (empty($request['listRepresentative']) || $request['listRepresentative']==$representativeId)) {

                            $productId  = array_search($itemSet['title'],$infoGoodsInProduct);

                            $amountRepayment = RepaymentAmounts::CalculateRepaymentGoods('representative',$warehouseId,$productId);

                            if(empty($info[$representativeId])){
                                //TODO:KAA  персонализировать откуда идут зачисления
                                $repaymentCompanyWarehouse = Repayment::getRepayment('representative',$representativeId,'company_representative',$request['from'],$request['to']);
                                $repaymentWarehouseCompany = Repayment::getRepayment('representative',$representativeId,'representative_company',$request['from'],$request['to']);

                                $info[$representativeId] = [
                                    'number_buy_cash'                   => 0,
                                    'number_buy_prepayment'             => 0,
                                    'amount_for_the_device'             => 0,
                                    'amount_repayment_for_company'      => 0,
                                    'amount_repayment_for_warehouse'    => 0,
                                    'repayment'                         => ($repaymentCompanyWarehouse-$repaymentWarehouseCompany),
                                ];
                            }

                            $countTemp[$representativeId] = 1;

                            $info[$representativeId]['amount_for_the_device'] += $item->sales->price;
                            $info[$representativeId]['amount_repayment_for_warehouse'] += $amountRepayment;

                        }
                    }

//                    if(!empty($countTemp)){
//                        foreach ($countTemp as $kTemp=>$itemWTemp){
//                            foreach ($itemWTemp as $kWTemp=>$itemTemp){
//                                $info[$kTemp][$kWTemp]['number_buy_prepayment'] += $itemTemp;
//                            }
//                        }
//                    }

                }

            }
        }

        return $this->render('offsets-with-representative',[
            'language'          => Yii::$app->language,
            'request'           => $request,
            'info'              => $info
        ]);
    }
//    public function actionRepaymentRepresentative($id)
//    {
//        $model = Repayment::find()
//            ->where(['warehouse_id'=>new ObjectID($id)])
//            ->all();
//
//        $differenceRepaymentNow = $this->getDifferenceRepaymentNow($id);
//
//        return $this->render('repayment',[
//            'id'                            => $id,
//            'model'                         => $model,
//            'differenceRepaymentNow'        => $differenceRepaymentNow,
//            'alert'                         => Yii::$app->session->getFlash('alert', '', true)
//        ]);
//    }


}