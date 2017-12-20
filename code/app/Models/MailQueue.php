<?php

namespace App\Models;

use Moloquent;

class MailQueue extends Moloquent {

    protected $table = 'mailqueues';

    const MESSENGER_WHATSAPP = 'WhatsApp';
    const MESSENGER_VIBER = 'Viber';
    const MESSENGER_FACEBOOK = 'Facebook';
    const MESSENGER_TELEGRAM = 'Telegram';
    const MESSENGER_ALL = 'all';
    const MESSENGER_MAIL = 'Mail';
    const MESSENGERS = [self::MESSENGER_WHATSAPP, self::MESSENGER_VIBER, self::MESSENGER_FACEBOOK, self::MESSENGER_TELEGRAM, self::MESSENGER_MAIL];

    const STATUS_SENDED = 1;
    const STATUS_NOT_SENDED = 0;

}