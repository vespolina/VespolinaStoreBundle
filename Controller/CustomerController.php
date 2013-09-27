<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Controller;

class CustomerController extends AbstractController
{
    public function navBarAction()
    {
        $customer = $this->container->get('session')->get('customer');

        return $this->render('VespolinaStoreBundle:Customer:navBar.html.twig', array('customer' => $customer));
    }
}
