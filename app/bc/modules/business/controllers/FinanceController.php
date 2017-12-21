<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api\Sale;
use app\models\User;
use DateTime;
use kartik\mpdf\Pdf;
use yii\base\Object;
use yii\web\Response;
use Yii;
use app\modules\business\models\FinanceForm;
use app\modules\business\models\CardForm;
use app\components\THelper;
use app\models\api;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\api\Pin;

class FinanceController extends BaseController
{
    /**
     * @return array
     */
    public static function getProductGroupList()
    {
        return [
            [
                'key' => 0,
                'name' => THelper::t('web_wellness'),
                'products' => [
                    'pack' => [37, 36, 22, 21, 23, 20, 35, 19],
                    'upgrade' => [39, 38, 27, 26, 25],
                    'balance' => [32, 34, 31, 30, 29, 28],
                ]
            ],
            [
                'key' => 1,
                'name' => THelper::t('vip_vip'),
                'products' => [
                    'pack' => [3, 2, 1],
                    'upgrade' => [17, 16, 15],
                    'balance' => [33, 7, 10, 12, 8, 6],
                ] // 11, 13, 9 - removed
            ],
            [
                'key' => 2,
                'name' => THelper::t('vip_coin'),
                'products' => [
                    'pack' => [45, 44, 43, 42, 41, 40],
                    'upgrade' => [48, 47, 46],
                ]
            ],
            [
                'key' => 3,
                'name' => THelper::t('other'),
                'products' => [5, 4, 14]
            ]
        ];
    }

    /**
     * @param $groupId
     * @param string $column
     * @return mixed
     */
    public static function getProductGroupByItsId($groupId, $column = 'key')
    {
        $list = self::getProductGroupList();
        $newList = array_combine(array_column($list, $column), $list);

        return $newList[$groupId];
    }

    /**
     * @return array
     */
    public function getAllProducts()
    {
        $allProducts = [];

        $products = api\Product::all();

        foreach($products as $product) {
            if (!in_array($product->product, [19, 25])) {
                $allProducts[] = $product;
            }
        }

        return $allProducts;
    }


