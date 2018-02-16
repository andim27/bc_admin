<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\api\dictionary\Country;
use app\modules\business\models\WellnessClubMembers;
use DateTime;
use MongoDate;
use Yii;
use yii\data\Pagination;


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
                ['like', 'middleName', $search],
                ['like', 'countryId', $search],
                ['like', 'country', $search],
                ['like', 'city', $search],
                ['like', 'address', $search],
                ['like', 'mobile', $search],
                ['like', 'skype', $search],
            ]);
        }

        $pages = new Pagination(['totalCount' => $wellnessClubMembers->count()]);

        $columns = [
            'surname', 'name', 'middleName', 'countryId',
            'city', 'address', 'mobile', 'email', 'skype', 'created', 'action'
        ];

        $filterColumns = [
            'surname', 'name', 'middleName', 'countryId',
            'city', 'address', 'mobile', 'email', 'skype'
        ];

        if ($order = $request->get('order')[0]) {
            $wellnessClubMembers->orderBy([$filterColumns[$order['column']] => ($order['dir'] === 'asc' ? SORT_ASC : SORT_DESC)]);
        }

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = [];

            $wellnessClubMembers = $wellnessClubMembers
                ->offset($request->get('start') ?: $pages->offset)
                ->limit($request->get('length') ?: $pages->limit);

            $count = $wellnessClubMembers->count();

            foreach ($wellnessClubMembers->all() as $key => $user){
                $nestedData = [];

                $nestedData[$columns[0]] = $user->surname;
                $nestedData[$columns[1]] = $user->name;
                $nestedData[$columns[2]] = $user->middleName;
                $nestedData[$columns[3]] = !empty(Country::get($user->country)->name) ? Country::get($user->country)->name : '';
                $nestedData[$columns[4]] = $user->city;
                $nestedData[$columns[5]] = $user->street . ' ' . $user->apartment;
                $nestedData[$columns[6]] = $user->mobile;
                $nestedData[$columns[7]] = $user->email;
                $nestedData[$columns[8]] = $user->skype;
                $nestedData[$columns[9]] = $user->wellness_club_partner_date_end;
                $nestedData[$columns[10]] = '<button class="btn btn-success ' . ($user->wellness_club_partner_date_end ? '' : 'apply'). '"' .(!! $user->wellness_club_partner_date_end ? 'disabled' : '' ).' data-email="'. $user->email.'">' . THelper::t('apply') . '</button>';

                $data[] = $nestedData;
            }

            return [
                'draw' => $request->get('draw'),
                'data' => $data,
                'recordsTotal' => $count,
                'recordsFiltered' => $count
            ];
        }

        return $this->render('index', []);
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