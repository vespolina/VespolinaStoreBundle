<?php

namespace Vespolina\StoreBundle\ProcessScenario\Setup\Step;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Vespolina\Entity\Partner\Partner;
use Vespolina\Entity\Pricing\Element\TotalDoughValueElement;

class CreateStore extends AbstractSetupStep
{
    public function execute(&$context) {

        $storeManager = $this->getContainer()->get('vespolina_store.store_manager');
        $storeZoneManager = $this->getContainer()->get('vespolina_store.store_zone_manager');
        $storeZones = array();

        //Load stores configurations (for now get that from vespolina.yml)
        $stores = $storeManager->loadStoresConfigurations();

        foreach($stores as $store) {

            if (!$store->getDefaultCurrency()) {
                //Default currency
                switch ($context['country']) {
                    case 'US': $currency = 'USD'; break;
                    default: $currency = 'EUR'; break;
                }
                $store->setDefaultCurrency($currency);
                $store->setDefaultCountry($context['country']);
                $store->setDefaultState($context['state']);
            }

            $storeManager->updateStore($store);
        }

        $this->getLogger()->addInfo('Setup ' . count($stores) . ' store(s)');
        $context['stores'] = $stores;

        foreach ($stores as $store) {

            //Setup store zones
            $storeZone = $storeZoneManager->createStoreZone($store);
            $storeZone->setDisplayName(ucfirst($context['type']));
            $storeZone->setTaxonomyName('products');

            $storeZoneManager->updateStoreZone($storeZone);
            $storeZones[] = $storeZone;
        }

        $this->getLogger()->addInfo('- Setup ' . count($storeZones) . ' store zone(s)');

        $context['storeZones'] = $storeZones;;

    }

    public function getName() {

        return 'create_store';
    }
}
