<?php

namespace app\modules\business\controllers;

use Yii;
use app\controllers\BaseController;
use app\models\Users;

class SendingWaitingParcelController extends BaseController {

    public function actionSendingWaitingParcel()
    {
        $model = '';

        return $this->render('sending-waiting-parcel',[
            'language' => Yii::$app->language,
            'model' => $model
        ]);
    }

    public function actionAddEditParcel()
    {
        $language = Yii::$app->language;

        $listAdmin = Users::getAllAdmin();
        $infoWarehouse = [];
        foreach ($listAdmin as $item){
            if(!empty($item->warehouseName[$language])){
                $infoWarehouse[(string)$item->_id] = [
                    'id'        => (string)$item->_id,
                    'warehouse' => $item->warehouseName[$language],
                    'adminName' => $item->secondName . ' ' . $item->firstName,
                ];
            }
        }

        return $this->renderAjax('_add-edit-parcel',[
            'language' => $language,
            'infoWarehouse' => $infoWarehouse
        ]);
    }

    public function actionSaveParcel()
    {
        $request = Yii::$app->request->post();

        header('Content-Type: text/html; charset=utf-8');
        echo "<xmp>";
        print_r($request);
        echo "</xmp>";
        die();
    }
    
}