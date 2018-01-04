<?php

namespace app\modules\business\models;

use Yii;
use app\components\THelper;

/**
 * This is the model class for table "crm_users_referrals".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $task_id
 * @property string $referral_link
 * @property integer $parent_id
 * @property integer $sponsor_id
 * @property integer $alert
 */
class UsersReferrals extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'crm_users_referrals';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uid', 'task_id', 'parent_id', 'sponsor_id', 'alert'], 'integer'],
            [['referral_link'], 'required'],
            [['referral_link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => THelper::t('id'),
            'uid' => THelper::t('user_id'),
            'task_id' => THelper::t('task_id'),
            'referral_link' => THelper::t('referral_link'),
            'parent_id' => THelper::t('parent_id﻿'),
            'sponsor_id' => THelper::t('sponsor_id'),
            'alert' => THelper::t('alert')
        ];
    }

    public static function getSpiloverApi($account_id, $levels = 5) {

        $url = Yii::$app->params['apiAddress'] . "user/spilover/{$account_id}&{$levels}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, false, 512, PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0);
    }

    public static function getLastSideApi($account_id, $side = 0) {

        $url = Yii::$app->params['apiAddress'] . "user/lastElement/{$account_id}&{$side}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, false, 512, PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0);
    }

    public static function maketree($arr, $pid = 0, $sid = 0) {//Массив передается по ссылке, чтобы мы могли сразу вытаскивать использованные компоненты. Еще можно отсортировать по parent.
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $color = '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];

        if ($sid) {
            $out = array((object) array('name' => ""), (object) array('name' => ""));
        } else {
            $out = array();
        }


        foreach ($arr as $n => $row) {

            $count = "";
            $info = array(
                "{$row->username}",
                "{$row->rightSideNumberUsers}/{$row->leftSideNumberUsers}",
                "{$row->pointsRight}/{$row->pointsLeft}",
            );

            $tmp = implode("|", $info);

            if ($tmp) {
                $color = '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];
                $r = array('name' => $tmp, "id" => $row->_id, "parent_id" => $row->parentId, "colour" => $color);
                Yii::$app->params['number'] ++;
            }
            if ($row->parentId == $pid) {//Если родитель равен запрашиваемому, такой элемент нам подходит
                unset($arr[$n]); //Удаление вставленного элемента.
                $children = self::maketree($arr, $row->_id, 0); // выбираем детей из массива
                if (count($children) > 0) {//если нашли больше 0 детей, создаем соответствующий ключ
                    $r['children'] = $children;
                }
                if (isset($r)) {
                    if ($sid) {
                        $out[$row->side] = (object) $r;
                    } else {
                        $out[] = (object) $r;
                    }
                }
            }
        }
        return $out;
    }

    public static function build_tree($arr, $pid = 0, $sid = 0, $counter = 0, $class = '', $currentUserModel)
    {
        $out = '';
        $counter++;

        foreach ($arr as $n => $row) {
            $login = $row->username;
            $title = $row->rankString;
            $name = "{$row->firstName} {$row->secondName}";

            if ($row->avatar) {
                $img = $row->avatar;
            } else {
                $img = "/images/avatar_default.png";
            }

            if ($counter == 1) {
                if ($currentUserModel->side == 0) {
                    if ($row->side == 1) {
                        $class = 'bg-info';
                    } else if ($row->side == 0) {
                        $class = 'bg-primary';
                    }
                } else if ($currentUserModel->side == 1) {
                    if ($row->side == 1) {
                        $class = 'bg-primary';
                    } else if ($row->side == 0) {
                        $class = 'bg-info';
                    }
                }
            }

            if ($row->parentId == $pid) {
                if ($row->side == 0) {
                    $r = '<div class="col-sm-6" style="padding: 0"></div>';
                } else {
                    $r = '';
                }
                if ($row->statistics->pack > 0) {
                    $icon = '<span class="block text-center"><img src="/images/genealogy/g_' . $row->statistics->pack . '.png?t=' . time() . '" class="icon m-r-xs m-b-xs" /></span>';
                } else {
                    $icon = '';
                }
                $r .= '
                    <div class="col-sm-6" style="padding: 0">
                        <div class="children panel o-h ' . $class . '" data-side="' . $row->side . '" data-lvl="' . $counter . '" parent-id="' . $row->parentId . '" data-id="' . $row->id . '">
                            <div class="pull-left w-69">
                                <span class="block">
                                    <a href="javascript:void(0);" class="thumb m-r m-b-xs">
                                        <img src="' . $img . '" class="img-circle"/>
                                    </a>
                                </span>
                                ' . $icon . '
                            </div>
                            <p class="user_id" data-id="' . $row->id . '">' . $login . '<br/>' . $name . '<br/>' . $title . '<br/><span class="text-yellow">' . $row->pointsLeft . '</span> / <span class="text-yellow">' . $row->pointsRight . '</span><br/><span>' . $row->leftSideNumberUsers . '</span> / <span>' . $row->rightSideNumberUsers . '</span></p>
                        </div>';
                Yii::$app->params['number'] ++;
                unset($arr[$n]);
                $children = self::build_tree($arr, $row->id, $sid, $counter, $class, $currentUserModel);
                if (count($children) > 0) {
                    $r .= $children . '</div>';
                }
                if (isset($r)) {
                    $out .= $r;
                }
            }
        }

        return $out;
    }

}