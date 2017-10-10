<?php

namespace app\modules\business\controllers;

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

//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($request);
//        echo "</xmp>";
//        die();

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


//    public function actionFix(){
//        $info = [];
//
//        $notReturn=[new ObjectID('5942f34525d78a537b80c957'),new ObjectID('5967abfffef1bec01df24676'),new ObjectID('5975e6eebf20b4440f2cfa47')];
//
//
//        $modelSaleStatus = StatusSales::find()->where(['NOT IN','idSale',$notReturn])->all();
//        /** @var StatusSales $item */
//        foreach ($modelSaleStatus as $item){
//            if(!empty($item->reviewsSales)){
//                $tempRev=[];
//                foreach ($item->reviewsSales as $itemRev){
//                    $line = strpos($itemRev['review'],'Выдан->Выдан');
//                    if($line !== false){
//                        if(empty($info[(string)$itemRev['idUser']][$itemRev['dateCreate']->toDateTime()->format('Y-m-d H:i:s')])){
//                            $info[(string)$itemRev['idUser']][$itemRev['dateCreate']->toDateTime()->format('Y-m-d H:i:s')]['idSale'] = (string)$item->idSale;
//                            $info[(string)$itemRev['idUser']][$itemRev['dateCreate']->toDateTime()->format('Y-m-d H:i:s')]['loginClient'] = $item->sales->username;
//                            $info[(string)$itemRev['idUser']][$itemRev['dateCreate']->toDateTime()->format('Y-m-d H:i:s')]['count'] = 0;
//                            $info[(string)$itemRev['idUser']][$itemRev['dateCreate']->toDateTime()->format('Y-m-d H:i:s')]['log'] = 0;
//                        }
//
//                        $info[(string)$itemRev['idUser']][$itemRev['dateCreate']->toDateTime()->format('Y-m-d H:i:s')]['count']++;
//
//                    } else{
//                        $tempRev[]=$itemRev;
//                    }
//                }
//                $item->reviewsSales = $tempRev;
//
//                if($item->save()){
//
//                }
//            }
//        }
//
//
//
//        /*******************************************************************************/
//        $model = LogWarehouse::find()->where(['action'=>'status_sale_issued'])->all();
//
//        /** @var LogWarehouse $item */
//        foreach ($model as $item){
//            $userInfo = (string)$item->who_performed_action;
//            $timeInfo = $item->date_create->toDateTime()->format('Y-m-d H:i:s');
//
//            if(!empty($info[$userInfo][$timeInfo]['count']) && $info[$userInfo][$timeInfo]['count']!=$info[$userInfo][$timeInfo]['log']){
//                $info[$userInfo][$timeInfo]['log']++;
//                $info[$userInfo][$timeInfo]['return'][] = (string)$item->_id;
//
//                $modelGoods=PartsAccessoriesInWarehouse::findOne([
//                    'warehouse_id'  =>  $item->admin_warehouse_id,
//                    'parts_accessories_id'  => $item->parts_accessories_id
//                ]);
//
//                $modelGoods->number += $item->number;
//
//                if($modelGoods->save()){
//                    $item->delete();
//                }
//
//            }
//
//        }
//
//
//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r($info);
//        echo "</xmp>";
//        die();
//
//    }

}