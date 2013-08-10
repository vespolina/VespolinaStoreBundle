<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Event;

use \Symfony\Component\HttpKernel\Event\KernelEvent;
use \Vespolina\Entity\Order\OrderInterface;

class CheckoutEvent extends KernelEvent
{
    /**
     * @var \Vespolina\Entity\Order\OrderInterface $order
     */
    protected $order;

    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
}