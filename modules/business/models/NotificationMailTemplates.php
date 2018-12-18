<?php

namespace app\modules\business\models;

use yii\mongodb\ActiveRecord;
use app\components\THelper;

/**
 * Class MailTemplates
 * @package app\models
 */
class NotificationMailTemplates extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'notification_mail_templates';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'language',
            'phrase',
            'message',
            'event',
            'next_day_transfer',
            'interval_hour',
            'interval_day',
            'group',
            'is_delivery',
            'delivery_from',
            'delivery_to',
            'author',
            'created_at',
            'updated_at',
        ];
    }

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

}