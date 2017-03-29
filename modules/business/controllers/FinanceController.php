<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use yii\base\Object;
use yii\web\Response;
use Yii;
use app\modules\business\models\FinanceForm;
use app\modules\business\models\CardForm;
use app\components\THelper;
use app\models\api;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class FinanceController extends BaseController
{
    public function actionIndex()
    {
        $model = new FinanceForm();
        $model->balance = $this->user->moneys;
        $model->userEmail = $this->user->email;

        if (Yii::$app->request->isPost) {
            switch ($_POST['finance']) {
                case 'withdrawal':
                    $url2 = Yii::$app->params['apiAddress'] . 'transactions/transferMoney/';
                    $ch2 = curl_init();
                    curl_setopt($ch2, CURLOPT_URL, $url2);
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch2, CURLOPT_POST, true);
                    curl_setopt($ch2, CURLOPT_POSTFIELDS, "idFrom=" . $this->user->id . "&idTo=000000000000000000000001&amount=" . $_POST['FinanceForm']['withdrawal'] . "");
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
                    $url3 = Yii::$app->params['apiAddress'] . 'sales/';
                    $ch3 = curl_init();
                    curl_setopt($ch3, CURLOPT_URL, $url3);
                    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch3, CURLOPT_POST, true);
                    curl_setopt($ch3, CURLOPT_POSTFIELDS, "iduser=" . $this->user->id . "&pin=" . $_POST['FinanceForm']['pincode'] . "");
                    $response3 = curl_exec($ch3);
                    curl_close($ch3);
                    if ($response3 == 'OK') {
                        Yii::$app->session->setFlash('success', THelper::t('buy_is_successful'));
                        return $this->refresh();
                    } else {
                        Yii::$app->session->setFlash('danger', THelper::t('something_wrong'));
                        return $this->refresh();
                    }
                    break;
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
            }
        }

        $products = api\Product::all();

        $autoBonusArray = explode(' ', THelper::t('auto_bonus'));
        $autoBonus = array_shift($autoBonusArray) . '<br />' . implode(' ', $autoBonusArray);

        $propertyBonusArray = explode(' ', THelper::t('property_bonus'));
        $propertyBonus = array_shift($propertyBonusArray) . '<br />' . implode(' ', $propertyBonusArray);

        return $this->render('index', [
            'data' => api\transactions\Withdrawal::all($this->user->id),
            'user' => $this->user,
            'model' => $model,
            'productPrices' => json_encode(ArrayHelper::map($products, 'product', 'price')),
            'productsSelect' => ArrayHelper::map($products, 'product', 'productName'),
            'autoBonus' => $autoBonus,
            'propertyBonus' => $propertyBonus,
        ]);
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
            'user' => $response
        ]);
    }

    /**
     * Vouchers action
     *
     * @return strin
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

    /**
     * Withdrawal action
     *
     * @return string
     */
    public function actionWithdrawal()
    {
        $cardForm = new CardForm();
        $cardForm->userEmail = $this->user->email;

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
                }

                $data = [
                    'iduser' => $this->user->id,
                    'amount' => $cardForm->amount
                ];

                if ($card) {
                    $data['card'] = $card;
                }

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
            'years' => $years
        ]);
    }

}