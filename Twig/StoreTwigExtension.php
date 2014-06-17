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
            /********************************* money_format not available on windows systems */
            //This is a server using Windows : money_format replacement.
            setlocale(LC_ALL, ''); // Locale will be different on each system.
            $locale = localeconv();
            //$amount = 1000000.97;
            //echo $locale['currency_symbol'],
            // number_format($amount, 2, $locale['decimal_point'], $locale['thousands_sep']);
            return $left . number_format($amount, 2, $locale['decimal_point'], $locale['thousands_sep']) . $right;
        } else {
            //This is a server not using Windows
            return $left . my_money_format('%i', $amount) . $right;
        }

    }

    public function getName()
    {
        return 'store_twig_extension';
    }

}