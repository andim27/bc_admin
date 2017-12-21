<?php

namespace app\components;
use yii\base\Widget;
use app\models\api;
use Yii;

class SidebarWidget extends Widget {

    public $items;
    public $supportHref = '';

    public function init()
    {
        $this->items = api\settings\Menu::items();

        if (isset($this->items->support) && $this->items->support) {
            $links = api\settings\Link::get();

            if ($links && $links->support) {
                $this->supportHref = UrlHelper::getValidUrl($links->support);
            }
        }
    }

    public function run()
    {
        return $this->render('sidebar', [
            'items'             => $this->items,
            'class_a'           => 'class="active"',
            'currentController' => Yii::$app->controller->id,
            'currentAction'     => Yii::$app->controller->action->id,
            'currentModule'     => Yii::$app->controller->module->id,
            'supportHref'       => $this->supportHref
        ]);
    }
}