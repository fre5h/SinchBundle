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

use Fresh\SinchBundle\Controller\SinchController;
use Fresh\SinchBundle\DependencyInjection\FreshSinchExtension;
use Fresh\SinchBundle\Service\Sinch;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Yaml\Parser;

/**
 * FreshSinchExtensionTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class FreshSinchExtensionTest extends TestCase
{
    /** @var FreshSinchExtension */
    private $extension;

    /** @var ContainerBuilder */
    private $container;

    protected function setUp(): void
    {
        $this->extension = new FreshSinchExtension();
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
    }

    public function testLoadExtension(): void
    {
        $yaml = <<<'CONFIG'
            fresh_sinch:
                key: some_dummy_key
                secret: some_dummy_secret
        CONFIG;
        $parser = new Parser();
        $config = $parser->parse($yaml);

        $this->extension->load($config, $this->container);
        $this->container->loadFromExtension($this->extension->getAlias(), $config['fresh_sinch']);
        $this->container->set('event_dispatcher', new \stdClass());
        $this->container->set('form.factory', new \stdClass());
        $this->container->compile();

        self::assertArrayHasKey(Sinch::class, $this->container->getRemovedIds());
        self::assertArrayHasKey(SinchController::class, $this->container->getRemovedIds());

        self::assertArrayNotHasKey(Sinch::class, $this->container->getDefinitions());
        self::assertArrayNotHasKey(SinchController::class, $this->container->getDefinitions());

        $this->expectException(ServiceNotFoundException::class);
        $this->container->get(Sinch::class);
        $this->container->get(SinchController::class);

        self::assertTrue($this->container->hasParameter('sinch.host'));
        self::assertTrue($this->container->hasParameter('sinch.key'));
        self::assertTrue($this->container->hasParameter('sinch.secret'));
        self::assertTrue($this->container->hasParameter('sinch.from'));
    }
}
