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
interface StoreInterface
{

    /**
     * Get the store name
     *
     * @abstract
     * @return string
     */
    public function getName();

    /**
     * Retrieve the sales channel of the store
     * A sales channel is typically in a sales order
     * to identify what the source was of the purchase
     *
     * @abstract
     *
     */
    public function getSalesChannel();

    public function setName($name);

    public function setSalesChannel($salesChannel);

}
