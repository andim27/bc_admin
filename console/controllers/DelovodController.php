<?php

namespace app\console\controllers;

use app\components\ApiDelovod;
use app\models\apiDelovod\CashAccounts;
use app\models\apiDelovod\CashIn;
use app\models\apiDelovod\Purchase;
use app\models\apiDelovod\Sale;
use app\models\apiDelovod\SaleOrder;
use app\models\apiDelovod\SaleOrderTpGoods;
use app\models\apiDelovod\SaleTpGoods;
use app\models\PartsAccessories;
use app\models\Products;
use app\models\SendingWaitingParcel;
use app\models\Warehouse;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use yii\console\Controller;

/**
 * Class DelovodController
 * @package app\console\controllers
 */
class DelovodController extends Controller{

    private $listActionForCron = [
        //'SetPurchase',
        'SetOrder'
    ];

    public function actionCron(){

        $listActionForCron = $this->listActionForCron;

        $pathFile = \Yii::getAlias('@apiDelovod');

        if (!file_exists($pathFile)) {
            mkdir($pathFile, 0775, true);
        }

        $pathFile .= '/cron.txt';
        if (!file_exists($pathFile)) {
            $fp = fopen($pathFile, "w");
            fclose($fp);
        }

        $content = file_get_contents($pathFile);

        if(!empty($content)){
            $usedAction = $content;
            $usedAction = array_search($usedAction,$this->listActionForCron) + 1;

            if(!empty($listActionForCron[$usedAction])){
                $usingAction = $usedAction;
            } else {
                $usingAction = 0;
            }

        } else {
            $usingAction = 0;
        }

        file_put_contents($pathFile,$this->listActionForCron[$usingAction]);

        return  $this->{'action'.$this->listActionForCron[$usingAction]}();
    }

    public function actionTest()
    {
        $modelWarehouse = Products::find()->all();

        header('Content-Type: text/html; charset=utf-8');
        echo '<xmp>';
        print_r($modelWarehouse['0']);
        echo '</xmp>';
        die();
    }




    public function actionSetOrder()
    {

        $CounterNumber = 0;
        $stopNumber = 3;

        $checkLog = ApiDelovod::getLog();

        if(!empty($checkLog)){
            die();
        }

        $listOrderForMonth = $this->getOrderForMonth();

        if(!empty($listOrderForMonth)){

            foreach ($listOrderForMonth as $k=>$item) {

                if ($CounterNumber >= $stopNumber){
                    break;
                }

                $warehouse = '1100700000000001';
                if(!empty($item['warehouse'])){
                    $warehouseInfo = Warehouse::getInfoWarehouse($item['warehouse']);
                    if(!empty($warehouseInfo->delovod_id)){
                        $warehouse = (string)$warehouseInfo->delovod_id;
                    }
                }

                $cashAccount = CashAccounts::getIdForPaymentCode($item['payment_code']);

                $item['order_id'] = 'b-'.$item['order_id'];

                // создаем документ заказа
                if(SaleOrder::check($item['order_id']) === false && !empty($cashAccount)){

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
                    $dataSaleOrderGoods = $dataSaleGoods = [];
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
                        'docMode'       =>  0

                    ];


                    $idSale = Sale::save($dataSale);
                    SaleTpGoods::save($dataSaleGoods,1,$idSale);

                    // создаем платеж
                    $dataCash = [
                        'date'=>$item['date'],

                        'number'=>$item['order_id'],

                        'baseDoc' => $idSaleOrder,

                        'firm' => '1100400000001004',
                        'cashAccount' => $cashAccount,
                        'person' => '1100100000000001',
                        'currency' => '1101200000001001',
                        'content' => 'Поступления от реализации товаров',
                        'contract' => $idSaleOrder,
                        'cashItem' => '1104300000001001',
                        'amountCur' => $totalAmountCur,
                        'operationType' => '1004000000000018',

                        'department' => '1101900000000001',
                        //'orderNumber'=>$item['order_id'],

                        'rate' => '1.0000',
                        'author' => '1000200000001004',
                        'business' => '1115000000000001'
                    ];

                    //print_r($dataCash);

                    CashIn::save($dataCash,1);

                    $CounterNumber++;

                } else if(empty($cashAccount)) {
                    ApiDelovod::setLog('Not payment - '.$item['payment_code'].' for order - ' . $item['order_id']);
                }


            }
        }

        die();
    }

    public function actionSetPurchase(){

        $CounterNumber = 0;
        $stopNumber = 3;

        $checkLog = ApiDelovod::getLog();

        if(!empty($checkLog)){
            die();
        }

        $queryDateFrom = strtotime(date('Y-m-d',strtotime('-15 day', strtotime(date('Y-m-d')))).' 00:00:00') * 1000;
        $queryDateTo = strtotime(date('Y-m-d').' 23:59:59') * 1000;

        $model = SendingWaitingParcel::find()
            ->where([
                'from_where_send'=>'592426f6dca7872e64095b45',
                'where_sent'=>'5a056671dca7873e022be781',
                'is_posting'=>1
            ])
            ->andWhere([
                'date_update' => [
                    '$gte' => new UTCDateTime($queryDateFrom),
                    '$lte' => new UTCDateTime($queryDateTo)
                ]
            ])
            ->all();

        if(!empty($model)){
            foreach ($model as $item) {

                $item->id = 'b-'.$item->id;

                if(Purchase::check($item->id) === false) {

                    if ($CounterNumber >= $stopNumber) {
                        break;
                    }

                    $dataGoods = [];

                    $data = [
                        'number' => $item->id,
                        'date' => $item->date_update->toDateTime()->format('Y-m-d H:i:s'),
                        'firm' => '1100400000001002',
                        'storage' => '1100700000000001',
                        'person' => '1100100000001044',
                        'operationType' => '1004000000000050',
                        'currency' => '1101200000001001',
                        'author' => '1000200000001004',
                        'paymentForm' => '1110300000000002',
                        'department' => '1101900000000001'
                    ];

                    foreach ($item->part_parcel as $itemPart) {
                        $modelPartsAccessories = PartsAccessories::findOne(['_id' => new ObjectId($itemPart['goods_id'])]);
                        if (!empty($modelPartsAccessories) && !empty($modelPartsAccessories->delovod_id)) {
                            $dataGoods[] = [
                                'good' => $modelPartsAccessories->delovod_id,
                                'goodType' => '1004000000000014',
                                'unit' => '1103600000000001',
                                'qty' => (int)$itemPart['goods_count'],
                            ];
                        }
                    }

                    Purchase::save($data,1,false,$dataGoods);

                    $CounterNumber++;

                }
            }
        }

        die();
    }

    protected function getOrderForMonth()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"http://vipsite.biz/admin/get_order.php");
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        return json_decode($server_output,TRUE);
    }

}