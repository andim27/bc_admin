<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\models\api\Product;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
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

    public function actionMoveOnWarehouseExcel()
    {

        $request = Yii::$app->request->post();

        $infoExport = [];
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

            if(!empty($model)){
                foreach ($model as $item) {
                    $infoExport[] = [
                        'date'          =>  $item->date_create->toDateTime()->format('Y-m-d H:i:s'),
                        'action'        =>  THelper::t($item->action),
                        'whoDoing'      =>  (!empty($item->adminInfo) ? $item->adminInfo->secondName .' '. $item->adminInfo->firstName : ''),
                        'fromWarehouse' =>  (!empty($item->admin_warehouse_id) ? $item->adminWarehouseInfo->title : ''),
                        'toWarehouse'   =>  (!empty($item->on_warehouse_id) ? $item->onWarehouseInfo->title : ''),
                        'goods'         =>  $item->infoPartsAccessories->title,
                        'count'         =>  $item->number,
                        'price'         =>  (!empty($item->money) ? $item->money . ' EUR' : ''),
                        'comment'       =>  (!empty($item->comment) ? $item->comment : ''),
                    ];
                }
            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'date',
                'action',
                'whoDoing',
                'fromWarehouse',
                'toWarehouse',
                'goods',
                'count',
                'price',
                'comment',
            ],
            'headers' => [
                'date'          =>  'Дата',
                'action'        =>  'Действие',
                'whoDoing'      =>  'Кто проводил',
                'fromWarehouse' =>  'Склад -->',
                'toWarehouse'   =>  'Склад <---',
                'goods'         =>  'Товар',
                'count'         =>  'Количество',
                'price'         =>  'Цена',
                'comment'       =>  'Коментарий',
            ],
        ]);

        die();
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

            $infoDateTo = explode("-",$request['to']);
            $countDay = cal_days_in_month(CAL_GREGORIAN, $infoDateTo['1'], $infoDateTo['0']);

            $model = LogWarehouse::find()
                ->where([
                    'date_create' => [
                        '$gte' => new UTCDateTime(strtotime($request['from'].'-01') * 1000),
                        '$lte' => new UTCDateTime(strtotime($request['to'].'-'.$countDay . '23:59:59') * 1000)
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


    public function actionFix()
    {
        $idArray = [];

        $modelOrder = StatusSales::find()->all();

        foreach ($modelOrder as $item) {
            if(!empty($item->setSales)){
                $indicator = 0;
                $array = [];
                foreach ($item->setSales as $itemSet) {
                    if($itemSet['status']=='status_sale_issued'){

                        if(empty($array[$itemSet['title']])){
                            $array[$itemSet['title']] = 0;
                        } else {
                            $indicator=1;
                        }

                        $array[$itemSet['title']]++;
                    }
                }

                if($indicator==1){
                    $idArray[] = $item;
                }
            }
        }

        $table = '';
        foreach ($idArray as $item) {
            $listGoods=$listCount='';
            if(!empty($item->setSales)){
                foreach ($item->setSales as $itemSet) {
                    $modelLog = LogWarehouse::find()->where(['date_create'=>$itemSet['dateChange']])->all();

                    $listCount .= count($modelLog).'-'.(!empty($modelLog) ? (string)$modelLog['0']->_id : '').'<br>';

                    $listGoods.='<a target="_blank" href="/ru/business/log-warehouse/apply-fix?id='.(string)$item->idSale.'&title='.$itemSet['title'].'">fix</a>'.$itemSet['title'] . ' - ('.$itemSet['status'].')'.$itemSet['dateChange']->toDateTime()->format('Y-m-d H:i:s').' -------------'.(string)$itemSet['idUserChange'].'<br>';
                }
            }


            $listRev='';
            if(!empty($item->reviewsSales)){
                foreach ($item->reviewsSales as $itemRev) {
                    $listRev.=$itemRev['review'].'-'.$itemRev['dateCreate']->toDateTime()->format('Y-m-d H:i:s').'<br>';
                }
            }


            $table .= '
                <tr>
                    <td style="border-bottom: 1px solid #000"> '.(string)$item->idSale.'
                    <td style="border-bottom: 1px solid #000"> '.$listCount.'
                    <td style="border-bottom: 1px solid #000"> '.$listGoods.'
                    <td style="border-bottom: 1px solid #000"> '.$listRev.'
            ';
        }

        $table = '<table>'.$table.'</table>';

        echo $table;die();

    }

    public function actionApplyFix($id,$title)
    {
        $arrayGoods = PartsAccessories::getListPartsAccessoriesForSaLe();

        $modelStatusSale= StatusSales::findOne(['idSale'=>new ObjectID($id)]);
        foreach ($modelStatusSale->setSales as $item) {
            if($item['title']==$title && $item['status']=='status_sale_issued'){
                $dateUse = $item['dateChange'];
                break;
            }
        }


        $listRev=[];
        if(!empty($modelStatusSale->reviewsSales)){
            foreach ($modelStatusSale->reviewsSales as $itemRev) {
                $listRev[]=$itemRev;
                if($itemRev['dateCreate']==$dateUse){
                    $listRev[]=$itemRev;
                }
            }
        }
        $modelStatusSale->reviewsSales = $listRev;

        if($modelStatusSale->save()){}

        $modelLog = LogWarehouse::find()->where(['date_create'=>$dateUse])->one();

        $modelLogNew = new LogWarehouse();
        $modelLogNew->action = $modelLog->action;
        $modelLogNew->who_performed_action = $modelLog->who_performed_action;
        $modelLogNew->parts_accessories_id = $modelLog->parts_accessories_id;
        $modelLogNew->number = $modelLog->number;
        $modelLogNew->suppliers_performers_id = $modelLog->suppliers_performers_id;
        $modelLogNew->admin_warehouse_id = $modelLog->admin_warehouse_id;
        $modelLogNew->on_warehouse_id = $modelLog->on_warehouse_id;
        $modelLogNew->money = $modelLog->money;
        $modelLogNew->comment = $modelLog->comment;
        $modelLogNew->date_create = $modelLog->date_create;

        if($modelLogNew->save()){}



        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($dateUse->toDateTime()->format('Y-m-d H:i:s'));
        echo "</xmp>";
        echo "<xmp>";
        print_r($id);
        echo "</xmp>";
        echo "<xmp>";
        print_r($title);
        echo "</xmp>";
        die();

        //5978ac393d073f3d0b411fd5
    }


    public function actionShowCancellation()
    {
        $model = Sales::find()->where(['type'=>-1])->all();

        $list = [];
        foreach ($model as $item) {
            if(!empty($item->statusSale)){
                    if(!empty($item->statusSale->setSales)){
                        foreach ($item->statusSale->setSales as $itemSet) {
                            if($itemSet['status']=='status_sale_issued' && !in_array((string)$item->_id,$list)){
                                $list[] = (string)$item->_id;
                                echo $item->username.'<br>';
                            }
                        }

                    }

            }
        }
        die();

    }
}