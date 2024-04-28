<?php

namespace Customize\Entity\Setting;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Category;

if (!class_exists('\Customize\Entity\Setting\CsvOutputTemplate')) {
    /**
     * CSV出力テンプレート
     *
     * @ORM\Table(name="dtb_csv_output_template")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Customize\Repository\Admin\Setting\CsvOutputTemplateRepository")
     */
    class CsvOutputTemplate extends \Eccube\Entity\AbstractEntity
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
         * CSVタイプID
         * @var string
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Master\CsvType")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="csv_type_id", referencedColumnName="id")
         * })
         */
        private $CsvType;

        /**
         * メンバー
         * @var string
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="member_id", referencedColumnName="id")
         * })
         */
        private $Member;

        /**
         * 全体公開フラグ
         * @var boolean
         *
         * @ORM\Column(name="is_public", type="boolean", nullable=true)
         */
        private $is_public;

        /**
         * タイトル
         * @var boolean
         *
         * @ORM\Column(name="title", type="string", nullable=true)
         */
        private $title;

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
         * Set title
         *
         * @param string|null $value
         *
         * @return CsvOutputTemplate
         */
        public function setTitle($value = null)
        {
            $this->title = $value;

            return $this;
        }

        /**
         * Get title.
         *
         * @return string|null
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * Set CsvType
         *
         * @param CsvType|null $value
         *
         * @return CsvOutputTemplate
         */
        public function setCsvType($value = null)
        {
            $this->CsvType = $value;

            return $this;
        }

        /**
         * Get CsvType.
         *
         * @return CsvType|null
         */
        public function getCsvType()
        {
            return $this->CsvType;
        }

        /**
         * Set Member
         *
         * @param Member|null $value
         *
         * @return CsvOutputTemplate
         */
        public function setMember($value = null)
        {
            $this->Member = $value;

            return $this;
        }

        /**
         * Get Member.
         *
         * @return Member|null
         */
        public function getMember()
        {
            return $this->Member;
        }

        /**
         * Set is_public
         *
         * @param string|null $value
         *
         * @return CsvOutputTemplate
         */
        public function setIsPubilic($value = null)
        {
            $this->is_public = $value;

            return $this;
        }

        /**
         * Get is_public.
         *
         * @return string|null
         */
        public function getIsPubilic()
        {
            return $this->is_public;
        }
    }
}
