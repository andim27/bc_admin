<?php
namespace app\modules\settings\controllers;

use Yii;
use yii\web\Controller;
use app\components\THelper;
use app\modules\settings\models\Code;
use app\modules\settings\models\CodeValue;

class GeneratorController extends Controller
{
    public function actionIndex()
    {
        $model = new Code();

        if($model->load(Yii::$app->request->post())){
            $model->created_at = strtotime("now");
            $amount = $_POST['Code']['number'];
            $model->save();
            for ($i = 0; $i < $amount; $i++){
                $code = new CodeValue();
                $code->code_id = $model->id;
                $code->code_value = md5(strtotime('now').rand(1000, 9999));
                $code->save();
            }
            Yii::$app->session->setFlash('success', THelper::t('the_keys_have_already_generated'));
            return $this->refresh();
        }

        $data = Code::find()->orderBy(['created_at' => SORT_DESC])->all();

        return $this->render('index', [
            'model' => $model,
            'data' => $data
        ]);
    }

    public function actionSaveFile($id)
    {
        $model = CodeValue::find()->where(['code_id' => $id])->all();
        $arr = array();
        $count = 1;
        foreach ($model as $mod){
            $arr[$count]['key'] = $mod->code_value;
            $arr[$count]['is_used'] = $mod->is_used;
            $count++;
        }
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename=keys.csv");
        header("Content-Transfer-Encoding: binary");

        if (count($arr) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($arr)));
        foreach ($arr as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();

    }


}