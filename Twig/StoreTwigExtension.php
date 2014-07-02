<?php

/**
 * (c) 2011 Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Twig;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class StoreTwigExtension extends \Twig_Extension
{
    public function getFilters() {
        return array(
            'price_format'   => new \Twig_Filter_Method($this, 'priceFormat'),
        );
    }

    public function priceFormat($amount, $currency = null)
    {
        $left = '';
        $right = '';

        switch ($currency) {

            case 'EUR':  $right = ' €'; break;
            case 'USD' :  $left = '$ '; break;
            case '%' : $right = ' %'; break;
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            setlocale(LC_ALL, '');
            $locale = localeconv();
            return $left . number_format($amount, 2, $locale['decimal_point'], $locale['thousands_sep']) . $right;
        } else {
            return $left . money_format('%i', $amount) . $right;
        }
    }

    public function getName()
    {
        return 'store_twig_extension';
    }

}