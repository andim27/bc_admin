<?php
    use app\components\THelper;
    use yii\helpers\Html;
    $this->title = strtoupper(THelper::t('company_name'));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="bg-dark">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript">
         TRANSLATION = {
            "processing": "���������...",
            "search": "�����: ",
            "lengthMenu": "�������� _MENU_ �������",
            "info": "������ � _START_ �� _END_ �� _TOTAL_ �������",
            "infoEmpty": "������ � 0 �� 0 �� 0 �������",
            "infoFiltered": "(������������� �� _MAX_ �������)",
            "infoPostFix": "",
            "loadingRecords": "�������� �������...",
            "zeroRecords": "������ �����������.",
            "emptyTable": "� ������� ����������� ������",
            "paginate": {
                "first": "������",
                "previous": "����������",
                "next": "���������",
                "last": "���������"
            },
            "aria": {
                "sortAscending": ": ������������ ��� ���������� ������� �� �����������",
                "sortDescending": ": ������������ ��� ���������� ������� �� ��������"
            }
        };
        LANG='<?=Yii::$app->language?>';
    </script>
</head>
<body>
<?php $this->beginBody() ?>


<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


