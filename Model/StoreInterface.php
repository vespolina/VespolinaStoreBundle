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

    /**
     * Get the default country this store is aiming at
     * (might be different from the country of the legal name )
     *
     *
     * @abstract
     * @return mixed
     */
    function getDefaultCountry();

    /**
     * Get the default state this store is aiming at
     * @abstract
     * @return mixed
     */
    function getDefaultState();

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

    function setDefaultState($defaultState);

    function setDefaultCountry($defaultCountry);

    function setDisplayName($displayName);

    function setLegalName($legalName);

    function setSalesChannel($salesChannel);

    function setOperationalMode($operationalMode);

}
