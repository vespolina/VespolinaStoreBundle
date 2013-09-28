<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Entity;

use Vespolina\StoreBundle\Model\Store as AbstractStore;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 * @author Myke Hines <myke@webhines.com>
 */
class BaseStore extends AbstractStore
{
    protected $code;

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }
}
