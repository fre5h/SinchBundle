<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Helper;

use Fresh\SinchBundle\Helper\SinchSupportedCountries;

/**
 * SinchSupportedCountriesTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchSupportedCountriesTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportedCountry()
    {
        $this->assertTrue(SinchSupportedCountries::isCountrySupported('UA'));
    }

    public function testUnsupportedCountry()
    {
        $this->assertFalse(SinchSupportedCountries::isCountrySupported('YO'));
    }
}
