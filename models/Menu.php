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
                        'key'   =>  'sidebar_list_users',
                        'label' => THelper::t('sidebar_users'),
                        'url' => ['/business/user'],
                        'action' => 'user',
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
                    [
                        'key'   =>  'sidebar_users_career_history',
                        'label' => THelper::t('sidebar_users_career_history'),
                        'url' => ['/business/career-history'],
                        'action' => 'index',
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
                        'key'   =>  'sidebar_settings_warehouse',
                        'label' => THelper::t('sidebar_settings_warehouse'),
                        'url' => ['/business/setting/warehouse'],
                        'action' => 'warehouse',
                    ],
                    [
                        'key'   =>  'sidebar_repayment_amounts',
                        'label' => THelper::t('sidebar_repayment_amounts'),
                        'url' => ['/business/offsets-with-warehouses/repayment-amounts'],
                        'action' => 'repayment-amounts',
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
                'key'   =>  'sidebar_transactions',
                'label' => THelper::t('sidebar_transactions'),
                'url' => '#',
                'controller' => 'transactions',
                'items' => [
                    [
                        'key'   =>  'sidebar_users_money_transfer',
                        'label' => THelper::t('sidebar_users_money_transfer'),
                        'url' => ['/business/user/money-transfer'],
                        'action' => 'money-transfer',
                    ],
                    [
                        'key'   =>  'sidebar_users_money_transfer_log',
                        'label' => THelper::t('sidebar_users_money_transfer_log'),
                        'url' => ['/business/user/money-transfer-log'],
                        'action' => 'money-transfer-log',
                    ],
                    [
                        'key'   =>  'sidebar_users_pincode_cancel',
                        'label' => THelper::t('sidebar_users_pincode_cancel'),
                        'url' => ['/business/user/pincode-cancel'],
                        'action' => 'pincode-cancel',
                    ],
                    [
                        'key'   =>  'sidebar_payment_card',
                        'label' => THelper::t('sidebar_payment_card'),
                        'url' => ['/business/transactions/payment-card'],
                        'action' => 'payment-card',
                    ],
                    [
                        'key'   =>  'sidebar_withdrawal',
                        'label' => THelper::t('sidebar_withdrawal'),
                        'url' => ['/business/transactions/withdrawal'],
                        'action' => 'withdrawal',
                    ],
                    [
                        'key'   =>  'sidebar_users_commission',
                        'label' => THelper::t('sidebar_users_commission'),
                        'url' => ['/business/user/commission'],
                        'action' => 'commission',
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
                    [
                        'key'   =>  'sidebar_promotion_turkey_forum',
                        'label' => THelper::t('sidebar_promotion_turkey_forum'),
                        'url' => ['/business/promotion/turkey-forum'],
                        'action' => 'turkey-forum',
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
                        'key'   =>  'sidebar_report_admins',
                        'label' => THelper::t('sidebar_report_admins'),
                        'url' => ['status-sales/report-sales-admins'],
                        'action' => 'report-sales-admins',
                    ],

                    [
                        'key'   =>  'sidebar_consolidated_report',
                        'label' => THelper::t('sidebar_consolidated_report'),
                        'url' => ['status-sales/consolidated-report-sales'],
                        'action' => 'consolidated-report-sales',
                    ],

                    [
                        'key'   =>  'sidebar_consolidated_report_headadmin',
                        'label' => THelper::t('sidebar_consolidated_report_headadmin'),
                        'url' => ['status-sales/consolidated-report-sales-headadmin'],
                        'action' => 'consolidated-report-sales-headadmin',
                    ],

                    [
                        'key'   =>  'sidebar_product_set',
                        'label' => THelper::t('sidebar_product_set'),
                        'url' => ['status-sales/product-set'],
                        'action' => 'product-set',
                    ],

                    [
                        'key'   =>  'sidebar_report_for_cash',
                        'label' => THelper::t('sidebar_report_for_cash'),
                        'url' => ['status-sales/report-for-cash'],
                        'action' => 'product-set',
                    ],
                ]
            ],

            [
                'key'   =>  'sidebar_sale_report',
                'label' => THelper::t('sidebar_reports'),
                'url' => '#',
                'controller' => 'sale-report',
                'items' => [
                    [
                        'key'   =>  'sidebar_sale_wait',
                        'label' => THelper::t('sidebar_report_not_issued_sales'),
                        'url' => ['sale-report/info-wait-sale-by-user'],
                        'action' => 'info-wait-sale-by-user',
                    ],
                    [
                        'key'   =>  'sidebar_sale_country',
                        'label' => THelper::t('sidebar_report_sale_for_country'),
                        'url' => ['sale-report/info-sale-for-country'],
                        'action' => 'info-sale-for-country',
                    ],
                    [
                        'key'   =>  'sidebar_sale_country_warehouse',
                        'label' => THelper::t('sidebar_report_sale_for_country_warehouse'),
                        'url' => ['sale-report/info-sale-for-country-warehouse'],
                        'action' => 'info-sale-for-country-warehouse',
                    ],
                    [
                        'key'   =>  'sidebar_offsets_with_representative',
                        'label' => THelper::t('sidebar_offsets_with_representative'),
                        'url' => ['offsets-with-warehouses/offsets-with-representative'],
                        'action' => 'offsets-with-representative',
                    ],
                ]
            ],

            [
                'key'   =>  'sidebar_manufacturing_suppliers',
                'label' => THelper::t('sidebar_manufacturing_suppliers'),
                'url' => '#',
                'controller' => 'manufacturing-suppliers',
                'items' => [
                    [
                        'key'   =>  'sidebar_currency_rate',
                        'label' => THelper::t('sidebar_currency_rate'),
                        'url' => ['currency-rate/currency-rate'],
                        'action' => 'currency-rate',
                    ],
                    [
                        'key'   =>  'sidebar_suppliers_performers',
                        'label' => THelper::t('sidebar_suppliers_performers'),
                        'url' => ['manufacturing-suppliers/suppliers-performers'],
                        'action' => 'suppliers-performers',
                    ],
                    [
                        'key'   =>  'sidebar_parts_accessories',
                        'label' => THelper::t('sidebar_parts_accessories'),
                        'url' => ['manufacturing-suppliers/parts-accessories'],
                        'action' => 'parts-accessories',
                    ],
                    [
                        'key'   =>  'sidebar_interchangeable_goods',
                        'label' => THelper::t('sidebar_interchangeable_goods'),
                        'url' => ['manufacturing-suppliers/interchangeable-goods'],
                        'action' => 'interchangeable-goods',
                    ],
                    [
                        'key'   =>  'sidebar_composite_products',
                        'label' => THelper::t('sidebar_composite_products'),
                        'url' => ['manufacturing-suppliers/composite-products'],
                        'action' => 'composite-products',
                    ],
                    [
                        'key'   =>  'sidebar_parts_ordering',
                        'label' => THelper::t('sidebar_parts_ordering'),
                        'url' => ['manufacturing-suppliers/parts-ordering'],
                        'action' => 'parts-ordering',
                    ],
                    [
                        'key'   =>  'sidebar_execution_posting',
                        'label' => THelper::t('sidebar_execution_posting'),
                        'url' => ['submit-execution-posting/execution-posting'],
                        'action' => 'execution-posting',
                    ],
                    [
                        'key'   =>  'sidebar_history_cancellation_posting',
                        'label' => THelper::t('sidebar_history_cancellation_posting'),
                        'url' => ['submit-execution-posting/history-cancellation-posting'],
                        'action' => 'history-cancellation-posting',
                    ],
                    [
                        'key'   =>  'planning',
                        'label' => THelper::t('planning_purchasing'),
                        'url' => ['planning-purchasing/planning'],
                        'action' => 'planning',
                    ],

                ]
            ],


            [
                'key'   =>  'sending_waiting_parcel',
                'label' => THelper::t('sending_waiting_parcel'),
                'url' => '#',
                'controller' => 'sending-waiting-parcel',
                'items' => [
                    [
                        'key'   =>  'sending_and_waiting_parcel',
                        'label' => THelper::t('sending_waiting_parcel'),
                        'url' => ['sending-waiting-parcel/sending-waiting-parcel'],
                        'action' => 'sending-waiting-parcel',
                    ],
                    [
                        'key'   =>  'all_sending_and_waiting_parcel',
                        'label' => THelper::t('all_sending_waiting_parcel'),
                        'url' => ['sending-waiting-parcel/all-sending-waiting-parcel'],
                        'action' => 'all-sending-waiting-parcel',
                    ],

                ]
            ],


            [
                'key'   =>  'parts_accessories_in_warehouse',
                'label' => THelper::t('parts_accessories_in_warehouse'),
                'url' => '#',
                'controller' => 'parts-accessories-in-warehouse',
                'items' => [
                    [
                        'key'   =>  'in_warehouse',
                        'label' => THelper::t('in_warehouse'),
                        'url' => ['parts-accessories-in-warehouse/in-warehouse'],
                        'action' => 'in-warehouse',
                    ],
                    [
                        'key'   =>  'cancellation_warehouse',
                        'label' => THelper::t('cancellation_warehouse'),
                        'url' => ['parts-accessories-in-warehouse/cancellation-warehouse'],
                        'action' => 'cancellation-warehouse',
                    ],
                    [
                        'key'   =>  'all_cancellation_warehouse',
                        'label' => THelper::t('all_cancellation_warehouse'),
                        'url' => ['parts-accessories-in-warehouse/all-cancellation-warehouse'],
                        'action' => 'all-cancellation-warehouse',
                    ],
                ]
            ]

        ];
        
        return $itemMenu;
    }
    
}