<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Vespolina\StoreBundle\StoreEvents;

class NotificationTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = $this->createClient();
    }

    /**
     * @param array $options
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function getKernel(array $options = array())
    {
        if (!self::$kernel) {
            self::$kernel = $this->createKernel($options);
            self::$kernel->boot();
        }

        return self::$kernel;
    }

    public function testCustomerNotification()
    {
        $originalMailer = $this->getKernel()->getContainer()->get('mailer');

        // set up null mailer with logger
        $mailLogger = new \Swift_Plugins_MessageLogger();
        $transport = new \Swift_Transport_NullTransport(new \Swift_Events_SimpleEventDispatcher());
        $transport->registerPlugin($mailLogger);

        $mailer = new \Swift_Mailer($transport);
        // override mailer from container
        $this->getKernel()->getContainer()->set('mailer', $mailer);


        // Create an product for the order
        $productManager = $this->getKernel()->getContainer()->get('vespolina.product_manager');
        $product = $productManager->createProduct();
        $product->setName('Awesome product');

        // create an order
        $orderManager = $this->getKernel()->getContainer()->get('vespolina_sales_order.order_manager');
        $order = $orderManager->createSalesOrder();
        $orderItem = $orderManager->createItem($order);
        $orderItem->setProduct($product);
        $orderItem->setOrderedQuantity(2);

        // create a customer with an email address
        $partnerManager = $this->getKernel()->getContainer()->get('vespolina.partner_manager');
        $contact = $partnerManager->createPartnerContact();
        $contact->setEmail('example@domain.com');
        $customer = $partnerManager->createPartner();
        $customer->setPrimaryContact($contact);
        $order->setCustomer($customer);

        // fire the complete checkout event
        $event = new \Vespolina\StoreBundle\Event\CheckoutEvent($order);
        $dispatcher = $this->getKernel()->getContainer()->get('event_dispatcher');
        $dispatcher->dispatch(StoreEvents::COMPLETE_CHECKOUT, $event);

        // test that we got 1 send message
        $this->assertEquals($mailLogger->countMessages(), 1);

        $messages = $mailLogger->getMessages();
        // test that the message contains the product name
        $this->assertTrue(strpos($messages[0]->getBody(), 'Awesome product') !== false);

        // reset mailer in case we need to use it in another test
        $this->getKernel()->getContainer()->set('mailer', $originalMailer);
    }

}