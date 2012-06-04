<?php
namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class TaxonomyController extends AbstractController
{
    protected $taxonomyManager;

    public function listTermsAction($taxonomyName, $currentTaxonomyTerm, $renderType)
    {
        $addAllTerm = true; //Add an 'All' category
        $taxonomy = $this->getTaxonomy($taxonomyName);
        $terms = $taxonomy->getTerms()->toArray();

        if ($addAllTerm) {

            $allTerm = $this->taxonomyManager->createTerm('all');
            $allTerm->setPath('_all');
            array_unshift($terms, $allTerm);
        }

        return $this->render('VespolinaStoreBundle:Taxonomy:listTerms' . $renderType . '.html.twig', array('terms' => $terms, 'currentTaxonomyTerm' => $currentTaxonomyTerm));
    }

    protected function getTaxonomy($taxonomyName)
    {
        $this->taxonomyManager = $this->container->get('vespolina.taxonomy.taxonomy_manager');

        return $taxonomy = $this->taxonomyManager->findTaxonomyById('products');
    }
}
