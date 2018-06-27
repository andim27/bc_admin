<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 12.12.17
 * Time: 17:23
 */

namespace app\components\PushNotification;


use Yii;
use app\Components\PushNotification\Interfaces\iPush;
use Gomoob\Pushwoosh\Client\CURLClient;
use Gomoob\Pushwoosh\Client\Pushwoosh;
use Gomoob\Pushwoosh\Model\Notification\Android;
use Gomoob\Pushwoosh\Model\Notification\IOS;
use Gomoob\Pushwoosh\Model\Notification\Notification;
use Gomoob\Pushwoosh\Model\Request\CreateMessageRequest;
use Gomoob\Pushwoosh\Model\Request\DeleteMessageRequest;

class PushNotification
{
    public static $STATUS_NEW = 'new';
    public static $STATUS_DELIVERING = 'delivering';
    public static $STATUS_CANCELLED = 'canceled';
    public static $STATUS_DONE = 'done';
    public static $STATUS_ERROR = 'error';

    protected $_pushModel;
    protected $pushwoosh;

    public $sound = 'push_points.wav';

    /**
     * PushNotification constructor.
     *
     * @param iPush $msg
     */
    public function __construct(iPush $msg)
    {
        $this->_pushModel = $msg;

        $this->pushwoosh = Pushwoosh::create()
            ->setApplication(Yii::$app->params['pushwooshApp'])
            ->setAuth(Yii::$app->params['pushwooshAuth']);
    }


    /**
     * @param $devices
     * @param $header
     * @param $message
     * @param bool $sound
     * @return \Gomoob\Pushwoosh\Model\Response\CreateMessageResponse
     */
    public function sendToDevice($devices, $header, $message, $sound = false)
    {
        $notification = Notification::create()
            ->setAndroid(Android::create()
                ->setHeader($header)
            )
            ->setContent($message)
            ->setDevices($devices);
//          ->setData(
//               []
//            );
//          ->setSendDate($dateSend->addMinutes(2)->format('Y-m-d H:i'));

        if ($sound) {
            $notification = $notification->setAndroid(Android::create()
                ->setHeader($header)
                ->setSound($this->sound)
            )
                ->setIOS(IOS::create()->setSound('res/raw/' . $this->sound));
        }

        $request = CreateMessageRequest::create()
            ->addNotification($notification);

        return $this->pushwoosh->createMessage($request);
    }


    /**
     * Message details
     *
     * @throws \Gomoob\Pushwoosh\Exception\PushwooshException
     */
    public function getMessageDetails()
    {
        $curl = new CURLClient();

        $data['message'] = (string)$this->_pushModel->_id;

        $data['auth'] = Yii::$app->params['pushwooshAuth'];

        $response = $curl->pushwooshCall('getMessageDetails', $data);

        $statusCode = $response['status_code'];

        if ($statusCode == '210') {

            $this->_setStatus(self::$STATUS_CANCELLED);

        } elseif ($statusCode == '200' && $response['response']['message']['status'] == 'done') {

            $this->_pushModel->delivered = $this->_pushModel->sended;

            $this->_setStatus(self::$STATUS_DONE);
        }
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $request = DeleteMessageRequest::create()->setMessage((string)$this->_pushModel->_id);

        if (! is_null($request->getMessage())) {

            $response = $this->pushwoosh->deleteMessage($request);

            return (boolean)$response->isOk();
        }

        return false;
    }


    /**
     * @param $code
     */
    protected function _setStatus($code)
    {
        switch ($code) {
            case self::$STATUS_NEW:
            case self::$STATUS_DELIVERING:
            case self::$STATUS_CANCELLED:
            case self::$STATUS_DONE:
            case self::$STATUS_ERROR:
                $this->_pushModel->status = $code;
                $this->_pushModel->save();
                break;
        }
    }
}