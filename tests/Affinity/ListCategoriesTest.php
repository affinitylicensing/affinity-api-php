<?php

namespace Affinity;

use Affinity\Error\ServerError;

class ListCategoriesTest extends DesignsTestCase
{
    /** @test */
    public function requests_categories_endpoint()
    {
        $this->designs->getCategories();

        // Should make 1 request
        $this->assertCount(1, $this->historyContainer);

        // That request should be a GET request to /product_categories
        $request = $this->historyContainer[0]['request'];
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/product_categories', $request->getUri()->getPath());
    }

    /** @test */
    public function request_has_appropriate_headers()
    {
        $this->designs->getCategories();

        $request = $this->historyContainer[0]['request'];
        $requestHeaders = $request->getHeaders();

        $this->assertEquals('application/json', $requestHeaders['Accept'][0]);
        $this->assertEquals('application/json', $requestHeaders['Content-Type'][0]);

        // API key header
        $this->assertEquals('test-api-key', $requestHeaders['X-Api-Key'][0]);
    }

    /** @test */
    public function request_includes_parameters()
    {
        $this->designs->getCategories(['is_available' => true]);

        $request = $this->historyContainer[0]['request'];

        $this->assertEquals('is_available=1', $request->getUri()->getQuery());
    }

    /** @test */
    public function throws_exception_on_non_200_response()
    {
        $handler = $this->getCustomMockHandler(500, ['message' => 'It broke']);
        $client = $this->getMockGuzzleClient($handler);
        $this->designs->setGuzzleClient($client);

        $this->expectException(ServerError::class);
        $this->designs->getCategories();
    }
}
