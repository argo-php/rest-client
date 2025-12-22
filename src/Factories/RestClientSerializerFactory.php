<?php

namespace Argo\RestClient\Factories;

use Argo\RestClient\Serializer\RestClientSerializer;
use Argo\RestClient\Serializer\RestClientSerializerInterface;
use Argo\Serializer\JsonEncoder\JsonEncoder;
use Argo\Serializer\Normalizer\ArrayableNormalizer;
use Argo\Serializer\Normalizer\ArrayNormalizer;
use Argo\Serializer\Normalizer\BackedEnumNormalizer;
use Argo\Serializer\Normalizer\BuiltinDenormalizer;
use Argo\Serializer\Normalizer\CarbonNormalizer;
use Argo\Serializer\Normalizer\CustomNormalizer;
use Argo\Serializer\Normalizer\JsonSerializableNormalizer;
use Argo\Serializer\Normalizer\ObjectNormalizer;
use Argo\Serializer\Normalizer\UnionDenormalizer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @api
 */
final readonly class RestClientSerializerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RestClientSerializerInterface
    {
        return new RestClientSerializer(
            $this->getNormalizers($container),
            $this->getEncoders($container),
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getNormalizers(ContainerInterface $container): array
    {
        return [
            $container->get(UnionDenormalizer::class),
            $container->get(ArrayNormalizer::class),
            $container->get(CarbonNormalizer::class),
            $container->get(BackedEnumNormalizer::class),
            $container->get(CustomNormalizer::class),
            $container->get(ArrayableNormalizer::class),
            $container->get(JsonSerializableNormalizer::class),
            $container->get(ObjectNormalizer::class),
            $container->get(BuiltinDenormalizer::class),
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getEncoders(ContainerInterface $container): array
    {
        return [
            $container->get(JsonEncoder::class),
        ];
    }
}
