<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


use app\modules\business\controllers\traits\NotificationTrait;
use app\modules\business\models\NotificationMailTemplates;
use yii\console\Controller;


class PushController extends Controller
{
    use NotificationTrait;

    /**
     * @var array
     */
    protected $partnersRank = [1, 2, 3];
    /**
     * @var array
     */
    protected $candidatesRank = [0];

    /**
     * Push actions
     */
    const TO_ALL = 0;
    const TO_PARTNERS = 1;
    const TO_CANDIDATES = 2;

    /**
     * У нас есть следующие сущности:
     * MailPushes - тут у нас находятся пуши, которые сохраняем в админке. Пока они просто есть и никуда не отправляются
     * MailQueue - тут мы заносим пуши, которые нужно отправить. Скрипт в кроне пробегается здесь и отправляет письма в очередь MailQueueForUsers
     * MailTemplates - тут мы храним шаблоны писем для ивентов. Как только получим ивент, который соответствует шаблону - передаем его в очередь
     * MailQueueForUsers - сюда попадают письма в итоге, очередь на отправку
     * MailUserTemplateHistory - сюда заносим сообщения шаблонов для того что бы понимать отправляли ли пользователю письмо и когда. Если да, то дальше включается интервальная логика
     * MailTemplateGroup - сюда попадают сообщения как в стек для реализации интервальной отправки. Как только время подходит для отправки, то отправляем отсюда все сообщения в очередь
     */


    /**
     * Run command "php yii push" by cron
     *
     * @param string $parameter
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionIndex($parameter = 'send')
    {
        $this->addPushesToAQueue();

        if ($parameter === 'send') {
            $this->sendPushes();
        }
    }


    /**
     * Run command "php yii push/send-to-users" by cron
     */
    public function actionSendToUsers()
    {
        $this->sendPushes();
    }


    /**
     * Run command "php yii push/fire-event 'event' 'user' 'json'"
     *
     * @param $event
     * @param $user
     * @param $data
     */
    public function actionFireEvent($event, $user, $data)
    {
        $templates = NotificationMailTemplates::find()->where(['event' => $event])->all();

        $this->addTemplatesToAQueue($templates, $user, json_decode($data));
    }
}
