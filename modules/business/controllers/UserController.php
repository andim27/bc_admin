<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api\finance\Operations;
use app\models\api\finance\Points;
use app\models\api\Pin;
use app\models\api\Product;
use app\models\api\Sale;
use app\models\api\User;
use app\models\Langs;
use app\models\MoneyTransfer;
use app\models\Pins;
use app\models\Products;
use app\models\Sales;
use app\models\Transaction;
use app\models\Users;
use app\modules\business\models\AddWriteOffFrom;
use app\modules\business\models\PincodeCancelForm;
use app\modules\business\models\PincodeForm;
use app\modules\business\models\PincodeGenerateForm;
use app\modules\business\models\ProfileForm;
use app\modules\business\models\PurchaseForm;
use app\modules\business\models\WriteOffs;
use Yii;
use app\models\api;
use app\modules\business\models\UsersReferrals;
use yii\base\Object;
use yii\data\Pagination;
use yii\helpers\Url;
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
    const DEFAULT_PRODUCT = 14;

    /**
     *
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
                if (isset($user->id)) {
                    $model->id                          = $user->id;
                }

                if (isset($user->firstName)) {
                    $model->name                        = $user->firstName;
                }

                if (isset($user->secondName)) {
                    $model->surname                     = $user->secondName;
                }

                if (isset($user->email)) {
                    $model->email                       = $user->email;
                }

                if (isset($user->phoneNumber)) {
                    $model->mobile                      = $user->phoneNumber;
                }

                if (isset($user->phoneNumber2)) {
                    $model->smobile                     = $user->phoneNumber2;
                }

                if (isset($user->phoneWellness)) {
                    $model->phoneWellness               = $user->phoneWellness;
                }

                if (isset($user->skype)) {
                    $model->skype                       = $user->skype;
                }

                if (isset($user->state)) {
                    $model->state                       = $user->state;
                }

                if (isset($user->city)) {
                    $model->city                        = $user->city;
                }

                if (isset($user->address)) {
                    $model->address                     = $user->address;
                }

                if (isset($user->links->site)) {
                    $model->site                        = $user->links->site;
                }

                if (isset($user->links->odnoklassniki)) {
                    $model->odnoklassniki               = $user->links->odnoklassniki;
                }

                if (isset($user->links->vk)) {
                    $model->vk                          = $user->links->vk;
                }

                if (isset($user->links->fb)) {
                    $model->fb                          = $user->links->fb;
                }

                if (isset($user->links->youtube)) {
                    $model->youtube                     = $user->links->youtube;
                }

                if (isset($user->username)) {
                    $model->login                       = $user->username;
                }

                if (isset($user->settings->notifyAboutJoinPartner)) {
                    $model->notifyAboutJoinPartner      = $user->settings->notifyAboutJoinPartner;
                }

                if (isset($user->settings->notifyAboutReceiptsMoney)) {
                    $model->notifyAboutReceiptsMoney    = $user->settings->notifyAboutReceiptsMoney;
                }

                if (isset($user->settings->notifyAboutReceiptsPoints)) {
                    $model->notifyAboutReceiptsPoints   = $user->settings->notifyAboutReceiptsPoints;
                }

                if (isset($user->settings->notifyAboutEndActivity)) {
                    $model->notifyAboutEndActivity   = $user->settings->notifyAboutEndActivity;
                }

                if (isset($user->settings->notifyAboutOtherNews)) {
                    $model->notifyAboutOtherNews        = $user->settings->notifyAboutOtherNews;
                }

                if (isset($user->settings->phoneTelegram)) {
                    $model->phoneTelegram               = $user->settings->phoneTelegram;
                }

                if (isset($user->settings->phoneViber)) {
                    $model->phoneViber               = $user->settings->phoneViber;
                }

                if (isset($user->settings->phoneWhatsApp)) {
                    $model->phoneWhatsApp               = $user->settings->phoneWhatsApp;
                }

                if (isset($user->settings->phoneFB)) {
                    $model->phoneFB               = $user->settings->phoneFB;
                }

                if (isset($user->settings->phoneWhatsApp)) {
                    $model->selectedLang               = $user->settings->selectedLang;
                }

                $model->cards                       = (!empty($user->cards) ? ArrayHelper::toArray($user->cards) : '');

                return $this->render('profile', [
                    'user'                  => $user,
                    'model'                 => $model,
                    'countries'             => api\dictionary\Country::all(),
                    'languages'             => ArrayHelper::map(api\dictionary\Lang::supported(), 'alpha2', 'native'),
                    'notes'                 => api\Note::all($user->id),
                    'modelSales'            => Sales::getAllSalesUser($model->id),
                    'modelMovementMoney'    => Transaction::getAllMoneyTransactionUser($model->id),
                    'modelMovementPoints'   => Transaction::getAllPointsTransactionUser($model->id)
                ]);
            } else {
                $columns = [

                    'email', 'username', 'created', 'phoneNumber', 'full_name',
                    'country_city', 'sponsor_username', 'sponsor_full_name', 'rank', 'action'
                ];

                $filterColumns = [
                    'email', 'username', 'created', 'phoneNumber', 'firstName',
                    'city', 'sponsor', 'sponsor', 'rank', 'action'
                ];

                $users = Users::find();

                function getRank($search){
                    $rank = Langs::find()->where(['stringValue' => $search])->andFilterWhere(['or',
                        ['like', 'stringId', 'rank_'],
                    ])->one();

                    return $rank ? (int) str_replace('rank_', '', $rank->stringId) : '';
                }

                if ($search = $request->get('search')['value']) {


                    // @todo filter
                    $users->andFilterWhere(['or',
                        ['=', 'email', $search],
                        ['like', 'username', $search],
                        ['like', 'firstName', explode(' ', $search)[0]],
                        ['like', 'secondName', $search],
                        ['like', 'created', $search],
                        ['like', 'phoneNumber', $search],
                        ['=', 'rank', getRank($search)],
                        ['like', 'country', $search],
                        ['like', 'city', $search],
                    ]);
                }

                $pages = new Pagination(['totalCount' => $users->count()]);

                if ($order = $request->get('order')[0]) {
                    $users->orderBy([$filterColumns[$order['column']] => ($order['dir'] === 'asc' ? SORT_ASC : SORT_DESC)]);
                }

                if (Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                    $data = [];

                    $users = $users
                        ->offset($request->get('start') ?: $pages->offset)
                        ->limit($request->get('length') ?: $pages->limit);

                    $count = $users->count();

                    foreach (api\User::convert($users->all()) as $key => $user) {
                        $nestedData = [];

                        $nestedData[$columns[0]] = $user->email;
                        $nestedData[$columns[1]] = $user->username;
                        $nestedData[$columns[2]] = $user->created ? gmdate('d.m.Y', $user->created) : '';
                        $nestedData[$columns[3]] = $user->phoneNumber;
                        $nestedData[$columns[4]] = $user->firstName . ' ' . $user->secondName;
                        $nestedData[$columns[5]] = $user->getCountryCityAsString();
                        $nestedData[$columns[6]] = $user->sponsor ? $user->username : '';
                        $nestedData[$columns[7]] = $user->sponsor ? ($user->secondName . ' ' . $user->firstName) : '';
                        $nestedData[$columns[8]] = $user->rankString ? $user->rankString : '';
                        $nestedData[$columns[9]] = Html::a('<i class="fa fa-pencil"></i>', ['/business/user', 'u' => $user->username]);
                        $nestedData[$columns[9]] .= '&nbsp;&nbsp;' . Html::a('<i class="fa fa-shopping-cart"></i>', ['/business/user/pincode', 'u' => $user->username], ['data-toggle' => 'ajaxModal']);

                        $data[] = $nestedData;
                    }

                    return [
                        'draw' => $request->get('draw'),
                        'data' => $data,
                        'recordsTotal' => $count,
                        'recordsFiltered' => $count
                    ];
                }

                return $this->render('index', [
                    'users' => []
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
                    'phoneWellness'             => $model->phoneWellness,
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

                    'cards'                     => (object)$request->post()['ProfileForm']['cards']
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

    public function actionPincode()
    {

        $pinForm = new PincodeForm();
        $request = Yii::$app->request;

        if ($request->isPost) {
            $pinForm->load($request->post());

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($pinForm);
            } else {
                $user = api\User::get($pinForm->user);

                $pin = Pin::checkPin($pinForm->pin);

                if ($pin) {
                    $pinHasErrors = $this->pinCheck($pin);

                    if ($pinHasErrors) {
                        return  $this->redirect('/' . Yii::$app->language . '/business/user');
                    }

                    $sponsor = $user->sponsor ? $user->sponsor->id : null;
                    $response = $this->checkSponsorActivity($sponsor);

                    if ($response) {
                        return  $this->redirect('/' . Yii::$app->language . '/business/user');
                    }
                }

                $response = Sale::buy([
                    'iduser' => $user->id,
                    'pin' => $pinForm->pin,
                    'warehouse' => !empty($pinForm->warehouse) ? $pinForm->warehouse : null
                ]);

                if ($response === 'OK') {
                    Yii::$app->session->setFlash('success', THelper::t('successful_operation'));

                    return  $this->redirect('/' . Yii::$app->language . '/business/user');
                }
            }
        }

        if ($request->isAjax) {
            $pinForm->user = $request->get('u');

            return $this->renderAjax('_pincode', [
                'model' => $pinForm,
                'warehouses' => $this->getWarehouseList(),
                'language' => Yii::$app->language
            ]);
        }

        return $this->render('_pincode', [
            'model' => $this->user
        ]);
    }

    /**
     * @param $pin
     * @return null|string
     */
    public function pinCheck($pin)
    {
        if (!empty($pin->error)) {
            Yii::$app->session->setFlash('danger', $pin->error);

            return true;
        }

        if ($pin && $pin->activated && !empty($pin->userId) && $pin->userId === $this->user->id) {
            Yii::$app->session->setFlash('warning', THelper::t('this_pin_is_applied_to_you_automatically'));

            return true;
        } elseif ($pin && $pin->activated && (empty($pin->userId) || $pin->userId !== $this->user->id)) {
            Yii::$app->session->setFlash('warning', THelper::t('this_pin_is_used_before'));

            return true;
        }

        return null;
    }

    /**
     * @param $sponsor
     * @param string $formId
     * @return bool|string
     */
    public function checkSponsorActivity($sponsor, $formId = 'pincode')
    {
        $sponsor = api\User::get($sponsor);
        $accepted = Yii::$app->request->post('accepted');

        if (!empty($sponsor) && empty($sponsor->bs) && empty($accepted)) {
            Yii::$app->session->setFlash('warning', THelper::t('sponsor_activity_is_not_paid'));
        }

        return false;
    }

    /**
     * @return array
     */
    public function getWarehouseList()
    {
        $infoAdmins = api\User::admins();

        $listWarehouse = [];
        $lang = Yii::$app->language;

        foreach($infoAdmins as $item){
            if(!empty($item->warehouseName->{$lang})){
                $listWarehouse[$item->id] = $item->warehouseName->{$lang};
            }
        }

        asort($listWarehouse);

        return $listWarehouse;
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
        $userId = Yii::$app->request->get('id');

        if (is_numeric(Yii::$app->request->get("side", NULL))) {
            $last_side = UsersReferrals::getLastSideApi($userId, Yii::$app->request->get('side'));
            $userId = $last_side->_id;
        }

        if ($models = api\User::spilover($userId, 4)) {
            if ($models[0]->accountId == $this->user->accountId) {
                $models[0]->parentId = "000000000000000000000000";
            }
            $current_user_model = $models[0];
            Yii::$app->params['number'] = 0;
            return $this->renderAjax('ajax_structure_tree', [
                'current_user_model' => $current_user_model,
                'tree' => UsersReferrals::build_tree($models, $userId, $userId, 0, '', $current_user_model),
                'count_all' => Yii::$app->params['number']
            ]);
        }
    }

    public function actionSearchLoginInTree($login, $iduser)
    {
        $user = api\User::get($login, false);

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
                $operations = Operations::all($parent->id);
                $points = Points::all($parent->id);
                $writeOffs = WriteOffs::find()->where(['uid' => $user->id])->all();
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
                'selfPoints' => $selfPoints,
                'operations' => isset($operations) ? $operations : null,
                'points' => isset($points) ? $points : null,
                'writeOffs' => isset($writeOffs) ? $writeOffs : null
            ]);
        }

        return false;
    }

    public function actionSentWriteOff()
    {
        $request = Yii::$app->request;

        $model = new AddWriteOffFrom();

        $model->userId = $request->get('u');

        return $this->renderAjax('/finance/add_write_off', [
            'model' => $model,
            'language' => Yii::$app->language
        ]);
    }

    public function actionWriteOff()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $addWriteOffForm = new AddWriteOffFrom();

            $addWriteOffForm->load($request->post());

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($addWriteOffForm);

            } else {
                $data = [
                    'iduser' => $addWriteOffForm->userId,
                    'amount' => $addWriteOffForm->amount,
                    'type' => 0,
                ];

                $status = Operations::add($data);

                if ($status) {
                    $date = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);

                    $writeOffs = new WriteOffs();

                    $writeOffs->uid = $addWriteOffForm->userId;
                    $writeOffs->amount = $addWriteOffForm->amount;
                    $writeOffs->comment = $addWriteOffForm->comment;
                    $writeOffs->who = $this->user->username;
                    $writeOffs->datetime = $date;

                    $writeOffs->save();
                } else {
                    Yii::$app->session->setFlash('danger', THelper::t('something_went_wrong'));
                }

                return $this->render('/user/info');
            }
        }

        return false;
    }

    public function actionTransactionCancel()
    {
        $request = Yii::$app->request;

        $status = Operations::cancel($request->get('id'));

        if ($status) {
            return $this->renderAjax('/finance/_success');
        }

        return false;
    }

    /**
     * All order
     *
     * @return array|string
     * @throws \yii\mongodb\Exception
     */
    public function actionPurchase()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $purchaseForm = new PurchaseForm();
            $purchaseForm->load($request->post());
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($purchaseForm);
            } else {
                $purchaseUser = api\User::get($purchaseForm->user, false);
            }
        }

        if($request->isAjax){
            $columns = [
                'dateCreate','product','productName','price','bonusPoints','username','firstName_secondName','action'
            ];

            $filterColumns = [
                'dateCreate','product','productName','price','bonusPoints','username','firstName_secondName','action'
            ];

            $purchases = Sales::find();

            if ($search = $request->get('search')['value']) {
                if(mb_strlen($search,'utf-8') >= 2){
                    $purchases->andFilterWhere(['or',
                        ['like', 'dateCreate', $search],
                        ['=', 'product', (int)$search],
                        ['like', 'bonusPoints', $search],

                        ['like', 'productName', Products::getSearchInfoProduct('productName',$search)],
                        ['like', 'price', Products::getSearchInfoProduct('price',$search)],

                        ['like', 'username', Users::getSearchInfoUser('username',$search)],
                        ['like', 'firstName', Users::getSearchInfoUser('firstName',$search)],
                        ['like', 'secondName', Users::getSearchInfoUser('secondName',$search)],

                    ]);
                }
            }


            if ($order = $request->get('order')[0]) {
                $purchases->orderBy([$filterColumns[$order['column']] => ($order['dir'] === 'asc' ? SORT_ASC : SORT_DESC)]);
            }

            $pages = new Pagination(['totalCount' => $purchases->count()]);

            if (Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

                $data = [];

                $purchases = $purchases
                    ->offset($request->get('start') ?: $pages->offset)
                    ->limit($request->get('length') ?: $pages->limit);

                $count = $purchases->count();

                foreach ($purchases->all() as $purchase){
                    $nestedData = [];

                    if (! isset($products[$purchase->product])) {
                        $product = $products[$purchase->product] = $purchase->getInfoProduct()->one();
                    } else {
                        $product = $products[$purchase->product];
                    }

                    $idUser = strval($purchase->idUser);
                    if (! isset($users[$idUser])) {
                        $purcahseUser = $users[$idUser] = $purchase->getInfoUser()->one();
                    } else {
                        $purcahseUser = $users[$idUser];
                    }


                    $nestedData[$columns[0]] = $purchase->dateCreate->toDateTime()->format('Y-m-d H:i:s');
                    $nestedData[$columns[1]] = $purchase->product;
                    $nestedData[$columns[2]] = (!empty($product->productName) ? $product->productName : '');
                    $nestedData[$columns[3]] = (!empty($product->price) ? $product->price : '');
                    $nestedData[$columns[4]] = $purchase->bonusPoints;
                    $nestedData[$columns[5]] = $purcahseUser->username;
                    $nestedData[$columns[6]] = $purcahseUser->firstName . ' ' . $purcahseUser->secondName;
                    $nestedData[$columns[7]] = ($purchase->type == 1 ?
                        Html::a('<i class="fa fa-trash-o"></i>', ['/business/user/cancel-purchase', 'id' => strval($purchase->_id)], ['onclick' => 'return confirmCancellation();']) : 
                        THelper::t('users_purchase_deleted'));

                    if(!empty($product->productName) && !empty($product->price)){
                        $data[] = $nestedData;
                    }
                }

                return [
                    'draw' => $request->get('draw'),
                    'data' => $data,
                    'recordsTotal' => $count,
                    'recordsFiltered' => $count
                ];
            }
        }


        return $this->render('purchase', [
            'model' => $this->user
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

                    $sponsor = api\User::get($user->sponsorId);

                    if ($sponsor) {
                        if ($sponsor->moneys - $sale->getAttribute('bonusMoney') >= 0) {
                            $result = api\Sale::cancel($id);
                            if ($result) {
                                Yii::$app->session->setFlash('success', THelper::t('user_purchase_cancellation_success'));

                                SaleController::cancellationGoodsInOrder($id);
                                
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

    public function actionPincodeCancel()
    {
        $status = '';
        $model = new PincodeCancelForm();

        $request = Yii::$app->request;

        if ($request->isPost && $model->load($request->post())) {
            $pin = Pins::find()->where(['pin' => $model->pin])->one();

            if (!$pin) {
                $status = THelper::t('pin_is_not_found');
            } else {
                $pin->used = true;
                $pin->isDelete = true;
                $pin->isActivate = true;

                $pin->save();

                $status = THelper::t('pin_is_deactivated');
            }
        }

        return $this->render('pincode_cancel', [
            'model' => $model,
            'action' => '/' . Yii::$app->language . '/business/user/pincode-cancel',
            'status' => $status
        ]);
    }

    public function actionPincodeGenerator()
    {
        $productList = [];
        $productListData = [];
        $pincode = null;
        $defaultProduct = self::DEFAULT_PRODUCT; //Пополнение счета оплаты
        $model = new PincodeGenerateForm();
        $request = Yii::$app->request;

        if ($request->isPost && $model->load($request->post())) {

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if (compareShortPin($model->pin)) {
                $product = Products::findOne(['product' => (integer)$model->product]);
                $pin = api\Pin::createPinForProduct($product ? $product->idInMarket : null, $model->quantity);

                if ($model->isLogin && $pin) {
                    $partner = api\User::get($model->partnerLogin);

                    if (!$partner) {
                        Yii::$app->session->setFlash('danger', THelper::t('partner_not_found'));
                    }

                    $response = Sale::buy([
                        'iduser' => $partner->id,
                        'pin' => $pin,
                        'warehouse' => !empty($_POST['warehouse']) ? $_POST['warehouse'] : null,
                        'formPayment' => 1
                    ]);

                    if ($response === 'OK') {
                        Yii::$app->session->setFlash('success', THelper::t('partner_payment_is_success'));
                    } else {
                        Yii::$app->session->setFlash('danger', THelper::t('partner_payment_is_unsuccessful')
                            . ' ' . '<span style="display:none;">' . $response . ' ' . $partner->id . '</span>');
                    }
                }

                $pincode = $pin;
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('pin_is_incorrect'));
            }


            if(!empty($pincode) && !empty($model->loan)){
                $modelPin = Pins::findOne(['pin'=>$pincode]);
                if(!empty($modelPin)){
                    $modelPin->loan=(boolean)true;
                    if($modelPin->save()){}
                }
            }
        }

        foreach (Product::all() as $product) {
            $productList[$product->product] = $product->productName . ' - ' . $product->price .' eur';
            $productListData[$product->product] = [
                'price' => $product->price,
                'bonusMoney' => $product->bonusMoney,
                'bonusPoints' => $product->bonusPoints,
            ];
        }



        return $this->render('pincode_generator', [
            'model' => $model,
            'productList' => $productList,
            'productListData' => $productListData,
            'defaultProduct' => $defaultProduct,
            'pincode' => !empty($pincode) ? $pincode : null
        ]);
    }

    public function actionSearchListUsers($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $dataUsers = \app\models\Users::getAllUsers();
        if (!is_null($q)) {
            $input = preg_quote($q, '~'); // don't forget to quote input string!
            $result = preg_grep('~' . $input . '~', $dataUsers);
            if(!empty($result)){
                $out['results'] = [];
                foreach ($result as $k=>$item) {
                    $out['results'][] = [
                        'id'        =>  $k,
                        'text'      =>  $item
                    ];
                }
            }
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => $dataUsers[$id]];
        }



        return $out;
    }

}

function compareShortPin($pin){
    //@todo algorithm

    return $pin === '1234';
}