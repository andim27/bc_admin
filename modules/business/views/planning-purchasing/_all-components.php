<?php



?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group row">
                <div class="col-md-5"></div>
                <div class="col-md-1">На одну шт.</div>
                <div class="col-md-1">В наличии</div>
                <div class="col-md-1">Надо заказать</div>
                <div class="col-md-1">Цена за шт.</div>
                <div class="col-md-1">Сколько берем</div>
                <div class="col-md-2">Стоимость</div>
            </div>
            <?php if(!empty($model->composite)){ ?>
                <?php foreach($model->composite as $item){ ?>

                    <?= $this->render('_complects',[
                        'infoComposite'     =>  $item,
                        'level'             =>  '1',
                        'count'             =>  '1'
                    ]); ?>

                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>


<script>
    $('.blPartsAccessories').on('change','select[name="complect[]"]', function () {

        var blComposite = $(this).closest('.blockComposite');

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['planning-purchasing/update-changeable-list'])?>',
            type: 'POST',
            data: {
                goodsId     :   $(this).val(),
                goodsParent :   $(this).data('parent'),
                goodsCount  :   $(this).data('count'),
                goodsLevel  :   $(this).data('level'),
                wantMake    :   $('.needPlaning').val()
            },
            success: function (data) {
                blComposite.html(data);
            }
        });
    })
</script>