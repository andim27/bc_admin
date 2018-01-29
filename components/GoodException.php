<?php

namespace app\components;

use yii;
use yii\base\ExitException;

class GoodException extends ExitException
{
    public function __construct($name, $message = null, $code = 0, $status = 500, \Exception $previous = null)
    {
        # Генерируем ответ
        $view = yii::$app->getView();
        $response = yii::$app->getResponse();
        $response->data = $view->renderFile('@app/components/views/exception.php', [
            'name' => $name,
            'message' => $message,
        ]);

        # Возвратим нужный статус (по-умолчанию отдадим 500-й)
        $response->setStatusCode($status);

        parent::__construct($status, $message, $code, $previous);
    }
}