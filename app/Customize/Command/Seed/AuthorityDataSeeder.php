<?php


namespace Customize\Command\Seed;


use Customize\Entity\ProductGift;
use Eccube\Command\PluginCommandTrait;
use Eccube\Entity\AuthorityRole;
use Eccube\Entity\Csv;
use Eccube\Entity\Master\Authority;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Member;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Doctrine\ORM\EntityManagerInterface;

class AuthorityDataSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:authority-master-data』で実行
    protected static $defaultName = 'seed:authority-master-data';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected $authorities = [
        [
            'id' => 1,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/product',
        ],
        [
            'id' => 2,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/contact',
        ],
        [
            'id' => 3,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/content',
        ],
        [
            'id' => 4,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/customer',
        ],
        [
            'id' => 5,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/order',
        ],
        [
            'id' => 6,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/point',
        ],
        [
            'id' => 7,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/setting',
        ],
        [
            'id' => 8,
            'authority_id' => 1,
            'creator_id' => 1,
            'deny_url' => '/store',
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
        try {
            $em = $this->entityManager;
            // DB初期化
            $connection = $em->getConnection();
            $dbPlatform = $connection->getDatabasePlatform();
            $connection->beginTransaction();

            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql('dtb_authority_role');
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $io = new SymfonyStyle($input, $output);
            foreach($this->authorities as $data) {
                $authority = $em->getRepository(Authority::class)->find($data['authority_id']);
                $member = $em->getRepository(Member::class)->find($data['creator_id']);

                $AuthorityRole = new AuthorityRole();
                $AuthorityRole->setAuthority($authority);
                $AuthorityRole->setCreator($member);
                $AuthorityRole->setDenyUrl($data['deny_url']);
                $this->entityManager->persist($AuthorityRole);
                $this->entityManager->flush();
            }

            $this->clearCache($io);
            $io->success('success.');
            $connection->commit();
        }
        catch (\Exception $e) {
            $connection->rollback();
        }
    }
}