    public function actionIndex()
    {
        $model = new FinanceForm();
        $model->balance = $this->user->moneys;
        $model->userEmail = $this->user->email;
        $model->userId = $this->user->id;

        if (Yii::$app->request->isPost) {
            switch ($_POST['finance']) {
                case 'withdrawal':
                    $withdrawal = $_POST['FinanceForm']['withdrawal'];

                    if ((float)$withdrawal > $model->balance) {
                        Yii::$app->session->setFlash('danger', THelper::t('not_enough_money'));
                        return $this->refresh();
                    }

                    if ((float)$withdrawal < 0) {
                        Yii::$app->session->setFlash('danger', THelper::t('withdrawal_can_not_be_negative'));
                        return $this->refresh();
                    }

                    $url2 = Yii::$app->params['apiAddress'] . 'transactions/transferMoney/';
                    $ch2 = curl_init();
                    curl_setopt($ch2, CURLOPT_URL, $url2);
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch2, CURLOPT_POST, true);
                    curl_setopt($ch2, CURLOPT_POSTFIELDS, "idFrom=" . $this->user->id . "&idTo=000000000000000000000001&amount=" . $withdrawal . "");
                    $response2 = curl_exec($ch2);
                    curl_close($ch2);

                    if ($response2 == 'OK') {
                        Yii::$app->session->setFlash('success', THelper::t('transaction_is_successful'));
                        return $this->refresh();
                    } else {
                        Yii::$app->session->setFlash('danger', THelper::t('something_wrong'));
                        return $this->refresh();
                    }

                    break;
                case 'pincode':
                    $postFinanceForm = Yii::$app->request->post('FinanceForm');
                    $pin = Pin::checkPin($postFinanceForm['pincode']);

                    if (!empty($_POST['is_partner']) && $_POST['is_partner'] === 'true') {
                        $session = Yii::$app->session;

                        if ($session->has('partner_pin') && $session->has('partner_id')) {
                            $response = Sale::buy([
                                'iduser' => $session->get('partner_id'),
                                'pin' => $session->get('partner_pin'),
                                'warehouse' => !empty($_POST['warehouse']) ? $_POST['warehouse'] : null
                            ]);

                            $session->remove('partner_pin');
                            $session->remove('partner_id');
                        }
                    } else {
                        if ($pin) {
                            $pinHasErrors = $this->pinCheck($pin);

                            if ($pinHasErrors) {
                                return $pinHasErrors;
                            }

                            $sponsor = $this->user->sponsor ? $this->user->sponsor->_id : null;
                            $response = $this->checkSponsorActivity($sponsor);

                            if ($response) {
                                return $response;
                            }
                        }

                        $response = Sale::buy([
                            'iduser' => $this->user->id,
                            'pin' => $postFinanceForm['pincode'],
                            'warehouse' => !empty($_POST['warehouse']) ? $_POST['warehouse'] : null
                        ]);
                    }

                    if (isset($response) && $response === 'OK') {
                        return $this->renderAjax('pincode_sponsor', [
                            'status' => 'success',
                            'message' => THelper::t('buy_is_successful')
                        ]);
                    } else {
                        return $this->renderAjax('pincode_sponsor', [
                            'status' => 'danger',
                            'message' => THelper::t('something_wrong') . (isset($response) ? '<span style="display: none">' . (string) $response . '</span>' : '')
                        ]);
                    }
                case 'voucher':
                    if ($model->load(Yii::$app->request->post())) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ActiveForm::validate($model, ['financePassword', 'product', 'productPrice']);
                        } else {
                            $result = api\Voucher::create($this->user->id, $model->product);
                            if ($result) {
                                Yii::$app->session->setFlash('success', THelper::t('voucher_has_been_created'));
                            } else {
                                Yii::$app->session->setFlash('danger', THelper::t('voucher_has_not_been_created'));
                            };
                            $this->refresh();
                        }
                    }
                    break;
                case 'pin':
                    if ($model->load(Yii::$app->request->post())) {
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;

                            $validation = ActiveForm::validate($model, ['financePassword', 'product', 'productPrice', 'partnerLogin']);

                            if (count($validation)) {
                                return $validation;
                            }

                            if ($model->pinMode === '0') {
                                $pin = api\Pin::create($model->product, $this->user->id);

                                if ($pin) {
                                    return $this->renderAjax('pincode_sponsor', [
                                        'status' => 'success',
                                        'message' => THelper::t('pin_has_been_created') . ': "' . $pin . '"'
                                    ]);
                                } else {
                                    return $this->renderAjax('pincode_sponsor', [
                                        'status' => 'danger',
                                        'message' => THelper::t('pin_has_not_been_created')
                                    ]);
                                }
                            } elseif ($model->pinMode === '1') {
                                $partner = api\User::get($model->partnerLogin);

                                if ($partner && empty(Yii::$app->request->post()['partner_accepted'])) {
                                    return [
                                        'modal' => 'partner_confirm',
                                        'template' => $this->render('partner_confirm', [
                                            'full_name' => $partner->firstName . ' ' . $partner->secondName
                                        ])
                                    ];
                                } elseif ($partner) {
                                    $sponsor = $partner->sponsor ? $partner->sponsor->_id : null;
                                    $response = $this->checkSponsorActivity($sponsor,'voucher');

                                    if (!empty($response) && empty(Yii::$app->request->post()['accepted'])) {
                                        return $response;
                                    }

                                    $pin = api\Pin::create($model->product, $this->user->id);

                                    if ($pin) {
                                        $hasWarehouse = $this->checkPinWellnessWarehouse($pin);

                                        $session = Yii::$app->session;

                                        $session->set('partner_pin', $pin);
                                        $session->set('partner_id', $partner->id);

                                        if ($hasWarehouse) {
                                            return ['modal' => 'warehouse'];
                                        }

                                        $response = Sale::buy([
                                            'iduser' => $partner->id,
                                            'pin' => $pin,
                                            'warehouse' => !empty($_POST['warehouse']) ? $_POST['warehouse'] : null
                                        ]);

                                        if ($response === 'OK') {
                                            return $this->renderAjax('pincode_sponsor', [
                                                'status' => 'success',
                                                'message' => THelper::t('payment_success')
                                            ]);
                                        } else {
                                            return $this->renderAjax('pincode_sponsor', [
                                                'status' => 'danger',
                                                'message' => THelper::t('something_wrong') . '<span style="display: none">' . (string) $response . '</span>'
                                            ]);
                                        }
                                    }

                                    return $this->renderAjax('pincode_sponsor', [
                                        'status' => 'danger',
                                        'message' => THelper::t('pin_has_not_been_created')
                                    ]);
                                } else {
                                    return $this->renderAjax('pincode_sponsor', [
                                        'status' => 'danger',
                                        'message' => THelper::t('partner_not_found')
                                    ]);
                                }
                            }
                        } else {
                            if ($model->pinMode === '0') {
                                $result = api\Pin::create($model->product, $this->user->id);

                                if ($result) {
                                    Yii::$app->session->setFlash('success', THelper::t('pin_has_been_created') . ': "' . $result . '"');
                                } else {
                                    Yii::$app->session->setFlash('danger', THelper::t('pin_has_not_been_created'));
                                }

                                return $this->refresh();
                            }
                        }
                    }
                break;
            }
        }



        $allProductGroups = self::getProductGroupList();
        $allProducts = $this->getAllProducts();

        ArrayHelper::multisort($allProducts,'sorting',SORT_ASC);

        $autoBonusArray = explode(' ', THelper::t('auto_bonus'));
        $autoBonus = array_shift($autoBonusArray) . '<br />' . implode(' ', $autoBonusArray);

        $propertyBonusArray = explode(' ', THelper::t('property_bonus'));
        $propertyBonus = array_shift($propertyBonusArray) . '<br />' . implode(' ', $propertyBonusArray);


        $infoAdmins = api\User::admins();

        $listWarehouse = [];
        $lang = Yii::$app->language;
        foreach($infoAdmins as $item){
            if(!empty($item->warehouseName->{$lang})){
                $listWarehouse[$item->id] = $item->warehouseName->{$lang};
            }
        }

        asort($listWarehouse);

        return $this->render('index', [
            'data'              => api\transactions\Withdrawal::all($this->user->id),
            'user'              => $this->user,
            'model'             => $model,
            'productPrices'     => json_encode(ArrayHelper::map($allProducts, 'idInMarket', 'price')),
            'productsSelect'    => ArrayHelper::map($allProducts, 'idInMarket', 'productName'),
            'productGroupsSelect'    => ArrayHelper::map($allProductGroups, 'key', 'name'),
            'pinModeSelect'     => [
                THelper::t('create_pin'),
                THelper::t('partner_payment')
            ],
            'autoBonus'         => $autoBonus,
            'propertyBonus'     => $propertyBonus,
            'listWarehouse'     => $listWarehouse,
        ]);
    }


    public function actionGetProductGroup()
    {
        if (Yii::$app->request->isPost) {
            $groupId = Yii::$app->request->post('group_id');
            $subGroupId = Yii::$app->request->post('sub_group_id');
            $products = self::getProductGroupByItsId($groupId)['products'];

            if (is_array($products) && $subGroupId) {
                $products = !empty($products[$subGroupId]) ? $products[$subGroupId] : null;
            }

            $products = array_map(function ($product){
                $productNew = api\Product::get($product);

                return !empty($productNew->idInMarket) ? $productNew->idInMarket : '';
            }, $products);

            $allProducts = $this->getAllProducts();
            $allProducts = ArrayHelper::map($allProducts, 'idInMarket', 'productName');

            Yii::$app->response->format = Response::FORMAT_JSON;

            return array_intersect_key($allProducts, array_flip($products));
        }

        return null;
    }

    public function actionGetProductSubGroup()
    {
        if (Yii::$app->request->isPost) {
            $groupId = (int)Yii::$app->request->post('group_id');
            $products = self::getProductGroupByItsId($groupId)['products'];
            $subGroups = [];

            if (is_array($products) && ArrayHelper::isAssociative($products)) {
                foreach ($products as $subGroupKey => $subGroupProducts) {
                    $subGroups[$subGroupKey] = THelper::t($subGroupKey);
                }
            }

            Yii::$app->response->format = Response::FORMAT_JSON;

            return $subGroups;
        }

        return null;
    }


    /**
     * @param $pin
     * @return null|string
     */
    public function pinCheck($pin)
    {
        if ($pin && $pin->activated && !empty($pin->userId) && $pin->userId === $this->user->id) {
            return $this->renderAjax('pincode_sponsor', [
                'status' => 'warning',
                'message' => THelper::t('this_pin_is_applied_to_you_automatically')
            ]);
        } elseif ($pin && $pin->activated && (empty($pin->userId) || $pin->userId !== $this->user->id)) {
            return $this->renderAjax('pincode_sponsor', [
                'status' => 'warning',
                'message' => THelper::t('this_pin_is_used_before')
            ]);
        }

        return null;
    }

    public function actionPoints()
    {
        $url = Yii::$app->params['apiAddress'] . 'transactions/points/' . $this->user->id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $options = PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0;
        $response = json_decode($response, false, 512, $options);

        return $this->render('points', [
            'user' => $response
        ]);
    }

    public function actionOperations()
    {
        $url = Yii::$app->params['apiAddress'] . 'transactions/money/' . $this->user->id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $options = PHP_INT_SIZE < 8 ? JSON_BIGINT_AS_STRING : 0;
        $response = json_decode($response, false, 512, $options);

        return $this->render('operations', [
            'user' => $response,
            'currentUser' => $this->user
        ]);
    }

    /**
     * Vouchers action
     *
     * @return string
     */
    public function actionVouchers()
    {
        return $this->renderAjax('vouchers', [
            'vouchers' => api\Voucher::get($this->user->id)
        ]);
    }

    /**
     * Changes autoExtensionBS
     */
    public function actionAutoExtensionBS()
    {
        $response['success'] = false;

        if (Yii::$app->request->isAjax) {
            $autoExtensionBS = Yii::$app->request->get('autoExtensionBS');
            $response['success'] = api\User::update($this->user->accountId, [
                'autoExtensionBS' => $autoExtensionBS
            ]);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $response;
    }

    public function actionCheckPincodeWellness()
    {
        $request = Yii::$app->request->post();

        return $this->checkPinWellnessWarehouse($request['pincode']);
    }


    /**
     * @param $pin
     * @return bool
     */
    public function checkPinWellnessWarehouse($pin)
    {
        $response = false;

        if(!empty($pin)) {
            $infoPincode = Pin::checkPin($pin);
            $pinHasErrors = $this->pinCheck($infoPincode);

            $infoAllProduct = ArrayHelper::index(api\Product::all(),'product');

            if(!empty($infoPincode->product) && !empty($infoAllProduct[$infoPincode->product]->productSet) && !$pinHasErrors){
                $response = true;
            }
        }

        return $response;
    }


    /**
     * Withdrawal action
     *
     * @return string
     */
    public function actionWithdrawal()
    {
        $cardForm = new CardForm();
        $cardForm->userEmail = $this->user->email;
        $cardForm->moneys = $this->user->moneys;
        
        $availableCards[] = [
            'key'   =>  '',
            'value' =>  THelper::t('select_type'),
            'card'  =>  '',
        ];

        if(!empty($this->user->cards)){
            foreach($this->user->cards as $k => $item){
                if(!empty($item->card_value)){
                    $availableCards[] = [
                        'key'   =>  $item->card_type,
                        'value' =>  THelper::t($item->card_label),
                        'card'  =>  $item->card_value,
                    ];
                }

            }
        }
        
        if (Yii::$app->request->isPost && $cardForm->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($cardForm);
            } else {
                $card = new \stdClass();
                if ($cardForm->type == CardForm::TYPE_2) {
                    $card->number = $cardForm->number;
                    $card->holder = $cardForm->holder;
                    $card->type = $cardForm->type;
                    $card->system = $cardForm->system;
                    $card->expiration = $cardForm->expirationMonth . '/' . $cardForm->expirationYear;
                } else {
                    $card->type = $cardForm->type;
                    $card->number = $cardForm->number;
                }

                $data = [
                    'iduser' => $this->user->id,
                    'amount' => $cardForm->amount
                ];

                if ($card) {
                    $data['card'] = $card;
                }

                //***********this is duplicated check for just in case...*******************************
                if ((float)$cardForm->amount > $this->user->moneys) {
                    Yii::$app->session->setFlash('danger', THelper::t('not_enough_money'));
                    return $this->redirect('/' . Yii::$app->language . '/business/finance');
                }

                if ((float)$cardForm->amount < 0) {
                    Yii::$app->session->setFlash('danger', THelper::t('withdrawal_can_not_be_negative'));
                    return $this->redirect('/' . Yii::$app->language . '/business/finance');
                }
                //***************************************************************************************

                if (api\transactions\Withdrawal::send($data)) {
                    Yii::$app->session->setFlash('success', THelper::t('transaction_is_successful'));
                } else {
                    Yii::$app->session->setFlash('danger', THelper::t('something_wrong'));
                }

                $this->redirect('/' . Yii::$app->language . '/business/finance');
            }
        }

        $yearStart = gmdate('Y', time());
        $yearFinish = $yearStart + 10;
        $yearsRange = range($yearStart, $yearFinish);
        foreach ($yearsRange as $key => $year) {
            $years[substr($year, 2)] = $year;
        }

        return $this->renderAjax('withdrawal', [
            'model' => $cardForm,
            'types' => CardForm::getTypes(),
            'systems' => CardForm::getSystems(),
            'months' => ['01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12'],
            'years' => $years,
            'availableCards' => $availableCards
        ]);
    }

    public function actionPincodeHistory()
    {
        return $this->renderAjax('pincode_history', [
            'pins' => api\PinsHistory::get($this->user->id)
        ]);
    }

    public function actionPincodeCancel($pin)
    {
        $pin = Pin::checkPin($pin);

        if ($pin) {
            $pin->used = true;
            $pin->isDelete = true;
            $pin->isActivate = true;

            $pin->save();
        }
    }

    /**
     * @param $pin
     * @param null $number
     * @return mixed
     */
    public function actionPincodeInfo($pin, $number = null)
    {
        $pin = Pin::checkPin($pin);
        $date = new DateTime($pin->order->date_create);

        $receipt = $number ?: '';
        $paymentGateway = 0;
        $quantity = $pin->order->qty;
        $price = $pin->price;
        $subTotal = $price * $quantity;
        $total = $subTotal + $paymentGateway;

        if (!empty($pin->userId)) {
            $user = api\User::get($pin->userId);
        }

        $userData = [
            'customerNo' => THelper::t('n/a'),
            'clientFullName' => THelper::t('n/a'),
        ];

        if (!empty($user)) {
            $userData = [
                'customerNo' => $user->accountId,
                'clientFullName' => $user->firstName . ' ' . $user->secondName,
            ];
        }

        $data = [
            'date' => $date->format('d/m/Y'),
            'quantity' => $quantity,
            'price' => $price,
            'productName' => $pin->productName,
            'paymentGateway' => $paymentGateway,
            'subTotal' => $subTotal,
            'total' => $total,
            'receipt' => $receipt,
            'currency' => '€'
        ] + $userData;

        $template = 'order';

        if ($this->user->countryCode === 'uk') {
            $template = 'invoice';
        }

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $this->renderPartial('invoices/' . $template, $data),
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => THelper::t('order_information') . THelper::t('number') . $receipt],
            'filename' => THelper::t('receipt_no') . $receipt .'.pdf',
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => [THelper::t('order_information') . THelper::t('number') . $receipt],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * @param $sponsor
     * @param string $formId
     * @return bool|string
     */
    public function checkSponsorActivity($sponsor, $formId = 'pincode')
    {
        $sponsor = api\User::get($sponsor);

        if (!empty($sponsor) && empty($sponsor->bs) && empty(Yii::$app->request->post('accepted'))) {
            return $this->renderAjax('pincode_sponsor', [
                'status' => 'warning',
                'message' => THelper::t('sponsor_activity_is_not_paid'),
                'buttons' => true,
                'form_id' => $formId
            ]);
        }

        return false;
    }
}