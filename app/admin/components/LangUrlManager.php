<?php
namespace app\components;

use yii\web\UrlManager;
use app\modules\settings\models\locale;
use app\models\api;
use yii\web\HttpException;

class LangUrlManager extends UrlManager
{
    public function init()
    {
        parent::init();

        $languages = api\dictionary\Lang::supported();
        $languagesToRoute = '';
        $languageCodes = [];

        if ($languages) {
            foreach ($languages as $language) {
                $languageCodes[] = $language->alpha2;
            }
            $languagesToRoute = implode('|', $languageCodes);
        }

        $this->rules = $this->buildRules([
            '' => '/login/login',
            '<language:(' . $languagesToRoute . ')>/<controller:\w+>/<id:\d+>'=>'<controller>/view',
            '<language:(' . $languagesToRoute . ')>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
            '<language:(' . $languagesToRoute . ')>/<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            '<language:(' . $languagesToRoute . ')>/<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
            '<language:(' . $languagesToRoute . ')>/<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>' => '<module>/<controller>/<action>/<id>',
            '<language:(' . $languagesToRoute . ')>/<module:\w+>/<controller:\w+>/<action:(\w|-)+>' => '<module>/<controller>/<action>',
        ]);
    }

    public function createUrl($params)
    {
        $lang = locale::getCurrent();

        if (! $lang) {
            throw new HttpException(500);
        }

        //Получаем сформированный URL(без префикса идентификатора языка)
        $url = parent::createUrl($params);
        
        //Добавляем к URL префикс - буквенный идентификатор языка

        if ($url == '/') {
            return '/' . $lang->prefix;
        } else {
            return '/' . $lang->prefix . $url;
        }

    }

}