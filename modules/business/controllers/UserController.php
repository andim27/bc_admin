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
use app\models\PreUp;
use app\models\Products;
use app\models\Sales;
use app\models\Transaction;
use app\models\Users;
use app\models\LoanRepayment;
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
    const DEFAULT_PRODUCT = 9001;

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
                'admin_user'=>$this->user,
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
                'writeOffs' => isset($writeOffs) ? $writeOffs : null,
                'sales' =>isset($sales) ? $sales: null
            ]);
        }

        return false;
    }
    //-------------------------------------cancel user sale-----------------------------
    public function actionCancelSale() {
        $result=['success'=>true,'message'=>THelper::t('confirmed_canceled')];

        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        try {
            $sale_id  = $request->post('sale_id');
            $comment  = $request->post('comment');
            $comment_user_name  = $request->post('comment_user_name');
            //---save comment---
            $sale = Sales::findOne(['_id' => new ObjectId($sale_id)]);
            $sale->comment = $comment;
            $sale->comment_user_name = $comment_user_name;
            $sale->save();
            $sales_res = api\Sale::cancel($sale_id);
            if ($sales_res == false) {
                $result=['success'=>false,'message'=>$sales_res['error']];
            }
        } catch (\Exception $e) {
            $result=['success'=>false,'message' =>(isset($sales_res['error'])?$sales_res['error']:'').':'.$e->getMessage().' code='.$e->getLine()];
        }

        return $result;
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

    public function actionCancelSaleLog()
    {
        return $this->render('cancel_sale_log', [
            'cancelSales' => Sales::find()->where(['type'=>-1])->orderBy('updated_at desc')->all()
        ]);
    }

    public function actionGetUserData()
    {
        $result = ['success' => false, 'message' => THelper::t('user_not_found')];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $login =Yii::$app->request->post('login');
        $user = api\User::get($login, false);
        if ($user) {
            $data=[];
            $data['fio']=$user->firstName.' '.$user->secondName;
            $data['email']=$user->email;
            if (isset($user->skype)&&($user->skype !='')) {
                $data['skype']=$user->skype;
            }

            $result = ['success' => true, 'message' => THelper::t('ok'),'data'=>$data];
        }
        return $result;
    }

    public function actionGetBalanceTable()
    {
        $result = ['success' => false, 'message' => THelper::t('user_not_found')];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $login = Yii::$app->request->post('login');
        $user  = api\User::get($login, false);
        if ($user) {
            $data = [];
            $loans = 0;
            $payments = 0;
            $data['username'] = $user->username;
            $data['user_id '] = $user->id;
            $data['moneys']   = round($user->moneys,2);
            $data['loans']    = $loans;
            $data['payments'] = $payments;
            $result = ['success' => true, 'message' => THelper::t('ok'),'data'=>$data];
        }
        return $result;
    }
    public function actionGetLoanTable()
    {
        $result = ['success' => false, 'message' => THelper::t('user_not_found')];
        Yii::$app->response->format = Response::FORMAT_JSON;
        $login = Yii::$app->request->post('login');
        $user  = api\User::get($login, false);

        if ($user) {
            $loans =0;
            //--b:loan--
            $wherePins = [
                'loan' => true,
                'isDelete' => false,
                'userId' =>  new ObjectID($user->id)
            ];
            $model = Pins::find()->where($wherePins)->all();
            if ($model) {
                foreach ($model as $item) {
                    $infoPin = api\Pin::getPinInfo($item->pin);
                    if (!empty($infoPin->pinUsedBy)) {
                        $loans += ($infoPin->productPrice * $infoPin->count);
                    }
                }
            }
            //--e:loan--
            //--b:parments--
            $payments = LoanRepayment::find()->where(['user_id'=>new ObjectID($user->id)])->sum('amount');
            //--e:payments--
            $data = [];

            $data['username'] = $user->username;
            $data['user_id '] = $user->id;
            $data['moneys']   = round($user->moneys,2);
            $data['loans']    = $loans;
            $data['payments'] = $payments;
            $data['debt'] = $loans - $payments;
            $result = ['success' => true, 'message' => THelper::t('ok'),'data'=>$data];
        }
        return $result;
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
        $kind_items =[
            'loan'=>'Займ',
            'bank'=>'Пополнение через банк',
            'paysera'  =>'Пополнение баланса PaySera',
            'advcash'  =>'Пополнение баланса AdvCash',
            'perevod'  =>'Пополнение переводом',
            'cash'     =>'Пополнение наличными',
            'advaction'=>'Пополнение по рекламной акции',
            'other'    =>'Другое'
        ];
        $model = new PincodeGenerateForm();
        $model->loan = 1;

        $request = Yii::$app->request;
        $kind    = $request->post('kind-operation') ?? '';
        $comment = $request->post('comment') ?? '';

        if ($request->isPost && $model->load($request->post())) {

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }

            if (compareShortPin($model->pin) ) {
                $product = Products::findOne(['product' => (integer)$defaultProduct]);
                $pin = api\Pin::createPinForProduct($product ? $product->idInMarket : null, $model->quantity, $this->user->id);

                if ($model->isLogin && $pin) {
                    $partner = api\User::get($model->partnerLogin);

                    if (!$partner) {
                        Yii::$app->session->setFlash('danger', THelper::t('partner_not_found'));
                    }

                    //if ($kind != 'loan') { //--Does loan generate purchase?
                        $data =[
                            'author_id' => $this->user->id, //new ObjectID($this->user->id),
                            'product'   => $defaultProduct,
                            'amount'    => (int)$model->quantity,
                            'iduser'    => $partner->id,
                            'username'  => $model->partnerLogin,
                            'pin'       => $pin,
                            'warehouse' => !empty($_POST['warehouse']) ? $_POST['warehouse'] : null,
                            'formPayment' => 1,
                            'kind'      => $kind,
                            'comment'   => $comment,
                            'status'    =>'created'//'wait','done','cancel'
                        ];
                        $response = self::actionPreUpCreate($data);


                        if ($response === 'OK') {
                            Yii::$app->session->setFlash('success', THelper::t('partner_payment_is_success'));
                        }
                         else {
                            Yii::$app->session->setFlash('danger', THelper::t('partner_payment_is_unsuccessful')
                                . ' ' . '<span style="display:none;">' . implode(",", $response). ' ' . $partner->id . '</span>'.
                            (isset($response['mes'])?$response['mes']:'') );
                        }
                    //}
                }

                $pincode = $pin;
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('pin_is_incorrect'));
            }

            if ($kind =='loan') {
                $model->loan = 1;
            }
            if(!empty($pincode) ){
                $mes='';
                $modelPin = Pins::findOne(['pin'=>$pincode]);
                if(!empty($modelPin)){
                    if (!empty($model->loan)) {
                        $modelPin->loan=(boolean)true;
                    }
                    If (!empty($comment)) {
                        $modelPin->comment = $comment;
                        $mes='!';
                    }
                    If (!empty($kind)) {
                        $modelPin->kind = $kind;
                        $mes='!!';
                    }
                    if ($modelPin->save()){
                        Yii::$app->session->setFlash('success', 'Сохранено '.$mes);
                    }
                }
            }
        }

        foreach (Product::all() as $product) {
            if (($product->product == self::DEFAULT_PRODUCT)) {//--balance top-up
                $productList[$product->product] = $product->productName . ' - ' . $product->price .' eur';
                $productListData[$product->product] = [
                    'price' => $product->price,
                    'bonusMoney' => $product->bonusMoney,
                    'bonusPoints' => $product->bonusPoints,
                ];
            }
        }



        return $this->render('pincode_generator', [
            'model'           => $model,
            'productList'     => $productList,
            'productListData' => $productListData,
            'defaultProduct'  => $defaultProduct,
            'pincode'         => !empty($pincode) ? $pincode : null,
            'kind_items'      => $kind_items
        ]);
    }

    public function actionPreUpCreate($data)
    {
        $res = [];
        //----!! Save to PreUp before buing -wait to be accepted
        // $data['kind'] = ';kind:'.$data['kind'];
        // $data['comment'] =';comment:'.$data['comment'];
        // $res = Sale::buy($data);
        $cat_coll_name ='pre_up';
        try {
            $Categories=Yii::$app->mongodb->getCollection($cat_coll_name);
            if (!($Categories->name == $cat_coll_name)) {
                Yii::$app->mongodb->createCollection($cat_coll_name);

            }
            //$data['created_at'] = \DateTime::createFromFormat('Y/m/d H:i:s',date("y.m.d"));
            $data['created_at'] = new UTCDatetime(strtotime(date("Y-m-d H:i:s")) * 1000);
            $pre_up_id = $Categories->insert($data);
            if($curl=curl_init())
            {
                $items = ['id' =>$pre_up_id,'comment' => $data['comment']];
                curl_setopt($curl,CURLOPT_URL,'http://ovh-1.ooo.ua:3039/receiveMessage');
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl,CURLOPT_POST,true);
                curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($items));

                //curl_setopt($curl,CURLOPT_POSTFIELDS,"items=".json_encode($items));
                $out=curl_exec($curl);
                curl_close($curl);

                $out = json_decode($out,True);
                //foreach ($out as $item) {
                    //$arrayUnits[$item['id__pr']] = $item['id'];
                //}
                if ($out['action'] == 'ok') {
                    //$pre_up_state ='ok';
                    $pre_up_id = '5c3f544f1198a40011232343';
                    $res = self::actionBalanceApply($pre_up_id);

                }
                //$res = 'OK';
            } else {
                $res['mes'] = 'Viber send error...';
            }
        } catch (\Exception $e) {
            $res['mes'] = 'Saved result:'.$e->getMessage().' line:'.$e->getLine();
        }
        //--send to vider for apply

        return $res;
    }

    public function actionBalanceApply($id)
    {
       $res = 'OK';
        try {
            //--status change--
            $rec = PreUp::find($id);
            $rec->status = 'done';
            $rec->save();
            //--buy product--
            $data =[
                'author_id' => $rec->id, //new ObjectID($this->user->id),
                'product'   => $rec->product,
                'amount'    => (int)$rec->quantity,
                'iduser'    => $rec->id,
                'username'  => $rec->partnerLogin,
                'pin'       => $rec->pin,
                //'warehouse' => !empty($_POST['warehouse']) ? $_POST['warehouse'] : null,
                'formPayment' => 1,
                'kind'      => ';kind:'.$rec->kind,
                'comment'   => ';comment:'.$rec->comment,
                'status'    => 'done' //'created','wait','done','cancel'
            ];
            $res = Sale::buy($data);
            if ($res != 'OK') {
                $res['mes'] = '!Balance Up ERROR!';
            }
        } catch (\Exception $e) {
            $res['mes'] = 'Pre status error id:'.$id.' '.$e->getMessage().' line:'.$e->getLine();
        }

        return $res;
    }
    public function actionBalanceAction()
    {
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }
        $request = Yii::$app->request;
        $id     = $request->post('id');
        $action = $request->post('action');
        $status_html_done = '<span class="glyphicon glyphicon-ok" style="color:green" title="done"></span>';
        if ($action == 'cancel') {
            $status_html_done = '<span class="glyphicon glyphicon-remove" title="done"></span>';
        }
        $res = ['success'=>true,'mes'=>'done','status_html'=>'done','id'=>$id,'status_html' => $status_html_done];
        return $res;
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

    return $pin === '7562';
}