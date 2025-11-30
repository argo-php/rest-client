<?php

namespace Argo\RestClient;

use Argo\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorInterface;
use Argo\RestClient\Exception\ClientException;
use Argo\RestClient\Exception\RestException;
use Argo\RestClient\Parsers\ClientMethodParser;
use Argo\RestClient\Parsers\MethodParametersMapper;
use Psr\Http\Client\ClientInterface;

/**
 * @api
 */
readonly class RestClient implements RestClientInterface
{
    private ClientMethodParser $clientMethodParser;
    private MethodParametersMapper $parametersMapper;

    public function __construct(
        private RestRequestFactoryInterface $requestFactory,
        private ClientInterface $client,
        MethodDefinitionReflectorInterface $methodDefinitionReflector,
    ) {
        $this->clientMethodParser = new ClientMethodParser($methodDefinitionReflector);
        $this->parametersMapper = new MethodParametersMapper();
    }

    /**
     * @throws ClientException
     * @throws RestException
     */
    public function send(string $method, array $parameters): mixed
    {
        try {
            $data = array_merge(
                $this->requestFactory->getVariables(),
                $this->parametersMapper->getNamedArguments($method, $parameters),
            );

            $methodDefinition = $this->clientMethodParser->parseMethod($method, $data);
            $request = $this->requestFactory->makeRequest($methodDefinition);

            $response = $this->client->sendRequest($request);

            return $this->requestFactory->parseResponse($methodDefinition, $response);
        } catch (RestException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ClientException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
