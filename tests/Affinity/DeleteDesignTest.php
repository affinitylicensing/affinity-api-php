<?php

namespace Affinity;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

use Affinity\Error\ServerError;

class DeleteDesignTest extends DesignsTestCase
{

    /**
     * Make Guzzle response a mock empty JSON response
     * @return MockHandler
     */
    protected function getMockHandler()
    {
        return new MockHandler([
            new Response(200, [], json_encode([])),
        ]);
    }

    /** @test */
    public function requests_delete_endpoint()
    {
        $this->designs->deleteDesign(12345);

        // Should make 1 request
        $this->assertCount(1, $this->historyContainer);

        // That request should be a DELETE request to /designs/id
        $request = $this->historyContainer[0]['request'];
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('/designs/12345', $request->getUri()->getPath());
    }

    /** @test */
    public function request_has_appropriate_headers()
    {
        $this->designs->deleteDesign(12345);
        $request = $this->historyContainer[0]['request'];
        $requestHeaders = $request->getHeaders();

        $this->assertEquals('application/json', $requestHeaders['Accept'][0]);
        $this->assertEquals('application/json', $requestHeaders['Content-Type'][0]);

        // API key header
        $this->assertEquals('test-api-key', $requestHeaders['X-Api-Key'][0]);
    }

    /** @test */
    public function throws_exception_on_non_200_response()
    {
        $handler = $this->getCustomMockHandler(500, ['message' => 'It broke.']);
        $client = $this->getMockGuzzleClient($handler);
        $this->designs->setGuzzleClient($client);

        $this->expectException(ServerError::class);
        $this->designs->deleteDesign(12345);
    }
}
