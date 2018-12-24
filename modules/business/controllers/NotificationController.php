<?php

namespace app\modules\business\controllers;

use app\modules\business\models\NotificationMailQueueForUsers;
use MongoDB\BSON\ObjectID;
use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\models\api;
use app\components\THelper;
use app\controllers\BaseController;
use app\modules\business\models\NotificationMailPushes;
use app\modules\business\models\NotificationMailTemplates;
use app\modules\business\models\PushAddForm;
use app\modules\business\models\PushTemplateAddForm;

class NotificationController extends BaseController
{
    const NOTIFICATION_URL = '/business/notification';

    /**
     * @return string
     */
    public function actionIndex()
    {
        $pushAddForm = new PushAddForm();
        $pushTemplateAddForm = new PushTemplateAddForm();

        $pushes = api\Notification::getPushes();
        $queueForUsers = NotificationMailQueueForUsers::find()->all();
        $pushTemplates = NotificationMailTemplates::find()->all();

        $languages = api\dictionary\Lang::supported();

        return $this->render('index', [
            'notificationUrl' => '/' . Yii::$app->language . self::NOTIFICATION_URL,
            'pushAddForm' => $pushAddForm,
            'pushTemplateAddForm' => $pushTemplateAddForm,
            'languages' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
            'pushes' => $pushes,
            'pushTemplates' => $pushTemplates,
            'queueForUsers' => $queueForUsers,
            'deliveryConditions' => NotificationMailTemplates::getDeliveryConditions()
        ]);
    }

