<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api;
use app\models\apiDelovod\Goods;
use app\models\apiDelovod\UnitMeasure;
use app\models\apiDelovod\Storages;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use Yii;
use yii\helpers\ArrayHelper;


class TestController extends BaseController
{

    public function actionSyncComponents()
    {
        $units = ArrayHelper::map(UnitMeasure::all(),'shortName','id');
        $listGoods = ArrayHelper::index(Goods::getGoods(),'id');

        $catalogId = '1100300000001005';

        $model = PartsAccessories::find()->all();
        foreach ($model as $item) {
            if(empty($item->delovod_id) || empty($listGoods[$item->delovod_id])){

                $data = [
                    'code'=>(!empty($item->article) ? 'bpt-'.$item->article : ''),
                    'name'=>$item->title,
                    'isGroup'=>'0',
                    'goodType'=>'1004000000000014',
                    'parent'=>$catalogId,
                    'mainUnit'=>$units[rtrim(THelper::t($item->unit),'.')],
                ];

//                $idLine = Goods::save($data);
//                $item->delovod_id = $idLine;
//                if($item->save()){}

                sleep(1);
            }
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }

    public function actionSyncFinishedProducts()
    {
        $listGoods = ArrayHelper::index(Goods::getGoods(),'id');

        $catalogId = '1100300000001006';

        $model = Products::find()->where(['statusHide'=>['$ne'=>1]])->all();
        foreach ($model as $item) {
            if(empty($item->delovod_id) || empty($listGoods[$item->delovod_id])){

                $data = [
                    'name'=>$item->productName,
                    'isGroup'=>'0',
                    'goodType'=>'1004000000000014',
                    'parent'=>$catalogId,
                    'mainUnit'=>'1103600000000001',
                ];

//                $idLine = Goods::save($data);
//                $item->delovod_id = $idLine;
//                if($item->save()){}

                sleep(1);
            }
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }

    public function actionSyncWarehouses()
    {

        $listWarehouse = ArrayHelper::index(Storages::all(),'id');

        $model = Warehouse::find()->all();
        foreach ($model as $item) {
            if(empty($item->delovod_id) || empty($listWarehouse[$item->delovod_id])){

                $data = [

                    'id'=>'catalogs.storages',
                    'name'=>$item->title,
                    'storageType'=>'1004000000000082',
                    
                ];

                $idLine = Storages::save($data);
                $item->delovod_id = $idLine;
                if($item->save()){}

                sleep(1);
            }
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }
    
    
    public function actionXz()
    {


        $catalogs = Goods::getCatalogs();

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($catalogs);
        echo "</xmp>";
        die();
    }


    /**
     * тест
     */
    public function actionTemp()
    {
        $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
        $packet['version']="0.1";

        /**
         * get Units
         */

        $arrayUnits = [];

        $packet['action']='request';
        $packet['params']['from']='catalogs.units';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'isGroup'=>'isGroup',
            'parent'=>'parent',
            'mainUnit'=>'mainUnit',
            'productNum'=>'productNum',
            'category'=>'category'
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,True);
            foreach ($out as $item) {
                $arrayUnits[$item['id__pr']] = $item['id'];
            }
        }

        /**
         * get catalog
         */
//        $packet['action']='request';
//        $packet['params']['from']='catalogs.goods';
//        $packet['params']['fields']=[
//            'id'=>'id',
//            'code'=>'code',
//            'name'=>'name',
//            'isGroup'=>'isGroup',
//            'parent'=>'parent',
//            'mainUnit'=>'mainUnit',
//            'productNum'=>'productNum',
//            'category'=>'category'
//        ];
//        $packet['params']['filters'][]=[
//            'alias' => 'isGroup',
//            'operator'=>'=',
//            'value' => '1'
//        ];

//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r(json_decode($out,True));
//            echo "</xmp>";
//            die();
//        }

        $catalogId = '1100300000001005';

        /**
         * get goods
         */
        $packet['action']='request';
        $packet['params']['from']='catalogs.goods';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'isGroup'=>'isGroup',
            'goodType'=>'goodType',
            'parent'=>'parent',
            'mainUnit'=>'mainUnit',
            'productNum'=>'productNum',
            'category'=>'category'
        ];
        $packet['params']['filters'][]=[
            'alias' => 'isGroup',
            'operator'=>'=',
            'value' => '0'
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,TRUE);

            echo count($out) .'=';

            $out = ArrayHelper::index($out,'productNum');

            echo count($out).'<br>';
            die();

            $model = PartsAccessories::find()->all();
            foreach ($model as $item) {
                if(empty($out[(string)$item->_id]['id'])){
                    $packet = [];
                    $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
                    $packet['version']="0.1";
                    $packet['action']='saveObject';
                    $packet['params']['header']=[
                        'id'=>'catalogs.goods',
                        'code'=>(!empty($item->article) ? 'bpt-'.$item->article : ''),
                        'name'=>$item->title,
                        'isGroup'=>'0',
                        'goodType'=>'1004000000000014',
                        'parent'=>$catalogId,
                        'mainUnit'=>$arrayUnits[rtrim(THelper::t($item->unit),'.')],
                        'productNum'=>(string)$item->_id,
                    ];

                    echo (string)$item->_id.'<br>';

//                    if($curl=curl_init())
//                    {
//                        curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//                        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//                        curl_setopt($curl,CURLOPT_POST,true);
//                        curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//                        $xz= curl_exec($curl);
//                        curl_close($curl);
//
//                        header('Content-Type: text/html; charset=utf-8');
//                        echo "<xmp>";
//                        print_r(json_decode($xz,TRUE));
//                        echo "</xmp>";
//
//
//                        sleep(1);
//                    }
                }
            }

        }

