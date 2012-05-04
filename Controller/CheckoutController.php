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

            $checkoutProcess = $processManager->createProcess('checkout_b2c');

        }

        $checkoutProcess->init();

        $processResult = $checkoutProcess->execute();

        if ($processResult) {

            return $processResult;
        }

        //If we get here then there was a serious error
        throw new \Exception('Checkout failed - internal error');
        //return $this->render('VespolinaStoreBundle:Cart:quickInspection.html.twig', array('cart' => $cart, 'totalPrice' => $totalPrice ));
    }


}
