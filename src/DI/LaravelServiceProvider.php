<?php

namespace Argo\RestClient\DI;

use Argo\RestClient\Factories\PsrClientFactory;
use Argo\RestClient\Factories\PsrRequestFactoryFactory;
use Argo\RestClient\Factories\PsrResponseFactoryFactory;
use Argo\RestClient\Factories\PsrStreamFactoryFactory;
use Argo\RestClient\Factories\RestClientSerializerFactory;
use Argo\RestClient\RestClient;
use Argo\RestClient\RestClientInterface;
use Argo\RestClient\Serializer\RestClientSerializerInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * @api
 */
final class LaravelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, (new PsrClientFactory())(...));
        $this->app->bind(RequestFactoryInterface::class, (new PsrRequestFactoryFactory())(...));
        $this->app->bind(ResponseFactoryInterface::class, (new PsrResponseFactoryFactory())(...));
        $this->app->bind(StreamFactoryInterface::class, (new PsrStreamFactoryFactory())(...));
        $this->app->bind(RestClientSerializerInterface::class, (new RestClientSerializerFactory())(...));
        $this->app->bind(RestClientInterface::class, RestClient::class);
    }
}
