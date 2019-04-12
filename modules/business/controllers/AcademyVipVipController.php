<?php namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\AcademyVipVip;
use app\models\AcademyVipVipUser;
use app\models\WellnessClubMembersInfoForm;
use Yii;
use yii\helpers\ArrayHelper;

class AcademyVipVipController extends BaseController
{
    public function actionIndex()
    {
        $request = Yii::$app->request;

        if ($currentTab = $request->get('t')) {
        } else {
            $currentTab = 'users';
        }

        $requestLanguage = $request->get('l');

        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = \app\models\api\dictionary\Lang::supported();

        $academyVipVipInfo = AcademyVipVip::find()->where(['language' => $language, 'type' => 'info'])->one();

        return $this->render('index', [
            'language' => Yii::$app->language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
            'academyVipVipInfo' => $academyVipVipInfo,
            'currentTab' => $currentTab,
            'selectedLanguage' => $language,
            'academyVipVipUsers' => AcademyVipVipUser::find()->all()
        ]);
    }

    public function actionAddInfo()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $language = $request->post('language');

            if (!$academyVipVipInfo = AcademyVipVip::find()->where(['language' => $language, 'type' => 'info'])->one()) {
                $academyVipVipInfo = new AcademyVipVip();
                $academyVipVipInfo->language = $language;
                $academyVipVipInfo->type = 'info';
            }

            $academyVipVipInfo->body = $request->post('body');

            if ($result = $academyVipVipInfo->save()) {
                Yii::$app->session->setFlash('success', 'academy_vipvip_info_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'academy_vipvip_info_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/academy-vip-vip?l=' . $language . '&t=info');
        }
    }

}