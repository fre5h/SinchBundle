<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Model;

use Fresh\SinchBundle\Model\Identity;

/**
 * IdentityTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 *
 * @see \Fresh\SinchBundle\Model\Identity
 */
class IdentityTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $identity = new Identity();

        $this->assertNull($identity->getType());
        $this->assertNull($identity->getEndpoint());
    }

    public function testSetGetType()
    {
        $type = 'number';
        $identity = (new Identity())->setType($type);
        $this->assertEquals($type, $identity->getType());
    }

    public function testSetGetEndpoint()
    {
        $endpoint = '+46700000000';
        $identity = (new Identity())->setEndpoint($endpoint);
        $this->assertEquals($endpoint, $identity->getEndpoint());
    }
}
