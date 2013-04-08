<?php

namespace Vespolina\StoreBundle\ProcessScenario\Setup\Step;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Vespolina\Entity\Pricing\Element\TotalDoughValueElement;
use Vespolina\Entity\Pricing\PricingSet;

class CreateProducts extends AbstractSetupStep
{
    public function execute(&$context) {

        $defaultTaxRate = $context['taxSchema']['defaultTaxRate'];
        $productCount = 10;

        //$productTaxonomyTerms = $context['productTaxonomy']->getTerms();
        //$keys = $productTaxonomyTerms->getKeys();

        $productManager = $this->getContainer()->get('vespolina.product_manager');

        for($i = 1; $i < $productCount; $i++) {

            //Get a random taxonomy term (= product category) to which we'll be attaching this product
            //$index = rand(0, $productTaxonomyTerms->count() - 1);
            //$aRandomTerm = $productTaxonomyTerms->get($keys[$index]);
            //$singularTermName = substr($aRandomTerm->getName(), 0, strlen($aRandomTerm->getName())-1);
            //$productName = ucfirst($singularTermName) . ' ' . $i;
            $productName = 'product ' . $i;
            $aProduct = $productManager->createProduct();
            $aProduct->setName($productName);
            $aProduct->setSlug($this->slugify($aProduct->getName()));   //Todo: move to manager

            //Set up a nice primary media item
            /**$imageBasePath = 'bundles' . DIRECTORY_SEPARATOR .
                'applicationvespolinastore' . DIRECTORY_SEPARATOR .
                'images' . DIRECTORY_SEPARATOR .
                $this->type . DIRECTORY_SEPARATOR . $singularTermName . '-' . $i ;
            ;*/

            //TODO: move into a pricing set builder

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
            $aProduct->setPricing(new PricingSet(new TotalDoughValueElement()));

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

        $this->getLogger()->addInfo('- Created ' . $productCount . ' sample products.' );
    }

    public function getName() {

        return 'create_products';
    }

    protected function slugify($text)
    {
        return preg_replace('/[^a-z0-9_\s-]/', '', preg_replace("/[\s_]/", "-", preg_replace('!\s+!', ' ', strtolower(trim($text)))));
    }
}
