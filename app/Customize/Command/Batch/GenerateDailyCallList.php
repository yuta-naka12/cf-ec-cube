<?php


namespace Customize\Command\Batch;


use Customize\Entity\CallList\CallList;
use Customize\Entity\CallList\CallListGroup;
use Eccube\Command\PluginCommandTrait;
use Eccube\Repository\CallList\CallListGroupRepository;
use Eccube\Repository\CallList\CallListRepository;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\MemberRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Dailyのコールリストを生成する
 */
class GenerateDailyCallList extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console generate:daily-call-list』で実行
    protected static $defaultName = 'generate:daily-call-list';
    /**
     * @var CallListRepository
     */
    protected $callListRepository;

    /**
     * @var CallListGroupRepository
     */
    protected $callListGroupRepository;

    /**
     * @var MemberRepository
     */
    protected $memberRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CallListRepository $callListRepository,
        CallListGroupRepository $callListGroupRepository,
        MemberRepository $memberRepository,
        CustomerRepository $customerRepository
    )
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->callListRepository = $callListRepository;
        $this->callListGroupRepository = $callListGroupRepository;
        $this->memberRepository = $memberRepository;
        $this->customerRepository = $customerRepository;
    }

    protected $csvTypes = [];

    use PluginCommandTrait;

    protected function configure()
    {
        $this
            ->setDescription('Load customize data fixtures to database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        // SAユーザーに基づき、コールリストグループを生成
        $members = $this->memberRepository->getQueryBuilderBySearchData([]);
        foreach($members as $member) {
            if (!$this->callListGroupRepository->isGeneratedCallListAtToday($member)) {
                // コールリストグループを保存
                $CallListGroup = new CallListGroup();
                $CallListGroup->setMember($member);
                $CallListGroup->setCreateDate(new \DateTime());
                $CallListGroup->setUpdateDate(new \DateTime());
                $this->entityManager->persist($CallListGroup);
                $this->entityManager->flush();
            }
        }

        // コールリストを生成
        $users = $this->customerRepository->getQueryBuilderBySearchData([]);
        if (!empty($users)) {
            foreach($users->getQuery()->getResult() as $user) {
                if (!$this->callListRepository->isGeneratedCallListAtToday($user)) {
                    // コールリストを保存
                    $CallList = new CallList();
                    $CallList->setCustomer($user);
                    $CallList->setCreateDate(new \DateTime());
                    $CallList->setUpdateDate(new \DateTime());
                    $this->entityManager->persist($CallList);
                    $this->entityManager->flush();

                    // コールリストをグループに紐付け
                    $date = date('Y/m/d 00:00:00');
                    $query = [
                        'Member' => $user['Member'],
                        'create_date' => $date,
                    ];
                    $groups = $this->callListGroupRepository->getQueryBuilderBySearchData($query);
                    if (!empty($groups)) {
                        $CallList->setCallListGroup($groups[0]);
                        $this->entityManager->persist($CallList);
                        $this->entityManager->flush();
                    }

                }
            }
        }

        $this->clearCache($io);
        $io->success('success.');
    }
}
