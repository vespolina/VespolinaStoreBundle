<?php
namespace Vespolina\StoreBundle\Controller;

use Vespolina\Taxonomy\Specification\TaxonomyNodeSpecification;
use Vespolina\StoreBundle\Controller\AbstractController;

class TaxonomyController extends AbstractController
{
    protected $taxonomyManager;

    public function listNodesAction($taxonomyName = 'products', $currentTaxonomyNode = null)
    {
        $taxonomyNodes = $this->getTaxonomy($taxonomyName);

        return $this->render('VespolinaStoreBundle:Taxonomy:listNodesFlat.html.twig', array('nodes' => $taxonomyNodes, 'currentNode' => $currentTaxonomyNode));
    }

    protected function getTaxonomy($taxonomyName)
    {
        $this->taxonomyManager = $this->container->get('vespolina.taxonomy_manager');
        $nodeSpecification = new TaxonomyNodeSpecification($taxonomyName);
        //$nodeSpecification->depth(1);

        $taxonomyNodes = $this->taxonomyManager->matchAll($nodeSpecification);

        return $taxonomyNodes;
    }
}
