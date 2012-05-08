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

    public function init()
    {
        $this->setDisplayName('identify customer');
        $this->setName('identify_customer');
    }

    public function complete()
    {

    }
    public function execute($context)
    {

        $customerIdentified = false;

        if (!$customerIdentified) {

            $controller = $this->getController('Vespolina\StoreBundle\Controller\Process\IdentifyCustomerController');
            $controller->setProcessStep($this);
            $controller->setContainer($this->process->getContainer());

            return $controller->executeAction();

        } else {

            return true;    //Todo encapsulate return value
        }

    }


}