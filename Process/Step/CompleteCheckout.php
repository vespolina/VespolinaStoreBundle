<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process\Step;

use Vespolina\OrderBundle\Model\SalesOrderInterface;
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

        $salesOrderManager = $this->getProcess()->getContainer()->get('vespolina_sales_order.sales_order_manager');
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

        //Todo: move to a service/manager
        $store = $this->getProcess()->getContainer()->get('vespolina.store.store_resolver')->getStore();

        $context = $this->getContext();
        $salesOrder = $salesOrderManager->createSalesOrder('default');
        $salesOrder->setCustomer($context->get('customer'));
        $salesOrder->setOrderDate(new \DateTime());
        $salesOrder->setOrderState('unprocessed');
        $salesOrder->setSalesChannel($store->getSalesChannel());
        $salesOrder->setPricingSet($cart->getPricingSet());

        $paymentAgreement = $salesOrderManager->createPaymentAgreement();
        $paymentMethodData = $context->get('payment_method');
        $paymentAgreement->setType($paymentMethodData['payment_method']);
        $paymentAgreement->setState('paid');

        $salesOrder->setPaymentAgreement($paymentAgreement);

        $fulfillmentAgreement = $salesOrderManager->createFulfillmentAgreement();
        $fulfillmentMethodData = $context->get('fulfillment_method');
        $fulfillmentAgreement->setType($fulfillmentMethodData['fulfillment_method']);
        $fulfillmentAgreement->setState('warehouse_notice');
        $fulfillmentAgreement->setServiceLevel('express_delivery');

        $salesOrder->setFulfillmentAgreement($fulfillmentAgreement);
        //$salesOrder->setCustomerComment('Hey, If possible can I get a free bag?');

        //Item data
        foreach($cart->getItems() as $cartItem) {

            $salesOrderItem = $salesOrderManager->createItem($salesOrder);
            $salesOrderItem->setOrderedQuantity($cartItem->getQuantity());
            $salesOrderItem->setProduct($cartItem->getCartableItem());
            $salesOrderItem->setItemState('open');
            $salesOrderItem->setPricingSet($cartItem->getPricingSet());
        }

        return $salesOrder;
    }

    protected function notifyPartners(SalesOrderInterface $salesOrder)
    {
        //Notify the customer
        $dispatcher = $this->getProcess()->getContainer()->get('event_dispatcher');
        $event = new CheckoutEvent($salesOrder);
        $dispatcher->dispatch(StoreEvents::COMPLETE_CHECKOUT, $event);
    }

}
