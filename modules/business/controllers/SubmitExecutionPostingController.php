<?php

namespace app\modules\business\controllers;

use app\models\PartsAccessories;
use MongoDB\BSON\ObjectID;
use Yii;
use app\controllers\BaseController;

class SubmitExecutionPostingController extends BaseController {

    public function actionExecutionPosting()
    {
       
        return $this->render('execution-posting',[
            'language' => Yii::$app->language,
        ]);
    }
    
    public function actionKitExecutionPosting()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['PartsAccessoriesId'])){
            $model = PartsAccessories::findOne(['_id'=>new ObjectID($request['PartsAccessoriesId'])]);
            return $this->renderPartial('_kit-execution-posting', [
                'language' => Yii::$app->language,
                'model' => $model,
            ]);
        }

        return false;
    }

}