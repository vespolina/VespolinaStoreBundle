<?php
namespace Vespolina\StoreBundle\Controller;

use Vespolina\Taxonomy\Specification\TaxonomyNodeSpecification;
use Vespolina\StoreBundle\Controller\AbstractController;

class TaxonomyController extends AbstractController
{
    protected $taxonomyManager;

    public function listNodesAction($taxonomyName = 'products', $nodePath = '')
    {
        $taxonomyNodes = $this->getTaxonomy($taxonomyName, $nodePath);
        //$taxonomyNodes = $rootTaxonomyNode->getChildren();
        $currentTaxonomyNode = null;

        return $this->render('VespolinaStoreBundle:Taxonomy:listNodesFlat.html.twig', array('nodes' => $taxonomyNodes, 'currentNode' => $currentTaxonomyNode));
    }

    protected function getTaxonomy($taxonomyName, $nodePath)
    {
        $this->taxonomyManager = $this->container->get('vespolina.taxonomy_manager');
        $nodeSpecification = new TaxonomyNodeSpecification('products');
        //$nodeSpecification->depth(1);

        $taxonomyNodes = $this->taxonomyManager->matchAll($nodeSpecification);

        return $taxonomyNodes;
    }
}
