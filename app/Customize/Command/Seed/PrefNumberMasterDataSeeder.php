<?php


namespace Customize\Command\Seed;

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
use Eccube\Entity\Master\PurchasingGroup;
use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductClass;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\Master\PrefRepository;
use Eccube\Repository\Master\PurchasingGroupRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductGenreDisplayModeRepository;

class PrefNumberMasterDataSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:pref-number-master-data』で実行
    protected static $defaultName = 'seed:pref-number-master-data';

    private $entityManager;

    /**
     * @var PrefRepository
     */
    protected $prefRepository;

    /**
     * ProductMasterDataSeeder constructor.
     *
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PrefRepository $prefRepository
    ) {
        $this->entityManager = $entityManager;
        $this->prefRepository = $prefRepository;
        parent::__construct();
    }

    protected $prefNumbers = [
        '北海道' => '01',
        '青森県' => '02',
        '岩手県' => '03',
        '宮城県' => '04',
        '秋田県' => '05',
        '山形県' => '06',
        '福島県' => '07',
        '茨城県' => '08',
        '栃木県' => '09',
        '群馬県' => '10',
        '埼玉県' => '11',
        '千葉県' => '12',
        '東京都' => '13',
        '神奈川県' => '14',
        '新潟県' => '15',
        '富山県' => '16',
        '石川県' => '17',
        '福井県' => '18',
        '山梨県' => '19',
        '長野県' => '20',
        '岐阜県' => '21',
        '静岡県' => '22',
        '愛知県' => '23',
        '三重県' => '24',
        '滋賀県' => '25',
        '京都府' => '26',
        '大阪府' => '27',
        '兵庫県' => '28',
        '奈良県' => '29',
        '和歌山県' => '30',
        '鳥取県' => '31',
        '島根県' => '32',
        '岡山県' => '33',
        '広島県' => '34',
        '山口県' => '35',
        '徳島県' => '36',
        '香川県' => '37',
        '愛媛県' => '38',
        '高知県' => '39',
        '福岡県' => '40',
        '佐賀県' => '41',
        '長崎県' => '42',
        '熊本県' => '43',
        '大分県' => '44',
        '宮崎県' => '45',
        '鹿児島県' => '46',
        '沖縄県' => '47',
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

        foreach ($this->prefNumbers as $pref_name => $pref_number) {
            $pref = $this->prefRepository->findOneBy(['name' => $pref_name]);
            if ($pref && $pref->getPrefNumber() !== $pref_number) {
                $pref->setPrefNumber($pref_number);
                $this->entityManager->persist($pref);
            }
        }
        $this->entityManager->flush();

        $this->clearCache($io);
        $io->success('success.');
    }

    public function truncateGenreDisplayMode()
    {
    }
}
