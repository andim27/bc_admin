<?php
namespace app\components\PushNotification\Interfaces;
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 21.12.17
 * Time: 17:34
 */

interface iPush
{
    /**
     * It must contains: phrase, message
     *
     * @return mixed
     */
    public function attributes();
}