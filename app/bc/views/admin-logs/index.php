<?php
use app\components\THelper;

$this->title = THelper::t('logs_action');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 

use yii\helpers\Html;
define("ROOT_T", "http://" . $_SERVER['SERVER_NAME']);
?>
<link rel="stylesheet" href="<?=ROOT_T?>/js/datatables/datatables.css" type="text/css">
<section class="panel panel-default">
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="log_list">
                <section class="panel panel-default">
                    <header class="panel-heading">
                         <?=THelper::t('logs_admin')?><!--Логи админа-->
                        <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                    </header>
                    <div class="table-responsive">
                        <?php echo HTML::a(THelper::t('export'), 'admin-logs/export', ['class'=>'btn btn-primary']); ?>
                        <?php echo HTML::a(THelper::t('delete'), 'admin-logs/delete', ['class'=>'btn btn-danger']); ?>
						<table id="adminlogs_table" class="table table-striped unique_table_class" data-ride="datatables2">
                            <thead>
                            <tr>
                                <th width="10%"><?=THelper::t('user')?><!--Пользователь--></th>
                                <th width="10%"><?=THelper::t('type')?><!--Тип--></th>
                                <th width="30%"><?=THelper::t('whats_changed')?><!--Что изменено--></th>
                                <th width="30%"><?=THelper::t('page')?><!--Страница--></th>
                                <th width="10%"><?=THelper::t('time')?><!--Время--></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($data as $key => $value){ ?>
                                <tr>
				<?php // echo '<pre>' . print_r($value, true) . '</pre>'; ?>
									<th width="10%"><?=$value->users['login'];?></th>
									<th width="10%"><?=$value->logType['title'];?></th>
									<th width="30%"><?=$value->changes;?></th>
									<th width="30%"><?=$value->page_id;?></th>
									<th width="10%"><?=$value['data'];?></th>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>

</section>



