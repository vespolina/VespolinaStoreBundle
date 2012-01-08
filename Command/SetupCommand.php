<?php

namespace Vespolina\StoreBundle\Command;

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
            ->setName('vespolina:setup')
            ->setDescription('Setup a Vespolina demo store')
            ->addOption('country', null, InputOption::VALUE_OPTIONAL, 'Country')
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Store type')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->country = $input->getOption('country', 'be');
        $this->type = $input->getOption('type', 'band');

        $customerTaxonomy = $this->setupCustomerTaxonomy($input, $output);
        $productTaxonomy = $this->setupProductTaxonomy($input, $output);
        $this->setupProducts($input, $output);


        $output->writeln('Finished setting up a demo store for country "' . $this->country . '" for type "' . $this->type . '"');
    }

    protected function setupCustomerTaxonomy($input, $output){

        $taxonomyManager = $this->getContainer()->get('vespolina.taxonomy_manager');
        $aTaxonomy = $taxonomyManager->createTaxonomy('customers', 'tags');
        $termFixtures = array();

        $termFixtures[] = array('code' => 'bronze', 'name' => 'Bronze');
        $termFixtures[] = array('code' => 'silver', 'name' => 'Silver');
        $termFixtures[] = array('code' => 'gold', 'name' => 'Gold');

        $taxonomyManager->updateTaxonomy($aTaxonomy, true);

        $output->writeln('Customer taxonomy has been setup with ' . count($termFixtures) . ' terms.' );
        return $aTaxonomy;
    }

    protected function setupProducts($input, $output)
    {
        $productCount = 10;

        $productManager = $this->getContainer()->get('vespolina.product_manager');

        for($i = 1; $i < $productCount; $i++) {

            $aProduct = $productManager->createProduct();
            $aProduct->setName('product ' . $i);
            $productManager->updateProduct($aProduct, true);
        }

        $output->writeln('Created ' . $productCount . ' products.' );
    }


    protected function setupProductTaxonomy($input, $output)
    {

        $taxonomyManager = $this->getContainer()->get('vespolina.taxonomy_manager');
        $aTaxonomy = $taxonomyManager->createTaxonomy('products', 'tags');

        $termFixtures = array();

        switch($this->type) {

            case 'band':

                $termFixtures = array();
                $termFixtures[] = array('code' => 'downloadable-tracks', 'name' => 'Downloadable tracks');
                break;

            case 'fashion':

                $termFixtures[] = array('code' => 'dresses', 'name' => 'Dresses');
                $termFixtures[] = array('code' => 'pants', 'name' => 'Pants');
                $termFixtures[] = array('code' => 'shoes', 'name' => 'Shoes');

                break;

        }
        foreach($termFixtures as $termFixture) {

            $aTerm = $aTaxonomy->createTerm($termFixture['code'], $termFixture['name']);
            $aTaxonomy->addTerm($aTerm);
        }

        $taxonomyManager->updateTaxonomy($aTaxonomy, true);

        $output->writeln('Product taxonomy has been setup with ' . count($termFixtures) . ' terms.' );

        return $aTaxonomy;
    }
}