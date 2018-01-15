<?php

namespace app\console\controllers;

use app\components\ApiDelovod;
use app\models\apiDelovod\CashAccounts;
use app\models\apiDelovod\CashIn;
use app\models\apiDelovod\Sale;
use app\models\apiDelovod\SaleOrder;
use app\models\apiDelovod\SaleOrderTpGoods;
use app\models\apiDelovod\SaleTpGoods;
use app\models\Products;
use app\models\Warehouse;
use yii\console\Controller;

class DelovodController extends Controller{

    public function actionTest()
    {
//        $modelWarehouse = Products::find()->all();
//
//        header('Content-Type: text/html; charset=utf-8');
//        echo '<xmp>';
//        print_r($modelWarehouse['0']);
//        echo '</xmp>';
//        die();
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
                        'orderNumber'=>$item['order_id'],

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