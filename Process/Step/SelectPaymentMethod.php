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
class SelectPaymentMethod extends AbstractProcessStep
{
    protected $process;

    public function init()
    {
        $this->setDisplayName('payment method');
        $this->setName('select_payment_method');
    }

    public function execute($context)
    {

        $customerIdentified = false;

        if (!$customerIdentified) {

            $controller = $this->getController('Vespolina\StoreBundle\Controller\Process\PaymentMethodController');
            $controller->setProcessStep($this);
            $controller->setContainer($this->process->getContainer());

            return $controller->executeAction();
        } else {

            return true;    //Todo encapsulate return value
        }

    }

    public function executeProcessStep($name) {


        $processStep = $this->getProcessStep($name);

        if ($processStep) {

            return $processStep->execute();
        }
    }

    public function getName()
    {
        return 'select_payment_method';
    }


}