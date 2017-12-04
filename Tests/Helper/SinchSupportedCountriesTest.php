<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Helper;

use Fresh\SinchBundle\Helper\SinchSupportedCountries;
use PHPUnit\Framework\TestCase;

/**
 * SinchSupportedCountriesTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class SinchSupportedCountriesTest extends TestCase
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
