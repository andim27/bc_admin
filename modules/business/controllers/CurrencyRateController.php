<?php

namespace app\modules\business\controllers;



use app\models\CurrencyRate;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Yii;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;

class CurrencyRateController extends BaseController {

    public function actionCurrencyRate()
    {
        $model = CurrencyRate::find()->all();

        return $this->render('currency-rate',[
            'language' => Yii::$app->language,
            'model' => $model,
            'alert' => Yii::$app->session->getFlash('alert', '', true)
        ]);
    }
    
    public function actionAddCurrencyRate()
    {
        Yii::$app->session->setFlash('alert' ,[
                'typeAlert' => 'danger',
                'message' => 'Сохранения не применились, что то пошло не так!!!'
            ]
        );

        $request = Yii::$app->request->post();

        $model = new CurrencyRate();

        if(!empty($request)){
            $model->dateCreate = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $model->eur = 1;
            $model->usd = (float)$request['usd'];
            $model->uah = (float)$request['uah'];
            $model->rub = (float)$request['rub'];

            if($model->save()){

                Yii::$app->session->setFlash('alert' ,[
                        'typeAlert'=>'success',
                        'message'=>'Сохранения применились.'
                    ]
                );

            }


        }


        return $this->redirect('/' . Yii::$app->language .'/business/currency-rate/currency-rate');
    }
}