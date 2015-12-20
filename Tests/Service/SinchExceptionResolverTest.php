<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Service;

use Fresh\SinchBundle\Service\SinchExceptionResolver;
use use GuzzleHttp\Exception\ClientException;

/**
 * FreshSinchExtensionTest
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchExceptionResolverTest extends \PHPUnit_Framework_TestCase
{
//    /**
//     * @var FreshSinchExtension $extension FreshSinchExtension
//     */
//    private $sinchExceptionResolver;
//
//    /**
//     * {@inheritdoc}
//     */
//    protected function setUp()
//    {
//        $this->extension = new FreshSinchExtension();
//        $this->container = new ContainerBuilder();
//        $this->container->registerExtension($this->extension);
//    }

    /**
     * Test load extension
     *
     * @expectedException SinchParameterValidationException
     */
    public function testLoadExtension()
    {
        $yaml = <<<EOF
fresh_sinch:
    key: some_dummy_key
    secret: some_dummy_secret
EOF;
        $parser = new Parser();
        $config = $parser->parse($yaml);

        $this->extension->load($config, $this->container);
        $this->container->loadFromExtension($this->extension->getAlias(), $config['fresh_sinch']);
        $this->container->compile();

        // Check loaded resources
        $resources = $this->container->getResources();
        $resourceList = [];
        foreach ($resources as $resource) {
            if ($resource instanceof FileResource) {
                $path = $resource->getResource();
                $resourceList[] = substr($path, strrpos($path, '/') + 1);
            }
        }
        $this->assertContains('services.yml', $resourceList);

        // Check auto generated parameters
        $this->assertTrue($this->container->hasParameter('sinch.host'));
        $this->assertTrue($this->container->hasParameter('sinch.key'));
        $this->assertTrue($this->container->hasParameter('sinch.secret'));

        // Check that service has been loaded
        $this->assertTrue($this->container->has('sinch'));
    }
}
