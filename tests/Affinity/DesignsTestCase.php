<?php

namespace Affinity;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

class DesignsTestCase extends TestCase
{

    /**
     * @var DesignsClient
     */
    protected $designs;

    /**
     * @var array
     */
    protected $historyContainer = [];

    public function setUp()
    {
        $this->designs = new DesignsClient(static::API_KEY, static::TEST_BASE);

        $client = $this->getMockGuzzleClient();

        $this->designs->setGuzzleClient($client);
    }

    /**
     * Mock responses used by Guzzle
     * @return MockHandler
     */
    protected function getMockHandler()
    {
        return new MockHandler([
            new Response(200, [], json_encode(['foo' => 'bar'])),
        ]);
    }

    protected function getCustomMockHandler($responseCode, $response)
    {
        return new MockHandler([
            new Response($responseCode, [], json_encode($response)),
        ]);
    }

    /**
     * Guzzle client used to mock request/responses for tests
     * @param MockHandler|null $mockHandler
     * @return GuzzleClient
     */
    protected function getMockGuzzleClient(MockHandler $mockHandler = null)
    {
        if ($mockHandler === null) {
            $mockHandler = $this->getMockHandler();
        }

        $this->historyContainer = [];
        $history = Middleware::history($this->historyContainer);

        $handler = HandlerStack::create($mockHandler);
        $handler->push($history);

        $clientOptions = $this->designs->getGuzzleClientOptions();
        $clientOptions['handler'] = $handler;

        return new GuzzleClient($clientOptions);
    }
}