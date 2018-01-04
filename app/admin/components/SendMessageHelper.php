<?php
namespace app\components;

class SendMessageHelper
{
    const LINK_SENDER = 'http://ovh-1.ooo.ua:3039/receiveMessage';

    public static function sendMessage($phone,$messenger,$body){

        $post = [
            'phone' => $phone,
            'messenger' => $messenger,
            'body'   => $body,
        ];

        $ch = curl_init(self::LINK_SENDER);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $response = curl_exec($ch);

        curl_close($ch);

        return true;

    }
}