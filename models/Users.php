<?php

namespace app\models;

use app\models\api\dictionary\Country;
use MongoDB\BSON\ObjectID;
use yii\helpers\ArrayHelper;
use yii2tech\embedded\mongodb\ActiveRecord;
use yii\base\Model;

/**
 * Class Users
 * @package app\models
 */
class Users extends ActiveRecord
{

    public static $userMenuRules;

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'users';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'username',
            'accountId',
            'created',
            'email',
            'skype',
            'phoneNumber',
            'statistics',
            'phoneNumber2',
            'firstName',
            'secondName',
            'rank',
            'isAdmin',
            'warehouseName',
            'rulesAdmin',
            'settings',
            'sponsor',
            'country',
            'city',
            'address',
            'moneys',
            'avatar',
            'firstPurchase',
            'countryData',
            'cityData',
            'city'
        ];
    }

    public function embedRules()
    {
        return $this->mapEmbedded('rulesAdmin', RulesAdmin::className());
    }

    public static function getUserEmail($idUser)
    {
        $infoUser = Users::findOne($idUser);
        if(!empty($infoUser->email)){
            $email = $infoUser->email;
        } else {
            $email = false;
        }
        return $email;
    }

    public static function getRulesUser()
    {
        $model = self::find()->select(['rulesAdmin'])
            ->where(['_id'=>new ObjectID(\Yii::$app->view->params['user']->id)])
            ->one();

        return (!empty($model->rulesAdmin) ? $model->rulesAdmin : '');
    }
    

    public static function checkRule($rule,$key)
    {
        $fl = false;

        if (\Yii::$app->view->params['user']->username == 'main'){
            $fl = true;
        }

        if($fl !== true){
            $rules = self::$userMenuRules;
            if(empty($rules)){
                $rules = self::getRulesUser();
                self::$userMenuRules = $rules;
            }

            if(!empty($rules[$rule]) && in_array($key,$rules[$rule])){
                $fl = true;
            }
        }

            
        return $fl;
    }

    public static function getAllAdmin(){
        return self::find()
            ->where(['isAdmin' => 1])
            ->all();
    }

    public static function getListAdmin()
    {
        $listAdmin['placeh'] = 'Выберите пользователя';

        $model = self::find()
            ->where(['isAdmin' => 1])
            ->orderBy(['username'=>SORT_ASC])
            ->all();
        if(!empty($model)){
            foreach ($model as $item) {
                $listAdmin[(string)$item->_id] = $item->username . ' ('.$item->secondName.' '.$item->firstName.')';
            }
        }


        return $listAdmin;
    }

    public static function getListHeadAdminAdmin()
    {
        $listAdmin['placeh'] = 'Выберите пользователя';

        $infoWarehouse = Warehouse::find()->where(['headUser'=>new ObjectID(\Yii::$app->view->params['user']->id)])->all();

        if(!empty($infoWarehouse)){
            foreach ($infoWarehouse as $item) {
                if(!empty($item->idUsers)){
                    $infoUser = self::getListAdmin();
                    foreach ($item->idUsers as $itemId){
                        if(!empty($infoUser[$itemId])){
                            $listAdmin[$itemId] = $infoUser[$itemId];
                        }
                    }
                }
            }
        }

        return $listAdmin;
    }

    public function getStatistics()
    {
        return $this->statistics;
    }

    public static function getStatisticBonusEquity()
    {
        return Transaction::find()
            ->where(['amount'])
            ->where([
                'idTo'=>new ObjectID(\Yii::$app->view->params['user']->id),
                'forWhat' => [
                    '$regex' => 'For stocks'
                ]
            ])
            ->sum('amount');
    }

    public static function checkHeadAdmin()
    {
        $infoWarehouse = Warehouse::find()->where(['headUser'=>new ObjectID(\Yii::$app->view->params['user']->id)])->all();

        if(!empty($infoWarehouse)){
            $answer = true;
        } else {
            $answer = false;
        }

        return $answer;
    }

    public static function getListWarehouseAdmin()
    {
        $lang = \Yii::$app->language;

        $model = self::getAllAdmin();

        $list = [];

        foreach ($model as $item){
            if(!empty($item->warehouseName[$lang])){
                $list[(string)$item->_id] = $item->warehouseName[$lang];
            }
        }

        return $list;
    }

    public static function getSearchInfoUser($field,$search){
        $model = self::find()->where(['LIKE',$field,$search])->one();

        return !empty($model) ? $model->{$field} : '';
    }


    public static function getAllUsers()
    {
        $list = [];

        $model = self::find()->select(['_id','username'])->all();
        if(!empty($model)){
            foreach ($model as $item) {
                $list[(string)$item->_id] = $item->username;
            }
        }

        return $list;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country ? Country::get($this->country)->name : '';
    }

}

class RulesAdmin extends Model
{
    public $showMenu;
    public $edit;
    public $transaction_cash;
    public $show_statistic;

    public function rules()
    {
        return [
            [['showMenu','edit','transaction_cash','show_statistic'], 'safe'],
        ];
    }
}
