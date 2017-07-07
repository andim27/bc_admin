<?php

namespace app\modules\business\controllers;

use app\models\LogWarehouse;
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

}