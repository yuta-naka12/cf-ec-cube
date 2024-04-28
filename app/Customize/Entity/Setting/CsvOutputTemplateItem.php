<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Entity\Setting;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Member;

if (!class_exists('\Customize\Entity\Setting\CsvOutputTemplateItem')) {
    /**
     * Csv出力テンプレートアイテム
     *
     * @ORM\Table(name="dtb_csv_output_template_item")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Customize\Repository\Admin\Setting\CsvOutputTemplateItemRepository")
     */
    class CsvOutputTemplateItem extends \Eccube\Entity\AbstractEntity
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(name="entity_name", type="string", length=255)
         */
        private $entity_name;

        /**
         * @var string
         *
         * @ORM\Column(name="field_name", type="string", length=255)
         */
        private $field_name;

        /**
         * @var integer
         *
         * @ORM\Column(name="csv_id", type="integer", length=255)
         */
        private $csv_id;

        /**
         * @var string|null
         *
         * @ORM\Column(name="reference_field_name", type="string", length=255, nullable=true)
         */
        private $reference_field_name;

        /**
         * @var string
         *
         * @ORM\Column(name="disp_name", type="string", length=255)
         */
        private $disp_name;

        /**
         * @var int
         *
         * @ORM\Column(name="sort_no", type="smallint", options={"unsigned":true})
         */
        private $sort_no;

        /**
         * @var boolean
         *
         * @ORM\Column(name="enabled", type="boolean", options={"default":true})
         */
        private $enabled = true;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="create_date", type="datetimetz")
         */
        private $create_date;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="update_date", type="datetimetz")
         */
        private $update_date;

        /**
         * @var \Eccube\Entity\Master\CsvType
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Setting\CsvOutputTemplate", cascade={"remove"})
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="csv_output_template_id", referencedColumnName="id",onDelete="CASCADE")
         * })
         */
        private $CsvOutputTemplate;

        /**
         * Get id.
         *
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set entityName.
         *
         * @param string $entityName
         *
         * @return Csv
         */
        public function setEntityName($entityName)
        {
            $this->entity_name = $entityName;

            return $this;
        }

        /**
         * Get entityName.
         *
         * @return string
         */
        public function getEntityName()
        {
            return $this->entity_name;
        }

        /**
         * Set csv_id.
         *
         * @param string $csv_id
         *
         * @return Csv
         */
        public function setCsvId($csv_id)
        {
            $this->csv_id = $csv_id;

            return $this;
        }

        /**
         * Get csv_id.
         *
         * @return string
         */
        public function getCsvId()
        {
            return $this->csv_id;
        }

        /**
         * Set fieldName.
         *
         * @param string $fieldName
         *
         * @return Csv
         */
        public function setFieldName($fieldName)
        {
            $this->field_name = $fieldName;

            return $this;
        }

        /**
         * Get fieldName.
         *
         * @return string
         */
        public function getFieldName()
        {
            return $this->field_name;
        }

        /**
         * Set referenceFieldName.
         *
         * @param string|null $referenceFieldName
         *
         * @return Csv
         */
        public function setReferenceFieldName($referenceFieldName = null)
        {
            $this->reference_field_name = $referenceFieldName;

            return $this;
        }

        /**
         * Get referenceFieldName.
         *
         * @return string|null
         */
        public function getReferenceFieldName()
        {
            return $this->reference_field_name;
        }

        /**
         * Set dispName.
         *
         * @param string $dispName
         *
         * @return Csv
         */
        public function setDispName($dispName)
        {
            $this->disp_name = $dispName;

            return $this;
        }

        /**
         * Get dispName.
         *
         * @return string
         */
        public function getDispName()
        {
            return $this->disp_name;
        }

        /**
         * Set sortNo.
         *
         * @param int $sortNo
         *
         * @return Csv
         */
        public function setSortNo($sortNo)
        {
            $this->sort_no = $sortNo;

            return $this;
        }

        /**
         * Get sortNo.
         *
         * @return int
         */
        public function getSortNo()
        {
            return $this->sort_no;
        }

        /**
         * Set enabled.
         *
         * @param boolean $enabled
         *
         * @return Csv
         */
        public function setEnabled($enabled)
        {
            $this->enabled = $enabled;

            return $this;
        }

        /**
         * Get enabled.
         *
         * @return boolean
         */
        public function getEnabled()
        {
            return $this->enabled;
        }

        /**
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return Csv
         */
        public function setCreateDate($createDate)
        {
            $this->create_date = $createDate;

            return $this;
        }

        /**
         * Get createDate.
         *
         * @return \DateTime
         */
        public function getCreateDate()
        {
            return $this->create_date;
        }

        /**
         * Set updateDate.
         *
         * @param \DateTime $updateDate
         *
         * @return Csv
         */
        public function setUpdateDate($updateDate)
        {
            $this->update_date = $updateDate;

            return $this;
        }

        /**
         * Get updateDate.
         *
         * @return \DateTime
         */
        public function getUpdateDate()
        {
            return $this->update_date;
        }

        /**
         * Set CsvOutputTemplate.
         *
         * @param CsvOutputTemplate|null $csvType
         *
         * @return CsvOutputTemplate
         */
        public function setCsvOutputTemplate($CsvOutputTemplate = null)
        {
            $this->CsvOutputTemplate = $CsvOutputTemplate;

            return $this;
        }

        /**
         * Get CsvOutputTemplate.
         *
         * @return CsvOutputTemplate|null
         */
        public function getCsvOutputTemplat()
        {
            return $this->CsvOutputTemplate;
        }
    }
}
