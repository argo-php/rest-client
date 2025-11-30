<?php

namespace Argo\RestClient\Factories;

use Psr\Http\Message\ResponseFactoryInterface;
use PsrDiscovery\Discover;
use PsrDiscovery\Exceptions\SupportPackageNotFoundException;

/**
 * @api
 */
final readonly class PsrResponseFactoryFactory
{
    /**
     * @throws SupportPackageNotFoundException
     */
    public function __invoke(): ResponseFactoryInterface
    {
        return Discover::httpResponseFactory()
            ?? throw new SupportPackageNotFoundException(ResponseFactoryInterface::class, '');
    }
}
