<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Handler;
use Vespolina\StoreBundle\Model\StoreZoneInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface StoreHandlerInterface
{
    /**
     * Which operational mode does this handler covers (eg. standard, dailyDeal, ...)
     * @abstract
     */
    function getOperationalMode();

    /**
     * Determines for a given zone which products should be displayed
     * @abstract
     */
    function getZoneProducts(StoreZoneInterface $storeZone, array $context);

    /**
     * Render the store zone for the given context
     *
     * @abstract
     * @param \Vespolina\StoreBundle\Model\StoreZoneInterface $storeZone
     * @param array $context
     * @return mixed
     */
    function renderStoreZone(StoreZoneInterface $storeZone, $templating, array $context);

}
