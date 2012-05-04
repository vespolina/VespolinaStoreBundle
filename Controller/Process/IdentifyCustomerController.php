<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;

class IdentifyCustomerController extends AbstractProcessStepController
{
    public function executeAction()
    {
        // We came here because the checkout process 'identify customer' step could not determine the identity of the customer

        return $this->render('VespolinaStoreBundle:Process:Step/identifyCustomer.html.twig', array('processStep' => $this->processStep));
    }

    protected function getCart($cartId = null)
    {
        if ($cartId) {
            return $this->container->get('vespolina.cart_manager')->findCartById($cartId);
        } else {
            return $this->container->get('vespolina.cart_manager')->getActiveCart();
        }
    }

}
