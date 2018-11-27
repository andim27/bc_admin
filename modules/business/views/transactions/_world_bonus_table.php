<?php
use app\components\THelper;
?>
<table class="table table-world-bonus table-striped datagrid m-b-sm">
    <thead>
    <tr>
        <th>Логин</th>
        <th>ФИО</th>
        <th>Статус</th>
        <th>Сумма</th>
        <th>Месяц</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($worldBonuses as $worldBonus) {
        $month = $worldBonus->month;
        $month = mb_strlen(strval($month)) == 1 ? '0' . $month : $month; ?>
        <tr>
            <td><?= $worldBonus->user->login ?></td>
            <td><?= trim($worldBonus->user->firstName . ' ' . $worldBonus->user->secondName) ?></td>
            <td><?= THelper::t('rank_' . $worldBonus->careerRank) ?></td>
            <td><?= $worldBonus->amount ?></td>
            <td><?= $month . '.' . $worldBonus->year ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<script>
    $('.table-world-bonus').dataTable({
        language: TRANSLATION,
        order: [[ 4, 'desc' ], [0, 'asc']]
    });
</script>