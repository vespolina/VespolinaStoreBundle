<?php

namespace Vespolina\StoreBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupCommand extends ContainerAwareCommand
{

    protected $input;
    protected $country;
    protected $output;
    protected $type;

    protected function configure()
    {
        $this
            ->setName('vespolina:store-setup')
            ->setDescription('Setup a Vespolina demo store')
            ->addOption('country', null, InputOption::VALUE_OPTIONAL, 'Country', 'us')
            ->addOption('state', null, InputOption::VALUE_OPTIONAL, 'State', 'or')
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

        $store = $this->setupStore($input, $output);


        $output->writeln('Finished setting up demo store "' . $store->getName() . '" for country "' . $this->country . '" with type "' . $this->type . '"');
    }

    protected function setupCustomerTaxonomy($input, $output){

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

            $aProduct = $productManager->createProduct();
            $aProduct->setName('product ' . $i);
            $aProduct->setSlug($this->slugify($aProduct->getName()));   //Todo: move to manager

            //Set some prices
            $pricing = array();
            $pricing['unitPrice'] = rand(2,80);

            if ($defaultTaxRate) {

                $pricing['unitPriceTax'] = $pricing['unitPrice'] / 100 * $defaultTaxRate;
                $pricing['unitPriceTotal'] = $pricing['unitPrice'] + $pricing['unitPriceTax'];

            } else {
                $pricing['unitPriceTotal'] = $pricing['unitPrice'];
            }

            $aProduct->setPricing($pricing);

            //Attach it to a random taxonomy term
            $index = rand(0, $productTaxonomyTerms->count() - 1);

            $aRandomTerm = $productTaxonomyTerms->get($keys[$index]);
            $aProduct->addTerm($aRandomTerm);

            $productManager->updateProduct($aProduct, true);
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
                $termFixtures[] = array('path' => 'beers', 'name' => 'Beer');
                $termFixtures[] = array('path' => 'wine',  'name' => 'Wine');
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

    protected function setupStore($input, $output)
    {
        $storeManager = $this->getContainer()->get('vespolina.store_manager');
        $store = $storeManager->createStore('default_store', 'Vespolina ' . ucfirst($this->type) . ' Shop');
        $store->setSalesChannel('default_store_web');
        $storeManager->updateStore($store);

        $output->writeln('- Setup a default store configuration' );
        return $store;
    }

    protected function slugify($text)
    {
        return preg_replace('/[^a-z0-9_\s-]/', '', preg_replace("/[\s_]/", "-", preg_replace('!\s+!', ' ', strtolower(trim($text)))));
    }

}