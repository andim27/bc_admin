<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Products;

$listSet = Products::getListPack();


?>

<?php if(!empty($request['error'])) {?>
    <div class="alert alert-danger fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong><?=$request['error']?></strong>
    </div>
<?php } ?>


<?php $formCom = ActiveForm::begin([
    'action' => '/' . $language . '/business/sale/save-order',
    'options' => ['name' => 'savePartsAccessories', 'data-pjax' => '1'],
]); ?>

<div class="row form-group">
    <div class="col-md-12">
        <div class="input-group">
            <?=Html::input('text',(!empty($request['phone']) ? 'phone' : 'username'),
                (!empty($request['phone']) ? $request['phone'] : (!empty($request['username']) ? $request['username'] : '')),[
                'class'=>'form-control infoUser',
                'required'=>true
            ])?>
            <span class="input-group-btn">
                <div class="btn-group">
                    <button class="btn-default btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span class="labelUser">
                            <i class="fa fa-<?=(!empty($request['phone']) ? 'phone' : 'user')?>"></i>
                            <?=(!empty($request['phone']) ? THelper::t('phone') : THelper::t('login'))?>
                        </span>
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" tabindex="-1" class="typeInfoUser" data-type="username"><i class="fa fa-user"></i> <?=THelper::t('login')?></a></li>
                        <li><a href="javascript:void(0);" tabindex="-1" class="typeInfoUser" data-type="phone"><i class="fa fa-phone"></i> <?=THelper::t('phone')?></a></li>
                    </ul>
                </div>
            </span>
        </div>
    </div>
</div>

<div class="row form-group">
    <div class="col-md-12">
        <?= Html::label(THelper::t('select_product'))?>
        <?=Html::dropDownList('pack',(!empty($request['pack']) ? $request['pack'] : ''),$listSet,[
            'class'=>'form-control',
            'id'=>'selectChangeStatus',
            'required'=>true,
            'options' => [
                '' => ['disabled' => true]
            ]
        ])?>
    </div>
</div>

<div class="row form-group">
    <div class="col-md-12 text-right">
        <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<div class="hidden hiddenForm"></div>


<?php if(!empty($request['answerOrder'])) { ?>
    <script type="text/javascript">
        var url = window.location.origin + '/ru/business/status-sales/search-sales';
        var form = $(
            '<form action="'+url+'" method="POST">' +
            '<input type="text" name="login" value="<?=$request['answerOrder']?>">' +
            '</form>');
        $('.hiddenForm').append(form);
        form.submit();

    </script>
<?php } ?>
