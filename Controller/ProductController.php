<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    public function detailAction($slug)
    {

        $productManager = $this->get('vespolina.product_manager');

        $product = $productManager->findProductBySlug($slug);

        return $this->render('VespolinaStoreBundle:Product:detail.html.twig', array('product' => $product));
    }

}
