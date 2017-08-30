<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Products;
use app\models\Sales;
use app\models\SendingWaitingParcel;
use app\models\Settings;
use app\models\StatusSales;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use yii\helpers\ArrayHelper;


class SaleReportController extends BaseController 
{
    /**
     * report not issued goods for client
     * @return string
     */
    public function actionInfoWaitSaleByUser()
    {

        $allListCountry = Settings::getListCountry();

        $request =  Yii::$app->request->post();
        $request['countryReport'] = (empty($request['countryReport']) ? 'all' : $request['countryReport']);
        $request['goodsReport'] = (empty($request['goodsReport']) ? 'all' : $request['goodsReport']);

        $infoSale = $infoGoods = [];

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

        $listCountry['all'] = 'Все страны';

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
                $modelSending = $modelSending->andWhere(['from_where_send'=>'592426f6dca7872e64095b45'])->all();
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
                $selectWarehouseKh = ['from_where_send'=>new ObjectID('592426f6dca7872e64095b45')];
            }

            // get info about sending parcel with goods
            $modelSending = SendingWaitingParcel::find()
                ->where(['is_posting' => (int)0])
                ->orWhere(['is_posting' => (string)0]);
            
            if($request['send_kh']==1){
                $modelSending = $modelSending->andWhere(['from_where_send'=>'592426f6dca7872e64095b45'])->all();
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
}