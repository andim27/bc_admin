<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\modules\business\models\WellnessClubMembers;
use Yii;
use app\models\api;
use app\models\api\user\CareerHistory;
use yii\data\Pagination;
use yii\helpers\Html;

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
                ['like', 'state', $search],
                ['like', 'country', $search],
                ['like', 'city', $search],
                ['like', 'address', $search],
                ['like', 'mobile', $search],
                ['like', 'skype', $search],
            ]);
        }

        $pages = new Pagination(['totalCount' => $wellnessClubMembers->count()]);

        $columns = [
            'surname', 'name', 'middleName', 'countryId', 'state',
            'city', 'address', 'mobile', 'email', 'skype', 'created', 'action'
        ];

        $filterColumns = [
            'surname', 'name', 'middleName', 'countryId', 'state',
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
                $nestedData[$columns[3]] = $user->countryId;
                $nestedData[$columns[4]] = $user->state;
                $nestedData[$columns[5]] = $user->city;
                $nestedData[$columns[6]] = $user->address;
                $nestedData[$columns[7]] = $user->mobile;
                $nestedData[$columns[8]] = $user->email;
                $nestedData[$columns[9]] = $user->skype;
                $nestedData[$columns[10]] = is_integer($user->created) ? gmdate('d.m.Y', $user->created) : $user->created;
                $nestedData[$columns[11]] = Html::a('<i class="fa fa-pencil"></i>', ['/business/user', 'u' => $user->name]);

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
}