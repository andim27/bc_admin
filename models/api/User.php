<?php

namespace app\models\api;

use app\components\ApiClient;
use app\components\THelper;

class User
{

    const DIAGRAM_LEVEL = 2;

    public $id;
    public $email;
    public $skype;
    public $username;
    public $unreadedNews;
    public $unreadedPromotions;
    public $accountId;
    public $layout;
    public $avatar;
    public $firstName;
    public $secondName;
    public $moneys;
    public $charityPercent;
    public $firstPurchase;
    public $phoneNumber;
    public $phoneNumber2;
    public $created;
    public $rank;
    public $expirationDateBS;
    public $leftSideNumberUsers;
    public $rightSideNumberUsers;
    public $sideToNextUser;
    public $countryCode;
    public $sponsor;
    public $statistics;
    public $qualification;
    public $links;
    public $city;
    public $state;
    public $birthday;
    public $address;
    public $zipCode;
    public $rankString;
    public $nextRegistration;
    public $settings;
    public $side;
    public $parentId;
    public $pointsLeft;
    public $pointsRight;
    public $autoExtensionBS;
    public $chldrnLeftId = '';
    public $chldrnRightId = '';
    public $structBonus;
    public $personalBonus;
    public $promotions;

    /**
     * Return user
     *
     * @param $param
     * @return User
     */
    public static function get($param)
    {
        $apiClient = new ApiClient('user/' . $param);

        $response = $apiClient->get();

        $result = self::_getResults($response);

        return $result ? current($result) : false;
    }

