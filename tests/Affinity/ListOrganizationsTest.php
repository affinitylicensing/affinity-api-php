<?php

namespace Affinity;

use Affinity\Error\ServerError;

class ListOrganizationsTest extends DesignsTestCase
{
    /** @test */
    public function requests_organizations_endpoint()
    {
        $this->designs->getOrganizations();

        // Should make 1 request
        $this->assertCount(1, $this->historyContainer);

        // That request should be a GET request to /clients
        $request = $this->historyContainer[0]['request'];
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/clients', $request->getUri()->getPath());
    }

    /** @test */
    public function request_has_appropriate_headers()
    {
        $this->designs->getOrganizations();

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
        $handler = $this->getCustomMockHandler(500, ['message' => 'It broke']);
        $client = $this->getMockGuzzleClient($handler);
        $this->designs->setGuzzleClient($client);

        $this->expectException(ServerError::class);
        $this->designs->getOrganizations();
    }
}
