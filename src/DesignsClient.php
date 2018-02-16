<?php

namespace Affinity;

use Affinity\Error\Generic;
use Affinity\Error\RateLimit;
use Affinity\Error\ServerError;
use GuzzleHttp\Psr7\Response;

use Affinity\Error\InvalidRequest;

/**
 * Affinity Designs Client
 *
 * @package Affinity
 */
class DesignsClient extends Affinity
{

    /**
     * Number of results to return for a getList() request
     * @var int
     */
    protected $pageSize = 50;

    /**
     * Page ID to fetch for a getList() request
     * @var int
     */
    protected $page = 1;

    /**
     * Returns a list of designs
     * @return array
     * @throws \Affinity\Error\Base
     */
    public function getList()
    {
        $path = '/designs';
        $res = $this->client->request('GET', $path, [
            'query' => [
                'page' => $this->page,
                'page_size' => $this->pageSize,
            ]
        ]);

        if ($res->getStatusCode() !== 200) {
            $this->throwInvalidResponseException($res);
        }
        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * Uploads a new design
     * @param $designData
     * @return array Design
     */
    public function uploadDesign($designData)
    {
        $path = '/designs';
        $res = $this->client->request('POST', $path, ['json' => $designData]);

        if ($res->getStatusCode() !== 200) {
            $this->throwInvalidResponseException($res);
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * Returns the design with the specified ID
     * @param string|int $id
     * @return array Design
     */
    public function getDesign($id)
    {
        $path = '/designs/' . $id;
        $res = $this->client->request('GET', $path);

        if ($res->getStatusCode() !== 200) {
            $this->throwInvalidResponseException($res);
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * Deletes the design with the specified ID
     * @param $id
     * @return boolean
     */
    public function deleteDesign($id)
    {
        $path = '/designs/' . $id;
        $res = $this->client->request('DELETE', $path);

        if ($res->getStatusCode() !== 200) {
            $this->throwInvalidResponseException($res);
        }

        return true;
    }

    /**
     * Submits a new iteration of a design
     * @param $id
     * @return array Updated design
     */
    public function resubmitDesign($id, $resubmissionData)
    {
        $path = '/designs/' . $id . '/iterations';
        $res = $this->client->request('POST', $path, ['json' => $resubmissionData]);

        if ($res->getStatusCode() !== 200) {
            $this->throwInvalidResponseException($res);
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * Returns a list of product categories
     *
     * Supports passing additional filter params, i.e., "'is_available' => 1"
     * @see http://apidocs.affinitygateway.com/#operation/listCategories
     * @param array $params key/value pairs of additional params
     * @return array Product categories listing
     */
    public function getCategories(array $params = [])
    {
        $path = '/product_categories';
        $res = $this->client->request('GET', $path, ['query' => $params]);

        if ($res->getStatusCode() !== 200) {
            $this->throwInvalidResponseException($res);
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    public function getOrganizations()
    {
        $path = '/clients';
        $res = $this->client->request('GET', $path);

        if ($res->getStatusCode() !== 200) {
            $this->throwInvalidResponseException($res);
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * Sets the page number for the getList() request
     * Sets the page number to request
     * @param int $i Page number
     * @throws \InvalidArgumentException Thrown when page size is out of bounds
     */
    public function setPage($i)
    {
        if (!is_int($i)) {
            throw new \InvalidArgumentException('Page number must be an integer value');
        }
        if ($i < 1) {
            throw new \InvalidArgumentException('Page number must be greater than or equal to 1');
        }
        $this->page = $i;
    }

    /**
     * Sets the number of records in a getList() response
     * @param int $i Page size
     * @throws \InvalidArgumentException Thrown when page size is out of bounds
     */
    public function setPageSize($i)
    {
        $minPageSize = 1;
        $maxPageSize = 100;
        if (!is_int($i)) {
            throw new \InvalidArgumentException('Page size must be an integer value');
        }
        if ($i < $minPageSize) {
            throw new \InvalidArgumentException('Page size must be greater than or equal to ' . $minPageSize);
        }
        if ($i > $maxPageSize) {
            throw new \InvalidArgumentException('Page size must be less than or equal to ' . $maxPageSize);
        }

        $this->pageSize = $i;
    }

    /**
     * @param Response $res
     * @throws InvalidRequest
     * @throws \Affinity\Error\Base
     */
    protected function throwInvalidResponseException(Response $res)
    {
        $responseCode = $res->getStatusCode();

        if ($responseCode === 400) {
            throw new InvalidRequest($res->getReasonPhrase());
        } else if ($responseCode === 429) {
            throw new RateLimit($res->getReasonPhrase());
        } else if ($responseCode >= 500) {
            throw new ServerError($res->getReasonPhrase());
        } else {
            throw new Generic($res->getReasonPhrase());
        }
    }
}
