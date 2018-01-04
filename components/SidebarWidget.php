<?php

namespace app\components;

use app\models\Menu;
use yii\base\Widget;
use app\models\api;
use Yii;

class SidebarWidget extends Widget {
    public function init()
    {
        
    }

    public function run()
    {
        
        $items = Menu::getItems();

        return $this->render('sidebar', [
            'items'             => $items,
            'class_a'           => 'class="active"',
            'currentController' => Yii::$app->controller->id,
            'currentAction'     => Yii::$app->controller->action->id,
            'currentModule'     => Yii::$app->controller->module->id
        ]);
    }
}