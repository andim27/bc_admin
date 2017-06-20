<?php
    use app\components\THelper;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\PaymentCard;

    $listPaymentCards = PaymentCard::getListCards();
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('users_profile_title'); ?></h3>
</div>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-5">
        <section class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'name')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_name')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'surname')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_surname')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'email')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_email')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'login')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_login')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'mobile')->textInput(['maxlength' => 16, 'class' => 'form-control'])->label(THelper::t('user_profile_mobile')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'smobile')->textInput(['maxlength' => 16, 'class' => 'form-control'])->label(THelper::t('user_profile_another_mobile')) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'phoneWellness')->textInput(['class' => 'form-control'])->label(THelper::t('profile_phone_wellness')) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'skype')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_skype')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="select-country"><?= THelper::t('user_profile_country') ?></label>
                            <select id="select-country" class="form-control" name="country">
                                <?php foreach ($countries as $country) { ?>
                                    <option value="<?= $country->alpha2 ?>" <?= strtoupper($user->countryCode) == $country->alpha2 ? 'selected="selected"' : ''?>><?= $country->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'state')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_state')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'city')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_city')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'address')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_address')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'site')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_website_blog')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'odnoklassniki')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_page_odnoklassniki')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'vk')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_page_vkontakte')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'fb')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_page_facebook')) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'youtube')->textInput(['class' => 'form-control'])->label(THelper::t('user_profile_youtube_channel')) ?>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="row infoCard">
                            <input type="hidden" name="ProfileForm[cards][1][card_type]" value="">
                            <input type="hidden" name="ProfileForm[cards][1][card_value]" value="">
                            <?php if(!empty($model->cards)){?>
                                <?php foreach($model->cards as $vCard){?>
                                    <?php if(!empty($vCard['card_value'])) {?>
                                        <div class="itemCard" data-card="'+cardVal+'">
                                            <div class="col-md-4 labelCard">
                                                <?=THelper::t($listPaymentCards[$vCard['card_type']]);?>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" name="ProfileForm[cards][<?=$vCard['card_type']?>][card_type]" value="<?=$vCard['card_type']?>">
                                                <input type="hidden" name="ProfileForm[cards][<?=$vCard['card_type']?>][card_label]" value="<?=$listPaymentCards[$vCard['card_type']]?>">
                                                <input type="text" name="ProfileForm[cards][<?=$vCard['card_type']?>][card_value]" value="<?=$vCard['card_value']?>" class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <a class="btn btn-default btn-block removeCard" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>

                        <div class="row">
                            <div class="col-md-10">
                                <?=Html::dropDownList('listGoods','',
                                    ArrayHelper::merge([''=>THelper::t('selecting_card')],$listPaymentCards),
                                    ['class'=>'form-control listCart']
                                )?>
                            </div>
                            <div class="col-md-2">
                                <?=Html::a('<i class="fa fa-plus"></i>','javascript:void(0);',['class'=>'btn btn-default btn-block addNewCard'])?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-5 col-sm-4 col-lg-7 col-xs-10 m-b">
                <div>
                    <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png' ; ?>" class="img-circle">
                </div>
                <div class="controls_edit">
                    <?= Html::a(THelper::t('change'), ['change-img', 'u' => $user->username], array('class' => 'btn btn-s-md btn-success', 'data-toggle' => 'ajaxModal')); ?>
                    <label for=""><?= THelper::t('200_200_pixels_in_the_formats') ?></label>
                </div>
            </div>
            <div class="col-md-7 col-sm-8 col-lg-5 col-xs-2 m-t m-b">
                <div>
                    <div class="form-group pull-right">
                        <label class="control-label switch-center"><?= THelper::t('show_my_phone') ?></label>
                        <label class="switch">
                            <input value="1" type="checkbox" name="showMobile" <?php if($user->settings->showMobile):?>checked<?php endif; ?>/>
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group pull-right">
                        <label class="control-label switch-center"><?= THelper::t('show_my_email') ?></label>
                        <label class="switch">
                            <input value="1" type="checkbox" name="showEmail" <?php if($user->settings->showEmail):?>checked<?php endif; ?>/>
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group pull-right">
                        <label  class="control-label switch-center"><?= THelper::t('show_my_name_to_the_structure') ?></label>
                        <label class="switch">
                            <input value="1" type="checkbox" name="showName" <?php if($user->settings->showName):?>checked<?php endif; ?>/>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div style="row">
            <div class="col-md-6">
                <div class="row m-b-sm">
                    <div class="col-sm-3 col-xs-4 m-b-sm"><img src="/images/whatsapp.png" /></div>
                    <div class="col-sm-3 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('whatsapp') ?></div>
                    <div class="col-sm-6 col-xs-12 m-b-sm p-t-20">
                        <?= $form->field($model, 'phoneWhatsApp')->textInput(['maxlength' => 16])->label(false) ?>
                    </div>
                </div>
                <div class="row m-b-sm">
                    <div class="col-sm-3 col-xs-4 m-b-sm"><img src="/images/viber.png" /></div>
                    <div class="col-sm-3 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('viber') ?></div>
                    <div class="col-sm-6 col-xs-12 m-b-sm p-t-20">
                        <?= $form->field($model, 'phoneViber')->textInput(['maxlength' => 16])->label(false) ?>
                    </div>
                </div>
                <div class="row m-b-sm">
                    <div class="col-sm-3 col-xs-4 m-b-sm"><img src="/images/telegram.png" /></div>
                    <div class="col-sm-3 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('telegram') ?></div>
                    <div class="col-sm-6 col-xs-12 p-t-20">
                        <?= $form->field($model, 'phoneTelegram')->textInput(['maxlength' => 16])->label(false) ?>
                    </div>
                </div>
                <div class="row m-b-md">
                    <div class="col-sm-3 col-xs-4 m-b-sm"><img src="/images/facebook.png" /></div>
                    <div class="col-sm-3 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('facebook') ?></div>
                    <div class="col-sm-6 col-xs-12 p-t-20">
                        <?= $form->field($model, 'phoneFB')->textInput(['maxlength' => 16])->label(false) ?>
                    </div>
                </div>
                <div class="row m-b-md">
                    <div class="col-md-12">
                        <?= $form->field($model, 'selectedLang')->dropDownList($languages)->label(THelper::t('what_is_your_language')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-t-20">
                <?= $form->field($model, 'notifyAboutJoinPartner')->checkbox(['label' => THelper::t('notify_about_join_partner')]); ?>
                <?= $form->field($model, 'notifyAboutReceiptsMoney')->checkbox(['label' => THelper::t('notify_about_receipts_money')]); ?>
                <?= $form->field($model, 'notifyAboutReceiptsPoints')->checkbox(['label' => THelper::t('notify_about_receipts_points')]); ?>
                <?= $form->field($model, 'notifyAboutEndActivity')->checkbox(['label' => THelper::t('notify_about_end_activity')]); ?>
                <?= $form->field($model, 'notifyAboutOtherNews')->checkbox(['label' => THelper::t('notify_about_other_news')]); ?>
            </div>
        </div>
        <section class="hbox stretch">
            <aside class="aside-xl b-r" id="note-list">
                <section class="vbox flex">
                    <header class="header clearfix">
                        <span class="pull-right m-t">
                            <button class="btn btn-dark btn-sm btn-icon" id="new-note" data-toggle="tooltip" data-placement="right" title="<?=THelper::t('new')?>">
                                <i class="fa fa-plus"></i>
                            </button>
                        </span>
                        <p class="h3"><?=THelper::t('notes')?><!--Заметки--></p>
                        <div class="input-group m-t-sm m-b-sm">
                            <span class="input-group-addon input-sm"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control input-sm" id="search-note" placeholder="<?=THelper::t('search')?>">
                        </div>
                    </header>
                    <section>
                        <div class="padder">
                            <ul id="note-items" class="list-group list-group-sp">
                                <?= $this->render('note', [
                                    'notes' => $notes,
                                    'user' => $user
                                ]); ?>
                            </ul>
                            <p class="text-center">&nbsp;</p>
                        </div>
                    </section>
                </section>
            </aside>
            <aside id="note-detail" class="op" style="visibility: hidden">
                <section class="vbox">
                    <header class="header bg-light lter bg-gradient b-b">
                        <p id="note-date"><?= THelper::t('added') ?> <span class="dat"></span></p>
                    </header>
                    <section class="bg-light lter">
                        <section class="hbox stretch">
                            <aside>
                                <section class="vbox b-b">
                                    <section class="paper">
                                        <textarea type="text" id="area" class="form-control scrollable" placeholder="<?=THelper::t('type_your_note_here')?>"></textarea>
                                    </section>
                                </section>
                            </aside>
                        </section>
                    </section>
                </section>
            </aside>
        </section>
    </div>
    <div class="row m-t m-b">
        <div class="col-md-12 text-center">
            <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
            <?= Html::submitButton(THelper::t('save'), array('class' => 'btn btn-s-md btn-success')); ?>
        </div>
    </div>
    <?php $form = ActiveForm::end(); ?>
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
