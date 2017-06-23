<?php

namespace Affinity;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

use Affinity\Error\InvalidRequest;

class DesignsListTest extends DesignsTestCase
{

    protected $mockResponse = [
        ['id' => 1],
        ['id' => 2],
    ];

    public function setUp()
    {
        parent::setUp();
    }

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

    protected function getCustomMockHandler($responseCode, $response)
    {
        return new MockHandler([
            new Response($responseCode, [], json_encode($response)),
        ]);
    }

    /** @test */
    public function requests_designs_endpoint()
    {
        $this->designs->getList();

        // Should make 1 request
        $this->assertCount(1, $this->historyContainer);

        // That request should be a GET request to /designs
        $request = $this->historyContainer[0]['request'];
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/designs', $request->getUri()->getPath());
    }

    /** @test */
    public function request_has_appropriate_headers()
    {
        $this->designs->getList();
        $this->assertCount(1, $this->historyContainer);
        $request = $this->historyContainer[0]['request'];
        $requestHeaders = $request->getHeaders();

        $this->assertEquals('application/json', $requestHeaders['Accept'][0]);
        $this->assertEquals('application/json', $requestHeaders['Content-Type'][0]);

        // API key header
        $this->assertEquals('test-api-key', $requestHeaders['X-Api-Key'][0]);
    }

    /** @test */
    public function request_supports_pagination()
    {

        $handler = new MockHandler([
            new Response(200, [], json_encode(['foo' => 'bar'])),
            new Response(200, [], json_encode(['foo' => 'bar'])),
        ]);
        $client = $this->getMockGuzzleClient($handler);
        $this->designs->setGuzzleClient($client);

        $this->designs->getList();
        $this->designs->setPage(2);
        $this->designs->setPageSize(66);
        $this->designs->getList();

        $tests = [
            [
                'page=1',
                'page_size=50',
            ],
            [
                'page=2',
                'page_size=66',
            ],
        ];


        $this->assertCount(2, $this->historyContainer);
        foreach ($tests as $i => $historyTests) {
            $request = $this->historyContainer[$i]['request'];
            $queryString = $request->getUri()->getQuery();
            $queryArgs = explode('&', $queryString);
            foreach($historyTests as $test) {
                $this->assertContains($test, $queryArgs);
            }
        }



    }

    /** @test */
    public function returns_designs()
    {
        $designs = $this->designs->getList();

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
        $this->designs->getList();
    }
}
