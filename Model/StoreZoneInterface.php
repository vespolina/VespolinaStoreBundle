<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Model;

/**
 * A store zone represents a way to group related products together with associated news, CMS content, you name it.
 * Each store zone can potentially has its own taxonomy, product view layout and individual settings
 *
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface StoreZoneInterface
{
    /**
     * Name of this zone
     *
     * @abstract
     * @return mixed
     */
    function getName();

    /**
     * Name of the taxonomy associated with this zone
     *
     * @abstract
     * @return mixed
     */
    function getTaxonomyName();


    function setTaxonomyName($taxonomyName);

}
