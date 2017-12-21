<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\components\UserHelper;
use app\controllers\BaseController;
use app\models\RegistrationForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\models\api;
use yii\web\Response;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        if (isset($this->user->sponsor)) {
            $parent = api\User::get($this->user->sponsor->accountId);
        } else {
            $parent = $this->user;
        }

        $promoShow = false;

        if (Yii::$app->language === 'en'){
            $linkList = [
                'linkToRegister' => Url::to(['/reg/registration', 'ref' => $this->user->username], Yii::$app->params['scheme']),
                'linkBusinessLanding' => [
                    "http://{$this->user->username}.vipbpt.com/" . Yii::$app->language, "http://{$this->user->username}.wawwe.net/" . Yii::$app->language
                ],
                'linkVipVipLanding' => "http://{$this->user->username}.shans.biz/" . Yii::$app->language,
                'linkSiteAppVipVip' => $this->user->phoneNumber2 && $this->user->phoneNumber2 != '+' ? "http://vipvip.com/" . Yii::$app->language . "/?ref={$this->user->phoneNumber2}" : THelper::t('fill_the_phone'),
                'linkWebWellnessLanding' => "http://{$this->user->username}.webwellness.ru/" . Yii::$app->language,
                'linkSiteAppWebWellness' => $this->user->phoneWellness && $this->user->phoneWellness != '+' ? "http://webwellness.net/" . Yii::$app->language . "/?ref={$this->user->phoneWellness}" : THelper::t('fill_the_phone'),
            ];
        } else {
            $linkList = [
                'linkToRegister' => Url::to(['/reg/registration', 'ref' => $this->user->username], Yii::$app->params['scheme']),
                'linkBusinessLanding' => [
                    "http://{$this->user->username}.vipbpt.com", "http://{$this->user->username}.wawwe.net"
                ],
                'linkVipVipLanding' => "http://{$this->user->username}.shans.biz",
                'linkSiteAppVipVip' => $this->user->phoneNumber2 && $this->user->phoneNumber2 != '+' ? "http://vipvip.com/?ref={$this->user->phoneNumber2}" : THelper::t('fill_the_phone'),
                'linkWebWellnessLanding' => "http://{$this->user->username}.webwellness.ru",
                'linkSiteAppWebWellness' => $this->user->phoneWellness && $this->user->phoneWellness != '+' ? "http://webwellness.net/?ref={$this->user->phoneWellness}" : THelper::t('fill_the_phone'),
            ];
        }

        $data = [
            'user' => $this->user,
            'parent' => $parent,
            'i' => $this->user->statistics->personalPartners,
            'links' => api\settings\Link::get(),
            'promoShow' => $promoShow
        ] + $linkList;

        if ($promoShow) {
            $promoInfo = api\Promo::get($this->user->id);

            if ($promoInfo) {
                foreach ($promoInfo as $pi) {
                    if (isset($pi->type) && $pi->type == 'TYPE_SHL_200917') {
                        $currentPromoInfo = $pi;
                    }
                }
            }

            $promoInfoAll = api\Promo::all($this->user->id);

            $completed = [];

            foreach($promoInfoAll as $pi) {
                if ($pi->completed && (!isset($pi->type) || $pi->type != 'TYPE_SHL_200917')) {
                    $completed[] = strval($pi->userId);
                }
            }

            $qtyCompleteProm = 0;
            if ($promoInfoAll) {
                foreach ($promoInfoAll as $pia) {
                    if ($pia->completed && isset($pia->type) && $pia->type == 'TYPE_SHL_200917' && !in_array(strval($pia->userId), $completed)) {
                        $qtyCompleteProm++;
                    }
                }
            }

            if (isset($currentPromoInfo)) {
                $promoInfo = $currentPromoInfo;
                $promoPriceOne = $promoInfo->needSteps;
                $promoYourPriceOne = $promoInfo->needSteps - $promoInfo->steps;
                if ($promoYourPriceOne < 0) {
                    $promoYourPriceOne = 0;
                }
                if ($promoInfo->salesSum) {
                    $promoSalesSum = $promoInfo->salesSum;
                } else {
                    $promoSalesSum = 0;
                }


                if (isset($promoInfo->steps) && $promoInfo->steps <= $promoInfo->needSteps) {
                    $promoInfoSteps = 2 * $promoInfo->steps;
                } else {
                    $promoInfoSteps = 2 * $promoInfo->needSteps;
                }

                $promoProgressOne = $promoInfoSteps;
            } else {
                $qtyCompleteProm = 0;
                $promoYourPriceOne = 25;
                $promoProgressOne = 0;
                $promoPriceOne = 25;
                $promoSalesSum = 0;
            }

            $promoPriceTwo = '';
            $promoYourPriceTwo = '';
            $promoProgressTwo = '';

            $promoNeedSalesSum = 2500 - $promoSalesSum;

            if (isset($promoInfo->salesSum) && $promoInfo->salesSum <= 2500) {
                $promoProgressOne += 0.02 * $promoInfo->salesSum;
            } else {
                $promoProgressOne += 0.02 * 2500;
            }

            $data = array_merge($data, [
                'promoPriceOne' => $promoPriceOne,
                'promoPriceTwo' => $promoPriceTwo,
                'promoYourPriceOne' => $promoYourPriceOne,
                'promoYourPriceTwo' => $promoYourPriceTwo,
                'promoProgressOne' => $promoProgressOne,
                'promoProgressTwo' => $promoProgressTwo,
                'qtyCompleteProm' => $qtyCompleteProm,
                'promoNeedSalesSum' => $promoNeedSalesSum > 0 ? $promoNeedSalesSum : 0
            ]);
        }

        return $this->render('index', $data);
    }

    public function actionRelogin()
    {
        $email = Yii::$app->getRequest()->get('email');

        $user = api\User::get($email);

        if ($user) {
            $cookies = Yii::$app->response->cookies;
            $cookies->remove('user');
            Yii::$app->getSession()->set('user', $user);
        }

        return $this->goHome();
    }

    public function actionInstruction()
    {
        $controller = Yii::$app->getRequest()->get('c');
        $action = Yii::$app->getRequest()->get('a');
        $module = Yii::$app->getRequest()->get('m');

        if ($controller == 'default' && $action == 'index') {
            $image = api\Image::get('pageMainIntro', Yii::$app->language, true);
        } else if ($controller == 'news' && $action == 'index') {
            $image = api\Image::get('pageNewsIntro', Yii::$app->language, true);
        } else if ($controller == 'information' && $action == 'promotions') {
            $image = api\Image::get('pagePromotionsIntro', Yii::$app->language, true);
        } else if ($controller == 'information' && $action == 'timesheet') {
            $image = api\Image::get('pageConferenceIntro', Yii::$app->language, true);
        } else if ($controller == 'information' && $action == 'marketing') {
            $image = api\Image::get('pageMarketingPlanIntro', Yii::$app->language, true);
        } else if ($controller == 'information' && $action == 'carrier') {
            $image = api\Image::get('pageCareerPlanIntro', Yii::$app->language, true);
        } else if ($controller == 'information' && $action == 'price') {
            $image = api\Image::get('pagePriceListIntro', Yii::$app->language, true);
        } else if ($controller == 'team' && $action == 'genealogy') {
            $image = api\Image::get('pageGeneologyIntro', Yii::$app->language, true);
        } else if ($controller == 'team' && $action == 'geography') {
            $image = api\Image::get('pageGeographyIntro', Yii::$app->language, true);
        } else if ($controller == 'team' && $action == 'self') {
            $image = api\Image::get('pagePersonalPartnersIntro', Yii::$app->language, true);
        } else if ($controller == 'carrier' && $action == 'status') {
            $image = api\Image::get('pageCareerStatusIntro', Yii::$app->language, true);
        } else if ($controller == 'carrier' && $action == 'certificate') {
            $image = api\Image::get('pageCertificateIntro', Yii::$app->language, true);
        } else if ($controller == 'statistic' && $action == 'index') {
            $image = api\Image::get('pageStatisticIntro', Yii::$app->language, true);
        } else if ($controller == 'sale' && $action == 'index') {
            $image = api\Image::get('pageSaleIntro', Yii::$app->language, true);
        } else if ($controller == 'finance' && $action == 'index') {
            $image = api\Image::get('pageFinanceIntro', Yii::$app->language, true);
        } else if ($controller == 'charity' && $action == 'index') {
            $image = api\Image::get('pageCharityIntro', Yii::$app->language, true);
        } else if ($controller == 'resource' && $action == 'index') {
            $image = api\Image::get('pageResourceIntro', Yii::$app->language, true);
        } else if ($controller == 'uploaded' && $action == 'index') {
            $image = api\Image::get('pageUploadsIntro', Yii::$app->language, true);
        } else if ($controller == 'setting' && $action == 'profile') {
            $image = api\Image::get('pageProfileIntro', Yii::$app->language, true);
        } else if ($controller == 'setting' && $action == 'unioncell') {
            $image = api\Image::get('pageUnioncellIntro', Yii::$app->language, true);
        } else if ($controller == 'setting' && $action == 'passwords') {
            $image = api\Image::get('pagePasswordsIntro', Yii::$app->language, true);
        } else if ($controller == 'setting' && $action == 'alert') {
            $image = api\Image::get('pageAlertsIntro', Yii::$app->language, true);
        } else if ($controller == 'notes' && $action == 'index') {
            $image = api\Image::get('pageNotesIntro', Yii::$app->language, true);
        }

        return $this->renderAjax('instruction', [
            'image' => isset($image) ? $image : false
        ]);
    }

    public function actionGetRegistrationsStatisticsPerMoths()
    {
        return api\graph\RegistrationsStatistics::get($this->user->accountId);
    }

    public function actionFillMissingData()
    {
        $registrationForm = new RegistrationForm();

       // hh($this->user);

        if (Yii::$app->request->isAjax && $registrationForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($registrationForm);
        } else {
            if (Yii::$app->request->isPost) {
                if ($registrationForm->load(Yii::$app->request->post())) {
                    $data = [
                        'username'    => strtolower($registrationForm->login) ?: $this->user->username,
                        'fname'       => $registrationForm->name ?: $this->user->firstName,
                        'sname'       => $registrationForm->second_name ?: $this->user->secondName,
                        'skype'       => $registrationForm->skype ?: $this->user->skype
                    ] + ($registrationForm->finance_pass ? ['finPassword' => $registrationForm->finance_pass] : []);

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

                    $user = api\User::update($this->user->accountId, $data);
                    Yii::$app->getSession()->set('user', $user);
                }

                $this->redirect('/' . Yii::$app->language . '/business/team/genealogy');
            } else {
                $data = [
                    'model' => $registrationForm,
                    'user' => $this->user,
                    'empty_fields' => UserHelper::getEmptyFields($this->user)
                ];

//              return $this->renderAjax('registration', $data);
                return $this->render('fill_missing_data', $data);
            }
        }
    }
}
