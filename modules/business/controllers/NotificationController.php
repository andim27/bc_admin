<?php

namespace app\modules\business\controllers;

use App\Models\NotificationMailPush;
use app\modules\business\models\NotificationMailQueueForUsers;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\models\api;
use app\components\THelper;
use app\controllers\BaseController;
use app\modules\business\models\NotificationMailPushes;
use app\modules\business\models\NotificationMailVariables;
use app\modules\business\models\NotificationMailTemplates;
use app\modules\business\models\NotificationMailQueue;
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

        $pushes = NotificationMailPushes::find()->all();
        $pushTemplates = NotificationMailTemplates::find()->all();
        $queue = NotificationMailQueue::find()->all();
        $languages = api\dictionary\Lang::supported();
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

        $id = $request->post('id');

        $push = NotificationMailPushes::find()->where(['_id' => $id])->one();

        if ($push) {
            $push->delete();
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
            if ($queue = NotificationMailQueue::find()->where(['_id' => $id])->one()) {
                if ($queue->template_id) {
                    $template = NotificationMailTemplates::find()->where(['_id' => strval($queue->template_id)])->one();
                } else if ($queue->push_id) {
                    $push = NotificationMailPushes::find()->where(['_id' => strval($queue->push_id)])->one();
                }
            }
            return $this->renderAjax('modals/queue_view', [
                'id' => $id,
                'title' => isset($template) ? $template->phrase : isset($push) ? $push->phrase : '',
                'text' => isset($template) ? $template->message : isset($push) ? $push->message : '',
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

        $queue = NotificationMailQueue::find()->where(['_id' => $id])->one();

        if ($queue) {
            switch ($type){
                case 'current-one':
                    $queueForUser = NotificationMailQueueForUsers::find()->where(['queue_id' => $id])->all();

                    foreach ($queueForUser as $item) {
                        $item->delete();
                    }

                    $queue->delete();
                    break;
                case 'current-all':
                    $queueForUser = NotificationMailQueueForUsers::find()->where(['queue_id' => new ObjectID($id)])->one();

                    if ($queueForUser) {
                        $queueForUserAll = NotificationMailQueueForUsers::find()->where(['user_id' => $queueForUser->user_id])->all();

                        foreach ($queueForUserAll as $item) {
                            $item->delete();
                        }
                    }

                    $queue->delete();
                    break;
                case 'all':
                    $queueForUser = NotificationMailQueueForUsers::find()->where(['queue_id' => new ObjectID($id)])->one();

                    if ($queueForUser) {
                        $param = ['template_id' => new ObjectID($queueForUser->template_id)];
                        
                        $queueForUserAll = NotificationMailQueueForUsers::find()->where($param)->all();

                        foreach ($queueForUserAll as $item) {
                            $item->delete();
                        }

                        $queueAll = NotificationMailQueue::find()->where($param)->one();

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
            $push->author = $this->getAuthor();

            if ($push->save()) {
                Yii::$app->session->setFlash('success', THelper::t('push_template_has_been_updated'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('something_is_wrong'));
            }

            return $this->redirect(self::NOTIFICATION_URL);
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
                'action' => self::NOTIFICATION_URL . '/push-template-delete',
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
            $variable = new NotificationMailVariables();

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
        $push = NotificationMailPushes::find()->where(['_id' => $id])->one();

        if ($push->date) {
            $dateArray = explode('.', $push->date);
        }
        if ($push->isTime) {
            $timeArray = explode(':', $push->time);
        }

        $date = gmmktime(isset($timeArray[0]) ? $timeArray[0] : 0, isset($timeArray[1]) ? $timeArray[1] : 0, 0, isset($dateArray[1]) ? $dateArray[1] : null, isset($dateArray[0]) ? $dateArray[0] : null, isset($dateArray[2]) ? $dateArray[2] : null);
        $nowDate = time();

        if ($date <= $nowDate) {
            $theTimeIsOver = true;
            $date = $nowDate + 60;
        } else {
            $theTimeIsOver = false;
        }

        $queue = NotificationMailQueue::find()->where(['push_id' => new ObjectID($id)])->one();

        if (!$queue) {
            $queue = new NotificationMailQueue();
        }

        $queue->title = $push->phrase;
        $queue->language = $push->language;
        $queue->date = new UTCDateTime($date * 1000);
        $queue->event = '';
        $queue->status = 0;
        $queue->push_id = $push->_id;

        $queue->save();

        NotificationMailPushes::markAsInAQueue($push);

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
            'date_left' => gmdate('Y/m/d', $date),
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

        $push = NotificationMailPushes::find()->where(['_id' => $id])->one();
        $queue = NotificationMailQueue::find()->where(['push_id' => new ObjectID($id)])->one();

        $queue->delete();

        NotificationMailPushes::markAsStopped($push);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [];
    }

}