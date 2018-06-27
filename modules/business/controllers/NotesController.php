<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use app\models\api;
use app\components\THelper;

class NotesController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'notes' => api\Note::all($this->user->id),
            'user' => $this->user
        ]);
    }

    public function actionShowNote()
    {
        $noteId = Yii::$app->request->get('id');

        return json_encode(api\Note::get($noteId));
    }

    public function actionRemoveNote()
    {
        $noteId = Yii::$app->request->get('id');

        api\Note::delete($noteId);
    }

    public function actionAddNote()
    {
        $defaultText = THelper::t('new_note');

        return $this->renderPartial('note', [
            'notes' => [api\Note::add($this->user->id, $defaultText, $defaultText)],
            'user' => $this->user
        ]);
    }

    public function actionUpdateNote()
    {
        $noteId = Yii::$app->request->get('id');
        $title = $this->_getTitle(strip_tags(Yii::$app->request->get('title')), 30);
        $body = strip_tags(Yii::$app->request->get('body'));

        if ($noteId && $body) {
            return json_encode(api\Note::update($noteId, $title, $body));
        }
    }

    public function _getTitle($text, $length)
    {
        $text = strip_tags($text);
        if (mb_strlen($text, 'UTF-8') > $length) {
            $pos = mb_strpos($text, ' ', $length, 'UTF-8');
            $text = mb_substr($text, 0, $pos, 'UTF-8');
            return $text;
        } else {
            return $text;
        }
    }

}