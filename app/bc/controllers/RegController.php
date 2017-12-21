<?php

namespace app\controllers;
use app\components\THelper;
use Yii;
use app\models\RegistrationForm;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\models\api\dictionary;
use app\models\api;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\Response;

class RegController extends \yii\web\Controller
{
    public function actionRegistration()
    {
        $registrationForm = new RegistrationForm();
        $this->layout = 'start';

        $registrationForm->load(Yii::$app->request->post());

        if (Yii::$app->request->isAjax && $registrationForm->step == 2) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($registrationForm);
        } else {
            if (Yii::$app->request->isPost) {
                if ($registrationForm->step == 1) {
                    $validator = ActiveForm::validate($registrationForm);
                    if (! isset($validator['registrationform-ref'])) {
                        $registrationForm->referrer = $registrationForm->ref;
                        $registrationForm->clearErrors();
                        $registrationForm->step += 1;
                    }
                } else if ($registrationForm->step == 2) {
                    $sponsor = api\User::get($registrationForm->referrer);

                    $data = [
                        'sponsor'     => $sponsor->username,
                        'username'    => strtolower($registrationForm->login),
                        'email'       => $registrationForm->email,
                        'fname'       => $registrationForm->name,
                        'sname'       => $registrationForm->second_name,
                        'phone'       => $registrationForm->mobile,
                        'password'    => $registrationForm->pass,
                        'finPassword' => $registrationForm->finance_pass,
                        'skype'       => $registrationForm->skype,
                        'country'     => $registrationForm->country_id,
                        'ip'          => Yii::$app->request->getUserIP()
                    ];

                    if ($registrationForm->messenger && $registrationForm->messengerNumber) {
                        switch ($registrationForm->messenger) {
                            case 'telegram':
                                $data['phoneTelegram'] = $registrationForm->messengerNumber;
                            break;
                            case 'viber':
                                $data['phoneViber'] = $registrationForm->messengerNumber;
                            break;
                            case 'whatsapp':
                                $data['phoneWhatsApp'] = $registrationForm->messengerNumber;
                            break;
                            case 'facebook':
                                $data['phoneFB'] = $registrationForm->messengerNumber;
                            break;
                        }
                    }

                    $result = api\User::create($data);

                    if ($result && $registrationForm->mobile) {
                        $result = api\User::update($result->accountId, [
                            'phone2' => $registrationForm->mobile,
                            'phoneWellness' => $registrationForm->mobile,
                        ]);
                    }

                    if ($result) {
                        $user = api\User::auth($registrationForm->email, $registrationForm->pass);

                        if ($user) {
                            Yii::$app->getSession()->set('user', $user);
                        }
                        $registrationForm->step += 1;
                    }
                }
            } else {
                $ref = Yii::$app->request->get('ref');

                if ($ref) {
                    $sponsor = api\User::get($ref);
                    if ($sponsor) {
                        $registrationForm->referrer = $ref;
                        $registrationForm->step = 2;
                    } else {
                        $registrationForm->step = 1;
                    }
                } else {
                    $registrationForm->step = 1;
                }
            }

            $data = [
                'model' => $registrationForm,
            ];

            if ($registrationForm->step == 2) {
                $data['countries'] = ArrayHelper::map(api\dictionary\Country::all(), 'alpha2', 'name');
            } else if ($registrationForm->step == 3) {
                $data['url'] = Url::toRoute('/', true);
                $data['links'] = api\settings\Link::get();
            }

            $data['recommenderSearchUrl'] = Yii::$app->params['recommenderSearchUrl'];

            return $this->render('registration', $data);
        }

    }

    public function actionMore()
    {
        return $this->renderPartial('more', ['text' => '']);
    }

    public function actionParticipant()
    {
        $text = api\Agreement::get(Yii::$app->language);

        return $this->renderPartial('participant', ['text' => $text ? $text->body : '']);
    }

    public function actionSearch()
    {
        return $this->_checkInfo($_GET['login']);
    }

    public function actionSearchlogin()
    {
        return $this->_checkInfo($_GET['login']);
    }

    public function actionSearchemail()
    {
        return $this->_checkInfo($_GET['email']);
    }

    private function _checkInfo($data)
    {
        return User::getInfoApi($data) ? true : false;
    }

    /**
     * @return bool
     */
    public function actionSuccess()
    {
        $registrationForm = new RegistrationForm();

        if ($registrationForm->load(Yii::$app->request->get()) && $registrationForm->validate()) {

            $sponsor = api\User::get($registrationForm->ref);

            $result = api\User::create([
                'sponsor'     => $sponsor->username,
                'username'    => $registrationForm->login,
                'email'       => $registrationForm->email,
                'fname'       => $registrationForm->name,
                'sname'       => $registrationForm->second_name,
                'phone'       => $registrationForm->mobile,
                'password'    => $registrationForm->pass,
                'finPassword' => $registrationForm->finance_pass,
                'skype'       => $registrationForm->skype,
                'country'     => $registrationForm->country_id
            ]);

            if ($result) {
                $user = api\User::auth($registrationForm->email, $registrationForm->pass);

                if ($user) {
                    Yii::$app->getSession()->set('user', $user);
                }

                return json_encode($result);
            } else {
                return false;
            }
        }

        return false;
    }

    public function actionValidate()
    {
        $registrationForm = new RegistrationForm();

        if (Yii::$app->request->isAjax && $registrationForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($registrationForm);
        }
    }

}