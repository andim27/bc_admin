<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\models\api\Product;
use app\models\LogWarehouse;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Sales;
use app\models\StatusSales;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class LogWarehouseController extends BaseController {

    public function actionMoveOnWarehouse()
    {

        $request = Yii::$app->request->post();

        if(empty($request)){
            $request['infoWarehouse'] = '';
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-01-01");
        }

        $model = '';
        if(!empty($request['infoWarehouse'])){

            $whereAction = [];
            if(!empty($request['list_action'])){
                $whereAction = ['IN','action',$request['list_action']];
            }
            
            $model = LogWarehouse::find()
                ->where([
                    'date_create' => [
                        '$gte' => new UTCDateTime(strtotime($request['from']) * 1000),
                        '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                    ],
                    '$or' => [
                        ['admin_warehouse_id' => new ObjectID($request['infoWarehouse'])],
                        ['on_warehouse_id' => new ObjectID($request['infoWarehouse'])]
                    ]
                ])
                ->andFilterWhere($whereAction)
                ->all();


        }

        return $this->render('move-on-warehouse',[
            'language'          => Yii::$app->language,
            'request'           => $request,
            'model'             => $model
        ]);
    }

    public function actionMoveOnWarehouseForMonth()
    {
        $infoProduct = $infoProductAmount = $actionDontKnow = [];
        $infoAction = [
            'issued'        =>  ['status_sale_issued',],
            'posting'       =>  ['posting_parcel','write_off_parcel_and_add_warehouse','return_in_warehouse'],
            'send'          =>  ['send_parcel'],
            'cancellation'  =>  ['cancellation'],

            'skip_status'   => ['status_sale_delivered']
        ];

        $request = Yii::$app->request->post();

        if(empty($request)){
            $request['infoWarehouse'] = '';
            $request['to'] = date("Y-m");
            $request['from'] = date("Y-01");
        }

        $model = '';
        if(!empty($request['infoWarehouse'])){


            $model = LogWarehouse::find()
                ->where([
                    'date_create' => [
                        '$gte' => new UTCDateTime(strtotime($request['from']) * 1000),
                        '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                    ],
                    '$or' => [
                        ['admin_warehouse_id' => new ObjectID($request['infoWarehouse'])],
                        ['on_warehouse_id' => new ObjectID($request['infoWarehouse'])]
                    ]
                ])
                ->all();

            $infoProduct = [];
            if(!empty($model)){
                
                foreach ($model as $item) {

                    $dateCreate = $item->date_create->toDateTime()->format('Y-m');

                    if(empty($infoProduct[$dateCreate][(string)$item->parts_accessories_id])){
                        $infoProduct[$dateCreate][(string)$item->parts_accessories_id] = [
                            'title'         =>  $item->infoPartsAccessories->title,
                            'issued'        =>  0,
                            'posting'       =>  0,
                            'send'          =>  0,
                            'cancellation'  =>  0,
                        ];
                    }
                    
                    if(empty($infoProductAmount[(string)$item->parts_accessories_id])){
                        $infoProductAmount[(string)$item->parts_accessories_id] = [
                            'title'         =>  $item->infoPartsAccessories->title,
                            'issued'        =>  0,
                            'posting'       =>  0,
                            'send'          =>  0,
                            'cancellation'  =>  0,
                        ];
                    }

                    if(in_array($item->action,$infoAction['issued'])){
                        $infoProduct[$dateCreate][(string)$item->parts_accessories_id]['issued']+=$item->number;
                        $infoProductAmount[(string)$item->parts_accessories_id]['issued']+=$item->number;
                    } else if(in_array($item->action,$infoAction['posting'])){
                        $infoProduct[$dateCreate][(string)$item->parts_accessories_id]['posting']+=$item->number;
                        $infoProductAmount[(string)$item->parts_accessories_id]['posting']+=$item->number;
                    } else if(in_array($item->action,$infoAction['send'])){
                        if((string)$item->admin_warehouse_id==$request['infoWarehouse']){
                            $infoProduct[$dateCreate][(string)$item->parts_accessories_id]['send']+=$item->number;
                            $infoProductAmount[(string)$item->parts_accessories_id]['send']+=$item->number;
                        }
                    }else if(in_array($item->action,$infoAction['cancellation'])){
                        if($item->confirmation_action != '-1'){
                            $infoProduct[$dateCreate][(string)$item->parts_accessories_id]['cancellation']+=$item->number;
                            $infoProductAmount[(string)$item->parts_accessories_id]['cancellation']+=$item->number;
                        }
                    } else if(in_array($item->action,$infoAction['skip_status'])){

                    }else {
                        $actionDontKnow[$item->action] = THelper::t($item->action);
                    }
                }
            }
            

        }

        return $this->render('move-on-warehouse-for-month',[
            'language'          => Yii::$app->language,
            'request'           => $request,
            'infoProduct'       => $infoProduct,
            'infoProductAmount' => $infoProductAmount,
            'actionDontKnow'    => $actionDontKnow
        ]);
    }

    public function actionCancellationCancelletion($id)
    {
        $modelCheckCancellation = LogWarehouse::findOne(['cancellation'=>new ObjectID($id)]);

        $model = LogWarehouse::findOne(['_id'=>new ObjectID($id)]);

        if(!empty($model) && $model->action == 'cancellation' && empty($modelCheckCancellation)){

            $modelWarehouse = PartsAccessoriesInWarehouse::findOne([
                'parts_accessories_id'=>$model->parts_accessories_id,
                'warehouse_id'=>$model->admin_warehouse_id,
            ]);

            $modelWarehouse->number += $model->number;

            if($modelWarehouse->save()){
                // add log
                LogWarehouse::setInfoLog([
                    'action'                    =>  'cancellation_cancellation',
                    'parts_accessories_id'      =>  $model->parts_accessories_id,
                    'number'                    =>  $model->number,
                    'admin_warehouse_id'        =>  (string)$model->admin_warehouse_id,
                    'comment'                   =>  'Отмена списания за ' . $model->date_create->toDateTime()->format('Y-m-d H:i:s'),
                    'cancellation'              =>  (string)$id,
                ]);

                header('Content-Type: text/html; charset=utf-8');
                echo "<xmp>";
                print_r('ok');
                echo "</xmp>";
                die();
            }
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('fail');
        echo "</xmp>";
        die();
    }
}