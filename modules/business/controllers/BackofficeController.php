<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\modules\business\models\AddNewsForm;
use app\modules\business\models\AddPromotionForm;
use app\modules\business\models\ConferenceForm;
use app\modules\business\models\MarketingForm;
use app\modules\business\models\CareerForm;
use app\modules\business\models\PriceForm;
use app\modules\business\models\CharityReportForm;
use app\modules\business\models\InstructionForm;
use app\modules\business\models\DocumentForm;
use app\modules\business\models\AgreementForm;
use app\modules\business\models\RegbuttonForm;
use app\modules\business\models\ResourceForm;
use app\modules\business\models\VideofsForm;
use Yii;
use app\models\api;
use app\models\User;
use yii\helpers\ArrayHelper;

class BackofficeController extends BaseController
{
    public function actionNews()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = api\dictionary\Lang::all();

        return $this->render('news', [
            'news' => api\News::all($language),
            'user' => $this->user,
            'language' => $language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : []
        ]);
    }

    public function actionNewsAdd()
    {
        $addNewsForm = new AddNewsForm();
        if (Yii::$app->request->isPost) {
            $addNewsForm->load(Yii::$app->request->post());

            $result = api\News::add([
                'title'  => $addNewsForm->title,
                'body'   => $addNewsForm->body,
                'lang'   => $addNewsForm->lang,
                'author' => $addNewsForm->author
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_news_add_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_news_add_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/news/?l=' . $addNewsForm->lang);
        } else {
            return $this->renderAjax('news_add', [
                'language' => Yii::$app->language,
                'addNewsForm' => $addNewsForm
            ]);
        }
    }

    public function actionNewsEdit()
    {
        $addNewsForm = new AddNewsForm();

        if (Yii::$app->request->isPost) {
            $addNewsForm->load(Yii::$app->request->post());

            $result = api\News::update([
                'id'     => $addNewsForm->id,
                'title'  => $addNewsForm->title,
                'body'   => $addNewsForm->body,
                'author' => $this->user->username
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_news_update_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_news_update_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/news/?l=' . $addNewsForm->lang);
        } else {
            if ($id = Yii::$app->request->get('id')) {
                $news = api\News::get($id);
                if ($news) {
                    $addNewsForm->id    = $news->id;
                    $addNewsForm->title = $news->title;
                    $addNewsForm->body  = $news->body;
                }
            }

            return $this->renderAjax('news_edit', [
                'language'    => Yii::$app->language,
                'addNewsForm' => $addNewsForm
            ]);
        }
    }

    public function actionNewsRemove()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;

        if ($id = Yii::$app->request->get('id')) {
            $result = api\News::remove([
                'id' => $id
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_news_remove_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_news_remove_error');
            }
        }

        $this->redirect('/' . Yii::$app->language . '/business/backoffice/news/?l=' . $language);
    }

    public function actionPromotion()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = api\dictionary\Lang::all();

        return $this->render('promotion', [
            'promotions' => api\Promotion::getForAdmin($language),
            'user' => $this->user,
            'language' => $language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : []
        ]);
    }

    public function actionPromotionAdd()
    {
        $addPromotionForm = new AddPromotionForm();
        if (Yii::$app->request->isPost) {
            $addPromotionForm->load(Yii::$app->request->post());

            $result = api\Promotion::add([
                'title'      => $addPromotionForm->title,
                'body'       => $addPromotionForm->body,
                'lang'       => $addPromotionForm->lang,
                'author'     => $addPromotionForm->author,
                'dateStart'  => $addPromotionForm->dateStart,
                'dateFinish' => $addPromotionForm->dateFinish
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_promotion_add_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_promotion_add_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/promotion/?l=' . $addPromotionForm->lang);
        } else {
            return $this->renderAjax('promotion_add', [
                'language' => Yii::$app->language,
                'addPromotionForm' => $addPromotionForm
            ]);
        }
    }


    public function actionPromotionRemove()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;

        if ($id = Yii::$app->request->get('id')) {
            $result = api\Promotion::remove([
                'id' => $id
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_promotions_remove_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_promotions_remove_error');
            }
        }

        $this->redirect('/' . Yii::$app->language . '/business/backoffice/promotion/?l=' . $language);
    }


    public function actionConference()
    {
        $request = Yii::$app->request;
        $conferenceForm = new ConferenceForm();

        if ($request->isPost) {
            $conferenceForm->load($request->post());

            $result = api\Conference::add([
                'title'  => $conferenceForm->title,
                'body'   => $conferenceForm->body,
                'author' => $conferenceForm->author,
                'lang'   => $conferenceForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_conference_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_conference_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/conference/?l=' . $conferenceForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $conference = api\Conference::get($language);

            $conferenceForm->author  = $this->user->username;

            if ($conference) {
                $conferenceForm->title = $conference->title;
                $conferenceForm->body  = $conference->body;
                $conferenceForm->lang  = $conference->lang;
            } else {
                $conferenceForm->lang  = $language;
            }

            return $this->render('conference', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'conferenceForm' => $conferenceForm
            ]);
        }
    }

    public function actionMarketing()
    {
        $request = Yii::$app->request;
        $marketingForm = new MarketingForm();

        if ($request->isPost) {
            $marketingForm->load($request->post());

            $result = api\Marketing::add([
                'title'  => $marketingForm->title,
                'body'   => $marketingForm->body,
                'author' => $marketingForm->author,
                'lang'   => $marketingForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_marketing_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_marketing_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/marketing/?l=' . $marketingForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $marketing = api\Marketing::get($language);

            $marketingForm->author = $this->user->username;

            if ($marketing) {
                $marketingForm->title = $marketing->title;
                $marketingForm->body  = $marketing->body;
                $marketingForm->lang  = $marketing->lang;
            } else {
                $marketingForm->lang  = $language;
            }

            return $this->render('marketing', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'marketingForm' => $marketingForm
            ]);
        }
    }

    public function actionCareer()
    {
        $request = Yii::$app->request;
        $careerForm = new CareerForm();

        if ($request->isPost) {
            $careerForm->load($request->post());

            $result = api\Career::add([
                'title'  => $careerForm->title,
                'body'   => $careerForm->body,
                'author' => $careerForm->author,
                'lang'   => $careerForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_career_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_career_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/career/?l=' . $careerForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $career = api\Career::get($language);

            $careerForm->author = $this->user->username;

            if ($career) {
                $careerForm->title = $career->title;
                $careerForm->body  = $career->body;
                $careerForm->lang  = $career->lang;
            } else {
                $careerForm->lang  = $language;
            }

            return $this->render('career', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'careerForm' => $careerForm
            ]);
        }
    }

    public function actionPrice()
    {
        $request = Yii::$app->request;
        $priceForm = new PriceForm();

        if ($request->isPost) {
            $priceForm->load($request->post());

            $result = api\Price::add([
                'title'  => $priceForm->title,
                'body'   => $priceForm->body,
                'author' => $priceForm->author,
                'lang'   => $priceForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_price_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_price_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/price/?l=' . $priceForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $price = api\Price::get($language);

            $priceForm->author = $this->user->username;

            if ($price) {
                $priceForm->title = $price->title;
                $priceForm->body  = $price->body;
                $priceForm->lang  = $price->lang;
            } else {
                $priceForm->lang  = $language;
            }

            return $this->render('price', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'priceForm' => $priceForm
            ]);
        }
    }

    public function actionCharity()
    {
        $request = Yii::$app->request;
        $charityReportForm = new CharityReportForm();

        if ($request->isPost) {
            $charityReportForm->load($request->post());

            $result = api\CharityReport::add([
                'title'  => $charityReportForm->title,
                'body'   => $charityReportForm->body,
                'author' => $charityReportForm->author,
                'lang'   => $charityReportForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_charity_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_charity_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/charity/?l=' . $charityReportForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $charity = api\CharityReport::get($language);

            $charityReportForm->author = $this->user->username;

            if ($charity) {
                $charityReportForm->title = $charity->title;
                $charityReportForm->body  = $charity->body;
                $charityReportForm->lang  = $charity->lang;
            } else {
                $charityReportForm->lang  = $language;
            }

            return $this->render('charity', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'charityReportForm' => $charityReportForm
            ]);
        }
    }

    public function actionInstruction()
    {
        $request = Yii::$app->request;
        $instructionForm = new InstructionForm();

        if ($request->isPost) {
            $instructionForm->load($request->post());

            $result = api\Instruction::add([
                'title'  => $instructionForm->title,
                'body'   => $instructionForm->body,
                'author' => $instructionForm->author,
                'lang'   => $instructionForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_instruction_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_instruction_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/instruction/?l=' . $instructionForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $instruction = api\Instruction::get($language);

            $instructionForm->author = $this->user->username;

            if ($instruction) {
                $instructionForm->title = $instruction->title;
                $instructionForm->body  = $instruction->body;
                $instructionForm->lang  = $instruction->lang;
            } else {
                $instructionForm->lang  = $language;
            }

            return $this->render('instruction', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'instructionForm' => $instructionForm
            ]);
        }
    }

    public function actionDocument()
    {
        $request = Yii::$app->request;
        $documentForm = new DocumentForm();

        if ($request->isPost) {
            $documentForm->load($request->post());

            $result = api\Document::add([
                'title'  => $documentForm->title,
                'body'   => $documentForm->body,
                'author' => $documentForm->author,
                'lang'   => $documentForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_document_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_document_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/document/?l=' . $documentForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $document = api\Document::get($language);

            $documentForm->author = $this->user->username;

            if ($document) {
                $documentForm->title = $document->title;
                $documentForm->body  = $document->body;
                $documentForm->lang  = $document->lang;
            } else {
                $documentForm->lang  = $language;
            }

            return $this->render('document', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'documentForm' => $documentForm
            ]);
        }
    }

    public function actionAgreement()
    {
        $request = Yii::$app->request;
        $agreementForm = new AgreementForm();

        if ($request->isPost) {
            $agreementForm->load($request->post());

            $result = api\Agreement::add([
                'title'  => $agreementForm->title,
                'body'   => $agreementForm->body,
                'author' => $agreementForm->author,
                'lang'   => $agreementForm->lang
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_agreement_save_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_agreement_save_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/agreement/?l=' . $agreementForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $agreement = api\Agreement::get($language);

            $agreementForm->author = $this->user->username;

            if ($agreement) {
                $agreementForm->title = $agreement->title;
                $agreementForm->body  = $agreement->body;
                $agreementForm->lang  = $agreement->lang;
            } else {
                $agreementForm->lang  = $language;
            }

            return $this->render('agreement', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'agreementForm' => $agreementForm
            ]);
        }
    }

    public function actionResource()
    {
        $request = Yii::$app->request;
        $resourceForm = new ResourceForm();

        if ($request->isPost) {
            $resourceForm->load($request->post());

            $data = [
                'id'  => $resourceForm->id,
                'title'  => $resourceForm->title,
                'body'   => $resourceForm->body,
                'author' => $this->user->username,
                'url'    => $resourceForm->url,
                'isVisible' => $resourceForm->isVisible
            ];

            $result = api\Resource::update($data);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_resource_update_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_resource_update_error');
            }

            $this->redirect('/' . Yii::$app->language . '/business/backoffice/resource/?l=' . $resourceForm->lang);
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $languages = api\dictionary\Lang::all();
            $resources = api\Resource::all($language, true);

            $resourceForms = [];
            foreach ($resources as $resource) {
                $resourceForm = new ResourceForm();
                $resourceForm->id = $resource->id;
                $resourceForm->title = $resource->title;
                $resourceForm->url = $resource->url;
                $resourceForm->body = $resource->body;
                $resourceForm->img = $resource->img;
                $resourceForm->isVisible = $resource->isVisible;
                $resourceForm->lang = $resource->lang;

                $resourceForms[] = $resourceForm;
            }

            return $this->render('resource', [
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
                'resourceForms' => $resourceForms
            ]);
        }
    }

    public function actionVideofs()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = api\dictionary\Lang::all();

        $videofsForm = new VideofsForm();
        $videofsForm->lang = $language;

        return $this->render('videofs', [
            'language' => $language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
            'videofsForm' => $videofsForm
        ]);
    }

    public function actionRegbutton()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $languages = api\dictionary\Lang::all();

        $regbuttonForm = new RegbuttonForm();
        $regbuttonForm->lang = $language;

        return $this->render('regbutton', [
            'language' => $language,
            'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : [],
            'regbuttonForm' => $regbuttonForm
        ]);
    }

    public function actionResourceOrders()
    {
        $request = Yii::$app->request;
        $orders = $request->post('ids');
        $language = $request->post('l');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        foreach ($orders as $key => $order) {
            api\Resource::update([
                'id' => $order,
                'order' => $key
            ]);
        }

        $resources = api\Resource::all($language, true);

        $resourceForms = [];
        foreach ($resources as $resource) {
            $resourceForm = new ResourceForm();
            $resourceForm->id = $resource->id;
            $resourceForm->title = $resource->title;
            $resourceForm->url = $resource->url;
            $resourceForm->body = $resource->body;
            $resourceForm->img = $resource->img;
            $resourceForm->isVisible = $resource->isVisible;
            $resourceForm->lang = $resource->lang;

            $resourceForms[] = $resourceForm;
        }

        return $this->renderAjax('_resource', [
            'language' => $language,
            'resourceForms' => $resourceForms
        ]);
    }

    public function actionResourceRemove()
    {
        $request = Yii::$app->request;

        $id = $request->get('id');
        $language = $request->get('l');

        $resource = api\Resource::get($id);

        if ($resource) {
            $result = api\Resource::remove([
                'id' => $id
            ]);

            if ($result) {
                Yii::$app->session->setFlash('success', 'backoffice_resource_remove_success');
            } else {
                Yii::$app->session->setFlash('danger', 'backoffice_resource_remove_error');
            }
        }

        $this->redirect('/' . Yii::$app->language . '/business/backoffice/resource/?l=' . $language);
    }

}