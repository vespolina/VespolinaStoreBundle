<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Process;

use Vespolina\StoreBundle\Process\ProcessDefinitionInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface ProcessInterface
{

    /**
     * Build the process definition (which steps to perform)
     *
     * @return ProcessDefinitionInterface $definition
     */
    function build();

    /**
     * Execute the process
     */
    function execute();

    //function executeProcessStep($name);
    //function getCurrentProcessStep();
    function getState();
    function init($firstTime = false);
}
