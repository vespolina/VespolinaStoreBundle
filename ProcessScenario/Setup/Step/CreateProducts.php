<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\ProcessScenario\Setup\Step;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vespolina\Entity\Pricing\Element\TotalDoughValueElement;
use Vespolina\Entity\Pricing\PricingSet;
use Vespolina\Pricing\Manager\PricingManager;
use Vespolina\Entity\Product\Product;

class CreateProducts extends AbstractSetupStep
{
    protected $pricingManager;

    public function execute(&$context)
    {
        $defaultTaxRate = $context['taxSchema']['defaultTaxRate'];
        $productCount = 10;

        $productTaxonomy = null;
        $productTaxonomyNodes = new ArrayCollection();
        if (isset($context['productTaxonomy'])) {
            /* @var $productTaxonomy \Vespolina\Entity\Taxonomy\TaxonomyNodeInterface */
            $productTaxonomy = $context['productTaxonomy'];
            $productTaxonomyNodes = $productTaxonomy->getChildren();
        }

        $productManager = $this->getContainer()->get('vespolina.product_manager');

        for ($i = 1; $i < $productCount; $i++) {

            if ($productTaxonomyNodes->count()) {
                //Pick a random taxonomy node (= product category) to which we'll be attaching this product
                $index = rand(0, $productTaxonomyNodes->count() - 1);
                $aRandomTaxonomyNode = $productTaxonomyNodes->get($index);

                //Determine the product name from the taxonomy name (eg. category "beer" -> product name is "beer 1"
                $singularNodeName = substr($aRandomTaxonomyNode->getName(), 0, strlen($aRandomTaxonomyNode->getName())-1);
                $productName = ucfirst($singularNodeName) . ' ' . $i;
            } else {
                $singularNodeName = $productName = 'Foo ' . $i;
            }
            $aProduct = $productManager->createProduct();
            $aProduct->setName($productName);
            $aProduct->setSlug($this->productManager->slugify($aProduct->getName()));
            $aProduct->setType(Product::PHYSICAL);
            //Set up a nice primary media item
            /**$imageBasePath = 'bundles' . DIRECTORY_SEPARATOR .
                'applicationvespolinastore' . DIRECTORY_SEPARATOR .
                'images' . DIRECTORY_SEPARATOR .
                $this->type . DIRECTORY_SEPARATOR . $singularTermName . '-' . $i ;
            ;*/

            //TODO: move into a pricing set builder

            /** Set up for each product following pricing elements
             *  - netValue : unit price without tax
             *  - unitPriceMSRP: manufacturer suggested retail price without tax
             *  - unitPriceTax : tax over the net unit price (based on the default tax rate)
             *  - unitPriceTotal: final price a customer pays ( net unit price + tax )
             *  - unitMSRPTotal: manufacturer suggested retail price with tax
             **/


            $pricingValues = array();
            $pricingValues['netValue'] = rand(2,80);

            //Set Manufacturer Suggested Retail Price to +(random) % of the net unit price
            $pricingValues['MSRPDiscountRate'] = rand(10,35);
            $pricingValues['unitPriceMSRP'] = $pricingValues['netValue'] * ( 1 + $pricingValues['MSRPDiscountRate'] / 100);


            if ($defaultTaxRate) {

                $pricingValues['unitPriceTax'] = $pricingValues['netValue'] / 100 * $defaultTaxRate;
                $pricingValues['unitPriceMSRPTotal'] = $pricingValues['unitPriceMSRP'] * (1 + $defaultTaxRate / 100);
                $pricingValues['unitPriceTotal'] = $pricingValues['netValue'] + $pricingValues['unitPriceTax'];

            } else {

                $pricingValues['unitPriceTotal'] = $pricingValues['netValue'];
                $pricingValues['unitPriceMSRPTotal'] = $pricingValues['unitPriceMSRP'];
            }

            $pricingSet = $this->getPricingManager()->createPricing($pricingValues);
            $aProduct->setPricing($pricingSet);

            //TODO: fix taxonomy
            //$aProduct->addTerm($aRandomTerm);

            $productManager->updateProduct($aProduct, true);
            /**
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
             */
        }

        $this->getLogger()->addInfo('Created ' . $productCount . ' sample products.' );
    }

    public function getName()
    {
        return 'create_products';
    }

    protected function getPricingManager()
    {
        if (null == $this->pricingManager) {
            $this->pricingManager = new PricingManager();

            //Register the 'default_product' configuration
            $this->pricingManager->addConfiguration('default_product', 'Vespolina\Entity\Pricing\PricingSet', array());
        }

        // new TotalDoughValueElement; @todo!!! not sure why this was here :)

        return $this->pricingManager;
    }

    protected function slugify($text)
    {
        return preg_replace('/[^a-z0-9_\s-]/', '', preg_replace("/[\s_]/", "-", preg_replace('!\s+!', ' ', strtolower(trim($text)))));
    }
}
