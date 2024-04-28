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
use Customize\Entity\Voice\Voice;

class VoiceSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:voice』で実行
    protected static $defaultName = 'seed:voice';

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
            'title' => "会員歴１７年",
            'customer_initials' => "M.Kさま",
            'content' => "キャプテンフーヅの冷凍品は安全性にすぐれ、小分けパックや一切れずつの包装など使う側に立った商品で、便利に使わせてもらっています。特に魚介類の鮮度は抜群で、たまに足りなくなり、スーパーで買ってくると家族に直ぐ「いつもと違うね」と言われます。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-1.jpg"
        ],
        [
            'id' => 2,
            'title' => "会員歴６年",
            'customer_initials' => "O.Sさま",
            'content' => "キャプテンフーヅで加工された「漬け魚」はどれも無添加で、食の安全が騒がれる今、安心して食べられる食材です。
            味も良いし、ちょうどいい塩加減で凍ったまま焼けるので忙しい時に重宝します。急速凍結された魚介類は安心して使えます。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-2.jpg"
        ],
        [
            'id' => 3,
            'title' => "会員歴５年",
            'customer_initials' => "O.Mさま",
            'content' => "初めて使ったとき、鮮度がとてもよいと思いました。私は正直言って刺身類は苦手ですが、「真いかソーメン・えび刺」あと「（生）たらばがに」 は最高です。
            切り身魚や干物などはキャプテンフーヅを使ってから、買い物に行っても買う気になれず、注文日を待っています。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-3.jpg"
        ],
        [
            'id' => 4,
            'title' => "会員歴１６年",
            'customer_initials' => "I.Sさま",
            'content' => "キャプテンフーヅの商品は産地・鮮度にこだわっているから、どれも美味しくいただいています。
            真空包装されているので安心して冷凍庫に保存でき、冷凍庫をのぞけば、何でもそろっている便利さを実感しています。
            凍ったまま「煮る・焼く」解凍の必要が全くないので便利です。特に「生むきえび」はとても使い勝手がよく、重宝しています。",
            'image_path'=>"/html/template/default/assets/img/voice/voice-detail.jpg"
        ],
        [
            'id' => 5,
            'title' => "会員歴１３年",
            'customer_initials' => "T.Sさま",
            'content' => "昔はお魚の宅配はあまりなかったので、すごく興味を持った。
            バラ凍結なので、とても便利に使わせてもらっています。
            体に安心なものを扱っているので、この頃ほかでは買えなくなっている。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-1.jpg"
        ],
        [
            'id' => 6,
            'title' => "H.Hさま",
            'customer_initials' => "T.Sさま",
            'content' => "冷凍室にお魚のストックがあると、とても安心な気持ちになり、買い物に出かけても余裕ができる。
            添加物に気を使った商品ばかりで価格も手ごろ。お好みは（生むきえび・さわら・さばフィレ・するめいか・豚のばら肉など）",
            'image_path'=>"/html/template/default/assets/img/voice/voice-detail.jpg"
        ],
        [
            'id' => 7,
            'title' => "会員歴１１年",
            'customer_initials' => "Y.Hさま",
            'content' => "魚介類の宅配に初めて出会い、とても新鮮でした。
            特にお魚は凍ったまま煮たり、焼いたりできるので便利。
            配達日に留守しても、ドライを入れてバッグで置いてあるし鮮度もよく、味もよく、お値段も良心的です。",
            'image_path'=>"/html/template/default/assets/img/voice/voice-detail.jpg"
        ],
        [
            'id' => 8,
            'title' => "会員歴１１年",
            'customer_initials' => "T.Aさま",
            'content' => "スーパーなどで買い求めた商品が冷蔵庫の中で鮮度が落ちて、駄目になってしまう事がよくありましたが、特に「鶏のもも肉」など、 使いたいときに新鮮な状態で使えるので便利。
            「殻付しじみ」も砂だしした状態ですぐ使え、美味しいので冷凍室に欠かせません。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-8.jpg"
        ],
        [
            'id' => 9,
            'title' => "会員歴４年",
            'customer_initials' => "N.Sさま",
            'content' => "キャプテンフーヅを始める前には、冷凍魚は一切使った事がなく、抵抗があったが、実際に使ってみると、便利で、経済的で、 無駄がないことがわかった。
            解凍方法を間違えると、せっかくの美味しい魚が台無しになってしまうので注意！
            我が家ではお魚が 美味しいと人気を高めている。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-1.jpg"
        ],
        [
            'id' => 10,
            'title' => "会員歴１１年",
            'customer_initials' => "A.Yさま",
            'content' => "「えび」から始めた会社という事で「海老」は絶対におすすめ、特にお刺身は鮮度抜群。
            忙しい時、メインディッシュになり助かっています。2週間に一度の配達で献立の予定も立てやすく、安全面では一つ一つ商品の産地も 記載され、細かく調べて答えてくれるので、安心です。",
            'image_path'=>"/html/template/default/assets/img/voice/voice-detail.jpg"
        ],
        [
            'id' => 11,
            'title' => "会員歴１８年",
            'customer_initials' => "S.Rさま",
            'content' => "子供の食事を作る必要のない老夫婦の暮らしになって、今こそ多いに利用しています。
            個食パックでバラ凍結。必要な量だけ取り出して調理でき、刺身類はいつでも鮮度よく、食べられて、少人数の我が家にとって 、そのメリットは大きいと思います。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-1.jpg"
        ],
        [
            'id' => 12,
            'title' => "会員歴３年",
            'customer_initials' => "M.Sさま",
            'content' => "キャプテンフーヅのカタログを見て、今現在利用しているところと一つ一つ比べてみました。
            魚に関しては美味しくて、安いと感じ、今ではキャプテンフーヅだけを利用。家族も満足そうで大助かりです。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-1.jpg"
        ],
        [
            'id' => 13,
            'title' => "会員歴８年",
            'customer_initials' => "M.Nさま",
            'content' => "キャプテンフーヅの品で初めて食べたのは「生むきえび」でした。かき揚げにしましたが、ぷりぷりの 食感と美味しさにビックリ！抜群の使いやすさに、手放せない一品です。
            干物やお肉などが冷凍室にあると、忙しい主婦は大助かりです。食の安全が問題とされる今、鮮度の良い素材で 、家庭の好みの味付けで食べるのが一番安心で「家庭の味」を子供たちに伝えられると思います。",
            'image_path'=>"/html/template/default/assets/img/voice/voice-detail.jpg"
        ],
        [
            'id' => 14,
            'title' => "会員歴４年",
            'customer_initials' => "I.Hさま",
            'content' => "えび・刺身類は船内で急速凍結されているので鮮度がよい。
            干物もすぐに冷凍乾燥しているので、油焼けしていない。 「柳かれい」など、デパート・スーパーでは１枚分の値段。
            冷凍野菜も「国産」ばかりで、他ではあまり見られない。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-14.jpg"
        ],
        [
            'id' => 15,
            'title' => "会員歴１５年",
            'customer_initials' => "K.Mさま",
            'content' => "スーパーなどで市販されている冷凍食品の味を想像していたのでまず、第一印象は「こんなに美味しい 冷凍食品を食べたことがない」というのが感想です。お店では肉や魚の冷凍はあまり見かけないと思いますが いつでも鮮度よく使え大変重宝しています。
            キャプテンフーヅと出会ってから、スーパーの思い袋をもって辛い思いをすることもなく、買い物に行かなく なった時間を有意義に過ごしております。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-14.jpg"
        ],
        [
            'id' => 16,
            'title' => "会員歴１８年",
            'customer_initials' => "E.Kさま",
            'content' => "我が家ではキャプテンフーヅの商品は欠かせない食生活の全てです。
            野菜と調味料だけ買い物して、あとは２週間に一度献立を決めて注文！
            見ると何でも買ってしまう頃から比べると、経済的で無駄が省けます。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-1.jpg"
        ],
        [
            'id' => 17,
            'title' => "会員歴１４年",
            'customer_initials' => "Y.Yさま",
            'content' => "冷凍室に「紅鮭甘塩・リングカット・生むきえび・とろろ・オニオンソテー」は常備品です。
            紅鮭は１切れづつ使えるし、塩加減もちょうど良く、脂がギトギトしていないからとても良い。
            鶏もも正肉は臭みがないので、鶏肉嫌いな主人がよく食べる。
            海老の臭みが嫌いな娘もキャプテンフーヅのえびだけは大好物。
            スーパーの安売り品とは段違い！",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-17.jpg"
        ],
        [
            'id' => 18,
            'title' => "会員歴１２年",
            'customer_initials' => "K.Hさま",
            'content' => "かつては冷凍の魚は「まずい」というイメージが強く一切使うことがなかったが、早朝市場に 勤めるようになり、魚は冷凍流通が多く、解凍・解体して売られることを知り、ビックリ！
            スーパーで売っている魚を冷凍するとまずいので、急速凍結されたまま調理すると最高に美味しい！
            今ではよっぽと困らないと、スーパーで魚介類は買わなくなった。",
            'image_path'=>"/html/template/default/assets/img/voice/img-voice-1.jpg"
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

        $this->truncateTable('dtb_voice');

        foreach ($this->routes as $route) {
            // dtb_pageを保存
            $voice = new Voice();
            $voice->setId($route['id']);
            $voice->setTitle($route['title']);
            $voice->setCustomerInitials($route['customer_initials']);
            $voice->setContent($route['content']);
            $voice->setImagePath($route['image_path']);
           
            $this->entityManager->persist($voice);
            $this->entityManager->flush();


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
