<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use yii\widgets\Pjax;
use app\models\Products;

?>


<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('make_repair') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/sale/save-repair',
                'options' => ['name' => 'savePartsAccessories', 'data-pjax' => '1'],
            ]); ?>

            <div class="row form-group">
                <div class="col-md-8">
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
                <div class="col-md-4">
                    <a href="#" class="btn btn-default btn-block getGoods"><?=THelper::t('get_goods')?></a>
                </div>
            </div>

            <div class="listGoodsForRepair">

            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".infoUser").on('change',function () {
        $('.listGoodsForRepair').html('');
    });

    $(".typeInfoUser").on('click',function () {
        nameInput = $(this).data('type');
        contentLabel = $(this).html();

        $(".infoUser").attr('name',nameInput);
        $(".labelUser").html(contentLabel);

        $('.listGoodsForRepair').html('');
    });


    $('.getGoods').on('click',function (e) {
        e.preventDefault();

        if($('.infoUser').val() == ''){
            alert('Введите данные о клиенте!');
            return false;
        }

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['sale/sale-get-products'])?>',
            type: 'POST',
            data: {
                field : $('.infoUser').attr('name'),
                value : $('.infoUser').val(),
            },
            success: function (data) {
                $('.listGoodsForRepair').html(data);
            }
        });
    })
</script>