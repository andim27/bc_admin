<?php
use yii\helpers\Html;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\ExecutionPosting;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

//$contractorInfo = ExecutionPosting::getCountSpareForContractor();
$contractorInfo = [];
function pasportLang($name,$p_lang) {
    if ($p_lang == 'all') {
        return true;
    }
    if ( preg_match('/Паспорт/', $name)  ) {
        if ($p_lang == 'rus') {
            if (preg_match('/рус/', $name)) {
                return true;
            }
            else {
                return false;
            }
        }
        if ($p_lang == 'eng') {
            if ( preg_match('/анг/', $name)) {
                return true;
            }
            else {
                return false;
            }
        }
        if ($p_lang == 'lat') {
            //echo "<br>----p_lang:$p_lang-------------$name---------------<br>";
            if ( preg_match('/латв/', $name)) {
                return true;
            }
            else {
                return false;
            }
        }
        if ($p_lang == 'eng_rus') {
            if (preg_match('/рус|анг/', $name)) {
                return true;
            }
            else {
                return false;
            }
        }
        if ($p_lang == 'eng_lat') {
            if (preg_match('/латв|анг/', $name)) {
                return true;
            }
            else {
                return false;
            }
        }
    } else {
        return true;
    }

}
?>

<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row form-group">
            <div class="col-md-7"></div>
            <div class="col-md-1">В наличие</div>
            <div class="col-md-1">У исполнителя</div>
            <div class="col-md-1">На одну шт.</div>
            <div class="col-md-1">Надо отправить</div>
            <div class="col-md-1">С запасом</div>
        </div>
        <?php if(!empty($model->composite)){ ?>
            <?php foreach($model->composite as $item){ ?>
                    <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['_id']))) { ?>
                    <?php $interchangeableList = PartsAccessories::getInterchangeableList((string)$item['_id']);?>
                    <div class="panel panel-default blInterchangeable">
                        <div class="panel-body">
                            <div class="infoDangerExecution"></div>
                            <div class="form-group row">
                                <div class="col-md-12 blTitleInterchangeable">
                                    <?=implode(" / ",$interchangeableList)?>
                                </div>
                            </div>

                            <?php
                                $temp = [
                                    'listGoodsFromMyWarehouse'  =>  0,
                                    'partContractor'            =>  0,
                                ];
                            ?>
                            <?php foreach ($interchangeableList as $kInterchangeable => $itemInterchangeable) {?>
                                <?php

                                    $partContractor = ExecutionPosting::getPresenceInPerformer($kInterchangeable,$performerId);

                                    $temp['listGoodsFromMyWarehouse'] += (!empty($listGoodsFromMyWarehouse[$kInterchangeable]) ? $listGoodsFromMyWarehouse[$kInterchangeable] : 0);
                                    $temp['partContractor'] += $partContractor;

                                ?>
                        <?php if ((!empty($p_lang)) && pasportLang($itemInterchangeable,$p_lang)) {?>
                                <div class="form-group row">
                                    <div class="col-md-7 mmm">
                                        <?=Html::hiddenInput('complectInterchangeable['.(string)$item['_id'].'][]',$kInterchangeable,[]);?>
                                        <?=Html::input('text','',$itemInterchangeable,
                                            [
                                                'class'=>'form-control partTitle',
                                                'disabled'=>true,
                                                'data' => [
                                                    'toggle'    =>  'tooltip',
                                                    'placement' =>  'placement'
                                                ],
                                                'title' => $itemInterchangeable
                                            ]);?>
                                    </div>
                                    <div class="col-md-1">
                                        <?=Html::input('text',
                                            '',
                                            (!empty($listGoodsFromMyWarehouse[$kInterchangeable]) ? $listGoodsFromMyWarehouse[$kInterchangeable] : 0 ),
                                            ['class'=>'form-control inWarehouseInterchangeable','disabled'=>'disabled']);?>
                                    </div>
                                    <div class="col-md-1">
                                        <?=Html::input('text','',
                                            $partContractor,
                                            ['class'=>'form-control partContractorInterchangeable','disabled'=>'disabled']); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?=Html::hiddenInput('number['.$kInterchangeable.']',$item['number'],[]);?>
                                        <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOneInterchangeable','disabled'=>'disabled']);?>
                                    </div>
                                    <div class="col-md-1">
                                        <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[$kInterchangeable]) ? $listGoodsFromMyWarehouse[$kInterchangeable] : 0 ),['class'=>'numberWarehouse']);?>
                                        <?=Html::input('number','numberUseInterchangeable['.$kInterchangeable.']','0',[
                                            'class'=>'form-control needSendInterchangeable',
                                            'pattern'=>'\d*',
                                            'step'=>'0.01',
                                        ]);?>
                                    </div>
                                    <div class="col-md-1">
                                        <?=Html::input('number','reserve['.$kInterchangeable.']','0',[
                                            'class'=>'form-control partNeedReserveInterchangeable',
                                            'pattern'=>'\d*',
                                            'step'=>'1',
                                        ]);?>
                                    </div>
                                </div>
                        <?php } ?>
                            <?php } ?>


                            <div class="form-group row totalInterchangeable">
                                <div class="col-md-7"></div>
                                <div class="col-md-1">
                                    <?=Html::input('text',
                                        '',
                                        $temp['listGoodsFromMyWarehouse'],
                                        ['class'=>'form-control inWarehouse','disabled'=>'disabled']);?>
                                </div>
                                <div class="col-md-1">
                                    <?=Html::input('text','',
                                        $temp['partContractor'],
                                        ['class'=>'form-control partContractor','disabled'=>'disabled']); ?>
                                </div>
                                <div class="col-md-1">
                                    <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOne','disabled'=>'disabled']);?>
                                </div>
                                <div class="col-md-1">
                                    <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[$kInterchangeable]) ? $listGoodsFromMyWarehouse[$kInterchangeable] : 0 ),['class'=>'numberWarehouse']);?>
                                    <?=Html::input('text','',$item['number'],['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                </div>
                                <div class="col-md-1">
                                    <?=Html::input('text','','0',[
                                        'class'=>'form-control partNeedReserve',
                                        'disabled'=>'disabled'
                                    ]);?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } else {?>
                        <?php
                            $partContractor = ExecutionPosting::getPresenceInPerformer((string)$item['_id'],$performerId);
                        ?>
                    <?php if ((!empty($p_lang)) && pasportLang($listGoods[(string)$item['_id']],$p_lang)) {?>
                            <div class="form-group row blUnique">
                        <div class="col-md-7">
                            <?=Html::hiddenInput('complect[]',(string)$item['_id'],[]);?>
                            <?=Html::input('text','',$listGoods[(string)$item['_id']],
                                [
                                    'class'=>'form-control partTitle',
                                    'disabled'=>true,
                                    'data' => [
                                        'toggle'    =>  'tooltip',
                                        'placement' =>  'placement'
                                    ],
                                    'title' => $listGoods[(string)$item['_id']]
                                ]);?>
                        </div>
                        <div class="col-md-1">
                            <?=Html::input('text',
                                '',
                                (!empty($listGoodsFromMyWarehouse[(string)$item['_id']]) ? $listGoodsFromMyWarehouse[(string)$item['_id']] : 0 ),
                                ['class'=>'form-control inWarehouse','disabled'=>'disabled']);?>
                        </div>
                        <div class="col-md-1">
                            <?=Html::input('text','',
                                $partContractor,
                                ['class'=>'form-control partContractor','disabled'=>'disabled']); ?>
                        </div>
                        <div class="col-md-1">
                            <?=Html::hiddenInput('number['.(string)$item['_id'].']',$item['number'],[]);?>
                            <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOne','disabled'=>'disabled']);?>
                        </div>
                        <div class="col-md-1">
                            <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['_id']]) ? $listGoodsFromMyWarehouse[(string)$item['_id']] : 0 ),['class'=>'numberWarehouse']);?>
                            <?=Html::input('text','',$item['number'],['class'=>'form-control needSend','disabled'=>'disabled']);?>
                        </div>
                        <div class="col-md-1">
                            <?=Html::input('number','reserve['.(string)$item['_id'].']','0',[
                                'class'=>'form-control partNeedReserve',
                                'pattern'=>'\d*',
                                'step'=>'1',
                            ]);?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
    </div>
