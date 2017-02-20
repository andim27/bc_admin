<?php

namespace app\components;
use yii\base\Widget;
use app\models\api;
use Yii;

class SidebarWidget extends Widget {
    public function init()
    {

    }

    public function run()
    {
        return $this->render('sidebar', [
            'class_a'           => 'class="active"',
            'currentController' => Yii::$app->controller->id,
            'currentAction'     => Yii::$app->controller->action->id,
            'currentModule'     => Yii::$app->controller->module->id
        ]);
    }
}