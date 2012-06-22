<?php

namespace Vespolina\StoreBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vespolina\PartnerBundle\Model\Partner;

class SetupCommand extends ContainerAwareCommand
{

    protected $input;
    protected $country;
    protected $output;
    protected $state;
    protected $type;

    protected function configure()
    {
        $this
            ->setName('vespolina:store-setup')
            ->setDescription('Setup a Vespolina demo store')
            ->addOption('country', null, InputOption::VALUE_OPTIONAL, 'Country', 'us')
            ->addOption('state', null, InputOption::VALUE_OPTIONAL, 'State', '')
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Store type', 'beverages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->country = strtoupper($input->getOption('country'));
        $this->type = $input->getOption('type');
        $this->state = strtoupper($input->getOption('state'));

        $customerTaxonomy = $this->setupCustomerTaxonomy($input, $output);
        $productTaxonomy = $this->setupProductTaxonomy($input, $output);

        //Setup taxation based on country
        $taxSchema = $this->setupTaxation($input, $output);

        $this->setupProducts($productTaxonomy, $taxSchema, $input, $output);
        $this->setupCustomers($customerTaxonomy, $input, $output);

        //Setup on or multiple stores
        $stores = $this->setupStores($input, $output);

        //For each store set up a (default) store zone
        $storeZones = $this->setupStoreZones($stores, $input, $output);

        $output->writeln('Finished setting up stores for country "' . $this->country . '" with type "' . $this->type . '"');
    }

    protected function setupCustomers($customerTaxonomy, $input, $output) {

        $customerCount = 10;
        $partnerManager = $this->getContainer()->get('vespolina_partner.partner_manager');

        for ($i = 0; $i < $customerCount; $i++) {

            $aCustomer = $partnerManager->createPartner(Partner::ROLE_CUSTOMER, Partner::INDIVIDUAL);
            $aCustomer->setName('customer ' . $i);

            $anAddress = $partnerManager->createPartnerAddress();
            $anAddress->setCountry($this->country);
            $aCustomer->addAddress($anAddress);


            $partnerManager->updatePartner($aCustomer, true);
        }
        $output->writeln('- Created ' . $customerCount . ' customers.' );
    }

    protected function setupCustomerTaxonomy($input, $output) {

        $taxonomyManager = $this->getContainer()->get('vespolina.taxonomy_manager');
        $aTaxonomy = $taxonomyManager->createTaxonomy('customers', 'tags');
        $termFixtures = array();

        $termFixtures[] = array('path' => 'bronze', 'name' => 'Bronze');
        $termFixtures[] = array('path' => 'silver', 'name' => 'Silver');
        $termFixtures[] = array('path' => 'gold', 'name' => 'Gold');

        foreach($termFixtures as $termFixture) {

            $aTerm = $taxonomyManager->createTerm($termFixture['path'], $termFixture['name']);
            $aTaxonomy->addTerm($aTerm);
        }

        $taxonomyManager->updateTaxonomy($aTaxonomy, true);

        $output->writeln('- Customer taxonomy has been setup with ' . count($termFixtures) . ' terms.' );
        return $aTaxonomy;
    }

    protected function setupProducts($productTaxonomy, $taxSchema, $input, $output)
    {
        $defaultTaxRate = $taxSchema['defaultTaxRate'];
        $productCount = 10;
        $productTaxonomyTerms = $productTaxonomy->getTerms();
        $keys = $productTaxonomyTerms->getKeys();

        $productManager = $this->getContainer()->get('vespolina.product_manager');

        for($i = 1; $i < $productCount; $i++) {

            //Get a random taxonomy term (= product category) to which we'll be attaching this product
            $index = rand(0, $productTaxonomyTerms->count() - 1);
            $aRandomTerm = $productTaxonomyTerms->get($keys[$index]);
            $singularTermName = substr($aRandomTerm->getName(), 0, strlen($aRandomTerm->getName())-1);
            $productName = ucfirst($singularTermName) . ' ' . $i;

            $aProduct = $productManager->createProduct();
            $aProduct->setName($productName);
            $aProduct->setSlug($this->slugify($aProduct->getName()));   //Todo: move to manager

            //Set up a nice primary media item
            $imageBasePath = 'bundles' . DIRECTORY_SEPARATOR .
                                 'applicationvespolinastore' . DIRECTORY_SEPARATOR .
                                 'images' . DIRECTORY_SEPARATOR .
                                 $this->type . DIRECTORY_SEPARATOR . $singularTermName . '-' . $i ;
            ;

            /** Set up for each product following pricing elements
             *  - netUnitPrice : unit price without tax
             *  - unitPriceMSRP: manufacturer suggested retail price without tax
             *  - unitPriceTax : tax over the net unit price (based on the default tax rate)
             *  - unitPriceTotal: final price a customer pays ( net unit price + tax )
             *  - unitMSRPTotal: manufacturer suggested retail price with tax
             **/
            $pricing = array();
            $pricing['netUnitPrice'] = rand(2,80);

            //Set Manufacturer Suggested Retail Price to +(random) % of the net unit price
            $pricing['MSRPDiscountRate'] = rand(10,35);
            $pricing['unitPriceMSRP'] = $pricing['netUnitPrice'] * ( 1 + $pricing['MSRPDiscountRate'] / 100);


            if ($defaultTaxRate) {

                $pricing['unitPriceTax'] = $pricing['netUnitPrice'] / 100 * $defaultTaxRate;
                $pricing['unitPriceMSRPTotal'] = $pricing['unitPriceMSRP'] * (1 + $defaultTaxRate / 100);
                $pricing['unitPriceTotal'] = $pricing['netUnitPrice'] + $pricing['unitPriceTax'];

            } else {

                $pricing['unitPriceTotal'] = $pricing['netUnitPrice'];
                $pricing['unitPriceMSRPTotal'] = $pricing['unitPriceMSRP'];
            }
            $aProduct->setPricing($pricing);

            $aProduct->addTerm($aRandomTerm);

            $productManager->updateProduct($aProduct, true);

            $asset = $productManager->getAssetManager()->createAsset(
                $aProduct,
                $imageBasePath . '.jpg',
                'main_detail'
            );
            $asset = $productManager->getAssetManager()->createAsset(
                $aProduct,
                $imageBasePath . '_thumb.jpg',
                'thumbnail'
            );

            for ($c = 1; $c<= rand(0,5); $c++)
            {
                $asset = $productManager->getAssetManager()->createAsset(
                    $aProduct,
                    $imageBasePath . '.jpg',
                    'secondary_detail'
                );
            }

        }

        $output->writeln('- Created ' . $productCount . ' sample products.' );
    }


