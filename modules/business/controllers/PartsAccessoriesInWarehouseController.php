<?php

namespace app\modules\business\controllers;

use app\models\LogWarehouse;
use app\models\PartsAccessoriesInWarehouse;
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

        $implementation=[];
        if(!empty($modelChangeStatus)){
            foreach ($modelChangeStatus as $item){

                if($item->sales->type != -1){
                    foreach ($item->setSales as $itemSet){
                        if(!empty($itemSet['idUserChange']) && in_array((string)$itemSet['idUserChange'],$listAdmin)){
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
        
        return $this->render('in-warehouse',[
            'language'          => Yii::$app->language,
            'idWarehouse'       => $idWarehouse,
            'model'             => $model,
            'implementation'    => $implementation,
            'request'           => $request,
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

        return $this->render('cancellation-warehouse',[
            'language' => Yii::$app->language,
            'model' => $model,
            'dateInterval' => $dateInterval,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }
}