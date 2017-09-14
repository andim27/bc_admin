<?php

namespace app\modules\business\controllers;

use app\models\PartsAccessories;
use app\models\Warehouse;
use MongoDB\BSON\ObjectID;
use Yii;
use app\controllers\BaseController;

/**
 * Class WarehousesController
 * @package app\modules\business\controllers
 */
class WarehousesController extends BaseController {

    /**
     * @return string
     */
    public function actionStockWarehouses()
    {
        $headAdminId = '';
        $hideFilter = 0;
        $infoWarehouse = Warehouse::getInfoWarehouse();
        if((string)$infoWarehouse->_id != '592426f6dca7872e64095b45'){
            $hideFilter = 1;

            $headAdminId = (string)$infoWarehouse->headUser;
        }

        $request =  Yii::$app->request->post();
        if(empty($headAdminId) && !empty($request['listRepresentative'])){
            $headAdminId = $request['listRepresentative'];
        }

        $model = Warehouse::find();
        if(!empty($headAdminId)){
            $model = $model->where(['headUser'=>new ObjectID($headAdminId)]);
        }
        $model = $model->all();

        return $this->render('stock-warehouses',[
            'language' => Yii::$app->language,
            'model' => $model,
            'request' => $request,
            'hideFilter' => $hideFilter,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }

    public function actionUpdateStockWarehouses($id)
    {
        $listGoods = PartsAccessories::getListPartsAccessoriesForSaLe();
        $infoProduct = [];

        if(!empty($id)){
            $model = Warehouse::findOne(['_id'=>new ObjectID($id)]);

            foreach ($listGoods as $k=>$item) {
                $infoProduct[$k]['count']  = (!empty($model->stock[$k]) ? $model->stock[$k]['count'] : '0');
            }
        }

        return $this->renderAjax('_update-stock-warehouses', [
            'language' => Yii::$app->language,
            'infoProduct' => $infoProduct,
            'model' => $model
        ]);
    }

    public function actionSaveStockWarehouses()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();


        if(!empty($request['warehouse_id'])){

            $model = Warehouse::findOne(['_id'=>new ObjectID($request['warehouse_id'])]);

            $info = [];
            foreach ($request['product_id'] as $k=>$v){
                $info[$v]['count'] = (int)(!empty($request['stock'][$k]) ? $request['stock'][$k] : 0);
            }
            $model->stock = $info;

            if($model->save()){}

            Yii::$app->session->setFlash('alert' ,[
                    'typeAlert'=>'success',
                    'message'=>'Сохранения применились.'
                ]
            );
        }

        return $this->redirect('/' . Yii::$app->language .'/business/warehouses/stock-warehouses');
    }
}