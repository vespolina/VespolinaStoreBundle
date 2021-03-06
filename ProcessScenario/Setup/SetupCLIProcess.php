<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\ProcessScenario\Setup;

use Vespolina\CommerceBundle\Process\AbstractProcess;
use Vespolina\CommerceBundle\Process\ProcessDefinition;

/**
 * This process models a setup of a V store using the command line
 *
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class SetupCLIProcess extends AbstractProcess
{
    protected $currentStepIndex;

    public function build()
    {
        $definition = new ProcessDefinition();
        $definition->addProcessStep('create_taxation',
                                    'Vespolina\StoreBundle\ProcessScenario\Setup\Step\CreateTaxation');
        $definition->addProcessStep('create_store',
                                    'Vespolina\StoreBundle\ProcessScenario\Setup\Step\CreateStore');

        return $definition;
    }

    public function execute()
    {
        foreach ($this->definition->getSteps() as $stepDefinition) {

            $processStep = new $stepDefinition['class']($this);
            $processStep->init();
            $processStep->execute($this->context);
        }
    }

    public function getName()
    {
        return 'setup_cli_process';
    }
}
