<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\models\api\Product;
use app\models\LogWarehouse;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Sales;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Warehouse;
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
            'posting'       =>  [
                'posting_parcel','write_off_parcel_and_add_warehouse','return_in_warehouse',
                'posting_ordering','add_execution_posting'
            ],
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

    public function actionTemp()
    {

        $countListTitle = Settings::getListCountry();

        $table = '<table border="1">';
        $table .= '
            <tr>
                <td>Дата
                <td>Склад
                <td>Expert
                <td>Balance
                <td>Profi';

        $from = '2017-09-01';
        $to = '2017-12-31';

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime($from) * 1000),
                    '$lte' => new UTCDatetime(strtotime($to) *1000)
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->andWhere([
                'type' => ['$ne' => -1]
            ])
            ->all();
        $infoSetGoods = $infoCountry = [];
        if(!empty($model)){
            foreach ($model as $item) {
                $date = $item->dateCreate->toDateTime()->format('Y-m');

                foreach($item->statusSale->set as $itemSet){
                    $user = (!empty($itemSet->idUserChange) ? (string)$itemSet->idUserChange : 'none');

                    if($user != 'none'){
                        $warehouseAdmin = Warehouse::getInfoWarehouse($user);

                        if (empty($infoSetGoods[$warehouseAdmin->title][$date][$itemSet->title])) {
                            $infoSetGoods[$warehouseAdmin->title][$date][$itemSet->title]['books'] = 0;
                            $infoSetGoods[$warehouseAdmin->title][$date][$itemSet->title]['issue'] = 0;
                        }

                        $infoSetGoods[$warehouseAdmin->title][$date][$itemSet->title]['books']++;


                        if (empty($infoCountry[$warehouseAdmin->country][$date][$itemSet->title])) {
                            $infoCountry[$warehouseAdmin->country][$date][$itemSet->title]['books'] = 0;
                            $infoCountry[$warehouseAdmin->country][$date][$itemSet->title]['issue'] = 0;
                        }

                        $infoCountry[$warehouseAdmin->country][$date][$itemSet->title]['books']++;

                    }

                }
            }
        }

        $modelLastChangeStatus = StatusSales::find()
            ->where([
                'setSales.dateChange' => [
                    '$gte' => new UTCDateTime(strtotime($from) * 1000),
                    '$lt' => new UTCDateTime(strtotime($to . '23:59:59') * 1000)
                ],
                'setSales.status' => 'status_sale_issued',
            ])
            ->all();
        if(!empty($modelLastChangeStatus)) {

            $from = strtotime($from);
            $to = strtotime($to);

            foreach ($modelLastChangeStatus as $item){
                if($item->sales->type != -1) {
                    foreach ($item->setSales as $itemSet) {

                        $date = $itemSet['dateChange']->toDateTime()->format('Y-m');

                        $dateChange = strtotime($itemSet['dateChange']->toDateTime()->format('Y-m-d'));
                        if (!empty($itemSet['idUserChange']) && $dateChange>=$from && $dateChange<=$to && in_array($itemSet['status'],StatusSales::getListIssuedStatus())) {

                            $warehouseAdmin = Warehouse::getInfoWarehouse((string)$itemSet['idUserChange']);

                            if (empty($infoSetGoods[$warehouseAdmin->title][$date][$itemSet['title']])) {
                                $infoSetGoods[$warehouseAdmin->title][$date][$itemSet['title']]['books'] = 0;
                                $infoSetGoods[$warehouseAdmin->title][$date][$itemSet['title']]['issue'] = 0;
                            }

                            $infoSetGoods[$warehouseAdmin->title][$date][$itemSet['title']]['issue']++;

                            if (empty($infoCountry[$warehouseAdmin->country][$date][$itemSet['title']])) {
                                $infoCountry[$warehouseAdmin->country][$date][$itemSet['title']]['books'] = 0;
                                $infoCountry[$warehouseAdmin->country][$date][$itemSet['title']]['issue'] = 0;
                            }

                            $infoCountry[$warehouseAdmin->country][$date][$itemSet['title']]['issue']++;

                        }
                    }
                }
            }

        }


        $all = [
            'e' => ['books'=>0,'issue'=>0],
            'b' => ['books'=>0,'issue'=>0],
            'p' => ['books'=>0,'issue'=>0]
        ];
        foreach ($infoSetGoods as $kWarehouse=>$itemWarehouse){
            foreach ($itemWarehouse as $kDate => $itemDate) {
                $e = !empty($itemDate['Комплект для продажи Life Expert']) ? $itemDate['Комплект для продажи Life Expert'] : ['books'=>0,'issue'=>0];
                $b = !empty($itemDate['Комплект для продажи Life Balance']) ? $itemDate['Комплект для продажи Life Balance'] : ['books'=>0,'issue'=>0];
                $p = !empty($itemDate['Комплект для продажи Life Expert PROFI']) ? $itemDate['Комплект для продажи Life Expert PROFI'] : ['books'=>0,'issue'=>0];

                $table .= '
                <tr>
                    <td>'.$kDate.'
                    <td>'.$kWarehouse.'
                    <td>'.$e['books'].' / '.$e['issue'].'
                    <td>'.$b['books'].' / '.$b['issue'].'
                    <td>'.$p['books'].' / '.$p['issue'];

                $all['e']['books'] += $e['books'];
                $all['e']['issue'] += $e['issue'];
                $all['b']['books'] += $b['books'];
                $all['b']['issue'] += $b['issue'];
                $all['p']['books'] += $p['books'];
                $all['p']['issue'] += $p['issue'];
            }

            $counWarehouse = [
                '59620f49dca78761ae2d01c1'  =>  0, //e
                '59620f57dca78747631d3c62'  =>  0, //b
                '5975afe2dca78748ce5e7e02'  =>  0 //p
            ];
            $warehouseId = Warehouse::findOne(['title'=>$kWarehouse])->_id;
            $countList = PartsAccessoriesInWarehouse::find()->where(['warehouse_id'=>$warehouseId])->all();
            if(!empty($countList)){
                foreach ($countList as $k=>$item){
                    $counWarehouse[(string)$item['parts_accessories_id']] = $item['number'];
                }
            }


            $table .= '
                <tr style="background-color: grey">
                    <td>На складе
                    <td>
                    <td> '.$counWarehouse['59620f49dca78761ae2d01c1'].'
                    <td> '.$counWarehouse['59620f57dca78747631d3c62'].'
                    <td>'.$counWarehouse['5975afe2dca78748ce5e7e02'];

        }

        $table .= '
                <tr style="background-color: #2aabd2">
                    <td colspan="5"><center>Итого</center>
                <tr>
                    <td> 
                    <td> 
                    <td> '.$all['e']['books'].'/'.$all['e']['issue'].'
                    <td> '.$all['b']['books'].'/'.$all['b']['issue'].'
                    <td> '.$all['p']['books'].'/'.$all['p']['issue'].'
                <tr>
                    <td>
                    <td>
                    <td>
                    <td>
                    <td>    
                    ';

        $table .= '</table>';
        $table .= '<h1>По странам</h1>';
        $table .= '<table border="1">';
        if(!empty($infoCountry)){
            foreach ($infoCountry as $kCountry => $itemCountry) {
                foreach ($itemCountry as $kDate=>$itemDate) {
                    $e = !empty($itemDate['Комплект для продажи Life Expert']) ? $itemDate['Комплект для продажи Life Expert'] : ['books'=>0,'issue'=>0];
                    $b = !empty($itemDate['Комплект для продажи Life Balance']) ? $itemDate['Комплект для продажи Life Balance'] : ['books'=>0,'issue'=>0];
                    $p = !empty($itemDate['Комплект для продажи Life Expert PROFI']) ? $itemDate['Комплект для продажи Life Expert PROFI'] : ['books'=>0,'issue'=>0];

                    $table .= '
                    <tr>
                        <td>'.$kDate.'
                        <td>'.(!empty($countListTitle[$kCountry]) ? $countListTitle[$kCountry] : $kCountry).'
                        <td>'.$e['books'].' / '.$e['issue'].'
                        <td>'.$b['books'].' / '.$b['issue'].'
                        <td>'.$p['books'].' / '.$p['issue'];


                }
            }
        }
        $table .= '</table>';


        echo $table;die();
    }

}