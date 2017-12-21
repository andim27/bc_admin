<?php

namespace App\Models\Repositories;

use App\Models\Sale;
use App\Models\User;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectID;

class UserRepository
{
    public $model;

    public function __construct(User $user)
    {
        $this->model = $user;

        return $this;
    }

    public function getUsersToMain()
    {
        $users = [];
        $this->_getParent($this->model, $users);

        return $users;
    }

    /**
     * @param User $user
     * @param $result
     * @return array
     */
    private function _getParent(User $user, &$result)
    {
        $parent = $user->parent;

        if (!$parent) {
            return $result;
        } else {
            $result[] = $parent;
            $this->_getParent($parent, $result);
        }
    }

    /**
     * @return bool
     */
    public function havePack()
    {
        return $this->model->statistics['pack'] >= User::PACK_BEGINNER && $this->model->statistics['pack'] <= User::PACK_VIP;
    }

    /**
     * @return bool
     */
    public function hasInvestorPack()
    {
        return Sale::where('idUser', '=', new ObjectID($this->model->_id))
                ->where('type', '=', Sale::TYPE_CREATED)
                ->whereIn('product', [43, 44, 45, 46, 47, 48])
                ->count() > 0;
    }

    /**
     * @return mixed
     */
    public function getFirstSale()
    {
        return $firstSale = Sale::where('idUser', '=', new ObjectID($this->model->_id))
            ->where('reduced', '=', true)
            ->where('type', '=', Sale::TYPE_CREATED)
            ->orderBy('dateReduce', 'asc')->first();
    }

    public function getPersonalPartnersWithPurchasesCount()
    {
        return User::where('sponsorId', '=', new ObjectID($this->model->_id))
            ->where('firstPurchase', '!=', null)
            ->where('firstPurchase', '!=', '')
            ->count();
    }

    public function getPersonalPartners()
    {
        return User::where('sponsorId', '=', new ObjectID($this->model->_id))->get();
    }

    /**
     * @return mixed
     */
    public function getPersonalPartnersCount()
    {
        return User::count('sponsorId', '=', new ObjectID($this->model->_id));
    }

    /**
     * @param $side
     * @return int
     */
    public function countPartners($side)
    {
        $count = 0;

        switch ($side) {
            case User::SIDE_LEFT:
                if ($this->model->chldrnLeftId) {
                    $user = User::where('_id', '=', new ObjectID($this->model->chldrnLeftId))->select('chldrnLeftId', 'chldrnRightId')->first();
                }
                if (isset($user) && $user) {
                    $count++;
                    $this->_countPartners([$user], $count);
                }
                break;
            case User::SIDE_RIGHT:
                if ($this->model->chldrnRightId) {
                    $user = User::where('_id', '=', new ObjectID($this->model->chldrnRightId))->select('chldrnLeftId', 'chldrnRightId')->first();
                }
                if (isset($user) && $user) {
                    $count++;
                    $this->_countPartners([$user], $count);
                }
                break;
        }

        return $count;
    }

    /**
     * @param $users
     * @param $number
     */
    private function _countPartners($users, &$number)
    {
        if (!$users) {
            return;
        } else {
            $tmpUsers = [];
            foreach ($users as $user) {
                if ($user->chldrnLeftId) {
                    $number++;
                    if ($childrenLeft = User::where('_id', '=', new ObjectID($user->chldrnLeftId))->select('chldrnLeftId', 'chldrnRightId')->first()) {
                        $tmpUsers[] = $childrenLeft;
                    }
                }
                if ($user->chldrnRightId) {
                    $number++;
                    if ($childrenRight = User::where('_id', '=', new ObjectID($user->chldrnRightId))->select('chldrnLeftId', 'chldrnRightId')->first()) {
                        $tmpUsers[] = $childrenRight;
                    }
                }
            }
            $this->_countPartners($tmpUsers, $number);
        }
    }

    public function countPartnersWithPurchases()
    {
        $count = 0;

        $this->_countPartnersWithPurchases([$this->model], $count);

        return $count;
    }

