<?php


namespace app\modules\settings\controllers;
use yii\web\Controller;
use Yii;
use app\modules\settings\models\UploadForm;
use app\modules\bekofis\models\PageList;
use app\modules\settings\models\Localisation;
use yii\web\UploadedFile;

class LogoController extends Controller
{
    public function actionIndex()
    {
        $model = new UploadForm();

        $language = Localisation::find()->select('id')->where('prefix=:id', [':id' =>Yii::$app->language])->one();
        $logo_auth = PageList::find()->where('title=:more',[':more'=>'Logo for authorization'])->andWhere('language_id=:mor',[':mor'=> $language->id])->one();
        $logo_reg = PageList::find()->where('title=:more',[':more'=>'Logo for registration'])->andWhere('language_id=:mor',[':mor'=> $language->id])->one();
        $logo_admin = PageList::find()->where('title=:more',[':more'=>'Logo for admin'])->andWhere('language_id=:mor',[':mor'=> $language->id])->one();
        $logo_business = PageList::find()->where('title=:more',[':more'=>'Logo for business center'])->andWhere('language_id=:mor',[':mor'=> $language->id])->one();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file_reg = UploadedFile::getInstance($model, 'file_reg');
            $model->file_admin = UploadedFile::getInstance($model, 'file_admin');
            $model->file_business = UploadedFile::getInstance($model, 'file_business');


            if ($model->file) {

                $model->file->saveAs('images/' . $model->file->baseName . '.' . $model->file->extension);
                $logo_auth->description = '<img alt ="logo authorization" src= "/images/'.$model->file->baseName.'.'.$model->file->extension.'" width = "'.$_POST['UploadForm']['width'].'" height = "'.$_POST['UploadForm']['height'].'" />';
            }



            if ($model->file_reg) {

                $model->file_reg->saveAs('images/' . $model->file_reg->baseName . '.' . $model->file_reg->extension);
                $logo_reg->description = '<img alt ="logo registration" src= "/images/'.$model->file_reg->baseName.'.'.$model->file_reg->extension.'" width = "'.$_POST['UploadForm']['width_reg'].'" height = "'.$_POST['UploadForm']['height_reg'].'" />';
            }

            if ($model->file_admin) {

                $model->file_admin->saveAs('images/' . $model->file_admin->baseName . '.' . $model->file_admin->extension);
                $logo_admin->description = '<img alt ="logo admin" src= "/images/'.$model->file_admin->baseName.'.'.$model->file_admin->extension.'" width = "'.$_POST['UploadForm']['width_admin'].'" height = "'.$_POST['UploadForm']['height_admin'].'" />';
            }

            if ($model->file_business) {

                $model->file_business->saveAs('images/' . $model->file_business->baseName . '.' . $model->file_business->extension);
                $logo_business->description = '<img alt ="logo admin" src= "/images/'.$model->file_business->baseName.'.'.$model->file_business->extension.'" width = "'.$_POST['UploadForm']['width_business'].'" height = "'.$_POST['UploadForm']['height_business'].'" />';
            }


            $logo_auth->save();
            $logo_reg->save();
            $logo_admin->save();
            $logo_business->save();


        }
        return $this->render('index', ['model' => $model,
        'log_auth' => $logo_auth,
        'log_reg' => $logo_reg,
        'log_admin' => $logo_admin,
        'log_business' => $logo_business,
        ]);

    }

}