<?php

namespace app\components;

class DateHelper {

    /**
     * Check ISO8601
     *
     * @param $date
     * @return bool
     */
    public static function validateISO8601Date($date){
        $pattern = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';

        if (date("U", strtotime($date)) > 0 && preg_match($pattern, $date) > 0){
            return true;
        }

        return false;
    }
}