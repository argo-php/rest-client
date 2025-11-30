<?php

namespace Argo\RestClient;

use Argo\Types\TypeInterface;

final readonly class RestMethodDefinition
{
    public function __construct(
        public string $method,
        public string $path,
        public TypeInterface $returnType,
        public mixed $body = null,
        public array $parameters = [],
    ) {}
}
