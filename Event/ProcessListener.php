<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Event;

use Symfony\Component\DependencyInjection\Container;
use Vespolina\Entity\Order\OrderInterface;

class ProcessListener
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onCompleteCheckout(CheckoutEvent $event)
    {
        $salesOrder = $event->getOrder();

        if (!$salesOrder->getOwner()->getPrimaryContact()->getEmail()) {
            return;
        }

        $customer = $salesOrder->getOwner();

        $mailer = $this->container->get('mailer');
        $translator = $this->container->get('translator');
        $message = \Swift_Message::newInstance();
        // @TODO: Should be configurable
        $message->setContentType('text/html');
        $message->setTo($customer->getPrimaryContact()->getEmail(), $customer->getName());
        $message->setSubject($translator->trans('Order confirmation'));
        $message->setBody($this->getMailBody($salesOrder));

        try {
            $mailer->send($message);
        } catch (\Swift_SwiftException $e) {
            $this->container->get('logger')->err('Exception while sending email', array($e->getMessage()));
        }
    }

    protected function getMailBody(OrderInterface $salesOrder)
    {
        // @TODO: Make template configurable
        $twig = $this->container->get('twig');

        return $twig->render('VespolinaStoreBundle:Email:checkout_complete.html.twig', array('order' => $salesOrder));
    }
}