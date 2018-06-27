<?php

namespace app\modules\settings\models;

use Yii;
use yii\base\Model;
use yii\base\Object;
use yii\db\Connection;
use yii\di\Instance;
use yii\db\Query;
use yii\db\QueryBuilder;
use app\models\api;

/**
 * Locale is the model behind the localization page.
 */
class Locale extends Model
{
    public $db = 'db';

    //Переменная, для хранения текущего объекта языка
    static $current = null;
   
    public function init()
    {
        $this->db = Instance::ensure($this->db, Connection::className());
        self::$current = self::getDefaultLang();
    }

    //Получение текущего объекта языка
    static function getCurrent()
    {
        if( self::$current === null ){
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

    //Установка текущего объекта языка и локаль пользователя
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);

        self::$current = ($language === null) ? self::getDefaultLang() : $language;

        if (self::$current) {
            Yii::$app->language = self::$current->alpha2;
        }
    }

    //Получения объекта языка по умолчанию
    static function getDefaultLang()
    {
        return api\dictionary\Lang::defaultLanguage();
    }

    //Получения объекта языка по буквенному идентификатору
    static function getLangByUrl($url = null)
    {
        $language = null;

        if ($url) {
            $languages = api\dictionary\Lang::supported();
            foreach ($languages as $lang) {
                if ($lang->alpha2 == $url) {
                    $language = $lang;
                }
            }
        }

        return $language;
    }

    static function getTranslate($title)
    {   
        $language_id = self::getCurrent()->id;
        $query = new Query;
        $query->select('translate')
            ->from('crm_translate_list')
            ->where(['lang' => $language_id,'key'=>$title]);
            
        $rows = $query->all();
        $command = $query->createCommand();
        $rows = $command->queryOne();
        return $rows['translate'];
    }
    function get_language_id()
    {
        return  self::getCurrent()->id;
    }
   
    function get_language_list()
    {
        $query = new Query;
        $query->select('*')
            ->from('crm_language_list');
        $rows = $query->all();
        $command = $query->createCommand();
        $rows = $command->queryAll();
        return $rows;
    }


    function add_language()
    {
        $db = $this->db;
        $db->createCommand()->insert('crm_language_list', [
            'title' => 'English',
            'prefix' => 'en',
            'tag' => 'En-en',
            'status' => 1
        ])->execute();
    }

    function insert_country_city() {
        $db = $this->db;
        $handle = fopen( $_SERVER['DOCUMENT_ROOT']. "/crm_country_list.csv", "r");
        while (($line = fgets($handle)) !== false) {
            $line = explode(';', $line);
            $db->createCommand()->insert('crm_country_list', [
                'id' => str_replace('"', '', $line[0]),
                'title' => str_replace('"', '', $line[1]),
                'status' => 1
            ])->execute();
        }
        fclose($handle);
        /*$handle = fopen( $_SERVER['DOCUMENT_ROOT']. "/crm_city_list.csv", "r");
        while (($city = fgets($handle)) !== false) {
            $city = explode(';', $city);
            $db->createCommand()->insert('crm_city_list', [
                'id' => str_replace('"', '', $city[0]),
                'title' => str_replace('"', '', $city[2]),
                'country_id' => str_replace('"', '', $city[1]),
                'state' => str_replace('"', '', $city[3]),
                'region' => str_replace('"', '', $city[4]),
                'status' => 1
            ])->execute();
        }
        fclose($handle);*/
    }

}