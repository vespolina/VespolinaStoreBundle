<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Process\Step;

use Vespolina\StoreBundle\Process\AbstractProcessStep;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class IdentifyCustomer extends AbstractProcessStep
{
    protected $process;

    public function init($firstTime = false)
    {
        $this->setDisplayName('identify customer');
    }

    public function execute($context)
    {
        if ($this->isCustomerIdentified()) {

            return $this->complete();

        } else {

            $controller = $this->getController('Vespolina\StoreBundle\Controller\Process\IdentifyCustomerController');
            $controller->setProcessStep($this);
            $controller->setContainer($this->process->getContainer());

            return $controller->executeAction();
        }
    }

    public function getName()
    {
        return 'identify_customer';
    }

    protected function isCustomerIdentified()
    {
        //First attempt to load the customer from the process container
        $customer = $this->getContext()->get('customer');

        if (!$customer) {
            //If this fails get it from the user session
            if ($customer = $this->process->getContainer()->get('session')->get('customer')) {
                $this->getContext()->set('customer', $customer);
            }
        }

        return null !== $customer;
    }
}