    private function _countPartnersWithPurchases($users, &$number)
    {
        if (!$users) {
            return;
        } else {
            $tmpUsers = [];
            foreach ($users as $user) {
                if ($user->chldrnLeftId) {
                    if ($leftUser = User::where('_id', '=', new ObjectID($user->chldrnLeftId))->select('firstPurchase', 'chldrnLeftId', 'chldrnRightId')->first()) {
                        if ($leftUser->firstPurchase) {
                            if (is_string($leftUser->firstPurchase)) {
                                $firstSaleDate = strtotime($leftUser->firstPurchase);
                            } else {
                                $firstSaleDate = $leftUser->firstPurchase->toDateTime()->getTimestamp();
                            }
                            if ($firstSaleDate > 0) {
                                $number++;
                            }
                        }
                        $tmpUsers[] = $leftUser;
                    }
                }
                if ($user->chldrnRightId) {
                    if ($rightUser = User::where('_id', '=', new ObjectID($user->chldrnRightId))->select('firstPurchase', 'chldrnLeftId', 'chldrnRightId')->first()) {
                        if ($rightUser->firstPurchase) {
                            if (is_string($rightUser->firstPurchase)) {
                                $firstSaleDate = strtotime($rightUser->firstPurchase);
                            } else {
                                $firstSaleDate = $rightUser->firstPurchase->toDateTime()->getTimestamp();
                            }
                            if ($firstSaleDate > 0) {
                                $number++;
                            }
                        }
                        $tmpUsers[] = $rightUser;
                    }
                }
            }
            $this->_countPartnersWithPurchases($tmpUsers, $number);
        }
    }

    /**
     * @param $level
     * @return array
     */
    public function getPersonalSpilover($level)
    {
        $user = $this->model;

        $currentLevel = 0;

        $tmpUsers = [$user];
        $spilovers = [];

        while ($currentLevel < $level) {
            $tmpUsers2 = $tmpUsers;
            $tmpUsers = [];

            foreach ($tmpUsers2 as $tmpUser2) {
                $users = User::where('sponsorId', '=', new ObjectID($tmpUser2->_id))->get();

                foreach ($users as $u) {
                    array_push($spilovers, $u);
                    array_push($tmpUsers, $u);
                }
            }

            $currentLevel++;
        }

        return $spilovers;
    }

    /**
     * @param bool $smallData
     * @param null $level
     * @return array
     */
    public function getSpilover($smallData = false, $level = null)
    {
        $user = $this->model;

        if (!is_null($level)) {
            $level--;
            if ($level > 0) {
                $queue[] = $user;
            }
        } else {
            $queue[] = $user;
        }

        $spilover[] = $smallData ? new ObjectID($user->_id) : $user;

        $this->_getSpilover($smallData, $queue, $spilover, $level);

        return $spilover;
    }

    /**
     * @param $queue
     * @param $spilover
     * @param $level
     */
    private function _getSpilover($smallData, &$queue, &$spilover, $level)
    {
        if (count($queue) > 0) {
            $currentUser = array_shift($queue);
            if ($currentUser->chldrnLeftId) {
                $childrenLeft = $currentUser->childrenLeft;
                if (!is_null($level)) {
                    $level--;
                    if ($level > 0) {
                        $queue[] = $childrenLeft;
                    }
                } else {
                    $queue[] = $childrenLeft;
                }
                $spilover[] = $smallData ? new ObjectID($childrenLeft->_id) : $childrenLeft;
            }
            if ($currentUser->chldrnRightId) {
                $childrenRight = $currentUser->childrenRight;
                if (!is_null($level)) {
                    $level--;
                    if ($level > 0) {
                        $queue[] = $childrenRight;
                    }
                } else {
                    $queue[] = $childrenRight;
                }
                $spilover[] = $smallData ? new ObjectID($childrenRight->_id) : $childrenRight;
            }
            $this->_getSpilover($smallData, $queue, $spilover, $level);
        }
        return;
    }

    /**
     * @param $side
     * @return mixed
     */
    public function getLastUser($side)
    {
        $result = '';

        $this->_getLastUser($this->model, $side, $result);

        return $result;
    }

    /**
     * @param User $user
     * @param $side
     * @return User
     */
    private function _getLastUser(User $user, $side, &$result)
    {
        switch ($side) {
            case User::SIDE_LEFT:
                if ($user->chldrnLeftId) {
                    $leftUser = User::where('_id', '=', new ObjectID($user->chldrnLeftId))->select('_id', 'chldrnLeftId', 'chldrnRightId')->first();
                    if ($leftUser) {
                        $this->_getLastUser($leftUser, $side, $result);
                    } else {
                        $result = false;
                    }
                } else {
                    $result = User::find($user->_id);
                }
                break;
            case User::SIDE_RIGHT:
                if ($user->chldrnRightId) {
                    $rightUser = User::where('_id', '=', new ObjectID($user->chldrnRightId))->select('_id', 'chldrnLeftId', 'chldrnRightId')->first();
                    if ($rightUser) {
                        $this->_getLastUser($rightUser, $side, $result);
                    } else {
                        $result = false;
                    }
                } else {
                    $result = User::find($user->_id);
                }
                break;
        }
    }

