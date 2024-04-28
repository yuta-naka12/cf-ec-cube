<?php


namespace Customize\Command\Seed;

use Eccube\Command\PluginCommandTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Page;
use Eccube\Entity\PageLayout;
use Eccube\Repository\LayoutRepository;
use Eccube\Repository\PageRepository;

class RouteSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:route』で実行
    protected static $defaultName = 'seed:route';

    private $entityManager;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @var LayoutRepository
     */
    private $layoutRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PageRepository $pageRepository,
        LayoutRepository $layoutRepository
    ) {
        $this->entityManager = $entityManager;
        $this->pageRepository = $pageRepository;
        $this->layoutRepository = $layoutRepository;
        parent::__construct();
    }

    protected $routes = [
        [
            'id' => 1,
            'master_page_id' => NULL,
            'page_name' => "プレビューデータ",
            'url' => "preview",
            'file_name' => NULL,
            'edit_type' => 1,
            'author' => NULL,
            'description' => NULL,
            'keyword' => NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => NULL,
            'meta_tags' => NULL,
            'page_layout' => [
                'layout_id' => 0,
                'sort_no' => 2,
            ]
        ],
        [
            'id' => 2,
            'master_page_id' =>  NULL,
            'page_name' => "TOPページ",
            'url' => "homepage",
            'file_name' => "index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 1,
                'sort_no' => 2,
            ]
        ],
        [
            'id' => 3,
            'master_page_id' =>  NULL,
            'page_name' => "商品一覧ページ",
            'url' => "product_list",
            'file_name' => "Product/list",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 4,
            ]
        ],
        [
            'id' => 4,
            'master_page_id' =>  NULL,
            'page_name' => "商品詳細ページ",
            'url' => "product_detail",
            'file_name' => "Product/detail",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 5,
            ]
        ],
        [
            'id' => 5,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ",
            'url' => "mypage",
            'file_name' => "Mypage/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 6,
            ]
        ],
        [
            'id' => 6,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/会員登録内容変更(入力ページ)",
            'url' => "mypage_change",
            'file_name' => "Mypage/change",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 7,
            ]
        ],
        [
            'id' => 7,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/会員登録内容変更(完了ページ)",
            'url' => "mypage_change_complete",
            'file_name' => "Mypage/change_complete",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 8,
            ]
        ],
        [
            'id' => 8,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/お届け先一覧",
            'url' => "mypage_delivery",
            'file_name' => "Mypage/delivery",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 36,
            ]
        ],
        [
            'id' => 9,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/お届け先追加",
            'url' => "mypage_delivery_new",
            'file_name' => "Mypage/delivery_edit",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 37,
            ]
        ],
        [
            'id' => 10,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/お気に入り一覧",
            'url' => "mypage_favorite",
            'file_name' => "Mypage/favorite",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 9,
            ]
        ],
        [
            'id' => 11,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/購入履歴詳細",
            'url' => "mypage_history",
            'file_name' => "Mypage/history",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 10,
            ]
        ],
        [
            'id' => 12,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/ログイン",
            'url' => "mypage_login",
            'file_name' => "Mypage/login",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 11,
            ]
        ],
        [
            'id' => 13,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/退会手続き(入力ページ)",
            'url' => "mypage_withdraw",
            'file_name' => "Mypage/withdraw",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 12,
            ]
        ],
        [
            'id' => 14,
            'master_page_id' =>  NULL,
            'page_name' => "MYページ/退会手続き(完了ページ)",
            'url' => "mypage_withdraw_complete",
            'file_name' => "Mypage/withdraw_complete",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 14,
            ]
        ],
        [
            'id' => 15,
            'master_page_id' =>  NULL,
            'page_name' => "当サイトについて",
            'url' => "help_about",
            'file_name' => "Help/about",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 13,
            ]
        ],
        [
            'id' => 16,
            'master_page_id' =>  NULL,
            'page_name' => "現在のカゴの中",
            'url' => "cart",
            'file_name' => "Cart/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 15,
            ]
        ],
        [
            'id' => 17,
            'master_page_id' =>  NULL,
            'page_name' => "お問い合わせ(入力ページ)",
            'url' => "contact",
            'file_name' => "Contact/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 16,
            ]
        ],
        [
            'id' => 18,
            'master_page_id' =>  NULL,
            'page_name' => "お問い合わせ(完了ページ)",
            'url' => "contact_complete",
            'file_name' => "Contact/complete",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 17,
            ]
        ],
        [
            'id' => 19,
            'master_page_id' =>  NULL,
            'page_name' => "会員登録(入力ページ)",
            'url' => "entry",
            'file_name' => "Entry/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 18,
            ]
        ],
        [
            'id' => 20,
            'master_page_id' =>  NULL,
            'page_name' => "ご利用規約",
            'url' => "help_agreement",
            'file_name' => "Help/agreement",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 33,
            ]
        ],
        [
            'id' => 21,
            'master_page_id' =>  NULL,
            'page_name' => "会員登録(完了ページ)",
            'url' => "entry_complete",
            'file_name' => "Entry/complete",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 19,
            ]
        ],
        [
            'id' => 22,
            'master_page_id' =>  NULL,
            'page_name' => "特定商取引に関する法律に基づく表記",
            'url' => "help_tradelaw",
            'file_name' => "Help/tradelaw",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 20,
            ]
        ],
        [
            'id' => 23,
            'master_page_id' =>  NULL,
            'page_name' => "本会員登録(完了ページ)",
            'url' => "entry_activate",
            'file_name' => "Entry/activate",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 21,
            ]
        ],
        [
            'id' => 24,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入",
            'url' => "shopping",
            'file_name' => "Shopping/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 35,
            ]
        ],
        [
            'id' => 25,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/お届け先の指定",
            'url' => "shopping_shipping",
            'file_name' => "Shopping/shipping",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 22,
            ]
        ],
        [
            'id' => 26,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/お届け先の複数指定",
            'url' => "shopping_shipping_multiple",
            'file_name' => "Shopping/shipping_multiple",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 34,
            ]
        ],
        [
            'id' => 27,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/ご注文完了",
            'url' => "shopping_complete",
            'file_name' => "Shopping/complete",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 23,
            ]
        ],
        [
            'id' => 28,
            'master_page_id' =>  NULL,
            'page_name' => "プライバシーポリシー",
            'url' => "help_privacy",
            'file_name' => "Help/privacy",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 24,
            ]
        ],
        [
            'id' => 29,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入ログイン",
            'url' => "shopping_login",
            'file_name' => "Shopping/login",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 25,
            ]
        ],
        [
            'id' => 30,
            'master_page_id' =>  NULL,
            'page_name' => "非会員購入情報入力",
            'url' => "shopping_nonmember",
            'file_name' => "Shopping/nonmember",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 10:14:52",
            'update_date' => "2017-03-07 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 3,
            ]
        ],
        [
            'id' => 31,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/お届け先の追加",
            'url' => "shopping_shipping_edit",
            'file_name' => "Shopping/shipping_edit",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:02",
            'update_date' => "2017-03-07 01:15:02",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 3,
            ]
        ],
        [
            'id' => 32,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/お届け先の複数指定(お届け先の追加)",
            'url' => "shopping_shipping_multiple_edit",
            'file_name' => "Shopping/shipping_multiple_edit",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:02",
            'update_date' => "2017-03-07 01:15:02",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 28,
            ]
        ],
        [
            'id' => 33,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/購入エラー",
            'url' => "shopping_error",
            'file_name' => "Shopping/shopping_error",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:02",
            'update_date' => "2017-03-07 01:15:02",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 29,
            ]
        ],
        [
            'id' => 34,
            'master_page_id' =>  NULL,
            'page_name' => "ご利用ガイド",
            'url' => "help_guide",
            'file_name' => "Help/guide",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:02",
            'update_date' => "2017-03-07 01:15:02",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 30,
            ]
        ],
        [
            'id' => 35,
            'master_page_id' =>  NULL,
            'page_name' => "パスワード再発行(入力ページ)",
            'url' => "forgot",
            'file_name' => "Forgot/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:02",
            'update_date' => "2017-03-07 01:15:02",
            'meta_robots' =>  NULL,
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 31,
            ]
        ],
        [
            'id' => 36,
            'master_page_id' =>  NULL,
            'page_name' => "パスワード再発行(完了ページ)",
            'url' => "forgot_complete",
            'file_name' => "Forgot/complete",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:02",
            'update_date' => "2017-03-07 01:15:02",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 32,
            ]
        ],
        [
            'id' => 37,
            'master_page_id' =>  NULL,
            'page_name' => "パスワード再発行(再設定ページ)",
            'url' => "forgot_reset",
            'file_name' => "Forgot/reset",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:02",
            'update_date' => "2017-03-07 01:15:05",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 39,
            ]
        ],
        [
            'id' => 38,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/遷移",
            'url' => "shopping_redirect_to",
            'file_name' => "Shopping/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:03",
            'update_date' => "2017-03-07 01:15:03",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 38,
            ]
        ],
        [
            'id' => 39,
            'master_page_id' => 9,
            'page_name' => "MYページ/お届け先編集",
            'url' => "mypage_delivery_edit",
            'file_name' => "Mypage/delivery_edit",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:05",
            'update_date' => "2017-03-07 01:15:05",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 40,
            ]
        ],
        [
            'id' => 40,
            'master_page_id' =>  NULL,
            'page_name' => "商品購入/ご注文確認",
            'url' => "shopping_confirm",
            'file_name' => "Shopping/confirm",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2017-03-07 01:15:03",
            'update_date' => "2017-03-07 01:15:03",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 41,
            ]
        ],
        [
            'id' => 41,
            'master_page_id' => 19,
            'page_name' => "会員登録(確認ページ)",
            'url' => "entry_confirm",
            'file_name' => "Entry/confirm",
            'edit_type' =>  3,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2020-01-12 01:15:03",
            'update_date' => "2020-01-12 01:15:03",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 42,
            ]
        ],
        [
            'id' => 42,
            'master_page_id' => 12,
            'page_name' => "MYページ/退会手続き(確認ページ)",
            'url' => "mypage_withdraw_confirm",
            'file_name' => "Mypage/withdraw_confirm",
            'edit_type' =>  3,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2020-01-12 10:14:52",
            'update_date' => "2020-01-12 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 43,
            ]
        ],
        [
            'id' => 43,
            'master_page_id' => 16,
            'page_name' => "お問い合わせ(確認ページ)",
            'url' => "contact_confirm",
            'file_name' => "Contact/confirm",
            'edit_type' =>  3,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2020-01-12 10:14:52",
            'update_date' => "2020-01-12 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 44,
            ]
        ],
        [
            'id' => 44,
            'master_page_id' => null,
            'page_name' => "パンフレット申し込み",
            'url' => "apply_pamphlet",
            'file_name' => "Pamphlet/pamphlet",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2020-01-12 10:14:52",
            'update_date' => "2020-01-12 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 44,
            ]
        ],
        [
            'id' => 45,
            'master_page_id' => null,
            'page_name' => "パンフレット申し込み確認",
            'url' => "apply_pamphlet_confirm",
            'file_name' => "Pamphlet/pamphlet_confirm",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2020-01-12 10:14:52",
            'update_date' => "2020-01-12 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 45,
            ]
        ],
        [
            'id' => 46,
            'master_page_id' => null,
            'page_name' => "パンフレット申し込み完了",
            'url' => "apply_pamphlet_complete",
            'file_name' => "Pamphlet/pamphlet_complete",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2020-01-12 10:14:52",
            'update_date' => "2020-01-12 10:14:52",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 46,
            ]
        ],
        [
            'id' => 47,
            'master_page_id' => null,
            'page_name' => "おいしく食べるための解凍・調理方法",
            'url' => "cooking-method",
            'file_name' => "CookingMethod/cooking_method",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 47,
            ]
        ],
        [
            'id' => 48,
            'master_page_id' => null,
            'page_name' => "細菌検査の実施",
            'url' => "bacteriological-test",
            'file_name' => "BacteriologicalTest/bacteriological_test",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 48,
            ]
        ],
        [
            'id' => 49,
            'master_page_id' => null,
            'page_name' => "お客様の声",
            'url' => "voice-list",
            'file_name' => "VoiceList/voice_list",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 49,
            ]
        ],
        [
            'id' => 50,
            'master_page_id' => null,
            'page_name' => "よくある質問",
            'url' => "faq",
            'file_name' => "FAQ/faq",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 50,
            ]
        ],
        [
            'id' => 51,
            'master_page_id' => null,
            'page_name' => "ネット利用お申し込みフォーム",
            'url' => "net-form",
            'file_name' => "NetForm/net_form",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 51,
            ]
        ],
        [
            'id' => 52,
            'master_page_id' => null,
            'page_name' => "Ordering",
            'url' => "ordering",
            'file_name' => "Ordering/ordering",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 52,
            ]
        ],
        [
            'id' => 53,
            'master_page_id' => null,
            'page_name' => "のしについて",
            'url' => "faq_noshi",
            'file_name' => "FAQ/noshi",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 53,
            ]
        ],
        [
            'id' => 54,
            'master_page_id' => null,
            'page_name' => "複数回答先に送る。",
            'url' => "faq_hukusuu",
            'file_name' => "FAQ/hukusuu",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 54,
            ]
        ],
        [
            'id' => 55,
            'master_page_id' => null,
            'page_name' => "Shop(TOP)",
            'url' => "shop",
            'file_name' => "Shop/index",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 55,
            ]
        ],
        [
            'id' => 56,
            'master_page_id' => null,
            'page_name' => "ビギナー",
            'url' => "beginner",
            'file_name' => "Beginner/beginner",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 56,
            ]
        ],
        [
            'id' => 57,
            'master_page_id' => null,
            'page_name' => "自社便登録",
            'url' => "entry_jisha_use",
            'file_name' => "Entry/Jisha/jisha_use",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 57,
            ]
        ],
        [
            'id' => 58,
            'master_page_id' => null,
            'page_name' => "Delivery",
            'url' => "delivery",
            'file_name' => "Delivery/delivery",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 58,
            ]
        ],
        [
            'id' => 59,
            'master_page_id' => null,
            'page_name' => "Payment",
            'url' => "payment",
            'file_name' => "Payment/payment",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 59,
            ]
        ],

        [
            'id' => 60,
            'master_page_id' => null,
            'page_name' => "Company",
            'url' => "company",
            'file_name' => "Company/company",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 60,
            ]
        ],
        
        [
            'id' => 61,
            'master_page_id' => null,
            'page_name' => "VoiceDetail/",
            'url' => "voice-detail",
            'file_name' => "VoiceDetail/voice-detail",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 61,
            ]
        ],
        [
            'id' => 62,
            'master_page_id' => null,
            'page_name' => "Category/",
            'url' => "category",
            'file_name' => "Category/category",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 62,
            ]
        ],
        [
            'id' => 63,
            'master_page_id' => null,
            'page_name' => "Special/",
            'url' => "special",
            'file_name' => "Special/special",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 63,
            ]
        ],
        [
            'id' => 64,
            'master_page_id' => null,
            'page_name' => "Benefits/",
            'url' => "benefits",
            'file_name' => "Benefits/benefits",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 64,
            ]
        ],
        [
            'id' => 64,
            'master_page_id' => null,
            'page_name' => "Change/",
            'url' => "change",
            'file_name' => "Change/change",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 64,
            ]
        ],
        [
            'id' => 65,
            'master_page_id' => null,
            'page_name' => "Choose/",
            'url' => "choose",
            'file_name' => "Choose/choose",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 65,
            ]
        ],
        [
            'id' => 65,
            'master_page_id' => null,
            'page_name' => "Commitment/",
            'url' => "commitment",
            'file_name' => "Commitment/commitment",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 65,
            ]
        ],
        [
            'id' => 66,
            'master_page_id' => null,
            'page_name' => "Tokutei/",
            'url' => "tokutei",
            'file_name' => "Tokutei/tokutei",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 66,
            ]
        ],
        [
            'id' => 67,
            'master_page_id' => null,
            'page_name' => "Sitemap/",
            'url' => "sitemap",
            'file_name' => "Sitemap/sitemap",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 67,
            ]
        ],
        
        [
            'id' => 68,
            'master_page_id' => null,
            'page_name' => "Privacypolicy/",
            'url' => "privacy-policy",
            'file_name' => "Privacypolicy/privacy_policy",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 68,
            ]
        ],
        [
            'id' => 69,
            'master_page_id' => null,
            'page_name' => "Quickorder/",
            'url' => "quickorder",
            'file_name' => "Quickorder/quickorder",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 69,
            ]
        ],
        [
            'id' => 70,
            'master_page_id' => null,
            'page_name' => "Search/",
            'url' => "search",
            'file_name' => "Search/search",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 70,
            ]
        ],
        [
            'id' => 71,
            'master_page_id' => null,
            'page_name' => "Mypage/",
            'url' => "mypage-otoiawaserireki",
            'file_name' => "Mypage/mypage",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 71,
            ]
        ],
        [
            'id' => 72,
            'master_page_id' => null,
            'page_name' => "Mypagetwo/",
            'url' => "mypagetwo",
            'file_name' => "Mypagetwo/mypage_two",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 72,
            ]
        ],
        [
            'id' => 73,
            'master_page_id' => null,
            'page_name' => "Mypagethree/",
            'url' => "mypagethree",
            'file_name' => "Mypagethree/mypage_three",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 73,
            ]
        ],
        [
            'id' => 74,
            'master_page_id' => null,
            'page_name' => "Mypagefour/",
            'url' => "mypagefour",
            'file_name' => "Mypagefour/mypage_four",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 74,
            ]
        ],
        [
            'id' => 75,
            'master_page_id' => null,
            'page_name' => "Genres/",
            'url' => "genres",
            'file_name' => "Genres/genres",
            'edit_type' =>  2,
            'author' =>  NULL,
            'description' =>  NULL,
            'keyword' =>  NULL,
            'create_date' => "2023-01-12 00:00:00",
            'update_date' => "2023-01-12 00:00:00",
            'meta_robots' => "noindex",
            'meta_tags' =>  NULL,
            'page_layout' => [
                'layout_id' => 2,
                'sort_no' => 75,
            ]
        ],

    ];

    use PluginCommandTrait;

    protected function configure()
    {
        $this->setDescription('Load customize data fixtures to database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->truncateTable('dtb_page');
        $this->truncateTable('dtb_page_layout');

        foreach ($this->routes as $route) {
            // dtb_pageを保存
            $page = new Page();
            $page->setId($route['id']);
            $page->setName($route['page_name']);
            $page->setUrl($route['url']);
            $page->setFileName($route['file_name']);
            $page->setEditType($route['edit_type']);
            $page->setAuthor($route['author']);
            $page->setDescription($route['description']);
            $page->setKeyword($route['keyword']);
            $page->setCreateDate($route['create_date']);
            $page->setUpdateDate($route['update_date']);
            $page->setMetaRobots($route['meta_robots']);
            $page->setMetaTags($route['meta_tags']);
            $this->entityManager->persist($page);
            $this->entityManager->flush();

            // dtb_page_layoutを保存
            $pageLayout = $route['page_layout'];
            $addPageLayout = new PageLayout();
            $addPageLayout->setPageId($route['id']);
            $addPageLayout->setLayoutId($pageLayout['layout_id']);
            $addPageLayout->setSortNo($pageLayout['sort_no']);
            $addPageLayout->setPage($page);
            $layout = $this->layoutRepository->find(['id' => $pageLayout['layout_id']]);
            $addPageLayout->setLayout($layout);

            $this->entityManager->persist($addPageLayout);
            $this->entityManager->flush();
        }
        // マスターページの登録
        foreach ($this->routes as $route) {
            $hasMasterPage = $route['master_page_id'];
            $page = $this->pageRepository->find(['id' => $route['id']]);
            if (!empty($hasMasterPage)) {
                $addMasterPage = $this->pageRepository->find(['id' => $hasMasterPage]);
                $page->setMasterPage($addMasterPage);
                $this->entityManager->persist($page);
                $this->entityManager->flush();
            }
        }

        $this->clearCache($io);
        $io->success('success.');
    }

    public function truncateTable($table_name)
    {
        $connection = $this->entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query("SET FOREIGN_KEY_CHECKS=0");
            $q = $dbPlatform->getTruncateTableSQL($table_name);
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }
}
