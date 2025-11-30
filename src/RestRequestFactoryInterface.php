<?php

namespace Argo\RestClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RestRequestFactoryInterface
{
    public function getVariables(): array;

    public function makeRequest(RestMethodDefinition $methodDefinition): RequestInterface;

    public function parseResponse(RestMethodDefinition $methodDefinition, ResponseInterface $response): mixed;

}
