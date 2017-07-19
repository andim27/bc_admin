<?php

namespace app\modules\business\controllers;

use app\models\LogWarehouse;
use app\models\PartsAccessoriesInWarehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class LogWarehouseController extends BaseController {

    public function actionMoveOnWarehouse()
    {
        if(empty($request)){
            $request['infoWarehouse'] = '';
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-01-01");
        }

        $queryWarehouse = [];
        if(!empty($request['infoWarehouse'])){
            $queryWarehouse = [];
        }

        $model = LogWarehouse::find()
            ->where([
                'date_create' => [
                    '$gte' => new UTCDateTime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->all();

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

}