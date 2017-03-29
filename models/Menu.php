<?php
namespace app\models;

use app\components\THelper;

Class Menu
{
    public static function getItems()
    {
        $itemMenu = [
            [
                'key'   =>  'sidebar_home',
                'label' => THelper::t('sidebar_home'),
                'url' => ['/business/default'],
                'controller' => 'default'
            ],
            [
                'key'   =>  'sidebar_users',
                'label' => THelper::t('sidebar_users'),
                'url' => '#',
                'controller' => 'user',
                'items' => [
                    [
                        'key'   =>  'sidebar_users',
                        'label' => THelper::t('sidebar_users'),
                        'url' => ['/business/user'],
                        'action' => 'user',
                    ],
                    [
                        'key'   =>  'sidebar_users_qualification',
                        'label' => THelper::t('sidebar_users_qualification'),
                        'url' => ['/business/user/qualification'],
                        'action' => 'qualification',
                    ],
                    [
                        'key'   =>  'sidebar_users_genealogy',
                        'label' => THelper::t('sidebar_users_genealogy'),
                        'url' => ['/business/user/genealogy'],
                        'action' => 'genealogy',
                    ],
                    [
                        'key'   =>  'sidebar_users_purchases',
                        'label' => THelper::t('sidebar_users_purchases'),
                        'url' => ['/business/user/purchase'],
                        'action' => 'purchase',
                    ],
                    [
                        'key'   =>  'sidebar_users_commission',
                        'label' => THelper::t('sidebar_users_commission'),
                        'url' => ['/business/user/commission'],
                        'action' => 'commission',
                    ],
                    [
                        'key'   =>  'sidebar_users_info',
                        'label' => THelper::t('sidebar_users_info'),
                        'url' => ['/business/user/info'],
                        'action' => 'info',
                    ],
                    [
                        'key'   =>  'sidebar_users_docs',
                        'label' => THelper::t('sidebar_users_docs'),
                        'url' => ['/business/user/docs'],
                        'action' => 'docs',
                    ],

                ]
            ],
            [
                'key'   =>  'sidebar_backoffice',
                'label' => THelper::t('sidebar_backoffice'),
                'url' => '#',
                'controller' => 'backoffice',
                'items' => [
                    [
                        'key'   =>  'sidebar_backoffice_news',
                        'label' => THelper::t('sidebar_backoffice_news'),
                        'url' => ['/business/backoffice/news'],
                        'action' => 'news',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_promotion',
                        'label' => THelper::t('sidebar_backoffice_promotion'),
                        'url' => ['/business/backoffice/promotion'],
                        'action' => 'promotion',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_conference',
                        'label' => THelper::t('sidebar_backoffice_conference'),
                        'url' => ['/business/backoffice/conference'],
                        'action' => 'conference',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_resource',
                        'label' => THelper::t('sidebar_backoffice_resource'),
                        'url' => ['/business/backoffice/resource'],
                        'action' => 'resource',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_marketing',
                        'label' => THelper::t('sidebar_backoffice_marketing'),
                        'url' => ['/business/backoffice/marketing'],
                        'action' => 'marketing',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_career',
                        'label' => THelper::t('sidebar_backoffice_career'),
                        'url' => ['/business/backoffice/career'],
                        'action' => 'career',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_price',
                        'label' => THelper::t('sidebar_backoffice_price'),
                        'url' => ['/business/backoffice/price'],
                        'action' => 'price',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_charity',
                        'label' => THelper::t('sidebar_backoffice_charity'),
                        'url' => ['/business/backoffice/charity'],
                        'action' => 'charity',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_instruction',
                        'label' => THelper::t('sidebar_backoffice_instruction'),
                        'url' => ['/business/backoffice/instruction'],
                        'action' => 'instruction',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_document',
                        'label' => THelper::t('sidebar_backoffice_document'),
                        'url' => ['/business/backoffice/document'],
                        'action' => 'document',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_agreement',
                        'label' => THelper::t('sidebar_backoffice_agreement'),
                        'url' => ['/business/backoffice/agreement'],
                        'action' => 'agreement',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_videofs',
                        'label' => THelper::t('sidebar_backoffice_videofs'),
                        'url' => ['/business/backoffice/videofs'],
                        'action' => 'videofs',
                    ],
                    [
                        'key'   =>  'sidebar_backoffice_regbutton',
                        'label' => THelper::t('sidebar_backoffice_regbutton'),
                        'url' => ['/business/backoffice/regbutton'],
                        'action' => 'regbutton',
                    ],
                ]
            ],
            [
                'key'   =>  'sidebar_setting',
                'label' => THelper::t('sidebar_settings'),
                'url' => '#',
                'controller' => 'setting',
                'items' => [
                    [
                        'key'   =>  'sidebar_settings_admin',
                        'label' => THelper::t('sidebar_settings_admin'),
                        'url' => ['/business/setting/admin'],
                        'action' => 'admin',
                    ],
                    [
                        'key'   =>  'sidebar_settings_admin_rules',
                        'label' => THelper::t('sidebar_settings_admin_rules'),
                        'url' => ['/business/setting/admin-rules'],
                        'action' => 'admin-rules',
                    ],
                    [
                        'key'   =>  'sidebar_settings_translation',
                        'label' => THelper::t('sidebar_settings_translation'),
                        'url' => ['/business/setting/translation'],
                        'action' => 'translation',
                    ],
                    [
                        'key'   =>  'sidebar_settings_password',
                        'label' => THelper::t('sidebar_settings_password'),
                        'url' => ['/business/setting/password'],
                        'action' => 'password',
                    ],
                    [
                        'key'   =>  'sidebar_settings_whitelabel',
                        'label' => THelper::t('sidebar_settings_whitelabel'),
                        'url' => ['/business/setting/whitelabel'],
                        'action' => 'whitelabel',
                    ],
                ]
            ],
            [
                'key'   =>  'sidebar_lottery',
                'label' => THelper::t('sidebar_lottery'),
                'url' => '#',
                'controller' => 'lottery',
                'items' => [
                    [
                        'key'   =>  'sidebar_lottery_index',
                        'label' => THelper::t('sidebar_lottery_index'),
                        'url' => ['/business/lottery/index'],
                        'action' => 'index',
                    ],
                    [
                        'key'   =>  'sidebar_lottery_rules',
                        'label' => THelper::t('sidebar_lottery_rules'),
                        'url' => ['/business/lottery/rules'],
                        'action' => 'rules',
                    ],
                ]
            ],
            [
                'key'   =>  'sidebar_promotion',
                'label' => THelper::t('sidebar_promotion'),
                'url' => '#',
                'controller' => 'promotion',
                'items' => [
                    [
                        'key'   =>  'sidebar_promotion_travel',
                        'label' => THelper::t('sidebar_promotion_travel'),
                        'url' => ['/business/promotion/travel'],
                        'action' => 'travel',
                    ],
                ]
            ],
            [
                'key'   =>  'sidebar_cash_order',
                'label' => THelper::t('sidebar_cash_order'),
                'url' => '#',
                'controller' => 'status-sales',
                'items' => [
                    [
                        'key'   =>  'sidebar_order',
                        'label' => THelper::t('sidebar_order'),
                        'url' => ['status-sales/search-sales'],
                        'action' => 'search-sales',
                    ],
                    [
                        'key'   =>  'sidebar_report',
                        'label' => THelper::t('sidebar_report'),
                        'url' => ['status-sales/report-sales'],
                        'action' => 'report-sales',
                    ],
                    [
                        'key'   =>  'sidebar_product_set',
                        'label' => THelper::t('sidebar_product_set'),
                        'url' => ['status-sales/product-set'],
                        'action' => 'report-sales',
                    ],
                ]
            ]
        ];
        
        return $itemMenu;
    }
    
}