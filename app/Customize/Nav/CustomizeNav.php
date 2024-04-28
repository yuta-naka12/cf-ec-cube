<?php

namespace Customize\Nav;

use Eccube\Common\EccubeNav;

class CustomizeNav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'product' => [
                'children' => [
                    'brand' => [
                        'name' => 'ブランド管理',
                        'url' => 'admin_product_brand',
                    ],
                    'maker' => [
                        'name' => 'メーカー管理',
                        'url' => 'admin_product_maker',
                    ],
                    'gift' => [
                        'name' => 'ギフト管理',
                        'url' => 'admin_product_gift',
                    ],
                    'icon' => [
                        'name' => 'アイコン管理',
                        'url' => 'admin_product_icon',
                    ],
                    'supplier' => [
                        'name' => '仕入先管理',
                        'url' => 'admin_product_supplier',
                    ],
                    'extension_item' => [
                        'name' => '商品拡張項目管理',
                        'url' => 'admin_product_extension_item',
                    ],
                    'event' => [
                        'name' => 'イベント管理',
                        'url' => 'admin_product_event',
                    ],
                    'genre' => [
                        'name' => 'ジャンル管理',
                        'url' => 'admin_product_genre',
                    ],
                ],
            ],
            'order' => [
                'children' => [
                    'search_group' => [
                        'name' => '注文検索パターン設定',
                        'url' => 'admin_setting_system_masterdata_view',
                        'param' => ['entity' => 'Eccube-Entity-Master-OrderStatus'],
                    ],
                    'csv_order' => [
                        'name' => '受注CSV登録',
                        'url' => 'admin_order_csv_import',
                    ],
                    'instruction_shipping' => [
                        'name' => '出荷指示日一括更新',
                        'url' => 'admin_order_instruction_shipping',
                    ],
                ]
            ],
            'customer' => [
                'children' => [
                    'rank' => [
                        'name' => '会員区分管理',
                        'url' => 'admin_setting_system_master_rank',
                    ],
                    'sa_customer' => [
                        'name' => 'SA会員検索',
                        'url' => 'admin_sa_customer'
                    ],
                    'contact' => [
                        'name' => 'お問合せ',
                        'url' => 'admin_contact'
                    ],
                    'contact_subject' => [
                        'name' => 'お問合せ件名',
                        'url' => 'admin_contact_subject'
                    ],
                    'admin_point_history' => [
                        'name' => 'ポイント履歴',
                        'url' => 'admin_point_history'
                    ],
                    'apply_pamphlet' => [
                        'name' => 'パンフレット申込者',
                        'url' => 'admin_apply_pamphlet'
                    ],
                    'course_master' => [
                        'name' => 'コース管理',
                        'url' => 'admin_customer_courseMaster'
                    ],
                    'mtb_address' => [
                        'name' => '住所マスタ',
                        'url' => 'admin_master_mtb_address',
                    ],
                ]
            ],
            'gift_address' => [
                'name' => '贈答先管理',
                'icon' => 'fa fa-gift',
                'children' => [
                    'gift_address_list' => [
                        'name' => '贈答先一覧',
                        'url' => 'admin_gift_address_list',
                    ],
                    'gift_address_registration' => [
                        'name' => '贈答先登録',
                        'url' => 'admin_gift_address_registration',
                    ],
                ]
            ],
            'csvImport' => [
                'children' => [
                    'product_csv_import' => [
                        'name' => '商品CSV登録',
                        'url' => 'admin_product_csv_import'
                    ],
                    'category_csv_import' => [
                        'name' => 'カテゴリCSV登録',
                        'url' => 'admin_product_category_csv_import'
                    ],
                    'brand_csv_import' => [
                        'name' => 'ブランドCSV登録',
                        'url' => 'admin_product_brand_csv_import'
                    ],
                    'gift_csv_import' => [
                        'name' => 'ギフトCSV登録',
                        'url' => 'admin_product_gift_csv_import'
                    ],
                    'event_csv_import' => [
                        'name' => 'イベントCSV登録',
                        'url' => 'admin_product_event_csv_import'
                    ],
                    'genre_csv_import' => [
                        'name' => 'ジャンルCSV登録',
                        'url' => 'admin_product_genre_csv_import'
                    ],
                    'maker_csv_import' => [
                        'name' => 'メーカーCSV登録',
                        'url' => 'admin_product_maker_csv_import'
                    ],
                    'tag_csv_import' => [
                        'name' => 'タグCSV登録',
                        'url' => 'admin_product_tag_csv_import'
                    ],
                    'address_csv_import' => [
                        'name' => '住所CSV登録',
                        'url' => 'admin_mtb_address_csv_import'
                    ],
                ]
            ],
            'setting' => [
                'children' => [
                    'master_data' => [
                        'name' => 'マスターデータ管理',
                        'children' => [
                            'masterdata' => [
                                'name' => 'マスターデータ管理',
                                'url' => 'admin_setting_system_masterdata',
                            ],
                            'course' => [
                                'name' => 'コース管理',
                                'url' => 'admin_setting_system_master_course',
                            ],
                            'bulk_buying' => [
                                'name' => 'まとめ買いグループ管理',
                                'url' => 'admin_setting_system_master_bulk_buying',
                            ],
                            'pamphlet_master' => [
                                'name' => 'パンフレット管理',
                                'url' => 'admin_setting_system_master_pamphlet',
                            ],
                            'delivery_calendar' => [
                                'name' => '配送カレンダー管理',
                                'url' => 'admin_setting_system_master_delivery_calendar',
                            ],
                            'financial' => [
                                'name' => '金融機関管理',
                                'url' => 'admin_setting_system_master_financial',
                            ],
                            'settlement_type' => [
                                'name' => '決済種別',
                                'url' => 'admin_setting_system_master_settlement_type',
                            ],
                            'reason master' => [
                                'name' => 'admin.content.withdraw_management',
                                'url' => 'admin_withdraw_reason_item',
                            ],
                        ],
                    ],
                    'shop' => [
                        'children' => [
                            'purchase_group' => [
                                'name' => '購入グループ管理',
                                'url' => 'admin_setting_purchase_group',
                            ]
                        ]
                    ]
                ],
            ],
            'call_list' => [
                'name' => 'コールリスト',
                'url' => 'admin_call_list',
                'icon' => 'fa-cube',
            ],
        ];
    }
}
