<?php

namespace app\modules\business\controllers;

use app\models\CurrencyRate;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\PlanningPurchasing;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;

class PlanningPurchasingController extends BaseController {

    public function actionPlanning()
    {
        $model = PlanningPurchasing::find()->all();

        return $this->render('planning',[
            'language' => Yii::$app->language,
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionSavePlanning()
    {

        Yii::$app->session->setFlash('alert' ,[
                'typeAlert'=>'danger',
                'message'=>'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        if(!empty($request)){
            $model = new PlanningPurchasing();

            $model->parts_accessories_id = new ObjectID($request['parts_accessories_id']);
            $model->need_collect = (int)$request['need'];
            $model->date_create =  new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $complect = [];
            if (isset($request['complect'])) {
                foreach ($request['complect'] as $k=>$item){
                    $complect[] = [
                        'parts_accessories_id' => $item ,
                        'needForOne' => $request['needForOne'][$k] ,
                        'priceForOne' => $request['priceForOne'][$k] ,
                        'buy' => $request['buy'][$k] ,
                    ];
                }
            }

            $model->complect = $complect;

        }

        if($model->save()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/planning-purchasing/planning');
    }

    public function actionMakePlanning()
    {
        return $this->renderAjax('_make-planning',[
            'language' => Yii::$app->language
        ]);
    }

    public function actionAllComponents()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['PartsAccessoriesId'])){
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['PartsAccessoriesId'])]);
            return $this->renderPartial('_all-components', [
                'language' => Yii::$app->language,
                'model' => $model,
            ]);
        }

        return false;
    }

    public function actionUpdateChangeableList()
    {
        $request = Yii::$app->request->post();

        return $this->renderPartial('_update-changeable-list',[
                'infoComposite'     =>  ['_id'=>$request['goodsParent'],'number'=>1],
                'selectedGoodsId'   =>  $request['goodsId'],
                'level'             =>  $request['goodsLevel'],
                'count'             =>  $request['goodsCount'],
                'wantMake'          =>  $request['wantMake']
            ]
        );
    }

    public function actionLookPlanning($id)
    {
        $model = PlanningPurchasing::findOne(['_id'=>new ObjectID($id)]);

        return $this->renderPartial('_look-planning',[
            'model' => $model
        ]);
    }

    public function actionRemovePlanning($id)
    {
        if(PlanningPurchasing::findOne(['_id'=>new ObjectID($id)])->delete()){
            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Удаление прошло успешно.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/planning-purchasing/planning');
    }

    public function actionMultiplierPlanningPurchasing(){
        $info = [];

        $request = Yii::$app->request->post();

        if(!empty($request)){

            $listId = $listCount = [];
            foreach ($request['wantMake']['id'] as $k=>$item){
                $listId[] = new ObjectID($item);
                $listCount[$item] = $request['wantMake']['count'][$k];
            }

            $model = PartsAccessories::find()->where(['IN','_id',$listId])->all();


            foreach ($model as $item) {
                foreach ($item->composite as $itemComposite) {
                    $info = $this->getComplectInfo($info,(string)$itemComposite['_id'],($listCount[(string)$item->_id]*$itemComposite['number']));
                }
            }
        }

        return $this->render('multiplier-planning-purchasing',[
            'language' => Yii::$app->language,
            'info' => $info,
            'request' => $request,
        ]);
    }

    public function actionMultiplierPlanningPurchasingExcel()
    {

        $request = Yii::$app->request->get();

        $infoExport = $info = [];

        if(!empty($request)){
            $amount = 0;
            $partAccessoriesInWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();
            $partAccessoriesAll = PartsAccessories::getListPartsAccessories();
            $partAccessoriesPricePurchase = PartsAccessories::getPricePurchase();
            $actualCurrency = CurrencyRate::getActualCurrency();

            $request['listGoodsId'] = explode("::",$request['listGoodsId']);
            $request['listGoodsCount'] = explode("::",$request['listGoodsCount']);

            $listId = $listCount = [];
            foreach ($request['listGoodsId'] as $k=>$item){
                $listId[] = new ObjectID($item);
                $listCount[$item] = $request['listGoodsCount'][$k];
            }

            $model = PartsAccessories::find()->where(['IN','_id',$listId])->all();

            foreach ($model as $item) {
                foreach ($item->composite as $itemComposite) {
                    $info = $this->getComplectInfo($info,(string)$itemComposite['_id'],($listCount[(string)$item->_id]*$itemComposite['number']));
                }
            }

            foreach ($info as $k=>$item) {
                $inWarehouse = (!empty($partAccessoriesInWarehouse[$k]) ? $partAccessoriesInWarehouse[$k] : '0');
                $needCount = $item-$inWarehouse;
                $needCount = ($needCount > 0 ? $needCount : '0');
                $priceAmount = $partAccessoriesPricePurchase[$k]*$needCount;
                $amount += $priceAmount;

                $infoExport[] = [
                    'productTitle'      =>  $partAccessoriesAll[$k],
                    'needCount'         =>  $item,
                    'inWarehouseCount'  =>  $inWarehouse,
                    'needBuy'           =>  $needCount,
                    'priceForOne'       =>  $partAccessoriesPricePurchase[$k],
                    'amountPrice'       =>  $priceAmount
                ];
            }

            $infoExport[] = [
                'productTitle'      =>  'Итого',
                'needCount'         =>  '',
                'inWarehouseCount'  =>  $amount . ' eur',
                'needBuy'           =>  round($amount*$actualCurrency['usd'],2) . ' usd',
                'priceForOne'       =>  round($amount*$actualCurrency['uah'],2) . ' uah',
                'amountPrice'       =>  round($amount*$actualCurrency['rub'],2) . ' rub',
            ];
        }

        \moonland\phpexcel\Excel::export([
            'models' => $infoExport,
            'fileName' => 'export '.date('Y-m-d H:i:s'),
            'columns' => [
                'productTitle',
                'needCount',
                'inWarehouseCount',
                'needBuy',
                'priceForOne',
                'amountPrice'
            ],
            'headers' => [
                'productTitle'      =>  'Товар',
                'needCount'         =>  'Необходимо',
                'inWarehouseCount'  =>  'На складе',
                'needBuy'           =>  'Нужно заказать',
                'priceForOne'       =>  'Цена за шт',
                'amountPrice'       =>  'Сумма'
            ],
        ]);

        die();
    }

    protected function getComplectInfo($info,$id,$number){

        $model = PartsAccessories::findOne(['_id'=>new ObjectID($id)]);
        if(!empty($model->composite)){
            foreach ($model->composite as $itemComposite){
                $info = $this->getComplectInfo($info,(string)$itemComposite['_id'],($number*$itemComposite['number']));
            }
        } else {

            if(empty($info[$id])){
                $info[$id] = 0;
            }

            $info[$id] += $number;
        }

        return $info;
    }

}