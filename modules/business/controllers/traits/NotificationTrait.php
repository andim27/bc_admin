<?php
namespace app\modules\business\controllers\traits;

use Yii;
use MongoDB\BSON\ObjectID;
use app\components\DateTimeHelper;
use app\modules\business\models\NotificationMailQueueForUsers;
use app\modules\business\models\NotificationMailTemplates;
use app\modules\business\models\NotificationMailPushes;
use app\modules\business\models\NotificationMailQueue;
use app\models\Users;

/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 01.12.17
 * Time: 12:19
 */
trait NotificationTrait
{
    /**
     * @var array
     */
    protected $messagesToSend = [];

    /**
     * @return string
     */
    public function getAuthor()
    {
        return Yii::$app->view->params['user']->firstName . ' ' . Yii::$app->view->params['user']->secondName;
    }

    /**
     * @param $templates
     * @param $user
     * @param $data
     */
    protected function addTemplatesToAQueue($templates, $user, $data)
    {
        $offset = 0;
        $user = Users::find()->where(['_id' => $user])->one();

        if (isset($user->settings['timeZone'])) {
            $timeZoneData = json_decode($user->settings['timeZone']);

            $offset = $timeZoneData->offset;
        }

        $currentUsersDateTime = DateTimeHelper::modifyTimestampByOffset(time(), $offset);

        foreach ($templates as $template) {
            $sendDatetime = $this->setSendDateTime($template, $currentUsersDateTime);

            if (!$sendDatetime) {
                continue;
            }
        }
    }

    /**
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    protected function addPushesToAQueue()
    {
        $queue = NotificationMailQueue::find()->all();

        foreach ($queue as $item) {
            if (
                isset($item->datetime) &&
                $item->status === 'not_sent'
            ) {
                array_push($this->messagesToSend, $item);
            }
        }

        foreach ($this->messagesToSend as $msg) {
            $push = NotificationMailPushes::find()->where(['_id' => $msg->push_id])->one();
            $queue = NotificationMailQueue::find()->where(['push_id' => new ObjectID($msg->push_id)])->one();

            if ($msg->push_id && $queue) {
                $users = $this->getUsersToBeSent($push);
                $this->addPushQueueToQueueForUsers($push, $users, $queue);

                if (
                    $queue && !NotificationMailQueueForUsers::find()->where([
                        'queue_id' => new ObjectID($queue->_id)
                    ])->one()
                ) {
                    $queue->delete();
                }

                // Сменить статус сообщения на отправлено
                NotificationMailPushes::markAsSent($push);
            }
        }

        $this->messagesToSend = [];
    }

    /**
     * Do operation for every user
     *
     * @param callable $callback
     */
    protected function queueForUsersWalker(callable $callback)
    {
        $queue = NotificationMailQueueForUsers::find()->all();

        foreach ($queue as $item) {
            $push = NotificationMailPushes::find()->where(['_id' => new ObjectID($item->push_id)])->one();
            $template = NotificationMailTemplates::find()->where(['_id' => new ObjectID($item->template_id)])->one();
            $user = Users::find()->where(['_id' => new ObjectID($item->user_id)])->one();

            $pushOrTemplate = $push;

            if ($item->event && $item->template_id && $template) {
                $pushOrTemplate = $template;
            }

            $callback($pushOrTemplate, $user, $item);
        }
    }

    /**
     * @param $push
     * @param $users
     * @param $queue
     * @return bool
     */
    public function addPushQueueToQueueForUsers($push, $users, $queue)
    {
        if (
            NotificationMailQueueForUsers::find()->where([
                'queue_id' => new ObjectID($queue->_id)
            ])->one()
        ) {
            return false;
        }

        foreach ($users as $user) {
            $offset = 0;

            if (isset($user->settings['timeZone'])) {
                $timeZoneData = json_decode($user->settings['timeZone']);

                $offset = $timeZoneData->offset;
            }

            $datetime = DateTimeHelper::modifyTimestampByOffset($queue->datetime, $offset);
            // подставляем переменные
            $message = !empty($push->message) ? $this->substituteVariables($user, $push->message) : 'message is unavailable';

            NotificationMailQueueForUsers::create($user->_id, $queue->_id, $push->_id, null, $datetime, null, $message);
        }

        return true;
    }

    /**
     * @param $template
     * @param $user
     * @param $queue
     * @param $datetime
     * @param $message
     * @return bool
     */
    public function addTemplateQueueToQueueForUser($template, $user, $queue, $datetime, $message)
    {
        $queue = NotificationMailQueue::find()->where(['_id' => $queue])->one();

        if (
            NotificationMailQueueForUsers::find()->where([
                'queue_id' => new ObjectID($queue->_id)
            ])->one()
        ) {
            return false;
        }

        $template = is_numeric($template) ? NotificationMailTemplates::find()->where(['_id' => $template])->one() : $template;

        return NotificationMailQueueForUsers::create($user->_id, $queue->_id, null, $template->_id, $datetime, $template->event ?: null, $message);
    }

}