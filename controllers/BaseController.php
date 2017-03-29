<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\api;

class BaseController extends Controller
{
    public $user;

    public function beforeAction($action)
    {
        $cookies = Yii::$app->request->cookies;

        $accountIdFromCookies = $cookies->get('user');
        $userFromSession = Yii::$app->session->get('user');

        if ($userFromSession) {
            $this->user = $this->user = api\User::get($userFromSession->accountId);
            if (! $this->user) {
                return $this->goHome();
            }
        } else if ($accountIdFromCookies) {
            $this->user = api\User::get($accountIdFromCookies);
            if ($this->user) {
                Yii::$app->session->set('user', $this->user);
            } else {
                $cookies = Yii::$app->response->cookies;
                $cookies->remove('user');
                return $this->goHome();
            }
        } else {
            return $this->goHome();
        }

        $links = api\settings\Link::get();
        $list = api\user\Link::get($this->user->id);

        $favico = api\Image::get('favico', Yii::$app->language);
        $logo = api\Image::get('topHeaderLogo', Yii::$app->language);

        $this->view->params = array_merge($this->view->params, [
            'links' => $links,
            'user'  => $this->user,
            'list'  => $list,
            'favico' => $favico,
            'logo'  => $logo
        ]);

        return true;
    }
}