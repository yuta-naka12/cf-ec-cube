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

namespace Customize\Tests\Web\Admin\CallList;


use Customize\Controller\Admin\CallList\CallListController;
use Eccube\Repository\CallList\CallListRepository;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;

class CallListControllerTest extends AbstractAdminWebTestCase
{
    /** @test */
    public function testAccessCallList()
    {
        $this->client->request('GET', $this->generateUrl('admin_call_list'));
        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @test */
    public function testAccessCallListOrder()
    {
        $this->client->request('GET', $this->generateUrl('admin_call_list_order', ['id' => 1]));
        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @test */
    public function testPostNote()
    {
        $memoFormData = [
        'note' => 'メモテスト'
        ];

        $this->client->request(
            'POST',
            $this->generateUrl('admin_call_list_order', ['id' => 1]),
            ['body' => $memoFormData]
        );
        self::assertFalse($this->client->getResponse()->isSuccessful());
    }
//
//    public function TestPostCancel()
//    {
//
//    }


}
