<?php

namespace app\modules\business\controllers;
use app\controllers\BaseController;
use Yii;
use app\models\api;

class NewsController extends BaseController
{
    public function actionIndex()
    {
        $user = $this->user;

        $currLangName = strtoupper(Yii::$app->language);
        api\User::setLanguage($user->id, $currLangName);
        $unreadedIds = [];
        foreach (api\News::getUnreaded($user->id) as $news) {
            $unreadedIds[] = $news->id;
        }

        return $this->render('index', [
            'news' => api\News::all(Yii::$app->language),
            'unreadedIds' => $unreadedIds
        ]);
    }

    public function actionShowNews()
    {
        $newsId = Yii::$app->request->get('id');

        return json_encode(api\News::read($this->user->id, $newsId));
    }

    public function actionSeenNews()
    {
        return json_encode($this->user->unreadedNotifications());
    }
}