    protected function setupProductTaxonomy($input, $output)
    {

        $taxonomyManager = $this->getContainer()->get('vespolina.taxonomy_manager');
        $aTaxonomy = $taxonomyManager->createTaxonomy('products', 'tags');

        $termFixtures = array();

        switch($this->type) {

            case 'band':

                $termFixtures = array();
                $termFixtures[] = array('path' => 'clothing', 'name' => 'Clothing');
                $termFixtures[] = array('path' => 'downloadable-tracks', 'name' => 'Downloadable tracks');
                break;

            case 'beverages':

                $termFixtures = array();
                $termFixtures[] = array('path' => 'beers', 'name' => 'Beers');
                $termFixtures[] = array('path' => 'wines',  'name' => 'Wines');
                break;

            case 'fashion':

                $termFixtures[] = array('path' => 'dresses', 'name' => 'Dresses');
                $termFixtures[] = array('path' => 'pants', 'name' => 'Pants');
                $termFixtures[] = array('path' => 'shoes', 'name' => 'Shoes');

                break;
            default:
                return;

        }
        foreach($termFixtures as $termFixture) {

            $aTerm = $taxonomyManager->createTerm($termFixture['path'], $termFixture['name']);
            $aTaxonomy->addTerm($aTerm);
        }

        $taxonomyManager->updateTaxonomy($aTaxonomy, true);

        $output->writeln('- Product taxonomy has been setup with ' . count($termFixtures) . ' terms.' );

        return $aTaxonomy;
    }

    protected function setupTaxation($input, $output)
    {

        $defaultTaxRate = 0;
        $fallbackTaxRate = 0;
        $taxationManager = $this->getContainer()->get('vespolina.taxation_manager');
        $taxSchema = $taxationManager->loadTaxSchema($this->country);
        $taxSchema['defaultTaxRate']  = 0;

        if ($taxSchema['zones']) {

            foreach($taxSchema['zones'] as $taxZone) {
                $taxationManager->updateTaxZone($taxZone, true);

                if ($taxZone->getCountry() == $this->country &&
                    $taxZone->getState() == $this->state) {

                    $defaultTaxRate = $taxZone->getDefaultRate();
                }

                if (!$fallbackTaxRate) {

                    $fallbackTaxRate = $taxZone->getDefaultRate();
                }
            }

            if (!$defaultTaxRate) {

                $defaultTaxRate = $fallbackTaxRate;
            }

            $taxSchema['defaultTaxRate'] = $defaultTaxRate;

            $output->writeln('- Setup ' . count($taxSchema['zones']) . ' tax zone(s) with default tax rate ' . $defaultTaxRate . '%');

        } else {

            $output->writeln('No taxation schema exists for country ' . $this->country);
        }

        return $taxSchema;
    }

    protected function setupStores($input, $output)
    {
        $manipulator = $this->getContainer()->get('vespolina.store.util.store_manipulator');
        $manipulator->create ('default_store', 'Vespolina Store', 'Mr. Nice Corp', 'default_store_web', 'standard', 'tiled');

        $output->writeln('- Setup ' . count($stores) . ' store(s)');
    }

    protected function setupStoreZones($stores, $input, $output)
    {
        $storeZones = array();
        $storeZoneManager = $this->getContainer()->get('vespolina.store_zone_manager');

        foreach ($stores as $store) {

            //Setup store zones
            $storeZone = $storeZoneManager->createStoreZone($store);
            $storeZone->setDisplayName(ucfirst($this->type));
            $storeZone->setTaxonomyName('products');

            $storeZoneManager->updateStoreZone($storeZone);
            $storeZones[] = $storeZone;
        }

        $output->writeln('- Setup ' . count($storeZones) . ' store zone(s)');

        return $storeZones;
    }


    protected function slugify($text)
    {
        return preg_replace('/[^a-z0-9_\s-]/', '', preg_replace("/[\s_]/", "-", preg_replace('!\s+!', ' ', strtolower(trim($text)))));
    }

}
