<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Model;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class StoreZone implements StoreZoneInterface
{
    protected $displayName;
    protected $taxonomyName;
    protected $store;

    /**
     * Name of this zone
     *
     * @return mixed
     */
    function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Name of the taxonomy associated with this zone
     *
     * @return mixed
     */
    function getTaxonomyName()
    {
        return $this->taxonomyName;
    }

    public function setTaxonomyName($taxonomyName)
    {
        $this->taxonomyName = $taxonomyName;
    }

    public function setStore(StoreInterface $store)
    {
        $this->store = $store;
    }

    public function getStore()
    {
        return $this->store;
    }
}