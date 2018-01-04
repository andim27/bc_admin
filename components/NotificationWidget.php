<?php

namespace app\components;

use yii\base\Widget;
use app\models\api;
use Yii;

class NotificationWidget extends Widget {

    public $notifications = [];
    public $currLangName;
    public $q;

    public function init()
    {
        $user = Yii::$app->session->get('user');
        $this->currLangName = strtoupper(Yii::$app->language);
        $this->q = $user->layout == 0 ? 'light' : 'dark';

        if ($user) {
            api\User::setLanguage($user->id, $this->currLangName);
            $this->notifications = api\News::getUnreaded($user->id);
            $this->notifications = array_merge($this->notifications, api\Promotion::getUnreaded($user->id));
        }

        usort($this->notifications, array($this, '_sort'));
    }

    public function run()
    {
        return $this->render('notification', [
            'q' => $this->q,
            'notifications' => $this->notifications,
            'currLangName' => $this->currLangName
        ]);
    }

    private function _sort($first, $second)
    {
        if ($first->dateCreate < $second->dateCreate) {
            return 1;
        } elseif($first->dateCreate > $second->dateCreate) {
            return -1;
        } else {
            return 0;
        }
    }
}