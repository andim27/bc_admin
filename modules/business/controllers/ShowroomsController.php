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
     */
    public function actionRequestsOpen()
    {
        $requestsOpen = api\ShowroomsRequestsOpen::getList();

        return $this->render('requests-open', [
            'requestsOpen'  => $requestsOpen
        ]);
    }

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

    public function actionUpdateRequestOpen()
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

    public function actionAddFileRequestOpen()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response['error'] = 'error';

            $request = Yii::$app->request->post();

            if(!empty($request['fileName']) && !empty($_FILES['fileData'])){
                $data = file_get_contents($_FILES['fileData']['tmp_name']);
                $base64 = 'data:' . $_FILES['fileData']['type'] . ';base64,' . base64_encode($data);
                $key = time();

                $result = api\ShowroomsRequestsOpen::addFile([
                    'key'   => $key,
                    'id'    => $request['id'],
                    'title' => $request['fileName'],
                    'data'  => $base64,
                ]);

                if(!empty($result)){
                    $response = [
                        'key'   => $key,
                        'id'    => $request['id'],
                        'title' => $request['fileName']
                    ];
                }
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionDeleteFileRequestOpen($id,$key)
    {
        if (Yii::$app->request->isAjax) {

            $response = false;

            $result = api\ShowroomsRequestsOpen::deleteFile([
                'id'                => $id,
                'key'               => $key
            ]);

            if($result == 'OK'){
                $response = true;
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    public function actionGetFileRequestOpen($id,$key)
    {
        $result = api\ShowroomsRequestsOpen::getFile([
            'id'                => $id,
            'key'               => $key
        ]);

        if(!empty($result->file)){
            $data = explode(',', $result->file);
            $data = base64_decode($data[1]);
            header('Content-Type: application/pdf');
            header("Content-Disposition: attachment; filename=".$result->title.".pdf");
            echo $data;
            die();
        } else {
            return $this->redirect('/',301);
        }


    }

    public function actionGetSuccessRequestOpen()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $response = [];

            $listRequest = api\ShowroomsRequestsOpen::getSuccessRequests();

            if(!empty($listRequest)){
                foreach ($listRequest as $item) {
                    $response[$item->userId] = [
                        'userLogin'         => $item->userLogin,
                        'userFirstName'     => $item->userFirstName,
                        'userSecondName'    => $item->userSecondName,
                        'countryId'         => $item->countryId,
                        'countryTitle'      => $item->countryName->ru,
                        'cityId'            => $item->cityId,
                        'cityTitle'         => $item->cityName->ru,
                    ];
                }
            }

            return $response;
        } else {
            return $this->redirect('/','301');
        }
    }

    /**
     * Showrooms
     */
    public function actionList()
    {
        $showrooms = api\Showrooms::getList();

        return $this->render('list', [
            'showrooms'  => $showrooms
        ]);
    }

    public function actionAddEditShowroom()
    {
        $request = Yii::$app->request->post();

        if(!empty($request)){

            if(!empty($request['Showroom']['id'])){
                $result = api\Showrooms::edit($request['Showroom']);

                if($result == 'OK'){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'success',
                            'message' => 'Шоурум обновлен'
                        ]
                    );
                } else {
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'danger',
                            'message' => 'Шоурум не обновлен'
                        ]
                    );
                }
            } else {
                $result = api\Showrooms::add($request['Showroom']);

                if($result == 'OK'){
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'success',
                            'message' => 'Шоурум создан'
                        ]
                    );
                } else {
                    Yii::$app->session->setFlash('alert' ,[
                            'typeAlert' => 'danger',
                            'message' => 'Шоурум не создан'
                        ]
                    );
                }

            }

            return $this->redirect(['list'],301);
        } else {
            return $this->redirect('/',301);
        }
    }

    public function actionGetShowroom()
    {
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $request = Yii::$app->request->post();

            $response = api\Showrooms::get($request['id']);

            return $response;
        } else {
            return $this->redirect('/','301');
        }
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