<?php

namespace app\modules\business\controllers;

use app\components\DateTimeHelper;
use app\modules\business\models\MailQueueForUsers;
use MongoDB\BSON\ObjectID;
use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\models\api;
use app\components\THelper;
use app\controllers\BaseController;
use app\modules\business\models\MailPushes;
use app\modules\business\models\MailVariables;
use app\modules\business\models\MailTemplates;
use app\modules\business\models\MailQueue;
use app\modules\business\models\PushVarAddForm;
use app\modules\business\models\PushAddForm;
use app\modules\business\models\PushTemplateAddForm;
use app\modules\business\controllers\traits\NotificationTrait;


class NotificationController extends BaseController
{
    use NotificationTrait;

    const NOTIFICATION_URL = '/business/notification';

    /**
     * Entry point
     */
    public function actionIndex()
    {
        $pushAddForm = new PushAddForm();
        $pushTemplateAddForm = new PushTemplateAddForm();

        $pushes = MailPushes::find()->all();
        $pushTemplates = MailTemplates::find()->all();
        $queue = MailQueue::find()->all();
        $languages = api\dictionary\Lang::all();
        $deliveryConditions = self::getDeliveryConditions();
        $variables = $this->getVariables();

        return $this->render('index', [
            'notificationUrl' => self::NOTIFICATION_URL,
            'pushAddForm' => $pushAddForm,
            'pushTemplateAddForm' => $pushTemplateAddForm,
            'languages' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
            'pushes' => $pushes,
            'pushTemplates' => $pushTemplates,
            'queue' => $queue,
            'deliveryConditions' => $deliveryConditions,
            'variables' => $variables,
        ]);
    }


    /**
     * @return string
     */
    public function actionPushAdd()
    {
        $request = Yii::$app->request->post();

        $pushAddForm = new PushAddForm();

        if ($pushAddForm->load($request)) {
            $model = new MailPushes([
                'language' => $pushAddForm['language'],
                'phrase' => $pushAddForm['phrase'],
                'message' => $pushAddForm['message'],
                'date' => $pushAddForm['date'],
                'isTime' => $pushAddForm['isTime'],
                'time' => $request['time'],
                'action' => $pushAddForm['action'],
                'isSent' => 0,
            ]);

            if ($model->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_has_been_created'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }
        }

        return $this->redirect(self::NOTIFICATION_URL);
    }


    /**
     * @param null $id
     * @return PushAddForm|Response
     */
    public function actionPushEdit($id = null)
    {
        $pushAddForm = new PushAddForm();

        if ($pushAddForm->load($request = Yii::$app->request->post())) {
            $push = MailPushes::find()->where(['_id' => $pushAddForm['id']])->one();

            $push->language = $pushAddForm['language'];
            $push->phrase = $pushAddForm['phrase'];
            $push->message = $pushAddForm['message'];
            $push->date = $pushAddForm['date'];
            $push->isTime = $pushAddForm['isTime'];
            $push->time = $request['time'];
            $push->action = $pushAddForm['action'];

            if ($push->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_has_been_updated'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }

            return $this->redirect(self::NOTIFICATION_URL);
        }

        $push = MailPushes::find()->where(['_id' => $id])->one();

        $pushAddForm->id = (string)$push->_id;
        $pushAddForm->language = $push->language;
        $pushAddForm->phrase = $push->phrase;
        $pushAddForm->message = $push->message;
        $pushAddForm->date = $push->date;
        $pushAddForm->isTime = $push->isTime;
        $pushAddForm->time = $push->time;
        $pushAddForm->action = $push->action;

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
                'action' => self::NOTIFICATION_URL . '/push-delete',
                'title' => THelper::t('push_delete_title'),
            ]);
        }

        $id = $request->post('id');

        $push = MailPushes::find()->where(['_id' => $id])->one();

