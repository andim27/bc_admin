<?php
    use app\components\THelper;
    $this->title = THelper::t('status_history');
    $this->params['breadcrumbs'][] = $this->title;
?>

<section class="panel panel-default">
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="buy">
                <section class="panel panel-default">
                    <div class="table-responsive">
                        <table id="MyStretchGridHistory" class="table table-striped datagrid m-b-sm unique_table_class">
                            <thead>
                            <tr>
                                <th class="sortable">
                                    <?=THelper::t('qualifications')?><!--Квалификация-->
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('period_closing')?><!--Срок закрытия-->
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('steps')?><!--Шаги-->
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('youre_steps')?><!--Осталось шагов-->
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('achievement')?><!--Достижение-->
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('time_remaining')?><!--Осталось времени-->
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('the_award')?><!--Размер премии-->
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('payment_award')?><!--Оплата премии-->
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data)){foreach ($data as $value){ ?>
                                <tr class = "st" data-id="<?= $value['id'] ?>" id="st-<?= $value['id'] ?>">
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                </tr>

                            <?php }
                            }
                            ?>
                            </tbody>

                        </table>
                    </div>
                </section>
            </div>

        </div>

    </div>
</section>
<?php $this->registerJsFile('/js/main/initialization.js'); ?>