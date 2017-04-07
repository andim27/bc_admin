<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\AlertWidget;
use app\models\Users;
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_settings_warehouse') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
    
    <div class="col-md-offset-9 col-md-3 addWarehouse form-group">
        <?=Html::button(THelper::t('add_warehouse'),['class'=>'btn btn-default btn-block'])?>
    </div>

    <div class="addFormWarehouse">
        <?php $form = ActiveForm::begin([
            'options' => [
                'name'=>'addWarehouse',
                'id'=>'addWarehouse'
            ]
        ]); ?>

        <div class="col-sm-9">
            <div class="form-group">
                <?=Html::input('text','title','',['class'=>'form-control','placeholder'=> THelper::t('sidebar_settings_warehouse')])?>
            </div>
        </div>

        <div class="col-sm-3">
            <?= Html::input('submit', '',THelper::t('add_warehouse'), ['class' => 'btn btn-primary btn-block']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('sidebar_settings_warehouse')?>
                </th>
                <th>
                    <?=THelper::t('user')?>
                </th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($infoWarehouse as $item) { ?>

                <tr>
                    <td>
                        <?=$item->title?>
                    </td>
                    <td class="text-center infoWarehouse">

                        <?=Html::input('hidden','id',$item->_id->__toString())?>

                        <div class="row">
                            <div class="descrItem col-md-12">
                                <?php if(!empty($item->idUsers)) { ?>
                                    <?php foreach($item->idUsers as $itemUser) { ?>
                                        <div class="input-group m-t-sm m-b-sm blItem">
                                            <span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>
                                            <input type="text" class="form-control input-sm" name="setName[]" placeholder="Входит в состав" value="<?= $itemSet->setName; ?>">
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?=Html::dropDownList('listAdmin','placeh',Users::getListAdmin(),[
                                    'class'=>'form-control',
                                    'id'=>'listAdmin',
                                    'options' => [
                                        'placeh' => ['disabled' => true],
                                    ]
                                ])?>

                                <a href="javascript:void(0);" class="btn btn-dark btn-sm btn-icon addItemAdmin" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить пользователя">
                                    <i class="fa fa-plus"></i>
                                </a>

                                <a href="javascript:void(0);" class="btn btn-sm btn-icon saveItemAdmin" data-toggle="tooltip" data-placement="right" title="" data-original-title="Применить правки">
                                    <i class="fa fa-save"></i>
                                </a>

                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>
</section>


<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "asc" ]]
    });

    $(document).on('click','.addWarehouse button',function () {
        $(this).closest('.addWarehouse').hide();
        $('.addFormWarehouse').show();
    });

    $(document).on('click','.addItemAdmin',function () {
        changeBl = $(this).closest('.infoWarehouse');
        valList = changeBl.find('select[name="listAdmin"] option').filter(":selected").val();
        titleList = changeBl.find('select[name="listAdmin"] option').filter(":selected").text();

        if(valList != 'placeh'){
            $(this).closest('.infoWarehouse').find('.descrItem').append(
                '<div class="input-group m-t-sm m-b-sm blItem">'+
                    '<span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>'+
                    '<input type="text" class="form-control input-sm" disabled="disabled" value="'+titleList+'">'+
                    '<input type="hidden" name="idUsers[]" value="'+valList+'">'+
                '</div>'
            );
        }

    });

    $(document).on('click','.removeItem',function () {
        if (confirm('Вы уверены что хотете удалить пользователя?')) {
            $(this).closest('.blItem').remove();
        }
    });

    $(document).on('click','.saveItemAdmin',function () {
        if (confirm('Вы уверены что хотете применить правки?')) {

            var changeBl = $(this).closest('.infoWarehouse');

            $.ajax({
                url: '<?=\yii\helpers\Url::to(['setting/warehouse-admin-save'])?>',
                type: 'POST',
                data: {
                    id : changeBl.find('input[name="id"]').val(),
                    idUsers : changeBl.find('input[name="idUsers[]"]').map(function(){
                        return this.value;
                    }).get(),
                },
                beforeSend: function( xhr){
                    changeBl.append('<div class="loader"><div></div></div>')
                },
                success: function (data) {
                    changeBl.html(data);
                }
            });
        }
    });

</script>