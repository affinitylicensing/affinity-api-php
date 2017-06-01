<?php

namespace Affinity;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Affinity
 *
 * @package Affinity
 */
class Affinity
{
    /**
     * The Affinity API key to be used for requests.
     * @var string
     */
    private $apiKey;

    /**
     * The base URL for the Affinity API.
     * @var string
     */
    private $apiBase = 'https://api.affinitygateway.com';

    const VERSION = '1.0.0';
    const USER_AGENT_SUFFIX = 'affinity-api-php-client/';

    /**
     * @var GuzzleClientInterface
     */
    protected $client;

    /**
     * Constructor
     *
     * @param string $apiKey The API key used for requests
     * @param string|null $apiBase Basde URL for Affinity API
     */
    public function __construct($apiKey, $apiBase = null)
    {
        $this->apiKey = $apiKey;

        if ($apiBase) {
            $this->apiBase = $apiBase;
        }

        $this->client = new GuzzleClient($this->getGuzzleClientOptions());
    }

    public function getGuzzleClientOptions()
    {
        return [
            'base_uri' => $this->apiBase,
            'headers' => [
                'User-Agent' => $this->getUserAgent(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Api-Key' => $this->apiKey,
            ],
            'http_errors' => false,
        ];
    }

    /**
     * @return string The API key used for requests.
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getUserAgent()
    {
        return self::USER_AGENT_SUFFIX . self::VERSION;
    }

    /**
     * @return string The API base URL to be used for requests
     */
    public function getApiBase()
    {
        return $this->apiBase;
    }

    /**
     * Sets Guzzle client used by the API.
     * @param GuzzleClientInterface $client
     */
    public function setGuzzleClient(GuzzleClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return GuzzleClientInterface Guzzle client used by the API
     */
    public function getGuzzleClient()
    {
        return $this->client;
    }
}
