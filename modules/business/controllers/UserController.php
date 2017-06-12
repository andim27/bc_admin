<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\MoneyTransfer;
use app\models\Sales;
use app\models\Transaction;
use app\models\Users;
use app\modules\business\models\ProfileForm;
use app\modules\business\models\PurchaseForm;
use Yii;
use app\models\api;
use app\modules\business\models\UsersReferrals;
use app\models\User;
use yii\base\Object;
use yii\web\Response;
use yii\helpers\Html;
use app\components\THelper;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;

class UserController extends BaseController
{
    const LIMIT = 500;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $model = new ProfileForm();

        if ($request->isGet) {
            if ($request->get('u')) {
                $user = api\User::get($request->get('u'));
            }

            if (isset($user)) {
                $model->id                          = $user->id;
                $model->name                        = $user->firstName;
                $model->surname                     = $user->secondName;
                $model->email                       = $user->email;
                $model->mobile                      = $user->phoneNumber;
                $model->smobile                     = $user->phoneNumber2;
                $model->skype                       = $user->skype;
                $model->state                       = $user->state;
                $model->city                        = $user->city;
                $model->address                     = $user->address;
                $model->site                        = $user->links->site;
                $model->odnoklassniki               = $user->links->odnoklassniki;
                $model->vk                          = $user->links->vk;
                $model->fb                          = $user->links->fb;
                $model->youtube                     = $user->links->youtube;
                $model->login                       = $user->username;
                $model->notifyAboutJoinPartner      = $user->settings->notifyAboutJoinPartner;
                $model->notifyAboutReceiptsMoney    = $user->settings->notifyAboutReceiptsMoney;
                $model->notifyAboutReceiptsPoints   = $user->settings->notifyAboutReceiptsPoints;
                $model->notifyAboutEndActivity      = $user->settings->notifyAboutEndActivity;
                $model->notifyAboutOtherNews        = $user->settings->notifyAboutOtherNews;
                $model->phoneTelegram               = $user->settings->phoneTelegram;
                $model->phoneViber                  = $user->settings->phoneViber;
                $model->phoneWhatsApp               = $user->settings->phoneWhatsApp;
                $model->phoneFB                     = $user->settings->phoneFB;
                $model->selectedLang                = $user->settings->selectedLang;
                $model->cards                       = (!empty($user->cards) ? ArrayHelper::toArray($user->cards) : '');

                return $this->render('profile', [
                    'user' => $user,
                    'model' => $model,
                    'countries' => api\dictionary\Country::all(),
                    'languages' => ArrayHelper::map(api\dictionary\Lang::supported(), 'alpha2', 'native'),
                    'notes' => api\Note::all($user->id),
                ]);
            } else {
                return $this->render('index', [
                    'users' => api\User::getList()
                ]);
            }
        } else if ($request->isPost) {
            if ($model->load($request->post())) {

                $user = api\User::get($model->id);


                $phoneTelegram = str_replace('+', '', trim($model->phoneTelegram));
                $phoneTelegram = $phoneTelegram ? '+' . $phoneTelegram : '';
                $phoneViber    = str_replace('+', '', trim($model->phoneViber));
                $phoneViber    = $phoneViber ? '+' . $phoneViber : '';
                $phoneWhatsApp = str_replace('+', '', trim($model->phoneWhatsApp));
                $phoneWhatsApp = $phoneWhatsApp ? '+' . $phoneWhatsApp : '';
                $phoneFB       = str_replace('+', '', trim($model->phoneFB));
                $phoneFB       = $phoneFB ? '+' . $phoneFB : '';

                $data = [
                    'username'                  => strtolower($model->login),
                    'fname'                     => $model->name,
                    'sname'                     => $model->surname,
                    'email'                     => $model->email,
                    'skype'                     => $model->skype,
                    'phone'                     => $model->mobile,
                    'phone2'                    => $model->smobile,
                    'country'                   => $request->post('country'),
                    'address'                   => $model->address,
                    'city'                      => $model->city,
                    'state'                     => $model->state,
                    'showMobile'                => $request->post('showMobile', 0),
                    'showEmail'                 => $request->post('showEmail', 0),
                    'showName'                  => $request->post('showName', 0),
                    'site'                      => $model->site,
                    'vk'                        => $model->vk,
                    'fb'                        => $model->fb,
                    'odnoklassniki'             => $model->odnoklassniki,
                    'youtube'                   => $model->youtube,
                    'notifyAboutJoinPartner'    => $model->notifyAboutJoinPartner,
                    'notifyAboutReceiptsMoney'  => $model->notifyAboutReceiptsMoney,
                    'notifyAboutReceiptsPoints' => $model->notifyAboutReceiptsPoints,
                    'notifyAboutEndActivity'    => $model->notifyAboutEndActivity,
                    'notifyAboutOtherNews'      => $model->notifyAboutOtherNews,
                    'phoneTelegram'             => $phoneTelegram,
                    'phoneViber'                => $phoneViber,
                    'phoneWhatsApp'             => $phoneWhatsApp,
                    'phoneFB'                   => $phoneFB,
                    'selectedLang'              => $model->selectedLang,

                    'cards'                     => $model->cards
                ];

                if ($user) {
                    $result = api\User::update($user->accountId, $data);
                    if ($result) {
                        Yii::$app->session->setFlash('success', 'user_profile_update_success');
                    } else {
                        Yii::$app->session->setFlash('danger', 'user_profile_update_error');
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'user_profile_user_error');
                }
            } else {
                Yii::$app->session->setFlash('danger', 'user_profile_load_data_error');
            }
        }

