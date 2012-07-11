<?php
/**
 * (c) 2011-2012 Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
if (file_exists($file = __DIR__.'/autoload.php')) {
    require_once $file;
} elseif (file_exists($file = __DIR__.'/autoload.php.dist')) {
    require_once $file;
}
