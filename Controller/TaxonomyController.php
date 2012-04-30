<?php

namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class TaxonomyController extends AbstractController
{

    public function listTermsAction($taxonomyName)
    {

        $taxonomy = $this->getTaxonomy($taxonomyName);
        $terms = $taxonomy->getTerms();
        return $this->render('VespolinaStoreBundle:Taxonomy:listTerms.html.twig', array('terms' => $terms));
    }


    protected function getTaxonomy($taxonomyName)
    {
        $taxonomyManager = $this->container->get('vespolina.taxonomy.taxonomy_manager');

        $taxonomy = $taxonomyManager->findTaxonomyById('products');

        if (!$taxonomy) {

        }
        return $taxonomy;
    }

}
