<?php

namespace Customize\Form\Extension;

use Eccube\Form\Type\Admin\MasterdataType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;

class MasterdataTypeExtension extends AbstractTypeExtension
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $masterdata = [];

        /** @var MappingDriverChain $driverChain */
        $driverChain = $this->entityManager->getConfiguration()->getMetadataDriverImpl();
        /** @var MappingDriver[] $drivers */
        $drivers = $driverChain->getDrivers();

        foreach ($drivers as $namespace => $driver) {
            if ($namespace == 'Eccube\Entity' || $namespace == 'Customize\Entity') {
                $classNames = $driver->getAllClassNames();
                foreach ($classNames as $className) {
                    /** @var ClassMetadata $meta */
                    $meta = $this->entityManager->getMetadataFactory()->getMetadataFor($className);
                    $metadataName = str_replace('\\', '-', $meta->getName());
                    if (strpos($meta->rootEntityName, 'Master') !== false
                        && $meta->hasField('id')
                    ) {
                        $metadataName = str_replace('\\', '-', $meta->getName());
                        $masterdata[$metadataName] = $meta->getTableName();
                    }
                }
            }
        }

        $options = $builder->get('masterdata')->getOptions();
        $options['choices'] = array_flip($masterdata);

        $builder
            ->add('masterdata', ChoiceType::class, $options);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [MasterdataType::class];
    }
}
