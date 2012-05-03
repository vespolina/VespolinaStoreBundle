<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vespolina\StoreBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function quickInspectionAction()
    {

        $cartManager = $this->container->get('vespolina.cart_manager');
        $cart = $this->getCart();

        $cartManager->determinePrices($cart);

        $totalPrice = $cart->getPricingSet()->get('total');

        return $this->render('VespolinaStoreBundle:Cart:quickInspection.html.twig', array('cart' => $cart, 'totalPrice' => $totalPrice ));
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
