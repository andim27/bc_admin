<?php
use app\models\Users;
use app\components\AlertWidget;

$userArray = Users::getListAdmin();
?>

<?= AlertWidget::widget($error) ?>

<?php if(!empty($infoWarehouse->idUsers)) { ?>
    <?php foreach($infoWarehouse->idUsers as $itemUser) { ?>
        <div class="input-group m-t-sm m-b-sm blItem">
            <span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>
            <input type="text" class="form-control input-sm" disabled="disabled" value="<?=$userArray[$itemUser]?>">
            <input type="hidden" name="idUsers[]" value="<?=$itemUser?>">
        </div>
    <?php } ?>
<?php } ?>
