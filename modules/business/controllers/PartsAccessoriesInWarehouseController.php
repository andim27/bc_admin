<?php

namespace app\modules\business\controllers;

use app\models\LogWarehouse;
use DateTime;
use app\models\PartsAccessoriesInWarehouse;
use app\models\SendingWaitingParcel;
use app\models\StatusSales;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class PartsAccessoriesInWarehouseController extends BaseController {

    /**
     * looking goods in warehouse
     * @return string
     */
    public function actionInWarehouse()
    {
        
        $request =  Yii::$app->request->post();

        $idWarehouse = Warehouse::getIdMyWarehouse();

        if(empty($request)){
            $request['dateInterval']['to'] = date("Y-m-d");
            $request['dateInterval']['from'] = date("Y-01-01");
        }

        if(empty($request['listWarehouse'])){
            $request['listWarehouse'] = $idWarehouse;
        }

        /** **** **/
        $infoWarehouse = Warehouse::find()->where(['_id'=> new ObjectID($request['listWarehouse'])])->one();
        $listAdmin = $listAdminObj = [];
        if(!empty($infoWarehouse->idUsers)){
            $listAdmin = $infoWarehouse->idUsers;
            foreach ($listAdmin as $item){
                $listAdminObj[] = new ObjectID($item);
            }
        }

        //TODO: replace goods title for goods id
        $modelChangeStatus = StatusSales::find()
            ->where([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime(strtotime($request['dateInterval']['from']) * 1000),
                    '$lt' => new UTCDateTime(strtotime($request['dateInterval']['to'] . '23:59:59') * 1000)
                ],
                'setSales.status' => 'status_sale_issued',
                'setSales.idUserChange' => [
                    '$in' => $listAdminObj
                ],
            ])
            ->all();

        $from = strtotime($request['dateInterval']['from']);
        $to = strtotime($request['dateInterval']['to']);

        $implementation=[];
        if(!empty($modelChangeStatus)){
            foreach ($modelChangeStatus as $item){

                if($item->sales->type != -1){
                    foreach ($item->setSales as $itemSet){
                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));

                        if(!empty($itemSet['idUserChange']) && in_array((string)$itemSet['idUserChange'],$listAdmin) && $dateChange>=$from && $dateChange<=$to && $itemSet['status']=='status_sale_issued'){
                            if(empty($implementation[$itemSet['title']])){
                                $implementation[$itemSet['title']] = 0;
                            }

                            $implementation[$itemSet['title']]++;

                        }
                    }
                }


            }
        }

        $model = '';
        if(!empty($request['listWarehouse'])){
            $model = PartsAccessoriesInWarehouse::find()
                ->where(['warehouse_id' => new ObjectID($request['listWarehouse'])])
                ->all();
        }

        $arrayProcurementPlanning=$this->procurementPlanning();
        
        return $this->render('in-warehouse',[
            'language'          => Yii::$app->language,
            'idWarehouse'       => $idWarehouse,
            'model'             => $model,
            'implementation'    => $implementation,
            'request'           => $request,
            'arrayProcurementPlanning' => $arrayProcurementPlanning,
            'alert'             => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * popup for cancellation goods
     * @return string
     */
    public function actionCancellation()
    {
        $request =  Yii::$app->request->get();
        if ($request['goodsID']){
            return $this->renderAjax('_cancellation', [
                'goodsID'   => $request['goodsID'],  
                'language'  => Yii::$app->language,
            ]);
        }
        
        return false;
    }

    /**
     * save cancellation
     * @return \yii\web\Response
     */
    public function actionSaveCancellation()
    {
        $request = Yii::$app->request->post();

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        if(!empty($request)){

            $myWarehouse = Warehouse::getIdMyWarehouse();

            $model = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'  =>  new ObjectID($request['parts_accessories_id']),
                'warehouse_id'          =>  new ObjectID($myWarehouse)
            ]);

            if(!empty($model->number) && $model->number < $request['number']){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'danger',
                        'message'=>'На складе меньше чем хотят списать!!!'
                    ]
                );

                return $this->redirect(['parts-accessories']);
            } else {
                $model->number -= $request['number'];
            }

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились!!!'
                    ]
                );

                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'cancellation',
                    'parts_accessories_id'      =>  $request['parts_accessories_id'],
                    'number'                    =>  $request['number'],

                    'comment'                   =>  $request['comment'],
                ]);

            }


        }

        return $this->redirect(['in-warehouse']);
    }

    /**
     * list cancellation for user`s warehouse
     * @return string
     */
    public function actionCancellationWarehouse(){
        $myWarehouse = Warehouse::getIdMyWarehouse();

        $request =  Yii::$app->request->post();

        if(!empty($request)){
            $dateInterval['to'] = $request['to'];
            $dateInterval['from'] =  $request['from'];
        } else {
            $dateInterval['to'] = date("Y-m-d");
            $dateInterval['from'] = date("Y-01-01");
        }

        $model = LogWarehouse::find()
            ->where(['action'=>'cancellation','admin_warehouse_id'=> new ObjectID($myWarehouse)])
            ->andWhere([
                'date_create' => [
                    '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->orderBy(['date_create'=>SORT_DESC])
            ->all();

        return $this->render('cancellation-warehouse',[
            'language' => Yii::$app->language,
            'model' => $model,
            'dateInterval' => $dateInterval,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    /**
     * list cancellation all warehouse
     * @return string
     */
    public function actionAllCancellationWarehouse(){

        $request =  Yii::$app->request->post();

        if(!empty($request)){
            $dateInterval['to'] = $request['to'];
            $dateInterval['from'] =  $request['from'];
        } else {
            $dateInterval['to'] = date("Y-m-d");
            $dateInterval['from'] = date("Y-01-01");
        }

        $model = LogWarehouse::find()
            ->where(['action'=>'cancellation'])
            ->andWhere([
                'date_create' => [
                    '$gte' => new UTCDatetime(strtotime($dateInterval['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateInterval['to'] . '23:59:59') * 1000)
                ]
            ])
            ->orderBy(['date_create'=>SORT_DESC])
            ->all();

        return $this->render('all-cancellation-warehouse',[
            'language' => Yii::$app->language,
            'model' => $model,
            'dateInterval' => $dateInterval,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionConfirmationCancellation()
    {
        $request =  Yii::$app->request->get();
        
        if (!empty($request['id'])){
            return $this->renderAjax('_confirmation-cancellation', [
                'cancellationID'    => $request['id'],
                'language'          => Yii::$app->language,
            ]);
        }

        return false;
    }

    public function actionSaveConfirmationCancellation()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request =  Yii::$app->request->post();

        if(!empty($request['_id'])){
            $model = LogWarehouse::findOne(['_id'=>new ObjectID($request['_id'])]);

            if(empty($model->confirmation_action)){
                if(!empty($request['flConfirm']) && $request['flConfirm']==1){
                    $model->confirmation_action = (int)1;

                    if($model->save()){
                        Yii::$app->session->setFlash('alert' ,[
                                'typeAlert'=>'success',
                                'message'=>'Сохранения применились.'
                            ]
                        );
                    }
                } else{
                    $model->confirmation_action = (int)-1;
                    $model->comment = 'Отмена списания по причине: «'.$request['comment'].'»' . "<br>" . $model->comment;

                    $infoWarahouse = PartsAccessoriesInWarehouse::findOne([
                        'parts_accessories_id'=>$model->parts_accessories_id,
                        'warehouse_id'=>$model->admin_warehouse_id
                    ]);

                    $infoWarahouse->number += $model->number;

                    if($infoWarahouse->save()){
                        if($model->save()){
                            // add log
                            LogWarehouse::setInfoLog([
                                'action'                    =>  'cancellation_cancellation',
                                'parts_accessories_id'      =>  (string)$model->parts_accessories_id,
                                'number'                    =>  $model->number,
                                'admin_warehouse_id'        =>  (string)$model->admin_warehouse_id,
                                'comment'                   =>  'Отмена списания за ' . $model->date_create->toDateTime()->format('Y-m-d H:i:s'),
                                'cancellation'              =>  $request['_id'],
                            ]);

                            Yii::$app->session->setFlash('alert' ,[
                                    'typeAlert'=>'success',
                                    'message'=>'Сохранения применились.'
                                ]
                            );
                        }
                    }
                }
            }
        }

        return $this->redirect('/' . Yii::$app->language .'/business/parts-accessories-in-warehouse/all-cancellation-warehouse');
    }

    protected function procurementPlanning()
    {
        $idWarehouse = Warehouse::getIdMyWarehouse();

        $listGoods = [];
        $statusGoods = [];

        // in warehouse
        $modelWarehouse = PartsAccessoriesInWarehouse::find()
            ->where([
                'warehouse_id'          => new ObjectID($idWarehouse)
            ])
            ->all();

        if(!empty($modelWarehouse) && $idWarehouse!='592426f6dca7872e64095b45'){
            foreach ($modelWarehouse as $item) {
                $listGoods[(string)$item->parts_accessories_id] = [
                    'inWarehouse'   =>  $item->number,
                    'usedMonth'     =>  0,
                    'timeDelivery'  =>  0,
                    'countDelivery' =>  0,
                    'wait'          =>  0
                ];
            }


            // use for month
            $to = strtotime(date('Y-m-d'. ' 23:59:59'));
            $from = strtotime(date('Y-m-d' . ' 00:00:00',strtotime("-1 month", $to)));
            $modelUse = LogWarehouse::find()
                ->where([
                    'date_create' => [
                        '$gte' => new UTCDateTime($from * 1000),
                        '$lt' => new UTCDateTime($to * 1000)
                    ],
                    'admin_warehouse_id' => new ObjectID($idWarehouse)
                ])
                ->all();
            if($modelUse){
                foreach ($modelUse as $item) {
                    if (in_array($item->action,['status_sale_issued','cancellation','send_parcel'])){
                        $listGoods[(string)$item->parts_accessories_id]['usedMonth'] += $item->number;
                    }
                }
            }

            //time delivery
            $modelSendingWaitingParcel = SendingWaitingParcel::find()
                ->where([
                    'date_create' => [
                        '$gte' => new UTCDateTime($from * 1000),
                        '$lt' => new UTCDateTime($to * 1000)
                    ],
                    'where_sent'=>$idWarehouse,
                    'is_posting'=>1
                ])
                ->all();
            if(!empty($modelSendingWaitingParcel)){
                foreach ($modelSendingWaitingParcel as $item) {
                    $countDeliveryDays = date_diff(new DateTime($item->date_update->toDateTime()->format('Y-m-d H:i:s')), new DateTime($item->date_create->toDateTime()->format('Y-m-d H:i:s')))->days;

                    if($countDeliveryDays == 0){
                        $countDeliveryDays = 7;
                    }

                    if(!empty($item->part_parcel)){
                        foreach ($item->part_parcel as $itemParcel){
                            if(!empty($listGoods[$itemParcel['goods_id']])){
                                $listGoods[$itemParcel['goods_id']]['timeDelivery'] += $countDeliveryDays;
                                $listGoods[$itemParcel['goods_id']]['countDelivery']++;
                            }
                        }
                    }

                }
            }

            // wait delivery parcel
            $modelSendingWaitingParcel = SendingWaitingParcel::find()->where(['where_sent'=>$idWarehouse,'is_posting'=>0])->all();
            if(!empty($modelSendingWaitingParcel)){
                foreach ($modelSendingWaitingParcel as $item) {
                    if(!empty($item->part_parcel)){
                        foreach ($item->part_parcel as $itemParcel){
                            if(!empty($listGoods[$itemParcel['goods_id']])){
                                $listGoods[$itemParcel['goods_id']]['wait'] = 1;
                            }
                        }
                    }
                }
            }



            foreach ($listGoods as $k=>$item) {
                if($item['wait'] == '1'){
                    $statusGoods[$k] = 'wait';
                }
                else if($item['inWarehouse']>0) {
                    $needForDay = round(($item['usedMonth'] / 30), 2, PHP_ROUND_HALF_EVEN);

                    $listGoods[$k]['needDay'] = $needForDay;

                    if ($item['timeDelivery'] > 0 && $item['inWarehouse'] > (ceil($item['timeDelivery'] / $item['countDelivery']) + 7) * $needForDay) {
                        $statusGoods[$k] = 'good';
                    } else if ($item['timeDelivery'] > 0 && $item['inWarehouse'] <= ceil($item['timeDelivery'] / $item['countDelivery']) * $needForDay) {
                        $statusGoods[$k] = 'alert';
                    } else if ($item['timeDelivery'] > 0 && $item['inWarehouse'] <= (ceil($item['timeDelivery'] / $item['countDelivery']) + 7) * $needForDay) {
                        $statusGoods[$k] = 'attention';
                    } else if ($item['timeDelivery'] == 0 && $item['inWarehouse'] > 14 * $needForDay) {
                        $statusGoods[$k] = 'good';
                    } else if ($item['timeDelivery'] == 0 && $item['inWarehouse'] <= 7 * $needForDay) {
                        $statusGoods[$k] = 'alert';
                    } else if ($item['timeDelivery'] == 0 && $item['inWarehouse'] <= 14 * $needForDay) {
                        $statusGoods[$k] = 'attention';
                    } else {
                        $statusGoods[$k] = 'alert';
                    }
                }
                else if($item['inWarehouse']==0 && $item['usedMonth']>0){
                    $statusGoods[$k] = 'alert';
                }else{
                    $statusGoods[$k] = 'empty';
                }

            }
        }

//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($listGoods);
//        echo "</xmp>";
//        echo "<xmp>";
//        print_r($statusGoods);
//        echo "</xmp>";
//        die();

        return $statusGoods;

    }

}