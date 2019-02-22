<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;

class SiteController extends BaseController {

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


}
