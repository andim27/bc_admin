<?php
namespace app\models;

//use app\components\ArrayInfoHelper;
//use MongoDB\BSON\ObjectID;
use app\components\THelper;

class PartsAccessoriesNone extends \yii2tech\embedded\mongodb\ActiveRecord
{
    protected static $typesUnit = [
        'pcs',
        'gr',
        'kg',
        'l',
        'cm',
        'm'
    ];

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'parts_accessories_none';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'execution_posting_id',
            'article_id',
            'title',
            'suppliers_performers_id',
            'parts_accessories_id',
            'list_none_component',
            'executed_none_complect',
            'date_create'
        ];
    }


    public static function getListUnit()
    {
        $typesUnit = self::$typesUnit;

        $list = [];

        foreach ($typesUnit as $item){
            $list[$item] = THelper::t($item);
        }

        return $list;
    }


}
