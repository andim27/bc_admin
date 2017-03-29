<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
class ErrorsController extends Controller
{

     public function init(){
         

     	$path = $_SERVER['DOCUMENT_ROOT'];

        //echo file_get_contents($path.'/404.php');
     	header('Location: /404.php');
        exit;
    }
}

?>