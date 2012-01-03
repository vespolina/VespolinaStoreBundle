<?php

namespace Vespolina\OrderBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Vespolina\StoreBundle\Model\Store;


class StoreTest extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = $this->createClient();
    }

    public function getKernel(array $options = array())
    {
        if (!self::$kernel) {
            self::$kernel = $this->createKernel($options);
            self::$kernel->boot();
        }

        return self::$kernel;
    }


    public function testStoreCreate()
    {

        $store = $this->getMockForAbstractClass('Vespolina\StoreBundle\Model\Store');

        $this->assertNotNull($store);
    }
}