    /**
     * Returns users
     *
     * @param $limit
     * @param $offset
     * @return bool|mixed
     */
    public static function users($limit, $offset)
    {
        $apiClient = new ApiClient('users/' . $limit . '&' . $offset);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns admins
     *
     * @return bool|mixed
     */
    public static function admins()
    {
        $apiClient = new ApiClient('users/admin');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Set language
     *
     * @param $userId
     * @param $language
     */
    public static function setLanguage($userId, $language)
    {
        $apiClient = new ApiClient('user');

        $apiClient->put([
            'iduser' => $userId,
            'selectedLang' => $language
        ]);
    }

    public static function setCharityPercent($userId, $charityPercent)
    {
        $apiClient = new ApiClient('user');

        $result = $apiClient->put([
            'iduser' => $userId,
            'charityPercent' => $charityPercent
        ], false);

        return $result == 'OK';
    }

    /**
     * Returns unreaded notifications
     *
     * @return array
     */
    public function unreadedNotifications()
    {
        return [
            'unreadedNews' => $this->unreadedNews,
            'unreadedPromotions' => $this->unreadedPromotions,
        ];
    }

    /**
     * Auth user
     *
     * @param $login
     * @param $password
     * @return User|bool
     */
    public static function auth($login, $password)
    {
        $apiClient = new ApiClient('auth/' . $login . '&' . $password);

        $result = $apiClient->get();

        if (isset($result->id) && $result->id) {
            return self::get($login);
        }

        return false;
    }

    /**
     * Auth admin
     *
     * @param $login
     * @param $password
     * @return User|bool
     */
    public static function authAdmin($login, $password)
    {
        $apiClient = new ApiClient('auth/admin/' . $login . '&' . $password);

        $result = $apiClient->get(false);

        return $result == 'OK';
    }

    /**
     * Returns personal partners
     *
     * @param $userId
     * @return bool|mixed
     */
    public static function personalPartners($userId)
    {
        $apiClient = new ApiClient('user/personalPartners/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns spilover
     *
     * @param $userId
     * @return bool|mixed
     */
    public static function spilover($userId, $levels = 5)
    {
        $apiClient = new ApiClient('user/spilover/' . $userId . '&' . $levels);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    public static function upSpilover($userId, $levels = null)
    {
        $url = 'user/upSpilover/' . $userId;

        if ($levels) {
            $url .= '&' . $levels;
        }

        $apiClient = new ApiClient($url);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Returns last element
     *
     * @param $userId
     * @param $side
     * @return User|bool
     */
    public static function lastElement($userId, $side)
    {
        $apiClient = new ApiClient('user/lastElement/'. $userId . '&' . $side);

        $response = $apiClient->get();

        if (isset($response->username)) {
            $result = self::get($response->username);
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return bool|mixed
     */
    private static function _getResults($data)
    {
        $result = [];

        if ($data) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $user = new self;

                $user->id                 = $object->{'_id'};
                $user->email              = $object->email;
                $user->skype              = $object->skype;
                $user->username           = $object->username;
                $user->unreadedNews       = $object->statistics->unreadNews;
                $user->unreadedPromotions = $object->statistics->unreadPromotions;
                $user->accountId          = $object->accountId;
                $user->layout             = $object->settings->layout;
                $user->avatar             = isset($object->avatar) ? $object->avatar : '';
                $user->firstName          = $object->firstName;
                $user->secondName         = $object->secondName;
                $user->moneys             = $object->moneys;
                $user->charityPercent     = $object->settings->charityPercent;
                $user->firstPurchase      = strtotime($object->firstPurchase);
                $user->created            = strtotime($object->created);
                $user->phoneNumber        = $object->phoneNumber;
                $user->phoneNumber2       = $object->phoneNumber2;
                $user->rank               = $object->rank;
                $user->status             = $object->status;
                $user->expirationDateBS   = strtotime($object->expirationDateBS);
                $user->side               = $object->side;
                $user->parentId           = $object->parentId;
                $user->pointsLeft         = $object->pointsLeft;
                $user->pointsRight        = $object->pointsRight;
                $user->autoExtensionBS    = $object->autoExtensionBS;

                if (isset($object->structBonus)) {
                    $user->structBonus = $object->structBonus;
                }

                if (isset($object->personalBonus)) {
                    $user->personalBonus = $object->personalBonus;
                }

                if (isset($object->qualification)) {
                    $user->qualification = $object->qualification;
                }

                if (isset($object->chldrnLeftId)) {
                    $user->chldrnLeftId = $object->chldrnLeftId;
                }

                if (isset($object->chldrnRightId)) {
                    $user->chldrnRightId = $object->chldrnRightId;
                }

                if ($user->side == -1) {
                    $user->side = 1;
                }

                if (isset($object->settings)) {
                    $user->settings = $object->settings;
                }

                if (isset($object->leftSideNumberUsers)) {
                    $user->leftSideNumberUsers  = $object->leftSideNumberUsers;
                }

                if (isset($object->rightSideNumberUsers)) {
                    $user->rightSideNumberUsers = $object->rightSideNumberUsers;
                }

                if (isset($object->sideToNextUser)) {
                    $user->sideToNextUser = $object->sideToNextUser;
                }

                $user->countryCode          = $object->country;
                $user->city                 = $object->city;
                $user->state                = $object->state;
                $user->address              = $object->address;
                $user->zipCode              = $object->zipCode;

                if (isset($object->birthday)) {
                    $user->birthday = strtotime($object->birthday);
                }
                if (isset($object->sponsor)) {
                    $user->sponsor = $object->sponsor;
                }
                if (isset($object->statistics)) {
                    $user->statistics = $object->statistics;
                }
                if (isset($object->qualification)) {
                    $user->qualification = $object->qualification;
                }
                if (isset($object->links)) {
                    $user->links = $object->links;
                }
                if (isset($object->settings)) {
                    $user->settings = $object->settings;
                }

                if (isset($user->rank)) {
                    $rank = THelper::t('rank_'.$user->rank);
                    $user->rankString = $rank;
                }

                if (isset($object->nextRegistration)) {
                    $user->nextRegistration = $object->nextRegistration;
                }

                if (isset($object->settings->timeZone)) {
                    $user->settings->timeZone = json_decode($object->settings->timeZone);
                }

                if (isset($object->promotions)) {
                    $user->promotions = $object->promotions;
                }

                $result[] = $user;
            }
        }

        return $result;
    }

    /**
     * Returns country
     *
     * @return bool|mixed
     */
    public function getCountry()
    {
        return dictionary\Country::get($this->countryCode);
    }


    public function getCountryCityAsString()
    {
        $country = $this->getCountry();
        $city = $this->city;

        $countryCity = [];

        if ($country) {
            array_push($countryCity, $country->name);
        }
        if ($city) {
            array_push($countryCity, $city);
        }

        return $countryCity ? implode(' / ', $countryCity) : '';
    }

    /**
     * Updates user info
     *
     * @param $accountId
     * @param $data
     * @return bool|mixed
     */
    public static function update($accountId, $data)
    {
        $apiClient = new ApiClient('user');

        $data = array_merge($data, ['accountId' => $accountId]);

        $response = $apiClient->put($data, false);

        if ($response == 'OK') {
            return self::get($accountId);
        }

        return false;
    }

    /**
     * Create user
     *
     * @param $data
     * @return bool|mixed
     */
    public static function create($data)
    {
        $apiClient = new ApiClient('user');

        $response = $apiClient->post($data);

        $result = $response ? self::_getResults($response) : false;

        return $result ? current($result) : false;
    }

    /**
     * Reset password
     *
     * @param $email
     * @return bool
     */
    public static function resetPassword($email)
    {
        $apiClient = new ApiClient('user/resetPassword');

        $response = $apiClient->post([
            'email' => $email,
            'type'  => 0
        ], false);

        return $response == 'OK';
    }

    /**
     * @param $users
     * @param int $parentId
     * @return array|bool
     */
    public static function buildDiagramData($users, $parentId)
    {
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        if (is_array($users) && isset($users[$parentId])) {
            $tree = [];
            foreach ($users[$parentId] as $user) {
                $children = self::buildDiagramData($users, $user['id']);
                $color = '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];
                $tree[] = [
                    'name' => $user['name'],
                    'children' => $children,
                    'colour' => ! $children ? $color : ''
                ];
            }
        } else {
            return [];
        }

        return $tree;
    }

    /**
     * Change password
     *
     * @param $userId
     * @param $oldPassword
     * @param $newPassword
     * @param $type
     * @return bool
     */
    public static function changePassword($userId, $oldPassword, $newPassword, $type)
    {
        $apiClient = new ApiClient('user/changePassword');

        $response = $apiClient->post([
            'iduser'      => $userId,
            'oldPassword' => $oldPassword,
            'newPassword' => $newPassword,
            'type'        => $type
        ], false);

        return $response == 'OK';
    }

}