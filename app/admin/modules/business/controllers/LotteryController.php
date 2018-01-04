<?php

namespace app\modules\business\controllers;
use app\controllers\BaseController;
use Yii;
use app\models\api;
use app\components\THelper;

class LotteryController extends BaseController
{
    public function actionIndex()
    {
        $usersWithTokens = api\lottery\UserWithTokens::get();

        $allUsers = [];
        $keyAll = 0;
        foreach ($usersWithTokens as $userWithTokens) {
            $keyAll++;
            $allUsers[$keyAll] = ['id' => $userWithTokens->id, 'username' => $userWithTokens->username];
        }

        return $this->render('index', [
            'users' => json_encode($allUsers),
            'countAll' => $keyAll,
            'winners' => api\lottery\User::winnerList()
        ]);
    }

    public function actionRules()
    {
        $usersWithTokens = api\lottery\UserWithTokens::get();
        $winners = api\lottery\User::winnerList();
        $banned = api\lottery\User::bannedList();

        $ids = [];

        foreach ($winners as $w) {
            $ids[] = $w->userId;
        }

        foreach ($banned as $b) {
            $ids[] = $b->userId;
        }

        $users = [];

        foreach ($usersWithTokens as $userWithTokens) {
            if (! in_array($userWithTokens->id, $ids)) {
                $users[] = $userWithTokens;
            }
        }

        return $this->render('rules', [
            'winners' => $winners,
            'banned'  => $banned,
            'users'   => $users
        ]);
    }

    public function actionRemoveWinner()
    {
        $request = Yii::$app->request;

        $userId = $request->get('id');

        $result = api\lottery\User::winnerRemove($userId);

        if ($result) {
            Yii::$app->session->setFlash('success', THelper::t('lottery_rules_remove_winner_success'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('lottery_rules_remove_winner_error'));
        }

        $this->redirect('/' . Yii::$app->language . '/business/lottery/rules');
    }

    public function actionRemoveBanned()
    {
        $request = Yii::$app->request;

        $userId = $request->get('id');

        $result = api\lottery\User::bannedRemove($userId);

        if ($result) {
            Yii::$app->session->setFlash('success', THelper::t('lottery_rules_remove_banned_success'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('lottery_rules_remove_banned_error'));
        }

        $this->redirect('/' . Yii::$app->language . '/business/lottery/rules');
    }

    public function actionClearWinners()
    {
        $result = api\lottery\User::winnerClear();

        if ($result) {
            Yii::$app->session->setFlash('success', THelper::t('lottery_rules_winner_clear_banned_success'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('lottery_rules_winner_clear_banned_error'));
        }

        $this->redirect('/' . Yii::$app->language . '/business/lottery/rules');
    }

    public function actionClearBanned()
    {
        $result = api\lottery\User::bannedClear();

        if ($result) {
            Yii::$app->session->setFlash('success', THelper::t('lottery_rules_banned_clear_banned_success'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('lottery_rules_banned_clear_banned_error'));
        }

        $this->redirect('/' . Yii::$app->language . '/business/lottery/rules');
    }

    public function actionAddBanned()
    {
        $request = Yii::$app->request;

        $userId = $request->get('id');

        $result = api\lottery\User::bannedAdd($userId);

        if ($result) {
            Yii::$app->session->setFlash('success', THelper::t('lottery_rules_add_banned_success'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('lottery_rules_add_banned_error'));
        }

        $this->redirect('/' . Yii::$app->language . '/business/lottery/rules');
    }

    public function actionAddWinner()
    {
        $request = Yii::$app->request;

        $userId = $request->post('id');

        api\lottery\User::winnerAdd($userId);

        echo $this->renderAjax('_winners', [
            'winners' => api\lottery\User::winnerList()
        ]);
    }

    public function actionGetWinnable()
    {
        $usersWithTokens = api\lottery\UserWithTokens::get();
        $winners = api\lottery\User::winnerList();
        $banned = api\lottery\User::bannedList();

        $ids = [];
        foreach ($winners as $w) {
            $ids[] = $w->userId;
        }

        foreach ($banned as $b) {
            $ids[] = $b->userId;
        }

        $users = [];
        $key = 0;
        foreach ($usersWithTokens as $userWithTokens) {
            if (! in_array($userWithTokens->id, $ids)) {
                $key++;
                $users[$key] = ['id' => $userWithTokens->id, 'username' => $userWithTokens->username];
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return ['count' => $key, 'winnableUsers' => $users];
    }
}