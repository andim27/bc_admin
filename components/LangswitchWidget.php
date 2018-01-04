<?php

namespace app\components;
use yii\base\Widget;
use app\models\api;

class LangswitchWidget extends Widget {
     
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('@app/modules/settings/views/locale/LangswitchWidget', [
            'languages' => api\dictionary\Lang::supported()
        ]);
    }
}
