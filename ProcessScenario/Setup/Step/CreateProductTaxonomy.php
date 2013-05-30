<?php

namespace Vespolina\StoreBundle\ProcessScenario\Setup\Step;

use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vespolina\StoreBundle\Process\AbstractProcessStep;

class CreateProductTaxonomy extends AbstractSetupStep
{
    protected $taxonomyManager;

    public function init($firstTime = false) {

        $this->taxonomyManager = $this->getContainer()->get('vespolina_taxonomy.taxonomy_manager');
    }

    public function execute(&$context) {

        $taxonomyManager = $this->getContainer()->get('vespolina_taxonomy.taxonomy_manager');
        $productTaxonomyNode = $taxonomyManager->createTaxonomyNode('products');
        $taxonomyManager->updateTaxonomyNode($productTaxonomyNode, true);

        $termFixtures = array();

        switch($context['type']) {

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

            $node = $taxonomyManager->createTaxonomyNode($termFixture['name'], $productTaxonomyNode);
            $taxonomyManager->updateTaxonomyNode($node, true);

            //($termFixture['path'], $termFixture['name']);
            //$aTaxonomy->addTerm($aTerm);
        }

        $taxonomyManager->updateTaxonomyNode($productTaxonomyNode, true);
        $context['productTaxonomy'] = $productTaxonomyNode;

        $this->getLogger()->addInfo('Product taxonomy has been setup with ' . count($termFixtures) . ' terms.' );

    }

    public function getName() {

        return 'create_product_taxonomy';
    }
}