        /**
         * delete label
         */
//        $packet['action']='setDelMark';
//        $packet['params']['header']=[
//            'id'=>'1100300000001005'
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r($out);
//            echo "</xmp>";
//            die();
//        }

        /**
         * save goods
         */
//        $packet['action']='saveObject';
//        $packet['params']['header']=[
//            'id'=>'catalogs.goods',
//            'name'=>'Прибор Balance (6)',
//            'isGroup'=>'0',
//            'parent'=>'1100300000001005',
//            'mainUnit'=>'1103600000000001',
//            'productNum'=>'5924362adca78730ff4a3f22',
//            'HSCode'=>'3',
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r(json_decode($out,True));
//            echo "</xmp>";
//            die();
//        }

        /**
         * update info
         */

//        $packet['action']='saveObject';
//        $packet['params']['saveType']=0;
//        $packet['params']['header']=[
//            'id'=>'1100300000001013',
//            'name'=>'Прибор Balance (6) update',
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r(json_decode($out,True));
//            echo "</xmp>";
//            die();
//        }




//        $model = PartsAccessories::find()->all();
//        foreach ($model as $item) {
//            $packet = [];
//            $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
//            $packet['version']="0.1";
//            $packet['action']='saveObject';
//            $packet['params']['header']=[
//                'id'=>'catalogs.goods',
//                'code'=>(!empty($item->article) ? 'bpt-'.$item->article : ''),
//                'name'=>$item->title,
//                'isGroup'=>'0',
//                'goodType'=>'1004000000000014',
//                'parent'=>$catalogId,
//                'mainUnit'=>$arrayUnits[rtrim(THelper::t($item->unit),'.')],
//                'productNum'=>(string)$item->_id,
//            ];
//
//            if($curl=curl_init())
//            {
//                curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//                curl_setopt($curl,CURLOPT_POST,true);
//                curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//                $out=curl_exec($curl);
//                curl_close($curl);
//
//                sleep(1);
//            }
//        }
//
//        header('Content-Type: text/html; charset=utf-8');
//        echo "<xmp>";
//        print_r('ok');
//        echo "</xmp>";
//        die();


    }

    /**
     * ввод остатков
     */
    public function actionTempCount()
    {


        $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
        $packet['version']="0.1";

//        $packet['action']='request';
//        $packet['params']['from']='documents.startBalance';
//        $packet['params']['fields']=[
//            'id'=>'id',
//            'date'=>'date',
//            'number'=>'number',
//            'presentation'=>'presentation',
//            'posted'=>'posted',
//            'remark'=>'remark',
//            'baseDoc'=>'baseDoc',
//            'version'=>'version',
//            'firm'=>'firm',
//            'operationType'=>'operationType',
//            'storage'=>'storage',
//            'amountCur'=>'amountCur',
//            'currency'=>'currency',
//            'taxAccount'=>'taxAccount',
//            'author'=>'author',
//            'priceType'=>'priceType',
//            'business'=>'business',
//            'currency2'=>'currency2',
//            'regPurchasePrice'=>'regPurchasePrice',
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            $out = json_decode($out,True);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r($out);
//            echo "</xmp>";
//            die();
//        }

        $packet['action']='request';
        $packet['params']['from']='documents.startBalance.tpBalances';
        $packet['params']['fields']=[
            'rowNum'=>'rowNum',
            'owner'=>'owner',
            'person'=>'person',
            'contract'=>'contract',
            'cashAccount'=>'cashAccount',
            'good'=>'good',
            'unit'=>'unit',
            'employee'=>'employee',
            'tax'=>'tax',
            'accPeriod'=>'accPeriod',
            'costItem'=>'costItem',
            'department'=>'department',
            'price'=>'price',
            'qty'=>'qty',
            'amount'=>'amount',
            'amountCur'=>'amountCur',
            'incomeItem'=>'incomeItem',
            'salePrice'=>'salePrice',
            'goodPart'=>'goodPart',
            'goodChar'=>'goodChar',
            'business'=>'business',
            'purchPrice'=>'purchPrice',
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,True);

            header('Content-Type: text/html; charset=utf-8');
            echo "<xmp>";
            print_r($out);
            echo "</xmp>";
            die();
        }

