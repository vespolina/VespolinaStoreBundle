<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaxonomyController extends Controller
{


    public function listTermsAction($taxonomyName)
    {

        $taxonomy = $this->getTaxonomy($taxonomyName);
        $terms = $taxonomy->getTerms();
        return $this->render('VespolinaStoreBundle:Taxonomy:listTerms.html.twig', array('terms' => $terms));
    }


    protected function getTaxonomy($taxonomyName)
    {
        $taxonomyManager = $this->get('vespolina.taxonomy.taxonomy_manager');

        $taxonomy = $taxonomyManager->findTaxonomyById('products');

        if (!$taxonomy) {

        }
        return $taxonomy;
    }

}
