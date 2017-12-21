<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\AlertForm;
use app\models\LandingForm;
use app\models\PassResetFormMessenger;
use app\modules\business\models\AddCell;
use Yii;
use app\models\User;
use yii\web\UploadedFile;
use app\modules\business\models\PasswordForm;
use app\components\THelper;
use app\modules\business\models\ProfileForm;
use app\models\api;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class SettingController extends BaseController {
    const FIN_PASS_TYPE = 1;

    public function actionUnioncell() {
        $session = Yii::$app->getSession();

        $linkedAccounts = api\user\Link::get($this->user->id, true);

        $model = new AddCell();

        $successText = $session->get('successText', '');
        $session->remove('successText');

        $errorText = $session->get('errorText', '');
        $session->remove('errorText');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $fromUser = api\User::get($model->login);
                $result = api\user\Link::link($fromUser->id, $this->user->id);
                if ($result == 'OK') {
                    $session->set('successText', THelper::t('link_success_msg'));
                } else if ($result == 'Conflict') {
                    $session->set('errorText', THelper::t('link_error_msg'));
                }
                $this->refresh();
            }
        }

        return $this->render('unioncell', [
            'linkedAccounts' => $linkedAccounts,
            'model' => $model,
            'successText' => $successText,
            'errorText' => $errorText
        ]);
    }

    public function actionDisconnect($id) {
        $fromUser = api\User::get($id);

        if (api\user\Link::unlink($fromUser->id, $this->user->id)) {
            Yii::$app->getSession()->set('successText', THelper::t('unlink_success_msg'));
        }

        return $this->redirect(['/business/setting/unioncell']);
    }

    public function actionProfile()
    {
        $request = Yii::$app->request;
        $model = new ProfileForm();

        $model->attributes = Yii::$app->request->post();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } else {
            if ($request->isPost) {
                if ($model->validate()) {
                    $result = api\User::update($this->user->accountId, [
                        'username' => strtolower($request->post('login')),
                        'fname' => $request->post('name'),
                        'sname' => $request->post('surname'),
                        'email' => $request->post('email'),
                        'skype' => $request->post('skype', ''),
                        'phone' => $request->post('mobile'),
                        'phone2' => $request->post('smobile', ''),
                        'phoneWellness' => $request->post('phone_wellness', ''),
                        'country' => $request->post('country'),
                        'address' => $request->post('address'),
                        'city' => $request->post('city'),
                        'state' => $request->post('state'),
                        'birthday' => date('Y-m-d', strtotime($request->post('birthday'))),
                        'showMobile' => $request->post('showMobile', 0),
                        'showEmail' => $request->post('showEmail', 0),
                        'showName' => $request->post('showName', 0),
                        'site' => $request->post('site', ''),
                        'vk' => $request->post('vk', ''),
                        'fb' => $request->post('fb', ''),
                        'odnoklassniki' => $request->post('odnoklassniki', ''),
                        'youtube' => $request->post('youtube', '')
                    ]);

                    if ($result) {
                        Yii::$app->session->setFlash('success', THelper::t('your_profile_has_been_saved_successfully'));
                    } else {
                        Yii::$app->session->setFlash('danger', THelper::t('your_profile_has_not_been_saved'));
                    }
                }

                return $this->refresh();
            }
        }

        return $this->render('profile', [
            'user' => $this->user,
            'model' => $model,
            'countries' => api\dictionary\Country::all()
        ]);

    }

    public function actionChangeImg() {
        $session = Yii::$app->session;
        $model = new ProfileForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if (! $model->avatar) {
                    return $this->redirect('profile');
                }

                if ($model->avatar) {
                    if (!$model->afterSave($this->user->id)) {
                        Yii::$app->session->setFlash('danger', THelper::t('avatar_minimum_size_should_be_200_200_dimension'));
                        return $this->redirect('profile');
                    }

                    if (!$model->avatar->extension) {
                        $extension = 'jpg';
                    } else {
                        $extension = $model->avatar->extension;
                    }

                    $model->avatar = base64_encode($model->avatar->baseName) . '.' . $extension;
                    $session->set('avatar', $model->avatar);

                    $path = "uploads/{$this->user->id}/{$model->avatar}";
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    User::updateInfoApi($this->user->accountId, array("avatar" => $base64));
                }
            }

            return $this->redirect('profile');
        }

        return $this->renderAjax('modal', [
            'model' => $model
        ]);
    }

    public function actionAlert() {
        $alertForm = new AlertForm();

        $timezones = api\dictionary\TimeZones::all();

        if ($alertForm->load(Yii::$app->request->post()) && $alertForm->validate()) {
            $timezone = Yii::$app->request->post('timezone', '');
            if ($timezone) {
                $timezone = str_replace('|', '"', $timezone);
            }

            $phoneTelegram = str_replace('+', '', trim($alertForm->phoneTelegram));
            $phoneTelegram = $phoneTelegram ? '+' . $phoneTelegram : '';
            $phoneViber    = str_replace('+', '', trim($alertForm->phoneViber));
            $phoneViber    = $phoneViber ? '+' . $phoneViber : '';
            $phoneWhatsApp = str_replace('+', '', trim($alertForm->phoneWhatsApp));
            $phoneWhatsApp = $phoneWhatsApp ? '+' . $phoneWhatsApp : '';
            $phoneFB       = str_replace('+', '', trim($alertForm->phoneFB));
            $phoneFB       = $phoneFB ? '+' . $phoneFB : '';

            if (api\User::update($this->user->accountId, [
                'notifyAboutJoinPartner'    => $alertForm->notifyAboutJoinPartner,
                'notifyAboutReceiptsMoney'  => $alertForm->notifyAboutReceiptsMoney,
                'notifyAboutReceiptsPoints' => $alertForm->notifyAboutReceiptsPoints,
                'notifyAboutEndActivity' => $alertForm->notifyAboutEndActivity,
                'notifyAboutOtherNews'   => $alertForm->notifyAboutOtherNews,
                'phoneTelegram'          => $phoneTelegram,
                'phoneViber'             => $phoneViber,
                'phoneWhatsApp'          => $phoneWhatsApp,
                'phoneFB'                => $phoneFB,
                'selectedLang'           => $alertForm->selectedLang,
                'timeZone'               => $timezone
            ])) {
                $this->refresh();
            }
        } else {
            $alertForm->notifyAboutJoinPartner = $this->user->settings->notifyAboutJoinPartner;
            $alertForm->notifyAboutReceiptsMoney = $this->user->settings->notifyAboutReceiptsMoney;
            $alertForm->notifyAboutReceiptsPoints = $this->user->settings->notifyAboutReceiptsPoints;
            $alertForm->notifyAboutEndActivity = $this->user->settings->notifyAboutEndActivity;
            $alertForm->notifyAboutOtherNews = $this->user->settings->notifyAboutOtherNews;
            $alertForm->phoneTelegram = $this->user->settings->phoneTelegram;
            $alertForm->phoneViber = $this->user->settings->phoneViber;
            $alertForm->phoneWhatsApp = $this->user->settings->phoneWhatsApp;
            $alertForm->phoneFB = $this->user->settings->phoneFB;
            $alertForm->selectedLang = $this->user->settings->selectedLang;
        }

        return $this->render('alert', [
            'user' => $this->user,
            'model' => $alertForm,
            'languages' => ArrayHelper::map(api\dictionary\Lang::supported(), 'alpha2', 'native'),
            'timezones' => $timezones
        ]);
    }

    public function actionPasswords() {
        $model = new PasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            $data['iduser'] = $this->user->id;
            $data['type'] = $model->type;
            switch ($model->type) {
                case 0:
                    $data['oldPassword'] = $model->currentPassword;
                    $data['newPassword'] = $model->newPassword;
                break;
                case 1:
                    $data['oldPassword'] = $model->currentfinPassword;
                    $data['newPassword'] = $model->newfinPassword;
                break;
            }

            if (api\User::changePassword($data)) {
                Yii::$app->session->setFlash('success', THelper::t('the_password_have_already_changed'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('the_password_is_not_correct'));
            }

            return $this->refresh();
        }

        return $this->render('passwords', [
            'model' => $model,
        ]);
    }

    /**
     * Change user layout
     *
     * @return array
     */
    public function actionChangeLayout()
    {
        if (Yii::$app->request->isAjax) {
            $result = api\User::update($this->user->accountId, ['layout' => $this->user->layout == 1 ? 0 : 1]);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => $result];
        }
    }

    public function actionSaveLatLng()
    {
        if (Yii::$app->request->isAjax) {
            $lat = Yii::$app->request->post('lat');
            $lng = Yii::$app->request->post('lng');
            $accountId = Yii::$app->request->post('accountId');

            api\User::update($accountId, [
                'onMapX' => $lat,
                'onMapY' => $lng
            ]);
        }
    }

    public function actionLanding()
    {
        $landingForm = new LandingForm();

        if ($landingForm->load(Yii::$app->request->post()) && $landingForm->validate()) {
            $result = api\User::update($this->user->accountId, [
                'landingAnalytics' => $landingForm->analytics,
                'landingAnalytics2' => $landingForm->analytics2,
                'landingAnalyticsVipVip' => $landingForm->analyticsVipVip,
                'landingAnalyticsWebwellnessRu' => $landingForm->analyticsWebwellnessRu,
                'landingAnalyticsWebwellnessNet' => $landingForm->analyticsWebwellnessNet,
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', THelper::t('your_setting_landing_has_been_saved_successfully'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('your_setting_landing_has_not_been_saved'));
            }

            return $this->refresh();
        } else {
            $landingForm->analytics = isset($this->user->landing->analytics) ? $this->user->landing->analytics : '';
            $landingForm->analytics2 = isset($this->user->landing->analytics2) ? $this->user->landing->analytics2 : '';
            $landingForm->analyticsVipVip = isset($this->user->landing->analytics_vipvip) ? $this->user->landing->analytics_vipvip : '';
            $landingForm->analyticsWebwellnessRu = isset($this->user->landing->analytics_webwellness_ru) ? $this->user->landing->analytics_webwellness_ru : '';
            $landingForm->analyticsWebwellnessNet = isset($this->user->landing->analytics_webwellness_net) ? $this->user->landing->analytics_webwellness_net : '';
        }

        return $this->render('landing', [
            'model' => $landingForm
        ]);
    }

    public function actionResetFinancePassword()
    {
        $status = false;

        $passResetFormMessenger = new PassResetFormMessenger();

        if ($passResetFormMessenger->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            switch ($passResetFormMessenger->messenger){
                case 'whatsapp':
                    $phone = $this->user->settings->phoneWhatsApp;
                    break;
                case 'viber':
                    $phone = $this->user->settings->phoneViber;
                    break;
                case 'telegram':
                    $phone = $this->user->settings->phoneTelegram;
                    break;
                case 'facebook':
                    $phone = $this->user->settings->phoneFB;
                    break;
            }

            $resetPassTime = Yii::$app->request->cookies->getValue('reset_pass_time');

            if (! $resetPassTime) {
                if (!empty($phone)) {
                    $status = api\User::resetPasswordByMessenger($passResetFormMessenger->messenger, $phone, self::FIN_PASS_TYPE);
                }

                if ($status) {
                    Yii::$app->session->setFlash('success', THelper::t('the_password_sent_by_messenger'));
                    $resetPassTime = time() + 5 * 60; // 5 minutes

                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'reset_pass_time',
                        'value' => $resetPassTime,
                        'expire' => $resetPassTime
                    ]));
                } else {
                    Yii::$app->session->setFlash('danger', THelper::t('the_password_was_not_sent_by_messenger'));
                }
            } else {
                Yii::$app->session->setFlash('warning', THelper::t('the_password_has_been_sent_before'));
            }

            return $status;
        }

        return $this->renderAjax('fin_pass_recovery', [
            'modelMessenger' => $passResetFormMessenger
        ]);
    }
}


function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);

    return $output_file;
}
