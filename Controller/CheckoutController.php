<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vespolina\StoreBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    public function checkoutAction()
    {

        $processManager = $this->container->get('vespolina.process_manager');
        $processOwner = $this->container->get('session')->getId();

        $checkoutProcess = $processManager->getActiveProcessByOwner('checkout_b2c', $processOwner);

        if (!$checkoutProcess) {

            $checkoutProcess = $processManager->createProcess('checkout_b2c', $processOwner);
            $checkoutProcess->init(true);   //initialize with first time set to true

            $context = $checkoutProcess->getContext();
            $context['cart'] = $this->getCart();
            $processResult = $checkoutProcess->execute();


            //Persist (in session)
            $processManager->updateProcess($checkoutProcess);

        } else{

            $checkoutProcess->init();
        }

        if ($processResult) {

            return $processResult;
        }

        //If we get here then there was a serious error
        throw new \Exception('Checkout failed - internal error');
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
