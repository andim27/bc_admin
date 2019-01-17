<?php

namespace app\modules\business\controllers;

use app\modules\business\models\ShowroomsOpeningConditions;
use yii\helpers\ArrayHelper;
use app\controllers\BaseController;
use Yii;
use app\models\api;

class ShowroomsController extends BaseController
{
    public function actionOpeningConditions()
    {
        $request = Yii::$app->request;
        $conditionForm = new ShowroomsOpeningConditions();

        if ($request->isPost) {
            $conditionForm->load($request->post());

            $result = api\ShowroomsOpeningCondition::add([
                'title'  => $conditionForm->title,
                'body'   => $conditionForm->body,
                'author' => $conditionForm->author,
                'lang'   => $conditionForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'showrooms_opening_conditions_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'showrooms_opening_conditions_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/showrooms/opening-conditions/?l=' . $conditionForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::supported();
            $condition = api\ShowroomsOpeningCondition::get($language);

            $conditionForm->author = $this->user->username;

            if ($condition) {
                $conditionForm->title = $condition->title;
                $conditionForm->body  = $condition->body;
                $conditionForm->lang  = $condition->lang;
            } else {
                $conditionForm->lang  = $language;
            }

            return $this->render('opening-conditions', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'conditionForm' => $conditionForm
            ]);
        }
    }

    public function actionRequestsOpen()
    {
        return $this->render('requests-open', [

        ]);
    }

    public function actionList()
    {
        return $this->render('list', [

        ]);
    }

    public function actionCompensationTable()
    {
        return $this->render('compensation-table', [

        ]);
    }

    public function actionChargeCompensation()
    {
        return $this->render('charge-compensation', [

        ]);
    }

    public function actionReceptionIssueGoods()
    {
        return $this->render('reception-issue-goods', [

        ]);
    }
}