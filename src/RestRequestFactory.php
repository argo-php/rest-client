<?php

namespace Argo\RestClient;

use Argo\RestClient\Exception\BadRequestException;
use Argo\RestClient\Exception\ConflictException;
use Argo\RestClient\Exception\ForbiddenException;
use Argo\RestClient\Exception\InternalServerError;
use Argo\RestClient\Exception\NotFoundException;
use Argo\RestClient\Exception\NotImplementedException;
use Argo\RestClient\Exception\PaymentRequiredException;
use Argo\RestClient\Exception\RestException;
use Argo\RestClient\Exception\UnauthorizedException;
use Argo\RestClient\Serializer\RestClientSerializerInterface;
use Argo\Serializer\Exception\SerializerException;
use Argo\Serializer\JsonEncoder\JsonEncoder;
use Argo\Types\Atomic\ClassType;
use Argo\Types\TypeInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @api
 */
class RestRequestFactory implements RestRequestFactoryInterface
{
    public function __construct(
        protected readonly RequestFactoryInterface $requestFactory,
        protected readonly StreamFactoryInterface $streamFactory,
        protected readonly RestClientSerializerInterface $serializer,
        protected string $baseUrl = '',
    ) {}

    public function setUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function getVariables(): array
    {
        return [];
    }

    /**
     * @throws \InvalidArgumentException
     * @throws SerializerException
     */
    public function makeRequest(RestMethodDefinition $methodDefinition): RequestInterface
    {
        $request = $this->requestFactory
            ->createRequest($methodDefinition->method, $this->getUrl($this->baseUrl, $methodDefinition))
            ->withHeader('Content-Type', 'application/json');

        if ($methodDefinition->body !== null) {
            $dataStream = $this->streamFactory->createStream(
                $this->serializeBody($methodDefinition->body),
            );

            $request = $request->withBody($dataStream);
        }

        return $request;
    }

    /**
     * @throws SerializerException
     */
    protected function serializeBody(mixed $body): string
    {
        return $this->serializer->serialize($body, JsonEncoder::FORMAT);
    }

    /**
     * @throws RestException
     */
    public function parseResponse(RestMethodDefinition $methodDefinition, ResponseInterface $response): mixed
    {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            $this->parseError($response);
        }

        try {
            if (
                $methodDefinition->returnType instanceof ClassType
                && is_a($methodDefinition->returnType->className, StreamInterface::class, true)
            ) {
                return $response->getBody();
            }

            $body = $response->getBody()->getContents();
            if (empty($body)) {
                return true;
            }

            return $this->deserializeResponse($body, $methodDefinition->returnType);
        } catch (\Throwable $e) {
            throw new RestException($response, $e);
        }
    }

    /**
     * @throws SerializerException
     */
    protected function deserializeResponse(string $response, TypeInterface $type): mixed
    {
        return $this->serializer->deserialize($response, $type, JsonEncoder::FORMAT);
    }

    /**
     * @throws RestException
     */
    protected function parseError(ResponseInterface $response): void
    {
        throw match ($response->getStatusCode()) {
            400 => new BadRequestException($response),
            401 => new UnauthorizedException($response),
            402 => new PaymentRequiredException($response),
            403 => new ForbiddenException($response),
            404 => new NotFoundException($response),
            409 => new ConflictException($response),
            500 => new InternalServerError($response),
            501 => new NotImplementedException($response),
            default => new RestException($response),
        };
    }

    public function getUrl(string $url, RestMethodDefinition $methodDefinition): string
    {
        $url = sprintf('%s/%s', trim($url, '/'), trim($methodDefinition->path, '/'));

        if (!empty($methodDefinition->parameters)) {
            $url .= '?' . http_build_query($methodDefinition->parameters);
        }

        return $url;
    }
}
