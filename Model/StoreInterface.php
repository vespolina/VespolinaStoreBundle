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
     * Get the store display name
     *
     * @abstract
     * @return string
     */
    function getDisplayName();

    function getLegalName();

    /**
     * Retrieve the sales channel of the store
     * A sales channel is typically in a sales order
     * to identify what the source was of the purchase
     *
     * @abstract
     *
     */
    function getSalesChannel();

    function setDisplayName($displayName);

    function setLegalName($legalName);

    function setSalesChannel($salesChannel);

    function setOperationalMode($operationalMode);

}
