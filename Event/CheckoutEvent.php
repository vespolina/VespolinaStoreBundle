<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Event;

use \Symfony\Component\HttpKernel\Event\KernelEvent;
use \Vespolina\OrderBundle\Model\SalesOrder;

class CheckoutEvent extends KernelEvent
{
    /**
     * @var \Vespolina\OrderBundle\Model\SalesOrder $order
     */
    protected $order;

    public function __construct(SalesOrder $order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
}