<?php

namespace app\components;
use yii\base\Widget;
use app\models\api;
use Yii;

class InstructionWidget extends Widget {

    public $image;

    public function init()
    {
        $currentController = Yii::$app->controller->id;
        $currentAction     = Yii::$app->controller->action->id;
        $currentModule     = Yii::$app->controller->module->id;

        if ($currentController == 'default' && $currentAction == 'index') {
            $this->image = api\Image::get('pageMainIntro', Yii::$app->language, true);
        } else if ($currentController == 'news' && $currentAction == 'index') {
            $this->image = api\Image::get('pageNewsIntro', Yii::$app->language, true);
        } else if ($currentController == 'information' && $currentAction == 'promotions') {
            $this->image = api\Image::get('pagePromotionsIntro', Yii::$app->language, true);
        } else if ($currentController == 'information' && $currentAction == 'timesheet') {
            $this->image = api\Image::get('pageConferenceIntro', Yii::$app->language, true);
        } else if ($currentController == 'information' && $currentAction == 'marketing') {
            $this->image = api\Image::get('pageMarketingPlanIntro', Yii::$app->language, true);
        } else if ($currentController == 'information' && $currentAction == 'carrier') {
            $this->image = api\Image::get('pageCareerPlanIntro', Yii::$app->language, true);
        } else if ($currentController == 'information' && $currentAction == 'price') {
            $this->image = api\Image::get('pagePriceListIntro', Yii::$app->language, true);
        } else if ($currentController == 'team' && $currentAction == 'genealogy') {
            $this->image = api\Image::get('pageGeneologyIntro', Yii::$app->language, true);
        } else if ($currentController == 'team' && $currentAction == 'geography') {
            $this->image = api\Image::get('pageGeographyIntro', Yii::$app->language, true);
        } else if ($currentController == 'team' && $currentAction == 'self') {
            $this->image = api\Image::get('pagePersonalPartnersIntro', Yii::$app->language, true);
        } else if ($currentController == 'carrier' && $currentAction == 'status') {
            $this->image = api\Image::get('pageCareerStatusIntro', Yii::$app->language, true);
        } else if ($currentController == 'carrier' && $currentAction == 'certificate') {
            $this->image = api\Image::get('pageCertificateIntro', Yii::$app->language, true);
        } else if ($currentController == 'statistic' && $currentAction == 'index') {
            $this->image = api\Image::get('pageStatisticIntro', Yii::$app->language, true);
        } else if ($currentController == 'sale' && $currentAction == 'index') {
            $this->image = api\Image::get('pageSaleIntro', Yii::$app->language, true);
        } else if ($currentController == 'finance' && $currentAction == 'index') {
            $this->image = api\Image::get('pageFinanceIntro', Yii::$app->language, true);
        } else if ($currentController == 'charity' && $currentAction == 'index') {
            $this->image = api\Image::get('pageCharityIntro', Yii::$app->language, true);
        } else if ($currentController == 'resource' && $currentAction == 'index') {
            $this->image = api\Image::get('pageResourceIntro', Yii::$app->language, true);
        } else if ($currentController == 'uploaded' && $currentAction == 'index') {
            $this->image = api\Image::get('pageUploadsIntro', Yii::$app->language, true);
        } else if ($currentController == 'setting' && $currentAction == 'profile') {
            $this->image = api\Image::get('pageProfileIntro', Yii::$app->language, true);
        } else if ($currentController == 'setting' && $currentAction == 'unioncell') {
            $this->image = api\Image::get('pageUnioncellIntro', Yii::$app->language, true);
        } else if ($currentController == 'setting' && $currentAction == 'passwords') {
            $this->image = api\Image::get('pagePasswordsIntro', Yii::$app->language, true);
        } else if ($currentController == 'setting' && $currentAction == 'alert') {
            $this->image = api\Image::get('pageAlertsIntro', Yii::$app->language, true);
        } else if ($currentController == 'notes' && $currentAction == 'index') {
            $this->image = api\Image::get('pageNotesIntro', Yii::$app->language, true);
        }
    }

    public function run()
    {
        return $this->render('instruction', [
            'image'             => $this->image ? trim($this->image->embedCode) ? $this->image : false : false,
            'currentController' => Yii::$app->controller->id,
            'currentAction'     => Yii::$app->controller->action->id,
            'currentModule'     => Yii::$app->controller->module->id,
        ]);
    }
}