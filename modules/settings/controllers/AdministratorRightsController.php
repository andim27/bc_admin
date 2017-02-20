<?php

namespace app\modules\settings\controllers;


use app\modules\settings\models\AuthAssignment;
use app\modules\settings\models\AuthItem;
use app\modules\settings\models\AuthItemChild;
use app\modules\settings\models\Menu;
use app\modules\settings\models\UserAccessRights;
use Yii;
use app\models\User;
use yii\web\ForbiddenHttpException;


class AdministratorRightsController extends \yii\web\Controller
{

    public function actionIndex()
    {
            $admin_list= User::find()->where('role_id=2')->all();
            $title_page= Menu::find()->all();
            if(isset($_POST['account'])) {
                $user_rights=UserAccessRights::find()->where('user_id=:id',[':id'=>$_POST['account']])->one();
            } else {
                $user_rights=array();
            }
            return $this->render('index',[
                'admin_list'=>$admin_list,
                'title_page'=>$title_page,
                'user_rights'=>$user_rights
            ]);
    }






    public function actionSaves(){
        if($_GET['id']<0){
            return 'error';
        }
        else{
            if(
            $models=UserAccessRights::find()
                ->where('user_id=:id',[':id'=>$_GET['id']])
                ->one()
            )
            {
                $models->user_id=$_GET['id'];
                $models->viewing=$_GET['right']['mas_viewing'];
                $models->editing=$_GET['right']['mas_editing'];
                $models->save()?$access=1:$access=0;
            }
            else{
                $models=new UserAccessRights();
                $models->user_id=$_GET['id'];
                $models->viewing=$_GET['right']['mas_viewing'];
                $models->editing=$_GET['right']['mas_editing'];
                $models->save()?$access=1:$access=0;
            }
            if($access){
                if(!empty($mfd=AuthItem::find()
                    ->where('name=:name',[':name'=>'admin_'.$_GET['id']])
                    ->one())){
                    $mfd->delete();
                }
                $auth = Yii::$app->authManager;
                if(!empty($models->viewing)){
                    $array_viewing=explode(',',$models->viewing);
                    foreach ($array_viewing as $tmp){
                        $url=Menu::findOne($tmp)->url.'/index';
                        // добавляем разрешение просмотр
                        $createPost = $auth->createPermission($url);
                        $createPost->description = $url;
                        if(!$mode=AuthItem::find()->where('name=:name',[':name'=>$createPost->name])->one()){
                            $auth->add($createPost);
                        }
                        $admin = $auth->createRole('admin_'.$_GET['id']);
                        if(!$mod=AuthItem::find()->where('name=:name',[':name'=>$admin->name])->one()){
                            $auth->add($admin);
                        }

                        (AuthItemChild::find()
                            ->where('parent=:admin',[':admin'=>$admin->name])
                            ->andWhere('child=:child',[':child'=>$createPost->name])
                            ->one())? $e="error":$e=$auth->addChild($admin, $createPost);
                        // Назначение ролей пользователям.
                        if(!$md=AuthAssignment::find()->where('user_id=:id',[':id'=>$_GET['id']])->one()){
                            $auth->assign($admin, $_GET['id']);
                        }
                    }
                }

                if(!empty($models->editing)){
                    $array_editing= explode(',',$models->editing);
                    foreach ($array_editing as $tmp){
                        $url=Menu::findOne($tmp)->url;
                        // добавляем разрешение просмотр
                        $createPost = $auth->createPermission($url);
                        $createPost->description = $url;
                        if(!$mode=AuthItem::find()->where('name=:name',[':name'=>$createPost->name])->one()){
                            $auth->add($createPost);
                        }
                        $admin = $auth->createRole('admin_'.$_GET['id']);
                        if(!$mod=AuthItem::find()->where('name=:name',[':name'=>$admin->name])->one()){
                            $auth->add($admin);
                        }
                        (AuthItemChild::find()
                            ->where('parent=:admin',[':admin'=>$admin->name])
                            ->andWhere('child=:child',[':child'=>$createPost->name])
                            ->one())? $e="error":$e=$auth->addChild($admin, $createPost);
                        // Назначение ролей пользователям.
                        if(!$md=AuthAssignment::find()->where('user_id=:id',[':id'=>$_GET['id']])->one()){
                            $auth->assign($admin, $_GET['id']);
                        }
                    }
                }
            }

        }
    }

}
