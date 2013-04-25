<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Process;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface ProcessStepInterface
{

    function init($firstTime = false);

    /**
     * Execute the process step with the provided process context
     *
     * @param $context
     * @return mixed
     */
    function execute(&$context);

    /**
     * Name of the process step
     * @return mixed
     */
    function getName();

}
