<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use yii\web\HttpException;
use app\models\api;
use app\modules\settings\models\locale;
use app\models\PassResetForm;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends \yii\web\Controller
{
    public function actionLogin()
    {
        $language = locale::getCurrent();
        if (! $language) {
            throw new HttpException(500);
        }

        $this->layout = 'start';
        $session = Yii::$app->session;

        $model = new LoginForm();

        if (Yii::$app->request->post()) {
            $user = api\User::authAdmin($_POST['LoginForm']['email'], $_POST['LoginForm']['password']);
            $rememberMe = $_POST['LoginForm']['rememberMe'];

            if ($user) {
                $user = api\User::get($_POST['LoginForm']['email']);
                if ($rememberMe) {
                    $cookies = Yii::$app->response->cookies;

                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'user',
                        'value' => $user->accountId,
                        'expire' => time() + 24 * 3600
                    ]));
                }

                if(! $session->isActive){
                    $session->open();

                    $session->set('user', $user);
                    $session->set('email', $user->email);
                    $session->set('skype', $user->skype);
                    $session->set('username', $user->username);
                    $session->set('accountId', $user->accountId);
                    $session->set('firstName', $user->firstName);
                    $session->set('secondName', $user->secondName);
                    $session->set('created', $user->created);
                    $session->set('phoneNumber', $user->phoneNumber);
                    $session->set('moneys', $user->moneys);
                    $session->set('layout', $user->layout);
                    $session->set('avatar', $user->avatar);
                    $session->set('id', $user->id);

                    return $this->redirect('/' . $language->prefix . '/business');
                }
            } else {
                $this->actionLogout();
            }
        }

        $cookies = Yii::$app->request->cookies;

        if ($session->get('user') || $cookies->has('user')) {
            return $this->redirect('/' . $language->prefix . '/business');
        } else {

            return $this->render('login', [
                'model' => $model,
                'languages' => api\dictionary\Lang::supported(),
                'year' => gmdate('Y', time()),
                'logo' => api\Image::get('loginLogo', $language->prefix)
            ]);
        }

    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->removeAll();
        $session->close();
        $session->destroy();
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('user');
        return $this->goHome();
    }

    /**
     * Reset password
     */
    public function actionReset()
    {
        $passResetForm = new PassResetForm();

        if (Yii::$app->request->isAjax && $passResetForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            ActiveForm::validate($passResetForm);
            if ($passResetForm->hasErrors()) {
                $result = ActiveForm::validate($passResetForm);
            } else {
                $result = api\User::resetPassword(strtolower($passResetForm->email));;
            }
            return $result;
        } else {
            return $this->renderAjax('recovery', [
                'model' => $passResetForm
            ]);
        }
    }

}
