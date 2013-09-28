<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Entity;

use Vespolina\StoreBundle\Model\StoreZone as AbstractStoreZone;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 * @author Myke Hines <myke@webhines.com>
 */
class BaseStoreZone extends AbstractStoreZone
{
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
