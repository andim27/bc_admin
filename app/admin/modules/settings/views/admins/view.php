<?php
use yii\helpers\Html;
?>

<td><?= Html::img($dir.$admins->avatar_img, array('width'=>80, 'height'=>80)); ?></td>
<td><?= $admins->login; ?></td>
<td><?= $admins->email; ?></td>
<td><?= $admins->second_name.' '.$admins->name.' '.$admins->middle_name; ?></td>
<td><?= $admins->mobile; ?></td>
<td><?= $admins->usersStatus->title; ?></td>
<td><?= $admins->country->title; ?>, <?= $admins->city->title; ?></td>
<td><?= $admins->localisation->title; ?></td>
<td><?= $admins->skype; ?></td>

<td><?= Html::a('<i class="fa fa-pencil"></i>', ['ajax'], ['onClick'=>'ajax_add_editform('.$admins->id.', '.$admins->country_id.'); return false;', 'class'=>'edit']); ?></td>