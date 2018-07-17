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

    //--------------------b:newAdmin-----------------
    public function actionGoods() {
        //$goods = [];
        //$goods = Products::find()->orderBy(['productName'=>SORT_ASC])->all();
        $category_items = ProductsCategories::find()->all();
        $cat_items=[
            ['id'=>0,'name'=>'Все товары','rec_id'=>0],
        ];
        $index=1;
        foreach ($category_items as $item) {
            //array_push($cat_items,['id'=>$index,'rec_id'=>(string)$item['_id'],'name'=>$item->name]);
            array_push($cat_items,['id'=>$index,'rec_id'=>(string)($item->_id),'name'=>$item->name]);
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $p_id = $request->post('p_id');
        $product_action     = $request->post('product-action');
        $cur_product_action = $request->post('cur-product-action');
        //---------------------PRODUCT EDIT----------------------------------
        if (($product_action =='edit') || ($product_action =='add')) {
            if ($cur_product_action =='add') {
                $product = new Products();
            } else {
                $product = Products::findOne(['_id'=>new ObjectID($p_id)]);
            }

            $product->pinsVouchers=[];
            $product_type_items=[
                ['id'=>1,'name'=>'Простой'],
                ['id'=>2,'name'=>'Комплект']
            ];
            $complect_items=[];

            $complect_products=$product->products;
            if (!Empty($complect_products)) {
                $index=1;
                foreach ($complect_products as $item) {
                    array_push($complect_items,['id'=>$index,'rec_id'=>(string)$item['_id'],'name'=>$item['productName'],'cnt'=>$item['cnt']]);
                    //array_push($complect_items,['id'=>$index,'rec_id'=>'asdf'.$index,'name'=>$item['productName'],'cnt'=>$item['cnt']]);
                    $index++;
                }
            }
//            $complect_items=[
//                ['id'=>1,'rec_id'=>'asdfg1','name'=>'Goods -1','cnt'=>1],
//                ['id'=>2,'rec_id'=>'asdfg2','name'=>'Goods -2','cnt'=>2],
//            ];
            $complect_goods_add_items=[];
            $goods = Products::find()->asArray()->all();
            foreach ($goods as $item) {
                //array_push($complect_goods_add_items,['id'=>$item['_id'],'name'=>$item['productName']]);
                array_push($complect_goods_add_items,['id'=>(string)($item['_id']),'name'=>$item['productName']]);
            }
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
                'product_action'=>$product_action,
                'product'       => $product,
                'cat_items'     => $cat_items,
                'complect_items'=>$complect_items,
                'complect_goods_add_items'=>$complect_goods_add_items,
                'product_type_items'      => $product_type_items
            ]);
        }
        //----------------------PRODUCT SAVE------------------------------
        if ($product_action =='save') {
            try {
                $product_lang    =$request->post('product-lang');
                $product_name    =$request->post('product-name');
                $product_natural    =$request->post('product-natural');
                $product_category   =$request->post('product-category');
                $product_type=$request->post('product-type');
                $product_id         =$request->post('product-id');
                $product_idInMarket =$request->post('product-idInMarket');
                $product_price      =$request->post('product-price');
                $product_bonusMoney =$request->post('product-premia-direct') ?? 0;
                $product_bonusStart    =$request->post('product-bonus-start') ?? 0;
                $product_bonusStandart =$request->post('product-bonus-standart') ?? 0;
                $product_bonusVip        =$request->post('product-bonus-vip') ?? 0;
                $product_bonusInvestor   =$request->post('product-bonus-investor') ?? 0;
                $product_bonusInvestor_2 =$request->post('product-bonus-investor-2') ?? 0;
                $product_bonusInvestor_3 =$request->post('product-bonus-investor-3') ?? 0;

                $product_expirationPeriodValue =$request->post('product-expirationPeriod-value');

                $product_description =$request->post('product-description');

                $product_singlePurchase =$request->post('product-single-purchase');
                $product_productActive  =$request->post('product-active');
                $product_bonusPoints    =$request->post('product-bonus-points') ?? 0;
                $product_taxNds         =$request->post('product-tax-nds') ?? 0;
                $product_stock          =$request->post('product-stock') ?? 0;
                $product_complect_goods =$request->post('product-complect-goods') ?? [];
                $product_products=[];
                foreach ($product_complect_goods as $item) {
                    array_push($product_products,['_id'=>new ObjectID($item['rec_id']),'productName'=>$item['name'],'cnt'=>$item['cnt']]);
                    //array_push($product_products,['_id'=>($item['rec_id']),'productName'=>$item['name'],'cnt'=>$item['cnt']]);
                }
                if ($cur_product_action =='add') {
                    $product = new Products();
                    //---check product_id
                    $isProduct=Products::find()->Where(['product'=>$product_id])->one();
                    if (!empty($isProduct)) {
                        $mes='Error! product_id='.$product_id.' Product exist.Fill anothe code';
                        $res=['success'=>false,'message'=>$mes];
                        return $res;
                    }
                } else {
                    $product = Products::findOne(['_id'=>new ObjectID($p_id)]);
                }

                if ($product) {
                    $product->category_id =new ObjectID($product_category);
                    //$product->category_id =$product_category;
                    $product->productType =(int)$product_type;
                    $product->productName =$product_name;
                    $product->productNatural =(int)$product_natural;
                    $product->product     =(int)$product_id;
                    $product->products     =[];//--depends on productType
                    $product->idInMarket  =$product_idInMarket;
                    $product->price       =(float)round($product_price,2);
                    $product->bonusMoney  =(int)$product_bonusMoney;
                    $product->bonusMoneys=[
                        'elementary'=>(int)$product_bonusStart,
                        'standart'=>(int)$product_bonusStandart,
                        'vip'=>(int)$product_bonusVip,
                        'investor'=>(int)$product_bonusInvestor,
                        'investor_2'=>(int)$product_bonusInvestor_2,
                        'investor_3'=>(int)$product_bonusInvestor_3,

                        ];

                    $product->bonus=[
                        'point'=>(float)$product_bonusPoints,
                        'money'=>[
                            'elementary'=>(int)$product_bonusStart,
                            'standart'=>(int)$product_bonusStandart,
                            'vip'=>(int)$product_bonusVip,
                            'investor'=>(int)$product_bonusInvestor,
                            'investor_2'=>(int)$product_bonusInvestor_2,
                            'investor_3'=>(int)$product_bonusInvestor_3,
                        ],
                        'stock'=>[
                            'vipvip'=>100,
                            'wellness'=>100,
                            'vipcoin'=>100,

                        ]
                    ];
                    $product->expirationPeriod=['value'=>$product_expirationPeriodValue,'format'=>'month'];

                    $productDescription = !Empty($product->productDescription)?$product->productDescription:[];
                    $productDescription[$product_lang]=$product_description;
                    $product->productDescription      =$productDescription;

                    $productNameLangs=!Empty($product->productNameLangs)?$product->productNameLangs:[];
                    $productNameLangs[$product_lang]=$product_name;
                    $product->productNameLangs      =$productNameLangs;

                    $product->singlePurchase  =(int)$product_singlePurchase;
                    $product->productActive   =(int)$product_productActive;
                    $product->bonusPoints     =(float)$product_bonusPoints;
                    $product->productTax      =(int)$product_taxNds;
                    $product->stock           =(int)$product_stock;
                    if (!Empty($product_complect_goods)) {
                        $product->products=$product_products;
                    } else {
                        $product->products=[];
                    }
                    if ($product->productType==1) {
                        $product->products=[];
                    }
                    $product->save();
                    $mes='<strong>Saved!</strong> product_category='.$product_category.'>> _id:'.$p_id;
                    $res=['success'=>true,'message'=>$mes];
                } else {
                    $mes='<strong>Error!</strong> product_id='.$p_id;
                    $res=['success'=>false,'message'=>$mes];
                }
            } catch (\Exception $e) {
                $mes='Error! product_id='.$p_id.' error mes:'.$e->getMessage().' line='.$e->getLine();
                $res=['success'=>false,'message'=>$mes];
            }

            //$res=['success'=>true,'message'=>$mes];

            return $res;
        }
      ;
    }
    public function actionProductSaveImage() {
        $request = Yii::$app->request;
        $mes='done!';
        $file_name='a.jpg';
        if (Yii::$app->request->isPost) {
            $mes.=' post';
        }
        $res=['success'=>true,'filename'=>$file_name,'message'=>$mes];
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $res;
    }
    public function actionNameDescLang(){
        $request = Yii::$app->request;
        $product_lang=$request->post('product-lang');
        $p_id = $request->post('p_id');
        $mes='done!';
        $name_lang='';
        $desc_lang='';
        try {
            $product = Products::findOne(['_id'=>new ObjectID($p_id)]);
            if ($product) {
                $name_lang=$product['productNameLangs'][$product_lang];
                $desc_lang=$product['productDescription'][$product_lang];
            }
        } catch (\Exception $e) {
            $mes='Error! product_id='.$p_id.' error mes:'.$e->getMessage().' line='.$e->getLine();
        }
        $res=['success'=>true,'name_lang'=>$name_lang,'desc_lang'=>$desc_lang,'message'=>$mes];
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $res;
    }
    public function actionProductCheck() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $product_id = (int)$request->post('product-id');
        $isProduct=Products::find()->Where(['product'=>$product_id])->one();
        if (!empty($isProduct)) {
            $mes='Error! product_id='.$product_id.' Product exist.Fill anothe code';
            $res=['success'=>false,'message'=>$mes];
            return $res;
        }
        $mes='done';
        $res=['success'=>true,'message'=>$mes];
        return $res;
    }
    //---------------e:new Admin-------------
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