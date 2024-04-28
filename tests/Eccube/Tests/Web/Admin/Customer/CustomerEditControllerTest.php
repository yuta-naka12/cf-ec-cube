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

namespace Eccube\Tests\Web\Admin\Customer;

use Eccube\Entity\Customer;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;

/**
 * Class CustomerEditControllerTest
 */
class CustomerEditControllerTest extends AbstractAdminWebTestCase
{
    /** @var Customer */
    protected $Customer;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();
        $this->Customer = $this->createCustomer();
    }

    /**
     * createFormData
     *
     * @return array
     */
    protected function createFormData()
    {
        $faker = $this->getFaker();
        $email = $faker->safeEmail;
        $password = $faker->lexify('????????');
        $birth = $faker->dateTimeBetween;


        $form = [
            'name' => ['name01' => $faker->lastName, 'name02' => $faker->firstName],
            'kana' => ['kana01' => $faker->lastKanaName, 'kana02' => $faker->firstKanaName],
            'sex' => rand(1, 3),
            'birth' => $birth->format('Y') . '-' . $birth->format('n') . '-' . $birth->format('j'),
            'postal_code' => $faker->postcode,
            'company_name' => $faker->company,
            'address' => ['pref' => rand(1, 47), 'addr01' => $faker->city, 'addr02' => $faker->streetAddress],
            'phone_number' => $faker->phoneNumber,
            'sub_tel' => $faker->phoneNumber,
            'fax' => $faker->phoneNumber,
            'note' => $faker->realText(200, 3),
            'note_2' => $faker->realText(200, 3),
            'note_3' => $faker->realText(200, 3),
            'delivery_code_name' => $faker->realText(200, 3),
            'email' => $email,
            'password' => ['first' => $password, 'second' => $password],
            'delivery_code_name' => '244-0842-020',
            'delivery_week_1' => 1,
            'delivery_week_2' => 3,
            'delivery_date' => '水',
            'map_page' => '1',
            'entry_date' => $birth->format('Y') . '-' . $birth->format('n') . '-' . $birth->format('j'),
            'WithdrawalReason' => 1,
            'job' => 1,
            'status' => 1,
            'point' => 0,
            '_token' => 'dummy',
        ];

        return $form;
    }

    /**
     * testIndex
     */
    public function testIndex()
    {
        $this->client->request(
            'GET',
            $this->generateUrl('admin_customer_edit', ['id' => $this->Customer->getId()])
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * testIndex
     */
    public function testIndexBackButton()
    {
        $crawler = $this->client->request(
            'GET',
            $this->generateUrl('admin_customer_edit', ['id' => $this->Customer->getId()])
        );

        $this->expected = '会員一覧';
        $this->actual = $crawler->filter('#customer_form > div.c-conversionArea > div > div > div:nth-child(1) > div')->text();
        $this->assertContains($this->expected, $this->actual);
    }

    /**
     * testIndexWithPost
     */
    public function testIndexWithPost()
    {
        $form = $this->createFormData();
        $this->client->request(
            'POST',
            $this->generateUrl('admin_customer_edit', ['id' => $this->Customer->getId()]),
            ['admin_customer' => $form]
        );
        $this->assertTrue($this->client->getResponse()->isRedirect(
            $this->generateUrl(
                'admin_customer_edit',
                ['id' => $this->Customer->getId()]
            )
        ));
        $EditedCustomer = $this->entityManager->getRepository(\Eccube\Entity\Customer::class)->find($this->Customer->getId());

        $this->expected = $form['email'];
        $this->actual = $EditedCustomer->getEmail();
        $this->verify();
    }

    /**
     * testNew
     */
    public function testNew()
    {
        $this->client->request(
            'GET',
            $this->generateUrl('admin_customer_new')
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * testNewWithPost
     */
    public function testNewWithPost()
    {
        $form = $this->createFormData();
        $this->client->request(
            'POST',
            $this->generateUrl('admin_customer_new'),
            ['admin_customer' => $form]
        );

        $NewCustomer = $this->entityManager->getRepository(\Eccube\Entity\Customer::class)->findOneBy(['email' => $form['email']]);
        $this->assertNotNull($NewCustomer);
        $this->assertTrue($form['email'] == $NewCustomer->getEmail());
    }

    /**
     * testCustomerWithdraw
     */
    public function testCustomerWithdraw()
    {
        $form = $this->createFormData();
        $form['status'] = 3;
        $this->client->request(
            'POST',
            $this->generateUrl('admin_customer_edit', ['id' => $this->Customer->getId()]),
            ['admin_customer' => $form]
        );

        $EditedCustomer = $this->entityManager->getRepository(\Eccube\Entity\Customer::class)->find($this->Customer->getId());

        $this->assertRegExp('/@dummy.dummy/', $EditedCustomer->getEmail());
    }

    /**
     * testMailNoRFC
     */
    public function testMailNoRFC()
    {
        $form = $this->createFormData();
        // RFCに準拠していないメールアドレスを設定
        $form['email'] = 'aa..@example.com';

        $this->client->request(
            'POST',
            $this->generateUrl('admin_customer_edit', ['id' => $this->Customer->getId()]),
            ['admin_customer' => $form]
        );
        $this->assertTrue($this->client->getResponse()->isRedirect(
            $this->generateUrl(
                'admin_customer_edit',
                ['id' => $this->Customer->getId()]
            )
        ));
        $EditedCustomer = $this->entityManager->getRepository(\Eccube\Entity\Customer::class)->find($this->Customer->getId());

        $this->expected = $form['email'];
        $this->actual = $EditedCustomer->getEmail();
        $this->verify();
    }
}
