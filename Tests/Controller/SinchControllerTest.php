<?php
/*
 * This file is part of the FreshSinchBundle
 *
 * (c) Artem Genvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\SinchBundle\Tests\Controller;

use Fresh\SinchBundle\Event\SinchEvents;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * SinchControllerTest.
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class SinchControllerTest extends WebTestCase
{
    const DEFAULT_SINCH_CALLBACK_URL = '/sinch/callback';

    /**
     * @var Client $client Client
     */
    private $client;

    private $eventIsDispatched;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->eventIsDispatched = false;
    }

    protected function tearDown()
    {
        unset($this->client);
        $this->eventIsDispatched = false;
    }

    public function testValidCallback()
    {
        $this->client->getContainer()->get('event_dispatcher')->addListener(SinchEvents::CALLBACK_RECEIVED, function () {
            $this->eventIsDispatched = true;
        });

        $this->client->request(
            'POST',
            self::DEFAULT_SINCH_CALLBACK_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "event": "incomingSms",
    "to": {
        "type": "number",
        "endpoint": "+46700000000"
    },
    "from": {
        "type": "number",
        "endpoint": "+46700000001"
    },
    "message": "Hello world",
    "timestamp": "2014-12-01T12:00:00Z",
    "version": 1
}
JSON
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->eventIsDispatched);
    }

    /**
     * @dataProvider invalidContentProvider
     */
    public function testInvalidCallbackContent($content)
    {
        $this->client->request(
            'POST',
            self::DEFAULT_SINCH_CALLBACK_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('Bad Request', $response->getContent());
        $this->assertFalse($this->eventIsDispatched);
    }

    public function invalidContentProvider()
    {
        return [
            [
                <<<'JSON'
    {
        "foo": "bar"
    }
JSON
            ],
            [
                <<<'JSON'
{
    "event": "unknownEvent",
    "to": {
        "type": "number",
        "endpoint": "+46700000000"
    },
    "from": {
        "type": "number",
        "endpoint": "+46700000001"
    },
    "message": "Hello world",
    "timestamp": "2014-12-01T12:00:00Z",
    "version": 1
}
JSON
            ],
        ];
    }

    public function testInternalServerError()
    {
        $eventDispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher')
                                ->disableOriginalConstructor()
                                ->setMethods(['dispatch'])
                                ->getMock();

        $eventDispatcher->expects($this->once())
                        ->method('dispatch')
                        ->willThrowException(new \Exception());

        $this->client->getKernel()->getContainer()->set('event_dispatcher', $eventDispatcher);

        $this->client->request(
            'POST',
            self::DEFAULT_SINCH_CALLBACK_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            <<<'JSON'
{
    "event": "incomingSms",
    "to": {
        "type": "number",
        "endpoint": "+46700000000"
    },
    "from": {
        "type": "number",
        "endpoint": "+46700000001"
    },
    "message": "Hello world",
    "timestamp": "2014-12-01T12:00:00Z",
    "version": 1
}
JSON
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $this->client->getResponse()->getStatusCode());
    }
}
