<?php

namespace App\Models;

use Moloquent;

class User extends Moloquent {

    const PACK_BEGINNER = 1;
    const PACK_STANDARD = 2;
    const PACK_VIP      = 3;

    const SIDE_LEFT  = 1;
    const SIDE_RIGHT = 0;

    const MESSENGER_FACEBOOK = 'facebook';
    const MESSENGER_VIBER    = 'viber';
    const MESSENGER_WHATSAPP = 'whatsapp';
    const MESSENGER_TELEGRAM = 'telegram';

    const PASSWORD_TYPE_MAIN = 0;
    const PASSWORD_TYPE_FINANCIAL = 1;

    protected $hidden = ['sponsor', '_plainPassword', '_plainFinPassword', 'promotions'];

//    protected $casts = [
//
//    ];
//
//    protected $dates = [
//        'firstPurchase',
//        'lastDateLogin',
//        'created',
//        'birthday',
//        'promotions.travel.dateComplete',
//        'statistics.dateBuyPack',
//        'expirationDateBS'
//    ];

    /**
     * @return mixed
     */
    public function sponsor()
    {
        return $this->belongsTo('App\Models\User', 'sponsorId', '_id');
    }

    /**
     * @return mixed
     */
    public function embedsSponsor()
    {
        return $this->embedsOne('App\Models\User', 'sponsor');
    }

    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\User', 'parentId', '_id');
    }

    /**
     * @return mixed
     */
    public function childrenLeft()
    {
        return $this->belongsTo('App\Models\User', 'chldrnLeftId', '_id');
    }

    /**
     * @return mixed
     */
    public function childrenRight()
    {
        return $this->belongsTo('App\Models\User', 'chldrnRightId', '_id');
    }

    /**
     * @return Repositories\UserRepository
     */
    public function getRepository()
    {
        return new Repositories\UserRepository($this);
    }

    /**
     * @param $side
     * @return mixed
     */
    public function getLastUser($side)
    {
        return $this->getRepository()->getLastUser($side);
    }

    /**
     * @param $sponsor
     * @param $params
     * @return mixed
     */
    public static function createUser($sponsor, $params)
    {
        $user = new self();

        return $user->getRepository()->createUser($sponsor, $params);
    }

    /**
     * @return mixed
     */
    public static function getMainUser()
    {
        $user = new self();

        return $user->getRepository()->getMainUser();
    }

    /**
     * @return mixed
     */
    public static function getCompanyUser()
    {
        $user = new self();

        return $user->getRepository()->getCompanyUser();
    }
}