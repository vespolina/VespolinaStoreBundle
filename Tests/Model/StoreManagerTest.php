<?php

namespace Vespolina\StoreBundle\Tests\Model;

class StoreManagerTest extends \PHPUnit_Framework_TestCase
{
    private $manager;
    private $storesConfiguration;

    protected function setUp()
    {
        $this->storesConfiguration = $this->getMockStoresConfiguration();

        $this->manager = $this->getStoreManager(array(
            $this->storesConfiguration
        ));
    }

    public function testXYZ()
    {
    }

    private function getMockStoresConfiguration()
    {
        return array(
            'default_store' => array(
                'id' => 'default_store',
                'default' => 'true',
                'display_name' => 'Test Store',
                'legal_name' => 'My Test Store',
                'sales_channel' => 'default_store_web',
                'operational_mode' => 'standard',
                'default_product_view' => 'tiled',
            )
        );
    }

    private function getMockStoreClass()
    {
        return $this->getMockBuilder('Vespolina\StoreBundle\Model\User')
            ->getMockForAbstractClass();
    }

    private function getStoreManager(array $args)
    {
        return $this->getMockBuilder('Vespolina\StoreBundle\Model\StoreManager')
            ->setConstructorArgs($args)
            ->getMockForAbstractClass();
    }
}
