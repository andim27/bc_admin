<?php
namespace app\components;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use app\modules\users\models;
use app\models\AdminLogList;
use yii\helpers\Url;

class AdminlogBehavior extends Behavior
{
    // ...

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterDelete($event)
    {
		self::addLogItem(3);
    }
	
    public function afterSave($event)
    {
		self::addLogItem(1);
    }
	
    public function afterUpdate($event)
    {
		self::addLogItem(2);
    }
	
	public function addLogItem($log_type){		
			/*
				$fp = fopen("debug.txt", "a"); // Открываем файл в режиме записи
				$mytext = '<pre>' . print_r($this, true) . '</pre>'; // Исходная строка
				$test = fwrite($fp, $mytext); // Запись в файл
				fclose($fp); //Закрытие файла
			
				echo '<pre>' . print_r($this, true) . '</pre>'; exit;
				*/
		$model = new AdminLogList();
		$model->uid = 1; //$this->owner->attributes["id"];
		$model->log_type_id = $log_type;
		$model->page_id = Url::to('');
		$model->data =  date("Y-m-d H:i:s"); //"Изменение пользователя " . $this->owner->attributes['login'];

		$model->save();
		/*if(!$model->save())
		{
			var_dump($model->getErrors());
		}*/
		//Yii::$app->response->redirect($_SERVER['HTTP_REFERER']);
		
		
		//echo '<pre>' . print_r($this->owner, true) . '</pre>';exit;
		//AdminLogList::addLogItem();
	}
	
}