    /**
     * @return Response
     */
    public function actionPushAdd()
    {
        $request = Yii::$app->request->post();

        $pushAddForm = new PushAddForm();

        if ($pushAddForm->load($request)) {
            $isTime = (bool)$pushAddForm['isTime'];

            $model = new NotificationMailPushes([
                'language' => $pushAddForm['language'],
                'phrase' => $pushAddForm['phrase'],
                'message' => $pushAddForm['message'],
                'date' => $pushAddForm['date'],
                'isTime' => $isTime,
                'time' => $isTime ? $request['time'] : '',
                'action' => intval($pushAddForm['action']),
                'isSent' => false,
                'isInAQueue' => false,
            ]);

            if ($model->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_has_been_created'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }
        }

        return $this->redirect('/' . Yii::$app->language . self::NOTIFICATION_URL);
    }

    /**
     * @param null $id
     * @return PushAddForm|Response
     */
    public function actionPushEdit($id = null)
    {
        $pushAddForm = new PushAddForm();

        if ($pushAddForm->load($request = Yii::$app->request->post())) {
            $push = NotificationMailPushes::find()->where(['_id' => $pushAddForm['id']])->one();

            $isTime = (bool)$pushAddForm['isTime'];

            $push->language = $pushAddForm['language'];
            $push->phrase = $pushAddForm['phrase'];
            $push->message = $pushAddForm['message'];
            $push->date = $pushAddForm['date'];
            $push->isTime = $isTime;
            $push->time = $isTime ? $request['time'] : '';
            $push->action = intval($pushAddForm['action']);

            if ($push->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_has_been_updated'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }

            return $this->redirect('/' . Yii::$app->language . self::NOTIFICATION_URL);
        }

        $push = NotificationMailPushes::find()->where(['_id' => $id])->one();

        $pushAddForm->id = strval($push->_id);
        $pushAddForm->language = $push->language;
        $pushAddForm->phrase = $push->phrase;
        $pushAddForm->message = $push->message;
        $pushAddForm->date = $push->date;
        $pushAddForm->isTime = (bool)$push->isTime;
        $pushAddForm->time = $push->time;
        $pushAddForm->action = intval($push->action);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $pushAddForm;
    }

    /**
     * @param null $id
     * @return string|Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionPushDelete($id = null)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            return $this->renderAjax('modals/delete', [
                'id' => $id,
                'action' => '/' . Yii::$app->language . self::NOTIFICATION_URL . '/push-delete',
                'title' => THelper::t('push_delete_title'),
            ]);
        }

        $pushId = $request->post('id');

        if (api\Notification::deletePush($pushId)) {
            Yii::$app->session->setFlash('success', THelper::t('push_has_been_deleted'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
        }

        return $this->redirect('/' . Yii::$app->language . self::NOTIFICATION_URL);
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function actionPushView($id)
    {
        $request = Yii::$app->request;
        $push = NotificationMailPushes::find()->where(['_id' => $id])->one();

        if ($request->isAjax) {
            return $this->renderAjax('modals/planing_view', [
                'id' => $id,
                'title' => $push->phrase,
                'text' => $push->message,
            ]);
        }

        return false;
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function actionTemplateView($id)
    {
        $request = Yii::$app->request;
        $tpl = NotificationMailTemplates::find()->where(['_id' => $id])->one();

        if ($request->isAjax) {
            return $this->renderAjax('modals/template_view', [
                'id' => $id,
                'title' => $tpl->phrase,
                'text' => $tpl->message,
            ]);
        }

        return false;
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function actionQueueView($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            if ($queueForUser = NotificationMailQueueForUsers::find()->where(['_id' => $id])->one()) {
                $title = $queueForUser->title;
                $body = $queueForUser->body;
            } else {
                $title = '';
                $body = '';
            }
            return $this->renderAjax('modals/queue_view', [
                'title' => $title,
                'body' => $body,
            ]);
        }

        return false;
    }

    /**
     * @param null $id
     * @param null $type
     * @return string|Response
     */
    public function actionQueueDelete($id = null, $type = null)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            return $this->renderAjax('modals/queue_delete', [
                'id' => $id,
                'type' => $type,
                'action' => '/' . Yii::$app->language . self::NOTIFICATION_URL . '/queue-delete',
                'title' => THelper::t('push_delete_title'),
            ]);
        }

        $id = $request->post('id');
        $type = $request->post('type');

        switch ($type){
            case 'current-one':
                $result = NotificationMailQueueForUsers::deleteAll(['_id' => new ObjectID($id)]);
                break;
            case 'current-all':
                if ($queueForUser = NotificationMailQueueForUsers::find()->where(['_id' => new ObjectID($id)])->one()) {
                    $result = NotificationMailQueueForUsers::deleteAll(['userId' => new ObjectID($queueForUser->userId), 'status' => 0]);
                } else {
                    $result = false;
                }
                break;
            case 'all':
                if ($queueForUser = NotificationMailQueueForUsers::find()->where(['_id' => new ObjectID($id)])->one()) {
                    if (isset($queueForUser->templateId) && $queueForUser->templateId) {
                        $result = NotificationMailQueueForUsers::deleteAll(['templateId' => new ObjectID($queueForUser->templateId)]);
                    } else if (isset($queueForUser->pushId) && $queueForUser->pushId) {
                        $result = NotificationMailQueueForUsers::deleteAll(['pushId' => new ObjectID($queueForUser->pushId)]);
                    }
                } else {
                    $result = false;
                }
                break;
            default:
                $result = false;
                break;
        }

        if ($result) {
            Yii::$app->session->setFlash('success', THelper::t('queue_has_been_deleted'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
        }

        return $this->redirect('/' . Yii::$app->language . self::NOTIFICATION_URL);
    }

    /**
     * @return Response
     */
    public function actionPushTemplateAdd()
    {
        $request = Yii::$app->request->post();

        $pushTplAddForm = new PushTemplateAddForm();

        if ($pushTplAddForm->load($request)) {
            $model = new NotificationMailTemplates([
                'language' => $pushTplAddForm['language'],
                'phrase' => $pushTplAddForm['phrase'],
                'message' => $pushTplAddForm['message'],
                'event' => $pushTplAddForm['event'],
                'next_day_transfer' => $pushTplAddForm['next_day_transfer'],
                'interval_hour' => $pushTplAddForm['interval_hour'],
                'interval_day' => $pushTplAddForm['interval_day'],
                'group' => $pushTplAddForm['group'],
                'is_delivery' => $pushTplAddForm['is_delivery'],
                'delivery_from' => $request['delivery_from'],
                'delivery_to' => $request['delivery_to'],
                'created_at' => date("d.m.y"),
                'updated_at' => date("d.m.y"),
                'author' => $this->user->username,
            ]);

            if ($model->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_template_has_been_created'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }
        }

        return $this->redirect('/' . Yii::$app->language . self::NOTIFICATION_URL);
    }

    /**
     * @param null $id
     * @return PushTemplateAddForm|Response
     */
    public function actionPushTemplateEdit($id = null)
    {
        $pushTplAddForm = new PushTemplateAddForm();

        if ($pushTplAddForm->load($request = Yii::$app->request->post())) {
            $push = NotificationMailTemplates::find()->where(['_id' => $pushTplAddForm['id']])->one();

            $push->language = $pushTplAddForm['language'];
            $push->phrase = $pushTplAddForm['phrase'];
            $push->message = $pushTplAddForm['message'];
            $push->event = $pushTplAddForm['event'];
            $push->next_day_transfer = $pushTplAddForm['next_day_transfer'];
            $push->interval_hour = $pushTplAddForm['interval_hour'];
            $push->interval_day = $pushTplAddForm['interval_day'];
            $push->is_delivery = $pushTplAddForm['is_delivery'];
            $push->delivery_from = $request['delivery_from'];
            $push->delivery_to = $request['delivery_to'];
            $push->group = $pushTplAddForm['group'];
            $push->updated_at = date("d.m.y");
            $push->author = $this->user->username;

            if ($push->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_template_has_been_updated'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }

            return $this->redirect('/' . Yii::$app->language . self::NOTIFICATION_URL);
        }

        $pushTpl = NotificationMailTemplates::find()->where(['_id' => $id])->one();

        $pushTplAddForm->id = (string)$pushTpl->_id;
        $pushTplAddForm->language = $pushTpl->language;
        $pushTplAddForm->phrase = $pushTpl->phrase;
        $pushTplAddForm->message = $pushTpl->message;
        $pushTplAddForm->event = $pushTpl->event;
        $pushTplAddForm->is_delivery = $pushTpl->is_delivery;
        $pushTplAddForm->delivery_from = $pushTpl->delivery_from;
        $pushTplAddForm->delivery_to = $pushTpl->delivery_to;
        $pushTplAddForm->interval_day = $pushTpl->interval_day;
        $pushTplAddForm->interval_hour = $pushTpl->interval_hour;
        $pushTplAddForm->group = $pushTpl->group;
        $pushTplAddForm->next_day_transfer = $pushTpl->next_day_transfer;

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $pushTplAddForm;
    }

    /**
     * @param null $id
     * @return string|Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionPushTemplateDelete($id = null)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            return $this->renderAjax('modals/delete', [
                'id' => $id,
                'action' => '/' . Yii::$app->language . '/push-template-delete',
                'title' => THelper::t('push_template_delete_title'),
            ]);
        }

        $id = $request->post('id');

        $push = NotificationMailTemplates::find()->where(['_id' => $id])->one();

        if ($push) {
            $push->delete();
            Yii::$app->session->setFlash('success', THelper::t('push_template_has_been_deleted'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
        }

        return $this->redirect('/' . Yii::$app->language . self::NOTIFICATION_URL);
    }

    /**
     * @param $id
     * @return array
     */
    public function actionPushSend($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return api\Notification::addPushToQueue($id);
    }

    /**
     * @return bool
     */
    public function actionPushSendStop()
    {
        $request = Yii::$app->request;

        $id = $request->post('id');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return api\Notification::deletePushFromQueue($id);
    }

}