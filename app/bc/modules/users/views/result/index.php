<?php

use yii\helpers\Html;
use app\components\THelper;
use app\modules\business\models\UserCarrier;
use app\modules\business\models\UsersReferrals;

/* @var $this yii\web\View */


$this->title = THelper::t('result_information_about_user');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-danger show_alert">
    Such login did not found!
</div>

<div class="container">
    <div class="row">
       <div class="col-sm-4">
           <div class="form-group wrapper m-b-none">
               <div class="input-group">
                     <span class="input-group-btn">
                          <button type="submit" class="btn btn-default btn-icon search_user"><i class="fa fa-search"></i></button>
                     </span>
                   <input class="form-control search_user_input" placeholder="<?= THelper::t('search_login'); ?>" type="text" name="hello">
               </div>
           </div>
       </div>
        <div class="col-sm-1">
            <div class="form-group wrapper m-b-none">
                <div class="input-group">
                    <?= Html::button(THelper::t('search'),  ['class' => 'btn btn-info pull-left search_user', 'style' => 'margin-left: -50px']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-11">
            <div class="form-group wrapper m-b-none html_here">

            </div>
        </div>
    </div>
</div>


<?php $this->registerJsFile('js/main/result_info.js',['depends'=>['app\assets\AppAsset']]); ?>
