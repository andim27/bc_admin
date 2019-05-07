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
            <div class="col-md-6"></div>
            <div class="col-md-1">В наличие</div>
            <div class="col-md-1">У исполнителя</div>
            <div class="col-md-1">На одну шт.</div>
            <div class="col-md-1">Надо отправить</div>
            <div class="col-md-1">С запасом</div>
            <div class="col-md-1 partNoneComplect">Не комплект</div>
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
                                    <div class="col-md-6 mmm">
                                        <?=Html::hiddenInput('complectInterchangeable['.(string)$item['_id'].'][]',$kInterchangeable,[]);?>
                                        <?=Html::input('text','',$itemInterchangeable,
                                            [
                                                'class'=>'form-control partTitle',
                                                'disabled'=>true,
                                                'data' => [
                                                    'toggle'    =>  'tooltip',
                                                    'placement' =>  'placement',
                                                    'part_id'   => (string)$kInterchangeable
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
                                            'data-part_id'=> (string)$kInterchangeable,
                                            'data-one_number'=> $item['number'],
                                            'data-n_in_wh'=>(!empty($listGoodsFromMyWarehouse[$kInterchangeable]) ? $listGoodsFromMyWarehouse[$kInterchangeable] : 0 )
                                        ]);?>
                                    </div>
                                    <div class="col-md-1">
                                        <?=Html::input('number','reserve['.$kInterchangeable.']','0',[
                                            'class'=>'form-control partNeedReserveInterchangeable',
                                            'pattern'=>'\d*',
                                            'step'=>'1',
                                        ]);?>
                                    </div>
                                    <div class="col-md-1">
                                        <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[$kInterchangeable]) ? $listGoodsFromMyWarehouse[$kInterchangeable] : 0 ),['class'=>'partNoneComplect']);?>
                                        <?=Html::input('number','numberNoneComplect['.$kInterchangeable.']','0',[
                                            'class'=>'form-control partNoneComplect',
                                            'pattern'=>'\d*',
                                            'disabled'=>'disabled',
                                            'title' => 'Не комплект(_kit-p-lang)',
                                            'id'=>'NoneComplect-'.(string)$kInterchangeable
                                        ]);?>
                                    </div>
                                </div>
                        <?php } ?>
                            <?php } ?>


                            <div class="form-group row totalInterchangeable">
                                <div class="col-md-6"></div>
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
                                <div class="col-md-1">
                                    <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[$kInterchangeable]) ? $listGoodsFromMyWarehouse[$kInterchangeable] : 0 ),['class'=>'partNoneComplect']);?>
                                    <?=Html::input('number','numberNoneComplect['.$kInterchangeable.']','',[
                                        'class'=>'form-control partNoneComplect',
                                        'pattern'=>'\d*',
                                        'disabled'=>'disabled',
                                        'title' => 'Не комплект(_kit)',
                                        'id'=>'NoneComplect-'.(string)$kInterchangeable
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
                        <div class="col-md-6">
                            <?=Html::hiddenInput('complect[]',(string)$item['_id'],[]);?>
                            <?=Html::input('text','',$listGoods[(string)$item['_id']],
                                [
                                    'class'=>'form-control partTitle',
                                    'disabled'=>true,
                                    'data' => [
                                        'toggle'    =>  'tooltip',
                                        'placement' =>  'placement',
                                        'part_id'   => (string)$item['_id']
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
                                <div class="col-md-1">
                                    <?=Html::input('number','numberNoneComplect['.(string)$item['_id'].']',0,[
                                        'class'=>'form-control partNoneComplect',
                                        'pattern'=>'\d*',
                                        'step'=>'1',
                                        'disabled'=>true,
                                        'title' => 'Не комплект(_kit_exec!empty)',
                                        'id'=>'NoneComplect-'.(string)$item['_id']
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

    $(document).ready(function() {
        $('.none-complect-ch-row').show();
        //needSendInterchangeable
        $(".needSendInterchangeable").on("blur",function () {
            cur_val     = parseFloat($(this).val());
            cur_part_id = $(this).attr('data-part_id');
            cur_one_number = parseFloat($(this).attr('data-one_number'));
            cur_n_in_wh = parseFloat($(this).attr('data-n_in_wh'));
            console.log('needSendInterchangeable val= '+cur_val+' part_id='+cur_part_id,' cur_n_in_wh='+cur_n_in_wh);
            if (($("#NoneComplect-"+cur_part_id).css('display') =='inline-block')){
                if ((cur_val >= cur_n_in_wh)) {
                    console.log('yes,need none comp');
                    $("#NoneComplect-"+cur_part_id).val(cur_val*cur_one_number);
                    $("#itemNoneComplect-"+cur_part_id).val(cur_val*cur_one_number);
                }


            }
        });
    });
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