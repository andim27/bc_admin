<?php

namespace app\modules\business\controllers;

use app\components\ArrayInfoHelper;
use app\components\GoodException;
use app\components\THelper;
use app\controllers\BaseController;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Repayment;
use app\models\RepaymentAmounts;
use app\models\Sales;
use app\models\SendingWaitingParcel;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use yii\base\Theme;
use yii\helpers\ArrayHelper;


class SaleReportController extends BaseController
{
    /**
     * report not issued goods for client
     * @return string
     */
    public function actionInfoWaitSaleByUser()
    {

        $listCountry = [];

        $infoWarehouse = Warehouse::getInfoWarehouse();

        $allListCountry = Settings::getListCountry();

        $request =  Yii::$app->request->post();

        if(empty($request['countryReport'])) {
            if(Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)===false){
                $request['countryReport'] = $infoWarehouse->country;
            } else {
                $request['countryReport'] = 'all';
            }
        }

        $request['goodsReport'] = (empty($request['goodsReport']) ? 'all' : $request['goodsReport']);

        $infoSale = $infoGoods = [];

        if (Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)) {
            $listCountry['all'] = 'Все страны';
        }

        $listCountry[$request['countryReport']] = ($request['countryReport']=='all' ? 'Все страны' : $allListCountry[$request['countryReport']]);

        $model = StatusSales::find()
            ->where(['IN','setSales.status',['status_sale_new','status_sale_delivered',
                'status_sale_repairs_under_warranty',
                'status_sale_repair_without_warranty',]
            ])
            ->all();
        if(!empty($model)){
            $listIdSale = ArrayHelper::getColumn($model,'idSale');

            $model = Sales::find()
                ->where(['IN','_id',$listIdSale])
                ->andWhere([
                    'type' => [
                        '$ne'   =>  -1
                    ]
                ])
                ->andWhere(['in','product',Products::productIDWithSet()])
                ->all();



            if(!empty($model)){
                /** @var \app\models\Sales $item */
                foreach ($model as $item) {
                    $tempInfoUser = [];
                    if(!empty($item->statusSale) && ($request['countryReport'] == 'all' || $request['countryReport'] == $item->infoUser->country)){

                        $tempInfoUser['_id'] = (string)$item->_id;

                        $tempInfoUser['name'] = $item->infoUser->secondName . ' ' . $item->infoUser->firstName . '('. $item->infoUser->username.')';
                        $tempInfoUser['country'] = $item->infoUser->country;
                        $tempInfoUser['city'] = $item->infoUser->city;
                        $tempInfoUser['address'] = $item->infoUser->address;


                        $tempInfoUser['phone']= [];
                        if(!empty($item->infoUser->phoneNumber)){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],[$item->infoUser->phoneNumber]);
                        }
                        if(!empty($item->infoUser->phoneNumber2)){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],[$item->infoUser->phoneNumber2]);
                        }
                        if(!empty($item->infoUser->settings['phoneViber'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['Viber' => $item->infoUser->settings['phoneViber']]);
                        }
                        if(!empty($item->infoUser->settings['phoneFB'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['FB' => $item->infoUser->settings['phoneFB']]);
                        }
                        if(!empty($item->infoUser->settings['phoneTelegram'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['Telegram' => $item->infoUser->settings['phoneTelegram']]);
                        }
                        if(!empty($item->infoUser->settings['phoneWhatsApp'])){
                            $tempInfoUser['phone'] = ArrayHelper::merge($tempInfoUser['phone'],['WhatsApp' => $item->infoUser->settings['phoneWhatsApp']]);
                        }

                        $tempInfoUser['date_create'] = $item->dateCreate->toDateTime()->format('Y-m-d H:i:s');
                        $tempInfoUser['type'] = $item->type;

                        /** @var StatusSales $itemSet */
                        foreach($item->statusSale->set as $k=>$itemSet){


                            if($request['goodsReport'] == 'all' || $request['goodsReport'] == $itemSet->title) {

                                $tempInfoGoods = [];

                                $tempInfoGoods['countryWarehouse'] = 'none';
                                $tempInfoGoods['nameWarehouse'] = '';

                                if(!empty($itemSet->idUserChange)){
                                    $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                    if(!empty($infoWarehouse->country)){
                                        $tempInfoGoods['countryWarehouse'] = $infoWarehouse->country;
                                        $tempInfoGoods['nameWarehouse'] = $infoWarehouse->title;

                                        if(empty($listCountry[$infoWarehouse->country])){
                                            $listCountry[$infoWarehouse->country] = $allListCountry[$infoWarehouse->country];
                                        }
                                    }
                                }

                                $tempInfoGoods['key'] = $k;
                                $tempInfoGoods['goods'] = $itemSet->title;
                                $tempInfoGoods['status'] = $itemSet->status;

                                $infoGoods[$itemSet->title] = (!empty($infoGoods[$itemSet->title]) ? ($infoGoods[$itemSet->title] +1) : '1');

                                if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                    $infoSale[] = ArrayHelper::merge($tempInfoUser, $tempInfoGoods);
                                }
                            }
                        }

                    }
                }
            }
        }


        return $this->render('info-wait-sale-by-user',[
            'language' => Yii::$app->language,
            'request' => $request,
            'infoSale' => $infoSale,
            'listCountry' => $listCountry,
            'infoGoods' => $infoGoods,


            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionChangeWarehouse()
    {
        $request = Yii::$app->request->get();

        $modelInfoSale = Sales::findOne(['_id'=>new ObjectID($request['_id'])]);

        $info = $modelInfoSale->statusSale->setSales[$request['k']];

        $info = [
            'idSale'            => $request['_id'],
            'key'               => $request['k'],
            'idUserWarehpouse'  => (string)$info['idUserChange']
        ];


        return $this->renderAjax('_change-warehouse', [
            'language' => Yii::$app->language,
            'info' => $info,
        ]);
    }

    public function actionSaveChangeWarehouse(){
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        $modelInfoSale = Sales::findOne(['_id'=>new ObjectID($request['idSale'])]);

        $info = $modelInfoSale->statusSale;

        $temp = $info->setSales;
        $temp[$request['key']]['idUserChange'] = new ObjectID($request['warehouse_id']);

        $info->setSales = $temp;

        if($info->save()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }


        return $this->redirect('/' . Yii::$app->language .'/business/sale-report/info-wait-sale-by-user');
    }

    public function actionInfoSaleForCountry()
    {
        $listGoodsWithKey = Products::getListGoodsWithKey();

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-m-d", strtotime( $request['to']." -6 months"));
        }

        if(empty($request['send_kh'])){
            $request['send_kh'] = 0;
        }

        if(empty($request['flGoods'])){
            if(empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';

        } else {
            $request['listGoods'] = '';
        }

        $infoSale = [];


        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();


        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($request['listPack'])){

                    if($item->product == $request['listPack'] || $request['listPack'] == 'all') {

                        $country = 'none';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if (empty($infoSale[$country][$item->productName])) {
                            $infoSale[$country][$item->productName] = [
                                'all'       => 0,
                                'issued'    => 0,
                                'in_stock'  => 0,
                                'wait'      => 0,
                                'repair'    => 0,
                                'send'      => 0,
                            ];
                        }

                        if ($flIssuedPack == '1') {
                            $infoSale[$country][$item->productName]['issued']++;
                        } else {
                            $infoSale[$country][$item->productName]['wait']++;
                        }

                        if($flRepairsPack == '1'){
                            $infoSale[$country][$item->productName]['repair']++;
                        }

                        $infoSale[$country][$item->productName]['all']++;
                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            $country = 'none';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }


                            if(empty($infoSale[$country][$itemSet->title])){
                                $infoSale[$country][$itemSet->title] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            $infoSale[$country][$itemSet->title]['all']++;

                            if(in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                                $infoSale[$country][$itemSet->title]['issued']++;
                            } else {
                                $infoSale[$country][$itemSet->title]['wait']++;
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $infoSale[$country][$itemSet->title]['repair']++;
                            }
                        }
                    }
                }

            }
        }

        if(!empty($request['listGoods'])) {

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            if($request['send_kh']==1){
                $modelSending = $modelSending->andWhere(['from_where_send'=>'5a056671dca7873e022be781'])->all();
            } else {
                $modelSending = $modelSending->all();
            }

            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }

                    if (!empty($item->part_parcel)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) &&
                                ($listGoodsWithKey[$itemParcel['goods_id']] == $request['listGoods'] || $request['listGoods'] == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];

                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $request['listGoods'] || $request['listGoods'] == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }


                        if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                            $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                'all' => 0,
                                'issued' => 0,
                                'in_stock' => 0,
                                'wait' => 0,
                                'repair' => 0,
                                'send' => 0,
                            ];
                        }

                        $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                    }

                }
            }

        }


        return $this->render('info-sale-for-country',[
            'language'      =>   Yii::$app->language,
            'infoSale'      =>   $infoSale,
            'request'       =>   $request
        ]);
    }

    public function actionExportInfoSaleForCountry($from,$to,$flGoods,$listPack,$listGoods)
    {
        $listGoodsWithKey = Products::getListGoodsWithKey();
        $listCountry = Settings::getListCountry();

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($from . '00:00:01') * 1000),
                    '$lte' => new UTCDateTime(strtotime($to . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();


        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($listPack)){

                    if($item->product == $listPack || $listPack == 'all') {

                        $country = 'none';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if (empty($infoSale[$country][$item->productName])) {
                            $infoSale[$country][$item->productName] = [
                                'all'       => 0,
                                'issued'    => 0,
                                'in_stock'  => 0,
                                'wait'      => 0,
                                'repair'    => 0,
                                'send'      => 0,
                            ];
                        }

                        if ($flIssuedPack == '1') {
                            $infoSale[$country][$item->productName]['issued']++;
                        } else {
                            $infoSale[$country][$item->productName]['wait']++;
                        }

                        if($flRepairsPack == '1'){
                            $infoSale[$country][$item->productName]['repair']++;
                        }

                        $infoSale[$country][$item->productName]['all']++;
                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $listGoods || $listGoods == 'all'){

                            $country = 'none';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                }
                            }


                            if(empty($infoSale[$country][$itemSet->title])){
                                $infoSale[$country][$itemSet->title] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            $infoSale[$country][$itemSet->title]['all']++;

                            if(in_array($itemSet->status,['status_sale_issued','status_sale_issued_after_repair'])){
                                $infoSale[$country][$itemSet->title]['issued']++;
                            } else {
                                $infoSale[$country][$itemSet->title]['wait']++;
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $infoSale[$country][$itemSet->title]['repair']++;
                            }
                        }
                    }
                }

            }
        }

        if(!empty($listGoods)) {

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            $modelSending = $modelSending->all();

            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }

                    if (!empty($item->part_parcel)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) &&
                                ($listGoodsWithKey[$itemParcel['goods_id']] == $listGoods || $listGoods == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];

                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $listGoods  || $listGoods == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }


                        if (empty($infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                            $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                'all' => 0,
                                'issued' => 0,
                                'in_stock' => 0,
                                'wait' => 0,
                                'repair' => 0,
                                'send' => 0,
                            ];
                        }

                        $infoSale[$item->infoWarehouse->country][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                    }

                }
            }
        }

        if($flGoods == 1){
            $infoExport = [];
            foreach ($infoSale as $kCountry=>$itemCountry) {
                foreach ($itemCountry as $k=>$item) {
                    $infoExport[] = [
                        'country'       => !empty($listCountry[$kCountry]) ? $listCountry[$kCountry] : 'none',
                        'product_name'  => $k,
                        'all'           => $item['all'],
                        'issued'        => $item['issued'],
                        'margin'        => ($item['issued'] + $item['send'] + $item['in_stock'] - $item['all']),
                        'repair'        => $item['repair']
                    ];
                }

            }


            \moonland\phpexcel\Excel::export([
                'models' => $infoExport,
                'fileName' => 'export_'.$from.'-'.$to,
                'columns' => [
                    'country',
                    'product_name',
                    'all',
                    'issued',
                    'margin',
                    'repair'
                ],
                'headers' => [
                    'country' => THelper::t('country'),
                    'product_name' => THelper::t('business_product'),
                    'all' => THelper::t('number_all_ordering'),
                    'issued' => THelper::t('number_issue'),
                    'margin' =>THelper::t('number_difference'),
                    'repair' => THelper::t('number_repair')
                ]
            ]);
        } else {$infoExport = [];

            foreach ($infoSale as $kCountry=>$itemCountry) {
                foreach ($itemCountry as $k=>$item) {
                    $infoExport[] = [
                        'country'       => !empty($listCountry[$kCountry]) ? $listCountry[$kCountry] : 'none',
                        'product_name'  => $k,
                        'all'           => $item['all'],
                        'issued'        => $item['issued'],
                        'in_stock'      => $item['in_stock'],
                        'send'          => $item['send'],
                        'margin'        => ($item['issued'] + $item['send'] + $item['in_stock'] - $item['all']),
                        'repair'        => $item['repair']
                    ];
                }

            }


            \moonland\phpexcel\Excel::export([
                'models' => $infoExport,
                'fileName' => 'export_'.$from.'-'.$to,
                'columns' => [
                    'country',
                    'product_name',
                    'all',
                    'issued',
                    'in_stock',
                    'send',
                    'margin',
                    'repair'
                ],
                'headers' => [
                    'country' => THelper::t('country'),
                    'product_name' => THelper::t('goods'),
                    'all' => THelper::t('number_all_ordering'),
                    'issued' => THelper::t('number_issue'),
                    'in_stock' => THelper::t('number_in_stock'),
                     'send' => THelper::t('number_send'),
                    'margin' =>THelper::t('number_difference'),
                    'repair' => THelper::t('number_repair')
                ]
            ]);
        }

        die();
    }

    public function actionInfoSaleForCountryWarehouse()
    {
        $listCountry = Settings::getListCountry();
        $listGoodsWithKey = Products::getListGoodsWithKey();

        $request =  Yii::$app->request->post();

        $listCountryWarehouse = [];

        if(empty($request['listCountry'])){
            $request['listCountry'] = 'all';
        }

        if(empty($request['send_kh'])){
            $request['send_kh'] = 0;
        }

        if(empty($request['flGoods'])){
            if(empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';

        } else {
            $request['listGoods'] = '';
        }

        $infoSale = [];

        $dateTo = date("Y-m-d");
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -6 months"));;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($dateFrom) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();




        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($request['listPack'])){

                    if($item->product == $request['listPack'] || $request['listPack'] == 'all') {

                        $country = 'none';
                        $warehouse = '';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if($request['listCountry'] == 'all' || $request['listCountry'] == $country){
                            if (empty($infoSale[$country][$warehouse][$item->productName])) {
                                $infoSale[$country][$warehouse][$item->productName] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            if ($flIssuedPack == '1') {
                                $infoSale[$country][$warehouse][$item->productName]['issued']++;
                            } else {
                                $infoSale[$country][$warehouse][$item->productName]['wait']++;
                            }

                            if($flRepairsPack == '1'){
                                $infoSale[$country][$warehouse][$item->productName]['repair']++;
                            }

                            $infoSale[$country][$warehouse][$item->productName]['all']++;
                        }

                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            $country = 'none';
                            $warehouse = '';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if($request['listCountry'] == $country || $request['listCountry'] == 'all') {
                                if (empty($infoSale[$country][$warehouse][$itemSet->title])) {
                                    $infoSale[$country][$warehouse][$itemSet->title] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$country][$warehouse][$itemSet->title]['all']++;

                                if (in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['issued']++;
                                } else {
                                    $infoSale[$country][$warehouse][$itemSet->title]['wait']++;
                                }

                                if (in_array($itemSet->status, ['status_sale_repairs_under_warranty', 'status_sale_repair_without_warranty'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['repair']++;
                                }

                            }
                        }
                    }
                }

            }
        }


        if(!empty($request['listGoods'])) {

            $selectWarehouseKh = [];
            if($request['send_kh']==1){
                $selectWarehouseKh = ['from_where_send'=>new ObjectID('5a056671dca7873e022be781')];
            }

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            if($request['send_kh']==1){
                $modelSending = $modelSending->andWhere(['from_where_send'=>'5a056671dca7873e022be781'])->all();
            } else {
                $modelSending = $modelSending->all();
            }


            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }
                    if (empty($item->infoWarehouse->title)) {
                        $item->infoWarehouse->title = 'none';
                    }

                    if (!empty($item->part_parcel) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) && ($listGoodsWithKey[$itemParcel['goods_id']] == $request['listGoods'] || $request['listGoods'] == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all' => 0,
                                        'issued' => 0,
                                        'in_stock' => 0,
                                        'wait' => 0,
                                        'repair' => 0,
                                        'send' => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];
                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $request['listGoods'] || $request['listGoods'] == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }
                        if (empty($item->infoWarehouse->title)) {
                            $item->infoWarehouse->title = 'none';
                        }

                        if (!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {

                            if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                    'all' => 0,
                                    'issued' => 0,
                                    'in_stock' => 0,
                                    'wait' => 0,
                                    'repair' => 0,
                                    'send' => 0,
                                ];
                            }

                            $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                        }
                    }
                }
            }

        }

        asort($listCountryWarehouse);

        return $this->render('info-sale-for-country-warehouse',[
            'language'              =>   Yii::$app->language,
            'infoSale'              =>   $infoSale,
            'listCountryWarehouse'  =>   $listCountryWarehouse,
            'request'               =>   $request
        ]);
    }

    public function actionInfoSaleForCountryWarehouseExcel()
    {
        $listCountry = Settings::getListCountry();
        $listGoodsWithKey = Products::getListGoodsWithKey();

        $request =  Yii::$app->request->post();

        $listCountryWarehouse = [];

        if(empty($request['listCountry'])){
            $request['listCountry'] = 'all';
        }

        if(empty($request['send_kh'])){
            $request['send_kh'] = 0;
        }

        if(empty($request['flGoods'])){
            if(empty($request['listGoods'])){
                $request['listGoods'] = 'all';
            }
            $request['listPack'] = '';

        } else {
            $request['listGoods'] = '';
        }

        $infoSale = [];

        $dateTo = date("Y-m-d");
        $dateFrom = date("Y-m-d", strtotime( $dateTo." -6 months"));;

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($dateFrom) * 1000),
                    '$lte' => new UTCDateTime(strtotime($dateTo . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere(['in','product',Products::productIDWithSet()])
            ->all();




        if(!empty($model)){

            /** @var Sales $item */
            foreach ($model as $item) {

                /** PACK */
                if(!empty($request['listPack'])){

                    if($item->product == $request['listPack'] || $request['listPack'] == 'all') {

                        $country = 'none';
                        $warehouse = '';
                        $flIssuedPack = '1';
                        $flRepairsPack = '0';
                        foreach ($item->statusSale->set as $itemSet) {

                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if (!in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                $flIssuedPack = '0';
                            }

                            if(in_array($itemSet->status,['status_sale_repairs_under_warranty','status_sale_repair_without_warranty'])){
                                $flRepairsPack = '1';
                            }
                        }

                        if($request['listCountry'] == 'all' || $request['listCountry'] == $country){
                            if (empty($infoSale[$country][$warehouse][$item->productName])) {
                                $infoSale[$country][$warehouse][$item->productName] = [
                                    'all'       => 0,
                                    'issued'    => 0,
                                    'in_stock'  => 0,
                                    'wait'      => 0,
                                    'repair'    => 0,
                                    'send'      => 0,
                                ];
                            }

                            if ($flIssuedPack == '1') {
                                $infoSale[$country][$warehouse][$item->productName]['issued']++;
                            } else {
                                $infoSale[$country][$warehouse][$item->productName]['wait']++;
                            }

                            if($flRepairsPack == '1'){
                                $infoSale[$country][$warehouse][$item->productName]['repair']++;
                            }

                            $infoSale[$country][$warehouse][$item->productName]['all']++;
                        }

                    }
                    /** GOODS */
                } else {
                    foreach ($item->statusSale->set as $itemSet){

                        if($itemSet->title == $request['listGoods'] || $request['listGoods'] == 'all'){

                            $country = 'none';
                            $warehouse = '';
                            if(!empty($itemSet->idUserChange)) {
                                $infoWarehouse = Warehouse::getInfoWarehouse((string)$itemSet->idUserChange);

                                if (!empty($infoWarehouse->country)) {
                                    $country = $infoWarehouse->country;
                                    $listCountryWarehouse[$infoWarehouse->country] = $listCountry[$infoWarehouse->country];
                                }
                                if (!empty($infoWarehouse->title)) {
                                    $warehouse = $infoWarehouse->title;
                                }
                            }

                            if($request['listCountry'] == $country || $request['listCountry'] == 'all') {
                                if (empty($infoSale[$country][$warehouse][$itemSet->title])) {
                                    $infoSale[$country][$warehouse][$itemSet->title] = [
                                        'all'               => 0,
                                        'issued'            => 0,
                                        'in_stock'          => 0,
                                        'wait'              => 0,
                                        'repair'            => 0,
                                        'send'              => 0,
                                    ];
                                }

                                $infoSale[$country][$warehouse][$itemSet->title]['all']++;

                                if (in_array($itemSet->status, ['status_sale_issued', 'status_sale_issued_after_repair'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['issued']++;
                                } else {
                                    $infoSale[$country][$warehouse][$itemSet->title]['wait']++;
                                }

                                if (in_array($itemSet->status, ['status_sale_repairs_under_warranty', 'status_sale_repair_without_warranty'])) {
                                    $infoSale[$country][$warehouse][$itemSet->title]['repair']++;
                                }

                            }
                        }
                    }
                }

            }
        }


        if(!empty($request['listGoods'])) {

            $selectWarehouseKh = [];
            if($request['send_kh']==1){
                $selectWarehouseKh = ['from_where_send'=>new ObjectID('5a056671dca7873e022be781')];
            }

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);

            if($request['send_kh']==1){
                $modelSending = $modelSending->andWhere(['from_where_send'=>'5a056671dca7873e022be781'])->all();
            } else {
                $modelSending = $modelSending->all();
            }


            if (!empty($modelSending)) {
                foreach ($modelSending as $item) {

                    if (empty($item->infoWarehouse->country)) {
                        $item->infoWarehouse->country = 'none';
                    }
                    if (empty($item->infoWarehouse->title)) {
                        $item->infoWarehouse->title = 'none';
                    }

                    if (!empty($item->part_parcel) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {
                        foreach ($item->part_parcel as $itemParcel) {
                            if(!empty($listGoodsWithKey[$itemParcel['goods_id']]) && ($listGoodsWithKey[$itemParcel['goods_id']] == $request['listGoods'] || $request['listGoods'] == 'all')) {
                                if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]])) {
                                    $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]] = [
                                        'all' => 0,
                                        'issued' => 0,
                                        'in_stock' => 0,
                                        'wait' => 0,
                                        'repair' => 0,
                                        'send' => 0,
                                    ];
                                }

                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[$itemParcel['goods_id']]]['send'] += $itemParcel['goods_count'];
                            }
                        }
                    }

                }
            }

            // get info about info for goods in warehouse
            $modelWarehouseGoods = PartsAccessoriesInWarehouse::find()->all();
            if (!empty($modelWarehouseGoods)) {
                foreach ($modelWarehouseGoods as $item) {
                    if(!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($listGoodsWithKey[(string)$item->parts_accessories_id] == $request['listGoods'] || $request['listGoods'] == 'all')) {

                        if (empty($item->infoWarehouse->country)) {
                            $item->infoWarehouse->country = 'none';
                        }
                        if (empty($item->infoWarehouse->title)) {
                            $item->infoWarehouse->title = 'none';
                        }

                        if (!empty($listGoodsWithKey[(string)$item->parts_accessories_id]) && ($request['listCountry'] == 'all' || $request['listCountry'] == $item->infoWarehouse->country)) {

                            if (empty($infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]])) {
                                $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]] = [
                                    'all' => 0,
                                    'issued' => 0,
                                    'in_stock' => 0,
                                    'wait' => 0,
                                    'repair' => 0,
                                    'send' => 0,
                                ];
                            }

                            $infoSale[$item->infoWarehouse->country][$item->infoWarehouse->title][$listGoodsWithKey[(string)$item->parts_accessories_id]]['in_stock'] += $item->number;

                        }
                    }
                }
            }

        }

        asort($listCountryWarehouse);

        $amount = [
            'ordering'  => 0,
            'issued'    => 0,
            'in_stock'  => 0,
            'send'      => 0,
            'repair'    => 0,
            'margin'    => 0,
        ];

        $infoExport = [];
        if(!empty($infoSale)){
            foreach ($infoSale as $k=>$itemWarehouse) {
                foreach($itemWarehouse as $kWarehouse=>$itemGoods) {
                    foreach($itemGoods as $kGoods=>$item) {
                        $margin = $item['issued'] + $item['send'] + $item['in_stock'] - $item['all'];
                        $amount['ordering'] += $item['all'];
                        $amount['issued'] += $item['issued'];
                        $amount['in_stock'] += $item['in_stock'];
                        $amount['send'] += $item['send'];
                        $amount['repair'] += $item['repair'];
                        $amount['margin'] += $margin;

                        $infoExport[] = [
                            'country'           =>  (!empty($listCountry[$k]) ? $listCountry[$k] : 'none'),
                            'warehouse'         =>  $kWarehouse,
                            'title'             =>  $kGoods,
                            'order'             =>  $item['all'],
                            'issued'            =>  $item['issued'],

                            'inStock'           =>  (!empty($request['listGoods']) ? $item['in_stock'] : '-'),
                            'send'              =>  (!empty($request['listGoods']) ? $item['send'] : '-'),

                            'different'         =>  $margin,
                            'inRepair'          =>  $item['repair'],
                        ];
                    }
                }

            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'country',
                'warehouse',
                'title',
                'order',
                'issued',
                'inStock',
                'send',
                'different',
                'inRepair'
            ],
            'headers' => [
                'country'           =>  'Страна',
                'warehouse'         =>  'Склад',
                'title'             =>  (!empty($request['listGoods']) ? 'Название товара' : 'Название пака'),
                'order'             =>  'Заказано',
                'issued'            =>  'Выданно',
                'inStock'           =>  'В наличии',
                'send'              =>  'Отправленно',
                'different'         =>  'Разница',
                'inRepair'          =>  'На ремонте',
            ],
        ]);

        die();

    }

    public function actionReportProjectVipcoin(){
        $infoWarehouse = Warehouse::getInfoWarehouse();

        $allListCountry = Settings::getListCountry();
        $totatPrice = 0;
        $infoSale = [];
        $listCountry = [];
        $listCity = [];

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to']=date("Y-m-d");
            $date = strtotime('-3 month', strtotime($request['to']));
            $request['from'] = date('Y-m-d', $date);
        }

        $listAvailableCities = [];
        if(Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)===false){
            $request['countryReport'] = $infoWarehouse->country;

            if(!empty($infoWarehouse->cities) && empty($request['cityReport'])){
                $listAvailableCities = $infoWarehouse->cities;
                $listCity = ArrayInfoHelper::getArrayEqualKeyValue($listAvailableCities);
            }
        }

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [1,9,10]
                ]
            ])
            ->andWhere([
                'productData.categories' => [
                    '$elemMatch' => ['$eq'=>'5b4c46469ed4eb002a683891']
                ]
            ])
            ->all();
        if(!empty($model)){
            foreach ($model as $item) {

                $country = mb_strtolower($item->infoUser->country);
                $listCountry[$country]=$allListCountry[$country];

                if(empty($request['countryReport']) || ($request['countryReport']==$country)){
                    $city = (!empty($item->infoUser->city) ? $item->infoUser->city : 'None');

                    if(empty($listAvailableCities)){
                        $listCity[$city] = $city;
                    }

                    if((empty($request['cityReport']) && empty($listAvailableCities))
                        || (empty($request['cityReport']) && !empty($listAvailableCities) && in_array($city,$listAvailableCities))
                        || (!empty($request['cityReport']) && in_array($city,$request['cityReport']))
                        ){
                        $infoSale[] = [
                            'dateCreate' => $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                            'userCountry'=>$allListCountry[$country],
                            'userCity'=>$city,
                            'userAddress'=>$item->infoUser->address,
                            'userName'=>$item->infoUser->secondName .' ' . $item->infoUser->firstName,
                            'userPhone'=>$item->infoUser->phoneNumber . ' / ' . $item->infoUser->phoneNumber2,
                            'productName' => $item->productName,
                            'productPrice' => $item->price
                        ];

                        $totatPrice += $item->price;
                    }
                }


            }

            asort($listCountry);
            asort($listCity);
        }


        return $this->render('report-project-vipcoin',[
                'language' => Yii::$app->language,
                'request' => $request,
                'totatPrice' => $totatPrice,
                'infoSale' => $infoSale,
                'listCountry' => $listCountry,
                'listCity' => $listCity
            ]
        );
    }

    public function actionReportProjectVipcoinExcel()
    {
        $infoWarehouse = Warehouse::getInfoWarehouse();

        $allListCountry = Settings::getListCountry();

        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to']=date("Y-m-d");
            $date = strtotime('-3 month', strtotime($request['to']));
            $request['from'] = date('Y-m-d', $date);
        }

        if(Warehouse::checkWarehouseKharkov((string)$infoWarehouse->_id)===false){
            $request['countryReport'] = $infoWarehouse->country;

            if(!empty($infoWarehouse->cities) && empty($request['cityReport'])){
                $listAvailableCities = $infoWarehouse->cities;
                $listCity = ArrayInfoHelper::getArrayEqualKeyValue($listAvailableCities);
            }
        }

        $model = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere([
                'productType' => [
                    '$in' => [1,9,10]
                ]
            ])
            ->andWhere([
                'productData.categories' => [
                    '$elemMatch' => ['$eq'=>'5b4c46469ed4eb002a683891']
                ]
            ])
            ->all();

        $infoExport = [];
        if(!empty($model)){
            foreach ($model as $item) {

                $country = mb_strtolower($item->infoUser->country);

                $listCountry[$country] = $allListCountry[$country];

                if(empty($request['countryReport']) || ($request['countryReport']==$country)){
                    $city = (!empty($item->infoUser->city) ? $item->infoUser->city : 'None');

                    if(empty($listAvailableCities)){
                        $listCity[$city] = $city;
                    }

                    if((empty($request['cityReport']) && empty($listAvailableCities))
                        || (empty($request['cityReport']) && !empty($listAvailableCities) && in_array($city,$listAvailableCities))
                        || (!empty($request['cityReport']) && in_array($city,$request['cityReport']))
                    ){
                        $infoExport[] = [
                            'date_create' => $item->dateCreate->toDateTime()->format('Y-m-d H:i:s'),
                            'country'=>$allListCountry[$country],
                            'city'=>$city,
                            'address'=>$item->infoUser->address,
                            'full_name'=>$item->infoUser->secondName .' ' . $item->infoUser->firstName,
                            'phone'=>$item->infoUser->phoneNumber . ' / ' . $item->infoUser->phoneNumber2,
                            'goods' => $item->productName,
                            'price' => $item->price
                        ];

                    }
                }


            }
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'date_create',
                'country',
                'city',
                'address',
                'full_name',
                'phone',
                'goods',
                'price'
            ],
            'headers' => [
                'date_create'   =>  THelper::t('date_create'),
                'country'       =>  THelper::t('country'),
                'city'          =>  THelper::t('city'),
                'address'       =>  THelper::t('address'),
                'full_name'     =>  THelper::t('full_name'),
                'phone'         =>  THelper::t('phone'),
                'goods'         =>  THelper::t('goods'),
                'price'         =>  THelper::t('price')
            ],
        ]);

        die();
    }

    /**
     * make report for charges representative
     * @return string
     * @throws GoodException
     */
    public function actionReportChargesRepresentative()
    {
        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['date'] = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        }

        $report = [];

        $listRepresentative = Warehouse::getListHeadAdmin();
        foreach ($listRepresentative as $k=>$item) {
            $report[$k] = [
                'title'         => $item,
                'percent'       => 0,
                'goods_turnover'=> 0,
                'issued_for_amount'=> 0,
                'accrued'       => 0,
                'deduction'     => 0,
                'repayment'     => 0,
                'goods'         => []
            ];
        }

        $modelRepaymentAmounts = RepaymentAmounts::find()->all();
        /** @var RepaymentAmounts $item */
        foreach ($modelRepaymentAmounts as $item){
            if(!empty($item->prices_representative[$request['date']])){
                $representativeId = (string)$item->warehouse->headUser;

                if(empty($report[$representativeId]['goods'][(string)$item->product_id])){
                    $report[$representativeId]['percent'] = $item->prices_representative[$request['date']]['percent'];
                    $report[$representativeId]['goods_turnover'] = $item->prices_representative[$request['date']]['goods_turnover'];
                    $report[$representativeId]['goods'][(string)$item->product_id] = [
                        'title' => $item->product->title,
                        'price' => 0,
                        'count' => 0
                    ];
                }

                $report[$representativeId]['goods'][(string)$item->product_id]['price'] += $item->prices_representative[$request['date']]['price'];
                $report[$representativeId]['goods'][(string)$item->product_id]['count'] += $item->prices_representative[$request['date']]['count'];

                @$report[$representativeId]['issued_for_amount'] += round(($item->prices_representative[$request['date']]['price']/$report[$representativeId]['percent']*100),2);

            }
//            else {
//                throw new GoodException('Отчет не возможно сфомировать','Нет выплат по данной дате');
//            }
        }

        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$in' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date']])
            ->all();
        if(!empty($modelRepayment)){
            /** @var Repayment $item */
            foreach ($modelRepayment as $item) {
                $representativeId = (string)$item->representative_id;

                if(isset($report[$representativeId])){
                    $report[$representativeId]['accrued'] += $item->accrued;
                    $report[$representativeId]['deduction'] += $item->deduction;
                    $report[$representativeId]['repayment'] += $item->repayment;
                }
            }
        }

        return $this->render('report-charges-representative',[
                'language' => Yii::$app->language,
                'request' => $request,
                'report' => $report
            ]
        );
    }

    /**
     * make report for charges representative
     * @return string
     * @throws GoodException
     */
    public function actionReportChargesWarehouse()
    {
        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['representative'] = '';
            $request['date'] = date('Y-m', strtotime('-1 month', strtotime(date("Y-m"))));
        }

        $report = [];

        $listWarehouse = Warehouse::getArrayWarehouse();

        foreach ($listWarehouse as $k=>$item) {
            $report[$k] = [
                'title'         => $item,
                'representative_id'=>'',
                'percent'       => 0,
                'goods_turnover'=> 0,
                'issued_for_amount'=> 0,
                'accrued'       => 0,
                'deduction'     => 0,
                'repayment'     => 0,
                'goods'         => []
            ];
        }

        $modelRepaymentAmounts = RepaymentAmounts::find()->all();
        /** @var RepaymentAmounts $item */
        foreach ($modelRepaymentAmounts as $item){
            if(!empty($item->prices_warehouse[$request['date']])){
                $representativeId = (string)$item->warehouse->headUser;
                $warehouse_id = (string)$item->warehouse_id;

                if(empty($report[$warehouse_id]['goods'][(string)$item->product_id])){
                    $report[$warehouse_id]['representative_id'] = $representativeId;
                    $report[$warehouse_id]['percent'] = $item->prices_warehouse[$request['date']]['percent'];
                    $report[$warehouse_id]['goods_turnover'] = $item->prices_warehouse[$request['date']]['goods_turnover'];
                    $report[$warehouse_id]['goods'][(string)$item->product_id] = [
                        'title' => $item->product->title,
                        'price' => 0,
                        'count' => 0
                    ];
                }

                $report[$warehouse_id]['goods'][(string)$item->product_id]['price'] += $item->prices_warehouse[$request['date']]['price'];
                $report[$warehouse_id]['goods'][(string)$item->product_id]['count'] += $item->prices_warehouse[$request['date']]['count'];

                if($report[$warehouse_id]['percent']>0){
                    $report[$warehouse_id]['issued_for_amount'] += round(($item->prices_warehouse[$request['date']]['price']/$report[$warehouse_id]['percent']*100),2);
                }
            }
//            else {
//                throw new GoodException('Отчет не возможно сфомировать','Нет выплат по данной дате');
//            }
        }

        $modelRepayment = Repayment::find()
            ->where([
                'warehouse_id'=>[
                    '$nin' => [null]
                ]
            ])
            ->andWhere(['date_for_repayment'=>$request['date']])
            ->all();
        if(!empty($modelRepayment)){
            /** @var Repayment $item */
            foreach ($modelRepayment as $item) {
                $warehouseId = (string)$item->warehouse_id;

                if(isset($report[$warehouseId])){
                    $report[$warehouseId]['accrued'] += $item->accrued;
                    $report[$warehouseId]['deduction'] += $item->deduction;
                    $report[$warehouseId]['repayment'] += $item->repayment;
                }
            }
        }

        foreach ($report as $k=>$item) {
            if(!empty($request['representative']) && $request['representative']!=$item['representative_id']){
                unset($report[$k]);
            }
        }


        return $this->render('report-charges-warehouse',[
                'language' => Yii::$app->language,
                'request' => $request,
                'report' => $report
            ]
        );
    }
    //------------b:--Report balance up
    public function actionReportBalanceUp()
    {
        $request =  Yii::$app->request->post();

        if(empty($request)){
            $request['to'] = date("Y-m-d");
            $request['from'] = date("Y-m-d", strtotime( $request['to']." -1 months"));
        }
        $dateTo = $request['to'];
        $dateFrom =  $request['from'];

        $infoSale = Sales::find()
            ->where([
                'dateCreate' => [
                    '$gte' => new UTCDatetime(strtotime($request['from']) * 1000),
                    '$lte' => new UTCDateTime(strtotime($request['to'] . '23:59:59') * 1000)
                ]
            ])
            ->andWhere([
                'type' => [
                    '$ne'   =>  -1
                ]
            ])
            ->andWhere([
                'product' => 9001
            ])
            ->orderBy(['dateCreate'=>SORT_DESC]) //SORT_ASC//SORT_DESC//
            ->all();
        return $this->render('report-balance-up',[
                'language' => Yii::$app->language,
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo,
                'report'   => $dateFrom,
                'infoSale' => $infoSale
            ]
        );
    }
    //------------e:--Report balance up

}