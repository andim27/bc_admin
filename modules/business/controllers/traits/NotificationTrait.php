<?php
namespace app\modules\business\controllers\traits;

use Yii;
use yii\helpers\ArrayHelper;
use MongoDB\BSON\ObjectID;
use app\components\THelper;
use app\components\DateTimeHelper;
use app\components\PushNotification\PushNotification;
use app\modules\business\models\NotificationMailUserTemplateHistory;
use app\modules\business\models\NotificationMailQueueForUsers;
use app\modules\business\models\NotificationMailTemplateGroup;
use app\modules\business\models\NotificationMailTemplates;
use app\modules\business\models\NotificationMailVariables;
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
     * @return array
     */
    public static function getDeliveryConditions()
    {
        $activityFinishesInX = [
            'activity_finishes_in_7_days' => THelper::t('activity_finishes_in_7_days'),
            'activity_finishes_in_3_days' => THelper::t('activity_finishes_in_3_days'),
            'activity_finishes_in_1_days' => THelper::t('activity_finishes_in_1_days'),
        ];

        $selfBirthdayX = [];

        foreach (range(1, 10) as $number) {
            $selfBirthdayX['self_birthday_' . $number] = THelper::t('self_birthday') . ' ' . $number;
        }

        return [
            'thanks_for_charity' => THelper::t('thanks_for_charity'),
            'withdrawal' => THelper::t('withdrawal'),
            'network_partner_registration' => THelper::t('network_partner_registration'),
            'self_invited_partner_registration' => THelper::t('self_invited_partner_registration'),
            'self_invited_partner_payment' => THelper::t('self_invited_partner_payment'),
            'points_notification' => THelper::t('points_notification'),
            'money_notification' => THelper::t('money_notification'),
            'missed_points' => THelper::t('missed_points'),
            'end_of_activity' => THelper::t('end_of_activity'),
            'birthday_of_sponsor' => THelper::t('birthday_of_sponsor'),
            'first_notification' => THelper::t('first_notification'),
            'career_updated_self' => THelper::t('career_updated_self'),
            'career_updated_personal_partner' => THelper::t('career_updated_personal_partner'),
            'career_updated_sponsor' => THelper::t('career_updated_sponsor'),
            ] + $activityFinishesInX + $selfBirthdayX;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        $defaultVariables = [
            '[MYLOGIN]' => 'username',
            '[MYNAME]' => 'firstName',
            '[NEWUSERLOGIN]' => 'test var',
            '[NEWUSERNAME]' => 'test var',
            '[NEWUSERMAIL]' => 'test var',
            '[NEWUSERSCYPE]' => 'test var',
            '[NEWUSERMESSENGER]' => 'test var',
            '[NEWUSERСCOUNTRY]' => 'test var',
            '[birthdaypartner]' => 'test var',
            '[STATUS]' => 'test var',
            '[missedpoints]' => 'test var',
            '[missedmoney]' => 'test var',
            '[lastdayactivity]' => 'test var',
            '[daystoendaction]' => 'test var',
        ];

        $dbVariables = NotificationMailVariables::find()->all();

        $customVariables = $dbVariables ? ArrayHelper::map($dbVariables, 'name', 'value') : [];

        return $defaultVariables + $customVariables;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return Yii::$app->view->params['user']->firstName . ' ' . Yii::$app->view->params['user']->secondName;
    }

    /**
     * @param $user
     * @param $message
     * @param null $data
     * @return mixed
     */
    public function substituteVariables($user, $message, $data = null)
    {
        foreach ($this->getVariables() as $variable => $value) {
            if (isset($variable, $user->{$value})) {
                $message = str_replace($variable, $user->{$value}, $message);
            }
        }

        if ($data) {
            foreach ($data as $option => $value) {
                $message = str_replace($option, $value, $message);
            }
        }

        return $message;
    }


    /**
     * @param $push
     * @return array|\yii\mongodb\ActiveRecord
     */
    public function getUsersToBeSent($push)
    {
        $all = $ranks = array_merge($this->partnersRank, $this->candidatesRank);

        switch ($push->action) {
            case self::TO_ALL:
                $ranks = $all;
                break;
            case self::TO_PARTNERS:
                $ranks = $this->partnersRank;
                break;
            case self::TO_CANDIDATES:
                $ranks = $this->candidatesRank;
                break;
            default:
                $ranks = $all;
                break;
        }

        return Users::find()->where(['in', 'rank', $ranks])->all();
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

            $templateInfo = NotificationMailUserTemplateHistory::find()->where([
                'template_id' => $template->_id,
                'user_id' => $user->_id
            ])->one();

            $this->sendPushTemplateConsideringInterval($template, $user, $templateInfo, $sendDatetime, $data);
        }
    }


    /**
     * @param $template
     * @param $user
     * @param NotificationMailUserTemplateHistory $templateInfo|null
     * @param $sendDatetime
     * @param $data
     */
    protected function sendPushTemplateConsideringInterval($template, $user, $templateInfo, $sendDatetime, $data)
    {
        if ($templateInfo) {
            $lastTimeNewTimeDiff = DateTimeHelper::dateTimeDiff($templateInfo->last_sent_at, $sendDatetime);

            $intervalHour = ($template->interval_hour < 10 ? '0' : '') . $template->interval_hour . ':00:00';
            //@todo day interval

            if (DateTimeHelper::firstDateIsSmaller($lastTimeNewTimeDiff, $intervalHour)) {
                NotificationMailTemplateGroup::create($template->_id, $user->_id, $data);
            } else {
                $templateGroupInfo = NotificationMailTemplateGroup::find()->where([
                    'template_id' => $template->_id,
                    'user_id' => $user->_id
                ])->all();

                if ($templateGroupInfo) {
                    if ($template->group) {
                        // Среди отобранных сообщений группирует те сообщения, шаблон которого это подразумевает.
                        $message = $this->groupMessages($templateGroupInfo);

                        $this->sendTemplatePush($message->template_id, $message->user_id, $sendDatetime, $message->data, [
                            'interval' => true,
                            'group' => true,
                            'from' => NotificationMailTemplateGroup::className(),
                        ]);
                    } else {
                        foreach ($templateGroupInfo as $item) {
                            $this->sendTemplatePush($item->template_id, $item->user_id, $sendDatetime, $item->data, [
                                'interval' => true,
                                'group' => false,
                                'from' => NotificationMailTemplateGroup::className(),
                            ]);

                            $item->delete();
                        }
                    }
                } else {
                    $this->sendTemplatePush($template->_id, $user->_id, $sendDatetime, $data, [
                        'interval' => true,
                        'group' => false,
                        'from' => 'when msg time > interval and it is time to send',
                    ]);
                }

                $templateInfo->updateAll([
                    'template_id' => $template->_id,
                    'user_id' => $user->_id,
                    'last_sent_at' => $sendDatetime
                ]);
            }
        } else {
            $this->sendTemplatePush($template->_id, $user->_id, $sendDatetime, $data, [
                'interval' => false,
                'group' => false,
                'from' => 'when MailUserTemplateHistory is empty (fresh sending)',
            ]);

            NotificationMailUserTemplateHistory::create($template->_id, $user->_id, $sendDatetime);
        }
    }


    /**
     * @param $template
     * @param $currentUsersDateTime
     * @return null|string
     */
    protected function setSendDateTime($template, $currentUsersDateTime)
    {
        if ($template->is_delivery) {
            $usersTime = date('H:i', strtotime($currentUsersDateTime));

            if (DateTimeHelper::firstDateIsSmaller($usersTime, $template->delivery_from)) {
                $sendDatetime = DateTimeHelper::modifyDateByTime($currentUsersDateTime, $template->delivery_from);
            } elseif (DateTimeHelper::firstDateIsBigger($usersTime, $template->delivery_to)) {
                $sendDatetime = null;

                if ($template->next_day_transfer) {
                    $sendDatetime = DateTimeHelper::modifyDateByTime($currentUsersDateTime, $template->delivery_from);
                    $sendDatetime = DateTimeHelper::modifyDateByTime($sendDatetime, '+ 1 day');
                }
            } else {
                $sendDatetime = $currentUsersDateTime;
            }

            return $sendDatetime;
        }

        return $currentUsersDateTime;
    }

    /**
     * Send message to every user
     *
     * @return array
     */
    public function sendPushes()
    {
        $sentList = [];

        $this->queueForUsersWalker(function($pushOrTemplate, $user, $item) {
            //отправим пуш
            if (
                isset($item->datetime) &&
                !DateTimeHelper::isFuture($item->datetime)
            ) {
                // публикацию тела письма в разделе Оповещения приложения "Lifestyle"
                $sentList[] = $this->sendToLifeStyle($user, $pushOrTemplate, $item->message);

                $item->delete();
            }
        });

        return $sentList;
    }


    /**
     * @param $template
     * @param $user
     * @param $sendDatetime
     * @param $data
     * @param null $debugData
     * @param bool $debug
     */
    public function sendTemplatePush($template, $user, $sendDatetime, $data, $debugData = null, $debug = false)
    {
        if ($debug) {
            var_dump($debugData);
            exit;
        }

        $template = NotificationMailTemplates::find()->where(['_id' => $template])->one();
        $user = Users::find()->where(['_id' => $user])->one();
        //@todo send to users

        $queue = NotificationMailQueue::create(null, $template->_id, $template->phrase, $template->language, $sendDatetime, $template->event, 'not_sent');
        // подставляем переменные
        $message = !empty($template->message) ? $this->substituteVariables($user, $template->message, $data) : 'message is unavailable';

        $this->addTemplateQueueToQueueForUser($template, $user, $queue, $sendDatetime, $message);
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


    /**
     * @param $messages
     * @return \stdClass
     */
    public function groupMessages($messages)
    {
        /* We should clear all messages for the next portion of messages */
        foreach ($messages as $message) {
            $this->messagesToSend[] = $message;

            $message->delete();
        }

        $groupMessage = new \stdClass;

        foreach ($this->messagesToSend as $index => $item) {
            if ($index === 0) {
                $groupMessage = $item;

                continue;
            }

            /* Cycle for each shortCode of a message. Do concatenation. */
            foreach ($item->data as $shortCodeName => $shortCodeValue) {
                $addNewValue = $groupMessage->data[$shortCodeName] . ', ' . $shortCodeValue;

                $groupMessage->data = array_merge($groupMessage->data, [$shortCodeName => $addNewValue]);
            }
        }

        return $groupMessage;
    }


    /**
     * @param $user
     * @param $pushOrTemplate
     * @param $message
     * @return bool|\Gomoob\Pushwoosh\Model\Response\CreateMessageResponse
     */
    public function sendToLifeStyle($user, $pushOrTemplate, $message)
    {
        $pushNotification = new PushNotification($pushOrTemplate);

        $title = !empty($pushOrTemplate->phrase) ? $pushOrTemplate->phrase : 'title is unavailable';

        if (isset($user->deviceId)) {

            $status = $pushNotification->sendToDevice([$user->deviceId], $title, $message);

            if ($status) {
                return $status;
            }
        }

        return false;
    }

}