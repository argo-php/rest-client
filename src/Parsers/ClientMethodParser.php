<?php

namespace Argo\RestClient\Parsers;

use Argo\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorInterface;
use Argo\RestClient\Attributes\Route;
use Argo\RestClient\RestMethodDefinition;

/**
 * @api
 */
final readonly class ClientMethodParser
{
    public function __construct(
        private MethodDefinitionReflectorInterface $methodDefinitionReflector,
    ) {}

    /**
     * @throws \ReflectionException
     */
    public function parseMethod(string $method, array $parameters): RestMethodDefinition
    {
        $httpMethod = 'GET';
        $path = '/';
        $body = null;
        $data = $parameters;

        $methodReflection = new \ReflectionMethod($method);
        $methodDefinition = $this->methodDefinitionReflector->getMethodDefinition($methodReflection);

        $routeAttribute = $methodDefinition->attributes->firstByType(Route::class);

        if ($routeAttribute !== null) {
            $httpMethod = $routeAttribute->method;
            $path = $routeAttribute->path;

            $resultParameters = $parameters;
            foreach ($parameters as $key => $value) {
                if (!is_string($value) && !is_int($value)) {
                    continue;
                }

                $path = str_replace('{' . $key . '}', (string) $value, $path, $count);
                if ($count > 0) {
                    unset($resultParameters[$key]);
                }
            }
            $parameters = $resultParameters;

            if ($httpMethod === 'PUT' || $httpMethod === 'POST' || $httpMethod === 'PATCH') {
                if ($routeAttribute->body !== null) {
                    $body = $parameters[$routeAttribute->body];
                    unset($parameters[$routeAttribute->body]);
                    $data = $parameters;
                } else {
                    $body = $parameters;
                    $data = [];
                }
            } else {
                $data = $parameters;
            }
        }

        return new RestMethodDefinition(
            $methodDefinition,
            $httpMethod,
            $path,
            $body,
            array_filter($data),
        );
    }
}
