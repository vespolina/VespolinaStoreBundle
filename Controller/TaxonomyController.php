<?php
namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class TaxonomyController extends AbstractController
{
    protected $taxonomyManager;

    public function listNodesAction($taxonomyName = 'products', $nodePath = '')
    {
        $rootTaxonomyNode = $this->getTaxonomy($taxonomyName, $nodePath);
        $taxonomyNodes = $rootTaxonomyNode->getChildren();
        $currentTaxonomyNode = null;

        return $this->render('VespolinaStoreBundle:Taxonomy:listNodesFlat.html.twig', array('nodes' => $taxonomyNodes, 'currentNode' => $currentTaxonomyNode));
    }

    protected function getTaxonomy($taxonomyName, $nodePath)
    {
        $this->taxonomyManager = $this->container->get('vespolina_taxonomy.taxonomy_manager');

        return $this->taxonomyManager->findOneByTaxonomyNodeName($taxonomyName);
    }
}
