<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
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
     * Get the technical store name
     *
     * @abstract
     * @return string
     */
    function getName();

    /**
     * Retrieve store settings
     *
     * @return mixed
     */
    function getSettings();

    /**
     * Get an individual setting by it's name
     *
     * @param $name
     * @param $default
     * @return mixed
     */
    function getSetting($name, $default = '');
}
