<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Document;

use Vespolina\StoreBundle\Model\Store as AbstractStore;
/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class BaseStore extends AbstractStore
{
    protected $id;
}