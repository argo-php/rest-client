<?php

namespace Argo\RestClient\Attributes;

/**
 * @api
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class Get extends Route
{
    public function __construct(string $path, ?string $body = null)
    {
        parent::__construct('GET', $path, $body);
    }
}
