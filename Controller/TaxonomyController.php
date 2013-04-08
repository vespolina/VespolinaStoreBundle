<?php
namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class TaxonomyController extends AbstractController
{
    protected $taxonomyManager;

    public function listNodesAction($taxonomyName, $nodePath = '')
    {
        $rootTaxonomyNode = $this->getTaxonomy($taxonomyName, $nodePath);
        $taxonomyNodes = $rootTaxonomyNode->getChildren();

        return $this->render('VespolinaStoreBundle:Taxonomy:listNodesFlat.html.twig', array('taxonomyNodes' => $taxonomyNodes, 'currentTaxonomyNode' => currentTaxonomyNode));
    }

    protected function getTaxonomy($taxonomyName, $nodePath)
    {
        $this->taxonomyManager = $this->container->get('vespolina_taxonomy.taxonomy_manager');

        return $taxonomy = $this->taxonomyManager->find('products');
    }
}