        $this->redirect('/' . Yii::$app->language . '/business/user?u=' . $user->username);
    }

    public function actionQualification()
    {
        return $this->render('qualification', [
            'users' => api\User::getQualification()
        ]);
    }

    public function actionLoad()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result = [];
            $users = api\User::users(Yii::$app->request->get('offset'), self::LIMIT);
            if ($users) {
                foreach ($users as $user) {
                    $result[] = [
                        $user->accountId,
                        $user->username,
                        gmdate('d.m.Y', $user->created),
                        'В структуре / Удален',
                        $user->firstName . ' ' . $user->secondName,
                        THelper::t('rank_' . $user->rank),
                        $user->getCountryCityAsString(),
                        $user->sponsor ? $user->sponsor->username : '',
                        $user->sponsor ? $user->sponsor->firstName : '' . ' ' . $user->sponsor ? $user->sponsor->secondName : '',
                        Html::a('<i class="fa fa-pencil"></i>', ['/business/user', 'u' => $user->username])
                    ];
                }
            }

            return $result;
        }
    }

    /**
     * @return string
     */
    public function actionGenealogy()
    {
        return $this->render('genealogy', [
            'model' => $this->user
        ]);
    }

    public function actionBuildTree()
    {
        if (preg_match("#[^\d]+#", Yii::$app->request->get("id"))) {
            $uid = Yii::$app->request->get("id");
        } else {
            if ($user_top = User::getInfoApi(Yii::$app->request->get("id"))) {
                $uid = $user_top->_id;
            }
        }

        if (is_numeric(Yii::$app->request->get("side", NULL))) {
            $last_side = UsersReferrals::getLastSideApi($uid, Yii::$app->request->get("side"));
            $uid = $last_side->_id;
        }

        if ($models = api\User::spilover($uid, 4)) {
            if ($models[0]->accountId == $this->user->accountId) {
                $models[0]->parentId = "000000000000000000000000";
            }
            $current_user_model = $models[0];
            Yii::$app->params['number'] = 0;
            return $this->renderAjax('ajax_structure_tree', [
                'current_user_model' => $current_user_model,
                'tree' => UsersReferrals::build_tree($models, $uid, $uid, 0, '', $current_user_model),
                'count_all' => Yii::$app->params['number']
            ]);
        }
    }

    public function actionSearchLoginInTree($login, $iduser)
    {
        $user = api\User::get($login);

        if ($user) {
            echo $user->id;
        }
        return false;
    }

    public function actionInfo()
    {
        return $this->render('info', [
            'model' => $this->user
        ]);
    }

    public function actionGetInfo()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $totalPurchases = 0;
            $selfPoints = 0;
            $user = api\User::get($request->get('u'));
            if ($user) {
                $sales = api\Sale::get($user->username);
                foreach ($sales as $sale) {
                    $totalPurchases += $sale->bonusPoints;
                    $selfPoints += $sale->price;
                }

                $product = api\Product::get($user->statistics->pack);
                $registrationsStatisticsPerMoths = api\graph\RegistrationsStatistics::get($user->accountId);
                $autoBonusArray = explode(' ', THelper::t('auto_bonus'));
                $autoBonus = array_shift($autoBonusArray) . '<br />' . implode(' ', $autoBonusArray);
                $propertyBonusArray = explode(' ', THelper::t('property_bonus'));
                $propertyBonus = array_shift($propertyBonusArray) . '<br />' . implode(' ', $propertyBonusArray);
                $personalPartners = api\User::personalPartners($user->id);
                $upSpilovers = api\User::upSpilover($user->id);
                $parent = api\User::get($user->parentId);
            }
            return $this->renderAjax('_info', [
                'user' => isset($user) && $user ? $user : '',
                'product' => isset($product) ? $product : false,
                'registrationsStatisticsPerMoths' => isset($registrationsStatisticsPerMoths) ? $registrationsStatisticsPerMoths : '',
                'autoBonus' => isset($autoBonus) && $autoBonus ? $autoBonus : 0,
                'propertyBonus' => isset($propertyBonus) && $propertyBonus ? $propertyBonus : 0,
                'personalPartners' => isset($personalPartners) && $personalPartners ? $personalPartners : '',
                'upSpilovers' => isset($upSpilovers) && $upSpilovers ? $upSpilovers : [],
                'parent' => isset($parent) && $parent ? $parent : '',
                'totalPurchases' => $totalPurchases,
                'selfPoints' => $selfPoints
            ]);
        }
    }

    public function actionPurchase()
    {
        if (Yii::$app->request->isPost) {
            $purchaseForm = new PurchaseForm();
            $purchaseForm->load(Yii::$app->request->post());
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($purchaseForm);
            } else {
                $purchaseUser = api\User::get($purchaseForm->user);

            }
        }

        return $this->render('purchase', [
            'model' => $this->user,
            'purchases' => Sales::find()->all()
        ]);
    }

    public function actionGetPurchase()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $user = api\User::get($request->get('u'));
            if ($user) {
                $purchases = api\Sale::get($user->username);
            }
            return $this->renderAjax('_purchase', [
                'purcahseUser' => $user ? $user : '',
                'purchases' => isset($purchases) && $purchases ? $purchases : []
            ]);
        }
    }

    public function actionAddPurchase()
    {
        $request = Yii::$app->request;

        return $this->renderAjax('add_purchase', [
            'model' => new PurchaseForm(),
            'language' => Yii::$app->language,
            'productsSelect' => ArrayHelper::map(api\Product::all(), 'product', 'productName')
        ]);
    }

    public function actionCancelPurchase()
    {
        $id = Yii::$app->request->get('id');
        if ($id) {
            $sale = Sales::findOne(['_id' => new ObjectId($id)]);
            if ($sale) {
                $user = api\User::get($sale->getAttribute('idUser'));
                if ($user) {
                    $sponsor = api\User::get($user->sponsor->_id);
                    if ($sponsor) {
                        if ($sponsor->moneys - $sale->getAttribute('bonusMoney') >= 0) {
                            $result = api\Sale::cancel($id);
                            if ($result) {
                                Yii::$app->session->setFlash('success', THelper::t('user_purchase_cancellation_success'));
                            } else {
                                Yii::$app->session->setFlash('danger', THelper::t('user_purchase_cancellation_error'));
                            }
                        } else {
                            Yii::$app->session->setFlash('danger', THelper::t('user_purchase_cancellation_sponsor_no_money'));
                        }
                    } else {
                        Yii::$app->session->setFlash('danger', THelper::t('user_purchase_cancellation_sponsor_not_founds'));
                    }
                } else {
                    Yii::$app->session->setFlash('danger', THelper::t('user_purchase_cancellation_user_not_founds'));
                }
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('user_purchase_cancellation_sale_not_founds'));
            }
        }

        $this->redirect('/' . Yii::$app->language . '/business/user/purchase');
    }

    public function actionChangeImg() {
        $model = new ProfileForm();

        $request = Yii::$app->request;

        if ($request->isPost) {
            if ($model->load($request->post())) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if (! $model->avatar || ! $model->login) {
                    return $this->redirect('profile');
                } else {
                    $user = api\User::get($model->login);

                    if (! $model->afterSave($user->id)) {
                        return $this->redirect('/' . Yii::$app->language . '/business/user/?u=' . $user->username);
                    }

                    if (! $model->avatar->extension) {
                        $extension = 'jpg';
                    } else {
                        $extension = $model->avatar->extension;
                    }

                    $model->avatar = base64_encode($model->avatar->baseName) . '.' . $extension;

                    $path = "uploads/{$user->id}/{$model->avatar}";
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    api\User::update($user->accountId, ['avatar' => $base64]);
                }
            }

            return $this->redirect('/' . Yii::$app->language . '/business/user/?u=' . $user->username);
        } else {
            if ($request->get('u')) {
                $user = api\User::get($request->get('u'));
                if ($user) {
                    $model->login = $user->username;
                }
            }
        }

        return $this->renderAjax('change_image', [
            'model' => $model
        ]);
    }

    public function actionDocs()
    {
        return $this->render('docs', [
            'number' => 5,
            'numbers' => [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5
            ],
            'docs' => api\user\Doc::getAll()
        ]);
    }

    public function actionCommission()
    {
        $url = Yii::$app->params['apiAddress'] . 'transactions/money/' . $this->user->id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $options = PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0;
        $response = json_decode($response, false, 512, $options);

        return $this->render('commission', [
            'user' => $response
        ]);
    }

    public function actionAddNote()
    {
        $defaultText = THelper::t('new_note');

        $request = Yii::$app->request;

        if ($request->isPost) {
            $user = api\User::get($request->post('u'));
            if ($user) {
                return $this->renderPartial('note', [
                    'notes' => [api\Note::add($user->id, $defaultText, $defaultText)],
                    'user' => $user
                ]);
            }
        }
    }

    public function actionUpdateNote()
    {
        $request = Yii::$app->request;
        $noteId = $request->post('id');
        $title = $this->_getTitle(strip_tags($request->post('title')), 30);
        $body = strip_tags($request->post('body'));

        if ($user && $noteId && $body) {
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

    public function actionPoint()
    {
        $user = api\User::get(Yii::$app->request->get('u'));
        $response = [];

        if ($user) {
            $url = Yii::$app->params['apiAddress'] . 'transactions/points/' . $this->user->id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $options = PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0;
            $response = json_decode($response, false, 512, $options);
        }

        return $this->render('point', [
            'user' => $response
        ]);
    }

    public function actionMoneyTransfer()
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            return $this->renderAjax('_money_transfer_info', [
                'userFrom' => Users::find()->where(['username' => $request->post('u1')])->one(),
                'userTo' => Users::find()->where(['username' => $request->post('u2')])->one()
            ]);
        } else {
            return $this->render('money-transfer');
        }
    }

    public function actionMoneyTransferSend()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        $userFrom = Users::find()->where(['username' => $request->post('u1')])->one();
        $userTo = Users::find()->where(['username' => $request->post('u2')])->one();

        if ($userFrom->_id == $userTo->_id) {
            return ['success' => false, 'error' => THelper::t('money_transfer_one_user_error')];
        }

        $money = floatval(trim($request->post('money')));

        if ($userFrom->moneys < $money) {
            $result = ['success' => false, 'error' => THelper::t('money_transfer_no_money')];
        } else {
            $transaction = new Transaction();

            $date = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

            $transaction->idFrom = new ObjectID($userFrom->_id);
            $transaction->idTo = new ObjectID($userTo->_id);
            $transaction->amount = $money;
            $transaction->forWhat = THelper::t('money_transfer_for_what');
            $transaction->type = Transaction::TYPE_MONEY;
            $transaction->reduced = true;
            $transaction->dateCreate = $date;
            $transaction->usernameTo = $userTo->username;
            $transaction->dateReduce = $date;

            $balanceFrom = $userFrom->moneys;
            $transaction->saldoFrom = $balanceFrom - $money;
            $balanceTo = $userTo->moneys;
            $transaction->saldoTo = $balanceTo + $money;
            $transaction->__v = 0;

            if ($transaction->save()) {
                $userFrom->moneys -= $money;
                $userTo->moneys += $money;
                if ($userFrom->save() && $userTo->save()) {
                    $moneyTransfer = new MoneyTransfer();

                    $moneyTransfer->idFrom = new ObjectID($userFrom->_id);
                    $moneyTransfer->balanceFrom = $balanceFrom;
                    $moneyTransfer->idTo = new ObjectID($userTo->_id);
                    $moneyTransfer->balanceTo = $balanceTo;
                    $moneyTransfer->amount = $money;
                    $moneyTransfer->admin = new ObjectID($this->user->id);
                    $moneyTransfer->date = $date;

                    $moneyTransfer->save();

                    $result = ['success' => true, 'data' => $transaction];
                } else {
                    $transaction->delete();
                    $result = ['success' => false, 'error' => THelper::t('money_transfer_error')];
                }
            } else {
                $result = ['success' => false, 'error' => THelper::t('money_transfer_error')];
            }
        }

        return $result;
    }

    public function actionMoneyTransferLog()
    {
        return $this->render('money_transfer_log', [
            'moneyTransfers' => MoneyTransfer::find()->orderBy('date desc')->all()
        ]);
    }

}