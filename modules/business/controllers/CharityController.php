<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use app\modules\business\models\CharityForm;
use app\models\api;
use app\models\api\transactions;
use app\components\THelper;

class CharityController extends BaseController
{
    public function actionIndex()
    {
        $charities = api\transactions\Charity::all($this->user->id);

        $charityForm = new CharityForm();
        $charityForm->balance = $this->user->moneys;

        $postData = Yii::$app->request->post('CharityForm');

        if ($postData) {
            $charityForm->attributes = $postData;

            if ($charityForm->validate()) {
                api\User::setCharityPercent($this->user->id, $charityForm->percent);
                api\transactions\Charity::transferMoney($this->user->id, '000000000000000000000000', $charityForm->amount, 'Charity');
                Yii::$app->getSession()->setFlash('success', THelper::t('charity_was_sended'));
                return $this->redirect('/' . Yii::$app->language . '/business/charity');
            } else {
                Yii::$app->getSession()->setFlash('errors', $charityForm->getErrors());
                return $this->redirect('/' . Yii::$app->language . '/business/charity');
            }
        }

        return $this->render('index', [
            'user' => $this->user,
            'charities' => $charities,
            'charityForm' => $charityForm,
            'successText' => Yii::$app->getSession()->getFlash('success', '', true),
            'errorsText' => Yii::$app->getSession()->getFlash('errors', '', true)
        ]);
    }

    public function actionSavePercent()
    {
        if (Yii::$app->request->isAjax) {
            $charityForm = new CharityForm();

            $percent = Yii::$app->request->get('percent');

            $charityForm->setAttributes(['percent' => $percent]);

            if ($charityForm->validate(['percent'])) {
                if (api\User::setCharityPercent($this->user->id, $percent)) {
                    $this->user->charityPercent = $percent;
                }
            }
        }
    }

    public function actionReports()
    {
        $charityReports = api\CharityReport::get(Yii::$app->language);

        return $this->render('reports',[
            'reports' => $charityReports,
        ]);
    }
}