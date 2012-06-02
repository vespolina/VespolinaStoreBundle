<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vespolina\StoreBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function quickInspectionAction()
    {

        $cartManager = $this->container->get('vespolina.cart_manager');
        $cart = $this->getCart();

        $cartManager->determinePrices($cart);   //Todo: pricing determination should only done once instead on every request

        $totalPrice = $cart->getPricingSet()->get('total');

        return $this->render('VespolinaStoreBundle:Cart:quickInspection.html.twig', array('cart' => $cart, 'totalPrice' => $totalPrice ));
    }

    public function showAction($cartId = null)
    {
        $cart = $this->getCart($cartId);

        $template = $this->container->get('templating')->render(sprintf('VespolinaStoreBundle:Cart:show.html.%s', $this->getEngine()), array('cart' => $cart));
        return new Response($template);

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
