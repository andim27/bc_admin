<?php

namespace app\controllers;

use app\components\THelper;
use Yii;
use app\models\LoginForm;
use yii\web\HttpException;
use app\models\api;
use app\modules\settings\models\locale;
use app\models\PassResetFormEmail;
use app\models\PassResetFormMessenger;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends \yii\web\Controller
{
    /**
     * Login
     *
     * @return string|Response
     * @throws HttpException
     */
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
            $login = $_POST['LoginForm']['email'];
//            $user = api\User::auth(, $_POST['LoginForm']['password']);
            $apiUrl = Yii::$app->params['apiAddress'] . 'auth/' . urlencode(mb_strtolower($login)) . '&' . $_POST['LoginForm']['password'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            $response = curl_exec($ch);
            $info = curl_getinfo($ch);

            if ($info['http_code'] == 200) {
                preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $response, $ms);
                $authCookie = isset($ms[1][0]) ? $ms[1][0] : '';
                if ($authCookie) {
                    $cookies = Yii::$app->response->cookies;

                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'auth_cookie',
                        'value' => $authCookie,
                        'expire' => time() + 24 * 3600
                    ]));
                }

                $user = api\User::get($login);
            }

            curl_close($ch);

            $rememberMe = $_POST['LoginForm']['rememberMe'];

            if (isset($user) && $user) {
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

    /**
     * Logout
     *
     * @return Response
     */
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
     * Reset password forms
     *
     * @return string
     */
    public function actionReset()
    {
        $passResetFormEmail = new PassResetFormEmail();
        $passResetFormMessenger = new PassResetFormMessenger();

        $resetPassTime = Yii::$app->request->cookies->getValue('reset_pass_time');

        if ($resetPassTime) {
            $minutes = (int)(($resetPassTime - time()) / 60);
            $seconds = ($resetPassTime - time()) - ($minutes * 60);

            if (strlen($seconds) == 1) {
                $seconds = '0' . $seconds;
            }

            $resetPassTime = $minutes . ':' . $seconds;
        }

        return $this->renderAjax('recovery', [
            'modelEmail' => $passResetFormEmail,
            'modelMessenger' => $passResetFormMessenger,
            'resetPassTime' => $resetPassTime ? $resetPassTime : false
        ]);
    }

    /**
     * Reset password via email
     *
     * @return array|bool|string
     */
    public function actionResetEmail()
    {
        $passResetForm = new PassResetFormEmail();

        if (Yii::$app->request->isAjax && $passResetForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            ActiveForm::validate($passResetForm);
            if ($passResetForm->hasErrors()) {
                $result = ActiveForm::validate($passResetForm);
            } else {
                $resetPassTime = Yii::$app->request->cookies->getValue('reset_pass_time');
                if (! $resetPassTime) {
                    $result = api\User::resetPasswordByEmail(strtolower($passResetForm->email));

                    if ($result) {
                        $resetPassTime = time() + 5 * 60; // 5 minutes
                        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                            'name' => 'reset_pass_time',
                            'value' => $resetPassTime,
                            'expire' => $resetPassTime
                        ]));
                    }
                } else {
                    $result = false;
                }
            }

            return $result;
        }

        return false;
    }

    /**
     * Reset password via messengers
     *
     * @return array|bool|string
     */
    public function actionResetMessenger()
    {
        $passResetForm = new PassResetFormMessenger();

        if (Yii::$app->request->isAjax && $passResetForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            ActiveForm::validate($passResetForm);
            if ($passResetForm->hasErrors()) {
                $result = ActiveForm::validate($passResetForm);
            } else {
                $resetPassTime = Yii::$app->request->cookies->getValue('reset_pass_time');
                if (! $resetPassTime) {
                    $result = api\User::resetPasswordByMessenger($passResetForm->messenger, $passResetForm->messengerNumber);

                    if ($result) {
                        $resetPassTime = time() + 5 * 60; // 5 minutes
                        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                            'name' => 'reset_pass_time',
                            'value' => $resetPassTime,
                            'expire' => $resetPassTime
                        ]));
                    }
                } else {
                    $result = false;
                }
            }

            return $result;
        }

        return false;
    }

    /**
     *
     */
    public function actionSimple()
    {
        $session = Yii::$app->session;
        $cookies = Yii::$app->request->cookies;

        $language = locale::getCurrent();
        if (! $language) {
            throw new HttpException(500);
        }

        if ($session->get('user') || $cookies->has('user')) {
            return $this->redirect('/' . $language->prefix . '/business');
        } else {
            $user = Yii::$app->request->get('user');
            $key = Yii::$app->request->get('key');

            if (sha1($user . Yii::$app->params['secretKey']) == $key) {
                $user = api\User::get($user);
                if ($user) {
                    if (! $session->isActive) {
                        $session->open();
                    } else {
                        $session->set('user', $user);
                    }
                } else {
                    $this->actionLogout();
                }
            }

            return $this->redirect('/' . $language->prefix . '/business');
        }
    }

}
