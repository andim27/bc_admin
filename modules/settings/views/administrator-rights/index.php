<?php

use yii\widgets\ActiveForm;
use app\components\THelper;

$this->title = THelper::t('administrator_rights');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-group" xmlns="http://www.w3.org/1999/html">
    <label class="col-sm-2 control-label"><?=THelper::t('administrator')?><!--Администратор--></label>
    <div class="col-sm-10">
        <?php $form = ActiveForm::begin(['options' => ['method' => 'post']]); ?>
        <select name="account" id="account" class="form-control m-b" onchange="submit();">
            <?php if(!empty($admin_list)){
                foreach($admin_list as $admin){?>
                    <option value="<?=$admin->id ?>" <?= (isset($_POST['account']) && $_POST['account'] == $admin->id)?'selected="selected"':'' ?> ><?php echo $admin->name." ".$admin->second_name." ".$admin->email; ?></option>
            <?php
                }
            }
            else{
                echo "<option value='-1'>".THelper::t('no_administrators')."<!--Нет администраторов--></option>";
            }
            ?>
        </select>
        <?php ActiveForm::end(); ?>
        <div class="col-md-6 m-l-n">
            <lable class="pull-left"><?=THelper::t('can_view')?><!--Просмотр--></lable>
            <span class="pull-right"><button class="select_all"><?=THelper::t('distinguish')?><!--выделить--></button>/<button class="remove_all"><?=THelper::t('remove_all')?><!--снять все--></button></span>
            <select multiple class="form-control" id="viewing">
                <?php if(!empty($title_page)){
                    if(!empty($user_rights)) {
                        $veiw = explode(",", $user_rights->viewing);
                    }
                    else{
                        $veiw= array();
                    }
                    foreach($title_page as $title){
                        ?>
                            <option value="<?=$title->id?>" <?= (in_array($title->id, $veiw))? 'selected="selected"':'' ?>><?=THelper::t($title->title)?></option>
                        <?php
                    }
                }
                else{
                    echo "<option>".THelper::t('there_are_no_pages')."<!--Нет страниц--></option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6 m-l-n">
            <lable class="pull-left"><?=THelper::t('can_edit')?><!--Редактирование--></lable>
            <span class="pull-right"><button class="select_all"><?=THelper::t('distinguish')?><!--выделить--></button>/<button class="remove_all"><?=THelper::t('remove_all')?><!--снять все--></button></span>
            <select multiple class="form-control" id="editing">
                <?php if(!empty($title_page)){
                        if(!empty($user_rights)){
                            $veiw = explode(",", $user_rights->editing);
                        }
                        else{
                            $veiw= array();
                        }
                        foreach($title_page as $title){
                            ?>
                            <option value="<?=$title->id?>" <?= (in_array($title->id, $veiw))? 'selected="selected"':'' ?>><?=THelper::t($title->title)?></option>
                            <?php
                        }
                }
                else{
                    echo "<option>".THelper::t('there_are_no_pages')."<!--Нет страниц--></option>";
                }
                ?>
            </select>
        </div>
    </div>
</div>

<?php $this->registerJsFile('/js/main/rights.js',['depends'=>['app\assets\AppAsset']]); ?>