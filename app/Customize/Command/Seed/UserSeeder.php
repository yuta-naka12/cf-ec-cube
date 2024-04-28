<?php


namespace Customize\Command\Seed;

use Customize\Entity\Product\ProductGenreDisplayMode;
use Customize\Entity\Product\ProductGift;
use Eccube\Command\PluginCommandTrait;
use Eccube\Entity\Csv;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Master\Pref;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\PrefRepository;
use Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Master\PurchasingGroup;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\Master\PurchasingGroupRepository;
use Eccube\Repository\ProductGenreDisplayModeRepository;

class UserSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:customer-data』で実行
    protected static $defaultName = 'seed:customer-data';

    /**
     * @var CustomerRepository
     */
    protected $userRepository;

    /**
     * @var PrefRepository
     */
    protected $prefRepository;

    private $entityManager;

    /**
     * ProductMasterDataSeeder constructor.
     * @param CustomerRepository $userRepository
     *
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CustomerRepository $userRepository,
        PrefRepository $prefRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->prefRepository = $prefRepository;

        parent::__construct();
    }

    use PluginCommandTrait;

    protected function configure()
    {
        $this
            ->setDescription('Create dummy customer data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $faker = \Faker\Factory::create('ja_JP');
        $pref = $this->prefRepository->find(11);

        for($i=0; $i<100; $i++)
        {
            $secretKey = mt_rand(0,90) . chr(mt_rand(0,90)) . chr(mt_rand(0,90)) . $i;

            $customer = new Customer();
            $customer->setName01($faker->lastName);
            $customer->setName02($faker->firstName);
            $customer->setKana01($faker->lastKanaName);
            $customer->setKana02($faker->firstKanaName);
            $customer->setPostalCode($faker->postcode);
            $customer->setPref($pref);
            $customer->setAddr01($faker->city . $faker->ward);
            $customer->setAddr02($faker->streetAddress);
            $customer->setEmail($faker->email);
            $customer->setPhoneNumber($faker->phoneNumber);
            $customer->setDeliveryType(rand(1, 2));
            $customer->setPassword('Password1234');
            $customer->setSecretKey($secretKey);
            $this->entityManager->persist($customer);
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
            $q = $dbPlatform->getTruncateTableSQL('dtb_ customer');
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }
}
