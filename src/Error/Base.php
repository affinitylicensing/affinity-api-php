<?php

namespace Affinity\Error;

use Exception;

abstract class Base extends Exception
{
    public function __construct(
        $message,
        $httpStatus = null,
        $httpBody = null,
        $jsonBody = null,
        $httpHeaders = null
    ) {
        parent::__construct($message);
        $this->httpStatus = $httpStatus;
        $this->httpBody = $httpBody;
        $this->jsonBody = $jsonBody;
        $this->httpHeaders = $httpHeaders;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function getHttpBody()
    {
        return $this->httpBody;
    }

    public function getJsonBody()
    {
        return $this->jsonBody;
    }

    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    public function __toString()
    {
        $id = $this->requestId ? " from API request '{$this->requestId}'": "";
        $message = explode("\n", parent::__toString());
        $message[0] .= $id;
        return implode("\n", $message);
    }
}
