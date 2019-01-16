<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;

class ShowroomsController extends BaseController
{
    public function actionOpeningConditions()
    {
        return $this->render('opening-conditions', [

        ]);
    }

    public function actionRequestsOpen()
    {
        return $this->render('requests-open', [

        ]);
    }

    public function actionList()
    {
        return $this->render('list', [

        ]);
    }

    public function actionCompensationTable()
    {
        return $this->render('compensation-table', [

        ]);
    }

    public function actionChargeCompensation()
    {
        return $this->render('charge-compensation', [

        ]);
    }

    public function actionReceptionIssueGoods()
    {
        return $this->render('reception-issue-goods', [

        ]);
    }
}