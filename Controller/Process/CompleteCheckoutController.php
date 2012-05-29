<?php

namespace Vespolina\StoreBundle\Controller\Process;

use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Controller\Process\AbstractProcessStepController;
use Vespolina\StoreBundle\Form\Type\Process\SelectPaymentMethodFormType;

//Todo: remove mongo hard coding
use Vespolina\OrderBundle\Document\FulfillmentAgreement;
use Vespolina\OrderBundle\Document\PaymentAgreement;

class CompleteCheckoutController extends AbstractProcessStepController
{
    public function executeAction()
    {
        $process = $this->getProcessStep()->getProcess();
        $processManager = $this->container->get('vespolina.process_manager');
        $request = $this->container->get('request');
        $salesOrderManager = $this->container->get('vespolina_sales_order.sales_order_manager');

        //Copy cart -> sales order
        $cart = $this->container->get('session')->get('cart');
        $cart = $process->getContext()->get('cart');

        $salesOrder = $this->createSalesOrderFromCart($cart, $salesOrderManager);

        if ($salesOrder) {
            $salesOrderManager->updateSalesOrder($salesOrder, true);
        }
        //Reset session cart
        $cart->clearItems();


        //Signal enclosing process step that we are done here
        $process->completeProcessStep($this->processStep);
        $processManager->updateProcess($process);

        //Todo: Kill this process in session


        return $this->render('VespolinaStoreBundle:Process:Step/completeCheckout.html.twig',
            array('currentProcessStep' => $this->processStep));
    }

    protected function createSalesOrderFromCart($cart, $salesOrderManager) {

        $context = $this->processStep->getContext();
        $salesOrder = $salesOrderManager->createSalesOrder('default');
        $salesOrder->setCustomer($context->get('customer'));
        $salesOrder->setOrderDate(new \DateTime());
        $salesOrder->setOrderState('paid');
        $salesOrder->setSalesChannel('webshop-foo.com');


        $paymentAgreement = new PaymentAgreement();
        $paymentAgreement->setType($context->get('payment_method')['payment_method']);
        $paymentAgreement->setState('paid');

        $salesOrder->setPaymentAgreement($paymentAgreement);


        $fulfillmentAgreement = new FulfillmentAgreement();
        $fulfillmentAgreement->setType($context->get('fulfillment_method')['fulfillment_method']);
        $fulfillmentAgreement->setState('warehouse_notice');
        $fulfillmentAgreement->setServiceLevel('express_delivery');

        $salesOrder->setFulfillmentAgreement($fulfillmentAgreement);
        //$salesOrder->setCustomerComment('Hey, If possible can I get a free bag?');

        //Item data
        foreach($cart->getItems() as $cartItem) {

            $salesOrderItem = $salesOrderManager->createItem($salesOrder);
        }

        return $salesOrder;
    }
}
