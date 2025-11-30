<?php

namespace Argo\RestClient;

use Argo\RestClient\Parsers\ClientMethodParser;
use Argo\RestClient\Parsers\MethodParametersMapper;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

/**
 * @api
 */
readonly class RestClient implements RestClientInterface
{
    public function __construct(
        private RestRequestFactoryInterface $requestFactory,
        private ClientInterface $client,
        private ClientMethodParser $clientMethodParser,
        private MethodParametersMapper $parametersMapper,
    ) {}

    /**
     * @throws \ReflectionException
     * @throws ClientExceptionInterface
     */
    public function send(string $method, array $parameters): mixed
    {
        $data = array_merge(
            $this->requestFactory->getVariables(),
            $this->parametersMapper->getNamedArguments($method, $parameters),
        );

        $methodDefinition = $this->clientMethodParser->parseMethod($method, $data);
        $request = $this->requestFactory->makeRequest($methodDefinition);

        $response = $this->client->sendRequest($request);

        return $this->requestFactory->parseResponse($methodDefinition, $response);
    }
}
