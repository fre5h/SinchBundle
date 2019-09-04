<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\DependencyInjection;

use Fresh\SinchBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * ConfigurationTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testInvalidConfiguration(): void
    {
        self::assertConfigurationIsInvalid(
            [
                [
                    'invalid_parameter' => 123,
                ],
            ],
            'invalid_parameter'
        );
    }

    public function testValidDefaultConfiguration(): void
    {
        self::assertProcessedConfigurationEquals(
            [],
            [
                'host' => 'https://messagingapi.sinch.com',
                'from' => null,
            ]
        );
    }

    public function testValidConfigurationWithHost(): void
    {
        self::assertProcessedConfigurationEquals(
            [
                [
                    'host' => 'https://test.com',
                    'key' => '1234567890',
                    'secret' => 'qwerty',
                ],
            ],
            [
                'host' => 'https://test.com',
                'key' => '1234567890',
                'secret' => 'qwerty',
                'from' => null,
            ]
        );
    }

    public function testValidConfigurationWithoutFrom(): void
    {
        self::assertProcessedConfigurationEquals(
            [
                [
                    'key' => '1234567890',
                    'secret' => 'qwerty',
                ],
            ],
            [
                'host' => 'https://messagingapi.sinch.com',
                'key' => '1234567890',
                'secret' => 'qwerty',
                'from' => null,
            ]
        );
    }

    public function testValidConfigurationWithFrom(): void
    {
        self::assertProcessedConfigurationEquals(
            [
                [
                    'key' => '1234567890',
                    'secret' => 'qwerty',
                    'from' => 'Santa Claus',
                ],
            ],
            [
                'host' => 'https://messagingapi.sinch.com',
                'key' => '1234567890',
                'secret' => 'qwerty',
                'from' => 'Santa Claus',
            ]
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
