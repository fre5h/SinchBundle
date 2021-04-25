<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Model;

use Fresh\SinchBundle\Model\Identity;
use PHPUnit\Framework\TestCase;

/**
 * IdentityTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class IdentityTest extends TestCase
{
    public function testConstructor(): void
    {
        $identity = new Identity();

        self::assertNull($identity->getType());
        self::assertNull($identity->getEndpoint());
    }

    public function testSetGetType(): void
    {
        $type = 'number';
        $identity = (new Identity())->setType($type);
        self::assertEquals($type, $identity->getType());
    }

    public function testSetGetEndpoint(): void
    {
        $endpoint = '+46700000000';
        $identity = (new Identity())->setEndpoint($endpoint);
        self::assertEquals($endpoint, $identity->getEndpoint());
    }
}
