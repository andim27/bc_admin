<?php

namespace app\modules\business\controllers;

use app\components\ApiDelovod;
use app\components\THelper;
use app\controllers\BaseController;
use app\models\api;
use app\models\apiDelovod\CashAccounts;
use app\models\apiDelovod\CashIn;
use app\models\apiDelovod\Goods;
use app\models\apiDelovod\Purchase;
use app\models\apiDelovod\PurchaseTpGoods;
use app\models\apiDelovod\Sale;
use app\models\apiDelovod\SaleOrder;
use app\models\apiDelovod\SaleOrderTpGoods;
use app\models\apiDelovod\SaleTpGoods;
use app\models\apiDelovod\UnitMeasure;
use app\models\apiDelovod\Storages;
use app\models\apiDelovod\Users;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Sales;
use app\models\Settings;
use app\models\Warehouse;
use app\modules\business\models\WellnessClubMembers;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\helpers\ArrayHelper;


class TestController extends BaseController
{
    /**
     * заводим комплектующие и записываем ключи позиций с деловода себе
     */
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

                $idLine = Goods::save($data);
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

    /**
     * заводим паки и записываем ключи позиций с деловода себе
     */
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

                $idLine = Goods::save($data);
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

    /**
     * заводим склады
     */
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

//                $idLine = Storages::save($data);
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

    /**
     * связываем основной склад с со складом производства
     */
    public function actionFixHeadWarehouse()
    {
        $headWarehouseId = '1100700000000001';

        $modelKh = Warehouse::findOne(['_id'=>new ObjectID('592426f6dca7872e64095b45')]);
        $modelKh->delovod_id = $headWarehouseId;

        if($modelKh->save()){}

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }

