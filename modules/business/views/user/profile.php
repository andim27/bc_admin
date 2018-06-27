<?php
    use app\components\THelper;
    use app\models\PaymentCard;

    $listPaymentCards = PaymentCard::getListCards();
?>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#by-user-info" class="" data-toggle="tab">
                            <?= THelper::t('users_profile_title'); ?>
                        </a>
                    </li>
<!--                    <li class="">-->
<!--                        <a href="#by-user-purchase" class="" data-toggle="tab">-->
<!--                            --><?//= THelper::t('user_purchase'); ?>
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="">-->
<!--                        <a href="#by-user-movement-money" class="" data-toggle="tab">-->
<!--                            --><?//= THelper::t('user_movement_money'); ?>
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="">-->
<!--                        <a href="#by-user-movement-points" class="" data-toggle="tab">-->
<!--                            --><?//= THelper::t('user_movement_points'); ?><!-- -->
<!--                        </a>-->
<!--                    </li>-->
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="by-user-info">
                        <?= $this->render('_tab-user-profile',[
                            'model'             => $model,
                            'languages'         => $languages,
                            'listPaymentCards'  => $listPaymentCards,
                            'countries'         => $countries,
                            'user'              => $user,
                            'notes'             => $notes,
                        ]); ?>
                    </div>
                    <div class="tab-pane" id="by-user-purchase">
                        <?= $this->render('_tab-user-purchase',[
                            'modelSales'        => $modelSales
                        ]); ?>
                    </div>
                    <div class="tab-pane" id="by-user-movement-money">
                        <?= $this->render('_tab-user-movement-money',[
                            'model'             => $model,
                            'modelMovementMoney'    => $modelMovementMoney
                        ]); ?>
                    </div>
                    <div class="tab-pane" id="by-user-movement-points">
                        <?= $this->render('_tab-user-movement-points',[
                            'model'             => $model,
                            'modelMovementPoints'    => $modelMovementPoints
                        ]); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



<?php $this->registerJsFile('/js/main/business_center_notes.js'); ?>
<?php $this->registerCssFile('/css/main.css'); ?>


<script type="text/javascript">
    listPaymentCard = <?=json_encode($listPaymentCards)?>;

    $(".addNewCard").on('click',function () {
        flAddNow = 1;

        cardVal = $(".listCart  :selected").val();
        cardText = $('.listCart :selected').text();

        if(cardVal==''){
            alert('<?=THelper::t('not_selecting_card')?>');
            flAddNow = 0;
        }

        $('.infoCard .itemCard').each(function () {
            if($(this).data('card') == cardVal) {
                alert('<?=THelper::t('card_exists_already')?>');
                flAddNow = 0;
            }
        });

        if(flAddNow != 1){
            return;
        }

        $(".infoCard").append(
            '<div class="itemCard" data-card="'+cardVal+'">' +
                '<div class="col-md-4 labelCard">' +
                     cardText +
                '</div>' +
                '<div class="col-md-6">' +
                    '<input type="hidden" name="ProfileForm[cards]['+cardVal+'][card_type]" value="'+cardVal+'" class="form-control">' +
                    '<input type="hidden" name="ProfileForm[cards]['+cardVal+'][card_label]" value="'+listPaymentCard[cardVal]+'" class="form-control">' +
                    '<input type="text" name="ProfileForm[cards]['+cardVal+'][card_value]" value="" class="form-control">' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<a class="btn btn-default btn-block removeCard" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a>' +
                '</div>' +
            '</div>'
        );
    });

    $('.infoCard').on('click','.removeCard',function () {
       $(this).closest('.itemCard').remove();
    });

</script>


<script type="text/javascript">
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>
