<?php

namespace app\modules\business\controllers;

use app\modules\business\models\ShowroomsOpeningConditionsForm;
use yii\helpers\ArrayHelper;
use app\controllers\BaseController;
use Yii;
use app\models\api;

class ShowroomsController extends BaseController
{
    /**
     * Opening conditions
     * @return string
     */
    public function actionOpeningConditions()
    {
        $request = Yii::$app->request;
        $conditionForm = new ShowroomsOpeningConditionsForm();

        if ($request->isPost) {
            $conditionForm->load($request->post());

            if(!empty($conditionForm->id)) {
                $result = api\ShowroomsOpeningCondition::edit([
                    'id'     => $conditionForm->id,
                    'title'  => $conditionForm->title,
                    'body'   => $conditionForm->body,
                    'author' => $conditionForm->author,
                    'lang'   => $conditionForm->lang
                ]);
            } else {
                $result = api\ShowroomsOpeningCondition::add([
                    'title'  => $conditionForm->title,
                    'body'   => $conditionForm->body,
                    'author' => $conditionForm->author,
                    'lang'   => $conditionForm->lang
                ]);
            }

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
                $conditionForm->id      = $condition->id;
                $conditionForm->title   = $condition->title;
                $conditionForm->body    = $condition->body;
                $conditionForm->lang    = $condition->lang;
            } else {
                $conditionForm->lang    = $language;
            }

            return $this->render('opening-conditions', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'conditionForm' => $conditionForm
            ]);
        }
    }

    /**
     * Requests open
     * @return string
     */
    public function actionRequestsOpen()
    {
        $requestsOpen = api\ShowroomsRequestsOpen::getList();

        return $this->render('requests-open', [
            'requestsOpen'  => $requestsOpen
        ]);
    }

    /**
     * @return array|bool|mixed|\yii\web\Response
     */
    public function actionGetRequestsOpen()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $request = Yii::$app->request->post();

            $response = [];
            if(!empty($request['id'])){
                $response = api\ShowroomsRequestsOpen::get($request['id']);
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionUpdateRequestsOpen()
    {
        if (Yii::$app->request->isAjax) {

            $response = false;

            $request = Yii::$app->request->post();

            if(!empty($request)){
                $result = api\ShowroomsRequestsOpen::edit([
                    'id'                => $request['id'],
                    'status'            => $request['status'],
                    'comment'           => $request['comment'],
                    'userHowCheckId'    => $request['userHowCheck']
                ]);
            }

            if($result == 'OK'){
                $response = true;
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionAddFileRequestsOpen()
    {
        if (Yii::$app->request->isAjax) {

            $response = false;

            $request = Yii::$app->request->post();

            if(!empty($request)){
                $result = api\ShowroomsRequestsOpen::edit([
                    'id'                => $request['id'],
                    'status'            => $request['status'],
                    'comment'           => $request['comment'],
                    'userHowCheckId'    => $request['userHowCheck']
                ]);
            }

            if($result == 'OK'){
                $response = true;
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionDeleteFileRequestsOpen()
    {
        if (Yii::$app->request->isAjax) {

            $response = false;

            $request = Yii::$app->request->post();

            if(!empty($request)){
                $result = api\ShowroomsRequestsOpen::edit([
                    'id'                => $request['id'],
                    'status'            => $request['status'],
                    'comment'           => $request['comment'],
                    'userHowCheckId'    => $request['userHowCheck']
                ]);
            }

            if($result == 'OK'){
                $response = true;
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
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