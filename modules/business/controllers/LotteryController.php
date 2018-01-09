<?php

namespace app\modules\business\controllers;
use app\controllers\BaseController;
use app\models\LotteryBannedUser;
use app\models\LotteryTicket;
use app\models\LotteryWinnerTicket;
use app\models\Users;
use MongoDB\BSON\ObjectID;
use Yii;
use yii\web\Response;

class LotteryController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'winners' => LotteryWinnerTicket::find()->all()
        ]);
    }

    public function actionRules()
    {
        $lotteryTickets = LotteryTicket::find()->all();
        $winners = LotteryWinnerTicket::find()->all();
        $banned = LotteryBannedUser::find()->all();

        $users = [];
        foreach ($lotteryTickets as $lotteryTicket) {
            $lotteryTicketUser = $lotteryTicket->user();
            if (!$lotteryTicketUser) {
                hh($lotteryTicket);
            }
            $userId = strval($lotteryTicketUser->_id);
            if (!isset($users[$userId])) {
                $users[$userId] = [
                    'username' => $lotteryTicketUser->username,
                    'firstName' => $lotteryTicketUser->firstName,
                    'secondName' => $lotteryTicketUser->secondName,
                    'countryName' => $lotteryTicketUser->getCountry(),
                    'city' => $lotteryTicketUser->city,
                    'tickets' => [$lotteryTicket->ticket]
                ];
            } else {
                $users[$userId]['tickets'][] = $lotteryTicket->ticket;
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        LotteryWinnerTicket::deleteAll(['_id' => new ObjectID($id)]);

        $result = ['success' => true];

        return $result;
    }

    public function actionRemoveBanned()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = Yii::$app->request->post('user-id');

        LotteryBannedUser::deleteAll(['userId' => new ObjectID($userId)]);

        $result = ['success' => true];

        return $result;
    }

    public function actionClearWinners()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        LotteryWinnerTicket::deleteAll();

        $result = ['success' => true];

        return $result;
    }

    public function actionClearBanned()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        LotteryBannedUser::deleteAll();

        $result = ['success' => true];

        return $result;
    }

    public function actionAddBanned()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userLogin = Yii::$app->request->post('user-login');

        $user = Users::find()->where(['username' => $userLogin])->select(['_id'])->one();

        if ($user) {
            if (LotteryBannedUser::find()->where(['userId' => $user->_id])->one()) {
                $result = ['success' => false, 'error' => 'User already banned'];
            } else {
                $lbu = new LotteryBannedUser();
                $lbu->userId = $user->_id;
                if ($lbu->save()) {
                    $result = ['success' => true];
                } else {
                    $result = ['success' => false, 'error' => 'Error'];
                }
            }
        } else {
            $result = ['success' => false, 'error' => 'User not found'];
        }

        return $result;
    }

    public function actionGetBanned()
    {
        return $this->renderAjax('_banned', [
            'banned'  => LotteryBannedUser::find()->all()
        ]);
    }

    public function actionGetWinners()
    {
        $forAdmin = Yii::$app->request->post('for-admin', false);

        $viewName = $forAdmin ? '_winners_admin' : '_winners';

        return $this->renderAjax($viewName, [
            'winners'  => LotteryWinnerTicket::find()->all()
        ]);
    }

    public function actionGetTickets()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $winners = LotteryWinnerTicket::find()->all();

        $winnerIds = [];
        foreach ($winners as $winner) {
            $winnerIds[] = new ObjectID(strval($winner->ticketId));
        }

        $bannedUsers = LotteryBannedUser::find()->all();

        $bannedUserIds = [];
        foreach($bannedUsers as $bannedUser) {
            $bannedUserIds[] = new ObjectId(strval($bannedUser->userId));
        }

        $tickets = LotteryTicket::find()->where([
            'userId' => ['$nin' => $bannedUserIds],
            '_id' => ['$nin' => $winnerIds]
        ])->select(['ticket'])->limit(500)->all();

        shuffle($tickets);

        $result = [];
        foreach ($tickets as $ticket) {
            $result[] = ['id' => strval($ticket->_id), 'ticket' => strval($ticket->ticket)];
        }

        return $result;
    }

    public function actionAddWinner()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $ticketId = Yii::$app->request->post('id');

        if (LotteryTicket::find()->where(['_id' => $ticketId])->one()) {
            $lwt = new LotteryWinnerTicket();
            $lwt->ticketId = new ObjectID($ticketId);
            if ($lwt->save()) {
                $result = ['success' => true];
            } else {
                $result = ['success' => false, 'error' => 'Error'];
            }
        } else {
            $result = ['success' => false, 'error' => 'Lottery ticket not found'];
        }

        return $result;
    }
}