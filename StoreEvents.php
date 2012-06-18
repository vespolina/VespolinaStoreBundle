<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle;

final class StoreEvents
{
    /**
     * The complete checkout event is thrown when the checkout
     * is completed
     *
     * @var string
     */
    const COMPLETE_CHECKOUT = 'vespolina_store.complete_checkout';
}