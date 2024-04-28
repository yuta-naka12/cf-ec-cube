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

namespace Eccube\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists(MemberType::class, false)) {
    /**
     * TaxType
     *
     * 管理会員タイプ
     *
     * @ORM\Table(name="mtb_member_type")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Customize\Repository\Admin\Master\MemberTypeRepository")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     *
     */
    class MemberType extends \Eccube\Entity\Master\AbstractMasterEntity
    {
        /**
         * Admin
         *
         * 管理ロール
         * 管理画面上の全ての操作権限を持っている
         *
         * @var integer
         */
        const ADMIN = 1;

        /**
         * SA
         *
         * SAユーザー
         * ・コールリスト機能
         * ・パスワードの変更
         * ・ログアウト
         * のみ扱える
         *
         * @var integer
         */
        const SA = 2;
    }
}
