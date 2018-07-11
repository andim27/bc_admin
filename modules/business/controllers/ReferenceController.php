<?php

namespace app\modules\business\controllers;

use app\components\THelper;
use app\controllers\BaseController;
use app\models\Langs;
use app\models\Products;
use app\models\ProductsCategories;
use app\modules\business\models\Career;
use app\modules\business\models\CareerAddForm;
use MongoDB\BSON\Binary;
use MongoDB\BSON\ObjectID;
use Yii;
use app\models\api;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\web\Response;
use app\models\ProductCategories;

class ReferenceController extends BaseController
{
    /**
     * @return string
     */
    public function actionCareer()
    {
        $careers = [];

        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = api\dictionary\Lang::all();

        $userFromSession = Yii::$app->session->get('user');

        if ($userFromSession) {
            $this->user = $this->user = api\User::get($userFromSession->accountId);
        }

        $brench = api\User::spilover($userFromSession->id);

       // hh( api\User::spilover($userFromSession->id));

        foreach (Career::find()->all() as $item) {
            $selfInvitedStatusInOneBranch = THelper::t('no', $language);
            $selfInvitedStatusInAnotherBranch = THelper::t('no', $language);

            foreach ($brench as $brenchItem) {
                if ($brenchItem->sponsor && $brenchItem->sponsor->_id === $userFromSession->id && $brenchItem->rank == $item->rank) {
                    if ($brenchItem->side === 1) {
                        $selfInvitedStatusInOneBranch = THelper::t('yes', $language);
                    } elseif ($brenchItem->side === 0) {
                        $selfInvitedStatusInAnotherBranch = THelper::t('yes', $language);
                    }
                }
            }

            $statusAvatarSrc = "/images/no-image.png";
            $statusCertificateSrc = "/images/no-image.png";

            if (!empty($item->status_avatar[$language])) {
                $statusAvatarSrc = "data:image/png;base64," .  base64_encode($item->status_avatar[$language]);
            }

            if (!empty($item->status_certificate[$language])) {
                $statusCertificateSrc = "data:image/png;base64," . base64_encode($item->status_certificate[$language]);
            }

            $careers[] = [
               //'certificate' => api\settings\Certificate::get(true),
               'id' => $item->_id,
               'rank' => $item->rank,
               'rank_image' => $statusAvatarSrc,
               'certificate_image' => $statusCertificateSrc,
               'rank_name' => THelper::t('rank_' . $item->rank, $language),
               'short_name' => !empty($item->short_name[$language]) ? $item->short_name[$language] : '-',
               'steps' => $item->steps,
               'time' => $item->time,
               'bonus' => $item->bonus,
               'lang' => $language,
               'self_invited_status_in_one_branch' => !empty($item->self_invited_status_in_one_branch) ? THelper::t('yes', $language) : THelper::t('no', $language),
               'self_invited_status_in_another_branch' => !empty($item->self_invited_status_in_another_branch) ? THelper::t('yes', $language) : THelper::t('no', $language),
               'self_invited_status_in_spillover' => !empty($item->self_invited_status_in_spillover[$language]) ? $item->self_invited_status_in_spillover[$language] : '-',
               'self_invited_number_in_spillover' => !empty($item->self_invited_number_in_spillover) ? $item->self_invited_number_in_spillover : '-',
            ];
        }

        return $this->render('career', [
            'careers' => $careers,
            'language' => $language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : []
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCareerAdd()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;

        $careerAddForm = new CareerAddForm();

        if (!Yii::$app->request->isPost) {
            return $this->renderAjax('career_add', [
                'language' => $language,
                'careerAddForm' => $careerAddForm
            ]);
        }

        $careerAddForm->load(Yii::$app->request->post());

        $career = new Career();

        $career->rank = (double)$careerAddForm->serialNumber;
        $career->steps = (int)$careerAddForm->steps;
        $career->time = (int)$careerAddForm->timeForAward;
        $career->bonus = (int)$careerAddForm->bonus;
        $career->self_invited_status_in_one_branch = (boolean)$careerAddForm->selfInvitedStatusInOneBranch;
        $career->self_invited_status_in_another_branch = (boolean)$careerAddForm->selfInvitedStatusInAnotherBranch;
        $career->self_invited_status_in_spillover = [$careerAddForm->lang => $careerAddForm->selfInvitedStatusInSpillover];
        $career->self_invited_number_in_spillover = $careerAddForm->selfInvitedNumberInSpillover;
        $career->short_name = [$careerAddForm->lang => $careerAddForm->shortName];

        $stringId = 'rank_' . $career->rank;

        $translation = Langs::find()->where(['countryId' => $careerAddForm->lang, 'stringId' => $stringId])->one();

        if ($translation) {
            $id = (string) new ObjectID($translation->_id);

            api\Lang::update(
                $id,
                $careerAddForm->lang,
                $stringId,
                $careerAddForm->statusName,
                "edited from 'reference/career'",
                $translation->originalStringValue
            );
        } else {
            api\Lang::add(
                $careerAddForm->lang,
                $stringId,
                $careerAddForm->statusName,
                "edited from 'reference/career'",
                ""
            );
        }

        $careerAddForm->statusAvatar = UploadedFile::getInstance($careerAddForm, 'statusAvatar');
        $careerAddForm->statusCertificate = UploadedFile::getInstance($careerAddForm, 'statusCertificate');

        //$careerAddForm->statusAvatar->saveAs('images/ranks/' . $stringId . '.png');

        if (!empty($careerAddForm->statusAvatar->tempName)) {
            $imageModel = new Binary(file_get_contents($careerAddForm->statusAvatar->tempName), Binary::TYPE_GENERIC);
            $career->status_avatar = [$careerAddForm->lang => $imageModel];
        }

        if (!empty($careerAddForm->statusCertificate->tempName)) {

            $imageModel = new Binary(file_get_contents($careerAddForm->statusCertificate->tempName), Binary::TYPE_GENERIC);
            $career->status_certificate = [$careerAddForm->lang => $imageModel];
        }

        $errorMessage = '';

        if (!$career->validate()) {
           // Yii::$app->response->format = Response::FORMAT_JSON;

           // return ["careeraddform-serialnumber" => [THelper::t('unique_field_required')]];
            foreach ($career->errors as $error) {
                $errorMessage .= THelper::t(is_array($error) ? $error[0] : $error);
            }

            Yii::$app->session->setFlash('danger', $errorMessage);

            return $this->redirect('/' . Yii::$app->language . '/business/reference/career?l=' . $careerAddForm->lang);
        }

        if ($career->save()) {
            Yii::$app->session->setFlash('success', THelper::t('career_add_success'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('career_add_error'));
        }

        return $this->redirect('/' . Yii::$app->language . '/business/reference/career?l=' . $careerAddForm->lang);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCareerEdit()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;

        $careerAddForm = new CareerAddForm();


            if (!Yii::$app->request->isPost) {
                if ($id = Yii::$app->request->get('id')) {
                    $career = Career::findOne($id['oid']);

                    if ($career) {
                        $careerAddForm->id = $id['oid'];
                        $careerAddForm->serialNumber = $career->rank;
                        $careerAddForm->steps = $career->steps;
                        $careerAddForm->timeForAward = $career->time;
                        $careerAddForm->bonus = $career->bonus;
                        $careerAddForm->shortName =  !empty($career->short_name[$language]) ? $career->short_name[$language] : '';

                        $statusAvatarSrc = "/images/no-image.png";

                        if (!empty($career->status_avatar[$language])) {
                            $statusAvatarSrc = "data:image/png;base64," .  base64_encode($career->status_avatar[$language]);
                        }

                        $statusCertificateSrc = "/images/no-image.png";

                        if (!empty($career->status_avatar[$language])) {
                            $statusCertificateSrc = "data:image/png;base64," .  base64_encode($career->status_certificate[$language]);
                        }

                        $careerAddForm->statusAvatar = $statusAvatarSrc;
                        $careerAddForm->statusCertificate = $statusCertificateSrc;
                        $careerAddForm->statusName = THelper::t('rank_' . $career->rank, $language);
                        $careerAddForm->selfInvitedStatusInOneBranch = (int)$career->self_invited_status_in_one_branch;
                        $careerAddForm->selfInvitedStatusInAnotherBranch = (int)$career->self_invited_status_in_another_branch;
                        $careerAddForm->selfInvitedStatusInSpillover = !empty($career->self_invited_status_in_spillover[$language]) ? $career->self_invited_status_in_spillover[$language] : '';
                        $careerAddForm->selfInvitedNumberInSpillover = $career->self_invited_number_in_spillover;
                    }

                    return $this->renderAjax('career_edit', [
                        'language'    => $language,
                        'careerAddForm' => $careerAddForm
                    ]);
                }
            }

            $careerAddForm->load(Yii::$app->request->post());

            $career = Career::findOne($careerAddForm->id);

            if ($career) {
                $career->rank = (double)$careerAddForm->serialNumber;
                $career->steps = (int)$careerAddForm->steps;
                $career->time = (int)$careerAddForm->timeForAward;
                $career->bonus = (int)$careerAddForm->bonus;
                $career->self_invited_status_in_one_branch = (boolean)$careerAddForm->selfInvitedStatusInOneBranch;
                $career->self_invited_status_in_another_branch = (boolean)$careerAddForm->selfInvitedStatusInAnotherBranch;
                $career->self_invited_status_in_spillover = [$careerAddForm->lang => $careerAddForm->selfInvitedStatusInSpillover];
                $career->self_invited_number_in_spillover = (int)$careerAddForm->selfInvitedNumberInSpillover;
                $career->short_name = [$careerAddForm->lang => $careerAddForm->shortName];

                $stringId = 'rank_' . $career->rank;

                $translation = Langs::find()->where(['countryId' => $careerAddForm->lang, 'stringId' => $stringId])->one();

                if ($translation) {
                    $id = (string) new ObjectID($translation->_id);

                    api\Lang::update(
                        $id,
                        $careerAddForm->lang,
                        $stringId,
                        $careerAddForm->statusName,
                        "edited from 'reference/career'",
                        $translation->originalStringValue
                    );
                } else {
                    api\Lang::add(
                        $careerAddForm->lang,
                        $stringId,
                        $careerAddForm->statusName,
                        "edited from 'reference/career'",
                        ""
                    );
                }

                $careerAddForm->statusAvatar = UploadedFile::getInstance($careerAddForm, 'statusAvatar');
                $careerAddForm->statusCertificate = UploadedFile::getInstance($careerAddForm, 'statusCertificate');

                if (!empty($careerAddForm->statusAvatar->tempName)) {
                    $imageModel = new Binary(file_get_contents($careerAddForm->statusAvatar->tempName), Binary::TYPE_GENERIC);
                    $career->status_avatar = [$careerAddForm->lang => $imageModel];
                }

                if (!empty($careerAddForm->statusCertificate->tempName)) {

                    $imageModel = new Binary(file_get_contents($careerAddForm->statusCertificate->tempName), Binary::TYPE_GENERIC);
                    $career->status_certificate = [$careerAddForm->lang => $imageModel];
                }

                if ($career->save()) {
                    Yii::$app->session->setFlash('success', THelper::t('career_edit_success'));
                } else {
                    $errors = '';


                    foreach ($career->getErrors() as $error) {
                        $errors .= $error[0];
                    }

                    Yii::$app->session->setFlash('danger', $errors ?: THelper::t('career_edit_error'));
                }
            }

            return $this->redirect('/' . Yii::$app->language . '/business/reference/career?l=' . $careerAddForm->lang);
    }

    /**
     * Remove
     */
    public function actionCareerRemove()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;

        if ($id = Yii::$app->request->get('id')) {
            $career = Career::findOne($id['oid']);

            if ($career) {
                $result = $career->delete();

                if ($result) {
                    Yii::$app->session->setFlash('success', 'career_remove_success');
                } else {
                    Yii::$app->session->setFlash('danger', 'career_remove_error');
                }
            }
        }

        $this->redirect('/' . Yii::$app->language . '/business/reference/career/?l=' . $language);
    }

    //--------------------newAdmin-----------------
    public function actionGoods() {
        //$goods = [];
        //$goods = Products::find()->orderBy(['productName'=>SORT_ASC])->all();
        $category_items = ProductsCategories::find()->all();
        $cat_items=[
            ['id'=>0,'name'=>'Все товары','rec_id'=>0],
        ];
        $index=1;
        foreach ($category_items as $item) {
            array_push($cat_items,['id'=>$index,'rec_id'=>(string)$item['_id'],'name'=>$item->name]);
            $index++;
        }

        $goods = Products::find()->asArray()->all();
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = api\dictionary\Lang::all();
        return $this->render('goods', [
            'category_items'=>$category_items,
            'cat_items' => $cat_items,
            'goods' => $goods,
            'language' => $language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : []
        ]);
    }
    public function actionCategoryChange() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $res=['success'=>true,'message'=>'All is ok'];
        try {
            $request = Yii::$app->request;
            $category_action = $request->post('category-action');
            $category_name = $request->post('category-name');
            $category_id   = $request->post('category-id');
            $cat_coll_name ='product_categories';
            $Categories=Yii::$app->mongodb->getCollection($cat_coll_name);
            if (!($Categories->name ==$cat_coll_name)) {
                $col_create_res =Yii::$app->mongodb->createCollection($cat_coll_name);
                $mes='Collection created='.$cat_coll_name;
            } else {
                //--exist collection--

                if ($category_action =='add') {
                    $Categories->insert(['name'=>$category_name]);
                    $mes='Added ='.$category_name;
                }
                if ($category_action =='edit') {
                    $rec=ProductsCategories::findOne(['_id'=>new ObjectID($category_id)]);
                    $rec->name=$category_name;
                    $rec->save();
                    $mes='Changed!';
                }

            }


            $res=['success'=>true,'message'=>$mes];
        } catch (\Exception $e) {
            $res=['success'=>false,'message' =>$e->getMessage().' code='.$e->getLine()];
        }

        return $res;
    }
    public function actionProductEdit() {
        $request = Yii::$app->request;
        $p_id = $request->post('p_id');
        $product_action = $request->post('product-action');
        //---------------------PRODUCT EDIT----------------------------------
        if ($product_action =='edit') {
            $product = Products::findOne(['_id'=>new ObjectID($p_id)]);
            $product->pinsVouchers=[];
            $category_items = ProductsCategories::find()->all();
            $cat_items=[
                ['id'=>0,'name'=>'??','rec_id'=>0],
            ];
            $index=1;
            foreach ($category_items as $item) {
                array_push($cat_items,['id'=>$index,'rec_id'=>(string)$item['_id'],'name'=>$item->name]);
                $index++;
            }
            return $this->renderAjax('product_edit', [
                'product' => $product,
                'cat_items' => $cat_items
            ]);
        }
        //----------------------PRODUCT SAVE------------------------------
        if ($product_action =='save') {
            try {
                $product_name    =$request->post('product-name');
                $product_category=$request->post('product-category');
                $product_id      =$request->post('product-id');
                $product_idInMarket =$request->post('product-idInMarket');
                $product_price      =$request->post('product-price');
                $product_bonusMoney =$request->post('product-premia-direct');
                $product_bonusStart    =$request->post('product-bonus-start');
                $product_bonusStandart =$request->post('product-bonus-standart');
                $product_bonusVip        =$request->post('product-bonus-vip');
                $product_bonusInvestor   =$request->post('product-bonus-investor');
                $product_bonusInvestor_1 =$request->post('product-bonus-investor-1');
                $product_bonusInvestor_2 =$request->post('product-bonus-investor-2');
                $product = Products::findOne(['_id'=>new ObjectID($p_id)]);
                if ($product) {
                    $product->category_id =$product_category;
                    $product->productName =$product_name;
                    $product->product     =$product_id;
                    $product->idInMarket  =$product_idInMarket;
                    $product->price       =$product_price;
                    $product->bonusMoney  =$product_bonusMoney;
                    //$product->bonuses=[];
                    $product->setAttribute('bonuses.start', $product_bonusStart);
                    $product->setAttribute('bonuses.standart', $product_bonusStandart);
                    //$product->bonuses['start']     =$product_bonusStart;
                    //$product->bonuses['standart']  =$product_bonusStandart;
                    //$product->bonuses['vip']       =$product_bonusVip;
                    //$product->bonuses['investor']    =$product_bonusInvestor;
                    //$product->bonuses->investor_1  =$product_bonusInvestor_1;
                    //$product->bonuses->investor_2  =$product_bonusInvestor_2;
                    $product->save();
                    $mes='Saved! product_category='.$product_category;
                } else {
                    $mes='Error! product_id='.$p_id;
                }
            } catch (\Exception $e) {
                $mes='Error! product_id='.$p_id.' line='.$e->getLine();
            }


            $res=['success'=>true,'message'=>$mes];
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $res;
        }
      ;
    }
    //---------------new Admin-------------
    public function actionComplects() {
        $complects = [];

        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = api\dictionary\Lang::all();
        return $this->render('complects', [
            'goods' => $complects,
            'language' => $language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : []
        ]);
    }
}