//        $packet['action']='getObject';
//        $packet['params']['from']='documents.startBalance';
//        $packet['params']=[
//            'id'=>'1102500000001003',
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            $out = json_decode($out,True);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r($out);
//            echo "</xmp>";
//            die();
//        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('*');
        echo "</xmp>";
        die();
    }

    /**
     * закупка комплектующих
     */
    public function actionSaveCount()
    {
        $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
        $packet['version']="0.1";


//        $packet['action']='request';
//        $packet['params']['from']='documents.purchase';
//        $packet['params']['fields']=[
//            'id'=>'id'
//        ];
//        if($curl=curl_init()) {
//            curl_setopt($curl, CURLOPT_URL, 'https://delovod.ua/api/');
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($curl, CURLOPT_POST, true);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, "packet=" . json_encode($packet));
//            $out = curl_exec($curl);
//            curl_close($curl);
//
//            $out = json_decode($out, TRUE);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r($out);
//            echo "</xmp>";
//            die();
//        }




        /**
         * get Units
         */

        $arrayUnits = [];

        $packet['action']='request';
        $packet['params']['from']='catalogs.units';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'isGroup'=>'isGroup',
            'parent'=>'parent',
            'mainUnit'=>'mainUnit',
            'productNum'=>'productNum',
            'category'=>'category'
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,True);

            foreach ($out as $item) {
                $arrayUnits[$item['id__pr']] = $item['id'];
            }
        }


        /**
         * get goods
         */
        $packet['action']='request';
        $packet['params']['from']='catalogs.goods';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'isGroup'=>'isGroup',
            'goodType'=>'goodType',
            'parent'=>'parent',
            'mainUnit'=>'mainUnit',
            'productNum'=>'productNum',
            'category'=>'category'
        ];
        $packet['params']['filters'][]=[
            'alias' => 'isGroup',
            'operator'=>'=',
            'value' => '0'
        ];

        if($curl=curl_init()) {
            curl_setopt($curl, CURLOPT_URL, 'https://delovod.ua/api/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "packet=" . json_encode($packet));
            $out = curl_exec($curl);
            curl_close($curl);

            $arrayProducts = json_decode($out, TRUE);

            $arrayProducts = ArrayHelper::index($arrayProducts, 'productNum');
        }


        $packet['action']='request';
        $packet['params']['from']='catalogs.goods';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'isGroup'=>'isGroup',
            'goodType'=>'goodType',
            'parent'=>'parent',
            'mainUnit'=>'mainUnit',
            'productNum'=>'productNum',
            'category'=>'category'
        ];
        $packet['params']['filters'][]=[
            'alias' => 'isGroup',
            'operator'=>'=',
            'value' => '0'
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,TRUE);
            $out = ArrayHelper::index($out,'productNum');

            $model = PartsAccessories::find()->all();
            $packet = [];
            $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
            $packet['version']="0.1";
            $packet['action']='saveObject';
            $packet['params']['header']=[
                'id'=>'1100900000001002'
            ];

            foreach ($model as $item) {

                if(!empty($out[(string)$item->_id]['id'])){

                    $modelWarehouse = PartsAccessoriesInWarehouse::findOne([
                        'parts_accessories_id'=>$item->_id,
                        'warehouse_id'=>new ObjectID('592426f6dca7872e64095b45')
                    ]);

                    $count = 0;
                    if(!empty($modelWarehouse->number)){
                        $count = $modelWarehouse->number;
                    }

//                    header('Content-Type: text/html; charset=utf-8');
//                    echo "<xmp>";
//                    print_r($out[(string)$item->_id]);
//                    echo "</xmp>";
//                    die();

                    $price = (float)(!empty($item->last_price_eur) ? $item->last_price_eur : 0);

                    $packet['params']['tableParts']['tpGoods'][]=[
                        'good'=>$arrayProducts[(string)$item->_id]['id'],
                        'goodType'=>'1004000000000014',
                        'unit' => $arrayUnits[rtrim(THelper::t($item->unit),'.')],
                        'qty'=>(int)$count,
                        'price'=>$price,
                        'amountCur'=>$price*$count
                    ];



                }
            }

