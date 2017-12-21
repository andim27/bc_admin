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
            $this->redirect('/');
            return false;
        }

        $links = api\settings\Link::get();

        $list = api\user\Link::get($this->user->id);

        if ($this->user->layout == 0) {
            $a = 'black';
            $q = 'light';
            $u = 'soc';
        } else {
            $a = 'dark';
            $q = 'dark';
            $u = '';
        }

        $this->view->params = array_merge($this->view->params, [
            'links' => $links,
            'a'     => $a,
            'q'     => $q,
            'u'     => $u,
            'user'  => $this->user,
            'list'  => $list
        ]);

        return true;
    }
}