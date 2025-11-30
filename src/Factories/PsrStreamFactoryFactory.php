<?php

namespace Argo\RestClient\Factories;

use Psr\Http\Message\StreamFactoryInterface;
use PsrDiscovery\Discover;
use PsrDiscovery\Exceptions\SupportPackageNotFoundException;

/**
 * @api
 */
final readonly class PsrStreamFactoryFactory
{
    /**
     * @throws SupportPackageNotFoundException
     */
    public function __invoke(): StreamFactoryInterface
    {
        return Discover::httpStreamFactory()
            ?? throw new SupportPackageNotFoundException(StreamFactoryInterface::class, '');
    }
}
