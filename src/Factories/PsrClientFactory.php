<?php

namespace Argo\RestClient\Factories;

use Psr\Http\Client\ClientInterface;
use PsrDiscovery\Discover;
use PsrDiscovery\Exceptions\SupportPackageNotFoundException;

/**
 * @api
 */
final readonly class PsrClientFactory
{
    /**
     * @throws SupportPackageNotFoundException
     */
    public function __invoke(): ClientInterface
    {
        return Discover::httpClient()
            ?? throw new SupportPackageNotFoundException(ClientInterface::class, '');
    }
}
