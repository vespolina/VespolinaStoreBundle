<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vespolina\StoreBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    public function detailAction($slug)
    {

        $productManager = $this->container->get('vespolina.product_manager');

        $product = $productManager->findProductBySlug($slug);

        return $this->render('VespolinaStoreBundle:Product:detail.html.twig', array('product' => $product));
    }

}
