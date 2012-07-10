<?php

namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{

    public function navBarAction()
    {
        $customer = $this->container->get('session')->get('customer');
        //print_r($this->container->get('session')->getAttributes());
        return $this->render('VespolinaStoreBundle:Customer:navBar.html.twig', array('customer' => $customer));
    }

}
