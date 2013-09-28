<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\ProcessScenario\Checkout\Step;

use Vespolina\StoreBundle\Process\AbstractProcessStep;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class DetermineFulfillment extends AbstractProcessStep
{
    protected $process;

    public function init($firstTime = false)
    {
        $this->setDisplayName('delivery');
    }

    public function execute(&$context)
    {
        $customerIdentified = false;

        if (!$customerIdentified) {
            $controller = $this->getController('Vespolina\StoreBundle\Controller\Process\DetermineFulfillmentController');
            $controller->setContainer($this->process->getContainer());
            $controller->setProcessStep($this);

            return $controller->executeAction();
        } else {
            return true;    //Todo encapsulate return value
        }

    }

    public function getName()
    {
        return 'determine_fulfillment';
    }
}
