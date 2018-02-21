<?php

namespace app\components;

use DateTime;

class DateTimeHelper {
    /**
     * @param $time
     * @return bool
     */
    public static function isToday($time) // midnight second
    {
        return (strtotime($time) === strtotime('today'));
    }


    /**
     * @param $time
     * @return bool
     */
    public static function isPast($time)
    {
        return (strtotime($time) < time());
    }


    /**
     * @param $time
     * @return bool
     */
    public static function isFuture($time)
    {
        return (strtotime($time) > time());
    }


    /**
     * @param $strEnd
     * @param string $strStart
     * @param string $format
     * @return bool|\DateInterval|string
     */
    public static function dateTimeDiff($strEnd, $strStart = 'now', $format = '%H:%I:%S')
    {
        $dteStart = new DateTime($strStart);
        $dteEnd   = new DateTime($strEnd);

        $dteDiff  = $dteStart->diff($dteEnd);

        return $format ? $dteDiff->format($format) : $dteDiff;
    }


    /**
     * @param $timestamp
     * @param int $offset
     * @param string $format
     * @return string
     */
    public static function modifyTimestampByOffset($timestamp, $offset = 0, $format = 'Y-m-d H:i:s')
    {
        $objDateTime = new DateTime(date($format, $timestamp));

        if ($offset) {
            $objDateTime->modify($offset . ' hours');
        }

        return $objDateTime->format($format);
    }


    /**
     * @param $date1
     * @param $date2
     * @return bool
     */
    public static function firstDateIsBigger($date1, $date2)
    {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);

        return $datetime1 > $datetime2;
    }


    /**
     * @param $date1
     * @param $date2
     * @return bool
     */
    public static function firstDateIsSmaller($date1, $date2)
    {
        return !self::firstDateIsBigger($date1, $date2);
    }


    /**
     * @param $date1
     * @param $date2
     * @return DateTime
     */
    public static function subtractDates($date1, $date2)
    {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);

        return $datetime1 - $datetime2;
    }


    /**
     * @param $date
     * @param $timeString
     * @param string $format
     * @return string
     */
    public static function modifyDateByTime($date, $timeString, $format = 'Y-m-d H:i:s')
    {
        $dt = new DateTime($date);
        $hours = explode(':', $timeString);
        $minutes = isset($hours[1]) ? $hours[1] : null;
        $hours = $hours[0];

        if ($minutes) {
            $dt->setTime($hours, $minutes);
        } else {
            $dt->modify($timeString);
        }

        return $dt->format($format);
    }
}