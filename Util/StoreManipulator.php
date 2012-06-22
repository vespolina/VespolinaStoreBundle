<?php

namespace Vespolina\StoreBundle\Util;
use Vespolina\StoreBundle\Model\StoreManagerInterface;

/**
 * Handles manipulating attributes of a store
 *
 * @author Myke Hines <myke@webhines.com>
 */
class StoreManipulator
{
    /**
     * Store Manager
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * Creates a store and returns it
     */
    public function create( $code, $display_name, $legal_name, $sales_channel, $operational_mode = 'standard', $default_product_view = 'tiled')
    {
        $store = $this->storeManager->createStore();
        $store->setCode($code);
        $store->setDisplayName($display_name);
        $store->setLegalName($legal_name);
        $store->setSalesChannel($sales_channel);
        $store->setOperationalMode($operational_mode);
        $store->setDefaultProductView($default_product_view);

        $this->storeManager->updateStore($store);
        return $store;
    }
}
