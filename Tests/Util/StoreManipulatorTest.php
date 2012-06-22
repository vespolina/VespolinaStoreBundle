<?php

namespace Vespolina\StoreBundle\Tests\Util;

use Vespolina\StoreBundle\Util\StoreManipulator;
use Vespolina\StoreBundle\Tests\TestStore;

class StoreManipulatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $storeManagerMock = $this->getMock('Vespolina\StoreBundle\Model\StoreManagerInterface');
        $store = new TestStore();

        $code = 'default_store';
        $displayName = 'ABC Store';
        $legalName = 'ABC Store Sale-o-rama';
        $salesChannel = '';
        $operationalMode = 'standard';
        $taxationEnabled = true;
        $productView = 'tiled';

        $storeManagerMock->expects ($this->once())
            ->method('createStore')
            ->will ($this->returnValue($store));

        $manipulator = new StoreManipulator($storeManagerMock);
        $manipulator->create($code, $displayName, $legalName, $salesChannel, $taxationEnabled, $operationalMode, $productView);

        $this->assertEquals($code, $store->getCode());
        $this->assertEquals($displayName, $store->getDisplayName());
        $this->assertEquals($legalName, $store->getLegalName());
        $this->assertEquals($salesChannel, $store->getSalesChannel());
        $this->assertEquals($operationalMode, $store->getOperationalMode());
        $this->assertEquals($productView, $store->getDefaultProductView());
    }
}
