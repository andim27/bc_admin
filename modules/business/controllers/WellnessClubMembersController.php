<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api\dictionary\Country;
use app\models\api\Lang;
use app\models\WellnessClubInfo;
use app\models\WellnessClubMembersInfoForm;
use app\models\WellnessClubVideo;
use app\modules\business\models\WellnessClubMembers;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;


class WellnessClubMembersController extends BaseController
{
    public function actionIndex()
    {
        $request = Yii::$app->request;

        $wellnessClubMembers = wellnessClubMembers::find();

        if ($search = $request->get('search')['value']) {

            // @todo filter
            $wellnessClubMembers->andFilterWhere(['or',
                ['=', 'email', $search],
                ['like', 'surname', $search],
                ['like', 'name', explode(' ', $search)[0]],
                ['like', 'countryId', $search],
                ['like', 'country', $search],
                ['like', 'address', $search],
                ['like', 'mobile', $search],
                ['like', 'skype', $search],
            ]);
        }

        $pages = new Pagination(['totalCount' => $wellnessClubMembers->count()]);

        $columns = [
            'surname', 'name', 'countryId',
            'address', 'mobile', 'email', 'skype', 'created', 'action'
        ];

        $filterColumns = [
            'updated_at', 'surname', 'name', 'countryId',
            'address', 'mobile', 'email', 'skype'
        ];

        if ($order = $request->get('order')[0]) {
            $wellnessClubMembers->orderBy([$filterColumns[$order['column']] => ($order['dir'] === 'asc' ? SORT_ASC : SORT_DESC)]);
        }

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = [];

            $offset = $request->get('start') ?: $pages->offset;
            $wellnessClubMembers = $wellnessClubMembers
                ->offset($offset)
                ->limit($request->get('length') ?: $pages->limit);

            $count = $wellnessClubMembers->count();

            foreach ($wellnessClubMembers->all() as $key => $user){
                $nestedData = [];

                $address = (!empty($user->state) ? $user->state : '')
                    . ' ' .
                    (!empty($user->city) ? $user->city : '')
                    . ' ' .
                    (!empty($user->street) ? $user->street : '')
                    . ' ' .
                    (!empty($user->apartment) ? $user->apartment : '');

                if (!trim($address)) {
                    $address = $user->address;
                }

                $nestedData['id'] = $count - ($key + $offset);
                $nestedData[$columns[0]] = $user->surname;
                $nestedData[$columns[1]] = $user->name;
                $nestedData[$columns[2]] = !empty(Country::get($user->country)->name) ? Country::get($user->country)->name : '';
                $nestedData[$columns[3]] = $address;
                $nestedData[$columns[4]] = $user->phone;
                $nestedData[$columns[5]] = $user->email;
                $nestedData[$columns[6]] = $user->skype;
                $nestedData[$columns[7]] = $user->wellness_club_partner_date_end;
                $nestedData[$columns[8]] = '<button class="btn btn-success ' . ($user->wellness_club_partner_date_end ? '' : 'apply'). '"' .(!! $user->wellness_club_partner_date_end ? 'disabled' : '' ).' data-email="'. $user->email.'">' . THelper::t('accepted') . '</button>';

                $data[] = $nestedData;
            }

            return [
                'draw' => $request->get('draw'),
                'data' => $data,
                'recordsTotal' => $count,
                'recordsFiltered' => $count
            ];
        }

        if ($currentTab = $request->get('t')) {
        } else {
            $currentTab = 'members';
        }

        $requestLanguage = $request->get('l');

        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = \app\models\api\dictionary\Lang::all();

        $wellnessClubInfo = WellnessClubInfo::find()->where(['language' => $language])->one();
        $wellnessClubVideo = WellnessClubVideo::find()->where(['language' => $language])->one();

        return $this->render('index', [
            'language' => Yii::$app->language,
            'selectedLanguage' => $language,
            'body' => $wellnessClubInfo ? $wellnessClubInfo->body : '',
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
            'currentTab' => $currentTab,
            'videoUrl' => $wellnessClubVideo ? $wellnessClubVideo->url : '',
        ]);
    }

    public function actionAddInfo()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $language = $request->post('language');

            if (!$wellnessClubInfo = WellnessClubInfo::find()->where(['language' => $language])->one()) {
                $wellnessClubInfo = new WellnessClubInfo();
                $wellnessClubInfo->language = $language;
            }

            $wellnessClubInfo->body = $request->post('body');

            if ($result = $wellnessClubInfo->save()) {
                Yii::$app->session->setFlash('success', 'wellness_club_info_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'wellness_club_info_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/wellness-club-members?l=' . $wellnessClubInfo->language . '&t=info');
        }
    }

    public function actionAddVideo()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $language = $request->post('language');

            if (!$wellnessClubVideo = WellnessClubVideo::find()->where(['language' => $language])->one()) {
                $wellnessClubVideo = new WellnessClubVideo();
                $wellnessClubVideo->language = $language;
            }

            $wellnessClubVideo->url = $request->post('url');

            if ($result = $wellnessClubVideo->save()) {
                Yii::$app->session->setFlash('success', 'wellness_club_video_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'wellness_club_video_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/wellness-club-members?l=' . $wellnessClubVideo->language . '&t=video');
        }
    }

    /**
     * @return bool
     */
    public function actionApply()
    {
        $request = Yii::$app->request->post();

        $wellnessClubMember = wellnessClubMembers::find()->where(['email' => $request['email']])->one();

        $wellnessClubMember->wellness_club_partner_date_end = date('d/m/Y h:m:i', strtotime('+1 year',  time()));

        return $wellnessClubMember->save();
    }
}