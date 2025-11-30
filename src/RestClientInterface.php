<?php

namespace Argo\RestClient;

/**
 * @api
 */
interface RestClientInterface
{
    /**
     * @template TObject of object
     * @template TType of string|class-string<TObject>
     * @return (TType is class-string<TObject> ? TObject : mixed)
     */
    public function send(string $method, array $parameters): mixed;
}
