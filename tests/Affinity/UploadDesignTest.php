<?php

namespace Affinity;

use Affinity\Error\InvalidRequest;

class UploadDesignTest extends DesignsTestCase
{

    /** @test */
    public function requests_design_endpoint()
    {
        $this->designs->uploadDesign([]);

        // Should make 1 request
        $this->assertCount(1, $this->historyContainer);

        // That request should be a POST request to /designs
        $request = $this->historyContainer[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/designs', $request->getUri()->getPath());
    }

    /** @test */
    public function request_has_appropriate_headers()
    {
        $this->designs->uploadDesign([]);

        $request = $this->historyContainer[0]['request'];
        $requestHeaders = $request->getHeaders();

        $this->assertEquals('application/json', $requestHeaders['Accept'][0]);
        $this->assertEquals('application/json', $requestHeaders['Content-Type'][0]);

        // API key header
        $this->assertEquals('test-api-key', $requestHeaders['X-Api-Key'][0]);
    }

    /** @test */
    public function request_includes_body()
    {
        $this->designs->uploadDesign([
            'image' => 'ABCDEF',
            'image_filename' => 'hello.jpg',
        ]);
        $request = $this->historyContainer[0]['request'];
        $requestBody = $request->getBody()->getContents();
        $jsonBody = json_decode($requestBody, true);
        $this->assertEquals(['image' => 'ABCDEF','image_filename' => 'hello.jpg'], $jsonBody);
    }

    /** @test */
    public function throws_exception_on_non_200_response()
    {
        $handler = $this->getCustomMockHandler(400, ['message' => 'This is bad']);
        $client = $this->getMockGuzzleClient($handler);
        $this->designs->setGuzzleClient($client);

        $this->expectException(InvalidRequest::class);
        $this->designs->uploadDesign([]);
    }
}