    /**
     * @param $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return hash_hmac('sha1', $password, $this->model->_id) == $this->model->hashedPassword;
    }

    /**
     * @param $password
     * @return bool
     */
    public function checkFinancialPassword($password)
    {
        return hash_hmac('sha1', $password, $this->model->_id) == $this->model->hashedFinPassword;
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     * @return bool
     */
    public function changePassword($oldPassword, $newPassword)
    {
        if ($this->checkPassword($oldPassword)) {
            $this->model->_plainPassword = $newPassword;
            $this->model->hashedPassword = hash_hmac('sha1', $newPassword, $this->model->_id);
            return $this->model->save() ? $this->model->hashedPassword : false;
        } else {
            return false;
        }
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     * @return bool
     */
    public function changeFinancialPassword($oldPassword, $newPassword)
    {
        if ($this->checkFinancialPassword($oldPassword)) {
            $this->model->_plainFinPassword = $newPassword;
            $this->model->hashedFinPassword = hash_hmac('sha1', $newPassword, $this->model->_id);
            return $this->model->save() ? $this->model->hashedFinPassword : false;
        } else {
            return false;
        }
    }

    /**
     * @param $sponsor
     * @param $params
     * @return bool
     */
    public function createUser($sponsor, $params)
    {
        if ($sponsor->settings['manualRegistrationControl'] == 0 || ! $sponsor->nextRegistration) {
            $lastUser = $sponsor->getLastUser($sponsor->sideToNextUser);
        } else {
            $nextRegistrationUser = User::find($sponsor->nextRegistration['_id']);

            if ($nextRegistrationUser) {
                $lastUser = $nextRegistrationUser->getLastUser($sponsor->sideToNextUser);
            }
        }

        if (isset($lastUser) && $lastUser) {
            $user = $this->model;

            $user->sponsorId = new ObjectID($sponsor->_id);
            $user->email = $params['email'];
            $user->username = isset($params['username']) ? $params['username'] : '';
            $user->firstName = isset($params['fname']) ? $params['fname'] : '';
            $user->secondName = isset($params['sname']) ? $params['sname'] : '';
            $user->phoneNumber = $params['phone'];
            $user->country = $params['country'];
            $user->parentId = new ObjectID($lastUser->_id);
            $user->accountId = intval(rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9));
            $user->sideToNextUser = $sponsor->sideToNextUser;
            $user->side = $sponsor->sideToNextUser;
            $user->moneys = 0;
            $user->pointsLeft = 0;
            $user->pointsRight = 0;
            $user->created = new UTCDateTime(time() * 1000);
            $user->cardNumber = '';
            $user->phoneNumber2 = '';
            $user->phoneWellness = '';
            $user->bs = false;
            $user->qualification = false;
            $user->defaultLang = '';
            $user->address = '';
            $user->city = '';
            $user->state = '';
            $user->zipCode = '';
            $user->isAdmin = 0;
            $user->personal = false;
            $user->leftSideNumberUsers = 0;
            $user->rightSideNumberUsers = 0;
            $user->status = 1;
            $user->avatar = '';
            $user->rank = 0;
            $user->structBonus = false;
            $user->personalBonus = false;
            $user->autoExtensionBS = false;
            $user->linkedAccounts = [];
            $user->careerHistory = [];

            $system = [
                'readNews' => [],
                'readPromotions' => []
            ];

            $user->system = $system;

            $warehouseName = [
                'ru' => '',
                'en' => ''
            ];

            $user->warehouseName = $warehouseName;

            $landing = [
                'analytics' => '',
                'analytics2' => '',
                'analytics_vipvip' => '',
                'analytics_webwellness_ru' => '',
                'analytics_webwellness_net' => ''
            ];

            $user->landing = $landing;

            $user->potential = 0;

            $links = [
                'site' => '',
                'odnoklassniki' => '',
                'vk' => '',
                'fb' => '',
                'youtube' => ''
            ];

            $user->links = $links;

            $statistics = [
                'partnersWithPurchases' => 0,
                'structIncome' => 0,
                'personalIncome' => 0,
                'personalPartners' => 0,
                'personalPartnersWithPurchases' => 0,
                'unreadNews' => 0,
                'unreadPromotions' => 0,
                'steps' => 0,
                'pack' => 0,
                'mentorBonus' => 0,
                'careerBonus' => 0,
                'executiveBonus' => 0,
                'worldBonus' => 0,
                'autoBonus' => 0,
                'propertyBonus' => 0,
                'priorityInvestmentBonus' => 0,
                'sharesVIPVIP' => 0,
                'dividendsVIPVIP' => 0,
                'tokens' => 0,
                'stock' => [
                    'vipvip' => [
                        'total' => 0,
                        'buy' => 0,
                        'earned' => 0
                    ],
                    'wellness' => [
                        'total' => 0,
                        'buy' => 0,
                        'earned' => 0
                    ]
                ],
            ];

            $user->statistics = $statistics;

            $settings = [
                'layout' => 1,
                'showMobile' => 1,
                'showEmail' => 1,
                'showName' => 1,
                'deliveryEMail' => 1,
                'deliverySMS' => 1,
                'notifyAboutCheck' => 1,
                'notifyAboutJoinPartner' => 1,
                'notifyAboutReceiptsMoney' => 1,
                'notifyAboutReceiptsPoints' => 1,
                'notifyAboutEndActivity' => 1,
                'notifyAboutOtherNews' => 1,
                'selectedLang' => 'ru',
                'charityPercent' => 0,
                'manualRegistrationControl' => 0,
                'phoneWhatsApp' => '',
                'phoneViber' => '',
                'phoneTelegram' => '',
                'phoneFB' => '',
                'onMapX' => '',
                'onMapY' => ''
            ];

            $user->settings = $settings;

            if (isset($params['skype']) && $params['skype']) {
                $user->skype = $params['skype'];
            }

            if (isset($params['phoneViber']) && $params['phoneViber']) {
                $user->setAttribute('settings.phoneViber', $params['phoneViber']);
            }

            if (isset($params['phoneTelegram']) && $params['phoneTelegram']) {
                $user->setAttribute('settings.phoneTelegram', $params['phoneTelegram']);
            }

            if (isset($params['phoneWhatsApp']) && $params['phoneWhatsApp']) {
                $user->setAttribute('settings.phoneWhatsApp', $params['phoneWhatsApp']);
            }

            if (isset($params['phoneFB']) && $params['phoneFB']) {
                $user->setAttribute('settings.phoneFB', $params['phoneFB']);
            }

            if ($user->save()) {
                $user->_plainPassword = $params['password'];
                $user->hashedPassword = hash_hmac('sha1', $params['password'], $user->_id);
                $user->salt = $user->_id;
                if (isset($params['finPassword'])) {
                    $user->_plainFinPassword = $params['finPassword'];
                    $user->hashedFinPassword = hash_hmac('sha1', $params['finPassword'], $user->_id);
                    $user->finSalt = $user->_id;
                }
                if ($user->save()) {
                    switch ($sponsor->sideToNextUser) {
                        case User::SIDE_LEFT:
                            $lastUser->chldrnLeftId = new ObjectID($user->_id);
                            break;
                        case User::SIDE_RIGHT:
                            $lastUser->chldrnRightId = new ObjectID($user->_id);
                            break;
                    }

                    if ($lastUser->save() && $sponsor->settings['manualRegistrationControl'] == 0) {
                        $nextRegistrationShortData = [
                            '_id' => new ObjectID($user->_id),
                            'email' => $user->email,
                            'skype' => $user->skype,
                            'username' => $user->username,
                            'accountId' => $user->accountId,
                            'avatar' => $user->avatar,
                            'rank' => $user->rank,
                            'links' => $user->links,
                            'zipCode' => $user->zipCode,
                            'state' => $user->state,
                            'country' => $user->country,
                            'city' => $user->city,
                            'address' => $user->address,
                            'sideToNextUser' => $user->sideToNextUser,
                            'phoneNumber2' => $user->phoneNumber2,
                            'phoneNumber' => $user->phoneNumber,
                            'firstName' => $user->firstName,
                            'secondName' => $user->secondName,
                            'cards' => $user->cards
                        ];

                        $sponsor->nextRegistration = $nextRegistrationShortData;

                        if ($sponsor->save()) {
                            /**
                             * @todo add timezone, stock for registration, email for registration
                             */
                            return $user;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getMainUser()
    {
        return User::find('573a0d76965dd0fb16f60bfe');
    }

    /**
     * @return mixed
     */
    public function getCompanyUser()
    {
        return User::find('000000000000000000000001');
    }

}