<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\ProcessScenario\Checkout\Step;

use Vespolina\Entity\OrderInterface;
use Vespolina\StoreBundle\Process\AbstractProcessStep;
use Vespolina\StoreBundle\StoreEvents;
use Vespolina\StoreBundle\Event\CheckoutEvent;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class CompleteCheckout extends AbstractProcessStep
{
    protected $process;

    public function init($firstTime = false)
    {
        $this->setDisplayName('complete');
    }

    public function execute($context)
    {
        //Copy cart -> sales order
        $cart = $this->getContext()->get('cart');

        $salesOrderManager = $this->getProcess()->getContainer()->get('vespolina.sales_order_manager');
        $salesOrder = $this->createSalesOrderFromCart($cart, $salesOrderManager);

        if (null != $salesOrder) {
            $salesOrderManager->updateSalesOrder($salesOrder, true);

            //Notify involved partners about the sales order.  Tod: Move into a dispatcher event listener
            $this->notifyPartners($salesOrder);


        }
        //Reset session cart
        $cart->clearItems();

        //Signal enclosing process step that we are done here
//        $this->complete();

        $controller = $this->getController('Vespolina\StoreBundle\Controller\Process\CompleteCheckoutController');
        $controller->setProcessStep($this);
        $controller->setContainer($this->process->getContainer());

        return $controller->executeAction();
    }


    public function getName()
    {
        return 'complete_checkout';
    }


    protected function createSalesOrderFromCart($cart, $salesOrderManager) {

        $store = $this->getProcess()->getContainer()->get('vespolina_store.store_resolver')->getStore();
        $salesOrderManipulator = $this->getProcess()->getContainer()->get('vespolina_order.order_manipulator');

        $context = $this->getContext();

        //Do a basic copy process from cart to a sales order
        $salesOrder = $salesOrderManipulator->createSalesOrderFromCart($cart);

        $salesOrder->setCustomer($context->get('customer'));
        $salesOrder->setSalesChannel($store->getSalesChannel());

        $paymentAgreement = $salesOrder->getPaymentAgreement();
        $paymentMethodData = $context->get('payment_method');
        $paymentAgreement->setType($paymentMethodData['payment_method']);
        $paymentAgreement->setState('paid');

        $fulfillmentAgreement = $salesOrder->getFulfillmentAgreement();
        $fulfillmentMethodData = $context->get('fulfillment_method');
        $fulfillmentAgreement->setType($fulfillmentMethodData['fulfillment_method']);
        $fulfillmentAgreement->setState('warehouse_notice');
        $fulfillmentAgreement->setServiceLevel('express_delivery');

        $salesOrder->setFulfillmentAgreement($fulfillmentAgreement);

        return $salesOrder;
    }

    protected function notifyPartners(OrderInterface $salesOrder)
    {
        //Notify the customer
        $dispatcher = $this->getProcess()->getContainer()->get('event_dispatcher');
        $event = new CheckoutEvent($salesOrder);
        $dispatcher->dispatch(StoreEvents::COMPLETE_CHECKOUT, $event);
    }

}
