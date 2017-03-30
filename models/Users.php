<?php

namespace app\models;

use MongoDB\BSON\ObjectID;
use yii2tech\embedded\mongodb\ActiveRecord;
use yii\base\Model;

/**
 * Class Pins
 * @package app\models
 */
class Users extends ActiveRecord
{
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
            'email',
            'phoneNumber',
            'phoneNumber2',
            'firstName',
            'secondName',
            'rank',
            'isAdmin',
            'rulesAdmin'
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
        $model = self::find()
            ->where(['_id'=>new ObjectID(\Yii::$app->view->params['user']->id)])
            ->one();
        
        return $model->rules;
    }
    
    public static function checkRule($rule,$key)
    {
        $fl = false;
        $rules = self::getRulesUser();
        if(!empty($rules->{$rule}) && in_array($key,$rules->{$rule})){
            $fl = true;
        } elseif (\Yii::$app->view->params['user']->username == 'main'){
            $fl = true;
        }
            
        return $fl;
            
    }
    
}

class RulesAdmin extends Model
{
    public $showMenu;
    public $edit;

    public function rules()
    {
        return [
            [['showMenu','edit'], 'safe'],
        ];
    }
}