<?php

namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{

    public function navBarAction()
    {
        $customer = $this->container->get('session')->get('customer');

        return $this->render('VespolinaStoreBundle:Customer:navBar.html.twig', array('customer' => $customer));
    }

}
