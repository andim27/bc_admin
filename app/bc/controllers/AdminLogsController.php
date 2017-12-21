<?php

namespace app\controllers;

use Yii;
use app\models\AdminLogList;

class AdminLogsController extends \yii\web\Controller
{
    public $savePathAlias = '@app/logs/adminlogs';

    public function actionIndex()
    {
		$model = new AdminLogList();
		$data = AdminLogList::find()
						->joinWith('users', true, 'INNER JOIN')
						->joinWith('logType', true, 'INNER JOIN')
						->orderBy(['id'=>SORT_DESC])
						->all();
//			echo '<pre>' . print_r($data, true) . '</pre>';exit;
        return $this->render('index',array('data'=>$data));
    }

	public function actionLog()
	{
		//echo '<pre>' . print_r($_POST, true) . '</pre>'; exit;
		/*
		if (!isset($_POST)) {
			return;
		}*/
/*		$inArray['page_id'] = $_GET['page_id'];
		$inArray['log_type_id'] = $_GET['log_type_id'];
		$inArray['changes'] = $_GET['changes'];
		$model = new AdminLogList();
		$model->insertLog($inArray);	*/
	}

    public function actionExport()
    {
        // проверка что админ

        // экспорт
        $model = new AdminLogList();
        $model = $model->find()
                        ->joinWith('users', true, 'INNER JOIN')
                        ->joinWith('logType', true, 'INNER JOIN')
                        ->all();
        $output = '';
        foreach($model as $adminLog) {
            $output .= $adminLog->users['login'] . ' ' . $adminLog->logType['title'] . ' '
                        . $adminLog->changes . ' ' . 'http://' . $_SERVER['HTTP_HOST']
                        . $adminLog->page_id . ' ' . $adminLog->data . "\r\n";
        }
        $filepath = Yii::getAlias($this->savePathAlias) . '/logs_' . date('Y_m_d_H_i_s') . '.log';
        $file = fopen($filepath, 'w');
        $res = fwrite($file, $output);
        if ($res) {
            Yii::$app->response->sendFile($filepath);
        }
    }

    public function actionDelete()
    {
        // проверка что админ

        // удаление
        AdminLogList::deleteAll('1=1');
        $this->redirect(['index']);
    }

}
