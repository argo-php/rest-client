<?php

namespace Argo\RestClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @api
 */
interface RestRequestFactoryInterface
{
    public function setUrl(string $baseUrl): void;

    public function getVariables(): array;

    public function makeRequest(RestMethodDefinition $methodDefinition): RequestInterface;

    public function parseResponse(RestMethodDefinition $methodDefinition, ResponseInterface $response): mixed;
}
