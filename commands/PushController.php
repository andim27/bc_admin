<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


use app\modules\business\controllers\traits\NotificationTrait;
use app\modules\business\models\MailTemplates;
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
        $templates = MailTemplates::find()->where(['event' => $event])->all();

        $this->addTemplatesToAQueue($templates, $user, json_decode($data));
    }
}