</div>

<script>
    canCollect = '<?=PartsAccessoriesInWarehouse::getHowMuchCanCollectWithInterchangeable((string)$model->_id,$performerId)?>';
    atContractor = <?=json_encode($contractorInfo)?>;

    if(canCollect==0){
        $('.assemblyBtn').hide();
    } else {
        $('.assemblyBtn').show();
    }

    console.log(canCollect);

    $(document).find('.CanCollect').val(canCollect);

//    $(document).on('change','select[name="complect[]"]',function () {
//
//        changeRow = $(this).closest('.row');
//        newComplect = $(this).val();
//
//        countNewComplect = 0;
//        if(listGoodsFromMyWarehouse[newComplect]){
//            countNewComplect = listGoodsFromMyWarehouse[newComplect];
//        }
//
//        countAtContractor = 0;
//        if(atContractor[newComplect]){
//            countAtContractor = atContractor[newComplect];
//        }
//
//        listComponents = $(".blPartsAccessories").find('input[name="complect[]"],select[name="complect[]"] option:selected').map(function(){
//            return this.value;
//        }).get();
//
//        $.ajax({
//            url: '<?//=\yii\helpers\Url::to(['submit-execution-posting/calculate-kit'])?>//',
//            type: 'POST',
//            data: {
//                id : $('#selectGoods').val(),
//                listComponents : listComponents,
//            },
//            success: function (data) {
//                $(document).find('.CanCollect').val(data);
//                changeRow.find('.inWarehouse').val(countNewComplect);
//                changeRow.find('.numberWarehouse').val(countNewComplect);
//                changeRow.find('.partContractor').val(countAtContractor);
//                changeRow.find('input[name="contractor[]"]').val(countAtContractor);
//            }
//        });
//
//    })

</script>