    /**
     * вывводим остатки
     */
    public function actionInputRest()
    {
        $units = ArrayHelper::map(UnitMeasure::all(),'shortName','id');
        // Создаем приходную накладную
        $data = [
            'date'=>date('Y-m-d H:i:s'),
            'firm'=>'1100400000001002',
            'storage' => '1100700000000001',
            'person' => '1100100000001044',
            'operationType' => '1004000000000050',
            'currency' => '1101200000001001',
            'author'=>'1000200000001004',
            'paymentForm' => '1110300000000002',
            'department' => '1101900000000001'
        ];

        $idPurchase = Purchase::save($data);

        $dataGoods = [];

        // добавляем в нее закупки
        $modelComponents = PartsAccessories::find()->all();
        if(!empty($modelComponents) && !empty($idPurchase)){
            foreach ($modelComponents as $item) {
                $modelWarehouse = PartsAccessoriesInWarehouse::findOne([
                    'parts_accessories_id'=>$item->_id,
                    'warehouse_id'=>new ObjectID('592426f6dca7872e64095b45')
                ]);

                $count = 0;
                if(!empty($modelWarehouse->number)){
                    $count = $modelWarehouse->number;
                }

                $price = (float)(!empty($item->last_price_eur) ? $item->last_price_eur : 0);

                $dataGoods['tableParts']['tpGoods'][]=[
                    'good'=>$item->delovod_id,
                    'goodType'=>'1004000000000014',
                    'unit' => $units[rtrim(THelper::t($item->unit),'.')],
                    'qty'=>(int)$count,
                    'price'=>$price,
                    'amountCur'=>$price*$count
                ];
            }

            PurchaseTpGoods::save($dataGoods,$idPurchase);


        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r('ok');
        echo "</xmp>";
        die();
    }

    /**
     * отправить заказы в деловод
     */
    public function actionSetOrder()
    {
        $listOrderForMonth = $this->getOrderForMonth();

        if(!empty($listOrderForMonth)){
            foreach ($listOrderForMonth as $item) {

                $warehouse = '1100700000000001';
                if(!empty($item['warehouse'])){
                    $warehouseInfo = Warehouse::getInfoWarehouse($item['warehouse']);
                    if(!empty($warehouseInfo->delovod_id)){
                        $warehouse = (string)$warehouseInfo->delovod_id;
                    }
                }

                // создаем документ заказа
                if(SaleOrder::check($item['order_id']) === false){
                    $dataSaleOrder = [
                        'date'      =>  $item['date'],
                        'number'    =>  $item['order_id'],
                        'rate'      =>  '1',
                        'firm'      =>  '1100400000001004',
                        'person'    =>  '1100100000000001',
                        'currency'  =>  '1101200000001001',
                        'state'     =>  '1111500000000005',
                        'storage'   =>  $warehouse,
                        'author'    =>  '1000200000001004',
                    ];


                    $idSaleOrder = SaleOrder::save($dataSaleOrder);

                    // заполняем заказ
                    $totalAmountCur = 0;
                    if(!empty($item['info'])){
                        foreach ($item['info']  as $itemOrder) {
                            $modelWarehouse = Products::findOne([
                                'idInMarket'=>(int)$itemOrder['product_id']
                            ]);

                            if(!empty($modelWarehouse->delovod_id)){
                                $amountCur = ($itemOrder['quantity']*$itemOrder['price']);
                                $totalAmountCur += $amountCur;

                                $dataSaleOrderGoods['tableParts']['tpGoods'][]=[
                                    'good'=>$modelWarehouse->delovod_id,
                                    'goodType'=>'1004000000000014',
                                    'unit' => '1103600000000001',
                                    'qty'=>(int)$itemOrder['quantity'],
                                    'price'=>$itemOrder['price'],
                                    'amountCur'=>$amountCur
                                ];

                                $dataSaleGoods['tableParts']['tpGoods'][]=[
                                    'good'=>$modelWarehouse->delovod_id,
                                    'goodType'=>'1004000000000014',
                                    'unit' => '1103600000000001',
                                    'qty'=>(int)$itemOrder['quantity'],
                                    'baseQty'=>(int)$itemOrder['quantity'],
                                    'price'=>$itemOrder['price'],
                                    'amountCur'=>$amountCur,
                                    'priceAmount'=>$amountCur
                                ];
                            }
                        }

                        SaleOrderTpGoods::save($dataSaleOrderGoods,1,$idSaleOrder);
                    }

                    // создаем расходная накладную
                    $dataSale = [
                        'date'          =>  $item['date'],
                        'number'        =>  $item['order_id'],
                        'baseDoc'       =>  $idSaleOrder,
                        'firm'          =>  '1100400000001004',
                        'business'      =>  '1115000000000001',
                        'storage'       =>  $warehouse,
                        'person'        =>  '1100100000000001',
                        'contract'      =>  $idSaleOrder,
                        'operationType' =>  '1004000000000018',
                        'currency'      =>  '1101200000001001',
                        'amountCur'     =>  $totalAmountCur,
                        'rate'          =>  '1.0000',
                        'department'    =>  '1101900000000001',
                        'author'        =>  '1000200000001004',
                        'costItem'      =>  '1106100000000003',
                        'incomeItem'    =>  '1106500000000002',
                        'priceType'     =>  '1101300000001001',
                        'state'         =>  '1111500000000005',

                        'operMode'      =>  1,
                        'docMode'      =>  0

                    ];


                    $idSale = Sale::save($dataSale);
                    SaleTpGoods::save($dataSaleGoods,1,$idSale);



                    // создаем платеж
                    $dataCash = [
                        'date'=>$item['date'],

                        'number'=>$item['order_id'],

                        'baseDoc' => $idSaleOrder,

                        'firm' => '1100400000001004',
                        'cashAccount' => CashAccounts::getIdForPaymentCode($item['payment_code']),
                        'person' => '1100100000000001',
                        'currency' => '1101200000001001',
                        'content' => 'Поступления от реализации товаров',
                        'contract' => $idSaleOrder,
                        'cashItem' => '1104300000001001',
                        'amountCur' => $totalAmountCur,
                        'operationType' => '1004000000000018',

                        'department' => '1101900000000001',
                        'orderNumber'=>$item['order_id'],

                        'rate' => '1.0000',
                        'author' => '1000200000001004',
                        'business' => '1115000000000001'
                    ];

                    CashIn::save($dataCash,1);


                    header('Content-Type: text/html; charset=utf-8');
                    echo '<xmp>';
                    print_r('ok');
                    echo '</xmp>';
                    die();






                    //finish one
                    die();
                }
            }
        }





        die();
    }


    public function actionXz()
    {
        //заказ 1109100000001010
        //расходная накладная 1106800000001012


        $infogoods = SaleTpGoods::getGoodsForSale('1106800000001012');

        echo '<xmp>';
        print_r($infogoods);
        echo '</xmp>';


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
                        'good'=>'1100300000003148',
                        'goodType'=>'1004000000000014',
                        'unit' => $arrayUnits[rtrim(THelper::t($item->unit),'.')],
                        'qty'=>(int)$count,
                        'price'=>$price,
                        'amountCur'=>$price*$count
                    ];


                    header('Content-Type: text/html; charset=utf-8');
                    echo "<xmp>";
                    print_r($packet);
                    echo "</xmp>";
                    die();

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

    public function getOrderForMonth()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"http://vipsite.biz/admin/get_order.php");
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return json_decode($server_output,TRUE);
    }


    public function actionTest()
    {
        $listCountry = Settings::getListCountry();

        $response = $turnover = [];

        $model = Products::find()
            ->select(['product','productSet'])
            ->where(['productSet'=>[
                '$exists' => true
            ]])
            ->all();
        $listProduct = $listProductName = [];
        foreach ($model as $item) {
            if(!empty($item->productSet)){
                foreach ($item->productSet as $itemSet) {
                    if(empty($listProduct[$item->product][$itemSet['setName']])){
                        $listProduct[$item->product][$itemSet['setName']]=0;
                    }
                    $listProduct[$item->product][$itemSet['setName']]++;

                    $listProductName[$itemSet['setName']]=0;
                }
            }
        }
        $model = Sales::find()
            ->select(['product','idUser','price'])
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDateTime(strtotime('2018-05-01 00:00:01') * 1000),
                    '$lte' => new UTCDateTime(strtotime('2018-05-31 23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',array_keys($listProduct)])
            ->all();
        foreach ($model as $item) {

            $country = \app\models\Users::findOne(['_id'=>$item->idUser])->country;

            if(empty($country)){
                $country = 'none';
            }

            $country = strtolower($country);


            $listProductOrder = $listProduct[$item->product];
            foreach ($listProductOrder as $kProduct => $itemProduct) {
                if(empty($response[$country][$kProduct])){
                    $response[$country][$kProduct] = 0;
                }

                $response[$country][$kProduct] += $itemProduct;

            }


            if(empty($turnover[$country])){
                $turnover[$country] = 0;
            }
            $turnover[$country] += $item->price;


        }

//
//        header('Content-Type: text/html; charset=utf-8');
//        echo '<xmp>';
//        print_r($turnover);
//        echo '</xmp>';
//        die();

        $bl = '
            <table>
                <tr>
                    <th>Страна
            ';
        foreach ($listProductName as $kP=>$itemP){
            $bl .= '<th> ' .$kP;
        }
        $bl .= '<th> Товарооборот';

        foreach ($response as $k=>$item) {
            $bl .= '
                <tr>
                    <td>' . $listCountry[$k];
            foreach ($listProductName as $kP=>$itemP){
                $bl .= '<td> ' .(!empty($item[$kP]) ? $item[$kP] : '0');
            }

            $bl .= '<th> ' . $turnover[$k];
        }
        $bl.='</table>';

        echo $bl;
        die();
    }


    public function actionGetListUsersClub()
    {
        $model = WellnessClubMembers::find()->all();


        foreach ($model as $item) {
            $loginUser = \app\models\Users::findOne(['_id'=>$item->userId])->username;

            echo "'".$loginUser ."',";
        }

        die();
    }



}