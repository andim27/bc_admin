<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use yii\web\UploadedFile;
use app\modules\business\models\UploadForm;
use app\models\api;
use app\components\THelper;

class UploadedController extends BaseController
{
    public function actionIndex()
    {
        $qtyDocsToLoading = api\Setting::get()->qtyDocsToLoading;

        $uploaded = false;
        if (Yii::$app->request->isPost) {
            for ($i = 1; $i <= $qtyDocsToLoading; $i++) {
                $uploadForm = new UploadForm();
                $uploadForm->{'file_' . $i} = UploadedFile::getInstance($uploadForm, 'file_' . $i);
                if ($uploadForm->{'file_' . $i}) {
                    $result = $uploadForm->upload($i, $this->user->id);
                    if (! $result) {
                        Yii::$app->getSession()->setFlash('errors', $uploadForm->getErrors());
                        return $this->redirect('/' . Yii::$app->language . '/business/uploaded');
                    } else {
                        $uploaded = true;
                        api\user\Doc::add($this->user->id, $result);
                    }
                }
            }

            if ($uploaded) {
                Yii::$app->getSession()->setFlash('success', THelper::t('files_was_added'));
                return $this->redirect('/' . Yii::$app->language . '/business/uploaded');
            }
        }

        return $this->render('index', [
            'qtyDocsToLoading' => $qtyDocsToLoading,
            'uploadForm' => new UploadForm(),
            'successText' => Yii::$app->getSession()->getFlash('success', '', true),
            'errorsText' => Yii::$app->getSession()->getFlash('errors', '', true)
        ]);
    }

    public function actionFind()
    {
        $docs = api\user\Doc::all($this->user->id);

        return json_encode($docs);
    }

    public function actionDelete()
    {
        $docId = Yii::$app->request->get('id');
        $path = Yii::$app->request->get('path');

        if ($docId && $path) {
            if (unlink($path)) {
                api\user\Doc::delete($docId);
            }
        }
    }

}