<?php


namespace Customize\Command\Seed;

use Customize\Entity\Master\CourseMaster;
use Customize\Entity\Product\ProductGenreDisplayMode;
use Customize\Entity\Product\ProductGift;
use Doctrine\ORM\EntityManager;
use Eccube\Command\PluginCommandTrait;
use Eccube\Entity\Csv;
use Eccube\Entity\Master\CsvType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\PurchasingGroup;
use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductClass;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\Master\PurchasingGroupRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductGenreDisplayModeRepository;

class ProductMaterDataSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:product-master-data』で実行
    protected static $defaultName = 'seed:product-master-data';

    private $entityManager;
    /**
     * @var CsvTypeRepository
     */
    protected $csvTypeRepository;
    /**
     * @var PurchasingGroupRepository
     */
    protected $purchasingGroupRepository;
    /**
     * @var ProductGenreDisplayModeRepository
     */
    protected $productGenreDisplayModeRepository;

    /**
     * @var ProductClassRepository
     */
    protected $productClassRepository;

    /**
     * ProductMasterDataSeeder constructor.
     *
     * @param CsvTypeRepository $csvTypeRepository
     * @param ProductGenreDisplayModeRepository $productGenreDisplayModeRepository
     *
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CsvTypeRepository $csvTypeRepository,
        ProductGenreDisplayModeRepository $productGenreDisplayModeRepository,
        PurchasingGroupRepository $purchasingGroupRepository,
        ProductClassRepository $productClassRepository
    ) {
        $this->entityManager = $entityManager;
        $this->csvTypeRepository = $csvTypeRepository;
        $this->purchasingGroupRepository = $purchasingGroupRepository;
        $this->productGenreDisplayModeRepository = $productGenreDisplayModeRepository;
        $this->productClassRepository = $productClassRepository;

        parent::__construct();
    }

    protected $csvTypes = [
        [
            'id' => 6,
            'name' => 'ブランドCSV',
            'sort_no' => 6,
            'items' => [
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'id',
                    'disp_name' => 'ID',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'name',
                    'disp_name' => 'ブランド',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'brand_name',
                    'disp_name' => 'ブランド名',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'brand_name_2',
                    'disp_name' => 'ブランド名2',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'link',
                    'disp_name' => 'リンク',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'image_url',
                    'disp_name' => '画像ファイル',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'comment',
                    'disp_name' => 'コメント',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'free_comment_1',
                    'disp_name' => 'フリーコメント1',
                    'sort_no' => 8,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductBrand',
                    'field_name' => 'free_comment_2',
                    'disp_name' => 'フリーコメント2',
                    'sort_no' => 9,
                ],
            ]
        ],
        [
            'id' => 7,
            'name' => '商品ギフトCSV',
            'sort_no' => 7,
            'items' => [
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGift',
                    'field_name' => 'id',
                    'disp_name' => 'ID',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGift',
                    'field_name' => 'name',
                    'disp_name' => 'ギフト',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGift',
                    'field_name' => 'gift_name',
                    'disp_name' => 'ギフト名',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGift',
                    'field_name' => 'comment',
                    'disp_name' => 'コメント',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGift',
                    'field_name' => 'note',
                    'disp_name' => '備考（内部用）',
                    'sort_no' => 4,
                ],
            ]
        ],
        [
            'id' => 8,
            'name' => 'メーカーCSV',
            'sort_no' => 8,
            'items' => [
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'id',
                    'disp_name' => 'ID',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'name',
                    'disp_name' => 'メーカー',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'maker_name',
                    'disp_name' => 'メーカー名',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'maker_name_2',
                    'disp_name' => 'メーカー名2',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'link',
                    'disp_name' => 'リンク',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'comment',
                    'disp_name' => 'コメント',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'free_comment_1',
                    'disp_name' => 'フリーコメント1',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductMaker',
                    'field_name' => 'free_comment_2',
                    'disp_name' => 'フリーコメント2',
                    'sort_no' => 8,
                ],
            ]
        ],
        [
            'id' => 9,
            'name' => 'タグCSV',
            'sort_no' => 9,
            'items' => [
                [
                    'entity_name' => 'Eccube\Entity\Tag',
                    'field_name' => 'id',
                    'disp_name' => 'ID',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => 'Eccube\Entity\Tag',
                    'field_name' => 'name',
                    'disp_name' => 'タグ名',
                    'sort_no' => 2,
                ],
            ]
        ],
        [
            'id' => 10,
            'name' => 'イベントCSV',
            'sort_no' => 10,
            'items' => [
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'id',
                    'disp_name' => 'ID',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'event_id',
                    'disp_name' => 'イベントID',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'name',
                    'disp_name' => 'イベント名',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'category_id',
                    'disp_name' => 'カテゴリーID',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'started_at',
                    'disp_name' => 'イベント開始日',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'ended_at',
                    'disp_name' => 'イベント終了日',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'link',
                    'disp_name' => 'リンク',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'keyword',
                    'disp_name' => 'キーワード',
                    'sort_no' => 8,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'display_position',
                    'disp_name' => 'イベント表示位置',
                    'sort_no' => 9,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'display_maximum_number',
                    'disp_name' => '表示最大数',
                    'sort_no' => 10,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'status',
                    'disp_name' => '状態',
                    'sort_no' => 11,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'comment',
                    'disp_name' => 'コメント',
                    'sort_no' => 12,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'free_comment_1',
                    'disp_name' => 'フリーコメント1',
                    'sort_no' => 13,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'free_comment_2',
                    'disp_name' => 'フリーコメント2',
                    'sort_no' => 14,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'free_comment_3',
                    'disp_name' => 'フリーコメント3',
                    'sort_no' => 15,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'free_comment_4',
                    'disp_name' => 'フリーコメント4',
                    'sort_no' => 16,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductEvent',
                    'field_name' => 'free_comment_5',
                    'disp_name' => 'フリーコメント5',
                    'sort_no' => 17,
                ],
            ]
        ],
        [
            'id' => 11,
            'name' => 'ジャンルCSV',
            'sort_no' => 11,
            'items' => [
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'id',
                    'disp_name' => 'ID',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'name',
                    'disp_name' => 'ジャンル',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'genre_hierarchy',
                    'disp_name' => 'ジャンル階層',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'genre_name',
                    'disp_name' => 'ジャンル名',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'genre_name_2',
                    'disp_name' => 'ジャンル名2',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'comment',
                    'disp_name' => 'コメント',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'display_mode_default',
                    'disp_name' => '商品表示モードデフォルト',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'free_space_top',
                    'disp_name' => 'フリースペース上部',
                    'sort_no' => 8,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'free_space_bottom',
                    'disp_name' => 'フリースペース下部',
                    'sort_no' => 9,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'status',
                    'disp_name' => '状態',
                    'sort_no' => 10,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductGenre',
                    'field_name' => 'ProductGenreDisplayMode',
                    'disp_name' => '商品表示モード',
                    'sort_no' => 11,
                ],
            ]
        ],
        [
            'id' => 12,
            'name' => '商品仕入先CSV',
            'sort_no' => 12,
            'items' => [
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'id',
                    'disp_name' => 'ID',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'name',
                    'disp_name' => '仕入先',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'supplier_name',
                    'disp_name' => '仕入先名',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'phone_number',
                    'disp_name' => '電話番号',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'fax',
                    'disp_name' => 'FAX',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'email',
                    'disp_name' => 'メールアドレス',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'postal_code',
                    'disp_name' => '郵便番号',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'address',
                    'disp_name' => '住所',
                    'sort_no' => 8,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'supplier_category_1',
                    'disp_name' => '仕入先区分１',
                    'sort_no' => 9,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => 'supplier_category_2',
                    'disp_name' => '仕入先区分２',
                    'sort_no' => 10,
                ],


                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => '',
                    'disp_name' => '商品コード',
                    'sort_no' => 11,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => '',
                    'disp_name' => '商品名',
                    'sort_no' => 12,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => '',
                    // product なかにはない
                    'disp_name' => '索引',
                    'sort_no' => 13,
                ],
                [
                    'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                    'field_name' => '',
                    'disp_name' => '大分類',
                    'sort_no' => 14,
                ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '中分類',
                //     'sort_no' => 15,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => 'サイトカテゴリ',
                //     'sort_no' => 16,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品名略称',
                //     'sort_no' => 17,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品配送グループ',
                //     'sort_no' => 18,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '送料計算用区分',
                //     'sort_no' => 19,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '詰込管理区分',
                //     'sort_no' => 20,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => 'リパック区分',
                //     'sort_no' => 21,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '加工区分',
                //     'sort_no' => 22,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '仕入先',
                //     'sort_no' => 23,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '量目',
                //     'sort_no' => 24,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '加工場所',
                //     'sort_no' => 25,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '調理方法',
                //     'sort_no' => 26,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '解凍区分',
                //     'sort_no' => 27,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '塩分',
                //     'sort_no' => 28,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => 'カロリー',
                //     'sort_no' => 29,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => 'アレルギー',
                //     'sort_no' => 30,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '原材料',
                //     'sort_no' => 31,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品説明',
                //     'sort_no' =>32,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品画像小',
                //     'sort_no' => 33,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品画像大',
                //     'sort_no' => 34,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品パッケージ画像',
                //     'sort_no' => 35,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品説明1',
                //     'sort_no' => 36,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '商品コメント2（補足説明）',
                //     'sort_no' => 37,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '販売種別',
                //     'sort_no' => 38,
                // ],
                // [
                //     'entity_name' => 'Customize\Entity\Product\ProductSupplier',
                //     'field_name' => '',
                //     'disp_name' => '備考',
                //     'sort_no' => 39,
                // ],

            ]
        ],
        [
            'id' => CsvType::CSV_TYPE_CUSTOMER,
            'name' => '会員マスタCSV',
            'sort_no' => 0,
            'items' => [
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'id',
                    'disp_name' => '会員番号',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'name01',
                    'disp_name' => '氏名（姓）',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'name02',
                    'disp_name' => '氏名（名）',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'kana01',
                    'disp_name' => '氏名カナ（姓）',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'kana02',
                    'disp_name' => '氏名カナ（名）',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'Sex',
                    'reference_field_name' => 'name',
                    'disp_name' => '性別',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'birth',
                    'disp_name' => '生年月日',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'postal_code',
                    'disp_name' => '郵便番号',
                    'sort_no' => 8,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'Pref',
                    'reference_field_name' => 'name',
                    'disp_name' => '都道府県',
                    'sort_no' => 9,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'addr01',
                    'disp_name' => '住所1',
                    'sort_no' => 10,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'addr02',
                    'disp_name' => '住所2',
                    'sort_no' => 11,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'addr03',
                    'disp_name' => '住所3',
                    'sort_no' => 12,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'phone_number',
                    'disp_name' => '電話番号',
                    'sort_no' => 13,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'sub_tel',
                    'disp_name' => '電話番号２',
                    'sort_no' => 14,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'fax',
                    'disp_name' => 'FAX',
                    'sort_no' => 15,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'note',
                    'disp_name' => 'コールコメント',
                    'sort_no' => 16,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'note_2',
                    'disp_name' => '注文コメント',
                    'sort_no' => 17,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'note_3',
                    'disp_name' => '配送コメント',
                    'sort_no' => 18,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'delivery_code_name',
                    'disp_name' => 'DCコード',
                    'sort_no' => 19,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'delivery_week_1',
                    'disp_name' => '配送週１',
                    'sort_no' => 20,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'delivery_week_2',
                    'disp_name' => '配送週２',
                    'sort_no' => 21,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'delivery_date',
                    'disp_name' => '曜日',
                    'sort_no' => 22,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'CourseMaster',
                    'reference_field_name' => 'name',
                    'disp_name' => 'コース',
                    'sort_no' => 23,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'map_page',
                    'disp_name' => '地図ページ',
                    'sort_no' => 24,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'entry_date',
                    'disp_name' => '登録日',
                    'sort_no' => 25,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'withdraw_date',
                    'disp_name' => '退会日',
                    'sort_no' => 26,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'WithdrawalReason',
                    'reference_field_name' => 'name',
                    'disp_name' => '退会理由',
                    'sort_no' => 27,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'Member',
                    'reference_field_name' => 'name',
                    'disp_name' => 'SAコード',
                    'sort_no' => 28,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'SettlementType',
                    'reference_field_name' => 'name',
                    'disp_name' => '決済種別',
                    'sort_no' => 29,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'delivery_type',
                    'disp_name' => '配送種別',
                    'sort_no' => 30,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'order_type_id',
                    'disp_name' => '注文種別',
                    'sort_no' => 31,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'admission_type_id',
                    'disp_name' => '入会種別',
                    'sort_no' => 32,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'initial_public_offering_id',
                    'disp_name' => '初回公募',
                    'sort_no' => 33,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'is_web_order',
                    'disp_name' => 'WEB注文',
                    'sort_no' => 34,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'customer_id',
                    'disp_name' => '顧客ID',
                    'sort_no' => 35,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'email',
                    'disp_name' => 'メール',
                    'sort_no' => 36,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'OrderTimeZone',
                    'reference_field_name' => 'name',
                    'disp_name' => '注文時間帯',
                    'sort_no' => 37,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'delivery_preferred_time',
                    'disp_name' => '約束時間',
                    'sort_no' => 38,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'CustomerRank',
                    'reference_field_name' => 'name',
                    'disp_name' => '会員区分',
                    'sort_no' => 39,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'unit_cost_rate',
                    'disp_name' => '単価掛率',
                    'sort_no' => 40,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'is_dm_subscription',
                    'disp_name' => 'DM購読',
                    'sort_no' => 41,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'is_dm_subscription_2',
                    'disp_name' => 'DM購読２',
                    'sort_no' => 42,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'call_list_public_date',
                    'disp_name' => 'コールリスト掲載日',
                    'sort_no' => 43,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'where_hear_about_this_site',
                    'disp_name' => 'どこで知りましたか',
                    'sort_no' => 44,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'company_name',
                    'disp_name' => '会社名',
                    'sort_no' => 45,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'bank_account_symbol',
                    'disp_name' => '貯金記号',
                    'sort_no' => 46,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'bank_account_number',
                    'disp_name' => '貯金番号',
                    'sort_no' => 47,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'bank_account_name',
                    'disp_name' => '名義人名',
                    'sort_no' => 48,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'bank_account_name_kana',
                    'disp_name' => '名義人名カナ',
                    'sort_no' => 49,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'bank_account_registration_date',
                    'disp_name' => '登録日',
                    'sort_no' => 50,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'first_buy_date',
                    'disp_name' => '初回購入日',
                    'sort_no' => 51,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'nonpayment_times',
                    'disp_name' => '未納回数',
                    'sort_no' => 52,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'no_consecutive_order_count',
                    'disp_name' => '連続休回数',
                    'sort_no' => 53,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'buy_times',
                    'disp_name' => '購入回数',
                    'sort_no' => 54,
                ],
                [
                    'entity_name' => Customer::class,
                    'field_name' => 'point',
                    'disp_name' => 'ポイント',
                    'sort_no' => 55,
                ],
            ]
        ],
        [
            'id' => CsvType::CSV_TYPE_PRODUCT,
            'name' => '商品マスタCSV',
            'sort_no' => 1,
            'items' => [
                [
                    'entity_name' => Product::class,
                    'field_name' => 'code',
                    'disp_name' => '商品コード',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'name',
                    'disp_name' => '商品名',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'product_index',
                    'disp_name' => '索引',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'unit',
                    'disp_name' => '単位',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'BroadCategory',
                    'reference_field_name' => 'name',
                    'disp_name' => '大分類',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'MiddleCategory',
                    'reference_field_name' => 'name',
                    'disp_name' => '中分類',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'Category',
                    'reference_field_name' => 'name',
                    'disp_name' => 'サイトカテゴリ',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'product_shortname',
                    'disp_name' => '商品名略称',
                    'sort_no' => 8,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'DeliveryCalculation',
                    'reference_field_name' => 'name',
                    'disp_name' => '送料計算用区分',
                    'sort_no' => 9,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'PackingManagement',
                    'reference_field_name' => 'name',
                    'disp_name' => '詰込管理区分',
                    'sort_no' => 10,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'Repack',
                    'reference_field_name' => 'name',
                    'disp_name' => 'リパック',
                    'sort_no' => 11,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'ProcessedProductCategory',
                    'reference_field_name' => 'name',
                    'disp_name' => '加工区分',
                    'sort_no' => 12,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'ProductSupplier',
                    'reference_field_name' => 'name',
                    'disp_name' => '仕入先',
                    'sort_no' => 13,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'weight',
                    'disp_name' => '量目',
                    'sort_no' => 14,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'processing_place',
                    'disp_name' => '加工場所',
                    'sort_no' => 15,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'cooking_method',
                    'disp_name' => '調理方法',
                    'sort_no' => 16,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'DecompressionMethod',
                    'reference_field_name' => 'name',
                    'disp_name' => '解凍区分',
                    'sort_no' => 17,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'salt_amount',
                    'disp_name' => '塩分',
                    'sort_no' => 18,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'calorie',
                    'disp_name' => 'カロリー',
                    'sort_no' => 19,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'allergy',
                    'disp_name' => 'アレルギー',
                    'sort_no' => 20,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'raw_materials',
                    'disp_name' => '原材料',
                    'sort_no' => 21,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'description_detail',
                    'disp_name' => '商品説明１',
                    'sort_no' => 22,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'sale_type',
                    'disp_name' => '販売種別',
                    'sort_no' => 25,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'note',
                    'disp_name' => '備考',
                    'sort_no' => 26,
                ],

            ]
        ],
        [
            'id' => 13,
            'name' => 'パンフレットマスタCSV',
            'sort_no' => 13,
            'items' => [
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'Product',
                    'reference_field_name' => 'code',
                    'disp_name' => '商品コード',
                    'sort_no' => 1,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'Category',
                    'reference_field_name' => 'name',
                    'disp_name' => 'カテゴリ名',
                    'sort_no' => 2,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'Product',
                    'reference_field_name' => 'name',
                    'disp_name' => '商品名',
                    'sort_no' => 3,
                ],
                [
                    'entity_name' => Product::class,
                    'field_name' => 'ProductSupplier',
                    'reference_field_name' => 'name',
                    'disp_name' => '仕入先',
                    'sort_no' => 4,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'BulkBuying',
                    'reference_field_name' => 'name',
                    'disp_name' => 'まとめ買いグループ名',
                    'sort_no' => 5,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'price',
                    'disp_name' => '通常価格',
                    'sort_no' => 6,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'cost',
                    'disp_name' => '原価（仕入価格）',
                    'sort_no' => 7,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'is_list_page',
                    'disp_name' => '一覧画面有無',
                    'sort_no' => 8,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'is_detail_page',
                    'disp_name' => '詳細画面有無',
                    'sort_no' => 9,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'purchase_limit',
                    'disp_name' => '購入制限数',
                    'sort_no' => 10,
                ],
                // 個別送料
                // 温度帯
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'SubscriptionPurchase',
                    'reference_field_name' => 'name',
                    'disp_name' => '定期購入品区分',
                    'sort_no' => 11,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'status',
                    'disp_name' => '状態',
                    'sort_no' => 12,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'ProductIcon1',
                    'reference_field_name' => 'name',
                    'disp_name' => 'アイコン１',
                    'sort_no' => 13,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'ProductIcon2',
                    'reference_field_name' => 'name',
                    'disp_name' => 'アイコン２',
                    'sort_no' => 14,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'ProductIcon3',
                    'reference_field_name' => 'name',
                    'disp_name' => 'アイコン３',
                    'sort_no' => 15,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'IntroduceGood',
                    'reference_field_name' => 'name',
                    'disp_name' => '紹介品区分',
                    'sort_no' => 16,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'stock',
                    'disp_name' => '在庫数',
                    'sort_no' => 17,
                ],
                // 在庫インポートフラグ
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'stock_type',
                    'disp_name' => '在庫扱いの種別',
                    'sort_no' => 18,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'create_date',
                    'disp_name' => '登録日',
                    'sort_no' => 19,
                ],
                [
                    'entity_name' => ProductClass::class,
                    'field_name' => 'update_date',
                    'disp_name' => '更新日',
                    'sort_no' => 20,
                ],
                // 在庫数
                // 小画像
                // タグ
            ],
        ],
    ];

    protected $purchasingGroups = [
        [
            'id' => 1,
            'name' => '共通（自社便・宅急便）',
            'sort_no' => 1,
        ],
        [
            'id' => 2,
            'name' => '産地直送品（同梱不可）',
            'sort_no' => 2,
        ],
        [
            'id' => 3,
            'name' => 'おせち',
            'sort_no' => 3,
        ],
    ];

    protected $genre_display_modes = [
        [
            'id' => 1,
            'name' => '一覧',
            'sort_no' => 1,
        ],
        [
            'id' => 2,
            'name' => '詳細一覧',
            'sort_no' => 2,
        ],
        [
            'id' => 3,
            'name' => 'ピックアップ（１列）',
            'sort_no' => 3,
        ],
        [
            'id' => 4,
            'name' => 'ピックアップ',
            'sort_no' => 4,
        ],
        [
            'id' => 5,
            'name' => 'サムネイル（１列）',
            'sort_no' => 5,
        ],
        [
            'id' => 6,
            'name' => 'サムネイル（２列）',
            'sort_no' => 6,
        ],
        [
            'id' => 7,
            'name' => 'サムネイル',
            'sort_no' => 7,
        ],
        [
            'id' => 8,
            'name' => 'リスト',
            'sort_no' => 8,
        ],
        [
            'id' => 9,
            'name' => 'ランキング',
            'sort_no' => 9,
        ],
        [
            'id' => 10,
            'name' => 'メーカー',
            'sort_no' => 10,
        ],
        [
            'id' => 11,
            'name' => '写真付き',
            'sort_no' => 11,
        ],
        [
            'id' => 12,
            'name' => '商品名',
            'sort_no' => 12,
        ],
        [
            'id' => 13,
            'name' => '一括購入',
            'sort_no' => 13,
        ],
        [
            'id' => 14,
            'name' => 'チェックリスト',
            'sort_no' => 14,
        ],
        [
            'id' => 15,
            'name' => 'サムネイル（１列購入ボタンなし）',
            'sort_no' => 15,
        ],
    ];

    use PluginCommandTrait;

    protected function configure()
    {
        $this
            ->setDescription('Load customize data fixtures to database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->csvTypes as $type) {
            $CsvType = $this->csvTypeRepository->find($type['id']);
            if ($CsvType) {
                $this->entityManager->remove($CsvType);
                $this->entityManager->flush();
            }
            // CSVタイプを保存
            $CssType = new CsvType();
            $CssType->setId($type['id']);
            $CssType->setName($type['name']);
            $CssType->setSortNo($type['sort_no']);
            $this->entityManager->persist($CssType);
            $this->entityManager->flush();
            // 項目を保存
            foreach ($type['items'] as $item) {
                $Csv = new Csv();
                $Csv->setCsvType($CssType);
                $Csv->setEntityName($item['entity_name']);
                $Csv->setFieldName($item['field_name']);
                $Csv->setReferenceFieldName($item['reference_field_name'] ?? null);
                $Csv->setDispName($item['disp_name']);
                $Csv->setSortNo($item['sort_no']);
                $Csv->setEnabled(1);
                $this->entityManager->persist($Csv);
                $this->entityManager->flush();
            }
        }

        foreach ($this->purchasingGroups as $purchasingGroup) {
            $hasPurchasingGroup = $this->purchasingGroupRepository->find($purchasingGroup['id']);
            if ($hasPurchasingGroup) {
                $this->entityManager->remove($hasPurchasingGroup);
                $this->entityManager->flush();
            }

            $PurchasingGroup = new PurchasingGroup();
            $PurchasingGroup->setId($purchasingGroup['id']);
            $PurchasingGroup->setName($purchasingGroup['name']);
            $PurchasingGroup->setSortNo($purchasingGroup['sort_no']);
            $this->entityManager->persist($PurchasingGroup);
        }
        $this->truncateGenreDisplayMode();

        foreach ($this->genre_display_modes as $display_mode) {
            $ProductGenreDisplayMode = $this->productGenreDisplayModeRepository->find($display_mode['id']);
            if ($ProductGenreDisplayMode) {
                $this->entityManager->remove($ProductGenreDisplayMode);
                $this->entityManager->flush();
            }
            // 保存処理
            $GenreDisplayMode = new ProductGenreDisplayMode();
            $GenreDisplayMode->setId($display_mode['id']);
            $GenreDisplayMode->setName($display_mode['name']);
            $GenreDisplayMode->setSortNo($display_mode['sort_no']);
            $this->entityManager->persist($GenreDisplayMode);
            $this->entityManager->flush();
        }

        $this->clearCache($io);
        $io->success('success.');
    }

    public function truncateGenreDisplayMode()
    {
        $connection = $this->entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query("SET FOREIGN_KEY_CHECKS=0");
            $q = $dbPlatform->getTruncateTableSQL('dtb_product_genre_display_mode');
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }
}