        if ($push) {
            $push->delete();
            Yii::$app->session->setFlash('success', THelper::t('push_has_been_deleted'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
        }

        return $this->redirect(self::NOTIFICATION_URL);
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function actionPushView($id)
    {
        $request = Yii::$app->request;
        $push = MailPushes::find()->where(['_id' => $id])->one();

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
        $tpl = MailTemplates::find()->where(['_id' => $id])->one();

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
        $queue = MailQueue::find()->where(['_id' => $id])->one();
        $template = MailTemplates::find()->where(['_id' => $queue->template_id])->one();

        if ($request->isAjax) {
            return $this->renderAjax('modals/queue_view', [
                'id' => $id,
                'title' => $template->phrase,
                'text' => $template->message,
            ]);
        }

        return false;
    }

    /**
     * @param null $id
     * @param null $type
     * @return string|Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionQueueDelete($id = null, $type = null)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            return $this->renderAjax('modals/queue_delete', [
                'id' => $id,
                'type' => $type,
                'action' => self::NOTIFICATION_URL . '/queue-delete',
                'title' => THelper::t('push_delete_title'),
            ]);
        }

        $id = $request->post('id');
        $type = $request->post('type');

        $queue = MailQueue::find()->where(['_id' => $id])->one();

        if ($queue) {
            switch ($type){
                case 'current-one':
                    $queueForUser = MailQueueForUsers::find()->where(['queue_id' => $id])->all();

                    foreach ($queueForUser as $item) {
                        $item->delete();
                    }

                    $queue->delete();
                    break;
                case 'current-all':
                    $queueForUser = MailQueueForUsers::find()->where(['queue_id' => new ObjectID($id)])->one();

                    if ($queueForUser) {
                        $queueForUserAll = MailQueueForUsers::find()->where(['user_id' => $queueForUser->user_id])->all();

                        foreach ($queueForUserAll as $item) {
                            $item->delete();
                        }
                    }

                    $queue->delete();
                    break;
                case 'all':
                    $queueForUser = MailQueueForUsers::find()->where(['queue_id' => new ObjectID($id)])->one();

                    if ($queueForUser) {
                        $param = ['template_id' => new ObjectID($queueForUser->template_id)];
                        
                        $queueForUserAll = MailQueueForUsers::find()->where($param)->all();

                        foreach ($queueForUserAll as $item) {
                            $item->delete();
                        }

                        $queueAll = MailQueue::find()->where($param)->one();

                        foreach ($queueAll as $item) {
                            $item->delete();
                        }
                    }

                    break;
            }

            Yii::$app->session->setFlash('success', THelper::t('queue_has_been_deleted'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
        }

        return $this->redirect(self::NOTIFICATION_URL);
    }

    /**
     * @return Response
     */
    public function actionPushTemplateAdd()
    {
        $request = Yii::$app->request->post();

        $pushTplAddForm = new PushTemplateAddForm();

        if ($pushTplAddForm->load($request)) {
            $model = new MailTemplates([
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
                'author' => $this->getAuthor(),
            ]);

            if ($model->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_template_has_been_created'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }
        }

        return $this->redirect(self::NOTIFICATION_URL);
    }

    /**
     * @param null $id
     * @return PushTemplateAddForm|Response
     */
    public function actionPushTemplateEdit($id = null)
    {
        $pushTplAddForm = new PushTemplateAddForm();

        if ($pushTplAddForm->load($request = Yii::$app->request->post())) {
            $push = MailTemplates::find()->where(['_id' => $pushTplAddForm['id']])->one();

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
            $push->author = $this->getAuthor();

            if ($push->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_template_has_been_updated'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }

            return $this->redirect(self::NOTIFICATION_URL);
        }

        $pushTpl = MailTemplates::find()->where(['_id' => $id])->one();

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
                'action' => self::NOTIFICATION_URL . '/push-template-delete',
                'title' => THelper::t('push_template_delete_title'),
            ]);
        }

        $id = $request->post('id');

        $push = MailTemplates::find()->where(['_id' => $id])->one();

        if ($push) {
            $push->delete();
            Yii::$app->session->setFlash('success', THelper::t('push_template_has_been_deleted'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
        }

        return $this->redirect(self::NOTIFICATION_URL);
    }

    /**
     * @return string|Response
     */
    public function actionVariableAdd()
    {
        $variableModel = new PushVarAddForm();

        $request = Yii::$app->request;

        if ($request->isAjax) {
            return $this->renderAjax('modals/variable_add', [
                'action' => self::NOTIFICATION_URL . '/variable-add',
                'title' => THelper::t('variable_add_title'),
                'variableModel' => $variableModel,
                'notificationUrl' => self::NOTIFICATION_URL,
            ]);
        }

        if ($variableModel->load($request = $request->post())) {
            $variable = new MailVariables();

            $variable->name = $variableModel['name'];
            $variable->value = $variableModel['value'];

            if ($variable->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_variable_has_been_added'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }
        }

        return $this->redirect(self::NOTIFICATION_URL);
    }

    /**
     * @param $id
     * @return array
     */
    public function actionPushSend($id)
    {
        $push = MailPushes::find()->where(['_id' => $id])->one();

        $datetime = $push->date;

        if ($push->isTime) {
            $datetime .= ' ' . $push->time;
        }

        $queue = MailQueue::find()->where(['push_id' => new ObjectID($id)])->one();

        if (!$queue) {
            $queue = new MailQueue();
        }

        $queue->title = $push->phrase;
        $queue->language = $push->language;

        $queue->datetime = strtotime($datetime);

        if ($theTimeIsOver = DateTimeHelper::isPast($datetime)) {
            $queue->datetime = time() + 60;
        }

        $queue->event = '';
        $queue->status = 'not_sent';
        $queue->push_id = $push->_id;

        $queue->save();

        MailPushes::markAsInAQueue($push);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($theTimeIsOver) {
            return [
                'date_format' => '%S' . ' ' . THelper::t('sec'),
                'queue_id' => (string)$queue->_id,
            ];
        }

        $format = '%D ' . THelper::t('days') . ' %H:%M:%S';

        return [
            //'time_left' => DateTimeHelper::dateTimeDiff($time, $format),
            'date_left' => date('Y/m/d', strtotime($datetime)),
            'date_format' => $format,
            'queue_id' => (string)$queue->_id,
        ];
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionPushSendStop()
    {
        $request = Yii::$app->request;

        $id = $request->post('id');

        $push = MailPushes::find()->where(['_id' => $id])->one();
        $queue = MailQueue::find()->where(['push_id' => new ObjectID($id)])->one();

        $queue->delete();

        MailPushes::markAsStopped($push);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [];
    }
}