//            if($curl=curl_init())
//            {
//                curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//                curl_setopt($curl,CURLOPT_POST,true);
//                curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//                $xz = curl_exec($curl);
//                curl_close($curl);
//            }

            header('Content-Type: text/html; charset=utf-8');
            echo "<xmp>";
            print_r($xz);
            echo "</xmp>";
            die();
        }
    }

    public function actionAddPack()
    {
        $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
        $packet['version']="0.1";

        /**
         * get Units
         */

        $arrayUnits = [];

        $packet['action']='request';
        $packet['params']['from']='catalogs.units';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'isGroup'=>'isGroup',
            'parent'=>'parent',
            'mainUnit'=>'mainUnit',
            'productNum'=>'productNum',
            'category'=>'category'
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,True);
            foreach ($out as $item) {
                $arrayUnits[$item['id__pr']] = $item['id'];
            }
        }

        /**
         * get catalog
         */
//        $packet['action']='request';
//        $packet['params']['from']='catalogs.goods';
//        $packet['params']['fields']=[
//            'id'=>'id',
//            'code'=>'code',
//            'name'=>'name',
//            'isGroup'=>'isGroup',
//            'parent'=>'parent',
//            'mainUnit'=>'mainUnit',
//            'productNum'=>'productNum',
//            'category'=>'category'
//        ];
//        $packet['params']['filters'][]=[
//            'alias' => 'isGroup',
//            'operator'=>'=',
//            'value' => '1'
//        ];
//
//        if($curl=curl_init())
//        {
//            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//            curl_setopt($curl,CURLOPT_POST,true);
//            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//            $out=curl_exec($curl);
//            curl_close($curl);
//
//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r(json_decode($out,True));
//            echo "</xmp>";
//            die();
//        }


        $model = Products::find()->where(['statusHide'=>['$ne'=>1]])->all();

        foreach ($model as $item) {
//            $packet = [];
//            $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
//            $packet['version']="0.1";
//            $packet['action']='saveObject';
//            $packet['params']['header']=[
//                'id'=>'catalogs.goods',
//                'name'=>$item->productName,
//                'isGroup'=>'0',
//                'goodType'=>'1004000000000014',
//                'parent'=>'1100300000001006',
//                'mainUnit'=>'1103600000000001',
//                'productNum'=>(string)$item->_id,
//            ];
//
//            if($curl=curl_init())
//            {
//                curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
//                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
//                curl_setopt($curl,CURLOPT_POST,true);
//                curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
//                $out=curl_exec($curl);
//                curl_close($curl);
//
//                sleep(1);
//            }
        }
        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();

    }

    public function actionAddWarehouse()
    {
        $this->getOrders();




        $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
        $packet['version']="0.1";

        $packet['action']='request';
        $packet['params']['from']='catalogs.storages';
        $packet['params']['fields']=[
            'id'=>'id',
            'code'=>'code',
            'name'=>'name',
            'sysName'=>'sysName',
            'version'=>'version',
            'storageType'=>'storageType'
        ];

        if($curl=curl_init())
        {
            curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
            $out=curl_exec($curl);
            curl_close($curl);

            $out = json_decode($out,True);

//            header('Content-Type: text/html; charset=utf-8');
//            echo "<xmp>";
//            print_r($out);
//            echo "</xmp>";
//            die();

        }

        $model = Warehouse::find()->all();
        foreach ($model as $item) {
            $packet = [];
            $packet['key']="G297bQn3o0PLwZaPW3kDEOvBjvJMFZ";
            $packet['version']="0.15";
            $packet['action']='saveObject';
            $packet['params']['header']=[
                'id'=>'catalogs.storages',
                'name'=>$item->title,
                'storageType'=>'1004000000000082',
            ];

            if($curl=curl_init())
            {
                curl_setopt($curl,CURLOPT_URL,'https://delovod.ua/api/');
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl,CURLOPT_POST,true);
                curl_setopt($curl,CURLOPT_POSTFIELDS,"packet=".json_encode($packet));
                $out=curl_exec($curl);
                curl_close($curl);
                $out = json_decode($out,True);
                header('Content-Type: text/html; charset=utf-8');
                echo "<xmp>";
                print_r($out);
                echo "</xmp>";
                die();

                sleep(1); 
            }
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();


    }


    protected function getOrders()
    {
        $listOrders = $this->getOrderForMonth();

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($listOrders);
        echo "</xmp>";
        die();

        if(!empty($listOrders)){
            foreach ($listOrders as $k=>$listOrder) {
                //$
            }
        }
    }

    public function getOrderForMonth()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"http://vipsite.biz/admin/get_order.php");
        curl_setopt($ch, CURLOPT_POST, 0);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(['date_from' => $date_from,'date_to'=>$date_to]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return json_decode($server_output,TRUE);
    }

    public function addKey()
    {
        $xz = parent::addKey();

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($xz);
        echo "</xmp>";
        die();
    }

}