<?php

namespace Argo\RestClient\Factories;

use Psr\Http\Message\RequestFactoryInterface;
use PsrDiscovery\Discover;
use PsrDiscovery\Exceptions\SupportPackageNotFoundException;

/**
 * @api
 */
final readonly class PsrRequestFactoryFactory
{
    /**
     * @throws SupportPackageNotFoundException
     */
    public function __invoke(): RequestFactoryInterface
    {
        return Discover::httpRequestFactory()
            ?? throw new SupportPackageNotFoundException(RequestFactoryInterface::class, '');
    }
}
