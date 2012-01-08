<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StoreController extends Controller
{
    public function indexAction()
    {

        $productTaxonomy = $this->getDefaultProductTaxonomy();

        return $this->render('VespolinaStoreBundle:Store:index.html.twig', array('productTaxonomy' => $productTaxonomy));
    }

    public function listAction()
    {
        $products = $this->get('vespolina.product_manager')->findBy(array());

        return $this->render('VespolinaStoreBundle:Store:list.html.twig', array('products' => $products));
    }


    protected function getDefaultProductTaxonomy()
    {
        $taxonomyManager = $this->get('vespolina.taxonomy_manager');

        $productTaxonomy = $taxonomyManager->findTaxonomyById('products');

        if (!$productTaxonomy) {

        }
        return $productTaxonomy;
    }
}
