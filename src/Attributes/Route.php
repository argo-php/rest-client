<?php

namespace Argo\RestClient\Attributes;

abstract readonly class Route
{
    public function __construct(
        public string $method,
        public string $path,
        public ?string $body = null,
    ) {}
}
