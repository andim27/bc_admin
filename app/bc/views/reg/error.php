<?php
/* @var $this yii\web\View
 * @var $iframe
 * @var $image
 * @var $link
 */
use yii\widgets\ActiveForm;
use app\assets\AppAsset;
use yii\helpers\Html;
use app\models\LanguageList;
use app\components\THelper;
$lang = LanguageList::find()
    ->where('status=:id',[':id'=> 1])
    ->all();

AppAsset::register($this);
?>

<section id="content" class="m-t-lg wrapper-md animated fadeInUp sect">
    <?= Html::a('<img alt ="logo registration" src= "/images/logo_reg.png" width = "" height = "" />', ['/login/login'], ['class' => 'block logo_logo', 'alt' => 'logotip registratsii']) ?>
    <section class="scrollable padder">
        <div class="m-b-md">
            <h3 class="m-b-none"><?=THelper::t('register_company')?> <?= THelper::t('company_name') ?></h3>
        </div>
        <div class="panel panel-default">
            <div class="wizard clearfix" id="form-wizard">
            </div>
            <div class="alert alert-danger">
                <h2><?=$message?></h2>
            </div>
            <div class="for_btn_center">
                <?= Html::a(THelper::t('create_account'), ['registration'], ['class' => 'btn btn-success btn-s-xs']) ?>
            </div>
        </div>
    </section>

    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>

</section>
<br>
<br>

<?php $this->registerJsFile('js/fuelux/fuelux.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/parsley/parsley.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/regref.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('js/fuelux/fuelux.css',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
