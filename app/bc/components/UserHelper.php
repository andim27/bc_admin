<?php

namespace app\components;

class UserHelper {
    private static $requiredFields = [
        'firstName', 'secondName', 'username', 'email'
    ];

    private static $phoneFields = [
        'phoneFB', 'phoneTelegram', 'phoneViber', 'phoneWhatsApp'
    ];

    /**
     * @param $user
     * @return bool
     */
    public static function hasEmptyFields($user)
    {
        function checkCommonFields($requiredFields, $user){
            $hasEmptyFields = false;

            foreach ($requiredFields as $field) {
                if (empty($user->{$field})) {
                    $hasEmptyFields = true;
                }
            }

            return $hasEmptyFields;
        }

        function checkPhoneFields($phoneFields, $user){
            $hasEmptyFields = true;

            foreach ($phoneFields as $field) {
                if (!empty($user->settings->{$field})) {
                    $hasEmptyFields = false;
                }
            }

            return $hasEmptyFields;
        }

        return checkCommonFields(static::$requiredFields, $user)/* || checkPhoneFields(static::$phoneFields, $user)*/;
    }

    /**
     * @param $user
     * @return array
     */
    public static function getEmptyFields($user)
    {
        function deleteFilledCommonFields($requiredFields, $user){
            foreach ($requiredFields as $field) {
                if (!empty($user->{$field})) {
                    unset($requiredFields[array_search($field, $requiredFields)]);
                }
            }

            return $requiredFields;
        }

        function deleteFilledPhoneFields($phoneFields, $user){
            foreach ($phoneFields as $field) {
                if (!empty($user->settings->{$field})) {
                    unset($phoneFields[array_search($field, $phoneFields)]);
                }
            }

            return $phoneFields;
        }

        $requiredFields = deleteFilledCommonFields(static::$requiredFields, $user);
        $phoneFields = deleteFilledPhoneFields(static::$phoneFields, $user);

        return array_merge($phoneFields, $requiredFields);
    }

    /**
     * @param $empty_fields
     * @return bool
     */
    public static function hasNoMessengerPhones($empty_fields)
    {
       return count(array_intersect($empty_fields, static::$phoneFields)) === count(static::$phoneFields);
    }
}