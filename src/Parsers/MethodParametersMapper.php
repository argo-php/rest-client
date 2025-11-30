<?php

namespace Argo\RestClient\Parsers;

/**
 * @api
 */
final readonly class MethodParametersMapper
{
    /**
     * @throws \ReflectionException
     */
    public function getNamedArguments(string $method, array $arguments): array
    {
        $methodReflection = new \ReflectionMethod($method);

        $data = [];
        foreach ($methodReflection->getParameters() as $parameter) {
            if (array_key_exists($parameter->getPosition(), $arguments)) {
                $data[$parameter->getName()] = $arguments[$parameter->getPosition()];
            } elseif ($parameter->isDefaultValueAvailable()) {
                $data[$parameter->getName()] = $parameter->getDefaultValue();
            } else {
                throw new MissingArgumentException(
                    sprintf(
                        'Can not get arguments in method [%s]: missing argument [%s]',
                        $method,
                        $parameter->getName(),
                    ),
                );
            }
        }

        return $data;
    }
}
