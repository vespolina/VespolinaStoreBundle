<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Controller;

use Vespolina\Taxonomy\Specification\TaxonomyNodeSpecification;

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

        $taxonomyNodes = $this->taxonomyManager->matchAll($nodeSpecification);

        return $taxonomyNodes;
    }
}
