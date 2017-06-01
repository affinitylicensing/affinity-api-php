<?php

namespace Affinity;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

use Affinity\Error\InvalidRequest;

class GetDesignTest extends DesignsTestCase
{

    protected $mockResponse = [
        ['id' => 1234, 'title' => 'My Design'],
    ];

    /**
     * Make Guzzle response a mock 'designs list' response
     * @return MockHandler
     */
    protected function getMockHandler()
    {
        return new MockHandler([
            new Response(200, [], json_encode($this->mockResponse)),
        ]);
    }

    /** @test */
    public function requests_design_endpoint()
    {
        $this->designs->getDesign(1234);

        // Should make 1 request
        $this->assertCount(1, $this->historyContainer);

        // That request should be a GET request to /designs/id
        $request = $this->historyContainer[0]['request'];
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/designs/1234', $request->getUri()->getPath());
    }

    /** @test */
    public function request_has_appropriate_headers()
    {
        $this->designs->getDesign(1234);
        $this->assertCount(1, $this->historyContainer);
        $request = $this->historyContainer[0]['request'];
        $requestHeaders = $request->getHeaders();

        $this->assertEquals('application/json', $requestHeaders['Accept'][0]);
        $this->assertEquals('application/json', $requestHeaders['Content-Type'][0]);

        // API key header
        $this->assertEquals('test-api-key', $requestHeaders['X-Api-Key'][0]);
    }

    /** @test */
    public function returns_design()
    {
        $designs = $this->designs->getDesign(1234);

        $this->assertCount(1, $this->historyContainer);
        $response = $this->historyContainer[0]['response'];
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals($this->mockResponse, $designs);
    }

    /** @test */
    public function throws_exception_on_non_200_response()
    {
        $handler = $this->getCustomMockHandler(400, ['message' => 'This is bad']);
        $client = $this->getMockGuzzleClient($handler);
        $this->designs->setGuzzleClient($client);

        $this->expectException(InvalidRequest::class);
        $this->designs->getDesign(1234);
    }
}
