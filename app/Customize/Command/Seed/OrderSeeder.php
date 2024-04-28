<?php


namespace Customize\Command\Seed;

use Customize\Entity\Product\ProductGenreDisplayMode;
use Customize\Entity\Product\ProductGift;
use Eccube\Command\PluginCommandTrait;
use Eccube\Entity\Csv;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Master\Pref;
use Eccube\Entity\Master\RoundingType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Entity\Shipping;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\PrefRepository;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\ProductRepository;
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

class OrderSeeder extends Command
{
    // docker コマンド『docker-compose exec ec-cube php bin/console seed:order-data』で実行
    protected static $defaultName = 'seed:order-data';

    /**
     * @var CustomerRepository
     */
    protected $userRepository;

    /**
     * @var PrefRepository
     */
    protected $prefRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    private $entityManager;

    /**
     * ProductMasterDataSeeder constructor.
     * @param CustomerRepository $userRepository
     *
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CustomerRepository $userRepository,
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        PrefRepository $prefRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->prefRepository = $prefRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;

        parent::__construct();
    }

    use PluginCommandTrait;

    protected function configure()
    {
        $this
            ->setDescription('Create dummy order data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $taxRate = 10;

        // Orderを初期化
        $this->truncateTable();

        $customers = $this->userRepository->findAll();

        $productRepo = $this->entityManager->getRepository(Product::class);
        // get Product total number
        $totalProduct = $productRepo->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $pref = $this->prefRepository->find(11);

        $roundingTypeRepo = $this->entityManager->getRepository(RoundingType::class);
        $roundingType = $roundingTypeRepo->find(1);

        $taxTypeRepo = $this->entityManager->getRepository(TaxType::class);
        $taxType = $taxTypeRepo->find(1);

        $taxDisplayTypeRepo = $this->entityManager->getRepository(TaxDisplayType::class);
        $taxDisplayType = $taxDisplayTypeRepo->find(1);

        $orderItemTypeRepo = $this->entityManager->getRepository(OrderItemType::class);
        $orderItemType = $orderItemTypeRepo->find(1);

        $orderStatusRepo = $this->entityManager->getRepository(OrderStatus::class);
        $orderStatus = $orderStatusRepo->find(1);

        $productClassRepo = $this->entityManager->getRepository(ProductClass::class);

        foreach($customers as $customer)
        {
            $order = new Order();
            $order->setCustomer($customer);
            $order->setPref($pref);
            $order->setName01($customer['name01']);
            $order->setName02($customer['name02']);
            $order->setKana01($customer['kana01']);
            $order->setKana02($customer['kana02']);
            $order->setEmail($customer['email']);
            $order->setPhoneNumber($customer['phone_number']);
            $order->setPostalCode($customer['postal_code']);
            $order->setAddr01($customer['addr01']);
            $order->setAddr02($customer['addr02']);
            $order->setOrderDate(new \DateTime());
            $order->setOrderStatus($orderStatus);
            $order->setDeliveryType($customer['id'] % 2 ? '1' : '2');
            $order->setDesiredDeliveryTime($customer['id'] % 3 ? '2' : '1');
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $totalAmount = 0;
            for($i=0; $i<5; $i++) {
                $randProduct = $this->productRepository->find(rand(1, $totalProduct));
                if (!empty($randProduct)) {
                    $productClasses = $productClassRepo->findBy(['Product' => $randProduct['id']]);
                    if (!empty($productClasses)) {
                        $productClass = $productClasses[0];
                        $quantity = rand(1, 5);
                        $totalAmount += (int)$productClass['price01'] * $quantity;

                        $orderItem = new OrderItem();
                        $orderItem->setOrder($order);
                        $orderItem->setProductName($randProduct['name']);
                        $orderItem->setProductClass($productClass);
                        $orderItem->setProduct($randProduct);
                        $orderItem->setPrice($productClass['price01']);
                        $orderItem->setTaxRate($taxRate);
                        $orderItem->setShipping();
                        $orderItem->setRoundingType($roundingType);
                        $orderItem->setTaxType($taxType);
                        $orderItem->setTaxDisplayType($taxDisplayType);
                        $orderItem->setOrderItemType($orderItemType);
                        $orderItem->setQuantity($quantity);
                        $this->entityManager->persist($orderItem);
                        $this->entityManager->flush();
                    }
                }
            }

            // 送り先登録
            $shipping = new Shipping();
            $shipping->setOrder($order);
            $shipping->setPref($pref);
            $shipping->setName01($customer['name01']);
            $shipping->setName02($customer['name02']);
            $shipping->setKana01($customer['kana01']);
            $shipping->setKana02($customer['kana02']);
            $shipping->setPhoneNumber($customer['phone_number']);
            $shipping->setPostalCode($customer['postal_code']);
            $shipping->setAddr01($customer['addr01']);
            $shipping->setAddr02($customer['addr02']);
            $this->entityManager->persist($shipping);


            // Set TotalAmount
            $order->setTotal($totalAmount);
            // Set PaymentTotal
            $order->setPaymentTotal($totalAmount*1.1);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }

        $this->clearCache($io);
        $io->success('success.');
    }

    public function truncateTable()
    {
        $relationTablesNames = [
            'dtb_order',
            'dtb_order_item',
            'dtb_shipment'
        ];

        $connection = $this->entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query("SET FOREIGN_KEY_CHECKS=0");
            foreach($relationTablesNames as $name) {
                $q = $dbPlatform->getTruncateTableSQL($name);
                $connection->executeUpdate($q);
            }
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }
}
