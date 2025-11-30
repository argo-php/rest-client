<?php

namespace Argo\RestClient;

use Argo\EntityDefinition\Definition\MethodDefinition;

final readonly class RestMethodDefinition
{
    public function __construct(
        public MethodDefinition $methodDefinition,
        public string $method,
        public string $path,
        public mixed $body = null,
        public array $parameters = [],
    ) {}
}
