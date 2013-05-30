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
interface ProcessManagerInterface
{
    /**
     * Creates a new process instance by the process definition name
     *
     * @param $name Process definition name
     * @param null $owner Process owner (if relevant)
     * @return ProcessInterface
     */
    function createProcess($name, $owner = null);

    /**
     * Find a process by the process id
     *
     * @param $processId the unique process id
     * @return ProcessInterface
     */
    function findProcessById($processId);

}
