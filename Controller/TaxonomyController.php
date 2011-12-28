<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaxonomyController extends Controller
{


    public function listTermsAction($taxonomy)
    {

        $terms = $taxonomy->getTerms();

        return $this->render('VespolinaStoreBundle:Taxonomy:listTerms.html.twig', array('terms' => $terms));
    }

}
