<?php

namespace app\modules\settings\controllers;
use app\modules\settings\models\locale;
use yii\web\Controller;
use app\modules\settings\controllers\ssp;
use Yii;

class DefaultController extends Controller
{
    public function actionIndex()
    {

     //   echo '<div style="position:fixed; background:#fff; z-index:9999;">';
      //  $model = new Locale();
      //  print_r($model->get_language_list());
      //  echo '</div>';
        return $this->render('locale');
    }
    public function actionGetLanguages()
    {
       $model = new Locale();
        $tmp =  $model->get_language_list();
     /*   $data['data'] = null;
        foreach($tmp as $value)
        {
            $row = null;
            $row[] = $value['id'];
            $row[] = $value['title'];
            $row[] = $value['prefix'];
            $data['data'][] = $row;
        }*/
      //  print_r($data);
        $data['data'] = $tmp;
        echo  \yii\helpers\Json::encode( $data);
    }
    public function actionHelloWorld()
    {
        return 'Hello World';
    }
}
