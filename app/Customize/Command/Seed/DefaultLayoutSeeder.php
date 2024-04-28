<?php


namespace Customize\Command\Seed;

use Customize\Entity\Product\ProductGift;
use Eccube\Command\PluginCommandTrait;
use Eccube\Entity\Block;
use Eccube\Entity\BlockPosition;
use Eccube\Entity\Csv;
use Eccube\Entity\Layout;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Master\DeviceType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Doctrine\ORM\EntityManagerInterface;

class DefaultLayoutSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:default-layout』で実行
    protected static $defaultName = 'seed:default-layout';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected $blocks = [
        [
            'id' => 18,
            'block_name' => '左サイドバー',
            'file_name' => 'sidemenu_left',
            'use_controller' => 0,
            'deletable' => 0,

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

        $defaultDeviceType = $this->entityManager->getRepository(DeviceType::class)
            ->find(DeviceType::DEVICE_TYPE_PC);
        $TopLayout = $this->entityManager->getRepository(Layout::class)
            ->find(1);
        $UnderLayerLayout = $this->entityManager->getRepository(Layout::class)
            ->find(2);

        foreach($this->blocks as $block) {
            // ブロックを保存
            $Block = new Block();
            $Block->setId($block['id']);
            $Block->setDeviceType($defaultDeviceType);
            $Block->setName($block['block_name']);
            $Block->setFileName($block['file_name']);
            $Block->setUseController($block['use_controller']);
            $Block->setDeletable($block['deletable']);
            $this->entityManager->persist($Block);
            $this->entityManager->flush();

            $TopPositionLayout = new BlockPosition();
            $TopPositionLayout->setBlock($Block);
            $TopPositionLayout->setLayout($TopLayout);
            $TopPositionLayout->setSection();
        }

        $this->clearCache($io);
        $io->success('success.');
    }
}
