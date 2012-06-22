<?php

namespace Vespolina\StoreBundle\Tests\Model;

class StoreTest extends \PHPUnit_Framework_TestCase
{

    public function testName()
    {
        $store = $this->getStore();
        $this->assertNull($store->getName());

        $store->setDisplayName('Store Name');
        $this->assertEquals( 'Store Name', $store->getName());
    }

    public function testCode()
    {
        $store = $this->getStore();
        $this->assertNull($store->getCode());

        $store->setCode('default_store');
        $this->assertEquals( 'default_store', $store->getCode());
    }

    public function testSalesChannel()
    {
        $store = $this->getStore();
        $this->assertNull($store->getSalesChannel());

        $store->setSalesChannel('channel');
        $this->assertEquals( 'channel', $store->getSalesChannel());
    }

    public function testDefaultCurrency()
    {
        $store = $this->getStore();
        $this->assertNull($store->getDefaultCurrency());

        $store->setDefaultCurrency('USD');
        $this->assertEquals( 'USD', $store->getDefaultCurrency());
    }
    public function testDisplayName()
    {
        $store = $this->getStore();
        $this->assertNull($store->getDisplayName());

        $store->setDisplayName('Test Store');
        $this->assertEquals( 'Test Store', $store->getDisplayName());
    }
    public function testLegalName()
    {
        $store = $this->getStore();
        $this->assertNull($store->getLegalName());

        $store->setLegalName('ABC Store Inc');
        $this->assertEquals( 'ABC Store Inc', $store->getLegalName());
    }
    public function testOperationalMode()
    {
        $store = $this->getStore();
        $this->assertNull($store->getOperationalMode());

        $store->setOperationalMode('standard');
        $this->assertEquals( 'standard', $store->getOperationalMode());
    }
    public function testDefaultProductView()
    {
        $store = $this->getStore();
        $this->assertNull($store->getDefaultProductView());

        $store->setDefaultProductView('tiled');
        $this->assertEquals( 'tiled', $store->getDefaultProductView());
    }
    public function testDefaultCountry()
    {
        $store = $this->getStore();
        $this->assertNull($store->getDefaultCountry());

        $store->setDefaultCountry('USA');
        $this->assertEquals( 'USA', $store->getDefaultCountry());
    }

    public function testDefaultState()
    {
        $store = $this->getStore();
        $this->assertNull($store->getDefaultState());

        $store->setDefaultState('california');
        $this->assertEquals( 'california', $store->getDefaultState());
    }

    public function testStoreZone()
    {
        $zone1 = $this->getStoreZone();
        $zone1->setDisplayName('Zone 1');
        $zone2 = $this->getStoreZone();
        $zone2->setDisplayName('Zone 2');
        $store = $this->getStore();

        $this->assertCount( 0, $store->getStoreZones());

        $store->addStoreZone($zone1);
        $store->addStoreZone($zone2);
        $this->assertCount( 2, $store->getStoreZones());

        $storeZones = $store->getStoreZones();
        $this->assertEquals ('Zone 1', $storeZones[0]->getDisplayName());


    }

    private function getStoreZone()
    {
        return $this->getMockForAbstractClass('Vespolina\StoreBundle\Model\StoreZone');
    }

    private function getStore()
    {
        return $this->getMockForAbstractClass('Vespolina\StoreBundle\Model\Store');
    }

}
